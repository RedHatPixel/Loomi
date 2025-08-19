<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductRating;
use App\Models\ProductSale;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        // 📦 Counts
        $productsCount = Product::count();
        $ordersCount   = Order::count();
        $usersCount    = User::count();
        $revenue       = ProductSale::sum('price_at_sale');

        // Group orders by date and sum totals
        $sales = ProductSale::select(
            DB::raw('DATE(sold_at) as date'),
            DB::raw('SUM(price_at_sale) as sale')
        )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $salesDates = $sales->pluck('date');
        $salesData = $sales->pluck('sale');

        // ⭐ Top Rated Products
        $topRatings = ProductRating::with(['product:id,title', 'user:id,name'])
            ->orderByDesc('stars')
            ->take(5)
            ->get()
            ->map(function ($rating) {
                return [
                    'product_title' => $rating->product->title ?? null,
                    'user_name'    => $rating->user->name ?? null,
                    'stars'        => $rating->stars,
                ];
            });

        // Recent Orders from user
        $recentOrders = Order::with(['user:id,name'])
            ->latest()
            ->take(15)
            ->get(['id', 'user_id', 'total_amount', 'status_id']);

        // 🏷️ Categories
        $categories = Category::all();

        return view('admin.index', compact(
            'productsCount',
            'ordersCount',
            'usersCount',
            'revenue',
            'salesDates',
            'salesData',
            'recentOrders',
            'topRatings',
            'categories',
        ));
    }

    public function products(Request $request)
    {
        $products = Product::with('creator');

        // 🔎 Search by title
        if ($request->filled('search')) {
            $products->where('title', 'like', '%' . $request->search . '%');
        }

        // 👤 Filter by owner
        if ($request->filled('owner')) {
            $products->where('created_by', $request->owner);
        }

        // 📦 Filter by stock
        if ($request->filled('stock')) {
            if ($request->stock === 'in') {
                $products->where('quantity', '>', 0);
            } elseif ($request->stock === 'out') {
                $products->where('quantity', '=', 0);
            }
        }

        $products = $products->paginate(20)->withQueryString();

        $admins = User::Admins()->get();

        return view('admin.product.index', compact('products', 'admins'));
    }

    public function orders()
    {
        $pendingOrders = Order::whereHas('status', function ($q) {
            $q->where('name', 'pending');
        })->get();

        $deniedOrders = Order::whereHas('status', function ($q) {
            $q->where('name', 'denied');
        })->get();

        $activeOrders = Order::whereHas('status', function ($q) {
            $q->whereIn('name', ['processing', 'shipped', 'delivered']);
        })->get();

        $activeStatuses = Status::whereIn('name', [
            'pending',
            'denied',
            'processing',
            'shipped',
            'delivered',
        ])->get();

        return view('admin.order.index', compact(
            'pendingOrders',
            'deniedOrders',
            'activeOrders',
            'activeStatuses'
        ));
    }

    public function order(Order $order)
    {
        $order->load(['user', 'items.product', 'status']);
        return view('admin.order.show', compact('order'));
    }

    public function users(Request $request)
    {
        $users = User::query();

        // 🔎 Search by name or email
        if ($request->filled('search')) {
            $users->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // 🎭 Filter by role
        if ($request->filled('role')) {
            $users->where('role', $request->role);
        }

        // 🚫 Filter by ban status
        if ($request->filled('ban')) {
            $users->where('ban', $request->ban);
        }

        // 📑 Use pagination so filters + search are easier to handle
        $users = $users->paginate(20)->withQueryString();

        return view('admin.user.index', compact('users'));
    }

    public function sales(Request $request)
    {
        $query = ProductSale::with(['product', 'buyer']);

        // 🔎 Search by product name or buyer name/email
        if ($search = $request->input('search')) {
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            })->orWhereHas('buyer', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // 📌 Filter by date (optional)
        if ($request->filled('date_from')) {
            $query->whereDate('sold_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('sold_at', '<=', $request->date_to);
        }

        // 📌 Filter by product (optional)
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        // 📌 Paginate results
        $sales = $query->paginate(20);

        return view('admin.sales', compact('sales'));
    }
}
