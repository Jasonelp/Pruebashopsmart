@extends('layouts.public')

@section('title', 'Mis Pedidos - ShopSmart IA')

@section('content')
    <div class="min-h-screen py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Encabezado -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-white mb-4">Mis Pedidos</h1>
                <p class="text-gray-300">Historial completo de tus compras en ShopSmart IA</p>
            </div>

            @if($orders->count() > 0)
                <div class="space-y-6">
                    @foreach($orders as $order)
                        <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300">
                            <!-- Header del pedido -->
                            <div class="bg-gray-700 px-6 py-4 border-b border-gray-600">
                                <div class="flex justify-between items-center flex-wrap gap-4">
                                    <div>
                                        <h3 class="text-xl font-bold text-white">Pedido #{{ $order->id }}</h3>
                                        <p class="text-gray-400 text-sm mt-1">
                                            <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ $order->created_at->format('d/m/Y H:i') }}
                                        </p>
                                    </div>

                                    <div class="text-right">
                                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold
                                                                            @if($order->status == 'entregado') bg-green-600 text-white
                                                                            @elseif($order->status == 'enviado') bg-blue-600 text-white
                                                                            @elseif($order->status == 'confirmado') bg-yellow-600 text-white
                                                                            @elseif($order->status == 'cancelado') bg-red-600 text-white
                                                                            @else bg-gray-600 text-white
                                                                            @endif">
                                            @if($order->status == 'entregado')
                                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            @elseif($order->status == 'enviado')
                                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                                                    <path
                                                        d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z" />
                                                </svg>
                                            @endif
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Detalles del pedido -->
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <!-- Información de envío -->
                                    <div class="bg-gray-700 rounded-lg p-4">
                                        <h4 class="text-white font-bold mb-3 flex items-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            Dirección de Envío
                                        </h4>
                                        <p class="text-gray-300 text-sm">{{ $order->shipping_address }}</p>
                                    </div>

                                    <!-- Método de pago -->
                                    <div class="bg-gray-700 rounded-lg p-4">
                                        <h4 class="text-white font-bold mb-3 flex items-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                            </svg>
                                            Método de Pago
                                        </h4>
                                        <p class="text-gray-300 text-sm">{{ ucfirst($order->payment_method) }}</p>
                                    </div>
                                </div>

                                <!-- Productos del pedido -->
                                <div class="mb-6">
                                    <h4 class="text-white font-bold mb-4 flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                        Productos ({{ $order->products->count() }})
                                    </h4>
                                    <div class="space-y-3">
                                        @foreach($order->products as $product)
                                            <div class="flex items-center justify-between bg-gray-700 rounded-lg p-4">
                                                <div class="flex items-center space-x-4">
                                                    <div
                                                        class="w-16 h-16 bg-gray-600 rounded-lg flex items-center justify-center overflow-hidden">
                                                        @if($product->image)
                                                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                                                class="w-full h-full object-cover">
                                                        @else
                                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <p class="text-white font-semibold">{{ $product->name }}</p>
                                                        <p class="text-gray-400 text-sm">Cantidad: {{ $product->pivot->quantity }}</p>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-blue-400 font-bold">S/.
                                                        {{ number_format($product->pivot->price * $product->pivot->quantity, 2) }}
                                                    </p>
                                                    <p class="text-gray-400 text-sm">S/. {{ number_format($product->pivot->price, 2) }}
                                                        c/u</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Total y acciones -->
                                <div
                                    class="flex flex-col md:flex-row justify-between items-start md:items-center border-t border-gray-600 pt-6 gap-4">
                                    <div class="text-white">
                                        <p class="text-2xl font-bold">Total: <span class="text-blue-400">S/.
                                                {{ number_format($order->total, 2) }}</span></p>
                                        @if($order->status != 'cancelado' && $order->status != 'entregado' && $order->status != 'completado')
                                            @php
                                                // Estimado de envío basado en el total (simulación)
                                                $deliveryDays = $order->total > 200 ? rand(1, 2) : ($order->total > 100 ? rand(2, 3) : rand(3, 5));
                                                $estimatedDate = $order->created_at->addDays($deliveryDays)->format('d/m/Y');
                                            @endphp
                                            <p class="text-sm text-gray-400 mt-2 flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                Entrega estimada: <span
                                                    class="text-green-400 ml-1 font-semibold">{{ $estimatedDate }}</span>
                                            </p>
                                        @endif
                                    </div>
                                    <div class="flex space-x-3">
                                        @if($order->status == 'completado' || $order->status == 'entregado')
                                            <span class="bg-green-600 text-white px-6 py-3 rounded-lg font-semibold flex items-center">
                                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Completado
                                            </span>
                                        @elseif($order->status == 'cancelado')
                                            <div class="flex flex-col items-end">
                                                <span
                                                    class="bg-red-600 text-white px-6 py-3 rounded-lg font-semibold flex items-center">
                                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    Cancelado
                                                </span>
                                                @if($order->cancellation_reason)
                                                    <p class="text-xs text-gray-400 mt-2">Motivo: {{ $order->cancellation_reason }}</p>
                                                @endif
                                            </div>
                                        @elseif($order->status == 'pendiente' || $order->status == 'confirmado')
                                            <a href="{{ route('chat.show', $order->id) }}"
                                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg font-semibold flex items-center transition duration-200">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                                </svg>
                                                Chat
                                            </a>
                                            <button type="button" onclick="openCancelModal({{ $order->id }})"
                                                class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-semibold transition duration-200">
                                                Cancelar Pedido
                                            </button>
                                        @elseif($order->status == 'enviado')
                                            <a href="{{ route('chat.show', $order->id) }}"
                                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg font-semibold flex items-center transition duration-200">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                                </svg>
                                                Chat
                                            </a>
                                            <span class="bg-purple-600 text-white px-6 py-3 rounded-lg font-semibold flex items-center">
                                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                                                    <path
                                                        d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z" />
                                                </svg>
                                                En Camino
                                            </span>
                                        @else
                                            <span class="bg-yellow-600 text-white px-6 py-3 rounded-lg font-semibold">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Paginación -->
                <div class="mt-8">
                    {{ $orders->links() }}
                </div>
            @else
                <!-- Mensaje cuando no hay pedidos -->
                <div class="text-center py-16 bg-gray-800 rounded-lg">
                    <svg class="mx-auto h-24 w-24 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <h3 class="mt-4 text-xl font-medium text-gray-300">Aún no tienes pedidos</h3>
                    <p class="mt-2 text-gray-400">Explora nuestro catálogo y realiza tu primera compra</p>
                    <a href="{{ route('products.public.index') }}"
                        class="inline-block mt-6 bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition duration-200">
                        Ir a Productos
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal de Cancelación -->
    <div id="cancelModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-gray-800 rounded-lg shadow-2xl max-w-md w-full p-6 border border-gray-700">
            <h3 class="text-xl font-bold text-white mb-4 flex items-center">
                <svg class="w-6 h-6 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                Cancelar Pedido
            </h3>

            <form id="cancelForm" method="POST">
                @csrf
                @method('DELETE')

                <p class="text-gray-300 mb-4">¿Estás seguro de que deseas cancelar este pedido? Por favor, indícanos el
                    motivo:</p>

                <div class="mb-4">
                    <label class="block text-gray-400 text-sm font-medium mb-2">Motivo de cancelación *</label>
                    <select name="cancellation_reason" id="cancel_reason_select"
                        class="w-full bg-gray-700 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-red-500 focus:outline-none"
                        required>
                        <option value="">Selecciona un motivo</option>
                        <option value="Cambié de opinión">Cambié de opinión</option>
                        <option value="Encontré mejor precio">Encontré mejor precio en otro lugar</option>
                        <option value="Error en el pedido">Error en el pedido</option>
                        <option value="Tiempo de entrega muy largo">Tiempo de entrega muy largo</option>
                        <option value="Problemas con el método de pago">Problemas con el método de pago</option>
                        <option value="otro">Otro motivo</option>
                    </select>
                </div>

                <div id="otro_motivo_container" class="mb-4 hidden">
                    <label class="block text-gray-400 text-sm font-medium mb-2">Especifica el motivo:</label>
                    <textarea id="otro_motivo_texto" rows="3"
                        class="w-full bg-gray-700 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-red-500 focus:outline-none"
                        placeholder="Escribe aquí tu motivo..."></textarea>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closeCancelModal()"
                        class="flex-1 bg-gray-600 hover:bg-gray-500 text-white py-3 rounded-lg font-semibold transition">
                        Volver
                    </button>
                    <button type="submit"
                        class="flex-1 bg-red-600 hover:bg-red-700 text-white py-3 rounded-lg font-semibold transition">
                        Confirmar Cancelación
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openCancelModal(orderId) {
            document.getElementById('cancelForm').action = '/orders/' + orderId;
            document.getElementById('cancel_reason_select').value = '';
            document.getElementById('otro_motivo_container').classList.add('hidden');
            document.getElementById('cancelModal').classList.remove('hidden');
        }

        function closeCancelModal() {
            document.getElementById('cancelModal').classList.add('hidden');
        }

        document.getElementById('cancel_reason_select').addEventListener('change', function () {
            const container = document.getElementById('otro_motivo_container');
            const textarea = document.getElementById('otro_motivo_texto');

            if (this.value === 'otro') {
                container.classList.remove('hidden');
                textarea.required = true;
            } else {
                container.classList.add('hidden');
                textarea.required = false;
            }
        });

        document.getElementById('cancelForm').addEventListener('submit', function (e) {
            const select = document.getElementById('cancel_reason_select');
            const textarea = document.getElementById('otro_motivo_texto');

            if (select.value === 'otro' && textarea.value.trim()) {
                // Create hidden input with the custom reason
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'cancellation_reason';
                hiddenInput.value = textarea.value.trim();
                this.appendChild(hiddenInput);

                // Remove the select's name so it doesn't conflict
                select.name = '';
            }
        });

        // Close modal on outside click
        document.getElementById('cancelModal').addEventListener('click', function (e) {
            if (e.target === this) {
                closeCancelModal();
            }
        });
    </script>
@endsection