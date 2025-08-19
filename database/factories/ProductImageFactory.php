<?php

namespace Database\Factories;

use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductImage>
 */
class ProductImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Get all files from products directory
        $directory = storage_path('app/public/products');
        $files = File::files($directory);

        // Pick a random file if available
        $randomFile = count($files) > 0
            ? 'products/' . basename($this->faker->randomElement($files)->getPathname())
            : null;

        return [
            'image_path' => $randomFile,
            'is_primary' => false,
        ];
    }
}
