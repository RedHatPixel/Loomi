<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Notifications\OrderConfirmed;
use App\Notifications\OrderShipped;
use App\Notifications\OrderDelivered;
use App\Notifications\OrderCancelled;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function index(Request $request): Response
    {
        $user = $request->user();
        $storeIds = $user->stores()->pluck('id')->toArray();

        if (empty($storeIds)) {
            return Inertia::render('Seller/Orders', [
                'orders' => [],
                'stores' => [],
            ]);
        }

        $storeId  = $request->integer('store_id') ?: null;
        $status   = $request->string('status')->toString();

        $orderIds = OrderItem::whereIn('store_id', $storeIds)
            ->when($storeId, fn ($q) => $q->where('store_id', $storeId))
            ->pluck('order_id')
            ->unique();

        $paginator = Order::whereIn('id', $orderIds)
            ->with(['user', 'items' => fn ($q) => $q->with('product.images')->whereIn('store_id', $storeIds)])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        // Build clean array data with extra fields for each order
        $orderData = $paginator->getCollection()->map(function ($order) use ($storeIds) {
            $allItems = OrderItem::where('order_id', $order->id)
                ->select('store_id', 'status')
                ->get();

            $otherStores = $allItems
                ->reject(fn ($i) => in_array($i->store_id, $storeIds))
                ->groupBy('store_id')
                ->map(function ($items, $sid) {
                    $store = \App\Models\Store::find($sid);
                    return [
                        'store_id'   => (int) $sid,
                        'store_name' => $store?->name ?? 'Unknown Store',
                        'status'     => Order::deriveStatus($items->pluck('status')),
                    ];
                })
                ->values()
                ->toArray();

            $myItems = $allItems->filter(fn ($i) => in_array($i->store_id, $storeIds));

            $arr = $order->toArray();
            $arr['my_store_status'] = Order::deriveStatus($myItems->pluck('status'));
            $arr['other_stores'] = $otherStores;

            return $arr;
        })->values()->toArray();

        return Inertia::render('Seller/Orders', [
            'orders' => [
                'data'  => $orderData,
                'meta'  => [
                    'current_page' => $paginator->currentPage(),
                    'last_page'    => $paginator->lastPage(),
                    'per_page'     => $paginator->perPage(),
                    'total'        => $paginator->total(),
                ],
                'links' => [
                    'next' => $paginator->nextPageUrl(),
                    'prev' => $paginator->previousPageUrl(),
                ],
            ],
            'stores' => $user->stores()->get(['id', 'name', 'slug']),
            'filters' => [
                'store_id' => $storeId,
                'status'   => $status,
            ],
        ]);
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $user = $request->user();
        $storeIds = $user->stores()->pluck('id');

        // Ensure this order has items from one of the seller's stores
        $belongsToSeller = OrderItem::where('order_id', $order->id)
            ->whereIn('store_id', $storeIds)
            ->exists();

        abort_if(!$belongsToSeller, 403);

        $validated = $request->validate([
            'status' => ['required', 'string', 'in:confirmed,shipped,delivered,cancelled'],
        ]);

        // Update only this seller's items in the order
        OrderItem::where('order_id', $order->id)
            ->whereIn('store_id', $storeIds)
            ->where('status', '!=', 'cancelled')
            ->update(['status' => $validated['status']]);

        // Recalculate overall order status from all items
        $remainingStatuses = OrderItem::where('order_id', $order->id)
            ->pluck('status');

        $order->update(['status' => Order::deriveStatus($remainingStatuses)]);

        // Notify the customer
        $storeNames = $user->stores()->whereIn('id', $storeIds)->pluck('name')->implode(', ');
        $order->load('user');
        switch ($validated['status']) {
            case 'confirmed':
                $order->user->notify(new OrderConfirmed($order, $storeNames));
                break;
            case 'shipped':
                $order->user->notify(new OrderShipped($order, $storeNames));
                break;
            case 'delivered':
                $order->user->notify(new OrderDelivered($order, $storeNames));
                break;
            case 'cancelled':
                $order->user->notify(new OrderCancelled($order, $storeNames));
                break;
        }

        return back()->with('success', "Order #{$order->id} updated to {$validated['status']}.");
    }
}
