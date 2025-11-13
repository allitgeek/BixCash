<?php

namespace App\Services;

use App\Models\Permission;
use App\Models\PermissionGroup;
use App\Models\User;
use App\Models\ActivityLog;

class PermissionService
{
    /**
     * Get all permissions grouped by their permission groups
     */
    public function getAllGroupedPermissions()
    {
        return PermissionGroup::with(['permissions' => function ($query) {
            $query->where('is_active', true)->orderBy('display_name');
        }])
        ->active()
        ->ordered()
        ->get();
    }

    /**
     * Get all active permissions as a flat list
     */
    public function getAllPermissions()
    {
        return Permission::active()->orderBy('display_name')->get();
    }

    /**
     * Get permissions for a specific group
     */
    public function getPermissionsByGroup(int $groupId)
    {
        return Permission::where('permission_group_id', $groupId)
            ->active()
            ->orderBy('display_name')
            ->get();
    }

    /**
     * Create a new permission
     */
    public function createPermission(array $data)
    {
        $permission = Permission::create($data);

        ActivityLog::createLog(
            'created',
            'Permission',
            $permission->id,
            "Created permission: {$permission->display_name}",
            null,
            $permission->toArray()
        );

        return $permission;
    }

    /**
     * Update a permission
     */
    public function updatePermission(Permission $permission, array $data)
    {
        $oldValues = $permission->toArray();
        $permission->update($data);

        ActivityLog::createLog(
            'updated',
            'Permission',
            $permission->id,
            "Updated permission: {$permission->display_name}",
            $oldValues,
            $permission->fresh()->toArray()
        );

        return $permission;
    }

    /**
     * Delete a permission
     */
    public function deletePermission(Permission $permission)
    {
        ActivityLog::createLog(
            'deleted',
            'Permission',
            $permission->id,
            "Deleted permission: {$permission->display_name}",
            $permission->toArray(),
            null
        );

        return $permission->delete();
    }

    /**
     * Grant permission to a user
     */
    public function grantPermissionToUser(User $user, int $permissionId, ?string $notes = null, ?string $expiresAt = null)
    {
        $permission = Permission::findOrFail($permissionId);

        $user->grantPermission($permissionId, auth()->id(), $expiresAt, $notes);

        ActivityLog::createLog(
            'granted',
            'UserPermission',
            $user->id,
            "Granted permission '{$permission->display_name}' to user: {$user->name}",
            null,
            [
                'user_id' => $user->id,
                'permission_id' => $permissionId,
                'granted_by' => auth()->id(),
                'notes' => $notes,
                'expires_at' => $expiresAt,
            ]
        );

        return true;
    }

    /**
     * Revoke permission from a user
     */
    public function revokePermissionFromUser(User $user, int $permissionId)
    {
        $permission = Permission::findOrFail($permissionId);

        $user->revokePermission($permissionId);

        ActivityLog::createLog(
            'revoked',
            'UserPermission',
            $user->id,
            "Revoked permission '{$permission->display_name}' from user: {$user->name}",
            [
                'user_id' => $user->id,
                'permission_id' => $permissionId,
            ],
            null
        );

        return true;
    }

    /**
     * Sync user's custom permissions
     */
    public function syncUserPermissions(User $user, array $permissionIds)
    {
        $oldPermissions = $user->customPermissions()->pluck('permissions.id')->toArray();

        $user->syncCustomPermissions($permissionIds, auth()->id());

        $newPermissions = $user->customPermissions()->pluck('permissions.id')->toArray();

        ActivityLog::createLog(
            'synced',
            'UserPermission',
            $user->id,
            "Updated custom permissions for user: {$user->name}",
            ['permission_ids' => $oldPermissions],
            ['permission_ids' => $newPermissions]
        );

        return true;
    }

    /**
     * Check if a permission is in use by any users
     */
    public function isPermissionInUse(Permission $permission): bool
    {
        return $permission->users()->exists();
    }

    /**
     * Get all users with a specific permission
     */
    public function getUsersWithPermission(Permission $permission)
    {
        return $permission->users()->with('role')->get();
    }

    /**
     * Get permission statistics
     */
    public function getPermissionStats()
    {
        return [
            'total_permissions' => Permission::count(),
            'active_permissions' => Permission::active()->count(),
            'total_groups' => PermissionGroup::count(),
            'active_groups' => PermissionGroup::active()->count(),
            'users_with_custom_permissions' => User::whereHas('customPermissions')->count(),
        ];
    }

    /**
     * Search permissions
     */
    public function searchPermissions(string $query)
    {
        return Permission::where(function ($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
                ->orWhere('display_name', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%");
        })
        ->active()
        ->orderBy('display_name')
        ->get();
    }
}
