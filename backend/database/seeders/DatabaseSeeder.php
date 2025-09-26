<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles first (required for users)
        $this->call([
            RoleSeeder::class,
        ]);

        // Clear existing users (except keep existing data)
        // User::query()->delete(); // Commented out to preserve existing data

        // Create admin users
        $this->call([
            AdminUserSeeder::class,
        ]);

        // Create test user if it doesn't exist
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password')
            ]
        );

        // Seed content data
        $this->call([
            SlideSeeder::class,
            CategorySeeder::class,
            BrandSeeder::class,
        ]);
    }
}
