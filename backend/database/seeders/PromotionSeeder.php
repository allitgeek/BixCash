<?php

namespace Database\Seeders;

use App\Models\Promotion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PromotionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing promotions
        Promotion::truncate();

        // Current promotion data from welcome.blade.php
        $promotions = [
            [
                'brand_name' => 'SAYA',
                'logo_path' => '/images/promotions/Saya.png',
                'discount_type' => 'upto',
                'discount_value' => 20,
                'discount_text' => 'Upto 20% Off',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'brand_name' => 'Junaid Jamshed',
                'logo_path' => '/images/promotions/junaid-jamshed.png',
                'discount_type' => 'upto',
                'discount_value' => 30,
                'discount_text' => 'Upto 30% Off',
                'is_active' => true,
                'order' => 2,
            ],
            [
                'brand_name' => 'Gul Ahmed',
                'logo_path' => '/images/promotions/gul-ahmed.png',
                'discount_type' => 'flat',
                'discount_value' => 20,
                'discount_text' => 'Flat 20% Off',
                'is_active' => true,
                'order' => 3,
            ],
            [
                'brand_name' => 'Bata',
                'logo_path' => '/images/promotions/Bata.png',
                'discount_type' => 'flat',
                'discount_value' => 50,
                'discount_text' => 'Flat 50% Off',
                'is_active' => true,
                'order' => 4,
            ],
            [
                'brand_name' => 'Tayto',
                'logo_path' => '/images/promotions/tayto.jpg',
                'discount_type' => 'upto',
                'discount_value' => 30,
                'discount_text' => 'Upto 30% Off',
                'is_active' => true,
                'order' => 5,
            ],
            [
                'brand_name' => 'KFC',
                'logo_path' => '/images/promotions/kfc.png',
                'discount_type' => 'upto',
                'discount_value' => 20,
                'discount_text' => 'Upto 20% Off',
                'is_active' => true,
                'order' => 6,
            ],
            [
                'brand_name' => 'Joyland',
                'logo_path' => '/images/promotions/joyland.png',
                'discount_type' => 'flat',
                'discount_value' => 50,
                'discount_text' => 'Flat 50% Off',
                'is_active' => true,
                'order' => 7,
            ],
            [
                'brand_name' => 'Sapphire',
                'logo_path' => '/images/promotions/Sapphire.jpg',
                'discount_type' => 'flat',
                'discount_value' => 50,
                'discount_text' => 'Flat 50% Off',
                'is_active' => true,
                'order' => 8,
            ],
        ];

        foreach ($promotions as $promotionData) {
            Promotion::create($promotionData);
        }

        $this->command->info('Successfully seeded ' . count($promotions) . ' promotions.');
    }
}
