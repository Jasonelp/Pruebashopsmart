<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenAI;

class AIController extends Controller
{
    private $client;

    public function __construct()
    {
        $this->client = OpenAI::client(config('services.openai.key'));
    }

    /**
     * Chat general con IA
     */
    public function chat(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        try {
            $response = $this->client->chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Eres el asistente oficial de ShopSmart-IA, experto en comercio electrónico y atención al cliente. Responde de manera concisa y útil.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $validated['message']
                    ]
                ],
                'max_tokens' => 500,
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'reply' => $response->choices[0]->message->content,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la solicitud de IA',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Análisis de producto con IA
     */
    public function productAnalysis(int $id): JsonResponse
    {
        $product = Product::with('category')->findOrFail($id);

        $prompt = "Analiza este producto con un enfoque comercial, explicando sus ventajas, público objetivo y puntos clave de venta.

Nombre: {$product->name}
Precio: S/ {$product->price}
Categoría: {$product->category->name}
Descripción: {$product->description}
Stock disponible: {$product->stock}

Proporciona un análisis breve y profesional.";

        try {
            $response = $this->client->chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => 'Eres un experto en marketing y ventas de e-commerce.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'max_tokens' => 500,
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'analysis' => $response->choices[0]->message->content,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al analizar el producto',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Análisis de imagen con IA (Vision)
     */
    public function vision(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'image_url' => 'required|url|max:500',
        ]);

        try {
            $response = $this->client->chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => [
                            [
                                'type' => 'text',
                                'text' => 'Analiza esta imagen y describe el producto, su categoría y posibles usos.'
                            ],
                            [
                                'type' => 'image_url',
                                'image_url' => [
                                    'url' => $validated['image_url']
                                ]
                            ]
                        ]
                    ]
                ],
                'max_tokens' => 500,
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'analysis' => $response->choices[0]->message->content,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al analizar la imagen',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
