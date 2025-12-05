@extends('layouts.public')

@section('title', 'Reporte #{{ $report->id }} - Admin - ShopSmart IA')

@section('content')
    <div class="min-h-screen py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="mb-6">
                <a href="{{ route('admin.reports.index') }}"
                    class="text-blue-400 hover:text-blue-300 flex items-center mb-4">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Volver a reportes
                </a>
                <h1 class="text-3xl font-bold text-white">Reporte #{{ $report->id }}</h1>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-600/20 border border-green-500 text-green-300 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Report Details -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Main Info -->
                    <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
                        <div class="flex justify-between items-start mb-4">
                            <h2 class="text-xl font-bold text-white">Información del Reporte</h2>
                            <span class="px-3 py-1 rounded-full text-sm font-semibold
                                @if($report->status == 'pending') bg-yellow-500/30 text-yellow-300
                                @elseif($report->status == 'reviewed') bg-blue-500/30 text-blue-300
                                @elseif($report->status == 'resolved') bg-green-500/30 text-green-300
                                @else bg-gray-500/30 text-gray-300 @endif">
                                {{ ucfirst($report->status) }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <p class="text-gray-400 text-sm">Reportante</p>
                                <p class="text-white font-medium">{{ $report->reporter->name }}</p>
                                <p class="text-gray-400 text-xs">{{ $report->reporter->email }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-sm">Reportado</p>
                                <p
                                    class="text-white font-medium {{ $report->reported->isSuspended() ? 'text-red-400' : '' }}">
                                    {{ $report->reported->name }}
                                    @if($report->reported->isSuspended())
                                        <span class="text-xs bg-red-600 text-white px-2 py-0.5 rounded ml-1">SUSPENDIDO</span>
                                    @endif
                                </p>
                                <p class="text-gray-400 text-xs">{{ $report->reported->email }}</p>
                                <p class="text-gray-500 text-xs">Rol: {{ ucfirst($report->reported->role) }}</p>
                            </div>
                        </div>

                        <div class="mb-4">
                            <p class="text-gray-400 text-sm">Motivo</p>
                            <p class="text-white">{{ $report->reason }}</p>
                        </div>

                        <div class="mb-4">
                            <p class="text-gray-400 text-sm">Descripción</p>
                            <p class="text-white bg-gray-700/50 rounded-lg p-3 mt-1">{{ $report->description }}</p>
                        </div>

                        @if($report->order)
                            <div class="mb-4 bg-blue-900/20 border border-blue-700 rounded-lg p-4">
                                <p class="text-blue-300 text-sm font-semibold mb-2">Pedido relacionado #{{ $report->order->id }}
                                </p>
                                <p class="text-gray-300 text-sm">Total: S/ {{ number_format($report->order->total, 2) }}</p>
                                <p class="text-gray-300 text-sm">Estado: {{ ucfirst($report->order->status) }}</p>
                            </div>
                        @endif

                        <div class="text-gray-400 text-xs">
                            Creado: {{ $report->created_at->format('d/m/Y H:i') }}
                            @if($report->reviewed_at)
                                | Revisado: {{ $report->reviewed_at->format('d/m/Y H:i') }} por
                                {{ $report->reviewer->name ?? 'N/A' }}
                            @endif
                        </div>
                    </div>

                    <!-- Update Status Form -->
                    <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
                        <h3 class="text-lg font-bold text-white mb-4">Actualizar Estado</h3>

                        <form action="{{ route('admin.reports.update', $report->id) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <div class="mb-4">
                                <label class="block text-gray-400 text-sm mb-2">Estado</label>
                                <select name="status" class="w-full bg-gray-700 text-white rounded-lg px-4 py-3">
                                    <option value="reviewed" {{ $report->status == 'reviewed' ? 'selected' : '' }}>Revisado
                                    </option>
                                    <option value="resolved" {{ $report->status == 'resolved' ? 'selected' : '' }}>Resuelto
                                    </option>
                                    <option value="dismissed" {{ $report->status == 'dismissed' ? 'selected' : '' }}>
                                        Descartado</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-400 text-sm mb-2">Notas del administrador</label>
                                <textarea name="admin_notes" rows="3"
                                    class="w-full bg-gray-700 text-white rounded-lg px-4 py-3"
                                    placeholder="Notas internas sobre este reporte...">{{ $report->admin_notes }}</textarea>
                            </div>

                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                                Guardar
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">

                    <!-- Actions -->
                    <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
                        <h3 class="text-lg font-bold text-white mb-4">Acciones</h3>

                        @if($report->reported->isSuspended())
                            <div class="bg-red-900/20 border border-red-700 rounded-lg p-4 mb-4">
                                <p class="text-red-300 text-sm font-semibold">Usuario suspendido</p>
                                <p class="text-gray-400 text-xs mt-1">{{ $report->reported->suspension_reason }}</p>
                                <p class="text-gray-500 text-xs">Desde:
                                    {{ $report->reported->suspended_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <form action="{{ route('admin.users.unsuspend', $report->reported_id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-semibold transition">
                                    Levantar Suspensión
                                </button>
                            </form>
                        @elseif($report->reported->role !== 'admin')
                            <form action="{{ route('admin.users.suspend', $report->reported_id) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label class="block text-gray-400 text-sm mb-2">Motivo de suspensión</label>
                                    <textarea name="suspension_reason" rows="2" required
                                        class="w-full bg-gray-700 text-white rounded-lg px-4 py-3 text-sm"
                                        placeholder="Motivo..."></textarea>
                                </div>
                                <button type="submit"
                                    class="w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-lg font-semibold transition">
                                    Suspender Usuario
                                </button>
                            </form>
                        @else
                            <p class="text-gray-400 text-sm">No se puede suspender a un administrador</p>
                        @endif
                    </div>

                    <!-- Previous Reports -->
                    @if($previousReports->count() > 0)
                        <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
                            <h3 class="text-lg font-bold text-white mb-4">Reportes Previos ({{ $previousReports->count() }})
                            </h3>
                            <div class="space-y-3">
                                @foreach($previousReports as $prev)
                                    <div class="bg-gray-700/50 rounded-lg p-3">
                                        <p class="text-sm text-white">{{ $prev->reason }}</p>
                                        <p class="text-xs text-gray-400">Por {{ $prev->reporter->name ?? 'N/A' }}</p>
                                        <p class="text-xs text-gray-500">{{ $prev->created_at->format('d/m/Y') }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
@endsection