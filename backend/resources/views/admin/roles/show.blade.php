@extends('layouts.admin')

@section('title', 'Role Details - ' . $role->display_name)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.roles.index') }}" class="p-2 text-gray-500 hover:text-[#021c47] hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-[#021c47]">{{ $role->display_name }}</h1>
                <p class="text-gray-500 mt-1">Role details and permissions</p>
            </div>
        </div>
        @if($role->name !== 'super_admin' && auth()->user()->hasPermission('roles.edit'))
        <a href="{{ route('admin.roles.edit', $role) }}" class="inline-flex items-center px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition-colors font-medium">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit Role
        </a>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Sidebar -->
        <div class="space-y-6">
            <!-- Role Info Card -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h2 class="font-semibold text-[#021c47] mb-4">Role Information</h2>
                <dl class="space-y-3 text-sm">
                    <div><dt class="text-gray-500">Display Name</dt><dd class="font-medium">{{ $role->display_name }}</dd></div>
                    <div><dt class="text-gray-500">Internal Name</dt><dd class="font-mono bg-gray-50 px-2 py-1 rounded text-xs">{{ $role->name }}</dd></div>
                    <div><dt class="text-gray-500">Description</dt><dd>{{ $role->description ?? 'No description' }}</dd></div>
                    <div class="flex justify-between items-center">
                        <dt class="text-gray-500">Type</dt>
                        <dd>@if($role->is_system)<span class="px-2 py-0.5 bg-purple-100 text-purple-700 rounded-full text-xs">System</span>@else<span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full text-xs">Custom</span>@endif</dd>
                    </div>
                    <div class="flex justify-between items-center">
                        <dt class="text-gray-500">Status</dt>
                        <dd>@if($role->is_active)<span class="px-2 py-0.5 bg-[#93db4d]/20 text-[#5a9a2e] rounded-full text-xs">Active</span>@else<span class="px-2 py-0.5 bg-red-100 text-red-600 rounded-full text-xs">Inactive</span>@endif</dd>
                    </div>
                    <div class="flex justify-between"><dt class="text-gray-500">Permissions</dt><dd class="text-xl font-bold text-[#021c47]">{{ count($role->permissions ?? []) }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Users</dt><dd class="text-xl font-bold text-[#93db4d]">{{ $role->users_count ?? 0 }}</dd></div>
                    <div><dt class="text-gray-500">Created</dt><dd>{{ $role->created_at->format('M d, Y') }}</dd></div>
                </dl>
            </div>

            <!-- Actions -->
            @if($role->name !== 'super_admin')
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 space-y-2">
                <h2 class="font-semibold text-[#021c47] mb-2">Actions</h2>
                @if(auth()->user()->hasPermission('roles.edit'))
                <form action="{{ route('admin.roles.toggle-status', $role) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit" class="w-full px-4 py-2 {{ $role->is_active ? 'bg-yellow-100 hover:bg-yellow-200 text-yellow-700' : 'bg-[#93db4d]/20 hover:bg-[#93db4d]/30 text-[#5a9a2e]' }} font-medium rounded-lg transition">
                        {{ $role->is_active ? 'Deactivate Role' : 'Activate Role' }}
                    </button>
                </form>
                @endif
                @if(auth()->user()->hasPermission('roles.delete') && $role->users_count == 0)
                <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Delete this role?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg transition">Delete Role</button>
                </form>
                @endif
                @if($role->users_count > 0)<p class="text-xs text-gray-500">Can't delete while users are assigned.</p>@endif
            </div>
            @endif
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Permissions -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h2 class="font-semibold text-[#021c47] mb-4">Assigned Permissions</h2>
                @if(count($role->permissions ?? []) > 0)
                    @foreach($permissionGroups as $group)
                        @php $groupPermissions = $group->permissions->filter(fn($p) => in_array($p->name, $role->permissions ?? [])); @endphp
                        @if($groupPermissions->count() > 0)
                        <div class="mb-4 last:mb-0">
                            <div class="flex items-center mb-2">
                                <span class="text-xl mr-2">{{ $group->icon }}</span>
                                <h3 class="text-sm font-semibold text-gray-900">{{ $group->display_name }}</h3>
                                <span class="ml-2 px-2 py-0.5 text-xs bg-[#021c47]/10 text-[#021c47] rounded-full">{{ $groupPermissions->count() }}</span>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                @foreach($groupPermissions as $permission)
                                <div class="flex items-center p-2 bg-gray-50 rounded-lg text-sm">
                                    <svg class="w-4 h-4 text-[#93db4d] mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span class="text-gray-700">{{ $permission->display_name }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    @endforeach
                @else
                    <div class="text-center py-8 text-gray-500">No permissions assigned</div>
                @endif
            </div>

            <!-- Users -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="font-semibold text-[#021c47]">Users with this Role</h2>
                </div>
                @if($users->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead><tr class="bg-[#021c47] text-white"><th class="px-4 py-2 text-left">Name</th><th class="px-4 py-2 text-left">Email</th><th class="px-4 py-2 text-left">Status</th><th class="px-4 py-2 text-left">Last Login</th></tr></thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr class="border-b border-gray-100 hover:bg-[#93db4d]/5">
                                <td class="px-4 py-2 font-medium">{{ $user->name }}</td>
                                <td class="px-4 py-2 text-gray-500">{{ $user->email }}</td>
                                <td class="px-4 py-2">@if($user->is_active)<span class="px-2 py-0.5 bg-[#93db4d]/20 text-[#5a9a2e] rounded-full text-xs">Active</span>@else<span class="px-2 py-0.5 bg-red-100 text-red-600 rounded-full text-xs">Inactive</span>@endif</td>
                                <td class="px-4 py-2 text-gray-500">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-8 text-gray-500">No users assigned to this role</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
