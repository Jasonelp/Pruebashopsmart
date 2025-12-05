@extends('layouts.app-simple')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-900 via-teal-800 to-blue-900 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-4xl font-bold text-white mb-2">Panel de Administración</h1>
                <p class="text-gray-300">Gestiona tu tienda desde aquí</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('home') }}" class="bg-white/10 backdrop-blur-sm hover:bg-white/20 text-white px-6 py-3 rounded-lg font-medium transition flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Ver Sitio Público
                </a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-500/80 hover:bg-red-600 text-white px-6 py-3 rounded-lg font-medium transition">
                        Cerrar Sesión
                    </button>
                </form>
            </div>
        </div>

        <!-- Cards de Estadísticas Principales -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            
            <!-- Card Productos -->
            <a href="{{ route('products.index') }}" class="group">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 shadow-2xl hover:shadow-blue-500/50 transition-all duration-300 transform hover:-translate-y-2">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-white/20 backdrop-blur-sm p-4 rounded-xl">
                            <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-blue-100 text-sm font-medium mb-1">Total Productos</p>
                    <p class="text-5xl font-bold text-white mb-4">{{ \App\Models\Product::count() }}</p>
                    <div class="flex items-center text-white font-semibold group-hover:translate-x-2 transition-transform">
                        Gestionar Productos
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </div>
            </a>

            <!-- Card Categorías -->
            <a href="{{ route('categories.index') }}" class="group">
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 shadow-2xl hover:shadow-green-500/50 transition-all duration-300 transform hover:-translate-y-2">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-white/20 backdrop-blur-sm p-4 rounded-xl">
                            <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-green-100 text-sm font-medium mb-1">Total Categorías</p>
                    <p class="text-5xl font-bold text-white mb-4">{{ \App\Models\Category::count() }}</p>
                    <div class="flex items-center text-white font-semibold group-hover:translate-x-2 transition-transform">
                        Gestionar Categorías
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </div>
            </a>

            <!-- Card Pedidos -->
            <a href="{{ route('orders.index') }}" class="group">
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 shadow-2xl hover:shadow-purple-500/50 transition-all duration-300 transform hover:-translate-y-2">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-white/20 backdrop-blur-sm p-4 rounded-xl">
                            <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-purple-100 text-sm font-medium mb-1">Total Pedidos</p>
                    <p class="text-5xl font-bold text-white mb-4">{{ \App\Models\Order::count() }}</p>
                    <div class="flex items-center text-white font-semibold group-hover:translate-x-2 transition-transform">
                        Gestionar Pedidos
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </div>
            </a>
        </div>

        <!-- Sección de Accesos y Estadísticas -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Accesos Rápidos -->
            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 shadow-xl border border-white/20">
                <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Accesos Rápidos
                </h3>
                <div class="space-y-3">
                    <a href="{{ route('products.create') }}" class="block bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl p-4 transition group">
                        <div class="flex items-center text-white">
                            <div class="bg-blue-500 p-2 rounded-lg mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <span class="font-medium group-hover:translate-x-1 transition-transform">Agregar Producto</span>
                        </div>
                    </a>
                    <a href="{{ route('categories.create') }}" class="block bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl p-4 transition group">
                        <div class="flex items-center text-white">
                            <div class="bg-green-500 p-2 rounded-lg mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <span class="font-medium group-hover:translate-x-1 transition-transform">Agregar Categoría</span>
                        </div>
                    </a>
                    <a href="{{ route('orders.index') }}" class="block bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl p-4 transition group">
                        <div class="flex items-center text-white">
                            <div class="bg-purple-500 p-2 rounded-lg mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <span class="font-medium group-hover:translate-x-1 transition-transform">Ver Pedidos Pendientes</span>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 shadow-xl border border-white/20">
                <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Estadísticas
                </h3>
                <div class="space-y-4">
                    <div class="bg-white/5 border border-white/10 rounded-xl p-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300 font-medium">Productos Activos</span>
                            <span class="text-3xl font-bold text-white">{{ \App\Models\Product::count() }}</span>
                        </div>
                    </div>
                    <div class="bg-white/5 border border-white/10 rounded-xl p-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300 font-medium">Pedidos del Mes</span>
                            <span class="text-3xl font-bold text-white">{{ \App\Models\Order::whereMonth('created_at', date('m'))->count() }}</span>
                        </div>
                    </div>
                    <div class="bg-white/5 border border-white/10 rounded-xl p-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300 font-medium">Categorías</span>
                            <span class="text-3xl font-bold text-white">{{ \App\Models\Category::count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info adicional -->
        <div class="mt-8 text-center text-white/60 text-sm">
            <p>ShopSmart IA - Panel de Administración © {{ date('Y') }}</p>
        </div>

    </div>
</div>
@endsection
