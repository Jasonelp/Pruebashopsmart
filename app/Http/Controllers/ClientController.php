<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        $orders = Order::where('user_id', $user->id)
                      ->with('products')
                      ->orderBy('created_at', 'desc')
                      ->take(10)
                      ->get();

        $stats = [
            'total_orders' => Order::where('user_id', $user->id)->count(),
            'pending_orders' => Order::where('user_id', $user->id)->where('status', 'pendiente')->count(),
            'completed_orders' => Order::where('user_id', $user->id)->where('status', 'entregado')->count(),
        ];

        return view('client.dashboard', compact('orders', 'stats'));
    }
}