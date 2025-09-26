<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'Super Administrator',
                'description' => 'Complete system access including user management and system settings',
                'permissions' => [
                    'manage_users', 'manage_roles', 'manage_settings', 'manage_content',
                    'manage_brands', 'manage_promotions', 'manage_partners', 'view_analytics',
                    'manage_finances', 'system_backup', 'system_maintenance'
                ],
                'is_active' => true
            ],
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Most administrative operations except system-critical functions',
                'permissions' => [
                    'manage_content', 'manage_brands', 'manage_promotions', 'manage_partners',
                    'view_analytics', 'manage_customer_support', 'manage_transactions'
                ],
                'is_active' => true
            ],
            [
                'name' => 'moderator',
                'display_name' => 'Moderator',
                'description' => 'Limited content management and customer support',
                'permissions' => [
                    'manage_content', 'view_analytics', 'manage_customer_support'
                ],
                'is_active' => true
            ],
            [
                'name' => 'partner',
                'display_name' => 'Partner',
                'description' => 'Business partners with access to their own brand management',
                'permissions' => [
                    'manage_own_brands', 'manage_own_promotions', 'view_own_analytics',
                    'manage_own_profile'
                ],
                'is_active' => true
            ],
            [
                'name' => 'customer',
                'display_name' => 'Customer',
                'description' => 'Regular customers with basic profile and transaction access',
                'permissions' => [
                    'view_own_profile', 'manage_own_profile', 'view_own_transactions',
                    'view_own_earnings', 'request_withdrawal'
                ],
                'is_active' => true
            ]
        ];

        foreach ($roles as $roleData) {
            Role::firstOrCreate(
                ['name' => $roleData['name']],
                $roleData
            );
        }
    }
}
