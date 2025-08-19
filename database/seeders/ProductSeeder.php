<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductRating;
use App\Models\ProductSale;
use App\Models\User;
use App\Models\Category;
use App\Models\ProductCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get only admins
        $admins = User::where('role', 'admin')->get();

        if ($admins->isEmpty()) {
            $this->command->warn("⚠️ No admin users found. Please seed at least one admin first.");
            return;
        }

        Product::factory()->count(100)->make()->each(function ($product) use ($admins) {
            // Only admins can be product creators
            $product->created_by = $admins->random()->id;
            $product->save();

            // Product Images
            $images = ProductImage::factory()->count(rand(1, 9))->make([
                'product_id' => $product->id
            ]);
            $images[0]->is_primary = true;
            $product->images()->saveMany($images);

            // Attaching categories
            $categoryIds = Category::inRandomOrder()->take(rand(1, 10))->pluck('id');
            $product->categories()->attach($categoryIds);

            // Product Ratings
            $ratingUsers = User::where('role', 'user')->inRandomOrder()->take(rand(1, 10))->get();
            foreach ($ratingUsers as $user) {
                $rating = ProductRating::factory()->make(['rated_by' => $user->id]);
                $product->ratings()->save($rating);
            }

            // Product Sales
            $salesUsers = User::where('role', 'user')->inRandomOrder()->take(rand(1, 10))->get();
            foreach ($salesUsers as $user) {
                $sale = ProductSale::factory()->make(['purchase_by' => $user->id]);
                $product->sales()->save($sale);
            }
        });
    }
}
