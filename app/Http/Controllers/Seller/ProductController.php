<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Notifications\ProductCreated;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function index(Request $request): Response
    {
        $user = $request->user();
        $storeIds = $user->stores()->pluck('id');

        if ($storeIds->isEmpty()) {
            return Inertia::render('Seller/Products', [
                'products'   => [],
                'stores'     => [],
                'categories' => Category::orderBy('name')->get(),
            ]);
        }

        $storeId = $request->integer('store_id') ?: null;
        $search  = $request->string('search')->toString();

        $products = Product::with(['store', 'category', 'images'])
            ->whereIn('store_id', $storeIds)
            ->when($storeId, fn ($q) => $q->where('store_id', $storeId))
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Seller/Products', [
            'products'   => $products,
            'stores'     => $user->stores()->get(['id', 'name', 'slug']),
            'categories' => Category::orderBy('name')->get(['id', 'name']),
            'filters'    => [
                'store_id' => $storeId,
                'search'   => $search,
            ],
        ]);
    }

    public function create(Request $request): Response
    {
        $user = $request->user();
        $stores = $user->stores()->get(['id', 'name', 'slug']);

        if ($stores->isEmpty()) {
            return redirect()->route('seller.create')->with('error', 'Create a store first.');
        }

        return Inertia::render('Seller/ProductCreate', [
            'stores'     => $stores,
            'categories' => Category::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();
        $storeIds = $user->stores()->pluck('id');

        $validated = $request->validate([
            'store_id'     => ['required', 'integer', 'in:' . $storeIds->implode(',')],
            'category_id'  => ['nullable', 'integer', 'exists:categories,id'],
            'name'         => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string', 'max:5000'],
            'price'        => ['required', 'numeric', 'min:0'],
            'stock'        => ['required', 'integer', 'min:0'],
            'is_published' => ['boolean'],
            'image'        => ['nullable', 'string', 'max:500'],
        ]);

        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(6);
        $validated['is_published'] = $request->boolean('is_published', false);

        $product = Product::create($validated);

        // Save product image if provided (store absolute URL as-is)
        if (!empty($validated['image'])) {
            $product->images()->create([
                'path'       => $validated['image'],
                'sort_order' => 0,
            ]);
        }

        $user->notify(new ProductCreated($product));

        return redirect()->route('seller.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Request $request, Product $product): Response
    {
        $user = $request->user();
        $storeIds = $user->stores()->pluck('id');

        abort_if(!$storeIds->contains($product->store_id), 403);

        $product->load('images');

        return Inertia::render('Seller/ProductEdit', [
            'product'    => $product,
            'stores'     => $user->stores()->get(['id', 'name', 'slug']),
            'categories' => Category::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $user = $request->user();
        $storeIds = $user->stores()->pluck('id');

        abort_if(!$storeIds->contains($product->store_id), 403);

        $validated = $request->validate([
            'store_id'     => ['required', 'integer', 'in:' . $storeIds->implode(',')],
            'category_id'  => ['nullable', 'integer', 'exists:categories,id'],
            'name'         => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string', 'max:5000'],
            'price'        => ['required', 'numeric', 'min:0'],
            'stock'        => ['required', 'integer', 'min:0'],
            'is_published' => ['boolean'],
            'image'        => ['nullable', 'string', 'max:500'],
        ]);

        $validated['is_published'] = $request->boolean('is_published', false);

        $product->update($validated);

        // Update product image
        $product->load('images');
        if (!empty($validated['image'])) {
            if ($product->images->isNotEmpty()) {
                $product->images()->first()->update(['path' => $validated['image']]);
            } else {
                $product->images()->create([
                    'path'       => $validated['image'],
                    'sort_order' => 0,
                ]);
            }
        } elseif ($validated['image'] === '' && $product->images->isNotEmpty()) {
            // Image was removed — delete product images
            $product->images()->delete();
        }

        return redirect()->route('seller.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        $user = $request->user();
        $storeIds = $user->stores()->pluck('id');

        abort_if(!$storeIds->contains($product->store_id), 403);

        $product->delete();

        return redirect()->route('seller.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function toggle(Request $request, Product $product): RedirectResponse
    {
        $user = $request->user();
        $storeIds = $user->stores()->pluck('id');

        abort_if(!$storeIds->contains($product->store_id), 403);

        $product->update(['is_published' => !$product->is_published]);

        return back()->with('success', $product->is_published
            ? 'Product published.'
            : 'Product unpublished.');
    }
}
