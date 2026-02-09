@extends('layouts.admin')

@section('title', 'Edit Customer - BixCash Admin')
@section('page-title', 'Edit Customer')

@section('content')
    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route('admin.customers.show', $customer) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-all duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Customer Details
        </a>
    </div>

    {{-- Edit Form Card --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-[#021c47]">Edit Customer Information</h3>
            <p class="text-sm text-gray-500 mt-1">Update account and profile details for {{ $customer->name }}</p>
        </div>
        
        <div class="p-6">
            <form method="POST" action="{{ route('admin.customers.update', $customer) }}">
                @csrf
                @method('PUT')

                {{-- Basic Information Section --}}
                <div class="mb-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-1 h-6 bg-[#021c47] rounded-full"></div>
                        <h4 class="text-base font-bold text-[#021c47]">Basic Information</h4>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Name --}}
                        <div>
                            <label for="name" class="block text-sm font-medium text-[#021c47] mb-2">
                                Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name', $customer->name) }}" required
                                   class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all @error('name') border-red-300 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-sm font-medium text-[#021c47] mb-2">
                                Email
                            </label>
                            <input type="email" id="email" name="email" value="{{ old('email', $customer->email) }}"
                                   class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all @error('email') border-red-300 @enderror">
                            @error('email')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Phone --}}
                        <div>
                            <label for="phone" class="block text-sm font-medium text-[#021c47] mb-2">
                                Phone Number <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone', $customer->phone) }}" required
                                   placeholder="+923001234567"
                                   class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all @error('phone') border-red-300 @enderror">
                            <p class="mt-1 text-xs text-gray-400">Format: +92XXXXXXXXXX</p>
                            @error('phone')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div>
                            <label for="is_active" class="block text-sm font-medium text-[#021c47] mb-2">
                                Account Status <span class="text-red-500">*</span>
                            </label>
                            <select id="is_active" name="is_active" required
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all @error('is_active') border-red-300 @enderror">
                                <option value="1" {{ old('is_active', $customer->is_active) == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('is_active', $customer->is_active) == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('is_active')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Profile Information Section --}}
                <div class="mb-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-1 h-6 bg-[#93db4d] rounded-full"></div>
                        <h4 class="text-base font-bold text-[#021c47]">Profile Information</h4>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Full Name --}}
                        <div>
                            <label for="full_name" class="block text-sm font-medium text-[#021c47] mb-2">
                                Full Name
                            </label>
                            <input type="text" id="full_name" name="full_name"
                                   value="{{ old('full_name', $customer->customerProfile->full_name ?? '') }}"
                                   class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all @error('full_name') border-red-300 @enderror">
                            @error('full_name')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Date of Birth --}}
                        <div>
                            <label for="date_of_birth" class="block text-sm font-medium text-[#021c47] mb-2">
                                Date of Birth
                            </label>
                            <input type="date" id="date_of_birth" name="date_of_birth"
                                   value="{{ old('date_of_birth', $customer->customerProfile->date_of_birth ?? '') }}"
                                   class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all @error('date_of_birth') border-red-300 @enderror">
                            @error('date_of_birth')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Gender --}}
                        <div>
                            <label for="gender" class="block text-sm font-medium text-[#021c47] mb-2">
                                Gender
                            </label>
                            <select id="gender" name="gender"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all @error('gender') border-red-300 @enderror">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender', $customer->customerProfile->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $customer->customerProfile->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender', $customer->customerProfile->gender ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- CNIC --}}
                        <div>
                            <label for="cnic" class="block text-sm font-medium text-[#021c47] mb-2">
                                CNIC
                            </label>
                            <input type="text" id="cnic" name="cnic"
                                   value="{{ old('cnic', $customer->customerProfile->cnic ?? '') }}"
                                   placeholder="12345-1234567-1"
                                   class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all @error('cnic') border-red-300 @enderror">
                            <p class="mt-1 text-xs text-gray-400">Format: XXXXX-XXXXXXX-X</p>
                            @error('cnic')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Address Information Section --}}
                <div class="mb-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-1 h-6 bg-[#021c47] rounded-full"></div>
                        <h4 class="text-base font-bold text-[#021c47]">Address Information</h4>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Address (Full Width) --}}
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-[#021c47] mb-2">
                                Address
                            </label>
                            <textarea id="address" name="address" rows="3"
                                      class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all resize-none @error('address') border-red-300 @enderror">{{ old('address', $customer->customerProfile->address ?? '') }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- City --}}
                        <div>
                            <label for="city" class="block text-sm font-medium text-[#021c47] mb-2">
                                City
                            </label>
                            <input type="text" id="city" name="city"
                                   value="{{ old('city', $customer->customerProfile->city ?? '') }}"
                                   class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all @error('city') border-red-300 @enderror">
                            @error('city')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Postal Code --}}
                        <div>
                            <label for="postal_code" class="block text-sm font-medium text-[#021c47] mb-2">
                                Postal Code
                            </label>
                            <input type="text" id="postal_code" name="postal_code"
                                   value="{{ old('postal_code', $customer->customerProfile->postal_code ?? '') }}"
                                   class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all @error('postal_code') border-red-300 @enderror">
                            @error('postal_code')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Submit Buttons --}}
                <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.customers.show', $customer) }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-all duration-200">
                        Cancel
                    </a>
                    <button type="submit" class="px-5 py-2.5 bg-[#021c47] text-white font-medium rounded-lg hover:bg-[#93db4d] hover:text-[#021c47] transition-all duration-200">
                        Update Customer
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
