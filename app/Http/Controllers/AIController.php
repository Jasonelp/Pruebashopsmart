<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use OpenAI;

class AIController extends Controller
{
    private $client;

    public function __construct()
    {
        // Conectar al cliente OpenAI usando la API KEY del .env
        $this->client = OpenAI::client(env('OPENAI_API_KEY'));
    }

    /**
     * 1️⃣ CHAT GENERAL - IA GLOBAL
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $text = $request->message;

        $response = $this->client->chat()->create([
            'model' => 'gpt-4o-mini', // Puedes usar gpt-4o para más poder
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Eres el asistente oficial de ShopSmart-IA, experto en comercio electrónico y atención al cliente.'
                ],
                [
                    'role' => 'user',
                    'content' => $text
                ]
            ]
        ]);

        return response()->json([
            'reply' => $response->choices[0]->message->content
        ]);
    }

    /**
     * 2️⃣ ANÁLISIS PROFESIONAL DE PRODUCTO
     */
    public function productAnalysis($id)
    {
        $product = Product::with('category')->findOrFail($id);

        $prompt = "
        Necesito que analices este producto con un enfoque comercial,
        explicando sus ventajas, público objetivo y puntos clave de venta.

        Nombre: {$product->name}
        Precio: {$product->price}
        Categoría: {$product->category->name}
        Descripción: {$product->description}
        Stock: {$product->stock}
        ";

        $response = $this->client->chat()->create([
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'system', 'content' => 'Eres una IA experta en marketing y ventas.'],
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        return response()->json([
            'reply' => $response->choices[0]->message->content,
            'product' => $product
        ]);
    }

    /**
     * 3️⃣ VISIÓN IA — DESCRIPCIÓN DE IMÁGENES (OPENAI OFICIAL)
     */
    public function vision(Request $request)
    {
        $request->validate([
            'image_url' => 'required|string',
        ]);

        // Aquí se usa GPT-4o o GPT-4o-mini con input_image (API nueva)
        $response = $this->client->chat()->create([
            'model' => 'gpt-4o-mini',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'input_text',
                            'text' => 'Analiza esta imagen y describe el producto, su categoría y posibles usos.'
                        ],
                        [
                            'type' => 'input_image',
                            'image_url' => $request->image_url
                        ]
                    ]
                ]
            ]
        ]);

        return response()->json([
            'reply' => $response->choices[0]->message->content
        ]);
    }
}
