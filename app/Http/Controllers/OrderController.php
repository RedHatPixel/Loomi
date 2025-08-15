<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id());
        return view('order.index', compact('orders'));
    }

    public function show(Order $order)
    {
        return view('order.show', compact('order'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // Ensure the user is authenticated
        $user = Auth::user();
        $status = Status::where('status', 'Pending')->first();

        // Calculate total amount
        $total_amount = 0;
        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            $total_amount += $product->price * $item['quantity'];
        }

        // Create the order
        $order = Order::create([
            'user_id' => $user->id,
            'status_id' => $status->id,
            'total_amount' => $total_amount,
        ]);

        // Create order items
        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'price_at_sale' => $product->price,
                'quantity' => $item['quantity'],
            ]);
        }

        return redirect()->route('orders.index')
            ->with('success', 'Order placed successfully.');
    }

    public function delete(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')
            ->with('success', 'Order deleted successfully.');
    }
}
