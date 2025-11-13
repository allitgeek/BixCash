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
            // System Roles (Cannot be deleted)
            [
                'name' => 'super_admin',
                'display_name' => 'Super Administrator',
                'description' => 'Complete system access including user management and system settings',
                'permissions' => [
                    // Legacy permissions (kept for backward compatibility)
                    'manage_users', 'manage_roles', 'manage_settings', 'manage_content',
                    'manage_brands', 'manage_promotions', 'manage_partners', 'view_analytics',
                    'manage_finances', 'system_backup', 'system_maintenance',
                    // New action-level permissions (ALL)
                    'dashboard.view', 'dashboard.view_stats',
                    'users.view', 'users.create', 'users.edit', 'users.delete', 'users.toggle_status', 'users.manage_permissions',
                    'roles.view', 'roles.create', 'roles.edit', 'roles.delete', 'roles.assign_permissions',
                    'customers.view', 'customers.create', 'customers.edit', 'customers.delete', 'customers.verify', 'customers.toggle_status', 'customers.view_profile', 'customers.edit_wallet',
                    'partners.view', 'partners.create', 'partners.edit', 'partners.delete', 'partners.verify', 'partners.toggle_status', 'partners.view_profile',
                    'brands.view', 'brands.create', 'brands.edit', 'brands.delete', 'brands.toggle_status',
                    'transactions.view', 'transactions.view_details', 'transactions.refund', 'transactions.export',
                    'withdrawals.view', 'withdrawals.approve', 'withdrawals.reject', 'withdrawals.view_details',
                    'profit_sharing.view', 'profit_sharing.calculate', 'profit_sharing.distribute', 'profit_sharing.view_history',
                    'content.view', 'content.create', 'content.edit', 'content.delete', 'content.toggle_status',
                    'categories.view', 'categories.create', 'categories.edit', 'categories.delete', 'categories.toggle_status',
                    'reports.view', 'reports.generate', 'reports.export', 'reports.view_analytics',
                    'settings.view', 'settings.edit_general', 'settings.edit_withdrawals', 'settings.edit_system',
                    'maintenance.view', 'maintenance.backup', 'maintenance.restore', 'maintenance.clear_cache', 'maintenance.view_logs',
                ],
                'is_active' => true,
                'is_system' => true
            ],
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Most administrative operations except system-critical functions',
                'permissions' => [
                    // Legacy permissions
                    'manage_content', 'manage_brands', 'manage_promotions', 'manage_partners',
                    'view_analytics', 'manage_customer_support', 'manage_transactions',
                    // New action-level permissions
                    'dashboard.view', 'dashboard.view_stats',
                    'users.view',
                    'customers.view', 'customers.create', 'customers.edit', 'customers.verify', 'customers.toggle_status', 'customers.view_profile',
                    'partners.view', 'partners.create', 'partners.edit', 'partners.verify', 'partners.toggle_status', 'partners.view_profile',
                    'brands.view', 'brands.create', 'brands.edit', 'brands.toggle_status',
                    'transactions.view', 'transactions.view_details', 'transactions.export',
                    'withdrawals.view', 'withdrawals.view_details',
                    'content.view', 'content.create', 'content.edit', 'content.toggle_status',
                    'categories.view', 'categories.create', 'categories.edit', 'categories.toggle_status',
                    'reports.view', 'reports.generate', 'reports.export', 'reports.view_analytics',
                    'settings.view', 'settings.edit_general',
                ],
                'is_active' => true,
                'is_system' => true
            ],
            [
                'name' => 'moderator',
                'display_name' => 'Moderator',
                'description' => 'Limited content management and customer support',
                'permissions' => [
                    // Legacy permissions
                    'manage_content', 'view_analytics', 'manage_customer_support',
                    // New action-level permissions
                    'dashboard.view',
                    'customers.view', 'customers.view_profile',
                    'content.view', 'content.create', 'content.edit',
                    'reports.view',
                ],
                'is_active' => true,
                'is_system' => true
            ],
            [
                'name' => 'partner',
                'display_name' => 'Partner',
                'description' => 'Business partners with access to their own brand management',
                'permissions' => [
                    'manage_own_brands', 'manage_own_promotions', 'view_own_analytics',
                    'manage_own_profile'
                ],
                'is_active' => true,
                'is_system' => true
            ],
            [
                'name' => 'customer',
                'display_name' => 'Customer',
                'description' => 'Regular customers with basic profile and transaction access',
                'permissions' => [
                    'view_own_profile', 'manage_own_profile', 'view_own_transactions',
                    'view_own_earnings', 'request_withdrawal'
                ],
                'is_active' => true,
                'is_system' => true
            ],

            // New Admin Team Roles (Can be modified/deleted)
            [
                'name' => 'manager',
                'display_name' => 'Manager',
                'description' => 'Full operational access to all modules except system-critical functions',
                'permissions' => [
                    'dashboard.view', 'dashboard.view_stats',
                    'users.view', 'users.create', 'users.edit', 'users.toggle_status',
                    'customers.view', 'customers.create', 'customers.edit', 'customers.delete', 'customers.verify', 'customers.toggle_status', 'customers.view_profile', 'customers.edit_wallet',
                    'partners.view', 'partners.create', 'partners.edit', 'partners.delete', 'partners.verify', 'partners.toggle_status', 'partners.view_profile',
                    'brands.view', 'brands.create', 'brands.edit', 'brands.delete', 'brands.toggle_status',
                    'transactions.view', 'transactions.view_details', 'transactions.refund', 'transactions.export',
                    'withdrawals.view', 'withdrawals.approve', 'withdrawals.reject', 'withdrawals.view_details',
                    'profit_sharing.view', 'profit_sharing.calculate', 'profit_sharing.distribute', 'profit_sharing.view_history',
                    'content.view', 'content.create', 'content.edit', 'content.delete', 'content.toggle_status',
                    'categories.view', 'categories.create', 'categories.edit', 'categories.delete', 'categories.toggle_status',
                    'reports.view', 'reports.generate', 'reports.export', 'reports.view_analytics',
                    'settings.view', 'settings.edit_general', 'settings.edit_withdrawals',
                ],
                'is_active' => true,
                'is_system' => false
            ],
            [
                'name' => 'assistant_manager',
                'display_name' => 'Assistant Manager',
                'description' => 'Limited operational access, can view and edit but cannot delete critical data',
                'permissions' => [
                    'dashboard.view', 'dashboard.view_stats',
                    'customers.view', 'customers.edit', 'customers.verify', 'customers.view_profile',
                    'partners.view', 'partners.edit', 'partners.view_profile',
                    'brands.view', 'brands.edit', 'brands.toggle_status',
                    'transactions.view', 'transactions.view_details', 'transactions.export',
                    'withdrawals.view', 'withdrawals.view_details',
                    'content.view', 'content.create', 'content.edit', 'content.toggle_status',
                    'categories.view', 'categories.edit',
                    'reports.view', 'reports.generate', 'reports.export',
                    'settings.view',
                ],
                'is_active' => true,
                'is_system' => false
            ],
            [
                'name' => 'partner_support',
                'display_name' => 'Partner Support',
                'description' => 'Dedicated to partner management and support',
                'permissions' => [
                    'dashboard.view',
                    'partners.view', 'partners.create', 'partners.edit', 'partners.verify', 'partners.toggle_status', 'partners.view_profile',
                    'brands.view', 'brands.create', 'brands.edit', 'brands.toggle_status',
                    'transactions.view', 'transactions.view_details',
                    'reports.view', 'reports.generate',
                ],
                'is_active' => true,
                'is_system' => false
            ],
            [
                'name' => 'customer_support',
                'display_name' => 'Customer Support',
                'description' => 'Dedicated to customer service and support',
                'permissions' => [
                    'dashboard.view',
                    'customers.view', 'customers.edit', 'customers.verify', 'customers.view_profile',
                    'transactions.view', 'transactions.view_details',
                    'withdrawals.view', 'withdrawals.view_details',
                    'reports.view',
                ],
                'is_active' => true,
                'is_system' => false
            ],
            [
                'name' => 'finance_admin',
                'display_name' => 'Finance Administrator',
                'description' => 'Manages financial operations including withdrawals and profit sharing',
                'permissions' => [
                    'dashboard.view', 'dashboard.view_stats',
                    'customers.view', 'customers.view_profile', 'customers.edit_wallet',
                    'transactions.view', 'transactions.view_details', 'transactions.refund', 'transactions.export',
                    'withdrawals.view', 'withdrawals.approve', 'withdrawals.reject', 'withdrawals.view_details',
                    'profit_sharing.view', 'profit_sharing.calculate', 'profit_sharing.distribute', 'profit_sharing.view_history',
                    'reports.view', 'reports.generate', 'reports.export', 'reports.view_analytics',
                    'settings.view', 'settings.edit_withdrawals',
                ],
                'is_active' => true,
                'is_system' => false
            ],
        ];

        foreach ($roles as $roleData) {
            Role::updateOrCreate(
                ['name' => $roleData['name']],
                $roleData
            );
        }

        $this->command->info('Roles seeded successfully!');
    }
}
