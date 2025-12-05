@extends('layouts.public')

@section('title', 'Categorías - ShopSmart IA')

@section('content')
<div class="min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Encabezado -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-white mb-4">Explora por Categorías</h1>
            <p class="text-gray-300 text-lg">Encuentra productos organizados por categoría</p>
        </div>

        @if($categories->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($categories as $category)
                    <a href="{{ route('categories.public.show', $category->id) }}" 
                       class="bg-gray-800 rounded-lg p-6 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 text-center group">
                        <!-- Icono de categoría -->
                        <div class="bg-green-600 w-20 h-20 rounded-full mx-auto mb-4 flex items-center justify-center group-hover:bg-green-700 transition">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </div>
                        
                        <h3 class="text-xl font-bold text-white mb-2 group-hover:text-green-400 transition">
                            {{ $category->name }}
                        </h3>
                        
                        @if($category->description)
                            <p class="text-gray-400 text-sm mb-4">{{ Str::limit($category->description, 60) }}</p>
                        @endif
                        
                        <div class="inline-flex items-center px-3 py-1 rounded-full bg-gray-700 text-gray-300 text-sm">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                            </svg>
                            {{ $category->products_count ?? 0 }} productos
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-16 bg-gray-800 rounded-lg">
                <svg class="mx-auto h-24 w-24 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
                <h3 class="mt-4 text-xl font-medium text-gray-300">No hay categorías disponibles</h3>
                <p class="mt-2 text-gray-400">Agrega categorías desde el panel de administración</p>
            </div>
        @endif
    </div>
</div>
@endsection
