@extends('layouts.public')

@section('title', 'Pago - ShopSmart IA')

@section('content')
    <div class="min-h-screen py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-6">
                <a href="{{ route('checkout.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                    ← Volver a datos de envío
                </a>
            </div>

            <!-- Progress Steps -->
            <div class="text-center mb-8">
                <div class="flex justify-center items-center space-x-4 mb-6">
                    <div class="flex items-center">
                        <div
                            class="w-10 h-10 bg-green-600 text-white rounded-full flex items-center justify-center font-bold">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <span class="ml-2 text-sm font-medium text-green-600">Envío</span>
                    </div>
                    <div class="w-16 h-1 bg-green-600"></div>
                    <div class="flex items-center">
                        <div
                            class="w-10 h-10 bg-green-600 text-white rounded-full flex items-center justify-center font-bold">
                            2</div>
                        <span class="ml-2 text-sm font-medium text-green-600">Pago</span>
                    </div>
                    <div class="w-16 h-1 bg-gray-300"></div>
                    <div class="flex items-center">
                        <div
                            class="w-10 h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center font-bold">
                            3</div>
                        <span class="ml-2 text-sm font-medium text-gray-500">Confirmar</span>
                    </div>
                </div>
            </div>

            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Payment Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            Datos de la Tarjeta
                        </h2>

                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                <p class="text-sm text-blue-700">Esta es una pasarela de pago de demostración. No se
                                    realizarán cargos reales.</p>
                            </div>
                        </div>

                        <form action="{{ route('checkout.payment.process') }}" method="POST" id="payment-form">
                            @csrf

                            <!-- Card Icons -->
                            <div class="flex space-x-3 mb-6">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/Visa_Inc._logo.svg/100px-Visa_Inc._logo.svg.png"
                                    alt="Visa" class="h-8">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Mastercard-logo.svg/100px-Mastercard-logo.svg.png"
                                    alt="Mastercard" class="h-8">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b5/PayPal.svg/100px-PayPal.svg.png"
                                    alt="PayPal" class="h-8">
                            </div>

                            <div class="space-y-4">
                                <!-- Card Number -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Número de Tarjeta
                                    </label>
                                    <div class="relative">
                                        <input type="text" name="card_number" id="card_number"
                                            placeholder="1234 5678 9012 3456" maxlength="19"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-gray-900 text-lg tracking-wider"
                                            required>
                                        <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                            </svg>
                                        </div>
                                    </div>
                                    @error('card_number')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Card Holder Name -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Nombre en la Tarjeta
                                    </label>
                                    <input type="text" name="card_name" placeholder="JUAN PEREZ"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-gray-900 uppercase"
                                        required>
                                    @error('card_name')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Expiry and CVV -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Fecha de Expiración
                                        </label>
                                        <input type="text" name="card_expiry" id="card_expiry" placeholder="MM/YY"
                                            maxlength="5"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-gray-900 text-center tracking-wider"
                                            required>
                                        @error('card_expiry')
                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            CVV
                                        </label>
                                        <div class="relative">
                                            <input type="password" name="card_cvv" placeholder="•••" maxlength="4"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-gray-900 text-center tracking-wider"
                                                required>
                                            <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                        </div>
                                        @error('card_cvv')
                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Security Info -->
                            <div class="mt-6 pt-6 border-t">
                                <div class="flex items-center justify-center text-sm text-gray-600 mb-4">
                                    <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>Pago 100% seguro con encriptación SSL</span>
                                </div>

                                <button type="submit"
                                    class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 rounded-lg transition flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    Pagar S/ {{ number_format($total, 2) }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                        <h2 class="text-lg font-bold text-gray-900 mb-4">Resumen del Pedido</h2>

                        <div class="space-y-3 mb-6">
                            @foreach($cart as $id => $item)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">{{ $item['name'] }} x{{ $item['quantity'] }}</span>
                                    <span class="font-medium text-gray-900">S/
                                        {{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="border-t pt-4 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-medium text-gray-900">S/ {{ number_format($total, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Envío</span>
                                <span class="font-medium text-green-600">Gratis</span>
                            </div>
                        </div>

                        <div class="border-t mt-4 pt-4">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-bold text-gray-900">Total</span>
                                <span class="text-2xl font-bold text-green-600">S/ {{ number_format($total, 2) }}</span>
                            </div>
                        </div>

                        <!-- Shipping Info -->
                        <div class="border-t mt-4 pt-4">
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Enviar a:</h3>
                            <p class="text-sm text-gray-600">{{ $checkoutData['shipping_name'] }}</p>
                            <p class="text-sm text-gray-600">{{ $checkoutData['shipping_address'] }}</p>
                            <p class="text-sm text-gray-600">{{ $checkoutData['shipping_district'] }},
                                {{ $checkoutData['shipping_city'] }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        // Format card number with spaces
        document.getElementById('card_number').addEventListener('input', function (e) {
            let value = e.target.value.replace(/\s/g, '').replace(/\D/g, '');
            let formatted = value.match(/.{1,4}/g)?.join(' ') || '';
            e.target.value = formatted;
        });

        // Format expiry date
        document.getElementById('card_expiry').addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            e.target.value = value;
        });
    </script>
@endsection