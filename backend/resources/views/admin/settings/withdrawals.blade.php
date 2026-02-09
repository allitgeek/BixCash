@extends('layouts.admin')

@section('title', 'Withdrawal Settings - BixCash Admin')
@section('page-title', 'Withdrawal Settings')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-[#021c47]">Withdrawal Settings</h1>
        <p class="text-gray-500 mt-1">Configure global limits for customer wallet withdrawals</p>
    </div>

    <!-- Info -->
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
        <h3 class="font-semibold text-blue-900 mb-2">💰 Withdrawal Limit Configuration</h3>
        <ul class="text-blue-700 text-sm ml-4 list-disc space-y-1">
            <li><strong>Min Amount:</strong> Minimum amount per withdrawal request</li>
            <li><strong>Max Per Withdrawal:</strong> Maximum amount for a single withdrawal</li>
            <li><strong>Max Per Day:</strong> Total withdrawal limit per customer per day</li>
            <li><strong>Max Per Month:</strong> Total withdrawal limit per customer per month</li>
            <li><strong>Min Gap Hours:</strong> Minimum time gap between withdrawal requests</li>
        </ul>
    </div>

    <form method="POST" action="{{ route('admin.settings.withdrawals.update') }}" class="space-y-6">
        @csrf

        <!-- Enable Toggle -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="enabled" value="1" {{ $settings->enabled ? 'checked' : '' }}
                       class="w-5 h-5 rounded border-gray-300 text-[#93db4d] focus:ring-[#93db4d]">
                <div>
                    <span class="text-lg font-semibold text-[#021c47]">Enable Withdrawals System-wide</span>
                    <p class="text-sm text-gray-500">Uncheck to temporarily disable all withdrawal requests</p>
                </div>
            </label>
        </div>

        <!-- Limits -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <h2 class="font-semibold text-[#021c47] mb-4 pb-2 border-b border-gray-200">Withdrawal Limits (PKR)</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Amount <span class="text-red-500">*</span></label>
                    <input type="number" name="min_amount" value="{{ old('min_amount', $settings->min_amount) }}" required min="0" step="0.01" placeholder="100"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20">
                    <p class="mt-1 text-xs text-gray-500">e.g., Rs. 100</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Maximum Per Withdrawal <span class="text-red-500">*</span></label>
                    <input type="number" name="max_per_withdrawal" value="{{ old('max_per_withdrawal', $settings->max_per_withdrawal) }}" required min="0" step="0.01" placeholder="50000"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Maximum Per Day <span class="text-red-500">*</span></label>
                    <input type="number" name="max_per_day" value="{{ old('max_per_day', $settings->max_per_day) }}" required min="0" step="0.01" placeholder="100000"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Maximum Per Month <span class="text-red-500">*</span></label>
                    <input type="number" name="max_per_month" value="{{ old('max_per_month', $settings->max_per_month) }}" required min="0" step="0.01" placeholder="500000"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20">
                </div>
            </div>
        </div>

        <!-- Time Gap -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <h2 class="font-semibold text-[#021c47] mb-4 pb-2 border-b border-gray-200">Time Gap Settings</h2>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Gap Between Withdrawals (Hours) <span class="text-red-500">*</span></label>
                <input type="number" name="min_gap_hours" value="{{ old('min_gap_hours', $settings->min_gap_hours) }}" required min="0" max="168" step="1" placeholder="6"
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20">
                <p class="mt-1 text-xs text-gray-500">0-168 hours (up to 7 days)</p>
            </div>
        </div>

        <!-- Message -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <h2 class="font-semibold text-[#021c47] mb-4 pb-2 border-b border-gray-200">Customer Communication</h2>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Processing Time Message</label>
                <textarea name="processing_message" rows="3" placeholder="e.g., Withdrawal requests are typically processed within 24-48 business hours."
                          class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20">{{ old('processing_message', $settings->processing_message) }}</textarea>
                <p class="mt-1 text-xs text-gray-500">Shown to customers on the wallet page</p>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-4">
            <button type="submit" class="px-6 py-2.5 bg-[#021c47] text-white rounded-lg hover:bg-[#93db4d] hover:text-[#021c47] transition-colors font-medium">💾 Save Settings</button>
            <a href="{{ route('admin.withdrawals.index') }}" class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">View Withdrawal Requests</a>
        </div>
    </form>
</div>
@endsection
