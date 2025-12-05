<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    /**
     * Lista todas las categorías
     */
    public function index(): JsonResponse
    {
        $categories = Cache::remember('categories_all', 3600, function () {
            return Category::query()
                ->withCount('products')
                ->orderBy('name')
                ->get();
        });

        return response()->json([
            'success' => true,
            'data' => CategoryResource::collection($categories),
        ]);
    }

    /**
     * Muestra una categoría específica
     */
    public function show(int $id): JsonResponse
    {
        $category = Category::query()
            ->withCount('products')
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => new CategoryResource($category),
        ]);
    }

    /**
     * Muestra los productos de una categoría
     */
    public function products(int $id): JsonResponse
    {
        $category = Category::findOrFail($id);

        $products = $category->products()
            ->active()
            ->where('stock', '>', 0)
            ->with(['reviews'])
            ->withAvg('reviews', 'rating')
            ->paginate(12);

        return response()->json([
            'success' => true,
            'data' => ProductResource::collection($products),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ],
        ]);
    }
}
