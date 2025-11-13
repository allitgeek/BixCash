<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;

class RoleService
{
    /**
     * Get all roles with user counts
     */
    public function getAllRoles()
    {
        return Role::withCount('users')->orderBy('name')->get();
    }

    /**
     * Get only active roles
     */
    public function getActiveRoles()
    {
        return Role::active()->orderBy('display_name')->get();
    }

    /**
     * Get system roles only
     */
    public function getSystemRoles()
    {
        return Role::system()->withCount('users')->orderBy('name')->get();
    }

    /**
     * Get non-system roles only (editable roles)
     */
    public function getEditableRoles()
    {
        return Role::nonSystem()->withCount('users')->orderBy('name')->get();
    }

    /**
     * Get roles suitable for admin users
     */
    public function getAdminRoles()
    {
        return Role::whereIn('name', [
            'super_admin', 'admin', 'moderator',
            'manager', 'assistant_manager', 'partner_support',
            'customer_support', 'finance_admin'
        ])->active()->orderBy('display_name')->get();
    }

    /**
     * Create a new role
     */
    public function createRole(array $data)
    {
        $role = Role::create([
            'name' => $data['name'],
            'display_name' => $data['display_name'],
            'description' => $data['description'] ?? null,
            'permissions' => $data['permissions'] ?? [],
            'is_active' => $data['is_active'] ?? true,
            'is_system' => false, // New roles are always non-system
        ]);

        ActivityLog::createLog(
            'created',
            'Role',
            $role->id,
            "Created role: {$role->display_name}",
            null,
            $role->toArray()
        );

        return $role;
    }

    /**
     * Update a role
     */
    public function updateRole(Role $role, array $data)
    {
        if ($role->is_system && isset($data['is_system'])) {
            throw new \Exception('Cannot modify system status of system roles');
        }

        $oldValues = $role->toArray();

        $role->update([
            'display_name' => $data['display_name'] ?? $role->display_name,
            'description' => $data['description'] ?? $role->description,
            'permissions' => $data['permissions'] ?? $role->permissions,
            'is_active' => $data['is_active'] ?? $role->is_active,
        ]);

        ActivityLog::createLog(
            'updated',
            'Role',
            $role->id,
            "Updated role: {$role->display_name}",
            $oldValues,
            $role->fresh()->toArray()
        );

        return $role;
    }

    /**
     * Delete a role
     */
    public function deleteRole(Role $role)
    {
        if ($role->is_system) {
            throw new \Exception('Cannot delete system roles');
        }

        if ($role->users()->exists()) {
            throw new \Exception('Cannot delete role with assigned users. Please reassign users first.');
        }

        ActivityLog::createLog(
            'deleted',
            'Role',
            $role->id,
            "Deleted role: {$role->display_name}",
            $role->toArray(),
            null
        );

        return $role->delete();
    }

    /**
     * Toggle role status
     */
    public function toggleRoleStatus(Role $role)
    {
        $oldStatus = $role->is_active;
        $role->is_active = !$role->is_active;
        $role->save();

        ActivityLog::createLog(
            'status_changed',
            'Role',
            $role->id,
            "Changed role status for '{$role->display_name}': " . ($role->is_active ? 'Activated' : 'Deactivated'),
            ['is_active' => $oldStatus],
            ['is_active' => $role->is_active]
        );

        return $role;
    }

    /**
     * Assign role to a user
     */
    public function assignRoleToUser(User $user, int $roleId)
    {
        $oldRole = $user->role;
        $newRole = Role::findOrFail($roleId);

        $user->role_id = $roleId;
        $user->save();

        ActivityLog::createLog(
            'role_assigned',
            'User',
            $user->id,
            "Changed role for '{$user->name}' from '{$oldRole?->display_name}' to '{$newRole->display_name}'",
            ['role_id' => $oldRole?->id, 'role_name' => $oldRole?->name],
            ['role_id' => $newRole->id, 'role_name' => $newRole->name]
        );

        return $user;
    }

    /**
     * Add permission to role
     */
    public function addPermissionToRole(Role $role, string $permission)
    {
        if ($role->is_system) {
            throw new \Exception('Cannot modify permissions of system roles');
        }

        $permissions = $role->permissions ?? [];

        if (!in_array($permission, $permissions)) {
            $permissions[] = $permission;
            $role->permissions = $permissions;
            $role->save();

            ActivityLog::createLog(
                'permission_added',
                'Role',
                $role->id,
                "Added permission '{$permission}' to role: {$role->display_name}",
                ['permissions' => $role->getOriginal('permissions')],
                ['permissions' => $permissions]
            );
        }

        return $role;
    }

    /**
     * Remove permission from role
     */
    public function removePermissionFromRole(Role $role, string $permission)
    {
        if ($role->is_system) {
            throw new \Exception('Cannot modify permissions of system roles');
        }

        $permissions = $role->permissions ?? [];
        $oldPermissions = $permissions;

        if (($key = array_search($permission, $permissions)) !== false) {
            unset($permissions[$key]);
            $role->permissions = array_values($permissions);
            $role->save();

            ActivityLog::createLog(
                'permission_removed',
                'Role',
                $role->id,
                "Removed permission '{$permission}' from role: {$role->display_name}",
                ['permissions' => $oldPermissions],
                ['permissions' => $role->permissions]
            );
        }

        return $role;
    }

    /**
     * Sync all permissions for a role
     */
    public function syncRolePermissions(Role $role, array $permissions)
    {
        $oldPermissions = $role->permissions ?? [];

        $role->permissions = $permissions;
        $role->save();

        ActivityLog::createLog(
            'permissions_synced',
            'Role',
            $role->id,
            "Updated permissions for role: {$role->display_name}",
            ['permissions' => $oldPermissions],
            ['permissions' => $permissions]
        );

        return $role;
    }

    /**
     * Get users assigned to a role
     */
    public function getUsersByRole(Role $role)
    {
        return $role->users()->with(['adminProfile', 'customerProfile', 'partnerProfile'])->get();
    }

    /**
     * Get role statistics
     */
    public function getRoleStats()
    {
        return [
            'total_roles' => Role::count(),
            'active_roles' => Role::active()->count(),
            'system_roles' => Role::system()->count(),
            'editable_roles' => Role::nonSystem()->count(),
            'total_users' => User::count(),
            'users_by_role' => Role::withCount('users')->get()->pluck('users_count', 'name'),
        ];
    }

    /**
     * Duplicate a role (useful for creating similar roles)
     */
    public function duplicateRole(Role $sourceRole, string $newName, string $newDisplayName)
    {
        $newRole = Role::create([
            'name' => $newName,
            'display_name' => $newDisplayName,
            'description' => $sourceRole->description . ' (Copy)',
            'permissions' => $sourceRole->permissions,
            'is_active' => true,
            'is_system' => false,
        ]);

        ActivityLog::createLog(
            'duplicated',
            'Role',
            $newRole->id,
            "Duplicated role '{$sourceRole->display_name}' to create '{$newRole->display_name}'",
            ['source_role_id' => $sourceRole->id],
            $newRole->toArray()
        );

        return $newRole;
    }

    /**
     * Check if role name is available
     */
    public function isRoleNameAvailable(string $name, ?int $excludeId = null): bool
    {
        $query = Role::where('name', $name);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return !$query->exists();
    }
}
