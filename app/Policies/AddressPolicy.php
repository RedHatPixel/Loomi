<?php

namespace App\Policies;

use App\Models\Address;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AddressPolicy
{
    public function store(User $user): bool
    {
        if (Auth::guest()) {
            return false;
        }

        return $user->id === Auth::id();
    }

    public function destroy(User $user, Address $address): bool
    {
        return $address->user_id === $user->id;
    }
}
