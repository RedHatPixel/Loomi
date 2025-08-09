<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class Controller
{
    public function home()
    {
        $featuredProducts = Product::with('primaryImage')->inRandomOrder()->take(8)->get();

        $latestProducts = Product::with('primaryImage')->with('userWishlist')
            ->orderBy('created_at', 'desc')->limit(12)->get();

        $lowestProducts = Product::with('primaryImage')->with('userWishlist')
            ->orderBy('price', 'asc')->limit(12)->get();

        $categories = Category::all();

        return view('welcome', compact('featuredProducts', 'latestProducts', 'lowestProducts', 'categories'));
    }
}
