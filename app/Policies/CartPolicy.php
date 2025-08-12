<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartPolicy
{
    public function create(User $user): bool
    {
        if (Auth::guest()) {
            return false;
        }

        return $user->id === Auth::id();
    }

    public function update(User $user, Cart $cart): bool
    {
        return $cart->user_id === $user->id;
    }

    public function delete(User $user, Cart $cart): bool
    {
        return $cart->user_id === $user->id;
    }

    public function clear(User $user): bool
    {
        if (Auth::guest()) {
            return false;
        }

        return $user->id === Auth::id();
    }
}
