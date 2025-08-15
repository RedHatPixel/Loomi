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

        // Check if the product exists and is available
        $product = Product::findOrFail($request->product_id);
        $cart = Cart::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        // Check if the requested quantity exceeds available stock
        $newQuantity = $request->quantity;
        if ($cart) {
            $newQuantity += $cart->quantity;
        }

        // Ensure the new quantity does not exceed available stock
        if ($newQuantity > $product->quantity) {
            return redirect()->back()
                ->with('info', 'Requested quantity exceeds available stock.');
        }

        // If the product is already in the cart, update the quantity
        if ($cart) {
            $cart->update(['quantity' => $newQuantity]);
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $request->quantity,
            ]);
        }

        return redirect()->route('cart.index')
            ->with('success', 'Item added to cart.');
    }

    public function update(Request $request, Cart $cart)
    {
        $request->validate([
            'quantity' => 'sometimes|integer|min:1'
        ]);

        // Validate the cart item exists
        $product = $cart->product;
        $newQuantity = $request->quantity ?? $cart->quantity;

        if ($newQuantity > $product->quantity) {
            return redirect()->back()
                ->with('info', 'Requested quantity exceeds available stock.');
        }

        if ($newQuantity == $cart->quantity) {
            return redirect()->back()
                ->with('info', 'No changes made to the cart item.');
        }

        $cart->update([
            'quantity' => $newQuantity,
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
