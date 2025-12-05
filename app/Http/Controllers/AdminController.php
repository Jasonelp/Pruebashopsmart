<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use App\Models\Report;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_clients' => User::where('role', 'cliente')->count(),
            'total_vendors' => User::where('role', 'vendedor')->count(),
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
            'total_categories' => Category::count(),
            'pending_orders' => Order::where('status', 'pendiente')->count(),
            'monthly_sales' => Order::whereMonth('created_at', date('m'))->sum('total'),
            'pending_reports' => Report::where('status', 'pending')->count(),
        ];

        $recent_orders = Order::with('user')->orderBy('created_at', 'desc')->take(5)->get();
        $recent_users = User::orderBy('created_at', 'desc')->take(5)->get();

        // Top 5 productos más vendidos
        $topProducts = Product::select('products.id', 'products.name')
            ->selectRaw('SUM(order_product.quantity) as total')
            ->join('order_product', 'products.id', '=', 'order_product.product_id')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        // Productos sin stock
        $outOfStock = Product::where('stock', 0)->get();

        return view('admin.dashboard', compact('stats', 'recent_orders', 'recent_users', 'topProducts', 'outOfStock'));
    }

    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function updateUserRole(Request $request, $id)
    {
        $validated = $request->validate([
            'role' => 'required|in:cliente,vendedor,admin',
        ]);

        $user = User::findOrFail($id);
        $user->update(['role' => $validated['role']]);

        return redirect()->back()->with('success', 'Rol actualizado correctamente');
    }

    public function destroyUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'No puedes eliminar tu propia cuenta');
        }

        $user->delete();
        return redirect()->back()->with('success', 'Usuario eliminado');
    }

    public function salesHistory()
    {
        $orders = Order::with(['user', 'products'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $total_sales = Order::where('status', '!=', 'cancelado')->sum('total');
        $monthly_sales = Order::whereMonth('created_at', date('m'))
            ->where('status', '!=', 'cancelado')
            ->sum('total');

        return view('admin.sales.index', compact('orders', 'total_sales', 'monthly_sales'));
    }
}