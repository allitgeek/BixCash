@extends('layouts.admin')

@section('title', 'Edit Customer - BixCash Admin')
@section('page-title', 'Edit Customer')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[#021c47]">Edit Customer</h1>
                <p class="text-gray-500 mt-1">{{ $customer->name }} &mdash; #{{ $customer->id }}</p>
            </div>
            <a href="{{ route('admin.customers.show', $customer) }}" class="inline-flex items-center px-4 py-2 border border-gray-200 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to Details
            </a>
        </div>

        <form method="POST" action="{{ route('admin.customers.update', $customer) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left: Form (2 cols) -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Basic Information Card -->
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-5 py-3 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-sm font-semibold text-[#021c47] uppercase tracking-wider">Basic Information</h3>
                        </div>
                        <div class="p-5 space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                                    <input type="text" id="name" name="name" value="{{ old('name', $customer->name) }}" required
                                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors @error('name') border-red-500 @enderror">
                                    @error('name')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <input type="email" id="email" name="email" value="{{ old('email', $customer->email) }}"
                                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors @error('email') border-red-500 @enderror">
                                    @error('email')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number <span class="text-red-500">*</span></label>
                                    <input type="text" id="phone" name="phone" value="{{ old('phone', $customer->phone) }}" required
                                           placeholder="+923001234567"
                                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors @error('phone') border-red-500 @enderror">
                                    <p class="mt-1 text-xs text-gray-400">Format: +92XXXXXXXXXX</p>
                                    @error('phone')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="is_active" class="block text-sm font-medium text-gray-700 mb-1">Account Status <span class="text-red-500">*</span></label>
                                    <select id="is_active" name="is_active" required
                                            class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors @error('is_active') border-red-500 @enderror">
                                        <option value="1" {{ old('is_active', $customer->is_active) == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('is_active', $customer->is_active) == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('is_active')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Information Card -->
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-5 py-3 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-sm font-semibold text-[#021c47] uppercase tracking-wider">Profile Information</h3>
                        </div>
                        <div class="p-5 space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                    <input type="text" id="full_name" name="full_name"
                                           value="{{ old('full_name', $customer->customerProfile->full_name ?? '') }}"
                                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors @error('full_name') border-red-500 @enderror">
                                    @error('full_name')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                                    <input type="date" id="date_of_birth" name="date_of_birth"
                                           value="{{ old('date_of_birth', $customer->customerProfile->date_of_birth ?? '') }}"
                                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors @error('date_of_birth') border-red-500 @enderror">
                                    @error('date_of_birth')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                                    <select id="gender" name="gender"
                                            class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors @error('gender') border-red-500 @enderror">
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender', $customer->customerProfile->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender', $customer->customerProfile->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender', $customer->customerProfile->gender ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('gender')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="cnic" class="block text-sm font-medium text-gray-700 mb-1">CNIC</label>
                                    <input type="text" id="cnic" name="cnic"
                                           value="{{ old('cnic', $customer->customerProfile->cnic ?? '') }}"
                                           placeholder="12345-1234567-1"
                                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors @error('cnic') border-red-500 @enderror">
                                    <p class="mt-1 text-xs text-gray-400">Format: XXXXX-XXXXXXX-X</p>
                                    @error('cnic')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Address Card -->
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-5 py-3 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-sm font-semibold text-[#021c47] uppercase tracking-wider">Address</h3>
                        </div>
                        <div class="p-5 space-y-4">
                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                                <textarea id="address" name="address" rows="3"
                                          class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors resize-none @error('address') border-red-500 @enderror">{{ old('address', $customer->customerProfile->address ?? '') }}</textarea>
                                @error('address')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                    <input type="text" id="city" name="city"
                                           value="{{ old('city', $customer->customerProfile->city ?? '') }}"
                                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors @error('city') border-red-500 @enderror">
                                    @error('city')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1">Postal Code</label>
                                    <input type="text" id="postal_code" name="postal_code"
                                           value="{{ old('postal_code', $customer->customerProfile->postal_code ?? '') }}"
                                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors @error('postal_code') border-red-500 @enderror">
                                    @error('postal_code')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="flex gap-3">
                        <button type="submit" class="px-6 py-2.5 bg-[#021c47] text-white rounded-lg font-medium hover:bg-[#93db4d] hover:text-[#021c47] transition-colors">
                            Update Customer
                        </button>
                        <a href="{{ route('admin.customers.show', $customer) }}" class="px-6 py-2.5 border border-gray-200 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                            Cancel
                        </a>
                    </div>
                </div>

                <!-- Right: Sidebar (1 col) -->
                <div class="space-y-6">
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden sticky top-4">
                        <div class="px-5 py-3 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-sm font-semibold text-[#021c47] uppercase tracking-wider">Customer Summary</h3>
                        </div>
                        <div class="p-5">
                            <dl class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">ID</dt>
                                    <dd class="font-medium text-gray-900">#{{ $customer->id }}</dd>
                                </div>
                                <div class="border-t border-gray-100"></div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Name</dt>
                                    <dd class="font-medium text-gray-900">{{ $customer->name }}</dd>
                                </div>
                                <div class="border-t border-gray-100"></div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Phone</dt>
                                    <dd class="font-medium text-gray-900">{{ $customer->phone }}</dd>
                                </div>
                                <div class="border-t border-gray-100"></div>
                                <div class="flex justify-between">
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
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Registered</dt>
                                    <dd class="font-medium text-gray-900">{{ $customer->created_at->format('M j, Y') }}</dd>
                                </div>
                                <div class="border-t border-gray-100"></div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Last Login</dt>
                                    <dd class="font-medium text-gray-900">{{ $customer->last_login_at ? $customer->last_login_at->format('M j, Y') : 'Never' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
