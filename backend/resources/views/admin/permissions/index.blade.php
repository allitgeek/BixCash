@extends('layouts.admin')

@section('title', 'Permissions Overview')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#021c47]">Permissions Overview</h1>
            <p class="text-gray-500 mt-1">View all available permissions in the system</p>
        </div>
        <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center px-4 py-2 bg-[#021c47] text-white rounded-lg hover:bg-[#93db4d] hover:text-[#021c47] transition-colors font-medium">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            Manage Roles
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-[#021c47]/10 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-[#021c47]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                </div>
                <div><p class="text-sm text-gray-500">Total</p><p class="text-xl font-bold text-[#021c47]">{{ $stats['total_permissions'] ?? 0 }}</p></div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-[#93db4d]/20 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-[#93db4d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div><p class="text-sm text-gray-500">Active</p><p class="text-xl font-bold text-[#93db4d]">{{ $stats['active_permissions'] ?? 0 }}</p></div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <div><p class="text-sm text-gray-500">Groups</p><p class="text-xl font-bold text-purple-600">{{ $stats['total_groups'] ?? 0 }}</p></div>
            </div>
        </div>
    </div>

    <!-- Search -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
        <form method="GET" action="{{ route('admin.permissions.index') }}" class="flex gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search permissions..."
                   class="flex-1 px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20">
            <button type="submit" class="px-4 py-2 bg-[#021c47] text-white rounded-lg hover:bg-[#93db4d] hover:text-[#021c47] transition-colors font-medium">Search</button>
            @if(request('search'))<a href="{{ route('admin.permissions.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">Clear</a>@endif
        </form>
    </div>

    <!-- Permissions by Group -->
    @if(request('search'))
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <h2 class="font-semibold text-[#021c47] mb-4">Search Results</h2>
            @if($permissions && $permissions->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($permissions as $permission)
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-[#93db4d] transition">
                        <div class="flex items-start justify-between mb-2">
                            <h3 class="text-sm font-semibold text-gray-900">{{ $permission->display_name }}</h3>
                            @if($permission->is_active)<svg class="w-5 h-5 text-[#93db4d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>@endif
                        </div>
                        <p class="text-xs text-gray-500 mb-2">{{ $permission->description }}</p>
                        <code class="text-xs text-[#021c47] bg-[#021c47]/5 px-2 py-1 rounded">{{ $permission->name }}</code>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">No permissions found</div>
            @endif
        </div>
    @else
        @foreach($permissionGroups as $group)
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <div class="flex items-center">
                    <span class="text-2xl mr-3">{{ $group->icon }}</span>
                    <div>
                        <h2 class="text-lg font-semibold text-[#021c47]">{{ $group->display_name }}</h2>
                        <p class="text-sm text-gray-600">{{ $group->description }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-[#021c47]">{{ $group->permissions->count() }}</p>
                    <p class="text-xs text-gray-500">permissions</p>
                </div>
            </div>
            <div class="p-6">
                @if($group->permissions->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($group->permissions as $permission)
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-[#93db4d] hover:shadow-md transition">
                        <div class="flex items-start justify-between mb-2">
                            <h3 class="text-sm font-semibold text-gray-900">{{ $permission->display_name }}</h3>
                            @if($permission->is_active)<svg class="w-5 h-5 text-[#93db4d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>@endif
                        </div>
                        <p class="text-xs text-gray-500 mb-2">{{ $permission->description }}</p>
                        <code class="text-xs text-[#021c47] bg-[#021c47]/5 px-2 py-1 rounded">{{ $permission->name }}</code>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8 text-gray-500">No permissions in this group</div>
                @endif
            </div>
        </div>
        @endforeach
    @endif

    <!-- Info Box -->
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
        <div class="flex">
            <svg class="h-6 w-6 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-900">About Permissions</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <p>Permissions control what actions users can perform. They are organized into groups for easier management.</p>
                    <ul class="list-disc list-inside mt-2 space-y-1">
                        <li>Permissions are assigned to roles, not individual users</li>
                        <li>Users inherit all permissions from their assigned role</li>
                        <li>System permissions are predefined and cannot be modified</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
