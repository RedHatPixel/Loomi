<?php

namespace App\Policies;

use App\Models\Store;
use App\Models\User;

class StorePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(?User $user, Store $store): bool
    {
        return $store->is_active;
    }

    public function create(User $user): bool
    {
        return true; // any authenticated user can create a store
    }

    public function update(User $user, Store $store): bool
    {
        return $user->id === $store->user_id || $user->isAdmin();
    }

    public function delete(User $user, Store $store): bool
    {
        return $user->id === $store->user_id || $user->isAdmin();
    }

    public function restore(User $user, Store $store): bool
    {
        return $user->isAdmin();
    }
}
