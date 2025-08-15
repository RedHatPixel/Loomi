<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;

class ProfileController extends Controller
{
    public function storeOrUpdate(Request $request)
    {
        $user = Auth::user();
        $data = $request->only(['first_name', 'last_name', 'contact_number', 'avatar']);

        $profile = Profile::where('user_id', $user->id)->first();

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        if ($profile) {
            $profile->update($data);
        } else {
            $profile = Profile::create(array_merge($data, ['user_id' => $user->id]));
        }

        return redirect()->route('user.index')
            ->with('success', 'Profile saved successfully.');
    }
}
