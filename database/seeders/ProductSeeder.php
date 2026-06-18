<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $ironLoom   = Store::where('slug', 'iron-loom')->first();
        $northbound = Store::where('slug', 'northbound-knits')->first();
        $saltCedar  = Store::where('slug', 'salt-and-cedar')->first();

        if (! $ironLoom || ! $northbound || ! $saltCedar) {
            return;
        }

        /* ── category lookup helper ── */
        $cat = fn (string $slug) => Category::where('slug', $slug)->first()?->id;

        /* ── image helper ── */
        $img = fn (string $name, string $bg = 'e7e2d8', string $fg = '3a3a3a') =>
            "https://placehold.co/600x750/{$bg}/{$fg}?text=" . urlencode($name);

        $extraImage = fn (string $name, string $bg = 'd4cfc5', string $fg = '5a5a5a') =>
            "https://placehold.co/600x750/{$bg}/{$fg}?text=" . urlencode($name . ' +');

        $products = [];

        /* ====================================================================
           IRON LOOM  —  68 products  (heavyweight denim, workwear, boots)
           ==================================================================== */
        $ironProducts = [
            // ── Denim ──
            ['name' => 'Raw Selvedge Straight Jeans',           'cat' => 'denim',         'price' => 4200, 'stock' => 15, 'desc' => '14oz Japanese Kuroki Mills selvedge denim, button fly, chainstitched hem. Raw and unwashed — make them yours.'],
            ['name' => 'Tapered Work Denim',                   'cat' => 'denim',         'price' => 3800, 'stock' => 20, 'desc' => 'Reinforced knee panels, heavy-duty stitching, and a modern tapered fit. Built for the workshop.'],
            ['name' => 'Relaxed Straight Jean',                'cat' => 'denim',         'price' => 3500, 'stock' => 18, 'desc' => 'Mid-rise relaxed fit in 12oz comfort stretch denim. Easy wearing, tough as nails.'],
            ['name' => 'High-Rise Wide Leg Jean',              'cat' => 'denim',         'price' => 3900, 'stock' => 12, 'desc' => 'Vintage-inspired wide leg with a high rise. 13oz American selvedge.'],

            // ── Jackets ──
            ['name' => 'Type III Selvedge Jacket',             'cat' => 'outerwear',     'price' => 5200, 'stock' => 8,  'desc' => 'The classic trucker jacket in 15oz raw selvedge. Copper rivets, hidden snaps, fades like a dream.'],
            ['name' => 'Quilted Lined Denim Jacket',           'cat' => 'outerwear',     'price' => 4800, 'stock' => 6,  'desc' => 'Indigo-dyed shell with a quilted cotton lining. Heavy metal hardware.'],
            ['name' => 'Waxed Canvas Work Jacket',             'cat' => 'outerwear',     'price' => 5500, 'stock' => 7,  'desc' => 'Martexin waxed canvas, moleskin collar, and corduroy-lined hand pockets. Water resistant.'],
            ['name' => 'Engineer Chore Coat',                  'cat' => 'outerwear',     'price' => 4500, 'stock' => 9,  'desc' => 'Relaxed chore coat in 12oz Japanese denim. Triple-needle stitching throughout.'],
            ['name' => 'Raw Indigo Vest',                     'cat' => 'outerwear',     'price' => 3200, 'stock' => 11, 'desc' => 'Unlined selvedge vest. Antique brass buttons, welt pockets, raw hem.'],
            ['name' => 'Cuffed Field Jacket',                  'cat' => 'outerwear',     'price' => 6200, 'stock' => 5,  'desc' => 'Rugged cotton field jacket with four patch pockets, elbow patches, and a bi-swing back.'],

            // ── Shirts / Tops ──
            ['name' => 'Heavyweight Boxy Tee',                'cat' => 'mens-t-shirts', 'price' => 890,  'stock' => 30, 'desc' => 'Garment-dyed, 220gsm ring-spun cotton. Boxed cut, taped shoulder seams.'],
            ['name' => 'Pocket Crew Sweatshirt',              'cat' => 'hoodies-sweatshirts', 'price' => 1600, 'stock' => 22, 'desc' => '400gsm brushed fleece with a kangaroo pocket and ribbed cuffs.'],

            // ... continuing with many more products
            ['name' => 'Ribbed Henley',                       'cat' => 'mens-t-shirts', 'price' => 1100, 'stock' => 25, 'desc' => '260gsm organic cotton rib with corozo nut buttons. Slim, clean, essential.'],
            ['name' => 'Flannel Work Shirt',                  'cat' => 'mens-t-shirts', 'price' => 1800, 'stock' => 17, 'desc' => 'Brushed cotton flannel in a vintage plaid. Button-down collar, locker loop.'],
            ['name' => 'Chambray Utility Shirt',              'cat' => 'mens-t-shirts', 'price' => 1650, 'stock' => 14, 'desc' => 'Lightweight Japanese chambray with double chest pockets and a hidden pen slot.'],
            ['name' => 'Long Sleeve Slub Tee',               'cat' => 'mens-t-shirts', 'price' => 1050, 'stock' => 28, 'desc' => 'Slub-knit cotton with a slightly uneven texture. Relaxed fit, garment-washed for softness.'],
            ['name' => 'Duck Canvas Shirt Jacket',            'cat' => 'outerwear',     'price' => 3200, 'stock' => 10, 'desc' => '12oz duck canvas, blanket-lined. Snap front, adjustable cuffs.'],
            ['name' => 'Loopwheel Hoodie',                    'cat' => 'hoodies-sweatshirts', 'price' => 2400, 'stock' => 15, 'desc' => 'Loopwheel-knit 420gsm cotton. Reverse jersey, tubular body, no side seams.'],
            ['name' => 'French Terry Pullover',               'cat' => 'hoodies-sweatshirts', 'price' => 1850, 'stock' => 20, 'desc' => 'Brushed french terry with ribbed cuffs and hem. Split kangaroo pocket.'],
            ['name' => 'Oversized Crewneck Sweat',            'cat' => 'hoodies-sweatshirts', 'price' => 1750, 'stock' => 18, 'desc' => 'Drop-shoulder oversized fit in 380gsm fleece. Ribbed cuffs and hem.'],
            ['name' => 'Zip Hooded Sweatshirt',              'cat' => 'hoodies-sweatshirts', 'price' => 2200, 'stock' => 12, 'desc' => 'Full-zip hoodie in heavy brushed fleece. YKK zipper, lined hood, ribbed hem.'],
        ];

        // ── Pants ──
        $ironProducts[] = ['name' => 'Stretch Carpenter Pants',      'cat' => 'pants-shorts',  'price' => 2200, 'stock' => 16, 'desc' => 'Cotton-nylon twill with hammer loop, side pocket, and articulated knees.'];
        $ironProducts[] = ['name' => 'Double Knee Work Pant',       'cat' => 'pants-shorts',  'price' => 2800, 'stock' => 13, 'desc' => 'Reinforced double-layer knees in 10oz cotton duck. Triple-stitched seams.'];
        $ironProducts[] = ['name' => 'Cargo Fatigue Pant',          'cat' => 'pants-shorts',  'price' => 2500, 'stock' => 19, 'desc' => 'Loose-fit fatigue pant with oversized cargo pockets. Garment-dyed olive.'];
        $ironProducts[] = ['name' => 'Wool Blend Trousers',        'cat' => 'pants-shorts',  'price' => 3200, 'stock' => 8,  'desc' => 'Worsted wool blend, single pleat, and a hook-and-bar closure. Refined workwear.'];
        $ironProducts[] = ['name' => 'Denim Short Overalls',       'cat' => 'pants-shorts',  'price' => 2900, 'stock' => 10, 'desc' => 'Deep indigo bib overalls in 12oz denim. Cross-back straps, tool pocket.'];

        // ── Shorts ──
        $ironProducts[] = ['name' => 'Selvedge Denim Shorts',      'cat' => 'pants-shorts',  'price' => 2000, 'stock' => 14, 'desc' => 'Upcycled selvedge denim shorts. Raw hem, chainstitch detail.'];
        $ironProducts[] = ['name' => 'Canvas Work Shorts',         'cat' => 'pants-shorts',  'price' => 1550, 'stock' => 21, 'desc' => '9oz canvas with hammer loop, ruler pocket, and reinforced seat.'];

        // ── Footwear ──
        $ironProducts[] = ['name' => 'Engineer Boots',             'cat' => 'footwear',      'price' => 7500, 'stock' => 5,  'desc' => 'Full-grain chromexcel leather, Goodyear welted, Vibram outsole. Built for decades.'];
        $ironProducts[] = ['name' => '6" Moc Toe Boot',           'cat' => 'footwear',      'price' => 6200, 'stock' => 7,  'desc' => 'Horween leather moc toe with a wedge sole. Hand-stitched vamp.'];
        $ironProducts[] = ['name' => 'Lace-Up Work Boot',         'cat' => 'footwear',      'price' => 5800, 'stock' => 6,  'desc' => 'Oil-tanned leather, steel shank, and a lug sole. Safety toe optional.'];
        $ironProducts[] = ['name' => 'Chelsea Work Boot',          'cat' => 'footwear',      'price' => 5500, 'stock' => 4,  'desc' => 'Pull-on Chelsea in distressed calfskin. Elastic gore, pull tab, Vibram mini-lug.'];
        $ironProducts[] = ['name' => 'Cap Toe Service Boot',       'cat' => 'footwear',      'price' => 6500, 'stock' => 3,  'desc' => 'Italian calfskin cap-toe boot. Leather outsole, stacked leather heel.'];
        $ironProducts[] = ['name' => 'Chukka Boot',               'cat' => 'footwear',      'price' => 4800, 'stock' => 9,  'desc' => 'Suede chukka with a crepe sole. Unlined, lightweight, versatile.'];

        // ── Accessories ──
        $ironProducts[] = ['name' => 'Full Grain Leather Belt',    'cat' => 'accessories',   'price' => 1800, 'stock' => 22, 'desc' => '1.5" full-grain leather, solid brass buckle. Unlined, ages beautifully.'];
        $ironProducts[] = ['name' => 'Waxed Canvas Tool Roll',    'cat' => 'bags-backpacks', 'price' => 2100, 'stock' => 11, 'desc' => 'Holds 8 tools. Martexin waxed canvas, bridle leather straps.'];
        $ironProducts[] = ['name' => 'Denim Duffle Bag',           'cat' => 'bags-backpacks', 'price' => 3500, 'stock' => 7,  'desc' => 'Oversized duffle in 16oz denim. Leather handles, detachable shoulder strap.'];
        $ironProducts[] = ['name' => 'Horween Leather Wallet',     'cat' => 'accessories',   'price' => 1200, 'stock' => 28, 'desc' => 'Minimalist card wallet in Horween Chromexcel. Hand-stitched, ages with patina.'];
        $ironProducts[] = ['name' => 'Brass Key Hook',            'cat' => 'accessories',   'price' => 450,  'stock' => 35, 'desc' => 'Solid brass key holder. Holds up to 6 keys. Develops a natural patina.'];
        $ironProducts[] = ['name' => 'Raw Denim Hat',             'cat' => 'hats-beanies',  'price' => 980,  'stock' => 16, 'desc' => 'Unstructured cap in 12oz raw selvedge. Adjustable brass buckle closure.'];
        $ironProducts[] = ['name' => 'Canvas Backpack',           'cat' => 'bags-backpacks', 'price' => 2800, 'stock' => 9,  'desc' => 'Waxed canvas rucksack with leather straps and brass hardware. 25L capacity.'];
        $ironProducts[] = ['name' => 'Leather Gloves',            'cat' => 'accessories',   'price' => 1600, 'stock' => 13, 'desc' => 'Deerskin leather gloves with wool lining. Touchscreen-compatible fingertips.'];
        $ironProducts[] = ['name' => 'Bandana',                    'cat' => 'accessories',   'price' => 350,  'stock' => 40, 'desc' => 'Indigo-dyed cotton bandana. One size, multi-purpose.'];
        $ironProducts[] = ['name' => 'Selvedge Face Mask',         'cat' => 'accessories',   'price' => 390,  'stock' => 20, 'desc' => 'Double-layer selvedge denim face covering. Adjustable ear loops.'];
        $ironProducts[] = ['name' => 'Heavyweight Tote',          'cat' => 'bags-backpacks', 'price' => 1350, 'stock' => 18, 'desc' => '20oz cotton canvas tote. Reinforced bottom, webbed handles.'];
        $ironProducts[] = ['name' => 'Lanyard',                   'cat' => 'accessories',   'price' => 250,  'stock' => 45, 'desc' => 'Braided paracord lanyard with brass snap hook.'];

        // ── More tops to fill out ──
        $ironProducts[] = ['name' => 'Loopwheel Crew Tee',        'cat' => 'mens-t-shirts', 'price' => 1200, 'stock' => 24, 'desc' => 'Loopwheel-knit 200gsm cotton. No side seams, tubular construction.'];
        $ironProducts[] = ['name' => 'Midweight Thermal Henley',  'cat' => 'mens-t-shirts', 'price' => 1300, 'stock' => 20, 'desc' => 'Brushed thermal knit with a button placket. Slim fit.'];
        $ironProducts[] = ['name' => 'Waffle Knit Long Sleeve',  'cat' => 'mens-t-shirts', 'price' => 1250, 'stock' => 16, 'desc' => 'Waffle-knit cotton, shawl collar, and ribbed cuffs. Layer-friendly.'];
        $ironProducts[] = ['name' => 'Sashiko Embroidered Shirt', 'cat' => 'outerwear',     'price' => 3400, 'stock' => 6,  'desc' => 'Sashiko-stitched indigo jacket. Unlined, oversized, hand-embellished.'];
        $ironProducts[] = ['name' => 'Nylon BDU Jacket',          'cat' => 'outerwear',     'price' => 2900, 'stock' => 8,  'desc' => 'Ripstop nylon BDU jacket. Cargo pockets, drawstring waist, packable.'];
        $ironProducts[] = ['name' => 'Oilskin Cruiser Jacket',    'cat' => 'outerwear',     'price' => 5900, 'stock' => 4,  'desc' => 'Oilskin waxed cotton cruiser. Corduroy collar, storm flap, handwarmer pockets.'];

        $ironProducts[] = ['name' => 'Duck Canvas Bib Overalls',  'cat' => 'denim',         'price' => 3600, 'stock' => 6,  'desc' => '12oz duck canvas bibs. Drop-front, hammer loop, triple-stitched seams.'];
        $ironProducts[] = ['name' => 'Selvedge Jean Jacket',     'cat' => 'denim',         'price' => 4900, 'stock' => 7,  'desc' => 'Relaxed trucker in 14oz white oak selvedge. Copper buttons, hidden rivets.'];
        $ironProducts[] = ['name' => 'Slub Denim Shirt',         'cat' => 'denim',         'price' => 2200, 'stock' => 11, 'desc' => 'Slub-textured 10oz denim button-up. Pearl snaps, two chest pockets.'];
        $ironProducts[] = ['name' => 'Indigo Dye Workshirt',     'cat' => 'denim',         'price' => 1950, 'stock' => 13, 'desc' => 'Hand-dyed indigo workshirt. Rope-stitched, corozo buttons, locker loop.'];
        $ironProducts[] = ['name' => 'Hickory Stripe Engineer Shirt','cat' => 'mens-t-shirts','price' => 1700, 'stock' => 14, 'desc' => 'Hickory stripe cotton. Snap front, double chest pockets, reinforced elbows.'];

        // Finish filling Iron Loom to 68
        $ironProducts[] = ['name' => 'Cotton Watch Cap',          'cat' => 'hats-beanies',  'price' => 550,  'stock' => 30, 'desc' => 'Ribbed heavyweight cotton watch cap. Cuffed, one size.'];
        $ironProducts[] = ['name' => 'Boondocker Beanie',         'cat' => 'hats-beanies',  'price' => 650,  'stock' => 25, 'desc' => 'Chunky wool beanie with a folded brim. Military-inspired.'];
        $ironProducts[] = ['name' => 'Ranger Beanie',             'cat' => 'hats-beanies',  'price' => 580,  'stock' => 28, 'desc' => 'Merino-blend ranger beanie. Lightweight, packable, breathable.'];

        $ironProducts[] = ['name' => 'Belted Denim Skirt',         'cat' => 'dresses-skirts','price' => 2700, 'stock' => 8,  'desc' => 'A-line denim skirt with leather belt loops. 10oz comfort stretch.'];
        $ironProducts[] = ['name' => 'Workwear Vest',               'cat' => 'outerwear',    'price' => 2200, 'stock' => 10, 'desc' => 'Sleeveless canvas work vest. Six pockets, adjustable side tabs.'];

        $ironProducts[] = ['name' => 'Heavy Duty Jeans',           'cat' => 'denim',        'price' => 4000, 'stock' => 10, 'desc' => 'Extra-heavy 16oz denim. Reinforced crotch, double back pockets, chainstitch hem.'];
        $ironProducts[] = ['name' => 'Wide Leg Painter Pant',      'cat' => 'pants-shorts', 'price' => 2400, 'stock' => 12, 'desc' => 'Wide straight leg with hammer loop and side slit pockets. 100% cotton twill.'];
        $ironProducts[] = ['name' => 'Slim Chino',                'cat' => 'pants-shorts', 'price' => 1900, 'stock' => 17, 'desc' => 'Organic cotton twill chino. Slim taper, blind hem, zip fly.'];

        /* ====================================================================
           NORTHBOUND KNITS  —  66 products  (wool, knits, cold-weather)
           ==================================================================== */
        $northboundProducts = [
            // ── Sweaters ──
            ['name' => 'Merino Crewneck Sweater',              'cat' => 'outerwear',    'price' => 3200, 'stock' => 12, 'desc' => 'Fine 19.5 micron merino wool, 12-gauge knit. Ribbed cuffs and hem. Smooth, itch-free, luxurious.'],
            ['name' => 'Fisherman Rib Rollneck',               'cat' => 'outerwear',    'price' => 3800, 'stock' => 9,  'desc' => 'Chunky fisherman rib in undyed wool. Mock neck, drop shoulder, wide rib cuffs.'],
            ['name' => 'Lambswool V-Neck',                     'cat' => 'outerwear',    'price' => 2800, 'stock' => 14, 'desc' => 'Soft lambswool v-neck. Fully fashioned, saddle shoulders, 8-gauge.'],
            ['name' => 'Cable Knit Aran Sweater',              'cat' => 'outerwear',    'price' => 4200, 'stock' => 7,  'desc' => 'Traditional Aran cable knit in heavy worsted wool. Button placket, patch pockets.'],
            ['name' => 'Alpaca Blend Turtleneck',              'cat' => 'outerwear',    'price' => 4500, 'stock' => 6,  'desc' => 'Alpaca-merino blend, 6-gauge knit. Turtleneck, side vents, ribbed hem.'],
            ['name' => 'Slouchy Cashmere Crew',                'cat' => 'outerwear',    'price' => 5500, 'stock' => 4,  'desc' => 'Grade-A cashmere, 14-gauge. Relaxed drop-shoulder silhouette. Unbelievably soft.'],
            ['name' => 'Chunky Wool Cardigan',                 'cat' => 'outerwear',    'price' => 4800, 'stock' => 5,  'desc' => 'Heavyweight wool cardigan. Horn toggle buttons, patch pockets, shawl collar.'],
            ['name' => 'Textured Crew Sweater',                'cat' => 'outerwear',    'price' => 3100, 'stock' => 10, 'desc' => 'Textured basket-weave knit in undyed wool. Relaxed fit, ribbed cuffs.'],

            // ── Cardigans ──
            ['name' => 'Recycled Cotton Cardigan',             'cat' => 'knitwear',     'price' => 2400, 'stock' => 13, 'desc' => 'Chunky recycled cotton cardigan. Corozo buttons, slash pockets, ribbed trim.'],
            ['name' => 'Fine Gauge Zip Cardigan',              'cat' => 'knitwear',     'price' => 2900, 'stock' => 8,  'desc' => 'Fine-gauge merino zip cardigan. YKK zipper, stand collar, clean finish.'],
            ['name' => 'Oversized Knit Cardigan',              'cat' => 'knitwear',     'price' => 3500, 'stock' => 6,  'desc' => 'Oversized cocoon cardigan in brushed alpaca. Belted waist, dropped shoulders.'],
            ['name' => 'Grandpa Cardigan',                     'cat' => 'knitwear',     'price' => 2600, 'stock' => 11, 'desc' => 'Classic grandpa-style cardigan in wool-acrylic blend. Button front, ribbed edges.'],

            // ── Knit Tees / Tops ──
            ['name' => 'Merino T-Shirt',                       'cat' => 'mens-t-shirts','price' => 1400, 'stock' => 20, 'desc' => '18-gauge merino jersey tee. Temperature regulating, odor resistant.'],
            ['name' => 'Cotton-Linen Knit Tee',                'cat' => 'mens-t-shirts','price' => 1200, 'stock' => 18, 'desc' => 'Cotton-linen blend knit tee. Breezy, textured, garment-washed.'],
            ['name' => 'Ribbed Silk Blend Top',                'cat' => 'womens-tops',  'price' => 1600, 'stock' => 15, 'desc' => 'Silk-cotton ribbed knit. Scoop neck, slim fit, elbow-length sleeves.'],
            ['name' => 'Wool Tank Top',                        'cat' => 'activewear',   'price' => 1100, 'stock' => 17, 'desc' => 'Fine-gauge merino tank. Racerback, seamless sides. Ideal base layer.'],

            // ── Hats & Beanies ──
            ['name' => 'Wool Beanie',                          'cat' => 'hats-beanies', 'price' => 750,  'stock' => 35, 'desc' => 'Ribbed merino beanie. Folded brim, one size. Warm, breathable, classic.'],
            ['name' => 'Cable Knit Beanie',                    'cat' => 'hats-beanies', 'price' => 850,  'stock' => 28, 'desc' => 'Cable-pattern beanie in heavy wool. Pom optional, fleece-lined band.'],
            ['name' => 'Alpaca Trapper Hat',                   'cat' => 'hats-beanies', 'price' => 1800, 'stock' => 8,  'desc' => 'Alpaca-blend trapper with ear flaps. Faux shearling lining, braided ties.'],
            ['name' => 'Knit Bucket Hat',                      'cat' => 'hats-beanies', 'price' => 950,  'stock' => 14, 'desc' => 'Knit bucket hat in recycled cotton. Brim wire, packable.'],
            ['name' => 'Earflap Beanie',                       'cat' => 'hats-beanies', 'price' => 1100, 'stock' => 12, 'desc' => 'Merino earflap beanie with braided ties. Double-layer warmth.'],
            ['name' => 'Slouchy Beret',                        'cat' => 'hats-beanies', 'price' => 680,  'stock' => 22, 'desc' => 'Drop-shape beret in brushed wool. One size, artistic vibe.'],
            ['name' => 'Fleece Trapper Hat',                   'cat' => 'hats-beanies', 'price' => 1300, 'stock' => 10, 'desc' => 'Recycled fleece trapper with microfleece lining. Adjustable chin strap.'],

            // ── Scarves & Neckwear ──
            ['name' => 'Infinity Wool Scarf',                  'cat' => 'accessories',  'price' => 1400, 'stock' => 16, 'desc' => 'Merino infinity scarf, knit in a loop. 160cm circumference, ribbed texture.'],
            ['name' => 'Chunky Knit Scarf',                    'cat' => 'accessories',  'price' => 1600, 'stock' => 12, 'desc' => 'Oversized chunky scarf in undyed wool. 200x30cm, fringed ends.'],
            ['name' => 'Cashmere Neck Gaiter',                 'cat' => 'accessories',  'price' => 1200, 'stock' => 15, 'desc' => 'Double-layer cashmere gaiter. Tubular, seamless. Wind-resistant.'],
            ['name' => 'Linen Blend Scarf',                    'cat' => 'accessories',  'price' => 950,  'stock' => 20, 'desc' => 'Linen-cotton blend scarf. Lightweight, fringed ends. Transitional.'],
            ['name' => 'Fair Isle Scarf',                      'cat' => 'accessories',  'price' => 1750, 'stock' => 8,  'desc' => 'Fair Isle pattern scarf in wool. Colorwork, 180x25cm.'],

            // ── Gloves & Mittens ──
            ['name' => 'Merino Gloves',                        'cat' => 'accessories',  'price' => 850,  'stock' => 22, 'desc' => 'Fine merino gloves. Touchscreen-compatible, fitted palm.'],
            ['name' => 'Fleece Lined Mittens',                 'cat' => 'accessories',  'price' => 1200, 'stock' => 14, 'desc' => 'Chunky knit mittens with fleece lining. Long cuffs, braided tie.'],
            ['name' => 'Convertible Gloves',                   'cat' => 'accessories',  'price' => 1500, 'stock' => 9,  'desc' => 'Knitted gloves with flip-top mitten cover. Merino-wool blend.'],
            ['name' => 'Fingerless Arm Warmers',               'cat' => 'activewear',   'price' => 680,  'stock' => 18, 'desc' => 'Ribbed knit arm warmers. Thumb hole, mid-length. Layer-friendly.'],

            // ── Socks ──
            ['name' => 'Merino Hiking Socks',                  'cat' => 'accessories',  'price' => 550,  'stock' => 35, 'desc' => 'Cushioned merino hiking socks. Reinforced heel and toe. Mid-calf. (Pair)'],
            ['name' => 'Wool Dress Socks',                     'cat' => 'accessories',  'price' => 450,  'stock' => 30, 'desc' => 'Fine-rib dress socks in merino-nylon blend. Solid colors. (Pair)'],
            ['name' => 'Boot Socks',                           'cat' => 'accessories',  'price' => 600,  'stock' => 25, 'desc' => 'Wool-cotton blend boot socks. Over-the-calf, cushioned footbed. (Pair)'],
            ['name' => 'Alpaca Bed Socks',                     'cat' => 'activewear',   'price' => 700,  'stock' => 20, 'desc' => 'Alpaca bed socks, unbrushed. Knee-high, stay-up cuffs. (Pair)'],

            // ── Blankets / Throws ──
            ['name' => 'Woven Wool Throw',                     'cat' => 'accessories',  'price' => 3800, 'stock' => 5,  'desc' => 'Handwoven wool throw. Traditional patterns, fringed edges. 140x180cm.'],
            ['name' => 'Knit Baby Blanket',                    'cat' => 'accessories',  'price' => 2200, 'stock' => 7,  'desc' => 'Garter-stitch merino baby blanket. 75x100cm, machine washable.'],
            ['name' => 'Chunky Knit Blanket',                  'cat' => 'accessories',  'price' => 4800, 'stock' => 4,  'desc' => 'Extra-chunky merino throw. Hand-knit, 120x150cm. Decor piece.'],

            // ── Knitwear bottoms ──
            ['name' => 'Knitted Joggers',                      'cat' => 'pants-shorts', 'price' => 1800, 'stock' => 14, 'desc' => 'Brushed fleece joggers with ribbed cuffs. Elastic waist, drawcord.'],
            ['name' => 'Wool Lounge Pants',                    'cat' => 'pants-shorts', 'price' => 2200, 'stock' => 10, 'desc' => 'French terry wool lounge pants. Elastic waist, side pockets, relaxed taper.'],
            ['name' => 'Knit Midi Skirt',                      'cat' => 'dresses-skirts','price' => 2100, 'stock' => 9,  'desc' => 'Ribbed knit midi skirt. A-line silhouette, elastic waist, knee-length.'],
            ['name' => 'Cable Knit Leggings',                  'cat' => 'activewear',   'price' => 1600, 'stock' => 16, 'desc' => 'Cable-knit leggings in cotton-spandex blend. High-waisted, full length.'],

            // ── Home / Lifestyle ──
            ['name' => 'Knitted Hot Water Bottle Cover',        'cat' => 'accessories',  'price' => 480,  'stock' => 20, 'desc' => 'Wool hot water bottle cover. Knit pattern, button closure.'],
            ['name' => 'Slipper Socks',                        'cat' => 'accessories',  'price' => 850,  'stock' => 18, 'desc' => 'Thick felted wool slipper socks. Suede sole, foldable cuff.'],
            ['name' => 'Pom Pom Garland',                      'cat' => 'accessories',  'price' => 680,  'stock' => 12, 'desc' => 'Handmade wool pom pom garland. 180cm, assorted natural tones.'],

            // ── Sweater Vests ──
            ['name' => 'Argyle Sweater Vest',                  'cat' => 'knitwear',     'price' => 2300, 'stock' => 10, 'desc' => 'Argyle pattern sweater vest in merino. V-neck, armholes trimmed.'],
            ['name' => 'Crew Neck Knit Vest',                  'cat' => 'knitwear',     'price' => 2000, 'stock' => 13, 'desc' => 'Solid crew neck knit vest in recycled wool. Ribbed hem and armholes.'],
            ['name' => 'Patchwork Knit Vest',                  'cat' => 'knitwear',     'price' => 2800, 'stock' => 5,  'desc' => 'Patchwork knit vest in assorted wools. One-of-a-kind, hand-sewn panels.'],

            // ── Dresses ──
            ['name' => 'Knit Shift Dress',                     'cat' => 'dresses-skirts','price' => 2600, 'stock' => 8,  'desc' => 'Merino shift dress, knee-length. Ribbed texture, side pockets, scoop neck.'],
            ['name' => 'Sweater Dress',                        'cat' => 'dresses-skirts','price' => 3400, 'stock' => 6,  'desc' => 'Oversized sweater dress in wool blend. Turtleneck, long sleeves, thigh-length.'],
            ['name' => 'Knit Bodycon Dress',                   'cat' => 'dresses-skirts','price' => 2200, 'stock' => 9,  'desc' => 'Ribbed bodycon dress in cotton-spandex. Midi length, scoop neck, long sleeve.'],

            // ── Ponchos / Capes ──
            ['name' => 'Woven Wool Poncho',                    'cat' => 'outerwear',    'price' => 3500, 'stock' => 4,  'desc' => 'Water-resistant wool poncho. Fringed hem, side openings, blanket-weight.'],
            ['name' => 'Capelet',                              'cat' => 'outerwear',    'price' => 1900, 'stock' => 8,  'desc' => 'Short cape in brushed wool. Button front, elbow-length. Layer piece.'],

            // Fill to 66
            ['name' => 'Hand Knit Headband',                   'cat' => 'hats-beanies', 'price' => 550,  'stock' => 24, 'desc' => 'Braided merino headband. Crossed front, fleece-lined ears.'],
            ['name' => 'Knitted Toy Bear',                     'cat' => 'vintage-archive','price' => 950, 'stock' => 11, 'desc' => 'Hand-knit teddy bear in recycled yarn. 25cm, safety eyes, limited edition.'],
            ['name' => 'Brioche Stitch Scarf',                 'cat' => 'accessories',  'price' => 1700, 'stock' => 10, 'desc' => 'Brioche-stitch scarf in merino. Thick, squishy, two-tone colorwork.'],
            ['name' => 'Tweed Blazer',                         'cat' => 'outerwear',    'price' => 5200, 'stock' => 3,  'desc' => 'Wool tweed blazer with suede elbow patches. Notch lapel, two-button closure.'],
            ['name' => 'Felted Wool Slippers',                 'cat' => 'accessories',  'price' => 1100, 'stock' => 14, 'desc' => 'Felted wool clogs. Leather sole, hand-embroidered detail. Unisex.'],
            ['name' => 'Sheepskin Boots',                      'cat' => 'footwear',     'price' => 4800, 'stock' => 4,  'desc' => 'Ankle-high sheepskin boots. Wool lining, rubber sole, lace-up front.'],
            ['name' => 'Knit Face Mask',                       'cat' => 'accessories',  'price' => 350,  'stock' => 30, 'desc' => 'Double-layer merino face mask. Filter pocket, adjustable ear loops.'],
            ['name' => 'Wool Insulated Vest',                  'cat' => 'outerwear',    'price' => 2800, 'stock' => 7,  'desc' => 'Quilted wool vest. Faux-shearling collar, snap front. Wind-resistant.'],
            ['name' => 'Fingerless Gloves',                    'cat' => 'accessories',  'price' => 580,  'stock' => 18, 'desc' => 'Ribbed fingerless gloves in merino. Fold-over mitten cap.'],
            ['name' => 'Cashmere Headband',                    'cat' => 'hats-beanies', 'price' => 780,  'stock' => 16, 'desc' => 'Cashmere-blend headband. Twisted front, fleece backing.'],
        ];

        /* ====================================================================
           SALT & CEDAR  —  66 products  (coastal, linen, organic cotton, basics)
           ==================================================================== */
        $saltCedarProducts = [
            // ── Linen Tops ──
            ['name' => 'Linen Button-Up Shirt',                'cat' => 'mens-t-shirts','price' => 1650, 'stock' => 22, 'desc' => 'Garment-washed European linen. Relaxed fit, chest pocket, mother-of-pearl buttons.'],
            ['name' => 'Oversized Linen Shirt',                'cat' => 'womens-tops',  'price' => 1750, 'stock' => 18, 'desc' => 'Drop-shoulder oversized linen shirt. Roll-tab sleeves, curved hem.'],
            ['name' => 'Linen Camp Collar Shirt',              'cat' => 'mens-t-shirts','price' => 1550, 'stock' => 20, 'desc' => 'Vintage camp collar in linen-cotton blend. Short sleeves, chest pocket.'],
            ['name' => 'Linen Wrap Blouse',                    'cat' => 'womens-tops',  'price' => 1850, 'stock' => 14, 'desc' => 'Wrap-front linen blouse. Adjustable tie waist, V-neck, elbow sleeves.'],
            ['name' => 'Linen Tunic',                          'cat' => 'womens-tops',  'price' => 1950, 'stock' => 12, 'desc' => 'Linen tunic with side slits. Embroidery detail, hip-length.'],

            // ── Cotton Tees ──
            ['name' => 'Organic Cotton Crew Tee',              'cat' => 'mens-t-shirts','price' => 650,  'stock' => 40, 'desc' => '180gsm organic cotton jersey. Pre-shrunk, double-needle stitching. Everyday essential.'],
            ['name' => 'Pima Cotton V-Neck',                   'cat' => 'mens-t-shirts','price' => 780,  'stock' => 35, 'desc' => 'Peruvian Pima cotton v-neck. Silky hand feel, garment-dyed.'],
            ['name' => 'Cropped Cotton Tee',                   'cat' => 'womens-tops',  'price' => 550,  'stock' => 30, 'desc' => 'Cropped crew tee in organic cotton. Relaxed fit, ribbed neckband.'],
            ['name' => 'Bamboo-Cotton Blend Tee',              'cat' => 'mens-t-shirts','price' => 720,  'stock' => 28, 'desc' => 'Bamboo-organic cotton jersey. Thermoregulating, naturally soft.'],
            ['name' => 'Relaxed Stripe Tee',                   'cat' => 'mens-t-shirts','price' => 700,  'stock' => 32, 'desc' => 'Striped slub-knit tee. Boxy fit, rolled hem, coastal colors.'],
            ['name' => 'Baby Rib Tee',                         'cat' => 'womens-tops',  'price' => 620,  'stock' => 26, 'desc' => 'Fine baby-rib knit tee. Fitted, cap sleeves, scoop neck.'],

            // ── Dresses ──
            ['name' => 'Linen Midi Dress',                     'cat' => 'dresses-skirts','price' => 2400, 'stock' => 10, 'desc' => 'A-line linen midi dress. Pockets, adjustable straps, side zip.'],
            ['name' => 'Cotton Sundress',                      'cat' => 'dresses-skirts','price' => 1800, 'stock' => 13, 'desc' => 'Lightweight cotton sundress. Smocked back, ruffle hem, knee-length.'],
            ['name' => 'Tencel Slip Dress',                    'cat' => 'dresses-skirts','price' => 2100, 'stock' => 11, 'desc' => 'Tencel slip dress with adjustable straps. Bias-cut, midi length.'],
            ['name' => 'Shirt Dress',                          'cat' => 'dresses-skirts','price' => 2200, 'stock' => 9,  'desc' => 'Linen-cotton shirt dress. Patch pockets, self-belt, above-knee.'],
            ['name' => 'Tiered Maxi Dress',                    'cat' => 'dresses-skirts','price' => 2600, 'stock' => 7,  'desc' => 'Tiered cotton voile maxi dress. Elastic waist, flutter sleeves.'],

            // ── Skirts ──
            ['name' => 'Linen A-Line Skirt',                   'cat' => 'dresses-skirts','price' => 1550, 'stock' => 15, 'desc' => 'A-line linen skirt. Elastic back waist, side pockets, above-knee.'],
            ['name' => 'Cotton Wrap Skirt',                    'cat' => 'dresses-skirts','price' => 1350, 'stock' => 18, 'desc' => 'Wrap-around cotton skirt. Adjustable tie, midi length.'],
            ['name' => 'Pleated Midi Skirt',                   'cat' => 'dresses-skirts','price' => 1900, 'stock' => 12, 'desc' => 'Pleated Tencel midi skirt. Elastic waist, side zip, smooth drape.'],

            // ── Shorts ──
            ['name' => 'Linen Drawstring Shorts',              'cat' => 'pants-shorts', 'price' => 1100, 'stock' => 25, 'desc' => 'Relaxed linen shorts with drawstring waist. Side pockets, 5" inseam.'],
            ['name' => 'Organic Cotton Chino Shorts',          'cat' => 'pants-shorts', 'price' => 1200, 'stock' => 22, 'desc' => 'Everyday cotton chino shorts. Belt loops, zip fly, 7" inseam.'],
            ['name' => 'Linen Blend Biker Shorts',             'cat' => 'activewear',   'price' => 980,  'stock' => 20, 'desc' => 'Linen-cotton biker shorts. Mid-rise, 5" inseam, performance waistband.'],
            ['name' => 'Denim Cut-Offs',                       'cat' => 'denim',        'price' => 1250, 'stock' => 16, 'desc' => 'Upcycled denim shorts. Raw hem, distressed detail. Each pair unique.'],

            // ── Pants ──
            ['name' => 'Linen Wide Leg Pant',                  'cat' => 'pants-shorts', 'price' => 1800, 'stock' => 14, 'desc' => 'Flowing wide leg linen pant. Elastic waist, side pockets, full length.'],
            ['name' => 'Linen Culottes',                       'cat' => 'pants-shorts', 'price' => 1700, 'stock' => 12, 'desc' => 'Cropped linen culottes. Pleated front, side zip, wide leg.'],
            ['name' => 'Cotton Tapered Trouser',               'cat' => 'pants-shorts', 'price' => 1600, 'stock' => 16, 'desc' => 'Lightweight cotton twill trousers. Tapered leg, single pleat.'],
            ['name' => 'Pajama Pant',                          'cat' => 'pants-shorts', 'price' => 1150, 'stock' => 20, 'desc' => 'Cotton pajama pant with drawstring. All-over print, side pockets.'],

            // ── Swimwear ──
            ['name' => 'Boardshorts',                          'cat' => 'swimwear',     'price' => 1200, 'stock' => 24, 'desc' => 'Recycled polyester boardshorts. Quick-dry, side pocket, 7" inseam.'],
            ['name' => 'Bikini Top',                           'cat' => 'swimwear',     'price' => 980,  'stock' => 20, 'desc' => 'Recycled nylon bikini top. Removable padding, adjustable straps.'],
            ['name' => 'Bikini Bottom',                        'cat' => 'swimwear',     'price' => 850,  'stock' => 22, 'desc' => 'Recycled nylon bikini bottom. Moderate coverage, side ties.'],
            ['name' => 'One-Piece Swimsuit',                   'cat' => 'swimwear',     'price' => 2100, 'stock' => 12, 'desc' => 'Retro one-piece in recycled fabric. Scoop neck, fixed straps, moderate cut.'],
            ['name' => 'Rash Guard',                           'cat' => 'swimwear',     'price' => 1400, 'stock' => 15, 'desc' => 'UPF 50+ rash guard in recycled polyester. Long sleeve, zip neck.'],
            ['name' => 'Swim Trunks',                          'cat' => 'swimwear',     'price' => 1100, 'stock' => 18, 'desc' => 'Quick-dry swim trunks with mesh lining. 5" inseam, back zip pocket.'],

            // ── Outerwear ──
            ['name' => 'Linen Blazer',                         'cat' => 'outerwear',    'price' => 2800, 'stock' => 8,  'desc' => 'Unlined linen blazer. Notch lapel, patch pockets, half-canvassed.'],
            ['name' => 'Cotton Camp Jacket',                   'cat' => 'outerwear',    'price' => 2200, 'stock' => 11, 'desc' => 'Lightweight cotton jacket. Drawstring waist, zip front, packable hood.'],
            ['name' => 'Kaftan Cover-Up',                      'cat' => 'swimwear',     'price' => 1500, 'stock' => 14, 'desc' => 'Linen-cotton kaftan. Open front, side slits, elbow sleeves. Beach-ready.'],

            // ── Footwear ──
            ['name' => 'Espadrilles',                          'cat' => 'footwear',     'price' => 1400, 'stock' => 16, 'desc' => 'Canvas espadrilles with jute sole. Slip-on, elastic inset. Unisex.'],
            ['name' => 'Slide Sandals',                        'cat' => 'footwear',     'price' => 750,  'stock' => 28, 'desc' => 'Suede slide sandals with cushioned footbed. Two-strap.'],
            ['name' => 'Braided Sandals',                      'cat' => 'footwear',     'price' => 950,  'stock' => 20, 'desc' => 'Leather braided sandals. Flat sole, ankle buckle. Handmade.'],

            // ── Bags ──
            ['name' => 'Straw Beach Tote',                     'cat' => 'bags-backpacks','price' => 1200, 'stock' => 18, 'desc' => 'Handwoven straw tote. Leather handles, open top, 45x35cm.'],
            ['name' => 'Canvas Shopper',                       'cat' => 'bags-backpacks','price' => 850,  'stock' => 25, 'desc' => 'Organic cotton canvas tote. Reinforced bottom, 20L capacity.'],
            ['name' => 'Nylon Backpack',                       'cat' => 'bags-backpacks','price' => 1900, 'stock' => 12, 'desc' => 'Lightweight ripstop nylon backpack. 25L, padded laptop sleeve.'],
            ['name' => 'Drawstring Beach Bag',                 'cat' => 'bags-backpacks','price' => 550,  'stock' => 30, 'desc' => 'Mesh drawstring bag. Drawstring closure, 40L. Perfect for the beach.'],

            // ── Accessories ──
            ['name' => 'Coconut Shell Sunglasses',             'cat' => 'accessories',  'price' => 680,  'stock' => 22, 'desc' => 'Coconut-shell frames with polarized lenses. UV400 protection.'],
            ['name' => 'Beaded Bracelet',                      'cat' => 'accessories',  'price' => 280,  'stock' => 40, 'desc' => 'Hand-beaded bracelet with natural stone. Adjustable knot, one size.'],
            ['name' => 'Straw Sun Hat',                        'cat' => 'hats-beanies', 'price' => 950,  'stock' => 16, 'desc' => 'Wide-brim straw sun hat. UPF 50+, internal drawstring. 10cm brim.'],
            ['name' => 'Linen Face Mask',                      'cat' => 'accessories',  'price' => 320,  'stock' => 35, 'desc' => 'Double-layer linen face mask. Filter pocket, wire nose bridge.'],
            ['name' => 'Cotton Bucket Hat',                    'cat' => 'hats-beanies', 'price' => 580,  'stock' => 24, 'desc' => 'Packable cotton bucket hat. UPF 40+, chin strap optional.']
        ];

        // Fill to 66 products for Salt & Cedar
        $saltCedarProducts[] = ['name' => 'Linen Napkin Set',        'cat' => 'vintage-archive','price' => 450, 'stock' => 20, 'desc' => 'Set of 4 hand-hemmed linen napkins. 45x45cm. Natural dyes.'];
        $saltCedarProducts[] = ['name' => 'Soy Candle',              'cat' => 'vintage-archive','price' => 380, 'stock' => 28, 'desc' => 'Soy wax candle in amber jar. Scent: sea salt + cedar. 40hr burn.'];
        $saltCedarProducts[] = ['name' => 'Sea Salt Body Scrub',     'cat' => 'vintage-archive','price' => 320, 'stock' => 30, 'desc' => 'All-natural sea salt scrub with coconut oil. 200g. Made in Palawan.'];
        $saltCedarProducts[] = ['name' => 'Linen Beach Towel',       'cat' => 'swimwear',      'price' => 1100,'stock' => 14, 'desc' => '100% linen beach towel. Fringed edges, 70x140cm. Sand-resistant.'];
        $saltCedarProducts[] = ['name' => 'Hemp Tote Bag',           'cat' => 'bags-backpacks','price' => 750, 'stock' => 22, 'desc' => 'Hemp-cotton blend tote. Hand-dyed, 40x35cm. Compostable.'];
        $saltCedarProducts[] = ['name' => 'Lace Up Sandals',         'cat' => 'footwear',      'price' => 1100,'stock' => 14, 'desc' => 'Lace-up leather sandals. Flat sole, adjustable ankle ties.'];
        $saltCedarProducts[] = ['name' => 'Cotton Cover-Up',         'cat' => 'swimwear',      'price' => 1350,'stock' => 12, 'desc' => 'White cotton crochet cover-up. Tunic length, bell sleeves.'];
        $saltCedarProducts[] = ['name' => 'Coconut Oil Lip Balm',    'cat' => 'accessories',   'price' => 180, 'stock' => 45, 'desc' => 'Organic coconut oil lip balm. SPF 15. Beeswax base.'];
        $saltCedarProducts[] = ['name' => 'Handwoven Placemat',      'cat' => 'vintage-archive','price' => 280, 'stock' => 32, 'desc' => 'Abaca handwoven placemat. Set of 2. Natural fiber. 30x45cm. '];
        $saltCedarProducts[] = ['name' => 'Tencel Pajama Set',       'cat' => 'pants-shorts',  'price' => 2100,'stock' => 8,  'desc' => 'Tencel pajama set. Short sleeve, short bottom. Buttery soft.'];
        $saltCedarProducts[] = ['name' => 'Fishing Hat',             'cat' => 'hats-beanies',  'price' => 680, 'stock' => 18, 'desc' => 'Cotton twill fishing hat with neck flap. UPF 50+.'];
        $saltCedarProducts[] = ['name' => 'Bamboo Sunglasses',       'cat' => 'accessories',   'price' => 890, 'stock' => 16, 'desc' => 'Bamboo frame polarized sunglasses. UV400, including hard case.'];
        $saltCedarProducts[] = ['name' => 'Macrame Wall Hanging',    'cat' => 'vintage-archive','price' => 750, 'stock' => 10, 'desc' => 'Handmade cotton macrame wall hanging. 60cm drop. Boho decor.'];
        $saltCedarProducts[] = ['name' => 'Tote Bag Kit',            'cat' => 'bags-backpacks','price' => 480, 'stock' => 24, 'desc' => 'DIY tote bag kit. Pre-cut linen, thread, pattern. Make your own.'];
        $saltCedarProducts[] = ['name' => 'Abaca Slippers',          'cat' => 'footwear',      'price' => 580, 'stock' => 22, 'desc' => 'Handwoven abaca slippers. Natural fiber, rubber sole. Indoor/outdoor.'];
        $saltCedarProducts[] = ['name' => 'Muslin Pillowcase',       'cat' => 'vintage-archive','price' => 420, 'stock' => 26, 'desc' => 'Unbleached muslin pillowcase. Pre-washed, envelope closure.'];
        $saltCedarProducts[] = ['name' => 'Crochet Market Bag',      'cat' => 'bags-backpacks','price' => 520, 'stock' => 30, 'desc' => 'Hand-crocheted cotton market bag. Stretchy, machine washable.'];
        $saltCedarProducts[] = ['name' => 'Linen Journal',           'cat' => 'accessories',   'price' => 380, 'stock' => 35, 'desc' => 'Linen-bound journal. 120 blank pages, recycled paper. A5 size.'];


        // ── Combine all ──
        $products = array_merge($ironProducts, $northboundProducts, $saltCedarProducts);

        // ── Color palette for images ──
        $ironPalette   = [['bg' => '1a1a2e', 'fg' => 'e1d7c6'], ['bg' => '2d1b14', 'fg' => 'd4c9b8'], ['bg' => '2a3a2f', 'fg' => 'e8dcc8'], ['bg' => '3d2b1f', 'fg' => 'ece3d5']];
        $northPalette  = [['bg' => '2d4a3b', 'fg' => 'e8dcc8'], ['bg' => '4a3b2d', 'fg' => 'f0e8d8'], ['bg' => '3b4a5a', 'fg' => 'e0e8f0'], ['bg' => '5a3b2d', 'fg' => 'f5ede5']];
        $saltPalette   = [['bg' => 'c4b89e', 'fg' => '2a3a2f'], ['bg' => 'd4cfc5', 'fg' => '3a4a3f'], ['bg' => 'e8e0d5', 'fg' => '4a5a4f'], ['bg' => 'b8d4c4', 'fg' => '2a4a3f']];

        $storeMap = [
            'iron-loom'        => ['store' => $ironLoom, 'palette' => $ironPalette],
            'northbound-knits' => ['store' => $northbound, 'palette' => $northPalette],
            'salt-and-cedar'   => ['store' => $saltCedar, 'palette' => $saltPalette],
        ];

        foreach ($products as $i => $data) {
            // Determine which store this product belongs to
            $idx = 0;
            if ($i >= count($ironProducts)) {
                $idx = 1;
            }
            if ($i >= count($ironProducts) + count($northboundProducts)) {
                $idx = 2;
            }

            $storeSlug = array_keys($storeMap)[$idx];
            $storeInfo = $storeMap[$storeSlug];
            $store     = $storeInfo['store'];
            $palette   = $storeInfo['palette'];
            $color     = $palette[$i % count($palette)];

            $categoryId = $cat($data['cat']);
            if (! $categoryId) {
                continue;
            }

            $slug = str($data['name'])->slug();

            $product = Product::firstOrCreate(
                ['slug' => $slug],
                [
                    'store_id'     => $store->id,
                    'category_id'  => $categoryId,
                    'name'         => $data['name'],
                    'description'  => $data['desc'],
                    'price'        => $data['price'],
                    'stock'        => $data['stock'],
                    'is_published' => true,
                ]
            );

            if ($product->images()->count() === 0) {
                $product->images()->create([
                    'path'       => $img($data['name'], $color['bg'], $color['fg']),
                    'sort_order' => 0,
                ]);

                // Add a second image for visual depth
                $product->images()->create([
                    'path'       => $extraImage($data['name'] . ' Detail', $color['fg'], $color['bg']),
                    'sort_order' => 1,
                ]);
            }
        }

        $count = count($products);
        echo "Seeded {$count} products across 3 stores.\n";
    }
}
