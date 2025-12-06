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
        $this->model = config('services.openrouter.model', env('AI_MODEL', 'meta-llama/llama-3.2-3b-instruct:free'));    }

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

        $response = Http::withoutVerifying()->withHeaders([
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
            \Log::error('OpenRouter API Error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
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
                $prompt .= "\n\n📦 Productos disponibles en la tienda:\n";
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

    public function analyzeProduct(Product $product): string
    {
        $productInfo = sprintf(
            "Producto: %s\nDescripción: %s\nPrecio: S/ %.2f\nStock: %d unidades\nCategoría: %s",
            $product->name,
            $product->description ?? 'Sin descripción',
            $product->price,
            $product->stock,
            $product->category->name ?? 'Sin categoría'
        );

        $systemPrompt = "Eres un experto en análisis de productos para ShopSmart IA. "
            . "Genera un análisis detallado y útil del producto que incluya: "
            . "1) Puntos destacados del producto, "
            . "2) Para quién es ideal este producto, "
            . "3) Relación calidad-precio, "
            . "4) Recomendaciones de uso. "
            . "Sé conciso pero informativo. Responde siempre en español.";

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user', 'content' => "Analiza este producto:\n\n" . $productInfo],
        ];

        $response = Http::withoutVerifying()->withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
            'HTTP-Referer' => config('app.url', 'http://localhost'),
            'X-Title' => 'ShopSmart IA',
        ])->post($this->baseUrl . '/chat/completions', [
            'model' => $this->model,
            'messages' => $messages,
            'max_tokens' => 800,
            'temperature' => 0.7,
        ]);

        if ($response->failed()) {
            \Log::error('OpenRouter API Error - Product Analysis', [
                'product_id' => $product->id,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \Exception('Error al analizar el producto: ' . $response->body());
        }

        $data = $response->json();

        return $data['choices'][0]['message']['content'] ?? 'No se pudo generar el análisis.';
    }
}

