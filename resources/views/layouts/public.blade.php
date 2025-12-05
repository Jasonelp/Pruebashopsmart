<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'ShopSmart IA')</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gradient-to-br from-green-900 via-teal-800 to-blue-900 min-h-screen text-white">

    <!-- NAVBAR -->
    <nav class="bg-gray-900/80 backdrop-blur-sm shadow-lg sticky top-0 z-50 border-b border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <div class="bg-green-600 rounded-lg p-2 mr-3">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                            </svg>
                        </div>
                        <a href="{{ route('home') }}" class="text-white text-2xl font-bold">
                            ShopSmart <span class="text-green-400">IA</span>
                        </a>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-4 items-center">
                        <a href="{{ route('products.public.index') }}"
                            class="text-gray-300 hover:text-white hover:bg-gray-800 px-3 py-2 rounded-md text-sm font-medium transition">
                            Productos
                        </a>
                        <a href="{{ route('categories.public.index') }}"
                            class="text-gray-300 hover:text-white hover:bg-gray-800 px-3 py-2 rounded-md text-sm font-medium transition">
                            Categorías
                        </a>
                    </div>
                </div>

                <!-- Búsqueda -->
                <div class="hidden md:flex flex-1 items-center justify-center px-4 lg:px-8">
                    <div class="w-full max-w-lg">
                        <form class="flex" action="{{ route('products.public.index') }}" method="GET">
                            <input
                                class="form-input bg-gray-800 text-white block w-full rounded-l-lg border-0 py-2 px-4 focus:ring-2 focus:ring-green-500 focus:outline-none placeholder-gray-400"
                                type="search" name="search" placeholder="Busca productos con IA...">
                            <button type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white font-semibold px-5 rounded-r-lg transition">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Iconos de usuario -->
                <div class="flex items-center space-x-4">
                    @auth
                        <!-- Carrito (solo para usuarios autenticados) -->
                        <a href="{{ route('cart.index') }}" class="text-gray-300 hover:text-white transition relative">
                            <span class="sr-only">Carrito</span>
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span
                                class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold">
                                {{ session('cart') ? count(session('cart')) : 0 }}
                            </span>
                        </a>
                        <!-- Dashboard -->
                        <a href="{{ route('dashboard') }}"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                            Dashboard
                        </a>
                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="text-gray-300 hover:text-white hover:bg-gray-800 px-3 py-2 rounded-md text-sm font-medium transition">
                                Cerrar Sesión
                            </button>
                        </form>
                    @else
                        <!-- Si no está autenticado, mostrar login -->
                        <a href="{{ route('login') }}"
                            class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition">
                            Iniciar Sesión
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- BARRA DE CATEGORÍAS -->
        <div class="border-t border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center space-x-6 py-3 overflow-x-auto">
                    <span class="text-gray-400 text-sm font-semibold whitespace-nowrap">Categorías:</span>
                    <a href="{{ route('products.public.index') }}"
                        class="text-gray-300 hover:text-white text-sm whitespace-nowrap transition">
                        Todas
                    </a>
                    @php
                        $categories = \App\Models\Category::all();
                    @endphp
                    @foreach($categories as $category)
                        <a href="{{ route('products.public.index', ['category' => $category->id]) }}"
                            class="text-gray-300 hover:text-white text-sm whitespace-nowrap transition">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main>
        <!-- Notificaciones Toast -->
        @if(session('success'))
            <div id="toast-notification"
                class="fixed top-20 right-6 z-50 bg-green-600 text-white px-6 py-4 rounded-lg shadow-2xl flex items-center gap-3 transform transition-all duration-300 ease-in-out">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-semibold">{{ session('success') }}</span>
                <button onclick="closeToast()" class="ml-4 hover:bg-green-700 rounded-full p-1 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div id="toast-notification"
                class="fixed top-20 right-6 z-50 bg-red-600 text-white px-6 py-4 rounded-lg shadow-2xl flex items-center gap-3 transform transition-all duration-300 ease-in-out">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-semibold">{{ session('error') }}</span>
                <button onclick="closeToast()" class="ml-4 hover:bg-red-700 rounded-full p-1 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="bg-gray-900/80 backdrop-blur-sm mt-16 py-8 border-t border-gray-700">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <div class="flex justify-center space-x-6 mb-4">
                <a href="#" class="text-gray-400 hover:text-white transition">Términos</a>
                <a href="#" class="text-gray-400 hover:text-white transition">Privacidad</a>
                <a href="#" class="text-gray-400 hover:text-white transition">Contacto</a>
            </div>
            <p class="text-gray-400">&copy; {{ date('Y') }} ShopSmart IA. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- CHATBOT FLOTANTE -->
    <div class="fixed bottom-6 right-6 z-50">
        <button id="chatbot-button"
            class="bg-gradient-to-r from-green-500 to-blue-500 hover:from-green-600 hover:to-blue-600 text-white rounded-full p-4 shadow-2xl transition-all duration-300 transform hover:scale-110 focus:outline-none focus:ring-4 focus:ring-green-300">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
            </svg>
        </button>

        <!-- Panel del chatbot (oculto por defecto) -->
        <div id="chatbot-panel"
            class="hidden absolute bottom-20 right-0 w-80 bg-gray-800 rounded-lg shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-500 to-blue-500 p-4 flex justify-between items-center">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-white mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z" />
                        <path
                            d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z" />
                    </svg>
                    <h3 class="font-bold text-white">Asistente IA</h3>
                </div>
                <button id="close-chatbot" class="text-white hover:text-gray-200 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Contenido del chat -->
            <div class="p-4 h-96 overflow-y-auto bg-gray-900">
                <div class="space-y-4">
                    <!-- Mensaje del bot -->
                    <div class="flex items-start">
                        <div class="bg-gray-700 rounded-lg p-3 max-w-xs">
                            <p class="text-white text-sm">¡Hola! Soy tu asistente virtual. ¿En qué puedo ayudarte hoy?
                            </p>
                        </div>
                    </div>

                    <!-- Opciones rápidas -->
                    <div class="flex flex-col space-y-2">
                        <a href="{{ route('products.public.index') }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white text-center px-4 py-2 rounded-lg text-sm transition">
                            Ver Productos
                        </a>
                        @auth
                            <a href="{{ route('orders.my-orders') }}"
                                class="bg-green-600 hover:bg-green-700 text-white text-center px-4 py-2 rounded-lg text-sm transition">
                                Mis Pedidos
                            </a>
                            <a href="{{ route('cart.index') }}"
                                class="bg-purple-600 hover:bg-purple-700 text-white text-center px-4 py-2 rounded-lg text-sm transition">
                                Ver Carrito
                            </a>
                        @endauth
                        <button
                            class="bg-gray-700 hover:bg-gray-600 text-white text-center px-4 py-2 rounded-lg text-sm transition"
                            onclick="alert('Métodos de pago: Tarjeta, Yape, PagoEfectivo')">
                            Métodos de Pago
                        </button>
                        <button
                            class="bg-gray-700 hover:bg-gray-600 text-white text-center px-4 py-2 rounded-lg text-sm transition"
                            onclick="alert('Envíos a todo el Perú. 24-48 horas en Lima.')">
                            Información de Envío
                        </button>
                    </div>
                </div>
            </div>

            <!-- Footer con powered by -->
            <div class="bg-gray-800 p-3 text-center border-t border-gray-700">
                <p class="text-xs text-gray-400">Powered by ShopSmart IA</p>
            </div>
        </div>
    </div>

    <!-- Script del chatbot -->
    <script>
        const chatbotButton = document.getElementById('chatbot-button');
        const chatbotPanel = document.getElementById('chatbot-panel');
        const closeChatbot = document.getElementById('close-chatbot');

        chatbotButton.addEventListener('click', () => {
            chatbotPanel.classList.toggle('hidden');
        });

        closeChatbot.addEventListener('click', () => {
            chatbotPanel.classList.add('hidden');
        });

        // Cerrar al hacer clic fuera
        document.addEventListener('click', (e) => {
            if (!chatbotButton.contains(e.target) && !chatbotPanel.contains(e.target)) {
                chatbotPanel.classList.add('hidden');
            }
        });

        // Toast notification auto-dismiss
        function closeToast() {
            const toast = document.getElementById('toast-notification');
            if (toast) {
                toast.style.transform = 'translateX(400px)';
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }
        }

        // Auto-dismiss after 4 seconds
        const toast = document.getElementById('toast-notification');
        if (toast) {
            setTimeout(() => closeToast(), 4000);
        }
    </script>

</body>

</html>