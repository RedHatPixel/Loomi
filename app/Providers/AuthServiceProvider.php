<?php

namespace App\Providers;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductRating;
use App\Models\Profile;
use App\Models\User;
use App\Models\Wishlist;
use App\Policies\AddressPolicy;
use App\Policies\CartPolicy;
use App\Policies\OrderPolicy;
use App\Policies\ProductPolicy;
use App\Policies\ProfilePolicy;
use App\Policies\RatePolicy;
use App\Policies\UserPolicy;
use App\Policies\WishlistPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     */
    protected $policies = [
        Product::class => ProductPolicy::class,
        Wishlist::class => WishlistPolicy::class,
        Cart::class => CartPolicy::class,
        Address::class => AddressPolicy::class,
        Profile::class => ProfilePolicy::class,
        ProductRating::class => RatePolicy::class,
        Order::class => OrderPolicy::class,
        User::class => UserPolicy::class
    ];

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
