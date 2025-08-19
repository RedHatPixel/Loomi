<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
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
            'yourWishlist'
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
                    $q->where('name', $category);
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

        // Featured products (random picks)
        $featuredProducts = Product::with(['primaryImage', 'yourWishlist'])
            ->inRandomOrder()
            ->take(10)
            ->get();

        // Trending products (based on total sales)
        $trendingProducts = Product::with(['primaryImage', 'yourWishlist'])
            ->withCount('sales')
            ->orderByDesc('sales_count')
            ->take(10)
            ->get();

        return view('product.index', compact('products', 'featuredProducts', 'trendingProducts', 'categories'));
    }

    public function show(Product $product)
    {
        $product->load([
            'images',
            'primaryImage',
            'categories',
            'yourWishlist',
        ])
            ->loadAvg('ratings', 'stars')
            ->loadSum('sales', 'quantity');

        $ratings = $product->ratings()->with('user')->latest()->paginate(10);

        return view('product.show', compact('product', 'ratings'));
    }

    public function random()
    {
        $product = Product::inRandomOrder()->first();

        if (!$product) {
            return redirect()->route('products.index')
                ->with('error', 'No products available right now.');
        }

        return redirect()->route('products.show', $product);
    }


    public function create(Request $request)
    {
        $this->authorize('create', Product::class);

        $categories = Category::all();
        return view('admin.product.create', compact('categories'));
    }

    public function edit(Product $product)
    {
        $this->authorize('edit', Product::class);

        $product->load(['images', 'categories', 'creator']);
        $categories = Category::all();
        return view('admin.product.edit', compact('product', 'categories'));
    }

    public function store(Request $request)
    {
        $this->authorize('store', Product::class);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'images.*' => 'required|image|mimes:jpg,jpeg,png,gif,avif,webp|max:2048',
        ]);

        $product = Product::create([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'created_by' => Auth::id()
        ]);

        $product->categories()->sync($request->categories);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                $product->images()->create([
                    'image_path' => $path,
                    'is_primary' => $index === 0,
                ]);
            }
        }

        return redirect()->route('admin.products')
            ->with('success', 'Product created successfully.');
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'images.*' => 'required|image|mimes:jpg,jpeg,png,gif,avif,webp|max:2048',
            'remove_images.*' => 'sometimes|exists:product_images,id'
        ]);

        $product->update([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'quantity' => $request->quantity,
        ]);

        $product->categories()->sync($request->categories);

        if ($request->filled('remove_images')) {
            $ids = $request->remove_images;
            foreach ($product->images()->whereIn('id', $ids)->get() as $image) {
                $image->delete();
            }
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                $product->images()->create([
                    'image_path' => $path,
                    'is_primary' => $index === 0,
                ]);
            }
        }

        return redirect()
            ->route('admin.products')
            ->with('success', 'Product was updated successfully.');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        $product->delete();
        return redirect()
            ->route('admin.products')
            ->with('success', 'Product was deleted successfully.');
    }
}
