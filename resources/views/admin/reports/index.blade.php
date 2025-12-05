@extends('layouts.public')

@section('title', 'Reportes - Admin - ShopSmart IA')

@section('content')
    <div class="min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">Reportes de Usuarios y Productos</h1>
                    <p class="text-gray-300">Gestiona las denuncias de la comunidad</p>
                </div>
                <div class="flex items-center gap-4">
                    @if($pendingCount > 0)
                        <span class="bg-red-600 text-white px-4 py-2 rounded-lg font-semibold">
                            {{ $pendingCount }} pendiente(s)
                        </span>
                    @endif
                    <a href="{{ route('admin.dashboard') }}"
                        class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-lg transition">
                        ← Dashboard
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-600/20 border border-green-500 text-green-300 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-600/20 border border-red-500 text-red-300 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Filter Tabs -->
            <div class="mb-6 flex gap-2">
                <a href="{{ route('admin.reports.index') }}"
                    class="px-4 py-2 rounded-lg transition {{ $type === 'all' ? 'bg-white text-gray-900 font-semibold' : 'bg-white/10 text-white hover:bg-white/20' }}">
                    Todos ({{ $pendingCount }})
                </a>
                <a href="{{ route('admin.reports.index', ['type' => 'user']) }}"
                    class="px-4 py-2 rounded-lg transition {{ $type === 'user' ? 'bg-blue-600 text-white font-semibold' : 'bg-white/10 text-white hover:bg-white/20' }}">
                    👤 Usuarios ({{ $pendingUserReports ?? 0 }})
                </a>
                <a href="{{ route('admin.reports.index', ['type' => 'product']) }}"
                    class="px-4 py-2 rounded-lg transition {{ $type === 'product' ? 'bg-purple-600 text-white font-semibold' : 'bg-white/10 text-white hover:bg-white/20' }}">
                    📦 Productos ({{ $pendingProductReports ?? 0 }})
                </a>
            </div>

            <!-- Reports Table -->
            <div class="bg-white/10 backdrop-blur rounded-xl border border-white/20 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-white/5">
                            <tr class="text-gray-300 text-left">
                                <th class="px-6 py-4">ID</th>
                                <th class="px-6 py-4">Tipo</th>
                                <th class="px-6 py-4">Reportante</th>
                                <th class="px-6 py-4">Reportado</th>
                                <th class="px-6 py-4">Motivo</th>
                                <th class="px-6 py-4">Estado</th>
                                <th class="px-6 py-4">Fecha</th>
                                <th class="px-6 py-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reports as $report)
                                <tr
                                    class="border-b border-white/5 text-gray-200 hover:bg-white/5 {{ $report->status == 'pending' ? 'bg-yellow-900/10' : '' }}">
                                    <td class="px-6 py-4 font-mono">#{{ $report->id }}</td>
                                    <td class="px-6 py-4">
                                        @if($report->type === 'product')
                                            <span class="bg-purple-600/30 text-purple-300 px-2 py-1 rounded text-xs">📦
                                                Producto</span>
                                        @else
                                            <span class="bg-blue-600/30 text-blue-300 px-2 py-1 rounded text-xs">👤 Usuario</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="font-medium">{{ $report->reporter->name ?? 'N/A' }}</p>
                                        <p class="text-gray-400 text-xs">{{ $report->reporter->email ?? '' }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($report->type === 'product' && $report->product)
                                            <div class="flex items-center">
                                                <div>
                                                    <p class="font-medium {{ !$report->product->is_active ? 'text-red-400' : '' }}">
                                                        {{ Str::limit($report->product->name, 25) }}
                                                        @if(!$report->product->is_active)
                                                            <span
                                                                class="text-xs bg-red-600 text-white px-2 py-0.5 rounded ml-1">RETIRADO</span>
                                                        @endif
                                                    </p>
                                                    <p class="text-gray-400 text-xs">Vendedor:
                                                        {{ $report->product->user->name ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        @else
                                            <div class="flex items-center">
                                                <div>
                                                    <p
                                                        class="font-medium {{ $report->reported && $report->reported->isSuspended() ? 'text-red-400' : '' }}">
                                                        {{ $report->reported->name ?? 'N/A' }}
                                                        @if($report->reported && $report->reported->isSuspended())
                                                            <span
                                                                class="text-xs bg-red-600 text-white px-2 py-0.5 rounded ml-1">SUSPENDIDO</span>
                                                        @endif
                                                    </p>
                                                    <p class="text-gray-400 text-xs">{{ $report->reported->email ?? '' }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm">{{ $report->reason }}</span>
                                        @if($report->order_id)
                                            <p class="text-gray-400 text-xs mt-1">Pedido #{{ $report->order_id }}</p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                                            @if($report->status == 'pending') bg-yellow-500/30 text-yellow-300
                                            @elseif($report->status == 'reviewed') bg-blue-500/30 text-blue-300
                                            @elseif($report->status == 'resolved') bg-green-500/30 text-green-300
                                            @else bg-gray-500/30 text-gray-300 @endif">
                                            {{ ucfirst($report->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-400 text-sm">
                                        {{ $report->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex gap-2">
                                            <a href="{{ route('admin.reports.show', $report->id) }}"
                                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded text-sm transition">
                                                Ver
                                            </a>
                                            @if($report->type === 'product' && $report->product)
                                                @if($report->product->is_active)
                                                    <button
                                                        onclick="openDeactivateModal({{ $report->product->id }}, '{{ addslashes($report->product->name) }}')"
                                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded text-sm transition">
                                                        Retirar
                                                    </button>
                                                @else
                                                    <form action="{{ route('admin.products.reactivate', $report->product->id) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit"
                                                            class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded text-sm transition">
                                                            Activar
                                                        </button>
                                                    </form>
                                                @endif
                                            @elseif($report->type === 'user' && $report->reported)
                                                @if(!$report->reported->isSuspended() && $report->reported->role !== 'admin')
                                                    <button
                                                        onclick="openSuspendModal({{ $report->reported_id }}, '{{ addslashes($report->reported->name) }}')"
                                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded text-sm transition">
                                                        Suspender
                                                    </button>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center">
                                        <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="text-gray-400">No hay reportes</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">
                {{ $reports->appends(['type' => $type])->links() }}
            </div>

        </div>
    </div>

    <!-- Suspend User Modal -->
    <div id="suspendModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-gray-800 rounded-xl shadow-2xl max-w-md w-full p-6 border border-gray-700">
            <h3 class="text-xl font-bold text-white mb-4 flex items-center">
                <svg class="w-6 h-6 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                </svg>
                Suspender Usuario
            </h3>

            <p class="text-gray-400 mb-4">
                Estás por suspender indefinidamente a <span id="suspendUserName" class="text-red-400 font-semibold"></span>.
            </p>

            <form id="suspendForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-400 text-sm font-medium mb-2">Motivo de la suspensión *</label>
                    <textarea name="suspension_reason" rows="3"
                        class="w-full bg-gray-700 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-red-500 focus:outline-none"
                        placeholder="Describe el motivo de la suspensión..." required></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeSuspendModal()"
                        class="flex-1 bg-gray-600 hover:bg-gray-500 text-white py-3 rounded-lg font-semibold transition">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="flex-1 bg-red-600 hover:bg-red-700 text-white py-3 rounded-lg font-semibold transition">
                        Confirmar Suspensión
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Deactivate Product Modal -->
    <div id="deactivateModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-gray-800 rounded-xl shadow-2xl max-w-md w-full p-6 border border-gray-700">
            <h3 class="text-xl font-bold text-white mb-4 flex items-center">
                <svg class="w-6 h-6 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Retirar Producto
            </h3>

            <p class="text-gray-400 mb-4">
                Estás por retirar <span id="deactivateProductName" class="text-red-400 font-semibold"></span> de la tienda.
            </p>

            <form id="deactivateForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-400 text-sm font-medium mb-2">Motivo del retiro *</label>
                    <textarea name="deactivation_reason" rows="3"
                        class="w-full bg-gray-700 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-red-500 focus:outline-none"
                        placeholder="Describe por qué se retira este producto..." required></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeDeactivateModal()"
                        class="flex-1 bg-gray-600 hover:bg-gray-500 text-white py-3 rounded-lg font-semibold transition">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="flex-1 bg-red-600 hover:bg-red-700 text-white py-3 rounded-lg font-semibold transition">
                        Retirar Producto
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openSuspendModal(userId, userName) {
            document.getElementById('suspendForm').action = `/admin/users/${userId}/suspend`;
            document.getElementById('suspendUserName').textContent = userName;
            document.getElementById('suspendModal').classList.remove('hidden');
        }

        function closeSuspendModal() {
            document.getElementById('suspendModal').classList.add('hidden');
        }

        function openDeactivateModal(productId, productName) {
            document.getElementById('deactivateForm').action = `/admin/products/${productId}/deactivate`;
            document.getElementById('deactivateProductName').textContent = productName;
            document.getElementById('deactivateModal').classList.remove('hidden');
        }

        function closeDeactivateModal() {
            document.getElementById('deactivateModal').classList.add('hidden');
        }

        document.getElementById('suspendModal')?.addEventListener('click', function (e) {
            if (e.target === this) closeSuspendModal();
        });

        document.getElementById('deactivateModal')?.addEventListener('click', function (e) {
            if (e.target === this) closeDeactivateModal();
        });
    </script>
@endsection