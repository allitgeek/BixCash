@extends('layouts.admin')

@section('title', 'Admin Settings - BixCash Admin')
@section('page-title', 'Admin Settings')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <h2 class="text-xl font-bold text-[#021c47] mb-1">Admin Settings</h2>
        <p class="text-sm text-gray-500">Configure administrative settings and preferences</p>
    </div>

    {{-- System Configuration --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-[#93db4d] flex items-center justify-center">
                    <svg class="w-5 h-5 text-[#021c47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-[#021c47]">System Configuration</h3>
                    <p class="text-sm text-gray-500">Active user criteria for profit sharing eligibility</p>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <form method="POST" action="{{ route('admin.settings.admin.criteria.update') }}">
                @csrf

                {{-- Criteria Form --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    {{-- Active Customer Criteria --}}
                    <div>
                        <label class="block text-sm font-medium text-[#021c47] mb-2">Active Customer Criteria</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-medium">Rs.</span>
                            <input type="text" id="active_customer_min_spending" name="active_customer_min_spending"
                                   value="{{ number_format($customerCriteria ?? 0, 0, '.', ',') }}"
                                   placeholder="30,000" oninput="formatCurrency(this)"
                                   class="w-full pl-12 pr-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all">
                        </div>
                        <p class="mt-1 text-xs text-gray-400">Minimum spending for active status</p>
                    </div>

                    {{-- Partner: Min Customers --}}
                    <div>
                        <label class="block text-sm font-medium text-[#021c47] mb-2">Partner: Min. Customers</label>
                        <input type="number" name="active_partner_min_customers" value="{{ $partnerMinCustomers ?? 0 }}" min="0"
                               placeholder="e.g., 10"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all">
                        <p class="mt-1 text-xs text-gray-400">Minimum number of customers</p>
                    </div>

                    {{-- Partner: Min Amount --}}
                    <div>
                        <label class="block text-sm font-medium text-[#021c47] mb-2">Partner: Min. Amount</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-medium">Rs.</span>
                            <input type="text" name="active_partner_min_amount"
                                   value="{{ number_format($partnerMinAmount ?? 0, 0, '.', ',') }}"
                                   placeholder="50,000" oninput="formatCurrency(this)"
                                   class="w-full pl-12 pr-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all">
                        </div>
                        <p class="mt-1 text-xs text-gray-400">Minimum transaction amount</p>
                    </div>
                </div>

                {{-- Info Message --}}
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg mb-6">
                    <p class="text-sm text-blue-800">
                        <strong>Note:</strong> Partners must meet <strong>BOTH</strong> minimum customers and minimum amount criteria to be considered active for profit sharing.
                    </p>
                </div>

                {{-- Customer Threshold Levels --}}
                <div class="pt-6 border-t border-gray-200">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-1 h-6 bg-[#021c47] rounded-full"></div>
                        <div>
                            <h4 class="text-base font-bold text-[#021c47]">Customer Threshold Levels</h4>
                            <p class="text-sm text-gray-500">Set minimum number of customers required for each tier level</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-4 mb-4">
                        @for($i = 1; $i <= 7; $i++)
                            <div>
                                <label class="block text-sm font-medium text-[#021c47] mb-2 text-center">Level {{ $i }}</label>
                                <input type="number" name="customer_threshold_level_{{ $i }}"
                                       value="{{ $customerThresholds[$i] ?? 0 }}" min="0"
                                       placeholder="{{ $i * 5 }}"
                                       class="w-full px-3 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all text-center">
                            </div>
                        @endfor
                    </div>

                    <div class="bg-purple-50 border-l-4 border-purple-400 p-4 rounded-r-lg">
                        <p class="text-sm text-purple-800">
                            <strong>Info:</strong> These thresholds set the capacity for each FIFO queue level in profit sharing. Users who meet criteria enter Level 1. When a level fills up, the oldest user graduates to the next level. Level 7 = most senior users.
                        </p>
                    </div>
                </div>

                {{-- Save Button --}}
                <div class="flex justify-end pt-6 mt-6 border-t border-gray-200">
                    <button type="submit" class="px-5 py-2.5 bg-[#021c47] text-white font-medium rounded-lg hover:bg-[#93db4d] hover:text-[#021c47] transition-all duration-200">
                        Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Coming Soon Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Security Settings --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-[#021c47] mb-1">Security Settings</h3>
                    <p class="text-sm text-gray-500 mb-4">Configure security policies and authentication methods</p>
                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-500">Coming Soon</span>
                </div>
            </div>
        </div>

        {{-- API & Integrations --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-yellow-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-[#021c47] mb-1">API & Integrations</h3>
                    <p class="text-sm text-gray-500 mb-4">Manage third-party integrations and API configurations</p>
                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-500">Coming Soon</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function formatCurrency(input) {
    let value = input.value.replace(/[^\d]/g, '');
    if (value) {
        value = parseInt(value).toLocaleString('en-US');
    }
    input.value = value;
}
</script>
@endpush
