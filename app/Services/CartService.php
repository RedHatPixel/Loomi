<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class CartService
{
    public function getOrCreateCart(User $user): Cart
    {
        return Cart::firstOrCreate(['user_id' => $user->id]);
    }

    public function addItem(User $user, int $productId, int $quantity): CartItem
    {
        $product = Product::findOrFail($productId);

        $this->ensureSufficientStock($product, $quantity);

        $cart = $this->getOrCreateCart($user);

        $item = $cart->items()->where('product_id', $productId)->first();

        if ($item) {
            $newQty = $item->quantity + $quantity;
            $this->ensureSufficientStock($product, $newQty);
            $item->update(['quantity' => $newQty]);
            return $item->fresh();
        }

        return $cart->items()->create([
            'product_id' => $productId,
            'quantity'   => $quantity,
        ]);
    }

    public function updateItem(CartItem $item, int $quantity): CartItem
    {
        $product = $item->product;

        $this->ensureSufficientStock($product, $quantity);

        if ($quantity < 1) {
            $item->delete();
            return $item;
        }

        $item->update(['quantity' => $quantity]);
        return $item->fresh();
    }

    public function removeItem(CartItem $item): void
    {
        $item->delete();
    }

    public function getCartWithItems(User $user): Cart
    {
        $cart = $this->getOrCreateCart($user);
        $cart->load(['items.product.images', 'items.product.store']);
        return $cart;
    }

    public function getTotal(Cart $cart): float
    {
        return $cart->items->sum(fn ($item) => $item->product->price * $item->quantity);
    }

    public function clearCart(Cart $cart): void
    {
        $cart->items()->delete();
    }

    /**
     * @throws ValidationException
     */
    private function ensureSufficientStock(Product $product, int $quantity): void
    {
        if ($quantity > $product->stock) {
            throw ValidationException::withMessages([
                'quantity' => "Only {$product->stock} available in stock.",
            ]);
        }
    }
}
