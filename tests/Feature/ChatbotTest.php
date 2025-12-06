<?php

use App\Models\User;
use App\Models\Conversation;
use App\Models\ChatbotMessage;
use App\Services\AiService;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('requires authentication for chat endpoint', function () {
    $response = $this->postJson('/api/v1/chat', [
        'message' => 'Hola',
    ]);

    $response->assertStatus(401);
});

it('validates message is required', function () {
    $response = $this->actingAs($this->user, 'sanctum')
        ->postJson('/api/v1/chat', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['message']);
});

it('validates message max length', function () {
    $response = $this->actingAs($this->user, 'sanctum')
        ->postJson('/api/v1/chat', [
            'message' => str_repeat('a', 2001),
        ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['message']);
});

it('creates a new conversation when none provided', function () {
    $this->mock(AiService::class, function ($mock) {
        $mock->shouldReceive('chat')
            ->once()
            ->andReturn('Hola, soy tu asistente de compras.');
    });

    $response = $this->actingAs($this->user, 'sanctum')
        ->postJson('/api/v1/chat', [
            'message' => 'Hola',
        ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'data' => [
                'conversation_id',
                'message',
            ],
        ]);

    $this->assertDatabaseHas('conversations', [
        'user_id' => $this->user->id,
    ]);
});

it('continues existing conversation', function () {
    $conversation = Conversation::create([
        'user_id' => $this->user->id,
        'title' => 'Test conversation',
    ]);

    ChatbotMessage::create([
        'conversation_id' => $conversation->id,
        'role' => 'user',
        'content' => 'Mensaje anterior',
    ]);

    $this->mock(AiService::class, function ($mock) {
        $mock->shouldReceive('chat')
            ->once()
            ->andReturn('Continuando la conversación...');
    });

    $response = $this->actingAs($this->user, 'sanctum')
        ->postJson('/api/v1/chat', [
            'message' => 'Continuar',
            'conversation_id' => $conversation->id,
        ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'data' => [
                'conversation_id' => $conversation->id,
            ],
        ]);

    $this->assertDatabaseCount('chatbot_messages', 3);
});

it('rejects conversation from another user', function () {
    $otherUser = User::factory()->create();
    $conversation = Conversation::create([
        'user_id' => $otherUser->id,
        'title' => 'Other user conversation',
    ]);

    $response = $this->actingAs($this->user, 'sanctum')
        ->postJson('/api/v1/chat', [
            'message' => 'Hola',
            'conversation_id' => $conversation->id,
        ]);

    $response->assertStatus(404);
});

it('stores user and assistant messages', function () {
    $this->mock(AiService::class, function ($mock) {
        $mock->shouldReceive('chat')
            ->once()
            ->andReturn('Respuesta del asistente');
    });

    $response = $this->actingAs($this->user, 'sanctum')
        ->postJson('/api/v1/chat', [
            'message' => 'Mi mensaje',
        ]);

    $response->assertStatus(200);

    $conversationId = $response->json('data.conversation_id');

    $this->assertDatabaseHas('chatbot_messages', [
        'conversation_id' => $conversationId,
        'role' => 'user',
        'content' => 'Mi mensaje',
    ]);

    $this->assertDatabaseHas('chatbot_messages', [
        'conversation_id' => $conversationId,
        'role' => 'assistant',
        'content' => 'Respuesta del asistente',
    ]);
});

it('can get conversation history', function () {
    $conversation = Conversation::create([
        'user_id' => $this->user->id,
        'title' => 'Test',
    ]);

    ChatbotMessage::create([
        'conversation_id' => $conversation->id,
        'role' => 'user',
        'content' => 'Hola',
    ]);

    ChatbotMessage::create([
        'conversation_id' => $conversation->id,
        'role' => 'assistant',
        'content' => 'Hola, ¿cómo puedo ayudarte?',
    ]);

    $response = $this->actingAs($this->user, 'sanctum')
        ->getJson("/api/v1/chat/conversations/{$conversation->id}");

    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'data' => [
                'conversation' => ['id', 'title', 'created_at'],
                'messages',
            ],
        ])
        ->assertJsonCount(2, 'data.messages');
});

it('can list user conversations', function () {
    Conversation::create([
        'user_id' => $this->user->id,
        'title' => 'Conversation 1',
    ]);

    Conversation::create([
        'user_id' => $this->user->id,
        'title' => 'Conversation 2',
    ]);

    $otherUser = User::factory()->create();
    Conversation::create([
        'user_id' => $otherUser->id,
        'title' => 'Other user conversation',
    ]);

    $response = $this->actingAs($this->user, 'sanctum')
        ->getJson('/api/v1/chat/conversations');

    $response->assertStatus(200)
        ->assertJsonCount(2, 'data');
});

it('cannot access other user conversation history', function () {
    $otherUser = User::factory()->create();
    $conversation = Conversation::create([
        'user_id' => $otherUser->id,
        'title' => 'Other user conversation',
    ]);

    $response = $this->actingAs($this->user, 'sanctum')
        ->getJson("/api/v1/chat/conversations/{$conversation->id}");

    $response->assertStatus(404);
});

it('handles AI service errors gracefully', function () {
    $this->mock(AiService::class, function ($mock) {
        $mock->shouldReceive('chat')
            ->once()
            ->andThrow(new \Exception('API Error'));
    });

    $response = $this->actingAs($this->user, 'sanctum')
        ->postJson('/api/v1/chat', [
            'message' => 'Hola',
        ]);

    $response->assertStatus(500)
        ->assertJson([
            'success' => false,
        ]);
});
