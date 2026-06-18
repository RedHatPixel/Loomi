<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StoreController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Store::with('user');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('user', fn ($q) => $q->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->has('active')) {
            $query->where('is_active', $request->boolean('active'));
        }

        $stores = $query->withCount(['products' => fn ($q) => $q->where('is_published', true)])
            ->latest()
            ->paginate(15)
            ->through(fn ($s) => [
                'id'             => $s->id,
                'name'           => $s->name,
                'slug'           => $s->slug,
                'is_active'      => $s->is_active,
                'owner'          => $s->user->name,
                'products_count' => $s->products_count,
                'created_at'     => $s->created_at->format('M d, Y'),
            ]);

        return Inertia::render('Admin/Stores/Index', [
            'stores'  => $stores,
            'filters' => [
                'search' => $request->get('search', ''),
                'active' => $request->get('active', ''),
            ],
        ]);
    }

    public function show(Store $store): Response
    {
        $store->load('user');

        $products = $store->products()->with('category')
            ->latest()
            ->paginate(10)
            ->through(fn ($p) => [
                'id'           => $p->id,
                'name'         => $p->name,
                'slug'         => $p->slug,
                'price'        => (float) $p->price,
                'stock'        => $p->stock,
                'is_published' => $p->is_published,
                'category'     => $p->category?->name,
            ]);

        return Inertia::render('Admin/Stores/Show', [
            'storeData' => [
                'id'          => $store->id,
                'name'        => $store->name,
                'slug'        => $store->slug,
                'description' => $store->description,
                'story'       => $store->story,
                'logo'        => $store->logo,
                'is_active'   => $store->is_active,
                'owner'       => $store->user->name,
                'owner_id'    => $store->user->id,
                'created_at'  => $store->created_at->format('M d, Y'),
            ],
            'products' => [
                'data' => $products->items(),
                'meta'  => [
                    'current_page' => $products->currentPage(),
                    'last_page'    => $products->lastPage(),
                    'total'        => $products->total(),
                    'per_page'     => $products->perPage(),
                ],
                'links' => $products->linkCollection()->toArray(),
            ],
        ]);
    }

    public function toggle(Store $store)
    {
        $store->update(['is_active' => !$store->is_active]);

        $status = $store->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "Store {$status} successfully.");
    }

    public function destroy(Store $store)
    {
        $store->delete();

        return redirect()->route('admin.stores.index')->with('success', 'Store deleted successfully.');
    }
}
