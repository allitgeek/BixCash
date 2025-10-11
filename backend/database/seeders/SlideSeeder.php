<?php

namespace Database\Seeders;

use App\Models\Slide;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SlideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Slide::query()->delete();

        Slide::create([
            'title' => 'Download & Win',
            'description' => 'Inspire Home Appliances',
            'media_type' => 'image',
            'media_path' => '/images/slides/slide1.jpg',
            'order' => 1,
            'is_active' => true,
        ]);

        Slide::create([
            'title' => 'Another Great Offer',
            'description' => 'Check out our new promotions',
            'media_type' => 'image',
            'media_path' => '/images/slides/slide2.jpg',
            'order' => 2,
            'is_active' => true,
        ]);
    }
}
