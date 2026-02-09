@extends('layouts.admin')

@section('title', 'Edit Partner - BixCash Admin')
@section('page-title', 'Edit Partner Profile')

@section('content')
    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route('admin.partners.show', $partner) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-all duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Cancel
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-[#021c47]">Edit Partner: {{ $partner->partnerProfile->business_name ?? $partner->name }}</h3>
            <p class="text-sm text-gray-500 mt-1">Update business and contact information</p>
        </div>
        
        <div class="p-6">
            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg mb-6">
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

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    {{-- Business Name --}}
                    <div>
                        <label for="business_name" class="block text-sm font-medium text-[#021c47] mb-2">
                            Business Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="business_name" name="business_name" required
                               value="{{ old('business_name', $partner->partnerProfile->business_name ?? '') }}"
                               placeholder="e.g., KFC Lahore"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all">
                    </div>

                    {{-- Contact Person Name --}}
                    <div>
                        <label for="contact_person_name" class="block text-sm font-medium text-[#021c47] mb-2">
                            Contact Person Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="contact_person_name" name="contact_person_name" required
                               value="{{ old('contact_person_name', $partner->partnerProfile->contact_person_name ?? $partner->name) }}"
                               placeholder="Full name"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all">
                    </div>

                    {{-- Phone (Read-only) --}}
                    <div>
                        <label for="phone_display" class="block text-sm font-medium text-[#021c47] mb-2">Mobile Number</label>
                        <input type="text" id="phone_display" readonly value="{{ $partner->phone }}"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-gray-50 cursor-not-allowed text-gray-500">
                        <p class="mt-1 text-xs text-gray-400">Phone number cannot be changed</p>
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-[#021c47] mb-2">Email (Optional)</label>
                        <input type="email" id="email" name="email"
                               value="{{ old('email', $partner->email) }}"
                               placeholder="partner@email.com"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all">
                    </div>

                    {{-- Business Type --}}
                    <div>
                        <label for="business_type" class="block text-sm font-medium text-[#021c47] mb-2">
                            Business Type <span class="text-red-500">*</span>
                        </label>
                        <select id="business_type" name="business_type" required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all">
                            <option value="">Select business type</option>
                            @foreach(['Restaurant', 'Retail', 'Cafe', 'Grocery', 'Fashion', 'Electronics', 'Salon', 'Pharmacy', 'Services', 'Other'] as $type)
                                <option value="{{ $type }}" {{ old('business_type', $partner->partnerProfile->business_type ?? '') == $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- City --}}
                    <div>
                        <label for="business_city" class="block text-sm font-medium text-[#021c47] mb-2">City (Optional)</label>
                        <input type="text" id="business_city" name="business_city"
                               value="{{ old('business_city', $partner->partnerProfile->business_city ?? '') }}"
                               placeholder="e.g., Lahore"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all">
                    </div>

                    {{-- Commission Rate --}}
                    <div>
                        <label for="commission_rate" class="block text-sm font-medium text-[#021c47] mb-2">Commission Rate (%)</label>
                        <input type="number" id="commission_rate" name="commission_rate"
                               value="{{ old('commission_rate', $partner->partnerProfile->commission_rate ?? 0) }}"
                               placeholder="0.00" min="0" max="100" step="0.01"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all">
                        <p class="mt-1 text-xs text-gray-400">Percentage the partner pays BixCash (0-100%)</p>
                    </div>
                </div>

                {{-- Business Address --}}
                <div class="mb-6">
                    <label for="business_address" class="block text-sm font-medium text-[#021c47] mb-2">Business Address (Optional)</label>
                    <input type="text" id="business_address" name="business_address"
                           value="{{ old('business_address', $partner->partnerProfile->business_address ?? '') }}"
                           placeholder="Complete business address"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all">
                </div>

                {{-- Logo Info --}}
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="flex items-center gap-4">
                        @if($partner->partnerProfile && $partner->partnerProfile->logo)
                            <img src="{{ asset('storage/' . $partner->partnerProfile->logo) }}" alt="Current Logo"
                                 class="w-16 h-16 object-cover rounded-lg border-2 border-gray-200 bg-white">
                            <div>
                                <p class="font-medium text-[#021c47]">Current Logo</p>
                                <p class="text-sm text-gray-500">To change logo, go back to partner details page.</p>
                            </div>
                        @else
                            <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-[#021c47]">No Logo Uploaded</p>
                                <p class="text-sm text-gray-500">To upload logo, go back to partner details page.</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Submit --}}
                <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.partners.show', $partner) }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-all duration-200">
                        Cancel
                    </a>
                    <button type="submit" class="px-5 py-2.5 bg-[#021c47] text-white font-medium rounded-lg hover:bg-[#93db4d] hover:text-[#021c47] transition-all duration-200">
                        Update Partner
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
