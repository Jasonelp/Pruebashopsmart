@extends('layouts.public')

@section('title', 'Pedidos Recibidos - ShopSmart IA')

@section('content')
    <div class="min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">Pedidos Recibidos</h1>
                    <p class="text-gray-300">Pedidos que incluyen tus productos</p>
                </div>
                <a href="{{ route('vendor.dashboard') }}"
                    class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-lg transition">
                    ← Dashboard
                </a>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-600/20 border border-green-500 text-green-300 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-600/20 border border-red-500 text-red-300 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Tabla de Pedidos -->
            <div class="bg-white/10 backdrop-blur rounded-xl border border-white/20 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-white/5">
                            <tr class="text-gray-300 text-left">
                                <th class="px-6 py-4">N° Orden</th>
                                <th class="px-6 py-4">Cliente</th>
                                <th class="px-6 py-4">Mis Productos</th>
                                <th class="px-6 py-4">Estado</th>
                                <th class="px-6 py-4">Fecha</th>
                                <th class="px-6 py-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr class="border-b border-white/5 text-gray-200 hover:bg-white/5">
                                    <td class="px-6 py-4 font-mono font-bold">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="font-medium">{{ $order->user->name ?? 'N/A' }}</p>
                                        <p class="text-gray-400 text-sm">{{ $order->user->email ?? '' }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        @foreach($order->products->where('user_id', Auth::id()) as $product)
                                            <div class="text-sm mb-1">
                                                <span class="text-white">{{ $product->name }}</span>
                                                <span class="text-gray-400">(x{{ $product->pivot->quantity }})</span>
                                                <span class="text-green-400">S/
                                                    {{ number_format($product->pivot->price * $product->pivot->quantity, 2) }}</span>
                                            </div>
                                        @endforeach
                                    </td>
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
                                        <div class="flex flex-col gap-2">
                                            <!-- Chat Button -->
                                            <a href="{{ route('chat.show', $order->id) }}"
                                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm flex items-center justify-center transition">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                                </svg>
                                                Chat
                                            </a>

                                            @if($order->status !== 'entregado' && $order->status !== 'cancelado')
                                                <!-- Update Status -->
                                                <form action="{{ route('vendor.orders.status', $order->id) }}" method="POST"
                                                    class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="status" onchange="this.form.submit()"
                                                        class="bg-gray-700 text-white text-sm rounded px-2 py-2 w-full cursor-pointer">
                                                        <option value="">Cambiar estado...</option>
                                                        @if($order->status === 'pendiente')
                                                            <option value="confirmado">Confirmar</option>
                                                        @endif
                                                        @if(in_array($order->status, ['pendiente', 'confirmado']))
                                                            <option value="enviado">Marcar Enviado</option>
                                                        @endif
                                                        @if(in_array($order->status, ['pendiente', 'confirmado', 'enviado']))
                                                            <option value="entregado">Marcar Entregado</option>
                                                        @endif
                                                    </select>
                                                </form>

                                                <!-- Quick Deliver Button -->
                                                <form action="{{ route('vendor.orders.delivered', $order->id) }}" method="POST"
                                                    onsubmit="return confirm('¿Marcar este pedido como entregado?')">
                                                    @csrf
                                                    <button type="submit"
                                                        class="w-full bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg text-sm flex items-center justify-center transition">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M5 13l4 4L19 7" />
                                                        </svg>
                                                        Entregado
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-green-400 text-sm flex items-center justify-center">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    Completado
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        <p class="text-gray-400">No tienes pedidos aún</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">
                {{ $orders->links() }}
            </div>

        </div>
    </div>
@endsection