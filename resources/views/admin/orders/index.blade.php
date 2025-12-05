@extends('layouts.app-simple')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-900 via-teal-800 to-blue-900 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-4xl font-bold text-white mb-2">Gestión de Pedidos</h1>
                <p class="text-gray-300">Administra los pedidos de la tienda</p>
            </div>
            <a href="{{ route('dashboard') }}" class="bg-white/10 backdrop-blur-sm hover:bg-white/20 text-white px-6 py-3 rounded-lg font-medium transition flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Volver al Dashboard
            </a>
        </div>

        <!-- Mensajes de éxito/error -->
        @if(session('success'))
            <div class="mb-6 bg-green-500/20 border border-green-500/50 text-green-100 px-6 py-4 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-500/20 border border-red-500/50 text-red-100 px-6 py-4 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Estadísticas Rápidas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white/10 backdrop-blur-md rounded-xl p-4 border border-white/20">
                <p class="text-gray-300 text-sm">Total Pedidos</p>
                <p class="text-3xl font-bold text-white">{{ $orders->total() }}</p>
            </div>
            <div class="bg-blue-500/20 backdrop-blur-md rounded-xl p-4 border border-blue-400/30">
                <p class="text-blue-100 text-sm">Pendientes</p>
                <p class="text-3xl font-bold text-white">{{ $orders->where('status', 'pendiente')->count() }}</p>
            </div>
            <div class="bg-green-500/20 backdrop-blur-md rounded-xl p-4 border border-green-400/30">
                <p class="text-green-100 text-sm">Enviados</p>
                <p class="text-3xl font-bold text-white">{{ $orders->where('status', 'enviado')->count() }}</p>
            </div>
            <div class="bg-purple-500/20 backdrop-blur-md rounded-xl p-4 border border-purple-400/30">
                <p class="text-purple-100 text-sm">Entregados</p>
                <p class="text-3xl font-bold text-white">{{ $orders->where('status', 'entregado')->count() }}</p>
            </div>
        </div>

        <!-- Tabla de Pedidos -->
        <div class="bg-white/10 backdrop-blur-md rounded-2xl shadow-2xl border border-white/20 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-white/5 border-b border-white/10">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Cliente</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Productos</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-300 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        @forelse($orders as $order)
                            <tr class="hover:bg-white/5 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-white font-mono">#{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-white font-medium">{{ $order->user->name ?? 'N/A' }}</div>
                                    <div class="text-gray-400 text-sm">{{ $order->user->email ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-gray-300 text-sm">
                                        {{ $order->products->count() }} producto(s)
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-green-400 font-bold text-lg">S/ {{ number_format($order->total, 2) }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'pendiente' => 'bg-yellow-500/20 text-yellow-300 border-yellow-400/30',
                                            'confirmado' => 'bg-blue-500/20 text-blue-300 border-blue-400/30',
                                            'enviado' => 'bg-purple-500/20 text-purple-300 border-purple-400/30',
                                            'entregado' => 'bg-green-500/20 text-green-300 border-green-400/30',
                                            'cancelado' => 'bg-red-500/20 text-red-300 border-red-400/30',
                                        ];
                                        $colorClass = $statusColors[$order->status] ?? 'bg-gray-500/20 text-gray-300';
                                    @endphp
                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full border {{ $colorClass }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-300 text-sm">
                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('orders.show', $order->id) }}" 
                                           class="bg-blue-500/20 hover:bg-blue-500/30 text-blue-300 px-3 py-2 rounded-lg text-sm font-medium transition border border-blue-400/30">
                                            Ver
                                        </a>
                                        @if($order->status === 'pendiente')
                                            <form method="POST" action="{{ route('orders.update', $order->id) }}" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="confirmado">
                                                <button type="submit" 
                                                        class="bg-green-500/20 hover:bg-green-500/30 text-green-300 px-3 py-2 rounded-lg text-sm font-medium transition border border-green-400/30">
                                                    Confirmar
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="text-gray-400">
                                        <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        <p class="text-lg font-medium">No hay pedidos registrados</p>
                                        <p class="text-sm mt-1">Los pedidos aparecerán aquí cuando los clientes realicen compras</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            @if($orders->hasPages())
                <div class="px-6 py-4 border-t border-white/10">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
