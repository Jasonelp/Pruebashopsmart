@extends('layouts.public')

@section('title', $category->name . ' - ShopSmart IA')

@section('content')
<div class="min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb y título -->
        <div class="mb-8">
            <nav class="text-gray-400 mb-4 flex items-center space-x-2">
                <a href="{{ route('home') }}" class="hover:text-white transition">Inicio</a>
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
                <a href="{{ route('categories.public.index') }}" class="hover:text-white transition">Categorías</a>
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
                <span class="text-white font-semibold">{{ $category->name }}</span>
            </nav>
            
            <h1 class="text-4xl font-bold text-white mb-2">{{ $category->name }}</h1>
            @if($category->description)
                <p class="text-gray-300 text-lg">{{ $category->description }}</p>
            @endif
        </div>

        @if($products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($products as $product)
                    <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 flex flex-col">
                        <!-- Imagen del producto -->
                        <div class="h-48 bg-gray-700 flex items-center justify-center overflow-hidden">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="text-gray-500 text-center">
                                    <svg class="h-20 w-20 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-sm mt-2">Sin imagen</p>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Contenido del card -->
                        <div class="p-4 flex-1 flex flex-col">
                            <h3 class="text-lg font-bold text-white mb-2 line-clamp-2">{{ $product->name }}</h3>
                            
                            @if($product->description)
                                <p class="text-gray-400 text-sm mb-4 line-clamp-2 flex-grow">{{ Str::limit($product->description, 80) }}</p>
                            @endif
                            
                            <!-- Precio y stock -->
                            <div class="mt-auto">
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-2xl font-bold text-green-400">S/. {{ number_format($product->price, 2) }}</span>
                                    @if($product->stock > 0)
                                        <span class="text-sm text-green-400 font-medium">Stock: {{ $product->stock }}</span>
                                    @else
                                        <span class="text-sm text-red-400 font-medium">Agotado</span>
                                    @endif
                                </div>
                                
                                <!-- Botón -->
                                <a href="{{ route('products.public.show', $product->id) }}" 
                                   class="block w-full text-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold transition duration-200">
                                    Ver Detalles
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Paginación -->
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center py-16 bg-gray-800 rounded-lg">
                <svg class="mx-auto h-24 w-24 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <h3 class="mt-4 text-xl font-medium text-gray-300">No hay productos en esta categoría</h3>
                <p class="mt-2 text-gray-400">Vuelve pronto para ver nuevos productos</p>
                <a href="{{ route('categories.public.index') }}" class="inline-block mt-4 text-green-400 hover:text-green-300 font-semibold">
                    ← Ver todas las categorías
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
