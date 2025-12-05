<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        // Sales by category
        $salesByCategory = DB::table('order_product')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->where('orders.status', '!=', 'cancelado')
            ->select(
                'categories.id',
                'categories.name',
                DB::raw('SUM(order_product.quantity * order_product.price) as total_sales'),
                DB::raw('SUM(order_product.quantity) as total_quantity')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_sales')
            ->get();

        $totalSales = $salesByCategory->sum('total_sales');

        // Add percentage to each category
        $salesByCategory = $salesByCategory->map(function ($item) use ($totalSales) {
            $item->percentage = $totalSales > 0 ? round(($item->total_sales / $totalSales) * 100, 1) : 0;
            return $item;
        });

        // Top 10 Best Selling Products (fixed GROUP BY)
        $bestSellers = DB::table('products')
            ->select(
                'products.id',
                'products.name',
                'products.price',
                'products.category_id',
                'products.user_id',
                'categories.name as category_name',
                'users.name as vendor_name',
                DB::raw('SUM(order_product.quantity) as total_sold'),
                DB::raw('SUM(order_product.quantity * order_product.price) as total_revenue')
            )
            ->join('order_product', 'products.id', '=', 'order_product.product_id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->leftJoin('users', 'products.user_id', '=', 'users.id')
            ->where('orders.status', '!=', 'cancelado')
            ->groupBy('products.id', 'products.name', 'products.price', 'products.category_id', 'products.user_id', 'categories.name', 'users.name')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();

        // Most Profitable Products (by revenue)
        $mostProfitable = DB::table('products')
            ->select(
                'products.id',
                'products.name',
                'products.price',
                'products.category_id',
                'products.user_id',
                'categories.name as category_name',
                'users.name as vendor_name',
                DB::raw('SUM(order_product.quantity) as total_sold'),
                DB::raw('SUM(order_product.quantity * order_product.price) as total_revenue')
            )
            ->join('order_product', 'products.id', '=', 'order_product.product_id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->leftJoin('users', 'products.user_id', '=', 'users.id')
            ->where('orders.status', '!=', 'cancelado')
            ->groupBy('products.id', 'products.name', 'products.price', 'products.category_id', 'products.user_id', 'categories.name', 'users.name')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();

        // Monthly Sales Trend (last 6 months)
        $monthlySales = Order::select(
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
            DB::raw('SUM(total) as total_sales'),
            DB::raw('COUNT(*) as total_orders')
        )
            ->where('status', '!=', 'cancelado')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Top Vendors by Sales
        $topVendors = DB::table('users')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                DB::raw('SUM(order_product.quantity * order_product.price) as total_sales'),
                DB::raw('COUNT(DISTINCT orders.id) as total_orders')
            )
            ->join('products', 'users.id', '=', 'products.user_id')
            ->join('order_product', 'products.id', '=', 'order_product.product_id')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->where('orders.status', '!=', 'cancelado')
            ->where('users.role', 'vendedor')
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('total_sales')
            ->limit(10)
            ->get();

        // General Stats
        $stats = [
            'total_revenue' => Order::where('status', '!=', 'cancelado')->sum('total'),
            'total_orders' => Order::where('status', '!=', 'cancelado')->count(),
            'avg_order_value' => Order::where('status', '!=', 'cancelado')->avg('total') ?? 0,
            'total_products_sold' => DB::table('order_product')
                ->join('orders', 'order_product.order_id', '=', 'orders.id')
                ->where('orders.status', '!=', 'cancelado')
                ->sum('quantity'),
            'active_products' => Product::where('is_active', true)->count(),
            'inactive_products' => Product::where('is_active', false)->count(),
            'total_customers' => User::where('role', 'cliente')->count(),
            'total_vendors' => User::where('role', 'vendedor')->count(),
        ];

        // Orders by Status
        $ordersByStatus = Order::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        return view('admin.analytics.index', compact(
            'salesByCategory',
            'bestSellers',
            'mostProfitable',
            'monthlySales',
            'topVendors',
            'stats',
            'ordersByStatus'
        ));
    }
}
