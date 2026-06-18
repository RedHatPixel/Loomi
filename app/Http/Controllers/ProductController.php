<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Support\PaginationData;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function index(Request $request): Response
    {
        $search     = $request->string('search')->toString();
        $categoryId = $request->integer('category') ?: null;
        $sort       = $request->string('sort', 'latest')->toString();
        $minPrice = $request->float('min_price') ?: null;
        $maxPrice = $request->float('max_price') ?: null;

        $paginated = Product::with(['store', 'images', 'category'])
            ->where('is_published', true)
            ->whereHas('store', fn ($q) => $q->where('is_active', true))
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->when($categoryId, fn ($q) => $q->where('category_id', $categoryId))
            ->when($minPrice, fn ($q) => $q->where('price', '>=', $minPrice))
            ->when($maxPrice, fn ($q) => $q->where('price', '<=', $maxPrice))
            ->when($sort === 'price_asc',  fn ($q) => $q->orderBy('price', 'asc'))
            ->when($sort === 'price_desc', fn ($q) => $q->orderBy('price', 'desc'))
            ->when($sort === 'latest',     fn ($q) => $q->latest())
            ->paginate(24)
            ->withQueryString();

        return Inertia::render('Shop/Product/Index', [
            'products'   => PaginationData::from($paginated),
            'categories' => Category::orderBy('name', 'asc')->get(),
            'filters'    => [
                'search'    => $search,
                'category'  => $categoryId,
                'sort'      => $sort,
                'min_price' => $minPrice,
                'max_price' => $maxPrice,
            ],
        ]);
    }

    public function show(Product $product): Response
    {
        abort_if(!$product->is_published, 404);

        $product->load(['store', 'images', 'category']);

        abort_if(!$product->store->is_active, 404);

        $related = Product::with(['images'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_published', true)
            ->whereHas('store', fn ($q) => $q->where('is_active', true))
            ->limit(4)
            ->get();

        return Inertia::render('Shop/Product/Show', [
            'product' => [
                'id'          => $product->id,
                'name'        => $product->name,
                'slug'        => $product->slug,
                'description' => $product->description,
                'price'       => $product->price,
                'stock'       => $product->stock,
                'category'    => $product->category,
                'store'       => ['id' => $product->store->id, 'name' => $product->store->name, 'slug' => $product->store->slug],
                'images'      => $product->images->map(fn ($img) => $img->path),
            ],
            'related' => $related->map(fn ($p) => [
                'id'    => $p->id,
                'name'  => $p->name,
                'slug'  => $p->slug,
                'price' => $p->price,
                'image' => $p->images->first()?->path,
            ]),
        ]);
    }
}
