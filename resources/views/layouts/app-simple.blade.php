<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'ShopSmart-IA') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-900 text-white">

    <!-- NAVBAR -->
    <nav class="bg-gray-800 border-b border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">

                <!-- Logo -->
                <a href="{{ route('home') }}" class="text-white text-xl font-bold">
                    ShopSmart <span class="text-green-400">IA</span>
                </a>

                <!-- Menú lateral -->
                <div class="flex items-center space-x-4">

                    <a href="{{ route('products.public.index') }}" class="text-gray-300 hover:text-white">
                        Productos
                    </a>

                    <a href="{{ route('categories.public.index') }}" class="text-gray-300 hover:text-white">
                        Categorías
                    </a>

                    <a href="{{ route('cart.index') }}" class="text-gray-300 hover:text-white">
                        🛒
                    </a>

                    <!-- Si está logueado -->
                    @auth
                        <span class="text-gray-300">{{ Auth::user()->name }}</span>

                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button class="text-red-400 hover:text-red-300">Salir</button>
                        </form>
                    @endauth

                    <!-- Invitado -->
                    @guest
                        <a href="{{ route('login') }}" class="text-gray-300 hover:text-white">
                            Iniciar sesión
                        </a>
                    @endguest

                </div>
            </div>
        </div>
    </nav>

    <!-- CONTENIDO -->
    <main class="max-w-7xl mx-auto py-10 px-4">
        @yield('content')
    </main>


    <!-- ===================== ASISTENTE IA ===================== -->

    <!-- Botón flotante -->
    <button onclick="toggleAssistant()"
        class="fixed bottom-6 right-6 w-16 h-16 rounded-full bg-gradient-to-br 
               from-green-500 to-teal-600 shadow-[0_0_20px_rgba(0,255,170,0.5)]
               hover:shadow-[0_0_25px_rgba(0,255,170,0.8)]
               flex items-center justify-center text-white text-3xl 
               transition-all z-50">
        💬
    </button>

    <!-- Ventana IA -->
    <div id="assistantWindow"
        class="hidden fixed bottom-24 right-6 w-96 h-[480px] bg-gray-900/95 border border-green-400/30 
               rounded-2xl shadow-2xl backdrop-blur-xl z-50 overflow-hidden">

        <!-- Header -->
        <div class="flex justify-between items-center px-4 py-3 bg-gray-800">
            <h2 class="font-bold text-white text-lg">Asistente IA</h2>
            <button onclick="toggleAssistant()" class="text-red-400 hover:text-red-300 text-2xl">×</button>
        </div>

        <!-- Mensajes -->
        <div id="assistantMessages" class="p-4 h-[330px] overflow-y-auto text-gray-200 text-sm space-y-3">
            <div class="text-gray-400">👋 Hola, soy tu asistente IA. ¿En qué puedo ayudarte hoy?</div>
        </div>

        <!-- Input -->
        <div class="bg-gray-800 p-3 flex gap-2">
            <input id="assistantInput"
                class="flex-1 bg-gray-700 border border-gray-600 rounded-xl px-3 py-2 text-white text-sm"
                placeholder="Escribe aquí..." />

            <button onclick="sendAssistantMessage()"
                class="bg-green-600 hover:bg-green-700 px-4 py-2 rounded-xl text-white text-sm">
                Enviar
            </button>
        </div>
    </div>


    <!-- ===================== SCRIPT IA ===================== -->
    <script>
        function toggleAssistant() {
            document.getElementById("assistantWindow").classList.toggle("hidden");
        }

        async function sendAssistantMessage() {
            const input = document.getElementById("assistantInput");
            const messages = document.getElementById("assistantMessages");
            let text = input.value.trim();
            if (!text) return;

            // Mostrar mensaje del usuario
            messages.innerHTML += `
                <div class='text-right'>
                    <span class='inline-block bg-green-600/40 px-3 py-2 rounded-xl text-white'>
                        ${text}
                    </span>
                </div>
            `;

            input.value = "";
            messages.scrollTop = messages.scrollHeight;

            // Indicador IA
            const loadingId = Date.now();
            messages.innerHTML += `
                <div id="${loadingId}" class='text-gray-400'>⏳ Procesando...</div>
            `;
            messages.scrollTop = messages.scrollHeight;

            // Llamada real a Laravel
            const response = await fetch("/ai/chat", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ message: text })
            });

            const data = await response.json();

            // Remover loading
            document.getElementById(loadingId)?.remove();

            // Respuesta IA real
            messages.innerHTML += `
                <div>
                    <span class='inline-block bg-gray-700 px-3 py-2 rounded-xl text-gray-200'>
                        ${data.reply}
                    </span>
                </div>
            `;
            messages.scrollTop = messages.scrollHeight;
        }
    </script>

</body>
</html>
