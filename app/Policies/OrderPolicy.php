<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\Status;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class OrderPolicy
{
    public function store(User $user)
    {
        return $user->id === Auth::id();
    }

    public function cancel(User $user, Status $status)
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $status->name === 'pending';
    }

    public function restore(User $user, Status $status)
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $status->name === 'cancelled' || $status->name === 'received' || $status->name === 'denied';
    }

    public function destroy(User $user, Status $status)
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $status->name === 'cancelled' || $status->name === 'received';
    }

    public function update(User $user)
    {
        return $user->isAdmin();
    }

    public function accept(User $user)
    {
        return $user->isAdmin();
    }

    public function deny(User $user)
    {
        return $user->isAdmin();
    }
}
