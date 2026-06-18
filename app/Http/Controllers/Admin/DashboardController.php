<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $totalUsers = User::count();
        $totalStores = Store::count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalRevenue = (float) OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereNotIn('orders.status', ['cancelled'])
            ->sum('order_items.subtotal');

        $pendingOrders = Order::where('status', 'pending')->count();
        $pendingStores = Store::where('is_active', false)->count();

        $recentUsers = User::latest()->limit(8)->get()->map(fn ($u) => [
            'id'         => $u->id,
            'name'       => $u->name,
            'email'      => $u->email,
            'created_at' => $u->created_at->diffForHumans(),
        ]);

        $recentOrders = Order::with('user')
            ->latest()
            ->limit(8)
            ->get()
            ->map(fn ($o) => [
                'id'         => $o->id,
                'status'     => $o->status,
                'total'      => (float) $o->total,
                'customer'   => $o->user?->name ?? 'Guest',
                'created_at' => $o->created_at->diffForHumans(),
            ]);

        // Weekly sales (last 7 days)
        $weeklySales = collect();
        $salesQuery = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereNotIn('orders.status', ['cancelled'])
            ->selectRaw("DATE(orders.created_at) as date, SUM(order_items.subtotal) as total")
            ->groupBy('date')
            ->pluck('total', 'date');

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $weeklySales->push([
                'date'  => now()->subDays($i)->format('D'),
                'total' => (float) ($salesQuery[$date] ?? 0),
            ]);
        }

        // Order status breakdown
        $statusBreakdown = OrderItem::selectRaw("status, COUNT(*) as count")
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $allStatuses = ['pending', 'confirmed', 'shipped', 'delivered', 'cancelled'];
        $orderStatuses = collect($allStatuses)->map(fn ($s) => [
            'status' => $s,
            'count'  => (int) ($statusBreakdown[$s] ?? 0),
        ]);

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'total_users'     => $totalUsers,
                'total_stores'    => $totalStores,
                'total_products'  => $totalProducts,
                'total_orders'    => $totalOrders,
                'total_revenue'   => $totalRevenue,
                'pending_orders'  => $pendingOrders,
                'pending_stores'  => $pendingStores,
            ],
            'recentUsers'  => $recentUsers,
            'recentOrders' => $recentOrders,
            'weeklySales'  => $weeklySales,
            'orderStatuses' => $orderStatuses,
        ]);
    }
}
