@extends('layouts.app-simple')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-900 via-teal-800 to-blue-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-white">Gestión de Productos</h1>
                <p class="text-gray-300">Administra todos los productos del marketplace</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.dashboard') }}" class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-lg transition">
                    ← Dashboard
                </a>
                <a href="{{ route('products.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nuevo Producto
                </a>
            </div>
        </div>

        @if(session('success'))
        <div class="bg-green-500/20 border border-green-500 text-green-300 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
        @endif

        <!-- Tabla de Productos -->
        <div class="bg-white/10 backdrop-blur rounded-xl border border-white/20 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-white/5">
                        <tr class="text-gray-300 text-left">
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">Producto</th>
                            <th class="px-6 py-4">Categoría</th>
                            <th class="px-6 py-4">Precio</th>
                            <th class="px-6 py-4">Stock</th>
                            <th class="px-6 py-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr class="border-b border-white/5 text-gray-200 hover:bg-white/5">
                            <td class="px-6 py-4">{{ $product->id }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-gray-700 rounded-lg mr-3 flex items-center justify-center overflow-hidden">
                                        @if($product->image)
                                            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-medium">{{ $product->name }}</p>
                                        <p class="text-gray-400 text-sm">{{ Str::limit($product->description, 30) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-blue-500/20 text-blue-300 px-2 py-1 rounded text-sm">
                                    {{ $product->category->name ?? 'Sin categoría' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-bold text-green-400">S/ {{ number_format($product->price, 2) }}</td>
                            <td class="px-6 py-4">
                                <span class="@if($product->stock > 10) text-green-400 @elseif($product->stock > 0) text-yellow-400 @else text-red-400 @endif">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('products.show', $product->id) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded text-sm">
                                        Ver
                                    </a>
                                    <a href="{{ route('products.edit', $product->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                                        Editar
                                    </a>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este producto?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                <p class="text-lg mb-2">No hay productos registrados</p>
                                <a href="{{ route('products.create') }}" class="text-green-400 hover:text-green-300">Crear el primer producto →</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection