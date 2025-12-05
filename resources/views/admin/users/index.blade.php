@extends('layouts.app-simple')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-900 via-teal-800 to-blue-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-white">Gestión de Usuarios</h1>
                <p class="text-gray-300">Administra los roles de los usuarios</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-lg transition">
                ← Volver al Dashboard
            </a>
        </div>

        @if(session('success'))
        <div class="bg-green-500/20 border border-green-500 text-green-300 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-500/20 border border-red-500 text-red-300 px-4 py-3 rounded-lg mb-6">
            {{ session('error') }}
        </div>
        @endif

        <!-- Tabla de Usuarios -->
        <div class="bg-white/10 backdrop-blur rounded-xl border border-white/20 overflow-hidden">
            <table class="w-full">
                <thead class="bg-white/5">
                    <tr class="text-gray-300 text-left">
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Nombre</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Rol Actual</th>
                        <th class="px-6 py-4">Cambiar Rol</th>
                        <th class="px-6 py-4">Registrado</th>
                        <th class="px-6 py-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr class="border-b border-white/5 text-gray-200 hover:bg-white/5">
                        <td class="px-6 py-4">{{ $user->id }}</td>
                        <td class="px-6 py-4 font-medium">{{ $user->name }}</td>
                        <td class="px-6 py-4">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                @if($user->role === 'admin') bg-red-500/30 text-red-300
                                @elseif($user->role === 'vendedor') bg-green-500/30 text-green-300
                                @else bg-blue-500/30 text-blue-300 @endif">
                                {{ ucfirst($user->role ?? 'cliente') }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.updateRole', $user->id) }}" method="POST" class="flex gap-2">
                                @csrf
                                @method('PUT')
                                <select name="role" class="bg-gray-800 text-white border border-gray-600 rounded px-2 py-1 text-sm">
                                    <option value="cliente" {{ $user->role === 'cliente' ? 'selected' : '' }}>Cliente</option>
                                    <option value="vendedor" {{ $user->role === 'vendedor' ? 'selected' : '' }}>Vendedor</option>
                                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                                    Guardar
                                </button>
                            </form>
                            @else
                            <span class="text-gray-500 text-sm">Tu cuenta</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-400 text-sm">{{ $user->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4">
                            @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" 
                                  onsubmit="return confirm('¿Estás seguro de eliminar este usuario?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                                    Eliminar
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-400">No hay usuarios registrados</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="mt-6">
            {{ $users->links() }}
        </div>

    </div>
</div>
@endsection