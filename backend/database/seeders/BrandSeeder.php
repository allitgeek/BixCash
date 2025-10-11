<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Brand::query()->delete();

        $brands = [
            ['name' => 'alkaram', 'logo_path' => '/images/brands/alkaram.jpg', 'order' => 1],
            ['name' => 'almirah', 'logo_path' => '/images/brands/almirah.jpg', 'order' => 2],
            ['name' => 'COTTON & SILK', 'logo_path' => '/images/brands/cotton and silk.png', 'order' => 3],
            ['name' => 'GulAhmed', 'logo_path' => '/images/brands/gulahmed.jpg', 'order' => 4],
            ['name' => 'J.', 'logo_path' => '/images/brands/J.png', 'order' => 5],
            ['name' => 'Khaadi', 'logo_path' => '/images/brands/khaadi.png', 'order' => 6],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }
    }
}
