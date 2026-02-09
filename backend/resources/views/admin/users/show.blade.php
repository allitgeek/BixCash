@extends('layouts.admin')

@section('title', 'View User - BixCash Admin')
@section('page-title', 'View User')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.users.index') }}" class="p-2 text-gray-500 hover:text-[#021c47] hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-[#021c47]">{{ $user->name }}</h1>
                    <p class="text-gray-500 mt-1">{{ $user->email }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                @if($user->id !== auth()->id())
                    <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition-colors font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit
                    </a>
                @else
                    <span class="px-3 py-2 bg-blue-100 text-blue-700 rounded-lg text-sm font-medium">Your Account</span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Profile Card -->
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="bg-gradient-to-r from-[#021c47] to-[#032a6b] p-8 text-center">
                        <div class="w-24 h-24 bg-white text-[#021c47] rounded-full flex items-center justify-center mx-auto text-3xl font-bold shadow-lg">
                            {{ strtoupper(substr($user->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $user->name)[1] ?? '', 0, 1)) }}
                        </div>
                        <h2 class="text-white text-xl font-bold mt-4">{{ $user->name }}</h2>
                        <p class="text-white/70">{{ $user->email }}</p>
                        <div class="flex items-center justify-center gap-2 mt-4">
                            <span class="px-3 py-1 bg-white/20 text-white rounded-full text-sm">{{ $user->role->display_name ?? 'No Role' }}</span>
                            @if($user->is_active)
                                <span class="px-3 py-1 bg-[#93db4d] text-[#021c47] rounded-full text-sm font-medium">Active</span>
                            @else
                                <span class="px-3 py-1 bg-red-500 text-white rounded-full text-sm">Inactive</span>
                            @endif
                            @if($user->id === auth()->id())
                                <span class="px-3 py-1 bg-blue-500 text-white rounded-full text-sm">You</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Details Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                        <h3 class="font-semibold text-[#021c47] mb-3">Basic Information</h3>
                        <dl class="space-y-2 text-sm">
                            <div class="flex justify-between"><dt class="text-gray-500">Name</dt><dd class="font-medium">{{ $user->name }}</dd></div>
                            <div class="flex justify-between"><dt class="text-gray-500">Email</dt><dd class="font-medium">{{ $user->email }}</dd></div>
                            <div class="flex justify-between"><dt class="text-gray-500">Role</dt><dd><span class="px-2 py-0.5 bg-[#021c47]/10 text-[#021c47] rounded text-xs">{{ $user->role->display_name ?? 'None' }}</span></dd></div>
                            <div class="flex justify-between"><dt class="text-gray-500">Status</dt><dd>@if($user->is_active)<span class="text-[#93db4d] font-medium">Active</span>@else<span class="text-red-500 font-medium">Inactive</span>@endif</dd></div>
                        </dl>
                    </div>
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                        <h3 class="font-semibold text-[#021c47] mb-3">Activity</h3>
                        <dl class="space-y-2 text-sm">
                            <div class="flex justify-between"><dt class="text-gray-500">Last Login</dt><dd class="font-medium">{{ $user->last_login_at ? $user->last_login_at->format('M j, Y g:i A') : 'Never' }}</dd></div>
                            <div class="flex justify-between"><dt class="text-gray-500">Created</dt><dd class="font-medium">{{ $user->created_at->format('M j, Y') }}</dd></div>
                            <div class="flex justify-between"><dt class="text-gray-500">Updated</dt><dd class="font-medium">{{ $user->updated_at->format('M j, Y') }}</dd></div>
                            <div class="flex justify-between"><dt class="text-gray-500">Account Age</dt><dd class="font-medium">{{ $user->created_at->diffForHumans() }}</dd></div>
                        </dl>
                    </div>
                </div>

                @if($user->role)
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <h3 class="font-semibold text-blue-900 mb-2">Role Permissions: {{ $user->role->display_name }}</h3>
                        <p class="text-blue-700 text-sm">
                            @if($user->role->name === 'super_admin') Full system access including user management, settings, and all modules
                            @elseif($user->role->name === 'admin') Manage content, users, brands, categories, and view analytics
                            @elseif($user->role->name === 'moderator') View-only access to most modules with limited editing
                            @else {{ $user->role->description ?? 'Custom role permissions' }} @endif
                        </p>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                @if($user->id !== auth()->id())
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                        <h3 class="font-semibold text-[#021c47] mb-3">Quick Actions</h3>
                        <div class="space-y-2">
                            <a href="{{ route('admin.users.edit', $user) }}" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition-colors font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Edit User
                            </a>
                            <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 {{ $user->is_active ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-[#93db4d] hover:bg-[#7bc73d]' }} text-white rounded-lg transition-colors font-medium">
                                    {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete this user?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Delete User
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <h3 class="font-semibold text-blue-900 mb-2">Your Account</h3>
                        <p class="text-blue-700 text-sm mb-3">This is your current account.</p>
                        <a href="{{ route('admin.users.edit', $user) }}" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-[#021c47] text-white rounded-lg hover:bg-[#93db4d] hover:text-[#021c47] transition-colors font-medium">Edit My Profile</a>
                    </div>
                @endif

                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                    <h3 class="font-semibold text-[#021c47] mb-3">Statistics</h3>
                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between"><dt class="text-gray-500">User ID</dt><dd class="font-mono text-gray-700">#{{ $user->id }}</dd></div>
                        <div class="flex justify-between"><dt class="text-gray-500">Role ID</dt><dd class="font-mono text-gray-700">#{{ $user->role_id }}</dd></div>
                    </dl>
                </div>

                <a href="{{ route('admin.users.index') }}" class="flex items-center justify-center gap-2 px-4 py-3 text-gray-600 hover:text-[#021c47] bg-white rounded-xl border border-gray-200 shadow-sm hover:border-[#93db4d] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back to Users
                </a>
            </div>
        </div>
    </div>
@endsection
