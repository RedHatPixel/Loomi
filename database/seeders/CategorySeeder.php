<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => "Men's T-Shirts",         'slug' => 'mens-t-shirts'],
            ['name' => "Women's Tops",           'slug' => 'womens-tops'],
            ['name' => 'Hoodies & Sweatshirts',  'slug' => 'hoodies-sweatshirts'],
            ['name' => 'Outerwear',              'slug' => 'outerwear'],
            ['name' => 'Denim',                  'slug' => 'denim'],
            ['name' => 'Pants & Shorts',         'slug' => 'pants-shorts'],
            ['name' => 'Dresses & Skirts',       'slug' => 'dresses-skirts'],
            ['name' => 'Knitwear',               'slug' => 'knitwear'],
            ['name' => 'Activewear',             'slug' => 'activewear'],
            ['name' => 'Swimwear',               'slug' => 'swimwear'],
            ['name' => 'Footwear',               'slug' => 'footwear'],
            ['name' => 'Accessories',            'slug' => 'accessories'],
            ['name' => 'Bags & Backpacks',       'slug' => 'bags-backpacks'],
            ['name' => 'Hats & Beanies',         'slug' => 'hats-beanies'],
            ['name' => 'Vintage & Archive',      'slug' => 'vintage-archive'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
