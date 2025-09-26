<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::query()->delete();

        $categories = [
            ['name' => 'Food & Beverage', 'icon_path' => '/images/categories/Food and Beverage.jpg', 'order' => 1],
            ['name' => 'Health & Beauty', 'icon_path' => '/images/categories/Health and beauty.png', 'order' => 2],
            ['name' => 'Fashion', 'icon_path' => '/images/categories/Fashion.png', 'order' => 3],
            ['name' => 'Clothing', 'icon_path' => '/images/categories/clothing.jpg', 'order' => 4],
            ['name' => 'Bags', 'icon_path' => '/images/categories/bags.png', 'order' => 5],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
