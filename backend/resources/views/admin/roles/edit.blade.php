@extends('layouts.admin')

@section('title', 'Edit Role - ' . $role->display_name)

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Role: {{ $role->display_name }}</h1>
                <p class="mt-2 text-sm text-gray-600">Modify role details and permissions</p>
            </div>
            <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Roles
            </a>
        </div>
    </div>

    <form action="{{ route('admin.roles.update', $role) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Role Details (Left Column) -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6 sticky top-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Role Details</h2>

                    <!-- Role Name (Read-only) -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Role Name (Slug)</label>
                        <input type="text" value="{{ $role->name }}" readonly
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed">
                        <p class="mt-1 text-xs text-gray-500">Role name cannot be changed</p>
                    </div>

                    <!-- Display Name -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Display Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="display_name" value="{{ old('display_name', $role->display_name) }}" required
                               placeholder="e.g., Content Manager"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('display_name') border-red-500 @enderror">
                        @error('display_name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" rows="4"
                                  placeholder="Describe what this role can do..."
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description', $role->description) }}</textarea>
                        @error('description')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Active Status -->
                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $role->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Active</span>
                        </label>
                    </div>

                    <!-- Permission Summary -->
                    <div class="border-t pt-4">
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Permission Summary</h3>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-2xl font-bold text-blue-600" id="selected-count">{{ count($role->permissions ?? []) }}</p>
                            <p class="text-xs text-gray-500">permissions selected</p>
                        </div>
                    </div>

                    <!-- Role Info -->
                    <div class="mt-6 border-t pt-4">
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Role Info</h3>
                        <dl class="space-y-2">
                            <div class="flex justify-between text-xs">
                                <dt class="text-gray-500">Type:</dt>
                                <dd class="font-medium">
                                    @if($role->is_system)
                                    <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded">System</span>
                                    @else
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded">Custom</span>
                                    @endif
                                </dd>
                            </div>
                            <div class="flex justify-between text-xs">
                                <dt class="text-gray-500">Users:</dt>
                                <dd class="font-medium text-gray-900">{{ $role->users_count ?? 0 }}</dd>
                            </div>
                            <div class="flex justify-between text-xs">
                                <dt class="text-gray-500">Created:</dt>
                                <dd class="font-medium text-gray-900">{{ $role->created_at->format('M d, Y') }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-6 space-y-2">
                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                            Update Role
                        </button>
                        <a href="{{ route('admin.roles.index') }}" class="block w-full px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition text-center">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>

            <!-- Permissions (Right Column) -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-semibold text-gray-900">Manage Permissions</h2>
                        <div class="flex gap-2">
                            <button type="button" onclick="selectAllPermissions()" class="px-3 py-1 text-sm bg-green-100 hover:bg-green-200 text-green-700 rounded-lg transition">
                                Select All
                            </button>
                            <button type="button" onclick="deselectAllPermissions()" class="px-3 py-1 text-sm bg-red-100 hover:bg-red-200 text-red-700 rounded-lg transition">
                                Deselect All
                            </button>
                        </div>
                    </div>

                    @foreach($permissionGroups as $group)
                    <div class="mb-6 border border-gray-200 rounded-lg overflow-hidden">
                        <!-- Group Header -->
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <span class="text-2xl mr-2">{{ $group->icon }}</span>
                                    <div>
                                        <h3 class="text-sm font-semibold text-gray-900">{{ $group->display_name }}</h3>
                                        <p class="text-xs text-gray-500">{{ $group->description }}</p>
                                    </div>
                                </div>
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" class="group-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500" data-group="{{ $group->id }}" onchange="toggleGroup(this, {{ $group->id }})">
                                    <span class="ml-2 text-xs text-gray-600">Select All</span>
                                </label>
                            </div>
                        </div>

                        <!-- Permissions Grid -->
                        <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($group->permissions as $permission)
                            <label class="flex items-start p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition group-{{ $group->id }}-permission">
                                <input type="checkbox"
                                       name="permissions[]"
                                       value="{{ $permission->name }}"
                                       class="permission-checkbox mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                       data-group="{{ $group->id }}"
                                       {{ in_array($permission->name, $role->permissions ?? []) ? 'checked' : '' }}
                                       onchange="updateCount()">
                                <div class="ml-3">
                                    <span class="text-sm font-medium text-gray-900">{{ $permission->display_name }}</span>
                                    <p class="text-xs text-gray-500">{{ $permission->description }}</p>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach

                    @if($permissionGroups->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">No permissions available</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function updateCount() {
    const checked = document.querySelectorAll('.permission-checkbox:checked').length;
    document.getElementById('selected-count').textContent = checked;
}

function toggleGroup(checkbox, groupId) {
    const groupCheckboxes = document.querySelectorAll(`.permission-checkbox[data-group="${groupId}"]`);
    groupCheckboxes.forEach(cb => {
        cb.checked = checkbox.checked;
    });
    updateCount();
    updateGroupCheckboxes();
}

function selectAllPermissions() {
    document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = true);
    document.querySelectorAll('.group-checkbox').forEach(cb => cb.checked = true);
    updateCount();
}

function deselectAllPermissions() {
    document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = false);
    document.querySelectorAll('.group-checkbox').forEach(cb => cb.checked = false);
    updateCount();
}

function updateGroupCheckboxes() {
    document.querySelectorAll('.group-checkbox').forEach(groupCheckbox => {
        const groupId = groupCheckbox.dataset.group;
        const groupPermissions = document.querySelectorAll(`.permission-checkbox[data-group="${groupId}"]`);
        const checkedCount = Array.from(groupPermissions).filter(cb => cb.checked).length;
        groupCheckbox.checked = checkedCount === groupPermissions.length && groupPermissions.length > 0;
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateCount();
    updateGroupCheckboxes();

    // Update group checkboxes when individual permissions change
    document.querySelectorAll('.permission-checkbox').forEach(cb => {
        cb.addEventListener('change', updateGroupCheckboxes);
    });
});
</script>

@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        alert('Please check the form for errors.');
    });
</script>
@endif
@endsection
