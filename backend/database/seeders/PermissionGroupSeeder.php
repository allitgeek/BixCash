<?php

namespace Database\Seeders;

use App\Models\PermissionGroup;
use Illuminate\Database\Seeder;

class PermissionGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groups = [
            [
                'name' => 'dashboard',
                'display_name' => 'Dashboard & Analytics',
                'icon' => '📊',
                'description' => 'Access to dashboard and analytics',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'users',
                'display_name' => 'User Management',
                'icon' => '👤',
                'description' => 'Manage admin users and their access',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'roles',
                'display_name' => 'Roles & Permissions',
                'icon' => '🔐',
                'description' => 'Manage roles and permissions',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'customers',
                'display_name' => 'Customer Management',
                'icon' => '👥',
                'description' => 'Manage customer accounts and profiles',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'partners',
                'display_name' => 'Partner Management',
                'icon' => '🤝',
                'description' => 'Manage business partners',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'brands',
                'display_name' => 'Brand Management',
                'icon' => '🏢',
                'description' => 'Manage partner brands',
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'transactions',
                'display_name' => 'Transaction Management',
                'icon' => '💳',
                'description' => 'View and manage transactions',
                'sort_order' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'withdrawals',
                'display_name' => 'Withdrawal Management',
                'icon' => '💰',
                'description' => 'Manage withdrawal requests',
                'sort_order' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'profit_sharing',
                'display_name' => 'Profit Sharing',
                'icon' => '💸',
                'description' => 'Manage profit sharing calculations',
                'sort_order' => 9,
                'is_active' => true,
            ],
            [
                'name' => 'commissions',
                'display_name' => 'Commission Management',
                'icon' => '💰',
                'description' => 'Manage partner commissions and settlements',
                'sort_order' => 10,
                'is_active' => true,
            ],
            [
                'name' => 'content',
                'display_name' => 'Content Management',
                'icon' => '📝',
                'description' => 'Manage slides, promotions, and content',
                'sort_order' => 11,
                'is_active' => true,
            ],
            [
                'name' => 'categories',
                'display_name' => 'Category Management',
                'icon' => '📂',
                'description' => 'Manage product categories',
                'sort_order' => 12,
                'is_active' => true,
            ],
            [
                'name' => 'reports',
                'display_name' => 'Reports & Analytics',
                'icon' => '📈',
                'description' => 'Generate and export reports',
                'sort_order' => 13,
                'is_active' => true,
            ],
            [
                'name' => 'settings',
                'display_name' => 'System Settings',
                'icon' => '⚙️',
                'description' => 'Configure system settings',
                'sort_order' => 14,
                'is_active' => true,
            ],
            [
                'name' => 'maintenance',
                'display_name' => 'System Maintenance',
                'icon' => '🔧',
                'description' => 'System backups and maintenance tasks',
                'sort_order' => 15,
                'is_active' => true,
            ],
        ];

        foreach ($groups as $group) {
            PermissionGroup::updateOrCreate(
                ['name' => $group['name']],
                $group
            );
        }

        $this->command->info('Permission groups seeded successfully!');
    }
}
