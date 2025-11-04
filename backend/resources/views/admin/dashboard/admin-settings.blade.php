@extends('layouts.admin')

@section('title', 'Admin Settings - BixCash Admin')
@section('page-title', 'Admin Settings')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Admin Settings</h2>
                <p class="text-sm text-gray-600">Configure administrative settings and preferences</p>
            </div>
        </div>
    </div>

    {{-- Active Criteria Settings Form --}}
    <form method="POST" action="{{ route('admin.settings.admin.criteria.update') }}" class="space-y-6">
        @csrf

        {{-- Active Customer Criteria --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-start mb-6">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-700">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">Active Customer Criteria</h3>
                    <p class="text-sm text-gray-600">Define the minimum spending threshold for customers to be considered active in profit sharing</p>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label for="active_customer_min_spending" class="block text-sm font-medium text-gray-700 mb-2">
                        Minimum Spending Amount
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Rs.</span>
                        <input type="text"
                               id="active_customer_min_spending"
                               name="active_customer_min_spending"
                               value="{{ number_format($customerCriteria ?? 0, 0, '.', ',') }}"
                               class="pl-12 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                               placeholder="30,000"
                               oninput="formatCurrency(this)">
                    </div>
                    <p class="mt-2 text-xs text-gray-500">Customers who have spent this amount or more will be eligible for profit distribution</p>
                </div>
            </div>
        </div>

        {{-- Active Partner Criteria --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-start mb-6">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-gradient-to-br from-purple-500 to-purple-700">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">Active Partner Criteria</h3>
                    <p class="text-sm text-gray-600">Choose the criteria type and set minimum threshold for partners to be considered active</p>
                </div>
            </div>

            <div class="space-y-5">
                {{-- Radio Button Group --}}
                <div class="space-y-3">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Criteria Type</label>

                    {{-- Option 1: Minimum Customers --}}
                    <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer transition-all duration-200 hover:border-purple-300 hover:bg-purple-50/50"
                           :class="criteriaType === 'customers' ? 'border-purple-500 bg-purple-50' : 'border-gray-200'"
                           x-data="{ criteriaType: '{{ $partnerCriteriaType ?? 'customers' }}' }">
                        <input type="radio"
                               name="active_partner_criteria_type"
                               value="customers"
                               x-model="criteriaType"
                               class="mt-1 h-4 w-4 text-purple-600 focus:ring-purple-500"
                               {{ ($partnerCriteriaType ?? 'customers') === 'customers' ? 'checked' : '' }}>
                        <div class="ml-3 flex-1">
                            <span class="block text-sm font-medium text-gray-900">Minimum Number of Customers</span>
                            <p class="text-xs text-gray-500 mt-1">Partners who have served this many customers</p>
                            <div class="mt-3" x-show="criteriaType === 'customers'">
                                <input type="number"
                                       name="active_partner_min_customers"
                                       value="{{ $partnerCriteria ?? 0 }}"
                                       min="0"
                                       x-bind:disabled="criteriaType !== 'customers'"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 disabled:bg-gray-100 disabled:cursor-not-allowed"
                                       placeholder="e.g., 10">
                            </div>
                        </div>
                    </label>

                    {{-- Option 2: Minimum Transaction Amount --}}
                    <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer transition-all duration-200 hover:border-purple-300 hover:bg-purple-50/50"
                           :class="criteriaType === 'amount' ? 'border-purple-500 bg-purple-50' : 'border-gray-200'"
                           x-data="{ criteriaType: '{{ $partnerCriteriaType ?? 'customers' }}' }">
                        <input type="radio"
                               name="active_partner_criteria_type"
                               value="amount"
                               x-model="criteriaType"
                               class="mt-1 h-4 w-4 text-purple-600 focus:ring-purple-500"
                               {{ ($partnerCriteriaType ?? 'customers') === 'amount' ? 'checked' : '' }}>
                        <div class="ml-3 flex-1">
                            <span class="block text-sm font-medium text-gray-900">Minimum Transaction Amount</span>
                            <p class="text-xs text-gray-500 mt-1">Total spending by partner's customers</p>
                            <div class="mt-3" x-show="criteriaType === 'amount'">
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Rs.</span>
                                    <input type="text"
                                           name="active_partner_min_amount"
                                           value="{{ number_format($partnerCriteria ?? 0, 0, '.', ',') }}"
                                           x-bind:disabled="criteriaType !== 'amount'"
                                           class="pl-12 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 disabled:bg-gray-100 disabled:cursor-not-allowed"
                                           placeholder="50,000"
                                           oninput="formatCurrency(this)">
                                </div>
                            </div>
                        </div>
                    </label>
                </div>

                <p class="text-xs text-gray-500 bg-gray-50 border border-gray-200 rounded-lg p-3">
                    <strong>Note:</strong> Partners meeting the selected threshold will be counted as "active" in dashboard statistics and profit sharing calculations.
                </p>
            </div>
        </div>

        {{-- Save Button --}}
        <div class="flex justify-end">
            <button type="submit"
                    class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 transform hover:-translate-y-0.5">
                <svg class="inline-block w-5 h-5 mr-2 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Save Criteria Settings
            </button>
        </div>
    </form>

    {{-- Placeholder Sections for Future Implementation --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">

        {{-- System Configuration --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-green-100">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">System Configuration</h3>
                    <p class="text-sm text-gray-600">Manage system-wide settings and configurations</p>
                    <div class="mt-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                            Coming Soon
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Security Settings --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-red-100">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Security Settings</h3>
                    <p class="text-sm text-gray-600">Configure security policies and authentication methods</p>
                    <div class="mt-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                            Coming Soon
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- API & Integrations --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-yellow-100">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">API & Integrations</h3>
                    <p class="text-sm text-gray-600">Manage third-party integrations and API configurations</p>
                    <div class="mt-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                            Coming Soon
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Format currency input with commas
function formatCurrency(input) {
    // Remove all non-digit characters
    let value = input.value.replace(/[^\d]/g, '');

    // Format with commas
    if (value) {
        value = parseInt(value).toLocaleString('en-US');
    }

    input.value = value;
}
</script>
@endpush
