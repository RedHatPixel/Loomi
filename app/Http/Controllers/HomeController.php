<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Support\PaginationData;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $categoryId = $request->integer('category') ?: null;
        $sort       = $request->string('sort', 'latest')->toString();

        // Products section: only filtered by category, no search
        $paginated = Product::with(['store', 'images', 'category'])
            ->where('is_published', true)
            ->whereHas('store', fn ($q) => $q->where('is_active', true))
            ->when($categoryId, fn ($q) => $q->where('category_id', $categoryId))
            ->when($sort === 'price_asc',  fn ($q) => $q->orderBy('price', 'asc'))
            ->when($sort === 'price_desc', fn ($q) => $q->orderBy('price', 'desc'))
            ->when($sort === 'latest',     fn ($q) => $q->latest())
            ->paginate(20)
            ->withQueryString();
        $products = PaginationData::from($paginated);

        // Trending products: own collection (random selection)
        $trendingProducts = Product::with(['store', 'images', 'category'])
            ->where('is_published', true)
            ->whereHas('store', fn ($q) => $q->where('is_active', true))
            ->inRandomOrder()
            ->limit(5)
            ->get();

        $featuredStores = Store::withCount('products')
            ->whereHas('products')
            ->inRandomOrder()
            ->limit(6)
            ->get();

        $categories = Category::orderBy('name', 'asc')->get();

        return Inertia::render('Shop/Home/Index', [
            'products'          => $products,
            'trendingProducts'  => $trendingProducts,
            'featuredStores'    => $featuredStores,
            'categories'        => $categories,
            'filters'           => [
                'category' => $categoryId,
                'sort'     => $sort,
            ],
        ]);
    }
}
