<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class WishlistPolicy
{
    public function store(User $user): bool
    {
        if (Auth::guest()) {
            return false;
        }

        return $user->id === Auth::id();
    }

    public function destroy(User $user, Wishlist $wishlist): bool
    {
        return $wishlist->user_id === $user->id;
    }

    public function clear(User $user): bool
    {
        if (Auth::guest()) {
            return false;
        }

        return $user->id === Auth::id();
    }
}
