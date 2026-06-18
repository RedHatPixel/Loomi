<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private CartService $cartService) {}

    public function index(Request $request): Response
    {
        $orders = Order::with(['items'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Customer/Orders/Index', [
            'orders' => [
                'data' => $orders->items(),
                'meta' => [
                    'current_page' => $orders->currentPage(),
                    'last_page'    => $orders->lastPage(),
                    'total'        => $orders->total(),
                ],
            ],
        ]);
    }

    public function show(Request $request, Order $order): Response
    {
        abort_if($order->user_id !== $request->user()->id && !$request->user()->isAdmin(), 404);

        $order->load(['items.product.images', 'items.store', 'address']);

        $items = $order->items->map(fn ($item) => [
            'id'           => $item->id,
            'product_name' => $item->product_name,
            'unit_price'   => $item->unit_price,
            'quantity'     => $item->quantity,
            'subtotal'     => $item->subtotal,
            'status'       => $item->status ?? 'pending',
            'store_id'     => $item->store_id,
            'store_name'   => $item->store?->name ?? 'Unknown Store',
            'image'        => $item->product?->images->first()?->path,
            'slug'         => $item->product?->slug,
        ]);

        $overallStatus = Order::deriveStatus($items->pluck('status'));

        return Inertia::render('Customer/Orders/Show', [
            'order' => [
                'id'              => $order->id,
                'status'          => $overallStatus,
                'total'           => $order->total,
                'notes'           => $order->notes,
                'payment_method'  => $order->payment_method,
                'payment_details' => $order->payment_details,
                'created_at'      => $order->created_at->toDateTimeString(),
                'address'         => $order->address,
                'items'           => $items,
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'notes'           => ['nullable', 'string', 'max:500'],
            'payment_method'  => ['required', 'string', 'in:cod,prepaid'],
            'card_type'       => ['required_if:payment_method,prepaid', 'string', 'max:50'],
            'card_number'     => ['required_if:payment_method,prepaid', 'string', 'max:20'],
            'card_password'   => ['required_if:payment_method,prepaid', 'string', 'max:50'],
        ]);

        $user = $request->user();
        $cart = $this->cartService->getCartWithItems($user);

        if ($cart->items->isEmpty()) {
            return back()->withErrors(['cart' => 'Your cart is empty.']);
        }

        $paymentDetails = $request->payment_method === 'prepaid'
            ? json_encode([
                'card_type'   => $request->input('card_type'),
                'card_number' => '****' . substr($request->input('card_number'), -4),
            ])
            : null;

        DB::transaction(function () use ($user, $cart, $request, $paymentDetails) {
            $total = $this->cartService->getTotal($cart);

            $order = Order::create([
                'user_id'         => $user->id,
                'status'          => 'pending',
                'total'           => $total,
                'notes'           => $request->input('notes'),
                'payment_method'  => $request->input('payment_method'),
                'payment_details' => $paymentDetails,
            ]);

            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id'   => $item->product_id,
                    'store_id'     => $item->product->store_id,
                    'product_name' => $item->product->name,
                    'unit_price'   => $item->product->price,
                    'quantity'     => $item->quantity,
                    'subtotal'     => $item->product->price * $item->quantity,
                    'status'       => 'pending',
                ]);
            }

            // Notify the customer
            $user->notify(new \App\Notifications\OrderPlaced($order));

            // Notify each store owner
            $storeIds = $cart->items->pluck('product.store_id')->unique();
            foreach ($storeIds as $storeId) {
                $store = \App\Models\Store::find($storeId);
                if ($store && $store->user) {
                    $storeItems = $cart->items->where('product.store_id', $storeId)->map(fn ($i) => [
                        'name'     => $i->product->name,
                        'quantity' => $i->quantity,
                    ])->values()->toArray();

                    $store->user->notify(new \App\Notifications\NewOrderReceived(
                        $order,
                        $store->name,
                        $user->name,
                        $storeItems,
                    ));
                }
            }

            $this->cartService->clearCart($cart);
        });

        return redirect()->route('orders.index')
            ->with('success', 'Order placed successfully.');
    }

    public function cancel(Request $request, Order $order): RedirectResponse
    {
        abort_if(
            $order->user_id !== $request->user()->id && !$request->user()->isAdmin(),
            404
        );

        // Only cancel items that are still pending (other stores may have confirmed already)
        $pendingCount = $order->items()->where('status', 'pending')->count();
        if ($pendingCount === 0) {
            return back()->with('error', 'No pending items to cancel.');
        }

        $order->items()->where('status', 'pending')->update(['status' => 'cancelled']);

        $order->update(['status' => Order::deriveStatus($order->items()->pluck('status'))]);

        // Notify
        $request->user()->notify(new \App\Notifications\OrderCancelled($order));

        return back()->with('success', 'Order cancelled.');
    }

    public function cancelStore(Request $request, Order $order, int $storeId): RedirectResponse
    {
        abort_if(
            $order->user_id !== $request->user()->id && !$request->user()->isAdmin(),
            404
        );

        $pendingCount = $order->items()->where('store_id', $storeId)->where('status', 'pending')->count();

        if ($pendingCount === 0) {
            return back()->with('error', 'No pending items from this store to cancel.');
        }

        $order->items()->where('store_id', $storeId)->where('status', 'pending')->update(['status' => 'cancelled']);

        $order->update(['status' => Order::deriveStatus($order->items()->pluck('status'))]);

        // Notify
        $storeName = \App\Models\Store::find($storeId)?->name ?? 'Store';
        $request->user()->notify(new \App\Notifications\OrderCancelled($order, $storeName));

        return back()->with('success', 'Items from this store have been cancelled.');
    }
}
