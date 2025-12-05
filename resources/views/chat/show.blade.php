@extends('layouts.public')

@section('title', 'Chat - Pedido #{{ $order->id }} - ShopSmart IA')

@section('content')
    <div class="min-h-screen py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <a href="{{ $isVendor ? route('vendor.orders') : route('orders.my-orders') }}"
                        class="text-blue-400 hover:text-blue-300 flex items-center mb-2">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Volver a pedidos
                    </a>
                    <h1 class="text-2xl font-bold text-white">Chat - Pedido #{{ $order->id }}</h1>
                    <p class="text-gray-400">
                        @if($isVendor)
                            Cliente: {{ $otherParticipant->name ?? 'N/A' }}
                        @else
                            Vendedor: {{ $otherParticipant->name ?? 'N/A' }}
                        @endif
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Report Button -->
                    @if($otherParticipant)
                        <button onclick="openReportModal()"
                            class="bg-red-600/20 hover:bg-red-600/30 text-red-400 px-3 py-2 rounded-lg text-sm flex items-center transition border border-red-600/30">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            Reportar
                        </button>
                    @endif

                    <!-- Order Status -->
                    <div class="text-right">
                        <span class="inline-flex px-3 py-1 rounded-full text-sm font-semibold
                            @if($order->status == 'entregado') bg-green-600 text-white
                            @elseif($order->status == 'enviado') bg-blue-600 text-white
                            @elseif($order->status == 'cancelado') bg-red-600 text-white
                            @else bg-yellow-600 text-white
                            @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                        <p class="text-gray-400 text-sm mt-1">S/ {{ number_format($order->total, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Chat Container -->
            <div class="bg-gray-800 rounded-xl border border-gray-700 shadow-lg overflow-hidden">

                <!-- Messages Area -->
                <div id="messages-container" class="h-96 overflow-y-auto p-4 space-y-4">
                    @forelse($messages as $message)
                        <div class="flex {{ $message->sender_id == Auth::id() ? 'justify-end' : 'justify-start' }}">
                            <div
                                class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg {{ $message->sender_id == Auth::id() ? 'bg-blue-600 text-white' : 'bg-gray-700 text-white' }}">
                                <p
                                    class="text-xs {{ $message->sender_id == Auth::id() ? 'text-blue-200' : 'text-gray-400' }} mb-1">
                                    {{ $message->sender->name }}
                                </p>
                                <p class="text-sm">{{ $message->message }}</p>
                                <p
                                    class="text-xs {{ $message->sender_id == Auth::id() ? 'text-blue-200' : 'text-gray-500' }} mt-1 text-right">
                                    {{ $message->created_at->format('H:i') }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-500 py-8" id="no-messages">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <p>No hay mensajes aún</p>
                            <p class="text-sm">¡Inicia la conversación!</p>
                        </div>
                    @endforelse
                </div>

                <!-- Input Area -->
                <div class="border-t border-gray-700 p-4 bg-gray-750">
                    <form id="message-form" class="flex gap-3">
                        @csrf
                        <input type="text" id="message-input" placeholder="Escribe tu mensaje..."
                            class="flex-1 bg-gray-700 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            maxlength="1000" required>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition flex items-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Order Products Summary -->
            <div class="mt-6 bg-gray-800 rounded-xl border border-gray-700 p-4">
                <h3 class="text-white font-semibold mb-3">Productos del pedido</h3>
                <div class="space-y-2">
                    @foreach($order->products as $product)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-300">{{ $product->name }} x{{ $product->pivot->quantity }}</span>
                            <span class="text-green-400">S/
                                {{ number_format($product->pivot->price * $product->pivot->quantity, 2) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    <!-- Report Modal -->
    @if($otherParticipant)
        <div id="reportModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
            <div class="bg-gray-800 rounded-xl shadow-2xl max-w-md w-full p-6 border border-gray-700">
                <h3 class="text-xl font-bold text-white mb-4 flex items-center">
                    <svg class="w-6 h-6 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    Reportar Usuario
                </h3>

                <p class="text-gray-400 text-sm mb-4">
                    Estás por reportar a <span class="text-white font-semibold">{{ $otherParticipant->name }}</span>.
                    Los reportes falsos pueden resultar en suspensión de tu cuenta.
                </p>

                <form id="reportForm">
                    @csrf
                    <input type="hidden" name="reported_id" value="{{ $otherParticipant->id }}">
                    <input type="hidden" name="order_id" value="{{ $order->id }}">

                    <div class="mb-4">
                        <label class="block text-gray-400 text-sm font-medium mb-2">Motivo del reporte *</label>
                        <select name="reason" id="report_reason"
                            class="w-full bg-gray-700 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-red-500 focus:outline-none"
                            required>
                            <option value="">Selecciona un motivo</option>
                            <option value="Fraude o estafa">Fraude o estafa</option>
                            <option value="Comportamiento inapropiado">Comportamiento inapropiado</option>
                            <option value="Acoso">Acoso o amenazas</option>
                            <option value="Producto no entregado">Producto no entregado</option>
                            <option value="Producto diferente al anunciado">Producto diferente al anunciado</option>
                            <option value="Información falsa">Información falsa</option>
                            <option value="Otro">Otro motivo</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-400 text-sm font-medium mb-2">Descripción detallada *</label>
                        <textarea name="description" id="report_description" rows="4"
                            class="w-full bg-gray-700 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-red-500 focus:outline-none"
                            placeholder="Describe con detalle lo sucedido..." required minlength="20"
                            maxlength="1000"></textarea>
                        <p class="text-gray-500 text-xs mt-1">Mínimo 20 caracteres</p>
                    </div>

                    <div id="reportError"
                        class="hidden mb-4 bg-red-600/20 border border-red-500 text-red-300 px-4 py-2 rounded-lg text-sm"></div>
                    <div id="reportSuccess"
                        class="hidden mb-4 bg-green-600/20 border border-green-500 text-green-300 px-4 py-2 rounded-lg text-sm">
                    </div>

                    <div class="flex gap-3">
                        <button type="button" onclick="closeReportModal()"
                            class="flex-1 bg-gray-600 hover:bg-gray-500 text-white py-3 rounded-lg font-semibold transition">
                            Cancelar
                        </button>
                        <button type="submit" id="submitReportBtn"
                            class="flex-1 bg-red-600 hover:bg-red-700 text-white py-3 rounded-lg font-semibold transition">
                            Enviar Reporte
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <script>
        const orderId = {{ $order->id }};
        const userId = {{ Auth::id() }};
        const messagesContainer = document.getElementById('messages-container');
        const messageForm = document.getElementById('message-form');
        const messageInput = document.getElementById('message-input');

        // Scroll to bottom
        function scrollToBottom() {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
        scrollToBottom();

        // Send message
        messageForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            const message = messageInput.value.trim();
            if (!message) return;

            try {
                const response = await fetch(`/chat/${orderId}/send`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ message })
                });

                const data = await response.json();

                if (data.success) {
                    const noMessages = document.getElementById('no-messages');
                    if (noMessages) noMessages.remove();

                    const msgDiv = document.createElement('div');
                    msgDiv.className = 'flex justify-end';
                    msgDiv.innerHTML = `
                        <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg bg-blue-600 text-white">
                            <p class="text-xs text-blue-200 mb-1">${data.message.sender.name}</p>
                            <p class="text-sm">${data.message.message}</p>
                            <p class="text-xs text-blue-200 mt-1 text-right">${new Date().toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' })}</p>
                        </div>
                    `;
                    messagesContainer.appendChild(msgDiv);
                    messageInput.value = '';
                    scrollToBottom();
                }
            } catch (error) {
                console.error('Error sending message:', error);
            }
        });

        // Poll for new messages every 5 seconds
        setInterval(async function () {
            try {
                const response = await fetch(`/chat/${orderId}/messages`);
                const data = await response.json();

                if (data.messages && data.messages.length > messagesContainer.querySelectorAll('.flex').length) {
                    location.reload();
                }
            } catch (error) {
                console.error('Error fetching messages:', error);
            }
        }, 5000);

        // Report Modal Functions
        function openReportModal() {
            document.getElementById('reportModal').classList.remove('hidden');
            document.getElementById('reportError').classList.add('hidden');
            document.getElementById('reportSuccess').classList.add('hidden');
        }

        function closeReportModal() {
            document.getElementById('reportModal').classList.add('hidden');
            document.getElementById('reportForm').reset();
        }

        // Report Form Submit
        document.getElementById('reportForm')?.addEventListener('submit', async function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitBtn = document.getElementById('submitReportBtn');
            const errorDiv = document.getElementById('reportError');
            const successDiv = document.getElementById('reportSuccess');

            submitBtn.disabled = true;
            submitBtn.textContent = 'Enviando...';
            errorDiv.classList.add('hidden');
            successDiv.classList.add('hidden');

            try {
                const response = await fetch('/reports', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(Object.fromEntries(formData))
                });

                const data = await response.json();

                if (data.success) {
                    successDiv.textContent = data.message;
                    successDiv.classList.remove('hidden');
                    setTimeout(() => closeReportModal(), 2000);
                } else {
                    errorDiv.textContent = data.error || 'Error al enviar el reporte';
                    errorDiv.classList.remove('hidden');
                }
            } catch (error) {
                errorDiv.textContent = 'Error de conexión';
                errorDiv.classList.remove('hidden');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Enviar Reporte';
            }
        });

        // Close modal on outside click
        document.getElementById('reportModal')?.addEventListener('click', function (e) {
            if (e.target === this) closeReportModal();
        });
    </script>
@endsection