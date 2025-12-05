<div 
    x-data="chatIA()" 
    class="fixed bottom-6 right-6 z-50">

    <!-- BURBUJA -->
    <button 
        @click="open = !open"
        class="bg-green-600 hover:bg-green-700 text-white w-14 h-14 rounded-full shadow-2xl flex items-center justify-center text-3xl transition">
        🤖
    </button>

    <!-- PANEL -->
    <div 
        x-show="open"
        x-transition
        class="w-80 h-[480px] bg-gray-900 border border-gray-700 rounded-2xl shadow-2xl mt-4 flex flex-col overflow-hidden">

        <!-- HEADER -->
        <div class="bg-gray-800 p-4 text-white font-bold text-lg border-b border-gray-700">
            Asistente IA
        </div>

        <!-- MENSAJES -->
        <div id="chatMessages" class="flex-1 p-4 space-y-3 overflow-y-auto text-sm text-white">

            <template x-for="msg in messages">
                <div :class="msg.from === 'user' ? 'text-right' : 'text-left'">
                    <div 
                        class="inline-block px-3 py-2 rounded-xl"
                        :class="msg.from === 'user' 
                                ? 'bg-green-600 text-white' 
                                : 'bg-gray-700 text-gray-200'">
                        <span x-text="msg.text"></span>
                    </div>
                </div>
            </template>

            <!-- typing -->
            <div x-show="typing" class="text-gray-400 text-xs">
                IA está escribiendo...
            </div>

        </div>

        <!-- INPUT -->
        <div class="p-3 border-t border-gray-700 bg-gray-800">
            <input 
                x-model="input"
                @keydown.enter="sendMessage()"
                class="w-full bg-gray-700 text-white px-3 py-2 rounded-xl outline-none"
                placeholder="Escribe tu mensaje...">
        </div>
    </div>

</div>

<script>
function chatIA() {
    return {
        open: false,
        input: '',
        typing: false,
        messages: [],

        async sendMessage() {
            if (this.input.trim() === '') return;

            // Mensaje del usuario
            this.messages.push({ from: 'user', text: this.input });
            const userText = this.input;
            this.input = '';

            this.typing = true;

            try {
                const res = await fetch("{{ route('ai.chat') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ message: userText })
                });

                const data = await res.json();

                this.messages.push({ from: 'ia', text: data.reply });

            } catch (err) {
                this.messages.push({ from: 'ia', text: "⚠ Error al conectar con IA." });
            }

            this.typing = false;

            this.$nextTick(() => {
                let box = document.getElementById('chatMessages');
                box.scrollTop = box.scrollHeight;
            });
        }
    }
}
</script>
