@extends('layouts.app-simple')

@section('content')
<div class="min-h-screen bg-gray-100 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-6 flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-900">Detalle del Pedido #{{ $order->id }}</h1>
            <a href="{{ route('orders.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                ← Volver a Pedidos
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold mb-4">Información del Pedido</h2>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Cliente</p>
                    <p class="font-semibold">{{ $order->user->name }}</p>
                    <p class="text-sm text-gray-500">{{ $order->user->email }}</p>
                </div>
                
                <div>
                    <p class="text-sm text-gray-600">Fecha del Pedido</p>
                    <p class="font-semibold">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                </div>
                
                <div>
                    <p class="text-sm text-gray-600">Estado</p>
                    @switch($order->status)
                        @case('pendiente')
                            <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Pendiente
                            </span>
                            @break
                        @case('confirmado')
                            <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                Confirmado
                            </span>
                            @break
                        @case('enviado')
                            <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-purple-100 text-purple-800">
                                Enviado
                            </span>
                            @break
                        @case('entregado')
                            <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                Entregado
                            </span>
                            @break
                        @case('cancelado')
                            <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                Cancelado
                            </span>
                            @break
                    @endswitch
                </div>
                
                <div>
                    <p class="text-sm text-gray-600">Total</p>
                    <p class="text-2xl font-bold text-green-600">S/. {{ number_format($order->total, 2) }}</p>
                </div>
            </div>

            @if($order->shipping_address)
                <div class="mt-4 pt-4 border-t">
                    <p class="text-sm text-gray-600">Dirección de Envío</p>
                    <p class="font-semibold">{{ $order->shipping_address }}</p>
                </div>
            @endif

            @if($order->notes)
                <div class="mt-4 pt-4 border-t">
                    <p class="text-sm text-gray-600">Notas</p>
                    <p class="text-gray-700">{{ $order->notes }}</p>
                </div>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold mb-4">Productos</h2>
            
            <div class="space-y-4">
                @foreach($order->products as $product)
                    <div class="flex items-center justify-between border-b pb-4">
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover rounded">
                                @else
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <p class="font-semibold">{{ $product->name }}</p>
                                <p class="text-sm text-gray-500">Cantidad: {{ $product->pivot->quantity }}</p>
                                <p class="text-sm text-gray-500">Precio unitario: S/. {{ number_format($product->pivot->price, 2) }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-lg">S/. {{ number_format($product->pivot->price * $product->pivot->quantity, 2) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 pt-4 border-t">
                <div class="flex justify-between text-lg font-bold">
                    <span>Total:</span>
                    <span class="text-green-600">S/. {{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>

        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'vendor')
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold mb-4">Cambiar Estado del Pedido</h2>
                
                <form action="{{ route('orders.update', $order->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="flex items-center space-x-4">
                        <select name="status" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="pendiente" {{ $order->status === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="confirmado" {{ $order->status === 'confirmado' ? 'selected' : '' }}>Confirmado</option>
                            <option value="enviado" {{ $order->status === 'enviado' ? 'selected' : '' }}>Enviado</option>
                            <option value="entregado" {{ $order->status === 'entregado' ? 'selected' : '' }}>Entregado</option>
                            <option value="cancelado" {{ $order->status === 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                        
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition">
                            Actualizar Estado
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection