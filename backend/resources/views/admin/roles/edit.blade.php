@extends('layouts.admin')

@section('title', 'Edit Role - ' . $role->display_name)

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.roles.index') }}" class="p-2 text-gray-500 hover:text-[#021c47] hover:bg-gray-100 rounded-lg transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-[#021c47]">Edit Role: {{ $role->display_name }}</h1>
            <p class="text-gray-500 mt-1">Modify role details and permissions</p>
        </div>
    </div>

    <form action="{{ route('admin.roles.update', $role) }}" method="POST">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left: Role Details -->
            <div class="space-y-6">
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 sticky top-4">
                    <h2 class="font-semibold text-[#021c47] mb-4">Role Details</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Role Name (Slug)</label>
                            <input type="text" value="{{ $role->name }}" readonly class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed">
                            <p class="mt-1 text-xs text-gray-500">Cannot be changed</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Display Name <span class="text-red-500">*</span></label>
                            <input type="text" name="display_name" value="{{ old('display_name', $role->display_name) }}" required
                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 @error('display_name') border-red-300 @enderror">
                            @error('display_name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20">{{ old('description', $role->description) }}</textarea>
                        </div>
                        <div>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $role->is_active) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-[#93db4d] focus:ring-[#93db4d]">
                                <span class="text-sm text-gray-700">Active</span>
                            </label>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 mt-4 pt-4">
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Summary</h3>
                        <div class="bg-gray-50 rounded-lg p-3 text-center">
                            <p class="text-2xl font-bold text-[#021c47]" id="selected-count">{{ count($role->permissions ?? []) }}</p>
                            <p class="text-xs text-gray-500">permissions</p>
                        </div>
                        <dl class="mt-3 space-y-1 text-xs">
                            <div class="flex justify-between"><dt class="text-gray-500">Type</dt><dd>@if($role->is_system)<span class="px-2 py-0.5 bg-purple-100 text-purple-700 rounded">System</span>@else<span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded">Custom</span>@endif</dd></div>
                            <div class="flex justify-between"><dt class="text-gray-500">Users</dt><dd class="font-medium">{{ $role->users_count ?? 0 }}</dd></div>
                        </dl>
                    </div>

                    <div class="mt-4 space-y-2">
                        <button type="submit" class="w-full px-4 py-2.5 bg-[#021c47] text-white rounded-lg hover:bg-[#93db4d] hover:text-[#021c47] transition-colors font-medium">Update Role</button>
                        <a href="{{ route('admin.roles.index') }}" class="block w-full px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium text-center">Cancel</a>
                    </div>
                </div>
            </div>

            <!-- Right: Permissions -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="font-semibold text-[#021c47]">Manage Permissions</h2>
                        <div class="flex gap-2">
                            <button type="button" onclick="selectAllPermissions()" class="px-3 py-1 text-sm bg-[#93db4d]/20 hover:bg-[#93db4d]/30 text-[#5a9a2e] rounded-lg transition">Select All</button>
                            <button type="button" onclick="deselectAllPermissions()" class="px-3 py-1 text-sm bg-red-100 hover:bg-red-200 text-red-700 rounded-lg transition">Deselect All</button>
                        </div>
                    </div>

                    @foreach($permissionGroups as $group)
                    <div class="mb-4 border border-gray-200 rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex items-center justify-between">
                            <div class="flex items-center">
                                <span class="text-xl mr-2">{{ $group->icon }}</span>
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900">{{ $group->display_name }}</h3>
                                    <p class="text-xs text-gray-500">{{ $group->description }}</p>
                                </div>
                            </div>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" class="group-checkbox w-4 h-4 rounded border-gray-300 text-[#93db4d] focus:ring-[#93db4d]" data-group="{{ $group->id }}" onchange="toggleGroup(this, {{ $group->id }})">
                                <span class="ml-2 text-xs text-gray-600">All</span>
                            </label>
                        </div>
                        <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-2">
                            @foreach($group->permissions as $permission)
                            <label class="flex items-start p-2 rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                       class="permission-checkbox mt-1 w-4 h-4 rounded border-gray-300 text-[#93db4d] focus:ring-[#93db4d]"
                                       data-group="{{ $group->id }}"
                                       {{ in_array($permission->name, $role->permissions ?? []) ? 'checked' : '' }}
                                       onchange="updateCount()">
                                <div class="ml-2">
                                    <span class="text-sm font-medium text-gray-900">{{ $permission->display_name }}</span>
                                    <p class="text-xs text-gray-500">{{ $permission->description }}</p>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function updateCount() { document.getElementById('selected-count').textContent = document.querySelectorAll('.permission-checkbox:checked').length; updateGroupCheckboxes(); }
function toggleGroup(checkbox, groupId) { document.querySelectorAll(`.permission-checkbox[data-group="${groupId}"]`).forEach(cb => cb.checked = checkbox.checked); updateCount(); }
function selectAllPermissions() { document.querySelectorAll('.permission-checkbox, .group-checkbox').forEach(cb => cb.checked = true); updateCount(); }
function deselectAllPermissions() { document.querySelectorAll('.permission-checkbox, .group-checkbox').forEach(cb => cb.checked = false); updateCount(); }
function updateGroupCheckboxes() {
    document.querySelectorAll('.group-checkbox').forEach(g => {
        const perms = document.querySelectorAll(`.permission-checkbox[data-group="${g.dataset.group}"]`);
        const checked = Array.from(perms).filter(cb => cb.checked).length;
        g.checked = checked === perms.length && perms.length > 0;
    });
}
document.addEventListener('DOMContentLoaded', function() { updateCount(); document.querySelectorAll('.permission-checkbox').forEach(cb => cb.addEventListener('change', updateGroupCheckboxes)); });
</script>
@endsection
