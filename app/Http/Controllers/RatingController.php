<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $this->authorize('store', [ProductRating::class, $product]);

        $validated = $request->validate([
            'stars' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        ProductRating::create([
            'product_id' => $product->id,
            'stars'      => $validated['stars'],
            'comment'    => $validated['comment'] ?? null,
            'rated_by'    => Auth::id(),
        ]);

        return back()->with('success', 'Thanks for your rating!');
    }
}
