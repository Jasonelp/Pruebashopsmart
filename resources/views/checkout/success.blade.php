@extends('layouts.public')

@section('title', 'Compra Exitosa - ShopSmart IA')

@section('content')
    <div class="min-h-screen py-16">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-4">
                    <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Compra realizada con éxito</h1>
                <p class="text-gray-600">Tu pedido ha sido confirmado y será enviado pronto a:</p>
            </div>

            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd" />
                    </svg>
                    <p class="text-sm text-blue-700">
                        El asistente IA te mantendrá informado sobre el estado de tu pedido
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Dirección de Envío</h2>
                <div class="text-gray-700">
                    <p class="font-medium">{{ $order->user->name }}</p>
                    <p class="text-sm">{{ $order->shipping_address }}</p>
                    <p class="text-sm">Teléfono: {{ $order->user->email }}</p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Productos</h2>
                <div class="space-y-3">
                    @foreach($order->products as $product)
                        <div class="flex justify-between text-sm border-b pb-3">
                            <div>
                                <p class="font-medium text-gray-900">{{ $product->name }}</p>
                                <p class="text-gray-500">Cantidad: {{ $product->pivot->quantity }}</p>
                            </div>
                            <p class="font-semibold">S/
                                {{ number_format($product->pivot->price * $product->pivot->quantity, 2) }}</p>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 pt-4 border-t flex justify-between">
                    <span class="font-bold text-lg">Total</span>
                    <span class="font-bold text-2xl text-green-600">S/ {{ number_format($order->total, 2) }}</span>
                </div>
            </div>

            <div class="flex gap-4">
                <a href="{{ route('orders.index') }}"
                    class="flex-1 bg-green-600 hover:bg-green-700 text-white text-center px-6 py-3 rounded-lg font-semibold transition">
                    Ver mis pedidos
                </a>
                <a href="{{ route('home') }}"
                    class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-900 text-center px-6 py-3 rounded-lg font-semibold transition">
                    Seguir comprando
                </a>
            </div>

        </div>
    </div>
@endsection