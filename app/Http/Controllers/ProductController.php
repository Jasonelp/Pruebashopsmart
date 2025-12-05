<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    // =======================================================
    // ADMIN CRUD
    // =======================================================

    public function index()
    {
        $products = Product::with('category')
            ->latest()
            ->get();

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateProduct($request);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($validated);

        return redirect()
            ->route('products.index')
            ->with('success', 'Producto creado exitosamente.');
    }

    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return view('admin.products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $validated = $this->validateProduct($request);

        $product = Product::findOrFail($id);

        if ($request->hasFile('image')) {

            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()
            ->route('products.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->orders()->count() > 0) {
            return redirect()->route('products.index')
                ->with('error', 'No se puede eliminar: producto está en órdenes.');
        }

        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Producto eliminado correctamente.');
    }

    // =======================================================
    // MÉTODOS PÚBLICOS (CATÁLOGO)
    // =======================================================

    public function publicIndex(Request $request)
    {
        $query = Product::with(['category', 'reviews'])
            ->where('stock', '>', 0)
            ->withCount([
                'orders as total_sold' => function ($query) {
                    $query->select(\DB::raw('COALESCE(SUM(order_product.quantity), 0)'));
                }
            ])
            ->withAvg('reviews', 'rating');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Por defecto ordenar por más vendidos
        $sortBy = $request->get('sort', 'best_sellers');
        $sortOrder = $request->get('order', 'desc');

        if ($sortBy === 'best_sellers') {
            $query->orderBy('total_sold', 'desc');
        } elseif (in_array($sortBy, ['name', 'price', 'created_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('products.public-index', compact('products', 'categories'));
    }

    public function publicShow($id)
    {
        $product = Product::with('category')->findOrFail($id);

        $reviews = collect();
        $reviewsCount = 0;
        $averageRating = 0;
        $canReview = false;

        if (Schema::hasTable('reviews')) {
            $reviews = $product->reviews()->with('user')->latest()->get();
            $reviewsCount = $reviews->count();
            $averageRating = $reviewsCount > 0 ? round($reviews->avg('rating'), 1) : 0;

            if (auth()->check()) {
                $canReview = auth()->user()->orders()
                    ->whereHas('products', function ($q) use ($product) {
                        $q->where('product_id', $product->id);
                    })
                    ->exists()
                    &&
                    !$product->reviews()->where('user_id', auth()->id())->exists();
            }
        }

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('stock', '>', 0)
            ->take(4)
            ->get();

        return view('products.public-show', compact(
            'product',
            'reviews',
            'reviewsCount',
            'averageRating',
            'canReview',
            'relatedProducts'
        ));
    }

    private function validateProduct(Request $request)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0.01',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'price.required' => 'El precio es obligatorio.',
            'stock.required' => 'El stock es obligatorio.',
            'category_id.required' => 'Debes seleccionar una categoría.',
        ]);
    }
}
