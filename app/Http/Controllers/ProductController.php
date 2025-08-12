<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => 'sometimes|min:0|max:255',
            'category' => 'sometimes|string|max:255',
            'min_price' => 'sometimes|integer|min:0|max:99999',
            'max_price' => 'sometimes|integer|min:0|max:99999',
            'rating' => 'sometimes|integer|min:0|max:5',
            'sort' => 'sometimes|string|max:100'
        ]);

        $search = $validated['search'] ?? null;
        $category = $validated['category'] ?? null;
        $minPrice = $validated['min_price'] ?? null;
        $maxPrice = $validated['max_price'] ?? null;
        $rating = $validated['rating'] ?? null;
        $sort = $validated['sort'] ?? null;

        // Filter product
        $products = Product::with([
            'primaryImage',
            'userWishlist'
        ])
            ->withAvg('ratings', 'stars')
            ->withSum('sales', 'quantity')

            // Search by title
            ->when($search, function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%");
            })

            // Filter by category name
            ->when($category, function ($query) use ($category) {
                $query->whereHas('categories', function ($q) use ($category) {
                    $q->where('category', $category);
                });
            })

            // Filter by price
            ->when($minPrice, function ($query) use ($minPrice) {
                $query->where('price', '>=', $minPrice);
            })
            ->when($maxPrice, function ($query) use ($maxPrice) {
                $query->where('price', '<=', $maxPrice);
            })

            // Filter by rating
            ->when($rating, function ($query) use ($rating) {
                $query->having('ratings_avg_stars', '>=', $rating);
            })

            // Sorting logic
            ->when($sort, function ($query) use ($sort) {
                switch ($sort) {
                    case 'latest':
                        $query->orderBy('created_at', 'desc');
                        break;
                    case 'top_sales':
                        $query->orderBy('sales_sum_quantity', 'desc');
                        break;
                    case 'high_ratings':
                        $query->orderBy('ratings_avg_stars', 'desc');
                        break;
                    case 'price_low':
                        $query->orderBy('price', 'asc');
                        break;
                    case 'price_high':
                        $query->orderBy('price', 'desc');
                        break;
                    default:
                        $query->orderBy('created_at', 'asc');
                        break;
                }
            })
            ->paginate(28)
            ->withQueryString();

        // Get all available categories
        $categories = Category::all();

        // Get random available products
        $featuredProducts = Product::with(['primaryImage', 'userWishlist'])
            ->inRandomOrder()->take(12)->get();

        return view('product.index', compact('products', 'featuredProducts', 'categories'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load([
            'images',
            'primaryImage',
            'userWishlist',
            'categories',
            'creator',
        ])
            ->loadAvg('ratings', 'stars')
            ->loadSum('sales', 'quantity');

        $ratings = $product->ratings()->with('user')->latest()->paginate(10);

        return view('product.show', compact('product', 'ratings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('product.create');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $user = Auth::getUser();

        $product->load(['images', 'categories', 'creator']);
        return view('product.edit', compact('product'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:20',
            'price' => 'required|integer|min:0|max:99999',
            'quantity' => 'required|integer|min:0|max:99999',
            'is_active' => 'sometimes|boolean',
        ]);

        $product = Product::create($validated);

        return redirect()
            ->route('products.show', $product)
            ->with('success', 'Product was created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|min:20',
            'price' => 'sometimes|integer|min:0|max:99999',
            'quantity' => 'sometimes|integer|min:0|max:99999',
            'is_active' => 'sometimes|boolean'
        ]);

        $product->update($validated);

        return redirect()
            ->route('products.show', $product)
            ->with('success', 'Product was updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()
            ->route('products.index')
            ->with('success', 'Product was deleted successfully.');
    }
}
