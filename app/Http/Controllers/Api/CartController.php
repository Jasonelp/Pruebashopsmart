<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Obtiene el carrito actual del usuario
     */
    public function index(Request $request): JsonResponse
    {
        $cart = session()->get('cart', []);
        $formattedCart = $this->formatCart($cart);

        return response()->json([
            'success' => true,
            'data' => $formattedCart,
        ]);
    }

    /**
     * Agrega un producto al carrito
     */
    public function add(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'quantity' => 'nullable|integer|min:1',
        ]);

        $product = Product::active()->findOrFail($id);

        if (!$product->isInStock()) {
            return response()->json([
                'success' => false,
                'message' => 'Producto sin stock',
            ], 422);
        }

        $cart = session()->get('cart', []);
        $quantity = $validated['quantity'] ?? 1;

        if (isset($cart[$id])) {
            $newQuantity = $cart[$id]['quantity'] + $quantity;

            if ($newQuantity > $product->stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stock insuficiente',
                ], 422);
            }

            $cart[$id]['quantity'] = $newQuantity;
        } else {
            if ($quantity > $product->stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stock insuficiente',
                ], 422);
            }

            $cart[$id] = [
                'name' => $product->name,
                'quantity' => $quantity,
                'price' => $product->price,
                'image' => $product->image,
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Producto agregado al carrito',
            'data' => $this->formatCart($cart),
        ]);
    }

    /**
     * Actualiza la cantidad de un producto en el carrito
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);

        if (!isset($cart[$id])) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado en el carrito',
            ], 404);
        }

        $product = Product::findOrFail($id);

        if ($validated['quantity'] > $product->stock) {
            return response()->json([
                'success' => false,
                'message' => 'Stock insuficiente',
            ], 422);
        }

        $cart[$id]['quantity'] = $validated['quantity'];
        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Cantidad actualizada',
            'data' => $this->formatCart($cart),
        ]);
    }

    /**
     * Elimina un producto del carrito
     */
    public function remove(int $id): JsonResponse
    {
        $cart = session()->get('cart', []);

        if (!isset($cart[$id])) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado en el carrito',
            ], 404);
        }

        unset($cart[$id]);
        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Producto eliminado del carrito',
            'data' => $this->formatCart($cart),
        ]);
    }

    /**
     * Vacía completamente el carrito
     */
    public function clear(): JsonResponse
    {
        session()->forget('cart');

        return response()->json([
            'success' => true,
            'message' => 'Carrito vaciado',
            'data' => [
                'items' => [],
                'total' => 0,
                'items_count' => 0,
            ],
        ]);
    }

    /**
     * Formatea el carrito para la respuesta
     */
    private function formatCart(array $cart): array
    {
        $items = [];
        $total = 0;

        foreach ($cart as $id => $item) {
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;

            $items[] = [
                'product_id' => $id,
                'name' => $item['name'],
                'price' => (float) $item['price'],
                'quantity' => $item['quantity'],
                'image' => $item['image'] ? asset('storage/' . $item['image']) : null,
                'subtotal' => $subtotal,
            ];
        }

        return [
            'items' => $items,
            'total' => $total,
            'items_count' => array_sum(array_column($cart, 'quantity')),
        ];
    }
}
