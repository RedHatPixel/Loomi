<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function home()
    {
        $featuredProducts = Product::with('primaryImage')->inRandomOrder()->take(8)->get();

        $latestProducts = Product::with('primaryImage')->with('yourWishlist')
            ->orderBy('created_at', 'desc')->limit(10)->get();

        $lowestProducts = Product::with('primaryImage')->with('yourWishlist')
            ->orderBy('price', 'asc')->limit(10)->get();

        $categories = Category::all();

        return view('welcome', compact('featuredProducts', 'latestProducts', 'lowestProducts', 'categories'));
    }

    public function index()
    {
        $this->authorize('view', User::class);

        return view('user.index');
    }

    public function edit()
    {
        $this->authorize('view', User::class);

        return view('user.edit');
    }

    public function ban(User $user)
    {
        $this->authorize('ban', [User::class, Auth::user()]);

        $user->update([
            'ban' => !$user->ban
        ]);

        if ($user->ban) {
            return redirect()->back()
                ->with('success', 'User ' . $user->name . ' account was banned');
        } else {
            return redirect()->back()
                ->with('success', 'User ' . $user->name . ' account was un banned');
        }
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', [User::class, Auth::user()]);

        $user->delete();
        return redirect()->back()
            ->with('success', 'User ' . $user->name . ' account was deleted');
    }
}
