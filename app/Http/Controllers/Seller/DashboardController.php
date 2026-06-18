<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $user = $request->user();
        $storeIds = $user->stores()->pluck('id');

        if ($storeIds->isEmpty()) {
            return Inertia::render('Seller/Dashboard', [
                'stores'   => [],
                'stats'    => null,
                'orders'   => [],
            ]);
        }

        // Overall stats across all stores
        $stats = [
            'total_products'  => Product::whereIn('store_id', $storeIds)->count(),
            'published'       => Product::whereIn('store_id', $storeIds)->where('is_published', true)->count(),
            'total_orders'    => OrderItem::whereIn('store_id', $storeIds)->distinct('order_id')->count('order_id'),
            'total_revenue'   => (float) OrderItem::whereIn('store_id', $storeIds)
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->whereNotIn('orders.status', ['cancelled'])
                ->sum('order_items.subtotal'),
            'pending_orders'  => Order::whereIn('id', function ($q) use ($storeIds) {
                $q->select('order_id')->from('order_items')->whereIn('store_id', $storeIds);
            })->where('status', 'pending')->count(),
        ];

        // Per-store breakdown — single grouped query
        $storeAggregates = OrderItem::whereIn('store_id', $storeIds)
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->selectRaw("
                store_id,
                COUNT(DISTINCT order_items.order_id) as orders_count,
                COALESCE(SUM(CASE WHEN orders.status != 'cancelled' THEN order_items.subtotal ELSE 0 END), 0) as revenue
            ")
            ->groupBy('store_id')
            ->get()
            ->keyBy('store_id');

        $stores = Store::whereIn('id', $storeIds)
            ->withCount(['products' => fn ($q) => $q->where('is_published', true)])
            ->get()
            ->map(fn ($s) => [
                'id'             => $s->id,
                'name'           => $s->name,
                'slug'           => $s->slug,
                'is_active'      => $s->is_active,
                'products_count' => $s->products_count,
                'orders_count'   => (int) ($storeAggregates[$s->id]->orders_count ?? 0),
                'revenue'        => (float) ($storeAggregates[$s->id]->revenue ?? 0),
            ]);

        // Weekly sales data (last 7 days)
        $weeklySales = collect();
        $orderItemQuery = OrderItem::whereIn('store_id', $storeIds)
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereNotIn('orders.status', ['cancelled'])
            ->selectRaw("DATE(orders.created_at) as date, SUM(order_items.subtotal) as total")
            ->groupBy('date')
            ->pluck('total', 'date');

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $weeklySales->push([
                'date'  => now()->subDays($i)->format('D'),
                'total' => (float) ($orderItemQuery[$date] ?? 0),
            ]);
        }

        // Order status breakdown
        $statusBreakdown = OrderItem::whereIn('store_id', $storeIds)
            ->selectRaw("status, COUNT(*) as count")
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $allStatuses = ['pending', 'confirmed', 'shipped', 'delivered', 'cancelled'];
        $orderStatuses = collect($allStatuses)->map(fn ($s) => [
            'status' => $s,
            'count'  => (int) ($statusBreakdown[$s] ?? 0),
        ]);

        // Recent orders across all stores
        $recentOrders = Order::whereIn('id', function ($q) use ($storeIds) {
                $q->select('order_id')->from('order_items')->whereIn('store_id', $storeIds);
            })
            ->with(['items' => fn ($q) => $q->whereIn('store_id', $storeIds), 'user'])
            ->latest()
            ->limit(10)
            ->get()
            ->map(fn ($o) => [
                'id'         => $o->id,
                'status'     => $o->status,
                'total'      => (float) $o->items->sum('subtotal'),
                'customer'   => $o->user?->name ?? 'Guest',
                'created_at' => $o->created_at->diffForHumans(),
            ]);

        return Inertia::render('Seller/Dashboard', [
            'stores'          => $stores,
            'stats'           => $stats,
            'orders'          => $recentOrders,
            'weeklySales'     => $weeklySales,
            'orderStatuses'   => $orderStatuses,
        ]);
    }
}
