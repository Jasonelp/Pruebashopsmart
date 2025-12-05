<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Lista de órdenes del usuario autenticado
     */
    public function index(Request $request): JsonResponse
    {
        $orders = Order::query()
            ->with(['products'])
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => OrderResource::collection($orders),
            'meta' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
            ],
        ]);
    }

    /**
     * Muestra una orden específica del usuario
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $order = Order::query()
            ->with(['products'])
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => new OrderResource($order),
        ]);
    }

    /**
     * Crea una nueva orden desde el carrito
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'shipping_address' => 'required|string|max:500',
            'payment_method' => 'required|string|in:transferencia,tarjeta,contraentrega',
            'notes' => 'nullable|string|max:1000',
            'cart' => 'required|array|min:1',
            'cart.*.product_id' => 'required|exists:products,id',
            'cart.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $total = 0;
            $orderItems = [];

            // Validar productos y calcular total
            foreach ($validated['cart'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                if (!$product->isAvailable()) {
                    throw new \Exception("El producto {$product->name} no está disponible");
                }

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stock insuficiente para {$product->name}");
                }

                $subtotal = $product->price * $item['quantity'];
                $total += $subtotal;

                $orderItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ];
            }

            // Crear orden
            $order = Order::create([
                'user_id' => $request->user()->id,
                'total' => $total,
                'status' => 'pendiente',
                'shipping_address' => $validated['shipping_address'],
                'payment_method' => $validated['payment_method'],
                'notes' => $validated['notes'] ?? null,
            ]);

            // Adjuntar productos y reducir stock
            foreach ($orderItems as $item) {
                $order->products()->attach($item['product']->id, [
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);

                $item['product']->decrement('stock', $item['quantity']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Orden creada exitosamente',
                'data' => new OrderResource($order->load('products')),
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error al crear la orden',
                'error' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Cancela una orden (solo si está pendiente o confirmada)
     */
    public function cancel(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'cancellation_reason' => 'required|string|max:500',
        ]);

        $order = Order::query()
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        if (!in_array($order->status, ['pendiente', 'confirmado'])) {
            return response()->json([
                'success' => false,
                'message' => 'Solo se pueden cancelar órdenes pendientes o confirmadas',
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Devolver stock
            foreach ($order->products as $product) {
                $product->increment('stock', $product->pivot->quantity);
            }

            // Marcar como cancelada
            $order->update([
                'status' => 'cancelado',
                'cancellation_reason' => $validated['cancellation_reason'],
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Orden cancelada exitosamente',
                'data' => new OrderResource($order->load('products')),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error al cancelar la orden',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Lista de órdenes del vendedor (productos del vendedor)
     */
    public function vendorIndex(Request $request): JsonResponse
    {
        $orders = Order::query()
            ->with(['user', 'products'])
            ->whereHas('products', function ($query) use ($request) {
                $query->where('user_id', $request->user()->id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => OrderResource::collection($orders),
            'meta' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
            ],
        ]);
    }

    /**
     * Actualiza el estado de una orden (vendedor/admin)
     */
    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pendiente,confirmado,enviado,entregado,cancelado',
        ]);

        $order = Order::findOrFail($id);

        // Validar permisos del vendedor
        if ($request->user()->role === 'vendor') {
            $hasProducts = $order->products()->where('user_id', $request->user()->id)->exists();
            if (!$hasProducts) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autorizado para modificar esta orden',
                ], 403);
            }
        }

        // Validar que no se retroceda el estado
        $estadosOrdenados = ['pendiente', 'confirmado', 'enviado', 'entregado'];
        $estadoActualIndex = array_search($order->status, $estadosOrdenados);
        $nuevoEstadoIndex = array_search($validated['status'], $estadosOrdenados);

        if ($estadoActualIndex !== false && $nuevoEstadoIndex !== false &&
            $nuevoEstadoIndex < $estadoActualIndex && $validated['status'] !== 'cancelado') {
            return response()->json([
                'success' => false,
                'message' => 'No se puede retroceder el estado de la orden',
            ], 422);
        }

        $order->update(['status' => $validated['status']]);

        return response()->json([
            'success' => true,
            'message' => 'Estado actualizado exitosamente',
            'data' => new OrderResource($order->load(['products', 'user'])),
        ]);
    }

    /**
     * Lista todas las órdenes (admin)
     */
    public function adminIndex(Request $request): JsonResponse
    {
        $orders = Order::query()
            ->with(['user', 'products'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => OrderResource::collection($orders),
            'meta' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
            ],
        ]);
    }

    /**
     * Muestra una orden específica (admin)
     */
    public function adminShow(int $id): JsonResponse
    {
        $order = Order::query()
            ->with(['user', 'products'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => new OrderResource($order),
        ]);
    }
}
