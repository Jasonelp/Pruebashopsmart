@extends('layouts.public')

@section('title', 'Mi Perfil - ShopSmart IA')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-white mb-2">Perfil de Usuario</h1>
            <p class="text-gray-300">Actualiza la información de tu cuenta y contraseña</p>
        </div>

        <!-- Información del Perfil -->
        <div
            class="bg-gradient-to-br from-gray-800/80 to-gray-900/80 backdrop-blur-md rounded-2xl shadow-2xl border border-white/10 mb-6 overflow-hidden">
            <div class="p-8">
                <h2 class="text-2xl font-bold text-white mb-2">Información del Perfil</h2>
                <p class="text-gray-300 mb-6">Actualiza el nombre y correo electrónico de tu cuenta.</p>

                <!-- Formulario de Profile Information -->
                <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                    @csrf
                    @method('patch')

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Nombre</label>
                        <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus
                            autocomplete="name"
                            class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('name')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Correo Electrónico</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required
                            autocomplete="username"
                            class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('email')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Save Button -->
                    <div class="flex items-center gap-4">
                        <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition">
                            Guardar Cambios
                        </button>

                        @if (session('status') === 'profile-updated')
                            <p class="text-sm text-green-400">Guardado correctamente.</p>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Actualizar Contraseña -->
        <div
            class="bg-gradient-to-br from-gray-800/80 to-gray-900/80 backdrop-blur-md rounded-2xl shadow-2xl border border-white/10 mb-6 overflow-hidden">
            <div class="p-8">
                <h2 class="text-2xl font-bold text-white mb-2">Actualizar Contraseña</h2>
                <p class="text-gray-300 mb-6">Asegúrate de usar una contraseña larga y segura.</p>

                <!-- Formulario de Password -->
                <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                    @csrf
                    @method('put')

                    <!-- Current Password -->
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-300 mb-2">Contraseña
                            Actual</label>
                        <input id="current_password" name="current_password" type="password" autocomplete="current-password"
                            class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('current_password', 'updatePassword')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Nueva Contraseña</label>
                        <input id="password" name="password" type="password" autocomplete="new-password"
                            class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('password', 'updatePassword')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">Confirmar
                            Contraseña</label>
                        <input id="password_confirmation" name="password_confirmation" type="password"
                            autocomplete="new-password"
                            class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('password_confirmation', 'updatePassword')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Save Button -->
                    <div class="flex items-center gap-4">
                        <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition">
                            Actualizar Contraseña
                        </button>

                        @if (session('status') === 'password-updated')
                            <p class="text-sm text-green-400">Contraseña actualizada.</p>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Eliminar Cuenta -->
        <div
            class="bg-gradient-to-br from-red-900/50 to-red-800/50 backdrop-blur-md rounded-2xl shadow-2xl border border-red-500/30 overflow-hidden">
            <div class="p-8">
                <h2 class="text-2xl font-bold text-white mb-2">Eliminar Cuenta</h2>
                <p class="text-gray-300 mb-6">Una vez eliminada tu cuenta, todos tus datos serán borrados permanentemente.
                    Antes de eliminar tu cuenta, descarga cualquier información que desees conservar.</p>

                <!-- Formulario de Delete -->
                <form method="post" action="{{ route('profile.destroy') }}" class="space-y-6">
                    @csrf
                    @method('delete')

                    <button type="submit"
                        onclick="return confirm('¿Estás seguro de que deseas eliminar tu cuenta? Esta acción no se puede deshacer.')"
                        class="bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-6 rounded-lg transition">
                        Eliminar Cuenta
                    </button>
                </form>
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-8 text-center">
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center text-white hover:text-gray-200 font-medium transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Volver al Dashboard
            </a>
        </div>

    </div>
    </div>
@endsection