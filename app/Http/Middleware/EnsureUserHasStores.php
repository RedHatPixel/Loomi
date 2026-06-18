<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasStores
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // If user has seller role but no stores — clean up and redirect to create
        if ($user->isSeller() && $user->stores()->count() === 0) {
            $user->removeRole('seller');
            return redirect()->route('seller.create')
                ->with('info', 'You need to create a store to access the seller dashboard.');
        }

        // If user doesn't have seller or admin role — redirect to create
        if (!$user->isSeller() && !$user->isAdmin()) {
            return redirect()->route('seller.create')
                ->with('info', 'Create a store to get started.');
        }

        return $next($request);
    }
}
