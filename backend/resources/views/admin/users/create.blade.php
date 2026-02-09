@extends('layouts.admin')

@section('title', 'Create User - BixCash Admin')
@section('page-title', 'Create User')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users.index') }}" class="p-2 text-gray-500 hover:text-[#021c47] hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-[#021c47]">Create New User</h1>
                <p class="text-gray-500 mt-1">Add a new admin user to the system</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-4">
                        <h2 class="font-semibold text-[#021c47] border-b border-gray-200 pb-2">User Information</h2>
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 @error('name') border-red-300 @enderror">
                            @error('name')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 @error('email') border-red-300 @enderror">
                            @error('email')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                                <input type="password" id="password" name="password" required minlength="6"
                                       class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 @error('password') border-red-300 @enderror">
                                <p class="mt-1 text-xs text-gray-500">Minimum 6 characters</p>
                                @error('password')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password <span class="text-red-500">*</span></label>
                                <input type="password" id="password_confirmation" name="password_confirmation" required minlength="6"
                                       class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20">
                            </div>
                        </div>
                        <div>
                            <label for="role_id" class="block text-sm font-medium text-gray-700 mb-1">Role <span class="text-red-500">*</span></label>
                            <select id="role_id" name="role_id" required
                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 @error('role_id') border-red-300 @enderror">
                                <option value="">Select Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->display_name }}</option>
                                @endforeach
                            </select>
                            @error('role_id')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                        </div>
                        <div class="pt-2">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                       class="w-5 h-5 rounded border-gray-300 text-[#93db4d] focus:ring-[#93db4d]">
                                <span class="text-sm font-medium text-gray-700">Active (user can login)</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                        <h3 class="font-semibold text-[#021c47] mb-3">Role Permissions</h3>
                        <div id="rolePermissions" class="text-sm text-gray-500">Select a role to view permissions</div>
                    </div>
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <h3 class="font-semibold text-blue-900 mb-2">Note</h3>
                        <p class="text-blue-700 text-sm">Only admin users can be created here. Customers and Partners have separate registration processes.</p>
                    </div>
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 space-y-2">
                        <button type="submit" class="w-full px-4 py-2.5 bg-[#021c47] text-white rounded-lg hover:bg-[#93db4d] hover:text-[#021c47] transition-colors font-medium">Create User</button>
                        <a href="{{ route('admin.users.index') }}" class="w-full flex items-center justify-center px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">Cancel</a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('role_id').addEventListener('change', function() {
            const perms = { 'Super Admin': 'Full system access', 'Admin': 'Manage content and users', 'Moderator': 'View with limited editing' };
            const text = perms[this.options[this.selectedIndex].text] || 'Select a role to view permissions';
            document.getElementById('rolePermissions').textContent = text;
        });
    </script>
@endsection
