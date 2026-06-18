<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();

        $cartCount = 0;
        $activeOrders = 0;
        $notifications = [];
        $unreadNotificationCount = 0;
        $sellerPendingOrders = 0;
        $pendingStores = 0;
        $pendingOrdersAdmin = 0;

        if ($user) {
            $user->loadMissing('roles');

            $cart = \App\Models\Cart::where('user_id', $user->id)->first();
            if ($cart) {
                $cartCount = $cart->items()->sum('quantity');
            }

            $activeOrders = \App\Models\Order::where('user_id', $user->id)
                ->whereIn('status', ['pending', 'confirmed', 'shipped'])
                ->count();

            $unreadNotificationCount = DatabaseNotification::where('notifiable_id', $user->id)
                ->where('notifiable_type', \App\Models\User::class)
                ->whereNull('read_at')
                ->count();

            // Seller pending orders count
            if ($user->isSeller()) {
                $storeIds = $user->stores()->pluck('id');
                $sellerPendingOrders = \App\Models\OrderItem::whereIn('store_id', $storeIds)
                    ->where('status', 'pending')
                    ->distinct('order_id')
                    ->count('order_id');
            }

            // Admin badge counts
            if ($user->isAdmin()) {
                $pendingStores = \App\Models\Store::where('is_active', false)->count();
                $pendingOrdersAdmin = \App\Models\Order::where('status', 'pending')->count();
            }

            $notifications = DatabaseNotification::where('notifiable_id', $user->id)
                ->where('notifiable_type', \App\Models\User::class)
                ->latest()
                ->limit(20)
                ->get()
                ->map(fn ($n) => [
                    'id'         => $n->id,
                    'type'       => $n->type,
                    'data'       => $n->data,
                    'read_at'    => $n->read_at?->toDateTimeString(),
                    'created_at' => $n->created_at->toDateTimeString(),
                ]);
        }

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user ? [
                    ...$user->toArray(),
                    'is_seller' => $user->isSeller(),
                    'is_admin'  => $user->isAdmin(),
                ] : null,
            ],
            'cart_count'               => $cartCount,
            'active_orders'            => $activeOrders,
            'notifications'             => $notifications,
            'unread_notification_count'  => $unreadNotificationCount,
            'seller_pending_orders'      => $sellerPendingOrders ?? 0,
            'pending_stores'             => $pendingStores,
            'pending_orders_admin'       => $pendingOrdersAdmin,
        ];
    }
}
