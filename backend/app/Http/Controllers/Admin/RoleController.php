<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Services\RoleService;
use App\Services\PermissionService;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $roleService;
    protected $permissionService;

    public function __construct(RoleService $roleService, PermissionService $permissionService)
    {
        $this->roleService = $roleService;
        $this->permissionService = $permissionService;
    }

    /**
     * Display a listing of roles
     */
    public function index(Request $request)
    {
        $query = Role::withCount('users');

        // Filter by type (system/custom)
        if ($request->filled('type')) {
            if ($request->type === 'system') {
                $query->where('is_system', true);
            } elseif ($request->type === 'custom') {
                $query->where('is_system', false);
            }
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('display_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $roles = $query->orderBy('is_system', 'desc')->orderBy('name')->paginate(15);
        $stats = $this->roleService->getRoleStats();

        return view('admin.roles.index', compact('roles', 'stats'));
    }

    /**
     * AJAX search for roles (returns JSON)
     */
    public function search(Request $request)
    {
        $query = Role::withCount('users');

        // Filter by type (system/custom)
        if ($request->filled('type')) {
            if ($request->type === 'system') {
                $query->where('is_system', true);
            } elseif ($request->type === 'custom') {
                $query->where('is_system', false);
            }
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('display_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $roles = $query->orderBy('is_system', 'desc')->orderBy('name')->paginate(15);
        $stats = $this->roleService->getRoleStats();

        // Generate table rows HTML
        $tableHtml = view('admin.roles.partials.table-rows', compact('roles'))->render();

        // Generate pagination HTML
        $paginationHtml = $roles->hasPages()
            ? $roles->appends($request->query())->links()->render()
            : '';

        return response()->json([
            'success' => true,
            'tableHtml' => $tableHtml,
            'paginationHtml' => $paginationHtml,
            'stats' => $stats,
            'total' => $roles->total(),
            'hasResults' => $roles->count() > 0
        ]);
    }

    /**
     * Show the form for creating a new role
     */
    public function create()
    {
        $permissionGroups = $this->permissionService->getAllGroupedPermissions();
        return view('admin.roles.create', compact('permissionGroups'));
    }

    /**
     * Store a newly created role
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name|max:50|regex:/^[a-z0-9_]+$/',
            'display_name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
            'is_active' => 'boolean',
        ], [
            'name.regex' => 'Role name must contain only lowercase letters, numbers, and underscores.',
        ]);

        try {
            $role = $this->roleService->createRole($validated);

            return redirect()->route('admin.roles.index')
                ->with('success', "Role '{$role->display_name}' created successfully!");
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error creating role: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified role
     */
    public function show(Role $role)
    {
        $role->loadCount('users');
        $users = $this->roleService->getUsersByRole($role);
        $permissionGroups = $this->permissionService->getAllGroupedPermissions();

        return view('admin.roles.show', compact('role', 'users', 'permissionGroups'));
    }

    /**
     * Show the form for editing the specified role
     */
    public function edit(Role $role)
    {
        if ($role->is_system) {
            return redirect()->route('admin.roles.show', $role)
                ->with('warning', 'System roles cannot be edited. You can only view their details.');
        }

        $permissionGroups = $this->permissionService->getAllGroupedPermissions();
        return view('admin.roles.edit', compact('role', 'permissionGroups'));
    }

    /**
     * Update the specified role
     */
    public function update(Request $request, Role $role)
    {
        if ($role->is_system) {
            return back()->with('error', 'System roles cannot be modified.');
        }

        $validated = $request->validate([
            'display_name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
            'is_active' => 'boolean',
        ]);

        try {
            $this->roleService->updateRole($role, $validated);

            return redirect()->route('admin.roles.index')
                ->with('success', "Role '{$role->display_name}' updated successfully!");
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error updating role: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified role
     */
    public function destroy(Role $role)
    {
        try {
            $this->roleService->deleteRole($role);

            return redirect()->route('admin.roles.index')
                ->with('success', "Role '{$role->display_name}' deleted successfully!");
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting role: ' . $e->getMessage());
        }
    }

    /**
     * Toggle role status
     */
    public function toggleStatus(Role $role)
    {
        try {
            $this->roleService->toggleRoleStatus($role);

            $status = $role->is_active ? 'activated' : 'deactivated';
            return back()->with('success', "Role '{$role->display_name}' {$status} successfully!");
        } catch (\Exception $e) {
            return back()->with('error', 'Error toggling role status: ' . $e->getMessage());
        }
    }

    /**
     * Duplicate a role
     */
    public function duplicate(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name|max:50|regex:/^[a-z0-9_]+$/',
            'display_name' => 'required|string|max:100',
        ]);

        try {
            $newRole = $this->roleService->duplicateRole($role, $validated['name'], $validated['display_name']);

            return redirect()->route('admin.roles.edit', $newRole)
                ->with('success', "Role duplicated successfully! You can now customize '{$newRole->display_name}'.");
        } catch (\Exception $e) {
            return back()->with('error', 'Error duplicating role: ' . $e->getMessage());
        }
    }
}
