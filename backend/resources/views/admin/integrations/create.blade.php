@extends('layouts.admin')

@section('title', 'Add Integration - BixCash Admin')
@section('page-title', 'Add API Integration')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[#021c47]">New Integration</h1>
                <p class="text-gray-500 mt-1">Create an API integration for an external partner</p>
            </div>
            <a href="{{ route('admin.integrations.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-200 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to Integrations
            </a>
        </div>

        <form action="{{ route('admin.integrations.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left: Form (2 cols) -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Integration Settings Card -->
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-5 py-3 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-sm font-semibold text-[#021c47] uppercase tracking-wider">Integration Settings</h3>
                        </div>
                        <div class="p-5 space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="partner_id" class="block text-sm font-medium text-gray-700 mb-1">Partner <span class="text-red-500">*</span></label>
                                    <select name="partner_id" id="partner_id" required
                                            class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors">
                                        <option value="">Select a partner...</option>
                                        @foreach($partners as $partner)
                                            <option value="{{ $partner->id }}" {{ old('partner_id') == $partner->id ? 'selected' : '' }}>
                                                {{ $partner->partnerProfile->business_name ?? $partner->name }} ({{ $partner->phone }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('partner_id')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                    @if($partners->isEmpty())
                                        <p class="mt-1 text-xs text-yellow-600">No approved partners available.</p>
                                    @endif
                                </div>
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Integration Name <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" id="name" required value="{{ old('name') }}"
                                           placeholder="e.g., FreshBox"
                                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors">
                                    @error('name')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="allowed_ips" class="block text-sm font-medium text-gray-700 mb-1">Allowed IPs</label>
                                <textarea name="allowed_ips" id="allowed_ips" rows="3"
                                          placeholder="Comma-separated IPs, e.g., 203.0.113.1, 198.51.100.2"
                                          class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors resize-none">{{ old('allowed_ips') }}</textarea>
                                <p class="mt-1 text-xs text-gray-400">Leave blank to allow all IPs</p>
                                @error('allowed_ips')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="rate_limit_per_minute" class="block text-sm font-medium text-gray-700 mb-1">Rate Limit (requests/min)</label>
                                    <input type="number" name="rate_limit_per_minute" id="rate_limit_per_minute"
                                           value="{{ old('rate_limit_per_minute', 60) }}" min="1" max="1000"
                                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors">
                                    @error('rate_limit_per_minute')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="flex gap-3">
                        <button type="submit" class="px-6 py-2.5 bg-[#021c47] text-white rounded-lg font-medium hover:bg-[#93db4d] hover:text-[#021c47] transition-colors">
                            Create Integration
                        </button>
                        <a href="{{ route('admin.integrations.index') }}" class="px-6 py-2.5 border border-gray-200 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                            Cancel
                        </a>
                    </div>
                </div>

                <!-- Right: Sidebar (1 col) -->
                <div class="space-y-6">
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden sticky top-4">
                        <div class="px-5 py-3 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-sm font-semibold text-[#021c47] uppercase tracking-wider">About Integrations</h3>
                        </div>
                        <div class="p-5">
                            <div class="space-y-4 text-sm">
                                <p class="text-gray-600">API integrations allow external partners to send transactions directly to BixCash via a secure REST API.</p>

                                <div class="space-y-2">
                                    <div class="flex items-start gap-2">
                                        <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        <p class="text-gray-600">API key will be generated automatically</p>
                                    </div>
                                    <div class="flex items-start gap-2">
                                        <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        <p class="text-gray-600">Default rate limit is 60 requests per minute</p>
                                    </div>
                                    <div class="flex items-start gap-2">
                                        <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        <p class="text-gray-600">IP whitelisting is optional but recommended for production</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
