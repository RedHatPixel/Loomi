<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        $master = User::where('email', 'admin@loomi.test')->first();
        if (! $master) {
            return;
        }

        $stores = [
            [
                'name'             => 'Iron Loom',
                'slug'             => 'iron-loom',
                'description'      => 'Heavyweight denim, raw selvedge, and workwear built to outlast you. Every stitch, rivet, and seam is made for the long haul.',
                'story'            => "Iron Loom started in a small garage in Marikina in 2018. What began as a one-man operation stitching denim prototypes has grown into a full workwear label worn by craftsmen, artists, and denim heads across the country. We source our denim from Japan and Italy, cut everything in-house, and finish every garment by hand. No compromises, no shortcuts — just honest clothes that get better with age.",
                'logo'             => 'https://placehold.co/200x200/1a1a2e/e1d7c6?text=IL',
                'background_image' => 'https://placehold.co/1200x400/1a1a2e/e1d7c6?text=Iron+Loom',
                'website'          => 'https://ironloom.example.com',
                'instagram'        => '@ironloomph',
            ],
            [
                'name'             => 'Northbound Knits',
                'slug'             => 'northbound-knits',
                'description'      => 'Wool, alpaca, and recycled-fiber knitwear from a two-person studio in Baguio. Every piece is slow-made, naturally dyed, and built for the cold.',
                'story'            => "Northbound Knits was born from a love of the Cordillera highlands and a frustration with flimsy fast-fashion sweaters. Founders Tanya and Marco started hand-knitting in their Baguio apartment, sourcing wool from local farmers and experimenting with plant dyes. Today they work with a small team of artisans, producing limited runs of sweaters, cardigans, and accessories that celebrate Filipino craftsmanship and keep you warm through the mountain chill.",
                'logo'             => 'https://placehold.co/200x200/2d4a3b/e8dcc8?text=NK',
                'background_image' => 'https://placehold.co/1200x400/2d4a3b/e8dcc8?text=Northbound+Knits',
                'website'          => 'https://northboundknits.example.com',
                'instagram'        => '@northboundknits',
            ],
            [
                'name'             => 'Salt & Cedar',
                'slug'             => 'salt-and-cedar',
                'description'      => 'Coastal-inspired everyday essentials. Linen, organic cotton, and slow-made staples for the beach, the city, and everything in between.',
                'story'            => "Salt & Cedar was conceived on a surf trip to Baler — three friends, one van, and a shared belief that the best clothes are the ones you forget you're wearing. Our collection is built around natural fibers, neutral palettes, and shapes that move with you. Every Salt & Cedar piece is designed in Manila, cut in Bulacan, and finished with coconut-shell buttons from Laguna. We believe in buying less, choosing well, and making it last.",
                'logo'             => 'https://placehold.co/200x200/c4b89e/2a3a2f?text=SC',
                'background_image' => 'https://placehold.co/1200x400/c4b89e/2a3a2f?text=Salt+%26+Cedar',
                'website'          => 'https://saltandcedar.example.com',
                'instagram'        => '@saltandcedarph',
            ],
        ];

        foreach ($stores as $data) {
            Store::firstOrCreate(
                ['slug' => $data['slug']],
                [
                    'user_id'          => $master->id,
                    'name'             => $data['name'],
                    'description'      => $data['description'],
                    'story'            => $data['story'],
                    'logo'             => $data['logo'],
                    'background_image' => $data['background_image'],
                    'website'          => $data['website'],
                    'instagram'        => $data['instagram'],
                    'is_active'        => true,
                ]
            );
        }
    }
}
