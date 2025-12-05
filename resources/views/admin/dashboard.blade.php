@extends('layouts.public')

@section('title', 'Dashboard Admin - ShopSmart IA')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-green-900 via-teal-800 to-blue-900 py-10">

        <div class="max-w-7xl mx-auto px-6">

            <!-- HEADER -->
            <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-4xl font-extrabold text-white drop-shadow-lg">
                        🚀 Dashboard ShopSmart IA
                    </h1>
                    <p class="text-gray-300 text-lg">
                        Bienvenido, <span class="font-semibold text-white">{{ Auth::user()->name }}</span>
                    </p>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('home') }}"
                        class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-lg transition flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Ver Tienda
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition shadow-lg">
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>

            <!-- TOP CARDS -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-10">

                <div class="bg-gradient-to-br from-blue-500 to-blue-700 p-6 rounded-xl shadow-lg">
                    <p class="text-blue-100 text-sm">Usuarios Registrados</p>
                    <p class="text-4xl font-extrabold text-white">{{ $stats['total_users'] }}</p>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-700 p-6 rounded-xl shadow-lg">
                    <p class="text-green-100 text-sm">Productos en Catálogo</p>
                    <p class="text-4xl font-extrabold text-white">{{ $stats['total_products'] }}</p>
                </div>

                <div class="bg-gradient-to-br from-purple-500 to-purple-700 p-6 rounded-xl shadow-lg">
                    <p class="text-purple-100 text-sm">Pedidos Totales</p>
                    <p class="text-4xl font-extrabold text-white">{{ $stats['total_orders'] }}</p>
                </div>

                <!-- Reports Card -->
                <a href="{{ route('admin.reports.index') }}"
                    class="bg-gradient-to-br from-red-500 to-red-700 p-6 rounded-xl shadow-lg hover:from-red-600 hover:to-red-800 transition group">
                    <p class="text-red-100 text-sm">Reportes Pendientes</p>
                    <p class="text-4xl font-extrabold text-white">{{ $stats['pending_reports'] ?? 0 }}</p>
                    <p class="text-red-200 text-xs mt-2 group-hover:text-white transition">Ver todos →</p>
                </a>

                <!-- Analytics Card -->
                <a href="{{ route('admin.analytics') }}"
                    class="bg-gradient-to-br from-indigo-500 to-purple-700 p-6 rounded-xl shadow-lg hover:from-indigo-600 hover:to-purple-800 transition group">
                    <p class="text-indigo-100 text-sm">Analíticas</p>
                    <p class="text-3xl font-extrabold text-white">📊</p>
                    <p class="text-indigo-200 text-xs mt-2 group-hover:text-white transition">Ver estadísticas →</p>
                </a>

            </div>

            <!-- SPECIAL STORED PROCEDURES SECTION -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">

                <!-- TOP PRODUCTOS SP -->
                <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20 shadow-lg">
                    <h2 class="text-2xl font-bold text-white mb-4">
                        🏆 Top 5 Productos Más Vendidos (SP)
                    </h2>

                    <table class="w-full text-white">
                        <thead>
                            <tr class="border-b border-white/20">
                                <th class="py-2 text-left">Producto</th>
                                <th class="py-2 text-left">Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topProducts as $item)
                                <tr class="border-b border-white/10">
                                    <td class="py-2">{{ $item->name }}</td>
                                    <td class="py-2 font-semibold text-green-300">{{ $item->total }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="py-3 text-gray-300 text-center">No hay datos disponibles</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- PRODUCTOS SIN STOCK SP -->
                <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20 shadow-lg">
                    <h2 class="text-2xl font-bold text-white mb-4">
                        ❌ Productos sin Stock (SP)
                    </h2>

                    @forelse ($outOfStock as $p)
                        <div class="bg-red-500/20 text-white p-3 rounded-lg mb-2">
                            {{ $p->name }} — <span class="text-red-300 font-bold">Agotado</span>
                        </div>
                    @empty
                        <p class="text-gray-300">Todos los productos tienen stock 👍</p>
                    @endforelse
                </div>

            </div>

            <!-- USERS + ORDERS -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Usuarios Recientes -->
                <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20 shadow-lg">
                    <h2 class="text-xl font-bold text-white mb-4">👥 Últimos Usuarios Registrados</h2>

                    @foreach ($recent_users as $u)
                        <div class="flex items-center justify-between bg-white/5 p-3 rounded-lg mb-2">
                            <div class="flex items-center">
                                <div
                                    class="bg-gray-600 w-10 h-10 rounded-full flex items-center justify-center font-bold text-white mr-3">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-white font-medium">{{ $u->name }}</p>
                                    <p class="text-gray-300 text-sm">{{ $u->email }}</p>
                                </div>
                            </div>
                            <span class="px-3 py-1 text-xs rounded-lg
                                                @if($u->role === 'admin') bg-red-500/30 text-red-200
                                                @elseif($u->role === 'vendedor') bg-green-500/30 text-green-200
                                                @else bg-blue-500/30 text-blue-200 @endif
                                                ">
                                {{ ucfirst($u->role ?? 'cliente') }}
                            </span>
                        </div>
                    @endforeach

                </div>

                <!-- Pedidos Recientes -->
                <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20 shadow-lg">
                    <h2 class="text-xl font-bold text-white mb-4">📦 Pedidos Recientes</h2>

                    <table class="w-full text-gray-200">
                        <thead>
                            <tr class="border-b border-white/10 text-gray-300">
                                <th class="py-2">ID</th>
                                <th class="py-2">Cliente</th>
                                <th class="py-2">Total</th>
                                <th class="py-2">Estado</th>
                                <th class="py-2">Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recent_orders as $o)
                                <tr class="border-b border-white/5">
                                    <td class="py-2">#{{ $o->id }}</td>
                                    <td class="py-2">{{ $o->user->name ?? 'N/A' }}</td>
                                    <td class="py-2 font-semibold">S/ {{ number_format($o->total, 2) }}</td>
                                    <td class="py-2">
                                        <span class="px-2 py-1 rounded text-xs
                                                            @if($o->status === 'pendiente') bg-yellow-500/20 text-yellow-300
                                                            @elseif($o->status === 'confirmado') bg-blue-500/20 text-blue-300
                                                            @elseif($o->status === 'enviado') bg-purple-500/20 text-purple-300
                                                            @elseif($o->status === 'entregado') bg-green-500/20 text-green-300
                                                            @else bg-red-500/20 text-red-300 @endif">
                                            {{ ucfirst($o->status) }}
                                        </span>
                                    </td>
                                    <td class="py-2">{{ $o->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>

            </div>

        </div>

    </div>
@endsection