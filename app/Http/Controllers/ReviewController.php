<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Almacena una nueva reseña
     */
    public function store(Request $request, $productId)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $product = Product::findOrFail($productId);

        // Verificar que el usuario haya comprado el producto
        $hasPurchased = Auth::user()->orders()
                            ->whereHas('products', function($query) use ($productId) {
                                $query->where('product_id', $productId);
                            })
                            ->exists();

        if (!$hasPurchased) {
            return redirect()->back()->with('error', 'Debes comprar el producto antes de poder reseñarlo');
        }

        // Verificar que no haya reseñado antes
        $existingReview = Review::where('product_id', $productId)
                                ->where('user_id', Auth::id())
                                ->first();

        if ($existingReview) {
            return redirect()->back()->with('error', 'Ya has reseñado este producto');
        }

        Review::create([
            'product_id' => $productId,
            'user_id' => Auth::id(),
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        return redirect()->back()->with('success', '¡Reseña publicada exitosamente!');
    }

    /**
     * Elimina una reseña
     */
    public function destroy($id)
    {
        $review = Review::findOrFail($id);

        // Solo el autor o admin pueden eliminar
        if (Auth::id() !== $review->user_id && Auth::user()->role !== 'admin') {
            abort(403, 'No autorizado para eliminar esta reseña');
        }

        $review->delete();

        return redirect()->back()->with('success', 'Reseña eliminada exitosamente');
    }
}
