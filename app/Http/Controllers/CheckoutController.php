<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        $collections = session('collections');
        if (!$collections) {
            return redirect()->back()
                ->withErrors('You are missing a product to order');
        }

        $addresses = Auth::user()->addresses;

        if ($addresses->isEmpty()) {
            session(['after_address_redirect' => route('checkout.index')]);

            return redirect()->route('address.create')
                ->with('info', 'Create an address before you order');
        }

        return view('checkout', compact('collections', 'addresses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.cart_id' => 'nullable|exists:carts,id'
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
                    'total_price' => $product->price * $item['quantity'],
                    'cart_id' => $item['cart_id'] ?? null
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
