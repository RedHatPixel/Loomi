<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index($products)
    {
        if (!$products) {
            return redirect()->route('user.index')
                ->withErrors('No products was selected.');
        }

        return view('checkout', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $products = [];
        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['product_id']);
            if ($product) {
                $products[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                ];
            }
        }

        return redirect()->route('checkout.index', ['products' => $products])
            ->with('success', 'Please review your order.');
    }
}
