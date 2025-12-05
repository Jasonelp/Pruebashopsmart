@extends('layouts.app-simple')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-900 via-teal-800 to-blue-900 py-8">
    <div class="max-w-2xl mx-auto px-4">

        <div class="mb-8">
            <a href="{{ route('products.index') }}" class="text-gray-300 hover:text-white mb-4 inline-flex items-center">
                ← Volver a productos
            </a>
            <h1 class="text-3xl font-bold text-white">Editar Producto</h1>
        </div>

        <div class="bg-white/10 backdrop-blur rounded-xl p-6 border border-white/20">
            <form action="{{ route('products.update', $product->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label class="block text-gray-300 mb-2">Nombre *</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                           class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 text-white focus:border-green-500 focus:outline-none">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-300 mb-2">Descripción</label>
                    <textarea name="description" rows="3"
                              class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 text-white focus:border-green-500 focus:outline-none">{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-300 mb-2">Precio (S/) *</label>
                        <input type="number" name="price" step="0.01" min="0" value="{{ old('price', $product->price) }}" required
                               class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 text-white focus:border-green-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-gray-300 mb-2">Stock *</label>
                        <input type="number" name="stock" min="0" value="{{ old('stock', $product->stock) }}" required
                               class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 text-white focus:border-green-500 focus:outline-none">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-300 mb-2">Categoría *</label>
                    <select name="category_id" required
                            class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 text-white focus:border-green-500 focus:outline-none">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 mb-2">URL de Imagen</label>
                    <input type="url" name="image" value="{{ old('image', $product->image) }}"
                           class="w-full bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 text-white focus:border-green-500 focus:outline-none">
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('products.index') }}" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white py-2 rounded-lg text-center transition">
                        Cancelar
                    </a>
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg transition">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection