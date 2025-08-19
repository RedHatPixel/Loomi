<?php

namespace App\Policies;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProfilePolicy
{
    public function store(User $user): bool
    {
        if (Auth::guest()) {
            return false;
        }

        return $user->id === Auth::id();
    }

    public function update(User $user, Profile $profile): bool
    {
        return $profile->user_id === $user->id;
    }
}
