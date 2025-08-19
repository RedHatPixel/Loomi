<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProductPolicy
{
    public function create(User $user)
    {
        return $user->isAdmin() && $user->id === Auth::id();
    }

    public function edit(User $user)
    {
        return $user->isAdmin() && $user->id === Auth::id();
    }

    public function store(User $user)
    {
        return $user->isAdmin() && $user->id === Auth::id();
    }

    public function update(User $user, Product $product)
    {
        return $user->isAdmin() || $user->id === $product->created_by;
    }

    public function delete(User $user, Product $product)
    {
        return $user->isAdmin() || $user->id === $product->created_by;
    }
}
