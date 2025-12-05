@extends('layouts.app-simple')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-900 via-teal-800 to-blue-900 py-8">
    <div class="max-w-2xl mx-auto px-4">

        <div class="mb-8">
            <a href="{{ route('products.index') }}" class="text-gray-300 hover:text-white mb-4 inline-flex items-center">
                ← Volver a productos
            </a>
            <h1 class="text-3xl font-bold text-white">Detalle del Producto</h1>
        </div>

        <div class="bg-white/10 backdrop-blur rounded-xl p-6 border border-white/20">
            <div class="mb-6">
                @if($product->image)
                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-48 object-cover rounded-lg">
                @else
                    <div class="w-full h-48 bg-gray-700 rounded-lg flex items-center justify-center">
                        <span class="text-gray-400">Sin imagen</span>
                    </div>
                @endif
            </div>

            <h2 class="text-2xl font-bold text-white mb-2">{{ $product->name }}</h2>
            
            <p class="text-gray-300 mb-4">{{ $product->description ?? 'Sin descripción' }}</p>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-white/5 rounded-lg p-4">
                    <p class="text-gray-400 text-sm">Precio</p>
                    <p class="text-2xl font-bold text-green-400">S/ {{ number_format($product->price, 2) }}</p>
                </div>
                <div class="bg-white/5 rounded-lg p-4">
                    <p class="text-gray-400 text-sm">Stock</p>
                    <p class="text-2xl font-bold text-white">{{ $product->stock }}</p>
                </div>
            </div>

            <div class="bg-white/5 rounded-lg p-4 mb-6">
                <p class="text-gray-400 text-sm">Categoría</p>
                <p class="text-white font-medium">{{ $product->category->name ?? 'Sin categoría' }}</p>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('products.edit', $product->id) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg text-center transition">
                    Editar
                </a>
                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="flex-1" onsubmit="return confirm('¿Eliminar?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg transition">
                        Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection