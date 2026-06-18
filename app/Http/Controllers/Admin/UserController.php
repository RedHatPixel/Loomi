<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index(Request $request): Response
    {
        $query = User::with('roles');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($role = $request->get('role')) {
            $query->whereHas('roles', fn ($q) => $q->where('name', $role));
        }

        $users = $query->latest()
            ->paginate(15)
            ->through(fn ($u) => [
                'id'         => $u->id,
                'name'       => $u->name,
                'email'      => $u->email,
                'roles'      => $u->roles->pluck('name'),
                'stores_count' => $u->stores()->count(),
                'orders_count' => $u->orders()->count(),
                'created_at' => $u->created_at->format('M d, Y'),
            ]);

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'filters' => [
                'search' => $request->get('search', ''),
                'role'   => $request->get('role', ''),
            ],
        ]);
    }

    public function show(User $user): Response
    {
        $user->load('roles');

        $stores = $user->stores()->withCount(['products' => fn ($q) => $q->where('is_published', true)])
            ->get()
            ->map(fn ($s) => [
                'id'             => $s->id,
                'name'           => $s->name,
                'slug'           => $s->slug,
                'is_active'      => $s->is_active,
                'products_count' => $s->products_count,
                'created_at'     => $s->created_at->format('M d, Y'),
            ]);

        $orders = $user->orders()->latest()->limit(10)->get()->map(fn ($o) => [
            'id'         => $o->id,
            'status'     => $o->status,
            'total'      => (float) $o->total,
            'created_at' => $o->created_at->diffForHumans(),
        ]);

        return Inertia::render('Admin/Users/Show', [
            'userData' => [
                'id'         => $user->id,
                'name'       => $user->name,
                'email'      => $user->email,
                'roles'      => $user->roles->pluck('name'),
                'created_at' => $user->created_at->format('M d, Y'),
            ],
            'stores' => $stores,
            'orders' => $orders,
        ]);
    }

    public function updateRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|string|in:customer,seller,admin',
        ]);

        $user->roles()->sync(\App\Models\Role::where('name', $validated['role'])->first()->id);

        return redirect()->back()->with('success', "User role updated to {$validated['role']}.");
    }

    public function destroy(User $user)
    {
        if ($user->isAdmin()) {
            return redirect()->back()->with('error', 'Cannot delete an admin user.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
