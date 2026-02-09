@extends('layouts.admin')

@section('title', 'Customer Details - BixCash Admin')
@section('page-title', 'Customer Details')

@section('content')
    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route('admin.customers.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-all duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Customers
        </a>
    </div>

    {{-- Customer Basic Info Card --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-[#021c47]">Customer Information</h3>
                <p class="text-sm text-gray-500 mt-1">Account #{{ $customer->id }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.customers.edit', $customer) }}" class="px-4 py-2 bg-[#021c47] text-white font-medium rounded-lg hover:bg-[#93db4d] hover:text-[#021c47] transition-all duration-200">
                    Edit Customer
                </a>
                <form method="POST" action="{{ route('admin.customers.destroy', $customer) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this customer? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white font-medium rounded-lg hover:bg-red-600 transition-all duration-200">
                        Delete
                    </button>
                </form>
            </div>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Left Column --}}
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Customer ID</span>
                        <span class="text-sm font-semibold text-[#021c47]">#{{ $customer->id }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Name</span>
                        <span class="text-sm font-semibold text-[#021c47]">{{ $customer->name }}</span>
                    </div>
                    @if($customer->customerProfile && $customer->customerProfile->full_name)
                        <div class="flex justify-between items-center py-3 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-500">Full Name</span>
                            <span class="text-sm font-semibold text-[#021c47]">{{ $customer->customerProfile->full_name }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Email</span>
                        <span class="text-sm text-[#021c47]">{{ $customer->email ?? 'Not provided' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Phone</span>
                        <span class="text-sm text-[#021c47] flex items-center gap-2">
                            {{ $customer->phone }}
                            @if($customer->hasVerifiedPhone())
                                <span class="px-2 py-0.5 text-xs font-medium rounded bg-[#93db4d]/20 text-[#65a030]">Verified</span>
                            @else
                                <span class="px-2 py-0.5 text-xs font-medium rounded bg-red-100 text-red-600">Unverified</span>
                            @endif
                        </span>
                    </div>
                    @if($customer->phone_verified_at)
                        <div class="flex justify-between items-center py-3 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-500">Phone Verified At</span>
                            <span class="text-sm text-[#021c47]">{{ $customer->phone_verified_at->format('M j, Y g:i A') }}</span>
                        </div>
                    @endif
                </div>

                {{-- Right Column --}}
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Status</span>
                        <span>
                            @if($customer->is_active)
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-[#93db4d]/20 text-[#65a030]">Active</span>
                            @else
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-600">Inactive</span>
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">PIN Status</span>
                        <span>
                            @if($customer->pin_hash)
                                @if($customer->isPinLocked())
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">
                                        Locked ({{ $customer->getPinLockoutTimeRemaining() }} min)
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-[#93db4d]/20 text-[#65a030]">Set</span>
                                @endif
                            @else
                                <span class="text-sm text-gray-400">Not Set</span>
                            @endif
                        </span>
                    </div>
                    @if($customer->pin_hash)
                        <div class="flex justify-between items-center py-3 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-500">PIN Attempts</span>
                            <span class="text-sm text-[#021c47]">{{ $customer->pin_attempts }}/5</span>
                        </div>
                    @endif
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Registered</span>
                        <span class="text-sm text-[#021c47]">{{ $customer->created_at->format('M j, Y g:i A') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Last Login</span>
                        <span class="text-sm text-[#021c47]">{{ $customer->last_login_at ? $customer->last_login_at->format('M j, Y g:i A') : 'Never' }}</span>
                    </div>
                </div>
            </div>

            {{-- PIN Management Section --}}
            @if($customer->pin_hash)
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h4 class="text-base font-bold text-[#021c47] mb-4">PIN Management</h4>
                    <div class="flex flex-wrap gap-3">
                        @if($customer->isPinLocked())
                            <form method="POST" action="{{ route('admin.customers.unlock-pin', $customer) }}" class="inline">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-yellow-500 text-white font-medium rounded-lg hover:bg-yellow-600 transition-all duration-200" onclick="return confirm('Unlock PIN for this customer?')">
                                    Unlock PIN
                                </button>
                            </form>
                        @endif
                        <form method="POST" action="{{ route('admin.customers.reset-pin', $customer) }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-red-500 text-white font-medium rounded-lg hover:bg-red-600 transition-all duration-200" onclick="return confirm('Reset PIN? Customer will need to set a new PIN.')">
                                Reset PIN
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            {{-- Status Toggle Section --}}
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h4 class="text-base font-bold text-[#021c47] mb-4">Account Status</h4>
                <form method="POST" action="{{ route('admin.customers.toggle-status', $customer) }}" class="inline">
                    @csrf
                    @method('PATCH')
                    @if($customer->is_active)
                        <button type="submit" class="px-4 py-2 bg-yellow-500 text-white font-medium rounded-lg hover:bg-yellow-600 transition-all duration-200" onclick="return confirm('Deactivate this customer account?')">
                            Deactivate Account
                        </button>
                    @else
                        <button type="submit" class="px-4 py-2 bg-[#93db4d] text-[#021c47] font-medium rounded-lg hover:bg-[#7fc93d] transition-all duration-200" onclick="return confirm('Activate this customer account?')">
                            Activate Account
                        </button>
                    @endif
                </form>
                <p class="mt-3 text-sm text-gray-500">
                    {{ $customer->is_active ? 'Deactivating will prevent the customer from logging in.' : 'Activating will allow the customer to log in again.' }}
                </p>
            </div>
        </div>
    </div>

    {{-- Customer Profile Card --}}
    @if($customer->customerProfile)
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-bold text-[#021c47]">Profile Details</h3>
            </div>
            <div class="p-6">
                @if($customer->customerProfile->date_of_birth || $customer->customerProfile->gender || $customer->customerProfile->address || $customer->customerProfile->city || $customer->customerProfile->cnic)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-4">
                            @if($customer->customerProfile->date_of_birth)
                                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                    <span class="text-sm font-medium text-gray-500">Date of Birth</span>
                                    <span class="text-sm text-[#021c47]">{{ \Carbon\Carbon::parse($customer->customerProfile->date_of_birth)->format('M j, Y') }}</span>
                                </div>
                            @endif
                            @if($customer->customerProfile->gender)
                                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                    <span class="text-sm font-medium text-gray-500">Gender</span>
                                    <span class="text-sm text-[#021c47]">{{ ucfirst($customer->customerProfile->gender) }}</span>
                                </div>
                            @endif
                            @if($customer->customerProfile->cnic)
                                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                    <span class="text-sm font-medium text-gray-500">CNIC</span>
                                    <span class="text-sm text-[#021c47] flex items-center gap-2">
                                        {{ $customer->customerProfile->cnic }}
                                        @if($customer->customerProfile->cnic_verified)
                                            <span class="px-2 py-0.5 text-xs font-medium rounded bg-[#93db4d]/20 text-[#65a030]">Verified</span>
                                        @endif
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="space-y-4">
                            @if($customer->customerProfile->address)
                                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                    <span class="text-sm font-medium text-gray-500">Address</span>
                                    <span class="text-sm text-[#021c47] text-right">{{ $customer->customerProfile->address }}</span>
                                </div>
                            @endif
                            @if($customer->customerProfile->city)
                                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                    <span class="text-sm font-medium text-gray-500">City</span>
                                    <span class="text-sm text-[#021c47]">{{ $customer->customerProfile->city }}</span>
                                </div>
                            @endif
                            @if($customer->customerProfile->postal_code)
                                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                    <span class="text-sm font-medium text-gray-500">Postal Code</span>
                                    <span class="text-sm text-[#021c47]">{{ $customer->customerProfile->postal_code }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
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
    @endif
@endsection
