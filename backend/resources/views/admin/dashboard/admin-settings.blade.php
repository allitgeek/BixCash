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

    {{-- Admin Sections Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- System Configuration --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 lg:col-span-2">
            <form method="POST" action="{{ route('admin.settings.admin.criteria.update') }}">
                @csrf

                {{-- Section Header --}}
                <div class="flex items-center mb-4 pb-3 border-b border-gray-200">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-8 w-8 rounded-lg bg-green-100">
                            <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-base font-semibold text-gray-900">System Configuration</h3>
                        <p class="text-xs text-gray-600">Active user criteria for profit sharing eligibility</p>
                    </div>
                </div>

                {{-- Compact Criteria Form --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    {{-- Active Customer Criteria --}}
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            Active Customer Criteria
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-700 text-sm font-semibold">Rs.</span>
                            <input type="text"
                                   id="active_customer_min_spending"
                                   name="active_customer_min_spending"
                                   value="{{ number_format($customerCriteria ?? 0, 0, '.', ',') }}"
                                   class="pl-16 w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                   placeholder="30,000"
                                   oninput="formatCurrency(this)">
                        </div>
                        <p class="text-xs text-gray-500">Minimum spending for active status</p>
                    </div>

                    {{-- Active Partner Criteria - Min Customers --}}
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            Partner: Min. Customers
                        </label>
                        <input type="number"
                               name="active_partner_min_customers"
                               value="{{ $partnerMinCustomers ?? 0 }}"
                               min="0"
                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                               placeholder="e.g., 10">
                        <p class="text-xs text-gray-500">Minimum number of customers</p>
                    </div>

                    {{-- Active Partner Criteria - Min Amount --}}
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            Partner: Min. Amount
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-700 text-sm font-semibold">Rs.</span>
                            <input type="text"
                                   name="active_partner_min_amount"
                                   value="{{ number_format($partnerMinAmount ?? 0, 0, '.', ',') }}"
                                   class="pl-16 w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                   placeholder="50,000"
                                   oninput="formatCurrency(this)">
                        </div>
                        <p class="text-xs text-gray-500">Minimum transaction amount</p>
                    </div>
                </div>

                {{-- Info Message --}}
                <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-xs text-blue-800">
                        <strong>Note:</strong> Partners must meet <strong>BOTH</strong> minimum customers and minimum amount criteria to be considered active for profit sharing.
                    </p>
                </div>

                {{-- Save Button --}}
                <div class="mt-4 pt-3 border-t border-gray-200 flex justify-end">
                    <button type="submit"
                            class="px-4 py-2 text-sm bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors duration-200">
                        Save Settings
                    </button>
                </div>
            </form>
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
