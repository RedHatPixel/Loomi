<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Product::with(['store', 'category']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('store', fn ($q) => $q->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->has('published')) {
            $query->where('is_published', $request->boolean('published'));
        }

        if ($storeId = $request->get('store_id')) {
            $query->where('store_id', $storeId);
        }

        $products = $query->latest()
            ->paginate(15)
            ->through(fn ($p) => [
                'id'           => $p->id,
                'name'         => $p->name,
                'slug'         => $p->slug,
                'price'        => (float) $p->price,
                'stock'        => $p->stock,
                'is_published' => $p->is_published,
                'store'        => $p->store->name,
                'category'     => $p->category?->name,
                'created_at'   => $p->created_at->format('M d, Y'),
            ]);

        $storeOptions = \App\Models\Store::select('id', 'name')->get();

        return Inertia::render('Admin/Products/Index', [
            'products' => $products,
            'storeOptions' => $storeOptions,
            'filters' => [
                'search'     => $request->get('search', ''),
                'published'  => $request->get('published', ''),
                'store_id'   => $request->get('store_id', ''),
            ],
        ]);
    }

    public function show(Product $product): Response
    {
        $product->load(['store', 'category', 'images']);

        return Inertia::render('Admin/Products/Show', [
            'productData' => [
                'id'           => $product->id,
                'name'         => $product->name,
                'slug'         => $product->slug,
                'description'  => $product->description,
                'price'        => (float) $product->price,
                'stock'        => $product->stock,
                'is_published' => $product->is_published,
                'store'        => $product->store->name,
                'store_id'     => $product->store->id,
                'category'     => $product->category?->name ?? 'Uncategorized',
                'images'       => $product->images->pluck('path'),
                'created_at'   => $product->created_at->format('M d, Y'),
            ],
        ]);
    }

    public function toggle(Product $product)
    {
        $product->update(['is_published' => !$product->is_published]);

        return redirect()->back()->with('success', 'Product status updated.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
