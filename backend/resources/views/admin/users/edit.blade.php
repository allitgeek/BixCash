@extends('layouts.admin')

@section('title', 'Edit User - BixCash Admin')
@section('page-title', 'Edit User')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.users.index') }}" class="p-2 text-gray-500 hover:text-[#021c47] hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-[#021c47]">Edit User</h1>
                    <p class="text-gray-500 mt-1">{{ $user->name }}</p>
                </div>
            </div>
            <a href="{{ route('admin.users.show', $user) }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">View User</a>
        </div>

        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-4">
                        <h2 class="font-semibold text-[#021c47] border-b border-gray-200 pb-2">User Information</h2>
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 @error('name') border-red-300 @enderror">
                            @error('name')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 @error('email') border-red-300 @enderror">
                            @error('email')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                                <input type="password" id="password" name="password" minlength="6"
                                       class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 @error('password') border-red-300 @enderror">
                                <p class="mt-1 text-xs text-gray-500">Leave blank to keep current</p>
                                @error('password')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" minlength="6"
                                       class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20">
                            </div>
                        </div>
                        <div>
                            <label for="role_id" class="block text-sm font-medium text-gray-700 mb-1">Role <span class="text-red-500">*</span></label>
                            <select id="role_id" name="role_id" required
                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 @error('role_id') border-red-300 @enderror">
                                <option value="">Select Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>{{ $role->display_name }}</option>
                                @endforeach
                            </select>
                            @error('role_id')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                        </div>
                        <div class="pt-2">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                                       class="w-5 h-5 rounded border-gray-300 text-[#93db4d] focus:ring-[#93db4d]">
                                <span class="text-sm font-medium text-gray-700">Active (user can login)</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                        <h3 class="font-semibold text-[#021c47] mb-3">User Details</h3>
                        <dl class="space-y-2 text-sm">
                            <div class="flex justify-between"><dt class="text-gray-500">Created</dt><dd>{{ $user->created_at->format('M j, Y') }}</dd></div>
                            <div class="flex justify-between"><dt class="text-gray-500">Last Login</dt><dd>{{ $user->last_login_at ? $user->last_login_at->format('M j, Y') : 'Never' }}</dd></div>
                            <div class="flex justify-between"><dt class="text-gray-500">Current Role</dt><dd class="font-medium">{{ $user->role->display_name ?? 'None' }}</dd></div>
                        </dl>
                    </div>

                    @if($user->id === auth()->id())
                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                            <p class="text-amber-700 text-sm"><strong>Warning:</strong> You are editing your own account. Be careful with role/status changes.</p>
                        </div>
                    @endif

                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 space-y-2">
                        <button type="submit" class="w-full px-4 py-2.5 bg-[#021c47] text-white rounded-lg hover:bg-[#93db4d] hover:text-[#021c47] transition-colors font-medium">Update User</button>
                        <a href="{{ route('admin.users.index') }}" class="w-full flex items-center justify-center px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">Cancel</a>
                    </div>

                    @if($user->id !== auth()->id())
                        <div class="bg-white rounded-xl border border-red-200 shadow-sm p-4">
                            <h3 class="font-semibold text-red-700 mb-2">Danger Zone</h3>
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete this user?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-full px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors font-medium">Delete User</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </form>
    </div>
@endsection
