<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductSale;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | USER CONTROLLER
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        // Active orders (exclude cancelled or received)
        $active = Order::where('user_id', Auth::id())
            ->whereDoesntHave('status', function ($query) {
                $query->whereIn('name', ['cancelled', 'received', 'denied']);
            })
            ->with(['status', 'items.product.primaryImage'])
            ->get();

        // Order history (status is cancelled or received)
        $history = Order::where('user_id', Auth::id())
            ->whereHas('status', function ($query) {
                $query->whereIn('name', ['cancelled', 'received', 'denied']);
            })
            ->with(['status', 'items.product.primaryImage'])
            ->get();

        return view('order', compact('active', 'history'));
    }

    public function store(Request $request)
    {
        $this->authorize('store', Order::class);

        $request->validate([
            // Order information 
            'first_name' => 'required|string|min:2|max:50',
            'last_name' => 'required|string|min:2|max:50',
            'address' => 'required|string|min:1',
            'contact_number' => 'required|string|min:10|max:20',
            'notes' => 'nullable|string',
            'items' => 'required|array',

            // Order items
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.cart_id' => 'nullable|exists:carts,id'
        ]);

        // Ensure the user is authenticated
        $user = Auth::user();
        $status = Status::where('name', 'Pending')->first();

        // Calculate total amount
        $total_amount = 0;
        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);

            if ($product->quantity < $item['quantity']) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(
                        "Not enough stock for {$product->name}. Available: {$product->quantity}, requested: {$item['quantity']}."
                    );
            }

            $total_amount += $product->price * $item['quantity'];
        }

        // Create the order
        $order = Order::create([
            'user_id' => $user->id,
            'status_id' => $status->id,
            'address' => $request->address,
            'name' => $request->first_name . ' ' . $request->last_name,
            'contact_number' => $request->contact_number,
            'notes' => $request->notes,
            'total_amount' => $total_amount,
        ]);

        // Create order items
        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['product_id']);
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'price_at_sale' => $product->price,
                'quantity' => $item['quantity'],
            ]);

            if (!empty($item['cart_id'])) {
                $cart = Cart::find($item['cart_id']);
                if ($cart) {
                    $cart->delete();
                }
            }
        }

        session()->forget('collections');
        return redirect()->route('orders.index')
            ->with('success', 'Order placed successfully.');
    }

    public function cancel(Order $order)
    {
        $status = $order->status;

        $this->authorize('cancel', [Order::class, $status]);

        $status = Status::where('name', 'cancelled')->first();

        $order->update([
            'status_id' => $status->id
        ]);

        return redirect()->back()
            ->with('success', 'Order was canceled successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | AUTHENTICATED CONTROLLER
    |--------------------------------------------------------------------------
    */
    public function restore(Order $order)
    {
        $status = $order->status;

        $this->authorize('restore', [Order::class, $status]);

        foreach ($order->items as $item) {
            $product = Product::findOrFail($item->product_id);

            if ($product->quantity < $item->quantity) {
                return redirect()->back()
                    ->withErrors("Not enough stock for {$product->name}. Available: {$product->quantity}, required: {$item->quantity}.");
            }
        }

        $status = Status::where('name', 'pending')->first();

        $order->update([
            'status_id' => $status->id
        ]);

        return redirect()->back()
            ->with('success', 'Order was restore successfully.');
    }

    public function destroy(Order $order)
    {
        $status = $order->status;

        $this->authorize('destroy', [Order::class, $status]);

        $order->delete();

        $user = User::findOrFail(Auth::id());

        if ($user->isAdmin()) {
            return redirect()->route('admin.orders');
        }

        return redirect()->back()
            ->with('success', 'Order deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN CONTROLLER
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        $request->validate([
            'status_id' => 'required|exists:statuses,id',
        ]);

        $order->update([
            'status_id' => $request->status_id
        ]);

        return redirect()->back()
            ->with('success', 'Order status updated successfully.');
    }

    public function accept(Order $order)
    {
        $this->authorize('accept', Order::class);

        $status = Status::where('name', 'processing')->first();

        $order->update([
            'status_id' => $status->id
        ]);

        return redirect()->back()
            ->with('success', 'Order was accept successfully.');
    }

    public function deny(Order $order)
    {
        $this->authorize('deny', Order::class);

        $status = Status::where('name', 'denied')->first();

        $order->update([
            'status_id' => $status->id
        ]);

        return redirect()->back()
            ->with('success', 'Order was deny successfully.');
    }
}
