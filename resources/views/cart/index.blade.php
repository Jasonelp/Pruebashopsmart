@extends('layouts.public')

@section('title', 'Carrito - ShopSmart IA')

@section('content')
    <div class="py-12 bg-gray-900 min-h-screen text-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold mb-8 text-blue-400">Tu Carrito de Compras</h1>

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-800 text-green-100 rounded border border-green-600">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-800 text-red-100 rounded border border-red-600">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-gray-800 p-6 rounded-lg shadow-lg border border-gray-700">
                @if(session('cart'))
                    <table class="w-full text-left mb-6">
                        <thead>
                            <tr class="text-gray-400 border-b border-gray-600">
                                <th class="p-4">Producto</th>
                                <th class="p-4 text-center">Precio</th>
                                <th class="p-4 text-center">Cantidad</th>
                                <th class="p-4 text-center">Subtotal</th>
                                <th class="p-4"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0 @endphp
                            @foreach(session('cart') as $id => $details)
                                @php $total += $details['price'] * $details['quantity'] @endphp
                                <tr class="border-b border-gray-700 hover:bg-gray-750 transition">
                                    <td class="p-4 font-semibold">{{ $details['name'] }}</td>
                                    <td class="p-4 text-center text-green-400">S/ {{ number_format($details['price'], 2) }}</td>
                                    <td class="p-4 text-center">{{ $details['quantity'] }}</td>
                                    <td class="p-4 text-center font-bold text-green-400">S/
                                        {{ number_format($details['price'] * $details['quantity'], 2) }}
                                    </td>
                                    <td class="p-4 text-center">
                                        <form action="{{ route('cart.remove') }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="id" value="{{ $id }}">
                                            <button type="submit" class="text-red-400 hover:text-red-300 font-medium transition">
                                                Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="border-t border-gray-700 pt-6">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-gray-400 text-sm mb-1">Total del pedido</p>
                                <p class="text-3xl font-bold text-green-400">S/ {{ number_format($total, 2) }}</p>
                            </div>

                            <div class="flex gap-4">
                                <a href="{{ route('products.public.index') }}"
                                    class="px-6 py-3 bg-gray-700 hover:bg-gray-600 rounded-lg font-semibold text-white transition">
                                    Seguir Comprando
                                </a>
                                <a href="{{ route('checkout.index') }}"
                                    class="px-8 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 rounded-lg font-semibold text-white shadow-lg transition transform hover:scale-105">
                                    Proceder al Pago
                                </a>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end text-sm text-gray-400">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span>Pago 100% seguro</span>
                        </div>
                    </div>
                @else
                    <div class="text-center py-16">
                        <svg class="mx-auto h-16 w-16 text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <p class="text-gray-400 text-xl mb-2">Tu carrito está vacío</p>
                        <p class="text-gray-500 mb-6">Agrega productos para comenzar tu compra</p>
                        <a href="{{ route('products.public.index') }}"
                            class="inline-block px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                            Explorar Productos
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection