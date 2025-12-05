@extends('layouts.app-simple')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-black to-gray-900 py-10">

    <div class="max-w-4xl mx-auto px-6 text-white">

        <!-- Volver -->
        <a href="{{ route('products.public.index') }}" 
           class="text-gray-400 hover:text-green-400 mb-6 inline-flex items-center transition">
            ← Volver al catálogo
        </a>

        <!-- CARD PRINCIPAL -->
        <div class="bg-gray-800/50 rounded-2xl p-8 shadow-xl border border-white/10 backdrop-blur-xl">

            <!-- Imagen -->
            <div class="w-full h-80 bg-gray-900/70 rounded-xl overflow-hidden mb-8 flex items-center justify-center shadow-inner">
                <img src="{{ $product->image_url }}"
                     alt="{{ $product->name }}"
                     class="max-h-80 object-contain drop-shadow-[0_0_20px_rgba(0,255,170,0.3)]">
            </div>

            <!-- Título -->
            <h1 class="text-4xl font-extrabold mb-4 text-green-400">
                {{ $product->name }}
            </h1>

            <!-- Descripción -->
            <p class="text-gray-300 mb-6 leading-relaxed">
                {{ $product->description ?? 'Sin descripción disponible.' }}
            </p>

            <!-- Precio -->
            <div class="mb-6">
                <p class="text-gray-400 text-sm">Precio</p>
                <p class="text-3xl font-bold text-green-400 drop-shadow-lg">
                    S/. {{ number_format($product->price, 2) }}
                </p>
            </div>

            <!-- Categoría -->
            <div class="mb-8">
                <p class="text-gray-400 text-sm">Categoría</p>
                <span class="bg-green-500/20 text-green-300 px-3 py-1 rounded-lg text-sm font-medium">
                    {{ $product->category->name }}
                </span>
            </div>

        </div>

    </div>
</div>


<!-- BLOQUE IA PRO -->
<div class="mt-10 bg-gray-800/50 border border-green-500/30 rounded-2xl p-6 shadow-xl
            backdrop-blur-xl animate-fadeIn">

    <h2 class="text-2xl font-bold text-white mb-4 flex items-center gap-2">
        🤖 Asistente IA para este producto
    </h2>

    <p class="text-gray-300 mb-6">
        Haz preguntas, analiza el producto o solicita una explicación detallada.
    </p>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

        <button onclick="analyzeProduct({{ $product->id }})"
            class="bg-gradient-to-r from-green-500 to-teal-600 px-4 py-3 rounded-xl text-white
                   font-semibold hover:scale-105 transition shadow-lg hover:shadow-green-500/40">
            🔍 Analizar Producto con IA
        </button>

        <button onclick="explainProduct({{ $product->id }})"
            class="bg-gradient-to-r from-blue-500 to-cyan-600 px-4 py-3 rounded-xl text-white
                   font-semibold hover:scale-105 transition shadow-lg hover:shadow-cyan-500/40">
            📘 Explicación Automática
        </button>

        <button onclick="visionProduct({{ $product->id }})"
            class="bg-gradient-to-r from-purple-500 to-fuchsia-600 px-4 py-3 rounded-xl text-white
                   font-semibold hover:scale-105 transition shadow-lg hover:shadow-fuchsia-500/40">
            👁️ Analizar Imagen (IA Visión)
        </button>

        <button onclick="toggleAssistant()"
            class="bg-gradient-to-r from-yellow-500 to-orange-600 px-4 py-3 rounded-xl text-white
                   font-semibold hover:scale-105 transition shadow-lg hover:shadow-orange-500/40">
            💬 Preguntar sobre el producto
        </button>

    </div>

    <!-- WhatsApp directo -->
    <button onclick="sendToWhatsApp('{{ $product->name }}')"
        class="bg-green-600/40 hover:bg-green-600/60 border border-green-500/40
               mt-4 w-full py-2 rounded-xl text-green-200 font-semibold">
        📲 Consultar por WhatsApp
    </button>

    <div id="ia-response"
        class="mt-6 hidden bg-gray-900/70 p-4 rounded-xl border border-gray-700 text-gray-300">
    </div>

</div>


<!-- IA SCRIPT -->
<script>
function showIAResponse(text) {
    const box = document.getElementById("ia-response");
    box.classList.remove("hidden");
    box.innerHTML = `<p class='animate-pulse text-gray-300'>${text}</p>`;
}

async function analyzeProduct(id) {
    showIAResponse("🔍 Analizando producto con IA...");
    const res = await fetch(`/ai/product/${id}`);
    const data = await res.json();
    showIAResponse(data.reply);
}

async function explainProduct(id) {
    showIAResponse("📘 Generando explicación...");
    const res = await fetch(`/ai/product/${id}`);
    const data = await res.json();
    showIAResponse("📘 Explicación del producto:<br><br>" + data.reply);
}

async function visionProduct(id) {
    showIAResponse("👁️ Analizando imagen con IA Visión...");
    const res = await fetch(`/ai/product/${id}`);
    const data = await res.json();
    showIAResponse("👁️ IA Visión:<br><br>" + data.reply);
}

function sendToWhatsApp(productName) {
    const message = encodeURIComponent(
        `Hola, estoy en ShopSmart-IA y quiero más información sobre el producto: ${productName}`
    );
    window.open(`https://wa.me/51926679050?text=${message}`, '_blank');
}
</script>
@endsection
