@extends('layouts.public')

@section('title', 'Mi Cuenta - ShopSmart IA')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-green-900 via-teal-800 to-blue-900 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-4xl font-bold text-white mb-2">Mi Cuenta</h1>
                    <p class="text-gray-300">Bienvenido, {{ Auth::user()->name }}</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('home') }}"
                        class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-lg transition">
                        Ir a la Tienda
                    </a>
                    <a href="{{ route('cart.index') }}"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Mi Carrito
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
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 shadow-lg">
                    <p class="text-blue-100 text-sm">Total de Pedidos</p>
                    <p class="text-4xl font-bold text-white">{{ $stats['total_orders'] }}</p>
                </div>
                <div class="bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl p-6 shadow-lg">
                    <p class="text-yellow-100 text-sm">Pedidos Pendientes</p>
                    <p class="text-4xl font-bold text-white">{{ $stats['pending_orders'] }}</p>
                </div>
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 shadow-lg">
                    <p class="text-green-100 text-sm">Pedidos Completados</p>
                    <p class="text-4xl font-bold text-white">{{ $stats['completed_orders'] }}</p>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <a href="{{ route('products.public.index') }}"
                    class="bg-white/10 backdrop-blur rounded-xl p-6 border border-white/20 hover:bg-white/20 transition text-center">
                    <svg class="w-12 h-12 text-green-400 mx-auto mb-3" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <h3 class="text-white font-bold text-lg">Explorar Productos</h3>
                    <p class="text-gray-400 text-sm">Descubre nuevos productos</p>
                </a>
                <a href="{{ route('orders.my-orders') }}"
                    class="bg-white/10 backdrop-blur rounded-xl p-6 border border-white/20 hover:bg-white/20 transition text-center">
                    <svg class="w-12 h-12 text-purple-400 mx-auto mb-3" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <h3 class="text-white font-bold text-lg">Mis Pedidos</h3>
                    <p class="text-gray-400 text-sm">Ver historial completo</p>
                </a>
                <a href="{{ route('profile.edit') }}"
                    class="bg-white/10 backdrop-blur rounded-xl p-6 border border-white/20 hover:bg-white/20 transition text-center">
                    <svg class="w-12 h-12 text-blue-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <h3 class="text-white font-bold text-lg">Mi Perfil</h3>
                    <p class="text-gray-400 text-sm">Editar mis datos</p>
                </a>
            </div>

            <!-- Mis Pedidos Recientes -->
            <div class="bg-white/10 backdrop-blur rounded-xl p-6 border border-white/20">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-white">Mis Pedidos Recientes</h3>
                    <a href="{{ route('orders.my-orders') }}" class="text-green-400 hover:text-green-300 text-sm">Ver todos
                        →</a>
                </div>

                @if($orders->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-gray-300 text-left border-b border-white/10">
                                    <th class="pb-3">N° Orden</th>
                                    <th class="pb-3">Total</th>
                                    <th class="pb-3">Estado</th>
                                    <th class="pb-3">Fecha</th>
                                    <th class="pb-3">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr class="border-b border-white/5 text-gray-200">
                                        <td class="py-3 font-mono">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</td>
                                        <td class="py-3 font-bold text-green-400">S/ {{ number_format($order->total, 2) }}</td>
                                        <td class="py-3">
                                            <span class="px-2 py-1 rounded text-xs font-medium
                                                @if($order->status === 'pendiente') bg-yellow-500/20 text-yellow-300
                                                @elseif($order->status === 'confirmado') bg-blue-500/20 text-blue-300
                                                @elseif($order->status === 'enviado') bg-purple-500/20 text-purple-300
                                                @elseif($order->status === 'entregado') bg-green-500/20 text-green-300
                                                @else bg-red-500/20 text-red-300 @endif">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="py-3 text-gray-400">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="py-3">
                                            <a href="{{ route('checkout.success', $order->id) }}"
                                                class="text-blue-400 hover:text-blue-300 text-sm">
                                                Ver comprobante →
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <p class="text-gray-400 mb-4">Aún no tienes pedidos</p>
                        <a href="{{ route('products.public.index') }}"
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition">
                            Explorar Productos
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection