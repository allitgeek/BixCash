@extends('layouts.admin')

@section('title', 'Customer Details - BixCash Admin')
@section('page-title', 'Customer Details')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[#021c47]">{{ $customer->name }}</h1>
                <p class="text-gray-500 mt-1">Customer #{{ $customer->id }}</p>
            </div>
            <a href="{{ route('admin.customers.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-200 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to Customers
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left: Content (2 cols) -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Account Details Card -->
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-3 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-sm font-semibold text-[#021c47] uppercase tracking-wider">Account Details</h3>
                    </div>
                    <div class="p-5">
                        <dl class="space-y-3 text-sm">
                            <div class="flex justify-between items-center">
                                <dt class="text-gray-500">Customer ID</dt>
                                <dd class="font-medium text-gray-900">#{{ $customer->id }}</dd>
                            </div>
                            <div class="border-t border-gray-100"></div>
                            <div class="flex justify-between items-center">
                                <dt class="text-gray-500">Name</dt>
                                <dd class="font-medium text-gray-900">{{ $customer->name }}</dd>
                            </div>
                            @if($customer->customerProfile && $customer->customerProfile->full_name)
                                <div class="border-t border-gray-100"></div>
                                <div class="flex justify-between items-center">
                                    <dt class="text-gray-500">Full Name</dt>
                                    <dd class="font-medium text-gray-900">{{ $customer->customerProfile->full_name }}</dd>
                                </div>
                            @endif
                            <div class="border-t border-gray-100"></div>
                            <div class="flex justify-between items-center">
                                <dt class="text-gray-500">Email</dt>
                                <dd class="font-medium text-gray-900">{{ $customer->email ?? 'Not provided' }}</dd>
                            </div>
                            <div class="border-t border-gray-100"></div>
                            <div class="flex justify-between items-center">
                                <dt class="text-gray-500">Phone</dt>
                                <dd class="font-medium text-gray-900 flex items-center gap-2">
                                    {{ $customer->phone }}
                                    @if($customer->hasVerifiedPhone())
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Verified</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Unverified</span>
                                    @endif
                                </dd>
                            </div>
                            @if($customer->phone_verified_at)
                                <div class="border-t border-gray-100"></div>
                                <div class="flex justify-between items-center">
                                    <dt class="text-gray-500">Phone Verified</dt>
                                    <dd class="font-medium text-gray-900">{{ $customer->phone_verified_at->format('M j, Y g:i A') }}</dd>
                                </div>
                            @endif
                            <div class="border-t border-gray-100"></div>
                            <div class="flex justify-between items-center">
                                <dt class="text-gray-500">Status</dt>
                                <dd>
                                    @if($customer->is_active)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Active</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Inactive</span>
                                    @endif
                                </dd>
                            </div>
                            <div class="border-t border-gray-100"></div>
                            <div class="flex justify-between items-center">
                                <dt class="text-gray-500">Registered</dt>
                                <dd class="font-medium text-gray-900">{{ $customer->created_at->format('M j, Y g:i A') }}</dd>
                            </div>
                            <div class="border-t border-gray-100"></div>
                            <div class="flex justify-between items-center">
                                <dt class="text-gray-500">Last Login</dt>
                                <dd class="font-medium text-gray-900">{{ $customer->last_login_at ? $customer->last_login_at->format('M j, Y g:i A') : 'Never' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Profile Details Card -->
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-3 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-sm font-semibold text-[#021c47] uppercase tracking-wider">Profile Details</h3>
                    </div>
                    <div class="p-5">
                        @if($customer->customerProfile && ($customer->customerProfile->date_of_birth || $customer->customerProfile->gender || $customer->customerProfile->address || $customer->customerProfile->city || $customer->customerProfile->cnic))
                            <dl class="space-y-3 text-sm">
                                @if($customer->customerProfile->date_of_birth)
                                    <div class="flex justify-between items-center">
                                        <dt class="text-gray-500">Date of Birth</dt>
                                        <dd class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($customer->customerProfile->date_of_birth)->format('M j, Y') }}</dd>
                                    </div>
                                    <div class="border-t border-gray-100"></div>
                                @endif
                                @if($customer->customerProfile->gender)
                                    <div class="flex justify-between items-center">
                                        <dt class="text-gray-500">Gender</dt>
                                        <dd class="font-medium text-gray-900">{{ ucfirst($customer->customerProfile->gender) }}</dd>
                                    </div>
                                    <div class="border-t border-gray-100"></div>
                                @endif
                                @if($customer->customerProfile->cnic)
                                    <div class="flex justify-between items-center">
                                        <dt class="text-gray-500">CNIC</dt>
                                        <dd class="font-medium text-gray-900 flex items-center gap-2">
                                            {{ $customer->customerProfile->cnic }}
                                            @if($customer->customerProfile->cnic_verified)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Verified</span>
                                            @endif
                                        </dd>
                                    </div>
                                    <div class="border-t border-gray-100"></div>
                                @endif
                                @if($customer->customerProfile->address)
                                    <div class="flex justify-between items-center">
                                        <dt class="text-gray-500">Address</dt>
                                        <dd class="font-medium text-gray-900 text-right max-w-[60%]">{{ $customer->customerProfile->address }}</dd>
                                    </div>
                                    <div class="border-t border-gray-100"></div>
                                @endif
                                @if($customer->customerProfile->city)
                                    <div class="flex justify-between items-center">
                                        <dt class="text-gray-500">City</dt>
                                        <dd class="font-medium text-gray-900">{{ $customer->customerProfile->city }}</dd>
                                    </div>
                                    <div class="border-t border-gray-100"></div>
                                @endif
                                @if($customer->customerProfile->postal_code)
                                    <div class="flex justify-between items-center">
                                        <dt class="text-gray-500">Postal Code</dt>
                                        <dd class="font-medium text-gray-900">{{ $customer->customerProfile->postal_code }}</dd>
                                    </div>
                                @endif
                            </dl>
                        @else
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <p class="text-gray-400">Customer has not completed their profile yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right: Sidebar (1 col) -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden sticky top-4">
                    <div class="px-5 py-3 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-sm font-semibold text-[#021c47] uppercase tracking-wider">Quick Actions</h3>
                    </div>
                    <div class="p-5 space-y-3">
                        <a href="{{ route('admin.customers.edit', $customer) }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-[#021c47] text-white font-medium rounded-lg hover:bg-[#93db4d] hover:text-[#021c47] transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit Customer
                        </a>

                        <form method="POST" action="{{ route('admin.customers.toggle-status', $customer) }}">
                            @csrf
                            @method('PATCH')
                            @if($customer->is_active)
                                <button type="submit" class="w-full px-4 py-2.5 bg-yellow-500 text-white font-medium rounded-lg hover:bg-yellow-600 transition-colors" onclick="return confirm('Deactivate this customer account?')">
                                    Deactivate Account
                                </button>
                            @else
                                <button type="submit" class="w-full px-4 py-2.5 bg-[#93db4d] text-[#021c47] font-medium rounded-lg hover:bg-[#7fc93d] transition-colors" onclick="return confirm('Activate this customer account?')">
                                    Activate Account
                                </button>
                            @endif
                        </form>
                        <p class="text-xs text-gray-400">
                            {{ $customer->is_active ? 'Deactivating will prevent login.' : 'Activating will allow login again.' }}
                        </p>

                        <div class="border-t border-gray-100 pt-3">
                            <form method="POST" action="{{ route('admin.customers.destroy', $customer) }}" onsubmit="return confirm('Are you sure you want to delete this customer? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full px-4 py-2.5 border border-red-200 text-red-600 font-medium rounded-lg hover:bg-red-50 transition-colors">
                                    Delete Customer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- PIN Management -->
                @if($customer->pin_hash)
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-5 py-3 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-sm font-semibold text-[#021c47] uppercase tracking-wider">PIN Management</h3>
                        </div>
                        <div class="p-5">
                            <dl class="space-y-3 text-sm mb-4">
                                <div class="flex justify-between items-center">
                                    <dt class="text-gray-500">PIN Status</dt>
                                    <dd>
                                        @if($customer->isPinLocked())
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                                Locked ({{ $customer->getPinLockoutTimeRemaining() }} min)
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Set</span>
                                        @endif
                                    </dd>
                                </div>
                                <div class="border-t border-gray-100"></div>
                                <div class="flex justify-between items-center">
                                    <dt class="text-gray-500">PIN Attempts</dt>
                                    <dd class="font-medium text-gray-900">{{ $customer->pin_attempts }}/5</dd>
                                </div>
                            </dl>

                            <div class="space-y-2">
                                @if($customer->isPinLocked())
                                    <form method="POST" action="{{ route('admin.customers.unlock-pin', $customer) }}">
                                        @csrf
                                        <button type="submit" class="w-full px-4 py-2 bg-yellow-500 text-white font-medium rounded-lg hover:bg-yellow-600 transition-colors text-sm" onclick="return confirm('Unlock PIN for this customer?')">
                                            Unlock PIN
                                        </button>
                                    </form>
                                @endif
                                <form method="POST" action="{{ route('admin.customers.reset-pin', $customer) }}">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-2 border border-red-200 text-red-600 font-medium rounded-lg hover:bg-red-50 transition-colors text-sm" onclick="return confirm('Reset PIN? Customer will need to set a new PIN.')">
                                        Reset PIN
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
