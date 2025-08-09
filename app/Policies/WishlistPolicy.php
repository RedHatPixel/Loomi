<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class WishlistPolicy
{
    public function create(User $user): bool
    {
        if (Auth::guest()) {
            return false;
        }

        return $user->id === Auth::id();
    }

    public function delete(User $user, Wishlist $wishlist): bool
    {
        return $wishlist->user_id === $user->id;
    }
}
