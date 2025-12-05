@extends('layouts.app-simple')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#0a1b1f] via-[#0c2a2e] to-[#0b1f25] pb-16">

    <!-- HEADER -->
    <div class="max-w-6xl mx-auto px-6 pt-10 pb-6">
        <h1 class="text-4xl font-bold text-white drop-shadow-[0_0_15px_rgba(0,255,170,0.8)]">
            Marketplace <span class="text-green-400">IA</span>
        </h1>
        <p class="text-gray-300">Explora productos recomendados con inteligencia artificial</p>
    </div>

    <!-- BUSCADOR -->
    <div class="max-w-6xl mx-auto px-6 mb-8">
        <form method="GET" class="flex gap-4">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}"
                placeholder="Buscar producto..."
                class="flex-1 bg-gray-900/50 border border-gray-700 text-white rounded-xl px-4 py-3 
                       focus:outline-none focus:border-green-400 shadow-[0_0_10px_rgba(0,255,170,0.2)]"
            >
            <button 
                class="bg-green-600 hover:bg-green-700 px-5 py-3 rounded-xl text-white 
                       shadow-[0_0_15px_rgba(0,255,170,0.6)] hover:shadow-[0_0_20px_rgba(0,255,170,0.9)] transition">
                Buscar
            </button>
        </form>
    </div>

    <!-- SKELETON LOADING (se muestra si no hay productos aún) -->
    @if ($products->isEmpty())
    <div id="skeletonLoader" class="max-w-6xl mx-auto px-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @for ($i = 0; $i < 6; $i++)
        <div class="animate-pulse bg-white/5 rounded-2xl p-4 border border-white/10">
            <div class="w-full h-44 bg-gray-700 rounded-lg mb-4"></div>
            <div class="h-4 bg-gray-700 rounded mb-2"></div>
            <div class="h-4 w-1/2 bg-gray-700 rounded"></div>
        </div>
        @endfor
    </div>
    @endif

    <!-- GRID DE PRODUCTOS -->
    <div class="max-w-6xl mx-auto px-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">

        @foreach ($products as $product)
        <div class="group relative bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl overflow-hidden
                    hover:border-green-500/40 shadow-xl transition-all duration-300 hover:-translate-y-1">

            <!-- Neon glow -->
            <div class="absolute inset-0 pointer-events-none opacity-0 group-hover:opacity-40 transition duration-300 
                        shadow-[0_0_25px_5px_rgba(0,255,170,0.6)]"></div>

            <!-- Imagen -->
            <a href="{{ route('products.public.show', $product->id) }}">
                <div class="h-56 bg-gray-900 overflow-hidden">
                    <img 
                        src="{{ $product->image_url }}"
                        class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                    >
                </div>
            </a>

            <!-- Info -->
            <div class="p-5 relative">

                <h2 class="text-xl font-bold text-white group-hover:text-green-400 transition">
                    {{ $product->name }}
                </h2>

                <p class="text-gray-400 text-sm mt-1 line-clamp-2">
                    {{ $product->description }}
                </p>

                <div class="mt-4 flex justify-between items-center">
                    <span class="text-green-400 font-semibold text-lg">
                        S/ {{ number_format($product->price, 2) }}
                    </span>
                    <span class="text-gray-400 text-xs">{{ $product->stock }} disponibles</span>
                </div>

                <!-- BOTÓN IA -->
                <button 
                    onclick="askAI('{{ $product->id }}', '{{ $product->name }}')"
                    class="mt-4 w-full bg-green-600/30 hover:bg-green-600/50 border border-green-400/40 
                           text-green-300 px-3 py-2 rounded-xl text-sm transition shadow-[0_0_10px_rgba(0,255,170,0.3)]">
                    🤖 Preguntar a la IA
                </button>

            </div>
        </div>
        @endforeach

    </div>

    <!-- PAGINACIÓN -->
    <div class="max-w-6xl mx-auto px-6 mt-10">
        {{ $products->links('pagination::tailwind') }}
    </div>

</div>

<!-- SCRIPT IA -->
<script>
function askAI(id, name) {

    // Abre la ventana del asistente
    toggleAssistant();

    // Muestra el mensaje que mandó el usuario
    const msg = document.getElementById('assistantMessages');
    msg.innerHTML += `
        <div class='mt-2 text-right'>
            <span class='inline-block bg-green-600/40 text-white px-3 py-2 rounded-xl'>
                ¿Qué opinas del producto "${name}"?
            </span>
        </div>
    `;

    // Scroll automático
    msg.scrollTop = msg.scrollHeight;

    // Aquí enviamos el mensaje a tu backend IA o ManyChat (C+D)
    // Próximo paso: conectar ManyChat
}
</script>

@endsection
