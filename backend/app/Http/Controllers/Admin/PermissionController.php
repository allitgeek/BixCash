<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\User;
use App\Services\PermissionService;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    protected $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    /**
     * Display a listing of permissions grouped by module
     */
    public function index(Request $request)
    {
        if ($request->has('search') && $request->search) {
            $permissions = $this->permissionService->searchPermissions($request->search);
            $permissionGroups = null;
        } else {
            $permissionGroups = $this->permissionService->getAllGroupedPermissions();
            $permissions = null;
        }

        $stats = $this->permissionService->getPermissionStats();

        return view('admin.permissions.index', compact('permissionGroups', 'permissions', 'stats'));
    }

    /**
     * Show users who have a specific permission
     */
    public function show(Permission $permission)
    {
        $users = $this->permissionService->getUsersWithPermission($permission);

        return view('admin.permissions.show', compact('permission', 'users'));
    }

    /**
     * Grant permission to a user (AJAX)
     */
    public function grantToUser(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'permission_id' => 'required|exists:permissions,id',
            'notes' => 'nullable|string|max:500',
            'expires_at' => 'nullable|date|after:now',
        ]);

        try {
            $user = User::findOrFail($validated['user_id']);

            $this->permissionService->grantPermissionToUser(
                $user,
                $validated['permission_id'],
                $validated['notes'] ?? null,
                $validated['expires_at'] ?? null
            );

            return response()->json([
                'success' => true,
                'message' => 'Permission granted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error granting permission: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Revoke permission from a user (AJAX)
     */
    public function revokeFromUser(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'permission_id' => 'required|exists:permissions,id',
        ]);

        try {
            $user = User::findOrFail($validated['user_id']);

            $this->permissionService->revokePermissionFromUser(
                $user,
                $validated['permission_id']
            );

            return response()->json([
                'success' => true,
                'message' => 'Permission revoked successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error revoking permission: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's custom permissions (AJAX)
     */
    public function getUserPermissions(User $user)
    {
        $customPermissions = $user->customPermissions()
            ->with('permissionGroup')
            ->get();

        $rolePermissions = $user->role ? $user->role->permissions : [];

        return response()->json([
            'custom_permissions' => $customPermissions,
            'role_permissions' => $rolePermissions,
            'all_permissions' => $user->getAllPermissions(),
        ]);
    }

    /**
     * Sync user's custom permissions (AJAX)
     */
    public function syncUserPermissions(Request $request, User $user)
    {
        $validated = $request->validate([
            'permission_ids' => 'required|array',
            'permission_ids.*' => 'exists:permissions,id',
        ]);

        try {
            $this->permissionService->syncUserPermissions($user, $validated['permission_ids']);

            return response()->json([
                'success' => true,
                'message' => 'User permissions updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating permissions: ' . $e->getMessage()
            ], 500);
        }
    }
}
