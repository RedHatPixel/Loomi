<?php

namespace App\Providers;

use App\Models\Cart;
use App\Models\Wishlist;
use App\Policies\CartPolicy;
use App\Policies\WishlistPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     */
    protected $policies = [
        Wishlist::class => WishlistPolicy::class,
        Cart::class => CartPolicy::class,
    ];

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
