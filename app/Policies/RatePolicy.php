<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\ProductRating;
use App\Models\ProductSale;
use App\Models\User;

class RatePolicy
{
    public function create(User $user, Product $product)
    {
        // Check if user purchased the product
        $hasPurchased = ProductSale::where('purchase_by', $user->id)
            ->where('product_id', $product->id)
            ->exists();

        if (!$hasPurchased) {
            return false;
        }

        // Check if user already rated this product
        $alreadyRated = ProductRating::where('rated_by', $user->id)
            ->where('product_id', $product->id)
            ->exists();

        return !$alreadyRated;
    }

    public function store(User $user, Product $product)
    {
        return $product->user_id !== $user->id;
    }
}
