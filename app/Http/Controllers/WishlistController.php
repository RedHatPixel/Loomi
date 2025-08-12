<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Wishlist;

class WishlistController extends Controller
{

    public function index()
    {
        $featuredProducts = Product::with('primaryImage')->with('userWishlist')
            ->inRandomOrder()->take(12)->get();

        $lowestProducts = Product::with('primaryImage')->with('userWishlist')
            ->orderBy('price', 'asc')->limit(12)->get();

        $wishlists = Wishlist::where('user_id', Auth::id())
            ->with(['product.primaryImage'])
            ->get();

        return view('wishlist', compact('wishlists', 'featuredProducts', 'lowestProducts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        Wishlist::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
        ]);

        return back()->with('success', 'Item added to wishlist.');
    }

    public function destroy(Wishlist $wishlist)
    {
        $wishlist->delete();
        return back()->with('success', 'Item removed from wishlist.');
    }

    public function clear()
    {
        $userId = Auth::id();
        Wishlist::where('user_id', $userId)->delete();

        return redirect()->back()->with('success', 'All wishlist items deleted.');
    }
}
