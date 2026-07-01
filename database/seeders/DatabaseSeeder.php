<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Category;
use App\Models\GalleryImage;
use App\Models\Product;
use App\Models\SiteContent;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@norajewellery.test'],
            [
                'name' => 'Nora Admin',
                'password' => Hash::make('password'),
                'is_admin' => true,
            ],
        );

        $categories = collect([
            ['name' => 'Rings', 'slug' => 'rings', 'description' => 'Statement solitaires, bands, and sculptural rings.', 'image_path' => 'https://images.unsplash.com/photo-1605100804763-247f67b3557e?auto=format&fit=crop&w=900&q=85'],
            ['name' => 'Necklaces', 'slug' => 'necklaces', 'description' => 'Fine chains, chokers, and luminous necklace sets.', 'image_path' => 'https://images.unsplash.com/photo-1611591437281-460bfbe1220a?auto=format&fit=crop&w=900&q=85'],
            ['name' => 'Earrings', 'slug' => 'earrings', 'description' => 'Delicate studs, drops, hoops, and occasion earrings.', 'image_path' => 'https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?auto=format&fit=crop&w=900&q=85'],
            ['name' => 'Bracelets', 'slug' => 'bracelets', 'description' => 'Refined bracelets with everyday ease and ceremony finish.', 'image_path' => 'https://images.unsplash.com/photo-1611652022419-a9419f74343d?auto=format&fit=crop&w=900&q=85'],
            ['name' => 'Bridal Sets', 'slug' => 'bridal-sets', 'description' => 'Coordinated bridal pieces for heirloom celebrations.', 'image_path' => 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?auto=format&fit=crop&w=900&q=85'],
            ['name' => 'Bangles', 'slug' => 'bangles', 'description' => 'Classic and contemporary bangles with polished detail.', 'image_path' => 'https://images.unsplash.com/photo-1602751584552-8ba73aad10e1?auto=format&fit=crop&w=900&q=85'],
        ])->mapWithKeys(function (array $data, int $index) {
            $category = Category::updateOrCreate(['slug' => $data['slug']], $data + [
                'sort_order' => $index + 1,
                'is_active' => true,
            ]);

            return [$category->slug => $category];
        });

        $products = [
            ['rings', 'Celeste Diamond Ring', 'celeste-diamond-ring', 189000, 225000, 7, true, true, 'A luminous solitaire-inspired ring with a refined gold shoulder.', 'https://images.unsplash.com/photo-1605100804763-247f67b3557e?auto=format&fit=crop&w=900&q=85'],
            ['rings', 'Aurora Halo Band', 'aurora-halo-band', 98000, 125000, 4, true, false, 'A halo-set ring shaped for celebration and daily elegance.', 'https://images.unsplash.com/photo-1603561591411-07134e71a2a9?auto=format&fit=crop&w=900&q=85'],
            ['necklaces', 'Seraphine Gold Necklace', 'seraphine-gold-necklace', 245000, 285000, 3, true, true, 'A radiant necklace with a soft curve and brilliant finish.', 'https://images.unsplash.com/photo-1611591437281-460bfbe1220a?auto=format&fit=crop&w=900&q=85'],
            ['necklaces', 'Moonlit Choker', 'moonlit-choker', null, null, 0, false, true, 'A sculpted choker with modern bridal polish.', 'https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?auto=format&fit=crop&w=900&q=85'],
            ['earrings', 'Isla Drop Earrings', 'isla-drop-earrings', 76000, 92000, 9, true, false, 'Elegant drops with graceful movement and warm gold reflections.', 'https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?auto=format&fit=crop&w=900&q=85'],
            ['earrings', 'Noor Pearl Studs', 'noor-pearl-studs', 42000, 52000, 12, false, true, 'Soft pearl studs balanced with polished gold detail.', 'https://images.unsplash.com/photo-1588444837495-c6cfeb53f32d?auto=format&fit=crop&w=900&q=85'],
            ['bracelets', 'Aurelia Tennis Bracelet', 'aurelia-tennis-bracelet', 165000, 198000, 5, true, false, 'A precise tennis bracelet with light-catching stones.', 'https://images.unsplash.com/photo-1611652022419-a9419f74343d?auto=format&fit=crop&w=900&q=85'],
            ['bracelets', 'Mira Chain Bracelet', 'mira-chain-bracelet', 64000, 78000, 8, false, true, 'A clean gold bracelet for refined everyday styling.', 'https://images.unsplash.com/photo-1617038260897-41a1f14a8ca0?auto=format&fit=crop&w=900&q=85'],
            ['bridal-sets', 'Nora Bridal Radiance Set', 'nora-bridal-radiance-set', null, null, 0, true, false, 'A complete bridal set designed for private consultation.', 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?auto=format&fit=crop&w=900&q=85'],
            ['bangles', 'Saanvi Gold Bangles', 'saanvi-gold-bangles', 132000, 158000, 6, false, true, 'Stackable bangles with a polished, ceremonial profile.', 'https://images.unsplash.com/photo-1602751584552-8ba73aad10e1?auto=format&fit=crop&w=900&q=85'],
        ];

        foreach ($products as [$categorySlug, $name, $slug, $price, $compareAtPrice, $stock, $featured, $newArrival, $short, $image]) {
            $product = Product::updateOrCreate(
                ['slug' => $slug],
                [
                    'category_id' => $categories[$categorySlug]->id,
                    'name' => $name,
                    'price' => $price,
                    'compare_at_price' => $compareAtPrice,
                    'price_on_request' => $price === null,
                    'stock_quantity' => $stock,
                    'short_description' => $short,
                    'description' => $short."\n\nEach piece is finished with Nora Jewellery's signature attention to proportion, polish, and wearability.",
                    'is_featured' => $featured,
                    'is_new_arrival' => $newArrival,
                    'is_active' => true,
                    'meta_title' => $name.' | Nora Jewellery',
                    'meta_description' => $short,
                ],
            );

            $product->images()->updateOrCreate(
                ['sort_order' => 1],
                [
                    'image_path' => $image,
                    'alt_text' => $name,
                    'is_primary' => true,
                ],
            );
        }

        foreach ([
            ['The Nora Bridal Edit', 'Rare radiance for vows, rituals, and heirloom moments.', 'Bridal 2026', 'Explore Collections', '/collections/bridal-sets', 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?auto=format&fit=crop&w=1800&q=85'],
            ['Fine Jewellery With Modern Grace', 'Hand-finished gold, diamonds, pearls, and bespoke silhouettes.', 'Atelier Pieces', 'Book Appointment', '/contact', 'https://images.unsplash.com/photo-1617038260897-41a1f14a8ca0?auto=format&fit=crop&w=1800&q=85'],
        ] as $index => [$title, $subtitle, $eyebrow, $cta, $url, $image]) {
            Banner::updateOrCreate(
                ['title' => $title],
                [
                    'subtitle' => $subtitle,
                    'eyebrow' => $eyebrow,
                    'cta_label' => $cta,
                    'cta_url' => $url,
                    'image_path' => $image,
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ],
            );
        }

        foreach ([
            'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?auto=format&fit=crop&w=900&q=85',
            'https://images.unsplash.com/photo-1605100804763-247f67b3557e?auto=format&fit=crop&w=900&q=85',
            'https://images.unsplash.com/photo-1611591437281-460bfbe1220a?auto=format&fit=crop&w=900&q=85',
            'https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?auto=format&fit=crop&w=900&q=85',
            'https://images.unsplash.com/photo-1611652022419-a9419f74343d?auto=format&fit=crop&w=900&q=85',
            'https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?auto=format&fit=crop&w=900&q=85',
        ] as $index => $image) {
            GalleryImage::updateOrCreate(
                ['image_path' => $image],
                [
                    'title' => 'Nora Jewellery Detail '.($index + 1),
                    'alt_text' => 'Luxury jewellery detail',
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ],
            );
        }

        SiteContent::updateOrCreate(
            ['key' => 'about'],
            [
                'title' => 'A House Of Quiet Radiance',
                'content' => 'Nora Jewellery creates premium pieces that feel intimate, sculptural, and enduring. Every design balances graceful proportions with meticulous finishing for jewellery that moves easily from private rituals to everyday elegance.',
                'image_path' => 'https://images.unsplash.com/photo-1617038260897-41a1f14a8ca0?auto=format&fit=crop&w=1200&q=85',
                'data' => [
                    'heritage' => '18K',
                    'craft' => 'Hand',
                    'promise' => 'Bespoke',
                ],
            ],
        );

        SiteContent::updateOrCreate(
            ['key' => 'contact'],
            [
                'title' => 'Book A Private Jewellery Appointment',
                'content' => 'Speak with Nora Jewellery consultants for bridal styling, custom designs, collection viewings, and gifting advice.',
                'data' => [
                    'phone' => '+91 8848254420',
                    'email' => 'norajewels0523@gmail.com',
                    'address' => 'Nora Jewels nss college(po) Nemmara, palakkad 678508',
                    'hours' => 'Monday to Saturday, 10:00 AM to 7:00 PM',
                    'map_url' => '',
                ],
            ],
        );

        SiteContent::updateOrCreate(
            ['key' => 'delivery'],
            [
                'title' => 'Delivery Settings',
                'data' => [
                    'is_free_delivery' => true,
                    'delivery_charge' => 0,
                ],
            ],
        );
    }
}
