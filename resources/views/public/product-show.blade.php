@extends('layouts.public')

@section('title', $product->name)

@section('content')
    <div class="max-w-6xl mx-auto px-6 py-10 text-white">

        <!-- HEADER DEL PRODUCTO -->
        <div class="flex flex-col md:flex-row gap-10">

            <!-- IMAGEN -->
            <div class="w-full md:w-1/2">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                        class="rounded-xl shadow-xl border border-gray-700 w-full h-auto object-cover">
                @else
                    <div class="rounded-xl shadow-xl border border-gray-700 bg-gray-800 flex items-center justify-center h-96">
                        <div class="text-center text-gray-500">
                            <svg class="h-32 w-32 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-lg font-semibold">{{ $product->name }}</p>
                            <p class="text-sm mt-2">Imagen no disponible</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- INFO PRINCIPAL -->
            <div class="w-full md:w-1/2 space-y-4">
                <h1 class="text-4xl font-extrabold">{{ $product->name }}</h1>

                <p class="text-green-300 text-3xl font-bold">
                    S/ {{ number_format($product->price, 2) }}
                </p>

                <p class="text-gray-300">
                    Categoría:
                    <span class="text-white font-semibold">{{ $product->category->name }}</span>
                </p>

                <p class="text-gray-400">
                    {{ $product->description }}
                </p>

                @if($product->stock > 0)
                    <p class="text-green-400 font-semibold">✔ Disponible ({{ $product->stock }} unidades)</p>
                @else
                    <p class="text-red-400 font-semibold">✖ Sin stock</p>
                @endif
            </div>
        </div>

        <!-- BOTÓN DE AÑADIR AL CARRITO -->
        @auth
            <div class="mt-8">
                <a href="{{ route('add_to_cart', $product->id) }}"
                    class="bg-green-600 hover:bg-green-700 text-white px-8 py-4 rounded-lg shadow-lg inline-flex items-center gap-3 text-lg font-semibold transition-all duration-200 transform hover:scale-105">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Añadir al Carrito
                </a>
            </div>
        @else
            <div class="mt-8">
                <a href="{{ route('login') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-lg shadow-lg inline-flex items-center gap-3 text-lg font-semibold transition-all duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    Inicia sesión para comprar
                </a>
            </div>
        @endauth

        <!-- BLOQUE DE IA -->
        <div class="mt-12 bg-gray-900/80 backdrop-blur-xl border border-gray-700 p-6 rounded-2xl shadow-xl">
            <h2 class="text-2xl font-bold mb-4 text-green-300">🤖 Preguntar a la IA sobre este producto</h2>

            <p class="leading-relaxed text-gray-200 whitespace-pre-line">
                {{ $aiAnalysis }}
            </p>
        </div>

        <!-- Report Product Button -->
        @auth
            @if(Auth::user()->role !== 'admin' && $product->user_id !== Auth::id())
                <div class="mt-6">
                    <button onclick="openProductReportModal()"
                        class="text-red-400 hover:text-red-300 text-sm flex items-center transition">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        Reportar este producto
                    </button>
                </div>
            @endif
        @endauth

    </div>

    <!-- Product Report Modal -->
    @auth
        <div id="productReportModal"
            class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
            <div class="bg-gray-800 rounded-xl shadow-2xl max-w-md w-full p-6 border border-gray-700">
                <h3 class="text-xl font-bold text-white mb-4 flex items-center">
                    <svg class="w-6 h-6 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    Reportar Producto
                </h3>

                <p class="text-gray-400 text-sm mb-4">
                    Estás por reportar <span class="text-white font-semibold">{{ $product->name }}</span>.
                    Si el producto viola nuestras políticas, será retirado de la tienda.
                </p>

                <form id="productReportForm">
                    @csrf
                    <input type="hidden" name="type" value="product">
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <div class="mb-4">
                        <label class="block text-gray-400 text-sm font-medium mb-2">Motivo del reporte *</label>
                        <select name="reason" id="product_report_reason"
                            class="w-full bg-gray-700 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-red-500 focus:outline-none"
                            required>
                            <option value="">Selecciona un motivo</option>
                            <option value="Producto falso o falsificado">Producto falso o falsificado</option>
                            <option value="Contenido inapropiado">Contenido inapropiado</option>
                            <option value="Información engañosa">Información engañosa</option>
                            <option value="Precio abusivo">Precio abusivo</option>
                            <option value="Producto prohibido">Producto prohibido o ilegal</option>
                            <option value="Spam o duplicado">Spam o publicación duplicada</option>
                            <option value="Otro">Otro motivo</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-400 text-sm font-medium mb-2">Descripción *</label>
                        <textarea name="description" id="product_report_description" rows="3"
                            class="w-full bg-gray-700 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-red-500 focus:outline-none"
                            placeholder="Describe por qué reportas este producto..." required minlength="10"
                            maxlength="1000"></textarea>
                    </div>

                    <div id="productReportError"
                        class="hidden mb-4 bg-red-600/20 border border-red-500 text-red-300 px-4 py-2 rounded-lg text-sm"></div>
                    <div id="productReportSuccess"
                        class="hidden mb-4 bg-green-600/20 border border-green-500 text-green-300 px-4 py-2 rounded-lg text-sm">
                    </div>

                    <div class="flex gap-3">
                        <button type="button" onclick="closeProductReportModal()"
                            class="flex-1 bg-gray-600 hover:bg-gray-500 text-white py-3 rounded-lg font-semibold transition">
                            Cancelar
                        </button>
                        <button type="submit" id="submitProductReportBtn"
                            class="flex-1 bg-red-600 hover:bg-red-700 text-white py-3 rounded-lg font-semibold transition">
                            Enviar Reporte
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function openProductReportModal() {
                document.getElementById('productReportModal').classList.remove('hidden');
            }

            function closeProductReportModal() {
                document.getElementById('productReportModal').classList.add('hidden');
                document.getElementById('productReportForm').reset();
                document.getElementById('productReportError').classList.add('hidden');
                document.getElementById('productReportSuccess').classList.add('hidden');
            }

            document.getElementById('productReportForm')?.addEventListener('submit', async function (e) {
                e.preventDefault();

                const formData = new FormData(this);
                const submitBtn = document.getElementById('submitProductReportBtn');
                const errorDiv = document.getElementById('productReportError');
                const successDiv = document.getElementById('productReportSuccess');

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
                        setTimeout(() => closeProductReportModal(), 2000);
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

            document.getElementById('productReportModal')?.addEventListener('click', function (e) {
                if (e.target === this) closeProductReportModal();
            });
        </script>
    @endauth
@endsection