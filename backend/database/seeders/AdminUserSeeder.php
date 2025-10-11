<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\AdminProfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get roles
        $superAdminRole = Role::where('name', 'super_admin')->first();
        $adminRole = Role::where('name', 'admin')->first();

        // Create Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@bixcash.com'],
            [
                'name' => 'BixCash Super Admin',
                'password' => Hash::make('admin123'),
                'role_id' => $superAdminRole->id,
                'is_active' => true,
                'email_verified_at' => now()
            ]
        );

        // Create Admin Profile for Super Admin
        AdminProfile::firstOrCreate(
            ['user_id' => $superAdmin->id],
            [
                'admin_level' => 'super_admin',
                'department' => 'System Administration',
                'bio' => 'System administrator with full access to BixCash platform',
                'is_active' => true
            ]
        );

        // Create Regular Admin
        $admin = User::firstOrCreate(
            ['email' => 'manager@bixcash.com'],
            [
                'name' => 'BixCash Manager',
                'password' => Hash::make('manager123'),
                'role_id' => $adminRole->id,
                'is_active' => true,
                'email_verified_at' => now()
            ]
        );

        // Create Admin Profile for Regular Admin
        AdminProfile::firstOrCreate(
            ['user_id' => $admin->id],
            [
                'admin_level' => 'admin',
                'department' => 'Content Management',
                'bio' => 'Content and brand manager for BixCash platform',
                'is_active' => true
            ]
        );

        $this->command->info('Admin users created successfully:');
        $this->command->info('Super Admin: admin@bixcash.com / admin123');
        $this->command->info('Manager: manager@bixcash.com / manager123');
    }
}
