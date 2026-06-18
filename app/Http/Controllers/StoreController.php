<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Notifications\StoreCreated;
use App\Support\PaginationData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StoreController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255', 'unique:stores,name'],
            'slug'        => ['required', 'string', 'max:255', 'unique:stores,slug', 'regex:/^[a-z0-9-]+$/'],
            'description' => ['required', 'string', 'max:1000'],
            'story'       => ['nullable', 'string', 'max:5000'],
            'category'    => ['nullable', 'string', 'max:255'],
            'experience'  => ['nullable', 'string', 'max:255'],
            'logo'        => ['nullable', 'string', 'max:255'],
            'website'     => ['nullable', 'string', 'max:255'],
            'instagram'   => ['nullable', 'string', 'max:255'],
            'tiktok'      => ['nullable', 'string', 'max:255'],
        ]);

        $store = Store::create([
            'user_id'     => $request->user()->id,
            'name'        => $validated['name'],
            'slug'        => $validated['slug'],
            'description' => $validated['description'] ?? '',
            'story'       => $validated['story'] ?? null,
            'logo'        => $validated['logo'] ?? null,
            'website'     => $validated['website'] ?? null,
            'instagram'   => $validated['instagram'] ?? null,
            'tiktok'      => $validated['tiktok'] ?? null,
            'is_active'   => true,
        ]);

        $request->user()->roles()->firstOrCreate(['name' => 'seller']);

        $request->user()->notify(new StoreCreated($store));

        return redirect()->route('home')->with('success', 'Store created successfully!');
    }

    public function index(Request $request): Response
    {
        $search = $request->string('search')->toString();
        $sort   = $request->string('sort', 'name')->toString();

        $stores = Store::withCount('products')
            ->where('is_active', true)
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->when($sort === 'name',   fn ($q) => $q->orderBy('name', 'asc'))
            ->when($sort === 'newest', fn ($q) => $q->latest())
            ->when($sort === 'popular', fn ($q) => $q->orderBy('products_count', 'desc'))
            ->paginate(24)
            ->withQueryString();

        return Inertia::render('Shop/Store/Index', [
            'stores'  => PaginationData::from($stores),
            'filters' => [
                'search' => $search,
                'sort'   => $sort,
            ],
        ]);
    }

    public function show(Request $request, Store $store): Response
    {
        abort_if(!$store->is_active, 404);

        $store->loadCount('products');

        $sort = $request->string('sort', 'latest')->toString();
        $categoryId = $request->integer('category') ?: null;

        $products = Product::with(['images', 'category', 'store'])
            ->where('store_id', $store->id)
            ->where('is_published', true)
            ->when($categoryId, fn ($q) => $q->where('category_id', $categoryId))
            ->when($sort === 'price_asc',  fn ($q) => $q->orderBy('price', 'asc'))
            ->when($sort === 'price_desc', fn ($q) => $q->orderBy('price', 'desc'))
            ->when($sort === 'latest',     fn ($q) => $q->latest())
            ->paginate(20)
            ->withQueryString();

        $categories = Category::whereHas('products', fn ($q) => $q->where('store_id', $store->id))
            ->orderBy('name')
            ->get();

        $featuredProducts = Product::with(['images', 'store'])
            ->where('store_id', '!=', $store->id)
            ->where('is_published', true)
            ->whereHas('store', fn ($q) => $q->where('is_active', true))
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return Inertia::render('Shop/Store/Show', [
            'store'            => $store,
            'products'         => PaginationData::from($products),
            'featuredProducts' => $featuredProducts,
            'categories'       => $categories,
            'filters'          => [
                'sort'     => $sort,
                'category' => $categoryId,
            ],
        ]);
    }
}
