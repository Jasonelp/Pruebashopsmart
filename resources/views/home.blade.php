@extends('layouts.public')

@section('title', 'ShopSmart IA - Tu Marketplace Inteligente')

@section('content')

<section class="bg-gradient-to-r from-green-900 via-teal-800 to-blue-900 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-5xl md:text-6xl font-bold text-white mb-6">
            Bienvenido a <span class="text-green-400">ShopSmart IA</span>
        </h1>
        <p class="text-xl text-gray-200 mb-8 max-w-3xl mx-auto">
            Descubre productos increíbles con la ayuda de nuestra asistente de IA.
            Encuentra exactamente lo que buscas de manera rápida y sencilla.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('products.public.index') }}" 
               class="bg-green-600 hover:bg-green-700 text-white font-bold px-8 py-4 rounded-lg transition transform hover:scale-105 shadow-lg text-lg">
                Explorar Productos
            </a>
            <a href="{{ route('categories.public.index') }}" 
               class="bg-white/10 backdrop-blur-sm hover:bg-white/20 text-white font-bold px-8 py-4 rounded-lg transition transform hover:scale-105 shadow-lg text-lg border border-white/30">
                Ver Categorías
            </a>
        </div>
    </div>
</section>

<section class="py-16 bg-gray-900/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-4xl font-bold text-white mb-8 text-center">Categorías</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @forelse($categories as $category)
                <a href="{{ route('categories.public.show', $category->id) }}"
                   class="bg-gray-800 hover:bg-gray-700 rounded-lg p-6 text-center transition transform hover:scale-105 shadow-lg group">
                    <div class="text-4xl mb-3 group-hover:scale-110 transition">
                        @switch($category->name)
                            @case('Smartphones')
                                📱
                                @break
                            @case('Computadoras')
                                💻
                                @break
                            @case('Cámaras')
                                📷
                                @break
                            @case('Relojes')
                                ⌚
                                @break
                            @case('Auriculares')
                                🎧
                                @break
                            @case('Tablets')
                                📱
                                @break
                            @default
                                🏷️
                        @endswitch
                    </div>
                    <h3 class="text-white font-semibold group-hover:text-green-400 transition">{{ $category->name }}</h3>
                    <p class="text-gray-400 text-sm mt-1">{{ $category->products_count }} productos</p>
                </a>
            @empty
                <div class="col-span-full text-center bg-gray-800 rounded-lg p-8">
                    <p class="text-gray-400 text-lg">No hay categorías disponibles</p>
                    <p class="text-gray-500 text-sm mt-2">Agrega categorías desde el panel de administración</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<section class="py-16 bg-transparent">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-4xl font-bold text-white">Productos Destacados</h2>
            <a href="{{ route('products.public.index') }}" class="text-green-400 hover:text-green-300 font-semibold text-lg">
                Ver todos →
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($featuredProducts as $product)
                <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="h-48 bg-gray-700 flex items-center justify-center overflow-hidden">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="text-gray-500 text-center">
                                <svg class="h-20 w-20 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                <p class="text-sm mt-2">Sin imagen</p>
                            </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <span class="text-xs text-green-400 font-semibold uppercase tracking-wide">
                            {{ $product->category->name ?? 'Sin categoría' }}
                        </span>
                        <h3 class="text-lg font-bold text-white mt-2 mb-2 line-clamp-2">{{ $product->name }}</h3>
                        @if($product->description)
                            <p class="text-gray-400 text-sm mb-4 line-clamp-2">{{ Str::limit($product->description, 60) }}</p>
                        @endif
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-2xl font-bold text-green-400">S/ {{ number_format($product->price, 2) }}</span>
                            @if($product->stock > 0)
                                <span class="text-xs text-green-400">Stock: {{ $product->stock }}</span>
                            @else
                                <span class="text-xs text-red-400">Agotado</span>
                            @endif
                        </div>
                        <a href="{{ route('products.public.show', $product->id) }}"
                           class="block w-full bg-green-600 hover:bg-green-700 text-white text-center px-4 py-2 rounded-lg font-semibold transition">
                            Ver Detalles
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center bg-gray-800 rounded-lg p-12">
                    <svg class="mx-auto h-24 w-24 text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <p class="text-xl text-gray-300 mb-2">No hay productos disponibles</p>
                    <p class="text-sm text-gray-500">Agrega productos desde el panel de administración</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<section class="bg-gradient-to-r from-green-600 to-teal-600 py-16 mt-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl font-bold text-white mb-4">¿Listo para empezar a comprar?</h2>
        <p class="text-xl text-green-100 mb-8">
            Únete a miles de usuarios que confían en ShopSmart IA para sus compras
        </p>
        @guest
            <a href="{{ route('register') }}"
               class="bg-white text-green-600 hover:bg-gray-100 font-bold px-8 py-4 rounded-lg transition transform hover:scale-105 inline-block shadow-lg text-lg">
                Crear cuenta gratis
            </a>
        @else
            <a href="{{ route('products.public.index') }}"
               class="bg-white text-green-600 hover:bg-gray-100 font-bold px-8 py-4 rounded-lg transition transform hover:scale-105 inline-block shadow-lg text-lg">
                Explorar productos
            </a>
        @endguest
    </div>
</section>

@endsection
