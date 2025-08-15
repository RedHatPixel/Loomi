<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        $collections = session('collections');
        if (!$collections) {
            return redirect()->route('user.index')
                ->withErrors('You are missing a product to order');
        }
        return view('checkout', compact('collections'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $collections = [];
        $total_product = 0;
        $total_amount = 0;
        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['product_id']);
            if ($product) {
                $collections['products'][] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'total_price' => $product->price * $item['quantity']
                ];
                $total_amount += $product->price * $item['quantity'];
                $total_product += 1;
            }
        }

        $collections['total_product'] = $total_product;
        $collections['total_amount'] = $total_amount;

        session(['collections' => $collections]);
        return redirect()->route('checkout.index')
            ->with('success', 'Please review your order.');
    }
}
