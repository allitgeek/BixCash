@extends('layouts.admin')

@section('title', 'Add Integration - BixCash Admin')
@section('page-title', 'Add API Integration')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('admin.integrations.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-[#021c47] transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Integrations
            </a>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-[#021c47]">New Integration</h3>
                <p class="text-sm text-gray-500 mt-1">Create an API integration for an external partner.</p>
            </div>

            <form action="{{ route('admin.integrations.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <!-- Partner Selection -->
                <div>
                    <label for="partner_id" class="block text-sm font-medium text-gray-700 mb-1">Partner</label>
                    <select name="partner_id" id="partner_id" required
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#021c47] focus:ring-[#021c47] text-sm">
                        <option value="">Select a partner...</option>
                        @foreach($partners as $partner)
                            <option value="{{ $partner->id }}" {{ old('partner_id') == $partner->id ? 'selected' : '' }}>
                                {{ $partner->partnerProfile->business_name ?? $partner->name }} ({{ $partner->phone }})
                            </option>
                        @endforeach
                    </select>
                    @error('partner_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @if($partners->isEmpty())
                        <p class="mt-1 text-sm text-yellow-600">No approved partners available. All approved partners already have integrations, or there are no approved partners.</p>
                    @endif
                </div>

                <!-- Integration Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Integration Name</label>
                    <input type="text" name="name" id="name" required value="{{ old('name') }}"
                           placeholder="e.g., FreshBox"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#021c47] focus:ring-[#021c47] text-sm">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Allowed IPs -->
                <div>
                    <label for="allowed_ips" class="block text-sm font-medium text-gray-700 mb-1">Allowed IPs (optional)</label>
                    <textarea name="allowed_ips" id="allowed_ips" rows="3"
                              placeholder="Comma-separated IPs, e.g., 203.0.113.1, 198.51.100.2"
                              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#021c47] focus:ring-[#021c47] text-sm">{{ old('allowed_ips') }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">Leave blank to allow all IPs.</p>
                    @error('allowed_ips')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Rate Limit -->
                <div>
                    <label for="rate_limit_per_minute" class="block text-sm font-medium text-gray-700 mb-1">Rate Limit (requests/minute)</label>
                    <input type="number" name="rate_limit_per_minute" id="rate_limit_per_minute"
                           value="{{ old('rate_limit_per_minute', 60) }}" min="1" max="1000"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#021c47] focus:ring-[#021c47] text-sm">
                    @error('rate_limit_per_minute')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit -->
                <div class="flex items-center justify-end pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.integrations.index') }}"
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors mr-3">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-2 text-sm font-medium text-white bg-[#021c47] rounded-lg hover:bg-[#032d6b] transition-colors">
                        Create Integration
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
