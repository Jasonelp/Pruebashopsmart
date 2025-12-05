@extends('layouts.public')

@section('title', 'Productos - ShopSmart IA')

@section('content')
    <div class="min-h-screen py-12 bg-gradient-to-br from-green-900 via-teal-800 to-blue-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Encabezado -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-white mb-4">Descubre Nuestros Productos</h1>
                <p class="text-gray-300">Encontrarás todo lo que necesitas para tu hogar y tu vida diaria.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

                <!-- SIDEBAR DE FILTROS -->
                <div class="lg:col-span-1">
                    <div class="bg-gray-800 rounded-lg p-6 shadow-lg sticky top-20">
                        <h3 class="text-xl font-bold text-white mb-6 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filtros
                        </h3>

                        <form method="GET" action="{{ route('products.public.index') }}">
                            <!-- Filtro de búsqueda -->
                            <div class="mb-6">
                                <label class="block text-gray-300 font-semibold mb-2">Buscar</label>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Nombre del producto..."
                                    class="w-full bg-gray-700 text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                            </div>

                            <!-- Filtro de categoría -->
                            <div class="mb-6">
                                <label class="block text-gray-300 font-semibold mb-2">Categoría</label>
                                <select name="category"
                                    class="w-full bg-gray-700 text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                                    <option value="">Todas las categorías</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filtro de precio -->
                            <div class="mb-6">
                                <label class="block text-gray-300 font-semibold mb-2">Rango de Precio</label>
                                <div class="flex gap-2">
                                    <input type="number" name="min_price" value="{{ request('min_price') }}"
                                        placeholder="Min"
                                        class="w-1/2 bg-gray-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                                    <input type="number" name="max_price" value="{{ request('max_price') }}"
                                        placeholder="Max"
                                        class="w-1/2 bg-gray-700 text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                                </div>
                            </div>

                            <!-- Botones -->
                            <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition duration-200 mb-2">
                                Aplicar Filtros
                            </button>
                            <a href="{{ route('products.public.index') }}"
                                class="block w-full text-center bg-gray-700 hover:bg-gray-600 text-white font-semibold py-3 rounded-lg transition duration-200">
                                Limpiar Filtros
                            </a>
                        </form>
                    </div>
                </div>

                <!-- GRID DE PRODUCTOS -->
                <div class="lg:col-span-3">
                    @if($products->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($products as $product)
                                <div
                                    class="bg-gray-800 rounded-lg overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 flex flex-col relative">
                                    
                                    <!-- Badge Más Vendido -->
                                    @if($product->total_sold > 5)
                                        <div class="absolute top-2 left-2 z-10">
                                            <span class="bg-gradient-to-r from-orange-500 to-red-500 text-white text-xs font-bold px-2 py-1 rounded-full flex items-center">
                                                🔥 Más Vendido
                                            </span>
                                        </div>
                                    @endif

                                    <!-- Imagen del producto -->
                                    <div class="h-48 bg-gray-700 flex items-center justify-center overflow-hidden">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <div class="text-gray-500 text-center">
                                                <svg class="h-20 w-20 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <p class="text-sm mt-2">Sin imagen</p>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Contenido del card -->
                                    <div class="p-4 flex-1 flex flex-col">
                                        <span class="inline-block text-xs text-blue-400 font-semibold uppercase tracking-wide mb-2">
                                            {{ $product->category->name ?? 'Sin categoría' }}
                                        </span>
                                        <h3 class="text-lg font-bold text-white mb-2 line-clamp-2">{{ $product->name }}</h3>

                                        <!-- Estrellas y reseñas -->
                                        <div class="flex items-center mb-2">
                                            @php
                                                $avgRating = $product->reviews_avg_rating ?? 0;
                                                $reviewCount = $product->reviews->count();
                                            @endphp
                                            <div class="flex items-center">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 {{ $i <= round($avgRating) ? 'text-yellow-400' : 'text-gray-600' }}" 
                                                         fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                @endfor
                                            </div>
                                            <span class="ml-2 text-sm text-gray-400">
                                                @if($reviewCount > 0)
                                                    {{ number_format($avgRating, 1) }} ({{ $reviewCount }})
                                                @else
                                                    Sin reseñas
                                                @endif
                                            </span>
                                        </div>

                                        @if($product->description)
                                            <p class="text-gray-400 text-sm mb-4 line-clamp-2 flex-grow">
                                                {{ Str::limit($product->description, 80) }}</p>
                                        @endif

                                        <!-- Precio y stock -->
                                        <div class="mt-auto">
                                            <div class="flex justify-between items-center mb-3">
                                                <span class="text-2xl font-bold text-blue-400">S/.
                                                    {{ number_format($product->price, 2) }}</span>
                                                @if($product->stock > 0)
                                                    <span class="text-sm text-green-400 font-medium">
                                                        <svg class="inline w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        Stock: {{ $product->stock }}
                                                    </span>
                                                @else
                                                    <span class="text-sm text-red-400 font-medium">Agotado</span>
                                                @endif
                                            </div>

                                            <!-- Botón -->
                                            <a href="{{ route('products.public.show', $product->id) }}"
                                                class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition duration-200">
                                                Ver Detalles </a>
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
                        <!-- Mensaje cuando no hay productos -->
                        <div class="text-center py-16 bg-gray-800 rounded-lg">
                            <svg class="mx-auto h-24 w-24 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="mt-4 text-xl font-medium text-gray-300">No se encontraron productos</h3>
                            <p class="mt-2 text-gray-400">Intenta ajustar tus filtros o buscar algo diferente</p>
                            <a href="{{ route('products.public.index') }}"
                                class="inline-block mt-4 text-blue-400 hover:text-blue-300 font-semibold">
                                Ver todos los productos →
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection