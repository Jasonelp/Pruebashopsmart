<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Http;

class AiService
{
    private string $apiKey;
    private string $model;
    private string $baseUrl = 'https://openrouter.ai/api/v1';

    public function __construct()
    {
        $this->apiKey = config('services.openrouter.api_key', env('OPENROUTER_API_KEY', ''));
        $this->model = config('services.openrouter.model', env('AI_MODEL', 'openai/gpt-3.5-turbo'));
    }

    public function chat(array $messages, bool $includeProducts = true): string
    {
        $systemPrompt = $this->buildSystemPrompt($includeProducts);

        $formattedMessages = [
            ['role' => 'system', 'content' => $systemPrompt],
        ];

        foreach ($messages as $message) {
            $formattedMessages[] = [
                'role' => $message['role'],
                'content' => $message['content'],
            ];
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
            'HTTP-Referer' => config('app.url', 'http://localhost'),
            'X-Title' => 'ShopSmart IA',
        ])->post($this->baseUrl . '/chat/completions', [
            'model' => $this->model,
            'messages' => $formattedMessages,
            'max_tokens' => 1000,
            'temperature' => 0.7,
        ]);

        if ($response->failed()) {
            throw new \Exception('Error al comunicarse con OpenRouter: ' . $response->body());
        }

        $data = $response->json();

        return $data['choices'][0]['message']['content'] ?? 'No se pudo generar una respuesta.';
    }

    private function buildSystemPrompt(bool $includeProducts): string
    {
        $prompt = "Eres un asistente de compras inteligente para ShopSmart IA, una tienda en línea. "
            . "Tu objetivo es ayudar a los usuarios a encontrar productos, responder preguntas sobre la tienda, "
            . "y proporcionar recomendaciones personalizadas. "
            . "Sé amable, conciso y útil. Responde siempre en español.";

        if ($includeProducts) {
            $products = $this->getAvailableProducts();
            if ($products->isNotEmpty()) {
                $prompt .= "\n\nProductos disponibles en la tienda:\n";
                foreach ($products as $product) {
                    $prompt .= sprintf(
                        "- %s (ID: %d): %s - Precio: S/ %.2f - Stock: %d unidades - Categoría: %s\n",
                        $product->name,
                        $product->id,
                        substr($product->description ?? '', 0, 100),
                        $product->price,
                        $product->stock,
                        $product->category->name ?? 'Sin categoría'
                    );
                }
                $prompt .= "\nCuando recomiendes productos, usa la información anterior para dar recomendaciones precisas.";
            }
        }

        return $prompt;
    }

    private function getAvailableProducts()
    {
        return Product::with('category')
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->select('id', 'name', 'description', 'price', 'stock', 'category_id')
            ->limit(50)
            ->get();
    }
}
