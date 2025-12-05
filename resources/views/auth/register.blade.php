<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-900 via-teal-800 to-blue-900 py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-lg">
            <!-- Card de Registro -->
            <div class="bg-white rounded-2xl shadow-2xl p-8">
                <!-- Logo y Título -->
                <div class="text-center mb-8">
                    <div class="bg-green-600 w-16 h-16 rounded-xl mx-auto mb-4 flex items-center justify-center shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900">Crear Cuenta</h2>
                    <p class="text-gray-600 mt-2">Únete a ShopSmart IA</p>
                </div>

                <form method="POST" action="{{ route('register') }}" x-data="{ role: 'cliente' }">
                    @csrf

                    <!-- Tabs de tipo de cuenta -->
                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-3">Tipo de cuenta</label>
                        <div class="grid grid-cols-2 gap-3">
                            <button type="button"
                                    @click="role = 'cliente'"
                                    :class="role === 'cliente' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'"
                                    class="flex items-center justify-center px-4 py-3 rounded-lg cursor-pointer font-semibold shadow-md transition">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                                Cliente
                            </button>
                            <button type="button"
                                    @click="role = 'vendedor'"
                                    :class="role === 'vendedor' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'"
                                    class="flex items-center justify-center px-4 py-3 rounded-lg cursor-pointer font-semibold shadow-md transition">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                                </svg>
                                Vendedor
                            </button>
                        </div>
                        <input type="hidden" name="role" :value="role">
                    </div>

                    <!-- Name -->
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 font-semibold mb-2">Nombre completo</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent bg-gray-50"
                               placeholder="Tu nombre completo">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 font-semibold mb-2">Correo electrónico</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent bg-gray-50"
                               placeholder="tu@email.com">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label for="password" class="block text-gray-700 font-semibold mb-2">Contraseña</label>
                        <input id="password" type="password" name="password" required
                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent bg-gray-50"
                               placeholder="Mínimo 8 caracteres">
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-gray-700 font-semibold mb-2">Confirmar Contraseña</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required
                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent bg-gray-50"
                               placeholder="Repite tu contraseña">
                    </div>

                    <!-- Terms -->
                    <div class="mb-6">
                        <p class="text-xs text-gray-600">
                            Al registrarte, aceptas nuestros <a href="#" class="text-green-600 hover:underline font-medium">Términos de Servicio</a> y <a href="#" class="text-green-600 hover:underline font-medium">Política de Privacidad</a>
                        </p>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200 shadow-lg transform hover:scale-[1.02]">
                        Crear Cuenta
                    </button>

                    <!-- Login Link -->
                    <div class="mt-6 text-center">
                        <p class="text-gray-600">
                            ¿Ya tienes cuenta?
                            <a href="{{ route('login') }}" class="text-green-600 hover:text-green-700 font-semibold">
                                Iniciar Sesión
                            </a>
                        </p>
                    </div>
                </form>
            </div>

            <!-- Back to Home -->
            <div class="text-center mt-6">
                <a href="{{ route('home') }}" class="text-white hover:text-gray-200 text-sm font-medium flex items-center justify-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Volver al inicio
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
