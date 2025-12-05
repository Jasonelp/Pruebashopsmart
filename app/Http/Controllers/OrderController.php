<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Muestra la lista de órdenes según el rol del usuario
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            // Admin ve todas las órdenes
            $orders = Order::with('user', 'products')
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } elseif ($user->role === 'vendor') {
            // Vendedor ve órdenes que incluyen sus productos
            $orders = Order::with('user', 'products')
                ->whereHas('products', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } else {
            // Cliente ve solo sus órdenes
            $orders = Order::with('products')
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        }

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Crea una nueva orden desde el carrito
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipping_address' => 'required|string',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->back()->with('error', 'El carrito está vacío');
        }

        try {
            DB::beginTransaction();

            // Calcular total
            $total = 0;
            foreach ($cart as $item) {
                $total += $item['price'] * $item['quantity'];
            }

            // Crear orden
            $order = Order::create([
                'user_id' => Auth::id(),
                'total' => $total,
                'status' => 'pendiente',
                'shipping_address' => $validated['shipping_address'],
                'notes' => $validated['notes'] ?? null,
            ]);

            // Crear detalles de la orden (relación many-to-many)
            foreach ($cart as $productId => $item) {
                $product = Product::find($productId);

                // Verificar stock
                if ($product->stock < $item['quantity']) {
                    DB::rollBack();
                    return redirect()->back()->with('error', "Stock insuficiente para {$product->name}");
                }

                // Adjuntar producto a la orden con precio y cantidad
                $order->products()->attach($productId, [
                    'quantity' => $item['quantity'],
                    'price' => $item['price'], // Precio histórico
                ]);

                // Reducir stock
                $product->decrement('stock', $item['quantity']);
            }

            DB::commit();

            // Vaciar carrito
            session()->forget('cart');

            return redirect()->route('orders.show', $order->id)
                ->with('success', '¡Pedido realizado exitosamente!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al procesar el pedido: ' . $e->getMessage());
        }
    }

    /**
     * Muestra el detalle de una orden específica
     */
    public function show($id)
    {
        $order = Order::with('user', 'products')->findOrFail($id);
        $user = Auth::user();

        // Verificar permisos
        if ($user->role === 'client' && $order->user_id !== $user->id) {
            abort(403, 'No autorizado para ver esta orden');
        }

        if ($user->role === 'vendor') {
            // Verificar que el vendedor tenga productos en esta orden
            $hasProducts = $order->products()->where('user_id', $user->id)->exists();
            if (!$hasProducts) {
                abort(403, 'No autorizado para ver esta orden');
            }
        }

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Actualiza el estado de una orden
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pendiente,confirmado,enviado,entregado,cancelado',
        ]);

        $order = Order::findOrFail($id);
        $user = Auth::user();

        // Solo admin o vendedor pueden cambiar estado
        if ($user->role !== 'admin' && $user->role !== 'vendor') {
            abort(403, 'No autorizado para modificar órdenes');
        }

        // Validar que no se retroceda el estado
        $estadosOrdenados = ['pendiente', 'confirmado', 'enviado', 'entregado'];
        $estadoActualIndex = array_search($order->status, $estadosOrdenados);
        $nuevoEstadoIndex = array_search($validated['status'], $estadosOrdenados);

        if ($nuevoEstadoIndex < $estadoActualIndex && $validated['status'] !== 'cancelado') {
            return redirect()->back()->with('error', 'No se puede retroceder el estado de la orden');
        }

        $order->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', 'Estado actualizado exitosamente');
    }

    /**
     * Muestra los pedidos del cliente autenticado (para vista pública)
     */
    public function myOrders()
    {
        $orders = Order::with('products')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.my-orders', compact('orders'));
    }

    /**
     * Cancela una orden (solo si está pendiente o confirmado)
     */
    public function destroy(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $user = Auth::user();

        // Verificar permisos
        if ($user->role === 'client' && $order->user_id !== $user->id) {
            abort(403, 'No autorizado para cancelar esta orden');
        }

        // Solo se puede cancelar si está pendiente o confirmado
        if (!in_array($order->status, ['pendiente', 'confirmado'])) {
            return redirect()->back()->with('error', 'Solo se pueden cancelar órdenes pendientes o confirmadas');
        }

        $validated = $request->validate([
            'cancellation_reason' => 'required|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            // Devolver stock
            foreach ($order->products as $product) {
                $product->increment('stock', $product->pivot->quantity);
            }

            // Marcar como cancelada con razón
            $order->update([
                'status' => 'cancelado',
                'cancellation_reason' => $validated['cancellation_reason'],
            ]);

            DB::commit();

            return redirect()->route('orders.my-orders')->with('success', 'Orden cancelada exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al cancelar la orden');
        }
    }
}