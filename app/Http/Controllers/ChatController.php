<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Show chat page for an order
     */
    public function show($orderId)
    {
        $order = Order::with(['user', 'products.user'])->findOrFail($orderId);
        $user = Auth::user();

        // Verify user has access to this chat
        $isCustomer = $order->user_id === $user->id;
        $isVendor = $order->products->contains(function ($product) use ($user) {
            return $product->user_id === $user->id;
        });

        if (!$isCustomer && !$isVendor && $user->role !== 'admin') {
            abort(403, 'No tienes acceso a este chat');
        }

        // Get other participant info
        if ($isCustomer) {
            // Customer is chatting with vendor(s)
            $vendor = $order->products->first()?->user;
            $otherParticipant = $vendor;
        } else {
            // Vendor is chatting with customer
            $otherParticipant = $order->user;
        }

        // Get messages for this order
        $messages = Message::where('order_id', $orderId)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark messages as read
        Message::where('order_id', $orderId)
            ->where('receiver_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('chat.show', compact('order', 'messages', 'otherParticipant', 'isCustomer', 'isVendor'));
    }

    /**
     * Get messages for an order (AJAX)
     */
    public function getMessages($orderId)
    {
        $order = Order::findOrFail($orderId);
        $user = Auth::user();

        // Verify access
        $isCustomer = $order->user_id === $user->id;
        $isVendor = $order->products->contains(function ($product) use ($user) {
            return $product->user_id === $user->id;
        });

        if (!$isCustomer && !$isVendor && $user->role !== 'admin') {
            return response()->json(['error' => 'Sin acceso'], 403);
        }

        $messages = Message::where('order_id', $orderId)
            ->with(['sender:id,name', 'receiver:id,name'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark as read
        Message::where('order_id', $orderId)
            ->where('receiver_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json([
            'messages' => $messages,
            'user_id' => $user->id
        ]);
    }

    /**
     * Send a new message
     */
    public function sendMessage(Request $request, $orderId)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $order = Order::with('products.user')->findOrFail($orderId);
        $user = Auth::user();

        // Verify access
        $isCustomer = $order->user_id === $user->id;
        $isVendor = $order->products->contains(function ($product) use ($user) {
            return $product->user_id === $user->id;
        });

        if (!$isCustomer && !$isVendor) {
            return response()->json(['error' => 'Sin acceso'], 403);
        }

        // Determine receiver
        if ($isCustomer) {
            // Customer sends to vendor
            $receiver = $order->products->first()?->user;
        } else {
            // Vendor sends to customer
            $receiver = $order->user;
        }

        if (!$receiver) {
            return response()->json(['error' => 'No se encontró el destinatario'], 404);
        }

        $message = Message::create([
            'order_id' => $orderId,
            'sender_id' => $user->id,
            'receiver_id' => $receiver->id,
            'message' => $request->message,
        ]);

        $message->load('sender:id,name');

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    /**
     * Get unread message count for user
     */
    public function unreadCount()
    {
        $count = Message::where('receiver_id', Auth::id())
            ->whereNull('read_at')
            ->count();

        return response()->json(['count' => $count]);
    }
}
