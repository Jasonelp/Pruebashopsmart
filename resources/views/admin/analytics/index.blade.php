@extends('layouts.public')

@section('title', 'Analíticas - Admin - ShopSmart IA')

@section('content')
    <div class="min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">📊 Analíticas</h1>
                    <p class="text-gray-300">Estadísticas y métricas de la tienda</p>
                </div>
                <a href="{{ route('admin.dashboard') }}"
                    class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-lg transition">
                    ← Dashboard
                </a>
            </div>

            <!-- General Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-gradient-to-br from-green-500 to-green-700 p-4 rounded-xl">
                    <p class="text-green-100 text-xs">Ingresos Totales</p>
                    <p class="text-2xl font-bold text-white">S/ {{ number_format($stats['total_revenue'], 2) }}</p>
                </div>
                <div class="bg-gradient-to-br from-blue-500 to-blue-700 p-4 rounded-xl">
                    <p class="text-blue-100 text-xs">Pedidos Completados</p>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['total_orders']) }}</p>
                </div>
                <div class="bg-gradient-to-br from-purple-500 to-purple-700 p-4 rounded-xl">
                    <p class="text-purple-100 text-xs">Ticket Promedio</p>
                    <p class="text-2xl font-bold text-white">S/ {{ number_format($stats['avg_order_value'], 2) }}</p>
                </div>
                <div class="bg-gradient-to-br from-orange-500 to-orange-700 p-4 rounded-xl">
                    <p class="text-orange-100 text-xs">Productos Vendidos</p>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['total_products_sold']) }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

                <!-- Sales by Category -->
                <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
                    <h2 class="text-xl font-bold text-white mb-4">📁 Ventas por Categoría</h2>
                    <div class="space-y-3">
                        @forelse($salesByCategory as $category)
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-300">{{ $category->name }}</span>
                                    <span class="text-white font-semibold">{{ $category->percentage }}%</span>
                                </div>
                                <div class="w-full bg-gray-700 rounded-full h-3">
                                    <div class="h-3 rounded-full bg-gradient-to-r from-green-500 to-blue-500"
                                        style="width: {{ $category->percentage }}%"></div>
                                </div>
                                <p class="text-gray-500 text-xs mt-1">
                                    S/ {{ number_format($category->total_sales, 2) }} ({{ $category->total_quantity }} unidades)
                                </p>
                            </div>
                        @empty
                            <p class="text-gray-400 text-center py-4">No hay datos de ventas</p>
                        @endforelse
                    </div>
                </div>

                <!-- Orders by Status -->
                <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
                    <h2 class="text-xl font-bold text-white mb-4">📦 Pedidos por Estado</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-yellow-500/20 border border-yellow-500/30 rounded-lg p-4 text-center">
                            <p class="text-3xl font-bold text-yellow-400">{{ $ordersByStatus['pendiente'] ?? 0 }}</p>
                            <p class="text-yellow-300 text-sm">Pendientes</p>
                        </div>
                        <div class="bg-blue-500/20 border border-blue-500/30 rounded-lg p-4 text-center">
                            <p class="text-3xl font-bold text-blue-400">{{ $ordersByStatus['confirmado'] ?? 0 }}</p>
                            <p class="text-blue-300 text-sm">Confirmados</p>
                        </div>
                        <div class="bg-purple-500/20 border border-purple-500/30 rounded-lg p-4 text-center">
                            <p class="text-3xl font-bold text-purple-400">{{ $ordersByStatus['enviado'] ?? 0 }}</p>
                            <p class="text-purple-300 text-sm">Enviados</p>
                        </div>
                        <div class="bg-green-500/20 border border-green-500/30 rounded-lg p-4 text-center">
                            <p class="text-3xl font-bold text-green-400">{{ $ordersByStatus['entregado'] ?? 0 }}</p>
                            <p class="text-green-300 text-sm">Entregados</p>
                        </div>
                    </div>
                    @if(isset($ordersByStatus['cancelado']) && $ordersByStatus['cancelado'] > 0)
                        <div class="mt-4 bg-red-500/20 border border-red-500/30 rounded-lg p-3 text-center">
                            <span class="text-red-400 font-bold">{{ $ordersByStatus['cancelado'] }}</span>
                            <span class="text-red-300 text-sm ml-2">Cancelados</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Best Sellers -->
            <div class="bg-gray-800 rounded-xl border border-gray-700 p-6 mb-8">
                <h2 class="text-xl font-bold text-white mb-4">🏆 Top 10 Productos Más Vendidos</h2>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-gray-400 text-left text-sm border-b border-gray-700">
                                <th class="pb-3">#</th>
                                <th class="pb-3">Producto</th>
                                <th class="pb-3">Categoría</th>
                                <th class="pb-3">Vendedor</th>
                                <th class="pb-3 text-right">Unidades</th>
                                <th class="pb-3 text-right">Ingresos</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bestSellers as $index => $product)
                                <tr class="border-b border-gray-700/50 text-gray-300">
                                    <td class="py-3">
                                        @if($index === 0)
                                            <span class="text-2xl">🥇</span>
                                        @elseif($index === 1)
                                            <span class="text-2xl">🥈</span>
                                        @elseif($index === 2)
                                            <span class="text-2xl">🥉</span>
                                        @else
                                            <span class="text-gray-500 font-mono">{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3 font-medium text-white">{{ Str::limit($product->name, 40) }}</td>
                                    <td class="py-3 text-sm">{{ $product->category_name ?? 'N/A' }}</td>
                                    <td class="py-3 text-sm">{{ $product->vendor_name ?? 'N/A' }}</td>
                                    <td class="py-3 text-right font-bold text-green-400">
                                        {{ number_format($product->total_sold) }}</td>
                                    <td class="py-3 text-right text-green-400">S/
                                        {{ number_format($product->total_revenue, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-gray-400">No hay datos de ventas</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Top Vendors -->
            <div class="bg-gray-800 rounded-xl border border-gray-700 p-6 mb-8">
                <h2 class="text-xl font-bold text-white mb-4">⭐ Top Vendedores</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    @forelse($topVendors as $index => $vendor)
                        <div
                            class="bg-gray-700/50 rounded-lg p-4 text-center {{ $index === 0 ? 'ring-2 ring-yellow-500' : '' }}">
                            @if($index === 0)
                                <span class="text-2xl">🏆</span>
                            @endif
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full mx-auto flex items-center justify-center text-white font-bold text-lg mb-2">
                                {{ strtoupper(substr($vendor->name, 0, 1)) }}
                            </div>
                            <p class="text-white font-medium text-sm">{{ Str::limit($vendor->name, 15) }}</p>
                            <p class="text-green-400 font-bold">S/ {{ number_format($vendor->total_sales, 0) }}</p>
                            <p class="text-gray-500 text-xs">{{ $vendor->total_orders }} pedidos</p>
                        </div>
                    @empty
                        <p class="col-span-5 text-gray-400 text-center py-4">No hay datos de vendedores</p>
                    @endforelse
                </div>
            </div>

            <!-- Monthly Trend -->
            <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
                <h2 class="text-xl font-bold text-white mb-4">📈 Tendencia de Ventas (Últimos 6 meses)</h2>
                <div class="flex items-end justify-around h-48 gap-2">
                    @php
                        $maxSales = $monthlySales->max('total_sales') ?: 1;
                    @endphp
                    @forelse($monthlySales as $month)
                        @php
                            $height = ($month->total_sales / $maxSales) * 100;
                            $monthName = \Carbon\Carbon::parse($month->month . '-01')->locale('es')->isoFormat('MMM');
                        @endphp
                        <div class="flex flex-col items-center flex-1">
                            <p class="text-green-400 text-xs mb-1">S/{{ number_format($month->total_sales / 1000, 1) }}k</p>
                            <div class="w-full bg-gradient-to-t from-green-600 to-green-400 rounded-t-lg transition-all duration-300 hover:from-green-500 hover:to-green-300"
                                style="height: {{ max($height, 5) }}%"></div>
                            <p class="text-gray-400 text-xs mt-2">{{ ucfirst($monthName) }}</p>
                            <p class="text-gray-500 text-xs">{{ $month->total_orders }} ped.</p>
                        </div>
                    @empty
                        <p class="text-gray-400 text-center w-full">No hay datos históricos</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
@endsection