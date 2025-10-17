@extends('layouts.admin')

@section('title', 'Admin Dashboard - BixCash')
@section('page-title', 'Dashboard')

@section('content')
    {{-- Stats Grid - Single Row Layout --}}
    <div class="grid grid-cols-7 gap-3 mb-6 overflow-x-auto">
        {{-- Total Users Card --}}
        <div class="group bg-white rounded-lg border border-gray-200/60 p-4 hover:border-blue-400 hover:shadow-lg transition-all duration-200 min-w-[160px]">
            <div class="flex flex-col items-center text-center space-y-2">
                <div class="w-11 h-11 rounded-full bg-blue-500/10 flex items-center justify-center text-blue-600 group-hover:bg-blue-500 group-hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $stats['total_users'] }}</h3>
                    <p class="text-xs text-gray-500 mt-1">Total Users</p>
                </div>
            </div>
        </div>

        {{-- Admin Users Card --}}
        <div class="group bg-white rounded-lg border border-gray-200/60 p-4 hover:border-purple-400 hover:shadow-lg transition-all duration-200 min-w-[160px]">
            <div class="flex flex-col items-center text-center space-y-2">
                <div class="w-11 h-11 rounded-full bg-purple-500/10 flex items-center justify-center text-purple-600 group-hover:bg-purple-500 group-hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $stats['admin_users'] }}</h3>
                    <p class="text-xs text-gray-500 mt-1">Admin Users</p>
                </div>
            </div>
        </div>

        {{-- Customers Card --}}
        <div class="group bg-white rounded-lg border border-gray-200/60 p-4 hover:border-green-400 hover:shadow-lg transition-all duration-200 min-w-[160px]">
            <div class="flex flex-col items-center text-center space-y-2">
                <div class="w-11 h-11 rounded-full bg-green-500/10 flex items-center justify-center text-green-600 group-hover:bg-green-500 group-hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $stats['customer_users'] }}</h3>
                    <p class="text-xs text-gray-500 mt-1">Customers</p>
                </div>
            </div>
        </div>

        {{-- Partners Card --}}
        <div class="group bg-white rounded-lg border border-gray-200/60 p-4 hover:border-orange-400 hover:shadow-lg transition-all duration-200 min-w-[160px]">
            <div class="flex flex-col items-center text-center space-y-2">
                <div class="w-11 h-11 rounded-full bg-orange-500/10 flex items-center justify-center text-orange-600 group-hover:bg-orange-500 group-hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $stats['partner_users'] }}</h3>
                    <p class="text-xs text-gray-500 mt-1">Partners</p>
                </div>
            </div>
        </div>

        {{-- Active Brands Card --}}
        <div class="group bg-white rounded-lg border border-gray-200/60 p-4 hover:border-indigo-400 hover:shadow-lg transition-all duration-200 min-w-[160px]">
            <div class="flex flex-col items-center text-center space-y-2">
                <div class="w-11 h-11 rounded-full bg-indigo-500/10 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-500 group-hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $stats['active_brands'] }}<span class="text-sm text-gray-400 ml-1">/{{ $stats['total_brands'] }}</span></h3>
                    <p class="text-xs text-gray-500 mt-1">Active Brands</p>
                </div>
            </div>
        </div>

        {{-- Active Categories Card --}}
        <div class="group bg-white rounded-lg border border-gray-200/60 p-4 hover:border-pink-400 hover:shadow-lg transition-all duration-200 min-w-[160px]">
            <div class="flex flex-col items-center text-center space-y-2">
                <div class="w-11 h-11 rounded-full bg-pink-500/10 flex items-center justify-center text-pink-600 group-hover:bg-pink-500 group-hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $stats['active_categories'] }}<span class="text-sm text-gray-400 ml-1">/{{ $stats['total_categories'] }}</span></h3>
                    <p class="text-xs text-gray-500 mt-1">Active Categories</p>
                </div>
            </div>
        </div>

        {{-- Active Slides Card --}}
        <div class="group bg-white rounded-lg border border-gray-200/60 p-4 hover:border-teal-400 hover:shadow-lg transition-all duration-200 min-w-[160px]">
            <div class="flex flex-col items-center text-center space-y-2">
                <div class="w-11 h-11 rounded-full bg-teal-500/10 flex items-center justify-center text-teal-600 group-hover:bg-teal-500 group-hover:text-white transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $stats['active_slides'] }}<span class="text-sm text-gray-400 ml-1">/{{ $stats['total_slides'] }}</span></h3>
                    <p class="text-xs text-gray-500 mt-1">Active Slides</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Activity Cards - 3 Column Layout --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        {{-- Recent Customers Card --}}
        <div class="bg-white rounded-xl border border-gray-200/60 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200/60 bg-gradient-to-r from-blue-50/50 to-transparent">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-blue-500 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-800">Recent Customers</h3>
                </div>
            </div>
            <div class="p-4">
                <ul class="space-y-2">
                    @forelse($recentCustomers as $customer)
                        <li class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors duration-150">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ $customer->name }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $customer->phone ?? $customer->email }}</p>
                            </div>
                        </li>
                    @empty
                        <li class="text-center py-8 text-gray-400 text-sm">
                            No customers yet
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>

        {{-- Recent Transactions Card --}}
        <div class="bg-white rounded-xl border border-gray-200/60 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200/60 bg-gradient-to-r from-green-50/50 to-transparent">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-green-500 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-800">Recent Transactions</h3>
                </div>
            </div>
            <div class="p-4">
                <ul class="space-y-2">
                    @forelse($recentTransactions as $transaction)
                        <li class="p-3 rounded-lg hover:bg-gray-50 transition-colors duration-150">
                            <div class="flex items-center justify-between mb-1">
                                <p class="text-xs text-gray-500">{{ $transaction->customer->name ?? 'N/A' }}</p>
                                <p class="text-sm font-bold text-green-600">${{ number_format($transaction->invoice_amount, 2) }}</p>
                            </div>
                            <p class="text-xs text-gray-400 truncate">→ {{ $transaction->partner->partnerProfile->business_name ?? 'N/A' }}</p>
                        </li>
                    @empty
                        <li class="text-center py-8 text-gray-400 text-sm">
                            No transactions yet
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>

        {{-- Recent Partners Card --}}
        <div class="bg-white rounded-xl border border-gray-200/60 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200/60 bg-gradient-to-r from-orange-50/50 to-transparent">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-orange-500 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-800">Recent Partners</h3>
                </div>
            </div>
            <div class="p-4">
                <ul class="space-y-2">
                    @forelse($recentPartners as $partner)
                        <li class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors duration-150">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ $partner->partnerProfile->business_name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500 mt-0.5 capitalize">{{ $partner->partnerProfile->business_type ?? 'N/A' }}</p>
                            </div>
                        </li>
                    @empty
                        <li class="text-center py-8 text-gray-400 text-sm">
                            No partners yet
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
@endsection
