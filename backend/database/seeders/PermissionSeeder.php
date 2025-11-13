<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PermissionGroup;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = $this->getPermissions();

        foreach ($permissions as $permissionData) {
            $group = PermissionGroup::where('name', $permissionData['group'])->first();

            if ($group) {
                Permission::updateOrCreate(
                    ['name' => $permissionData['name']],
                    [
                        'display_name' => $permissionData['display_name'],
                        'description' => $permissionData['description'],
                        'permission_group_id' => $group->id,
                        'is_active' => $permissionData['is_active'] ?? true,
                    ]
                );
            }
        }

        $this->command->info('Permissions seeded successfully!');
    }

    /**
     * Get all permissions organized by module
     */
    private function getPermissions(): array
    {
        return [
            // Dashboard & Analytics
            ['group' => 'dashboard', 'name' => 'dashboard.view', 'display_name' => 'View Dashboard', 'description' => 'Access main dashboard'],
            ['group' => 'dashboard', 'name' => 'dashboard.view_stats', 'display_name' => 'View Statistics', 'description' => 'View dashboard statistics and metrics'],

            // User Management
            ['group' => 'users', 'name' => 'users.view', 'display_name' => 'View Users', 'description' => 'View admin user list'],
            ['group' => 'users', 'name' => 'users.create', 'display_name' => 'Create Users', 'description' => 'Create new admin users'],
            ['group' => 'users', 'name' => 'users.edit', 'display_name' => 'Edit Users', 'description' => 'Edit admin user details'],
            ['group' => 'users', 'name' => 'users.delete', 'display_name' => 'Delete Users', 'description' => 'Delete admin users'],
            ['group' => 'users', 'name' => 'users.toggle_status', 'display_name' => 'Toggle User Status', 'description' => 'Activate/deactivate users'],
            ['group' => 'users', 'name' => 'users.manage_permissions', 'display_name' => 'Manage User Permissions', 'description' => 'Assign custom permissions to users'],

            // Roles & Permissions
            ['group' => 'roles', 'name' => 'roles.view', 'display_name' => 'View Roles', 'description' => 'View roles list'],
            ['group' => 'roles', 'name' => 'roles.create', 'display_name' => 'Create Roles', 'description' => 'Create new roles'],
            ['group' => 'roles', 'name' => 'roles.edit', 'display_name' => 'Edit Roles', 'description' => 'Edit role details and permissions'],
            ['group' => 'roles', 'name' => 'roles.delete', 'display_name' => 'Delete Roles', 'description' => 'Delete roles'],
            ['group' => 'roles', 'name' => 'roles.assign_permissions', 'display_name' => 'Assign Permissions', 'description' => 'Assign permissions to roles'],

            // Customer Management
            ['group' => 'customers', 'name' => 'customers.view', 'display_name' => 'View Customers', 'description' => 'View customer list'],
            ['group' => 'customers', 'name' => 'customers.create', 'display_name' => 'Create Customers', 'description' => 'Create new customers'],
            ['group' => 'customers', 'name' => 'customers.edit', 'display_name' => 'Edit Customers', 'description' => 'Edit customer details'],
            ['group' => 'customers', 'name' => 'customers.delete', 'display_name' => 'Delete Customers', 'description' => 'Delete customers'],
            ['group' => 'customers', 'name' => 'customers.verify', 'display_name' => 'Verify Customers', 'description' => 'Verify customer accounts'],
            ['group' => 'customers', 'name' => 'customers.toggle_status', 'display_name' => 'Toggle Customer Status', 'description' => 'Activate/deactivate customers'],
            ['group' => 'customers', 'name' => 'customers.view_profile', 'display_name' => 'View Customer Profile', 'description' => 'View detailed customer profile'],
            ['group' => 'customers', 'name' => 'customers.edit_wallet', 'display_name' => 'Edit Customer Wallet', 'description' => 'Adjust customer wallet balance'],

            // Partner Management
            ['group' => 'partners', 'name' => 'partners.view', 'display_name' => 'View Partners', 'description' => 'View partner list'],
            ['group' => 'partners', 'name' => 'partners.create', 'display_name' => 'Create Partners', 'description' => 'Create new partners'],
            ['group' => 'partners', 'name' => 'partners.edit', 'display_name' => 'Edit Partners', 'description' => 'Edit partner details'],
            ['group' => 'partners', 'name' => 'partners.delete', 'display_name' => 'Delete Partners', 'description' => 'Delete partners'],
            ['group' => 'partners', 'name' => 'partners.verify', 'display_name' => 'Verify Partners', 'description' => 'Verify partner accounts'],
            ['group' => 'partners', 'name' => 'partners.toggle_status', 'display_name' => 'Toggle Partner Status', 'description' => 'Activate/deactivate partners'],
            ['group' => 'partners', 'name' => 'partners.view_profile', 'display_name' => 'View Partner Profile', 'description' => 'View detailed partner profile'],

            // Brand Management
            ['group' => 'brands', 'name' => 'brands.view', 'display_name' => 'View Brands', 'description' => 'View brand list'],
            ['group' => 'brands', 'name' => 'brands.create', 'display_name' => 'Create Brands', 'description' => 'Create new brands'],
            ['group' => 'brands', 'name' => 'brands.edit', 'display_name' => 'Edit Brands', 'description' => 'Edit brand details'],
            ['group' => 'brands', 'name' => 'brands.delete', 'display_name' => 'Delete Brands', 'description' => 'Delete brands'],
            ['group' => 'brands', 'name' => 'brands.toggle_status', 'display_name' => 'Toggle Brand Status', 'description' => 'Activate/deactivate brands'],

            // Transaction Management
            ['group' => 'transactions', 'name' => 'transactions.view', 'display_name' => 'View Transactions', 'description' => 'View transaction list'],
            ['group' => 'transactions', 'name' => 'transactions.view_details', 'display_name' => 'View Transaction Details', 'description' => 'View detailed transaction information'],
            ['group' => 'transactions', 'name' => 'transactions.refund', 'display_name' => 'Refund Transactions', 'description' => 'Process transaction refunds'],
            ['group' => 'transactions', 'name' => 'transactions.export', 'display_name' => 'Export Transactions', 'description' => 'Export transaction data'],

            // Withdrawal Management
            ['group' => 'withdrawals', 'name' => 'withdrawals.view', 'display_name' => 'View Withdrawals', 'description' => 'View withdrawal requests'],
            ['group' => 'withdrawals', 'name' => 'withdrawals.approve', 'display_name' => 'Approve Withdrawals', 'description' => 'Approve withdrawal requests'],
            ['group' => 'withdrawals', 'name' => 'withdrawals.reject', 'display_name' => 'Reject Withdrawals', 'description' => 'Reject withdrawal requests'],
            ['group' => 'withdrawals', 'name' => 'withdrawals.view_details', 'display_name' => 'View Withdrawal Details', 'description' => 'View detailed withdrawal information'],

            // Profit Sharing
            ['group' => 'profit_sharing', 'name' => 'profit_sharing.view', 'display_name' => 'View Profit Sharing', 'description' => 'View profit sharing page'],
            ['group' => 'profit_sharing', 'name' => 'profit_sharing.calculate', 'display_name' => 'Calculate Profit', 'description' => 'Calculate profit distribution'],
            ['group' => 'profit_sharing', 'name' => 'profit_sharing.distribute', 'display_name' => 'Distribute Profit', 'description' => 'Distribute profit to users'],
            ['group' => 'profit_sharing', 'name' => 'profit_sharing.view_history', 'display_name' => 'View Distribution History', 'description' => 'View profit distribution history'],

            // Content Management
            ['group' => 'content', 'name' => 'content.view', 'display_name' => 'View Content', 'description' => 'View slides and promotions'],
            ['group' => 'content', 'name' => 'content.create', 'display_name' => 'Create Content', 'description' => 'Create slides and promotions'],
            ['group' => 'content', 'name' => 'content.edit', 'display_name' => 'Edit Content', 'description' => 'Edit slides and promotions'],
            ['group' => 'content', 'name' => 'content.delete', 'display_name' => 'Delete Content', 'description' => 'Delete slides and promotions'],
            ['group' => 'content', 'name' => 'content.toggle_status', 'display_name' => 'Toggle Content Status', 'description' => 'Activate/deactivate content'],

            // Category Management
            ['group' => 'categories', 'name' => 'categories.view', 'display_name' => 'View Categories', 'description' => 'View category list'],
            ['group' => 'categories', 'name' => 'categories.create', 'display_name' => 'Create Categories', 'description' => 'Create new categories'],
            ['group' => 'categories', 'name' => 'categories.edit', 'display_name' => 'Edit Categories', 'description' => 'Edit category details'],
            ['group' => 'categories', 'name' => 'categories.delete', 'display_name' => 'Delete Categories', 'description' => 'Delete categories'],
            ['group' => 'categories', 'name' => 'categories.toggle_status', 'display_name' => 'Toggle Category Status', 'description' => 'Activate/deactivate categories'],

            // Reports & Analytics
            ['group' => 'reports', 'name' => 'reports.view', 'display_name' => 'View Reports', 'description' => 'View all reports'],
            ['group' => 'reports', 'name' => 'reports.generate', 'display_name' => 'Generate Reports', 'description' => 'Generate custom reports'],
            ['group' => 'reports', 'name' => 'reports.export', 'display_name' => 'Export Reports', 'description' => 'Export reports to various formats'],
            ['group' => 'reports', 'name' => 'reports.view_analytics', 'display_name' => 'View Analytics', 'description' => 'View detailed analytics'],

            // System Settings
            ['group' => 'settings', 'name' => 'settings.view', 'display_name' => 'View Settings', 'description' => 'View system settings'],
            ['group' => 'settings', 'name' => 'settings.edit_general', 'display_name' => 'Edit General Settings', 'description' => 'Edit general system settings'],
            ['group' => 'settings', 'name' => 'settings.edit_withdrawals', 'display_name' => 'Edit Withdrawal Settings', 'description' => 'Configure withdrawal limits and rules'],
            ['group' => 'settings', 'name' => 'settings.edit_system', 'display_name' => 'Edit System Settings', 'description' => 'Edit critical system configurations'],

            // System Maintenance
            ['group' => 'maintenance', 'name' => 'maintenance.view', 'display_name' => 'View Maintenance', 'description' => 'View maintenance dashboard'],
            ['group' => 'maintenance', 'name' => 'maintenance.backup', 'display_name' => 'Create Backups', 'description' => 'Create system backups'],
            ['group' => 'maintenance', 'name' => 'maintenance.restore', 'display_name' => 'Restore Backups', 'description' => 'Restore from backups'],
            ['group' => 'maintenance', 'name' => 'maintenance.clear_cache', 'display_name' => 'Clear Cache', 'description' => 'Clear system cache'],
            ['group' => 'maintenance', 'name' => 'maintenance.view_logs', 'display_name' => 'View System Logs', 'description' => 'View system logs and errors'],
        ];
    }
}
