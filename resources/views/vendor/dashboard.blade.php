@extends('layouts.public')

@section('title', 'Panel de Vendedor - ShopSmart IA')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-green-900 via-teal-800 to-blue-900 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-4xl font-bold text-white mb-2">Panel de Vendedor</h1>
                    <p class="text-gray-300">Bienvenido, {{ Auth::user()->name }}</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('home') }}"
                        class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-lg transition">
                        Ver Tienda
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="bg-red-500/80 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition">
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 shadow-lg">
                    <p class="text-blue-100 text-sm">Mis Productos</p>
                    <p class="text-4xl font-bold text-white">{{ $stats['my_products'] }}</p>
                </div>
                <div class="bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl p-6 shadow-lg">
                    <p class="text-yellow-100 text-sm">Pedidos Pendientes</p>
                    <p class="text-4xl font-bold text-white">{{ $stats['pending_orders'] }}</p>
                </div>
            </div>

            <!-- Accesos Rápidos -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <a href="{{ route('vendor.products') }}"
                    class="bg-white/10 backdrop-blur rounded-xl p-6 border border-white/20 hover:bg-white/20 transition group">
                    <div class="flex items-center">
                        <div class="bg-green-500 p-4 rounded-xl mr-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white group-hover:text-green-400 transition">Mis Productos
                            </h3>
                            <p class="text-gray-400">Gestiona tu inventario</p>
                        </div>
                    </div>
                </a>
                <a href="{{ route('vendor.orders') }}"
                    class="bg-white/10 backdrop-blur rounded-xl p-6 border border-white/20 hover:bg-white/20 transition group">
                    <div class="flex items-center">
                        <div class="bg-purple-500 p-4 rounded-xl mr-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white group-hover:text-purple-400 transition">Mis Pedidos</h3>
                            <p class="text-gray-400">Ver pedidos recibidos</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Mis Productos Recientes -->
            <div class="bg-white/10 backdrop-blur rounded-xl p-6 border border-white/20 mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-white">Mis Productos</h3>
                    <a href="{{ route('vendor.products') }}" class="text-green-400 hover:text-green-300 text-sm">Ver todos
                        →</a>
                </div>

                @if($my_products->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($my_products->take(6) as $product)
                            <div class="bg-white/5 rounded-lg p-4 border border-white/10">
                                <h4 class="text-white font-medium mb-2">{{ Str::limit($product->name, 30) }}</h4>
                                <p class="text-green-400 font-bold">S/ {{ number_format($product->price, 2) }}</p>
                                <p class="text-gray-400 text-sm">Stock: {{ $product->stock }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-400 mb-4">Aún no tienes productos</p>
                        <a href="{{ route('vendor.products') }}"
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition">
                            Agregar mi primer producto
                        </a>
                    </div>
                @endif
            </div>

            <!-- Pedidos Recientes -->
            <div class="bg-white/10 backdrop-blur rounded-xl p-6 border border-white/20">
                <h3 class="text-xl font-bold text-white mb-4">Pedidos Recientes</h3>
                @if($my_orders->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-gray-300 text-left border-b border-white/10">
                                    <th class="pb-3">N° Orden</th>
                                    <th class="pb-3">Cliente</th>
                                    <th class="pb-3">Estado</th>
                                    <th class="pb-3">Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($my_orders as $order)
                                    <tr class="border-b border-white/5 text-gray-200">
                                        <td class="py-3">#{{ $order->id }}</td>
                                        <td class="py-3">{{ $order->user->name ?? 'N/A' }}</td>
                                        <td class="py-3">
                                            <span class="px-2 py-1 rounded text-xs
                                                @if($order->status === 'pendiente') bg-yellow-500/20 text-yellow-300
                                                @elseif($order->status === 'entregado') bg-green-500/20 text-green-300
                                                @else bg-blue-500/20 text-blue-300 @endif">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="py-3 text-gray-400">{{ $order->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-400 text-center py-4">No tienes pedidos aún</p>
                @endif
            </div>

        </div>
    </div>
@endsection