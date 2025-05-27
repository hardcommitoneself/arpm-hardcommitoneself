<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\CartItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        // Eager load relationships to minimize queries
        $orders = Order::all();

        // Prepare order data with necessary calculations
        $orderData = $orders->map(function ($order) {
            $totalAmount = $order->items->sum(function ($item) {
                return $item->price * $item->quantity;
            });

            $itemsCount = $order->items->count();

            // Get the last added to cart directly, if any
            $lastAddedToCart = $order->cartItems()
                ->orderByDesc('created_at')
                ->first()
                ->created_at ?? null;

            // Check if there is a completed order directly
            $completedOrderExists = $order->status === 'completed';

            return [
                'order_id' => $order->id,
                'customer_name' => $order->customer->name,
                'total_amount' => $totalAmount,
                'items_count' => $itemsCount,
                'last_added_to_cart' => $lastAddedToCart,
                'completed_order_exists' => $completedOrderExists,
                'created_at' => $order->created_at,
                'completed_at' => $order->completed_at,
            ];
        });

        // Sort orders by completed_at directly using collection sorting
        $orderData = $orderData->sortByDesc('completed_at')->values();

        return view('orders.index', ['orders' => $orderData]);
    }
}
