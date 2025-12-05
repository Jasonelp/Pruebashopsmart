<x-guest-layout>
    <div
        class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-900 via-teal-800 to-blue-900 py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-lg">
            <!-- Card de Login -->
            <div class="bg-white rounded-2xl shadow-2xl p-8">
                <!-- Logo y Título -->
                <div class="text-center mb-8">
                    <div
                        class="bg-green-600 w-16 h-16 rounded-xl mx-auto mb-4 flex items-center justify-center shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900">ShopSmart IA</h2>
                    <p class="text-gray-600 mt-2">Tu marketplace inteligente</p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <!-- Error Message (for suspended users) -->
                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="font-semibold">Cuenta Suspendida</span>
                        </div>
                        <p class="mt-1 text-sm">{{ session('error') }}</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="mb-4">
                        <x-input-label for="email" :value="__('Correo electrónico')"
                            class="text-gray-700 font-semibold mb-2" />
                        <x-text-input id="email"
                            class="block mt-1 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent bg-gray-50"
                            type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                            placeholder="tu@email.com" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <x-input-label for="password" :value="__('Contraseña')"
                            class="text-gray-700 font-semibold mb-2" />
                        <x-text-input id="password"
                            class="block mt-1 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent bg-gray-50"
                            type="password" name="password" required autocomplete="current-password"
                            placeholder="••••••••" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between mb-6">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox"
                                class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500"
                                name="remember">
                            <span class="ml-2 text-sm text-gray-600">Recordarme</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-sm text-green-600 hover:text-green-700 font-medium"
                                href="{{ route('password.request') }}">
                                ¿Olvidaste tu contraseña?
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200 shadow-lg transform hover:scale-[1.02]">
                        Iniciar Sesión
                    </button>

                    <!-- Register Link -->
                    <div class="mt-6 text-center">
                        <p class="text-gray-600">
                            ¿No tienes cuenta?
                            <a href="{{ route('register') }}" class="text-green-600 hover:text-green-700 font-semibold">
                                Regístrate
                            </a>
                        </p>
                    </div>
                </form>

                <!-- Demo Credentials -->
                <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <p class="text-sm text-blue-800 font-semibold mb-2">🚀 Prueba rápida (demo):</p>
                    <p class="text-xs text-blue-700">Email: demo@shopsmart.com</p>
                    <p class="text-xs text-blue-700">Contraseña: demo123</p>
                </div>
            </div>

            <!-- Back to Home -->
            <div class="text-center mt-6">
                <a href="{{ route('home') }}"
                    class="text-white hover:text-gray-200 text-sm font-medium flex items-center justify-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Volver al inicio
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>