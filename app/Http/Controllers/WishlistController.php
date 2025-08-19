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
        $featuredProducts = Product::with('primaryImage')->with('yourWishlist')
            ->inRandomOrder()->take(10)->get();

        $lowestProducts = Product::with('primaryImage')->with('yourWishlist')
            ->orderBy('price', 'asc')->limit(10)->get();

        $wishlists = Wishlist::where('user_id', Auth::id())
            ->with(['product.primaryImage'])
            ->get();

        return view('wishlist', compact('wishlists', 'featuredProducts', 'lowestProducts'));
    }

    public function store(Request $request)
    {
        $this->authorize('store', Wishlist::class);

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
        $this->authorize('destroy', $wishlist);

        $wishlist->delete();
        return back()->with('success', 'Item removed from wishlist.');
    }

    public function clear()
    {
        $this->authorize('clear', Wishlist::class);

        $userId = Auth::id();
        Wishlist::where('user_id', $userId)->delete();

        return redirect()->back()->with('success', 'All wishlist items deleted.');
    }
}
