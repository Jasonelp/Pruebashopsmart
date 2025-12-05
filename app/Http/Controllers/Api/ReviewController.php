<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Crea una nueva reseña para un producto
     */
    public function store(Request $request, int $productId): JsonResponse
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $product = Product::findOrFail($productId);

        // Verificar que el usuario haya comprado el producto
        $hasPurchased = $request->user()->orders()
            ->whereHas('products', function ($q) use ($productId) {
                $q->where('product_id', $productId);
            })
            ->exists();

        if (!$hasPurchased) {
            return response()->json([
                'success' => false,
                'message' => 'Solo puedes reseñar productos que hayas comprado',
            ], 403);
        }

        // Verificar que no haya reseñado antes
        $existingReview = Review::where('product_id', $productId)
            ->where('user_id', $request->user()->id)
            ->first();

        if ($existingReview) {
            return response()->json([
                'success' => false,
                'message' => 'Ya has reseñado este producto',
            ], 422);
        }

        $review = Review::create([
            'product_id' => $productId,
            'user_id' => $request->user()->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Reseña creada exitosamente',
            'data' => new ReviewResource($review->load('user')),
        ], 201);
    }

    /**
     * Actualiza una reseña existente
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'rating' => 'sometimes|required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review = Review::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $review->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Reseña actualizada exitosamente',
            'data' => new ReviewResource($review->load('user')),
        ]);
    }

    /**
     * Elimina una reseña
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $review = Review::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'Reseña eliminada exitosamente',
        ]);
    }
}
