<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    /**
     * Lista pública de productos con filtros y paginación
     */
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'search' => 'nullable|string|max:255',
            'category' => 'nullable|integer|exists:categories,id',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0',
            'sort' => 'nullable|in:best_sellers,name,price,created_at',
            'order' => 'nullable|in:asc,desc',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        // Cache key basada en parámetros de búsqueda
        $cacheKey = 'products_' . md5(json_encode($validated));

        $products = Cache::remember($cacheKey, 300, function () use ($request, $validated) {
            $query = Product::query()
                ->with(['category', 'reviews'])
                ->active()
                ->where('stock', '>', 0)
                ->withCount([
                    'orders as total_sold' => function ($query) {
                        $query->selectRaw('COALESCE(SUM(order_product.quantity), 0)');
                    }
                ])
                ->withAvg('reviews', 'rating');

            // Filtros
            if (!empty($validated['search'])) {
                $query->where(function ($q) use ($validated) {
                    $q->where('name', 'like', "%{$validated['search']}%")
                        ->orWhere('description', 'like', "%{$validated['search']}%");
                });
            }

            if (!empty($validated['category'])) {
                $query->where('category_id', $validated['category']);
            }

            if (isset($validated['min_price'])) {
                $query->where('price', '>=', $validated['min_price']);
            }

            if (isset($validated['max_price'])) {
                $query->where('price', '<=', $validated['max_price']);
            }

            // Ordenamiento
            $sortBy = $validated['sort'] ?? 'best_sellers';
            $sortOrder = $validated['order'] ?? 'desc';

            if ($sortBy === 'best_sellers') {
                $query->orderBy('total_sold', 'desc');
            } elseif (in_array($sortBy, ['name', 'price', 'created_at'])) {
                $query->orderBy($sortBy, $sortOrder);
            }

            return $query->paginate($validated['per_page'] ?? 12);
        });

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

    /**
     * Muestra un producto público específico
     */
    public function show(int $id): JsonResponse
    {
        $product = Product::query()
            ->with(['category', 'reviews.user', 'user'])
            ->active()
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => new ProductResource($product),
        ]);
    }

    /**
     * Lista de productos del vendedor autenticado
     */
    public function vendorIndex(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $products = Product::query()
            ->with('category')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate($validated['per_page'] ?? 15);

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

    /**
     * Muestra un producto específico del vendedor
     */
    public function vendorShow(Request $request, int $id): JsonResponse
    {
        $product = Product::query()
            ->with(['category', 'reviews'])
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => new ProductResource($product),
        ]);
    }

    /**
     * Crea un nuevo producto (vendedor/admin)
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0.01',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'specifications' => 'nullable|array',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $validated['user_id'] = $request->user()->id;
        $validated['is_active'] = true;

        $product = Product::create($validated);

        // Limpiar cache de productos
        Cache::tags(['products'])->flush();

        return response()->json([
            'success' => true,
            'message' => 'Producto creado exitosamente',
            'data' => new ProductResource($product->load('category')),
        ], 201);
    }

    /**
     * Actualiza un producto existente
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $product = Product::query()
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0.01',
            'stock' => 'sometimes|required|integer|min:0',
            'category_id' => 'sometimes|required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'specifications' => 'nullable|array',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image && \Storage::disk('public')->exists($product->image)) {
                \Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        // Limpiar cache de productos
        Cache::tags(['products'])->flush();

        return response()->json([
            'success' => true,
            'message' => 'Producto actualizado exitosamente',
            'data' => new ProductResource($product->load('category')),
        ]);
    }

    /**
     * Elimina un producto (solo si no tiene órdenes)
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $product = Product::query()
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        if ($product->orders()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar el producto porque tiene órdenes asociadas',
            ], 422);
        }

        if ($product->image && \Storage::disk('public')->exists($product->image)) {
            \Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        // Limpiar cache de productos
        Cache::tags(['products'])->flush();

        return response()->json([
            'success' => true,
            'message' => 'Producto eliminado exitosamente',
        ]);
    }
}
