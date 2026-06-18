<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StoreController extends Controller
{
    public function edit(Request $request): Response
    {
        $user = $request->user();
        $stores = $user->stores()->get(['id', 'name', 'slug', 'description', 'story', 'logo', 'background_image', 'website', 'instagram', 'tiktok', 'is_active']);

        return Inertia::render('Seller/Settings', [
            'stores' => $stores,
        ]);
    }

    public function update(Request $request, Store $store): RedirectResponse
    {
        $user = $request->user();

        abort_if($store->user_id !== $user->id, 403);

        $validated = $request->validate([
            'name'             => ['required', 'string', 'max:255', 'unique:stores,name,' . $store->id],
            'description'      => ['nullable', 'string', 'max:1000'],
            'story'            => ['nullable', 'string', 'max:5000'],
            'logo'             => ['nullable', 'string', 'max:255'],
            'background_image' => ['nullable', 'string', 'max:255'],
            'website'          => ['nullable', 'string', 'max:255'],
            'instagram'        => ['nullable', 'string', 'max:255'],
            'tiktok'           => ['nullable', 'string', 'max:255'],
            'is_active'        => ['boolean'],
        ]);

        $store->update([
            'name'             => $validated['name'],
            'description'      => $validated['description'] ?? $store->description,
            'story'            => $validated['story'] ?? $store->story,
            'logo'             => $validated['logo'] ?? $store->logo,
            'background_image' => $validated['background_image'] ?? $store->background_image,
            'website'          => $validated['website'] ?? $store->website,
            'instagram'        => $validated['instagram'] ?? $store->instagram,
            'tiktok'           => $validated['tiktok'] ?? $store->tiktok,
            'is_active'        => $request->boolean('is_active', $store->is_active),
        ]);

        return redirect()->route('seller.settings')
            ->with('success', 'Store settings updated.');
    }

    public function destroy(Request $request, Store $store): RedirectResponse
    {
        $user = $request->user();

        abort_if($store->user_id !== $user->id, 403);

        $store->delete();

        // If this was the last store, remove seller role and redirect to create
        if ($user->stores()->count() === 0) {
            $user->removeRole('seller');

            return redirect()->route('seller.create')
                ->with('info', 'Your last store was deleted. Create a new one to continue selling.');
        }

        return redirect()->route('seller.settings')
            ->with('success', 'Store deleted successfully.');
    }
}
