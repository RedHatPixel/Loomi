<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Order::with(['user', 'items']);

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('user', fn ($q) => $q->where('name', 'like', "%{$search}%"));
            });
        }

        $orders = $query->latest()
            ->paginate(15)
            ->through(fn ($o) => [
                'id'         => $o->id,
                'status'     => $o->status,
                'total'      => (float) $o->total,
                'customer'   => $o->user?->name ?? 'Guest',
                'items_count' => $o->items->count(),
                'created_at' => $o->created_at->format('M d, Y'),
            ]);

        return Inertia::render('Admin/Orders/Index', [
            'orders'  => $orders,
            'filters' => [
                'status' => $request->get('status', ''),
                'search' => $request->get('search', ''),
            ],
        ]);
    }

    public function show(Order $order): Response
    {
        $order->load(['user', 'items', 'address']);

        return Inertia::render('Admin/Orders/Show', [
            'orderData' => [
                'id'             => $order->id,
                'status'         => $order->status,
                'total'          => (float) $order->total,
                'notes'          => $order->notes,
                'payment_method' => $order->payment_method,
                'payment_details' => $order->payment_details,
                'customer'       => $order->user?->name ?? 'Guest',
                'customer_email' => $order->user?->email ?? '',
                'created_at'     => $order->created_at->format('M d, Y h:i A'),
                'address'        => $order->address,
                'items'          => $order->items->map(fn ($i) => [
                    'id'           => $i->id,
                    'product_name' => $i->product_name,
                    'unit_price'   => (float) $i->unit_price,
                    'quantity'     => $i->quantity,
                    'subtotal'     => (float) $i->subtotal,
                    'status'       => $i->status,
                    'store_name'   => $i->store_name,
                    'image'        => $i->image,
                ]),
            ],
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:pending,confirmed,shipped,delivered,cancelled',
        ]);

        $order->update(['status' => $validated['status']]);

        // Also update all order items to match
        $order->items()->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully.');
    }
}
