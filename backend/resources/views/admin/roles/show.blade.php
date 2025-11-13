@extends('layouts.admin')

@section('title', 'Role Details - ' . $role->display_name)

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $role->display_name }}</h1>
                <p class="mt-2 text-sm text-gray-600">Role details and assigned permissions</p>
            </div>
            <div class="flex gap-2">
                @if($role->name !== 'super_admin' && auth()->user()->hasPermission('roles.edit'))
                <a href="{{ route('admin.roles.edit', $role) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Role
                </a>
                @endif
                <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Roles
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Role Info (Left Column) -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Basic Info Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Role Information</h2>

                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Display Name</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $role->display_name }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Internal Name</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-mono bg-gray-50 px-2 py-1 rounded">{{ $role->name }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $role->description ?? 'No description provided' }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Type</dt>
                        <dd class="mt-1">
                            @if($role->is_system)
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800">System Role</span>
                            @else
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">Custom Role</span>
                            @endif
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            @if($role->is_active)
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span>
                            @else
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Inactive</span>
                            @endif
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Total Permissions</dt>
                        <dd class="mt-1 text-2xl font-bold text-blue-600">{{ count($role->permissions ?? []) }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Assigned Users</dt>
                        <dd class="mt-1 text-2xl font-bold text-green-600">{{ $role->users_count ?? 0 }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Created</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $role->created_at->format('M d, Y h:i A') }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $role->updated_at->format('M d, Y h:i A') }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Actions Card -->
            @if($role->name !== 'super_admin')
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Actions</h2>

                <div class="space-y-2">
                    @if(auth()->user()->hasPermission('roles.edit'))
                    <form action="{{ route('admin.roles.toggle-status', $role) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full px-4 py-2 {{ $role->is_active ? 'bg-red-100 hover:bg-red-200 text-red-700' : 'bg-green-100 hover:bg-green-200 text-green-700' }} font-medium rounded-lg transition">
                            {{ $role->is_active ? 'Deactivate Role' : 'Activate Role' }}
                        </button>
                    </form>
                    @endif

                    @if(auth()->user()->hasPermission('roles.delete') && $role->users_count == 0)
                    <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this role? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition">
                            Delete Role
                        </button>
                    </form>
                    @endif
                </div>

                @if($role->users_count > 0)
                <p class="mt-3 text-xs text-gray-500">Role cannot be deleted while users are assigned to it.</p>
                @endif
            </div>
            @endif
        </div>

        <!-- Permissions & Users (Right Column) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Assigned Permissions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">Assigned Permissions</h2>

                @if(count($role->permissions ?? []) > 0)
                    @foreach($permissionGroups as $group)
                        @php
                            $groupPermissions = $group->permissions->filter(function($permission) use ($role) {
                                return in_array($permission->name, $role->permissions ?? []);
                            });
                        @endphp

                        @if($groupPermissions->count() > 0)
                        <div class="mb-6 last:mb-0">
                            <div class="flex items-center mb-3">
                                <span class="text-2xl mr-2">{{ $group->icon }}</span>
                                <h3 class="text-sm font-semibold text-gray-900">{{ $group->display_name }}</h3>
                                <span class="ml-2 px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">{{ $groupPermissions->count() }}</span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                @foreach($groupPermissions as $permission)
                                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                    <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <div>
                                        <span class="text-sm font-medium text-gray-900">{{ $permission->display_name }}</span>
                                        <p class="text-xs text-gray-500">{{ $permission->description }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    @endforeach
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">No permissions assigned to this role</p>
                    </div>
                @endif
            </div>

            <!-- Users with this Role -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">Users with this Role</h2>

                @if($users->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Last Login</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($users as $user)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($user->is_active)
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">No users assigned to this role</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        alert('{{ session('success') }}');
    });
</script>
@endif

@if(session('warning'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        alert('{{ session('warning') }}');
    });
</script>
@endif
@endsection
