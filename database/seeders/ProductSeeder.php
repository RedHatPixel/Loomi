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
        $users = User::all();

        Product::factory()->count(100)->make()->each(function ($product) use ($users) {
            $product->created_by = $users->random()->id;
            $product->save();

            // Product Images
            $images = ProductImage::factory()->count(rand(1, 9))->make([
                'product_id' => $product->id
            ]);
            $images[0]->is_primary = true;
            $product->images()->saveMany($images);

            // Attaching categories
            $categories = ProductCategory::factory()->count(rand(1, 10))->make([
                'product_id' => $product->id
            ]);
            $product->categories()->saveMany($categories);

            // Product Ratings
            $ratingUsers = $users->random(rand(1, 10));
            foreach ($ratingUsers as $user) {
                $rating = ProductRating::factory()->make(['user_id' => $user->id]);
                $product->ratings()->save($rating);
            }

            // Product Sales
            $salesUsers = $users->random(rand(1, 10));
            foreach ($salesUsers as $user) {
                $sale = ProductSale::factory()->make(['buyer_id' => $user->id]);
                $product->sales()->save($sale);
            }
        });
    }
}
