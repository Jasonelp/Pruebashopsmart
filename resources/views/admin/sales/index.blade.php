@extends('layouts.app-simple')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-900 via-teal-800 to-blue-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-white">Historial de Ventas</h1>
                <p class="text-gray-300">Registro completo de todas las transacciones</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-lg transition">
                ← Volver al Dashboard
            </a>
        </div>

        <!-- Resumen de Ventas -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 shadow-lg">
                <p class="text-green-100 text-sm mb-1">Ventas Totales (Todo el tiempo)</p>
                <p class="text-4xl font-bold text-white">S/ {{ number_format($total_sales, 2) }}</p>
            </div>
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 shadow-lg">
                <p class="text-blue-100 text-sm mb-1">Ventas Este Mes</p>
                <p class="text-4xl font-bold text-white">S/ {{ number_format($monthly_sales, 2) }}</p>
            </div>
        </div>

        <!-- Tabla de Órdenes -->
        <div class="bg-white/10 backdrop-blur rounded-xl border border-white/20 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-white/5">
                        <tr class="text-gray-300 text-left">
                            <th class="px-6 py-4">N° Orden</th>
                            <th class="px-6 py-4">Cliente</th>
                            <th class="px-6 py-4">Productos</th>
                            <th class="px-6 py-4">Total</th>
                            <th class="px-6 py-4">Estado</th>
                            <th class="px-6 py-4">Fecha</th>
                            <th class="px-6 py-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr class="border-b border-white/5 text-gray-200 hover:bg-white/5">
                            <td class="px-6 py-4 font-mono font-bold">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-medium">{{ $order->user->name ?? 'Usuario eliminado' }}</p>
                                    <p class="text-gray-400 text-sm">{{ $order->user->email ?? '' }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm">
                                    @foreach($order->products->take(2) as $product)
                                        <p>{{ Str::limit($product->name, 20) }} (x{{ $product->pivot->quantity }})</p>
                                    @endforeach
                                    @if($order->products->count() > 2)
                                        <p class="text-gray-400">+{{ $order->products->count() - 2 }} más...</p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 font-bold text-green-400">S/ {{ number_format($order->total, 2) }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if($order->status === 'pendiente') bg-yellow-500/30 text-yellow-300
                                    @elseif($order->status === 'confirmado') bg-blue-500/30 text-blue-300
                                    @elseif($order->status === 'enviado') bg-purple-500/30 text-purple-300
                                    @elseif($order->status === 'entregado') bg-green-500/30 text-green-300
                                    @else bg-red-500/30 text-red-300 @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-400">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('orders.show', $order->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                                    Ver Detalle
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-400">No hay ventas registradas</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Paginación -->
        <div class="mt-6">
            {{ $orders->links() }}
        </div>

    </div>
</div>
@endsection