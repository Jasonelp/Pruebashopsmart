@extends('layouts.public')

@section('title', 'Checkout - ShopSmart IA')

@section('content')
    <div class="min-h-screen py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-6">
                <a href="{{ route('cart.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                    ← Volver al carrito
                </a>
            </div>

            <div class="text-center mb-8">
                <div class="flex justify-center items-center space-x-4 mb-6">
                    <div class="flex items-center">
                        <div
                            class="w-10 h-10 bg-green-600 text-white rounded-full flex items-center justify-center font-bold">
                            1</div>
                        <span class="ml-2 text-sm font-medium text-gray-700">Envío</span>
                    </div>
                    <div class="w-16 h-1 bg-gray-300"></div>
                    <div class="flex items-center">
                        <div
                            class="w-10 h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center font-bold">
                            2</div>
                        <span class="ml-2 text-sm font-medium text-gray-500">Pago</span>
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

            <form action="{{ route('checkout.process') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                            <h2 class="text-xl font-bold text-gray-900 mb-6">Datos de Envío</h2>

                            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                                <p class="text-sm text-blue-700">
                                    Necesitamos tu dirección para enviarte el pedido de forma segura
                                </p>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Nombre completo
                                    </label>
                                    <input type="text" name="shipping_name"
                                        value="{{ old('shipping_name', Auth::user()->name) }}"
                                        placeholder="Ej: Juan Carlos Pérez López"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-gray-900"
                                        required>
                                    @error('shipping_name')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Teléfono
                                        </label>
                                        <input type="text" name="shipping_phone" value="{{ old('shipping_phone') }}"
                                            placeholder="Ej: 987654321"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-gray-900"
                                            required>
                                        @error('shipping_phone')
                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Correo electrónico
                                        </label>
                                        <input type="email" name="shipping_email"
                                            value="{{ old('shipping_email', Auth::user()->email) }}"
                                            placeholder="Ej: juan@ejemplo.com"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-gray-900"
                                            required>
                                        @error('shipping_email')
                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Dirección completa
                                    </label>
                                    <input type="text" name="shipping_address" value="{{ old('shipping_address') }}"
                                        placeholder="Ej: Av. Javier Prado 123, Dpto 402"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-gray-900"
                                        required>
                                    @error('shipping_address')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Distrito
                                        </label>
                                        <input type="text" name="shipping_district" value="{{ old('shipping_district') }}"
                                            placeholder="Ej: San Isidro"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-gray-900"
                                            required>
                                        @error('shipping_district')
                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Ciudad/Departamento
                                        </label>
                                        <input type="text" name="shipping_city" value="{{ old('shipping_city') }}"
                                            placeholder="Ej: Lima"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-gray-900"
                                            required>
                                        @error('shipping_city')
                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Código postal (opcional)
                                    </label>
                                    <input type="text" name="shipping_zipcode" value="{{ old('shipping_zipcode') }}"
                                        placeholder="Ej: 15073"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-gray-900">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Referencia (opcional)
                                    </label>
                                    <input type="text" name="shipping_reference" value="{{ old('shipping_reference') }}"
                                        placeholder="Ej: Frente al parque"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-gray-900">
                                </div>
                            </div>

                            <div class="mt-6 pt-6 border-t">
                                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                                    <p class="text-sm text-yellow-700">
                                        Verifica que la dirección sea correcta. El envío se realizará a la dirección
                                        proporcionada
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h2 class="text-xl font-bold text-gray-900 mb-6">Método de Pago</h2>

                            <div class="space-y-3">
                                <label
                                    class="flex items-center p-4 border-2 border-blue-500 bg-blue-50 rounded-lg cursor-pointer">
                                    <input type="radio" name="payment_method" value="tarjeta_credito"
                                        class="w-4 h-4 text-blue-600" checked>
                                    <div class="ml-3">
                                        <div class="flex items-center">
                                            <span class="font-medium text-gray-900">Tarjeta de Crédito</span>
                                        </div>
                                        <p class="text-sm text-gray-500">Pago 100% seguro con encriptación SSL</p>
                                    </div>
                                </label>

                                <label
                                    class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-gray-400">
                                    <input type="radio" name="payment_method" value="tarjeta_debito"
                                        class="w-4 h-4 text-blue-600">
                                    <div class="ml-3">
                                        <div class="flex items-center">
                                            <span class="font-medium text-gray-900">Tarjeta de Débito</span>
                                        </div>
                                    </div>
                                </label>

                                <label
                                    class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-gray-400">
                                    <input type="radio" name="payment_method" value="contra_entrega"
                                        class="w-4 h-4 text-blue-600">
                                    <div class="ml-3">
                                        <div class="flex items-center">
                                            <span class="font-medium text-gray-900">Pago contra entrega (Efectivo)</span>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            <div class="mt-6 pt-6 border-t">
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Pago 100% seguro
                                </div>
                                <p class="text-xs text-gray-500 mt-2 ml-7">
                                    Devoluciones hasta 30 días - Soporte 24/7
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                            <h2 class="text-xl font-bold text-gray-900 mb-4">Resumen del Pedido</h2>

                            <div class="space-y-3 mb-6">
                                @foreach($cart as $id => $item)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">{{ $item['name'] }} x{{ $item['quantity'] }}</span>
                                        <span class="font-medium">S/
                                            {{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                                    </div>
                                @endforeach
                            </div>

                            <div class="border-t pt-4 space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Subtotal</span>
                                    <span class="font-medium">S/ {{ number_format($total, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Envío</span>
                                    <span class="font-medium text-green-600">Gratis</span>
                                </div>
                            </div>

                            <div class="border-t mt-4 pt-4">
                                <div class="flex justify-between items-center mb-6">
                                    <span class="text-lg font-bold text-gray-900">Total</span>
                                    <span class="text-2xl font-bold text-green-600">S/ {{ number_format($total, 2) }}</span>
                                </div>

                                <button type="submit"
                                    class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-lg transition">
                                    Continuar al pago
                                </button>

                                <p class="text-xs text-gray-500 text-center mt-4">
                                    Pago 100% seguro - Devoluciones hasta 30 días - Soporte 24/7
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </form>

        </div>
    </div>
@endsection