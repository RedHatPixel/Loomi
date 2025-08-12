<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::with('primaryImage')->with('userWishlist')
            ->inRandomOrder()->take(12)->get();

        $lowestProducts = Product::with('primaryImage')->with('userWishlist')
            ->orderBy('price', 'asc')->limit(12)->get();

        $carts = Cart::where('user_id', Auth::id())
            ->with(['product.primaryImage'])
            ->get();

        return view('cart', compact('carts', 'featuredProducts', 'lowestProducts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        Cart::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('cart.index')
            ->with('success', 'Item added to cart.');
    }

    public function update(Request $request, Cart $cart)
    {
        $request->validate([
            'quantity' => 'sometimes|integer|min:1'
        ]);

        if ($request->quantity == $cart->quantity) {
            return redirect()->back()->with('info', 'No changes made to the cart item.');
        }

        $cart->update([
            'quantity' => $request->quantity ?? $cart->quantity,
        ]);

        return redirect()->back()->with('success', 'Cart updated.');
    }

    public function destroy(Cart $cart)
    {
        $cart->delete();
        return redirect()->back()->with('success', 'Item removed from cart.');
    }

    public function clear()
    {
        $userId = Auth::id();
        Cart::where('user_id', $userId)->delete();

        return redirect()->back()->with('success', 'All cart items deleted.');
    }
}
