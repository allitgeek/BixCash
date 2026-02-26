@extends('layouts.admin')

@section('title', 'Edit Partner - BixCash Admin')
@section('page-title', 'Edit Partner Profile')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[#021c47]">Edit Partner</h1>
                <p class="text-gray-500 mt-1">{{ $partner->partnerProfile->business_name ?? $partner->name }} &mdash; #{{ $partner->id }}</p>
            </div>
            <a href="{{ route('admin.partners.show', $partner) }}" class="inline-flex items-center px-4 py-2 border border-gray-200 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Cancel
            </a>
        </div>

        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg">
                <p class="text-sm text-red-700 font-medium">Please fix the following errors:</p>
                <ul class="list-disc pl-5 mt-2 text-sm text-red-600">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.partners.update', $partner) }}" id="editPartnerForm">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left: Form (2 cols) -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Business Details Card -->
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-5 py-3 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-sm font-semibold text-[#021c47] uppercase tracking-wider">Business Details</h3>
                        </div>
                        <div class="p-5 space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="business_name" class="block text-sm font-medium text-gray-700 mb-1">Business Name <span class="text-red-500">*</span></label>
                                    <input type="text" id="business_name" name="business_name" required
                                           value="{{ old('business_name', $partner->partnerProfile->business_name ?? '') }}"
                                           placeholder="e.g., KFC Lahore"
                                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors">
                                </div>
                                <div>
                                    <label for="business_type" class="block text-sm font-medium text-gray-700 mb-1">Business Type <span class="text-red-500">*</span></label>
                                    <select id="business_type" name="business_type" required
                                            class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors">
                                        <option value="">Select business type</option>
                                        @foreach(['Restaurant', 'Retail', 'Cafe', 'Grocery', 'Fashion', 'Electronics', 'Salon', 'Pharmacy', 'Services', 'Other'] as $type)
                                            <option value="{{ $type }}" {{ old('business_type', $partner->partnerProfile->business_type ?? '') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="commission_rate" class="block text-sm font-medium text-gray-700 mb-1">Commission Rate (%)</label>
                                    <input type="number" id="commission_rate" name="commission_rate"
                                           value="{{ old('commission_rate', $partner->partnerProfile->commission_rate ?? 0) }}"
                                           placeholder="0.00" min="0" max="100" step="0.01"
                                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors">
                                    <p class="mt-1 text-xs text-gray-400">Percentage the partner pays BixCash (0-100%)</p>
                                </div>
                                <div>
                                    <label for="business_city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                    <input type="text" id="business_city" name="business_city"
                                           value="{{ old('business_city', $partner->partnerProfile->business_city ?? '') }}"
                                           placeholder="e.g., Lahore"
                                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information Card -->
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-5 py-3 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-sm font-semibold text-[#021c47] uppercase tracking-wider">Contact Information</h3>
                        </div>
                        <div class="p-5 space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="contact_person_name" class="block text-sm font-medium text-gray-700 mb-1">Contact Person <span class="text-red-500">*</span></label>
                                    <input type="text" id="contact_person_name" name="contact_person_name" required
                                           value="{{ old('contact_person_name', $partner->partnerProfile->contact_person_name ?? $partner->name) }}"
                                           placeholder="Full name"
                                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors">
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <input type="email" id="email" name="email"
                                           value="{{ old('email', $partner->email) }}"
                                           placeholder="partner@email.com"
                                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors">
                                </div>
                            </div>
                            <div>
                                <label for="phone_display" class="block text-sm font-medium text-gray-700 mb-1">Mobile Number</label>
                                <input type="text" id="phone_display" readonly value="{{ $partner->phone }}"
                                       class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 cursor-not-allowed text-gray-500">
                                <p class="mt-1 text-xs text-gray-400">Phone number cannot be changed</p>
                            </div>
                            <div>
                                <label for="business_address" class="block text-sm font-medium text-gray-700 mb-1">Business Address</label>
                                <input type="text" id="business_address" name="business_address"
                                       value="{{ old('business_address', $partner->partnerProfile->business_address ?? '') }}"
                                       placeholder="Complete business address"
                                       class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors">
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="flex gap-3">
                        <button type="submit" class="px-6 py-2.5 bg-[#021c47] text-white rounded-lg font-medium hover:bg-[#93db4d] hover:text-[#021c47] transition-colors">
                            Update Partner
                        </button>
                        <a href="{{ route('admin.partners.show', $partner) }}" class="px-6 py-2.5 border border-gray-200 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                            Cancel
                        </a>
                    </div>
                </div>

                <!-- Right: Sidebar (1 col) -->
                <div class="space-y-6">
                    <!-- Current Logo -->
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden sticky top-4">
                        <div class="px-5 py-3 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-sm font-semibold text-[#021c47] uppercase tracking-wider">Current Logo</h3>
                        </div>
                        <div class="p-5">
                            <div class="flex items-center gap-4">
                                @if($partner->partnerProfile && $partner->partnerProfile->logo)
                                    <img src="{{ asset('storage/' . $partner->partnerProfile->logo) }}" alt="Current Logo"
                                         class="w-20 h-14 object-contain rounded border border-gray-200 bg-white p-1">
                                @else
                                    <div class="w-20 h-14 bg-gray-100 rounded border border-gray-200 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                @endif
                                <p class="text-xs text-gray-400">To change logo, use the partner details page.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Partner Info -->
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-5 py-3 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-sm font-semibold text-[#021c47] uppercase tracking-wider">Partner Info</h3>
                        </div>
                        <div class="p-5">
                            <dl class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">ID</dt>
                                    <dd class="font-medium text-gray-900">#{{ $partner->id }}</dd>
                                </div>
                                <div class="border-t border-gray-100"></div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Phone</dt>
                                    <dd class="font-medium text-gray-900">{{ $partner->phone }}</dd>
                                </div>
                                <div class="border-t border-gray-100"></div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Status</dt>
                                    <dd>
                                        @if($partner->is_active)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Active</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Inactive</span>
                                        @endif
                                    </dd>
                                </div>
                                <div class="border-t border-gray-100"></div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Created</dt>
                                    <dd class="font-medium text-gray-900">{{ $partner->created_at->format('M j, Y') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
