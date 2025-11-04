@extends('layouts.admin')

@section('title', 'Admin Dashboard - BixCash')
@section('page-title', 'Dashboard')

@section('content')
    {{-- Compact Stats Grid - Responsive Layout --}}
    {{-- Mobile: 2 columns | Tablet: 3 columns | Desktop: 6 columns Grid --}}
    <div class="mb-6">
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-3">
        {{-- Total Users --}}
        <div class="group relative bg-gradient-to-br from-blue-50/30 to-transparent rounded-xl border border-gray-200/60 hover:border-blue-600 hover:shadow-lg hover:shadow-blue-600/10 transition-all duration-200 overflow-hidden">
            <div class="hidden sm:block absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-blue-500 to-blue-700"></div>
            <div class="flex flex-col sm:flex-row items-center sm:gap-3 gap-2 p-3 sm:p-4 sm:pl-5 text-center sm:text-left">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-gradient-to-br from-blue-500/20 to-blue-600/30 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform duration-200">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0 w-full sm:w-auto">
                    <h3 class="text-xl sm:text-2xl font-black text-gray-900 leading-none">{{ $stats['total_users'] }}</h3>
                    <p class="text-xs font-semibold text-gray-500 uppercase mt-1">Total Users</p>
                </div>
            </div>
        </div>

        {{-- Admin Users --}}
        <div class="group relative bg-gradient-to-br from-purple-50/30 to-transparent rounded-xl border border-gray-200/60 hover:border-purple-600 hover:shadow-lg hover:shadow-purple-600/10 transition-all duration-200 overflow-hidden">
            <div class="hidden sm:block absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-purple-500 to-purple-700"></div>
            <div class="flex flex-col sm:flex-row items-center sm:gap-3 gap-2 p-3 sm:p-4 sm:pl-5 text-center sm:text-left">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-gradient-to-br from-purple-500/20 to-purple-600/30 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform duration-200">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0 w-full sm:w-auto">
                    <h3 class="text-xl sm:text-2xl font-black text-gray-900 leading-none">{{ $stats['admin_users'] }}</h3>
                    <p class="text-xs font-semibold text-gray-500 uppercase mt-1">Admins</p>
                </div>
            </div>
        </div>

        {{-- Customers --}}
        <div class="group relative bg-gradient-to-br from-green-50/30 to-transparent rounded-xl border border-gray-200/60 hover:border-green-600 hover:shadow-lg hover:shadow-green-600/10 transition-all duration-200 overflow-hidden">
            <div class="hidden sm:block absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-green-500 to-green-700"></div>
            <div class="flex flex-col sm:flex-row items-center sm:gap-3 gap-2 p-3 sm:p-4 sm:pl-5 text-center sm:text-left">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-gradient-to-br from-green-500/20 to-green-600/30 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform duration-200">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0 w-full sm:w-auto">
                    <h3 class="text-xl sm:text-2xl font-black text-gray-900 leading-none">{{ $stats['customer_users'] }}</h3>
                    <p class="text-xs font-semibold text-gray-500 uppercase mt-1">Customers</p>
                </div>
            </div>
        </div>

        {{-- Partners --}}
        <div class="group relative bg-gradient-to-br from-orange-50/30 to-transparent rounded-xl border border-gray-200/60 hover:border-orange-600 hover:shadow-lg hover:shadow-orange-600/10 transition-all duration-200 overflow-hidden">
            <div class="hidden sm:block absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-orange-500 to-orange-700"></div>
            <div class="flex flex-col sm:flex-row items-center sm:gap-3 gap-2 p-3 sm:p-4 sm:pl-5 text-center sm:text-left">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-gradient-to-br from-orange-500/20 to-orange-600/30 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform duration-200">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0 w-full sm:w-auto">
                    <h3 class="text-xl sm:text-2xl font-black text-gray-900 leading-none">{{ $stats['partner_users'] }}</h3>
                    <p class="text-xs font-semibold text-gray-500 uppercase mt-1">Partners</p>
                </div>
            </div>
        </div>

        {{-- Active Brands --}}
        <div class="group relative bg-gradient-to-br from-indigo-50/30 to-transparent rounded-xl border border-gray-200/60 hover:border-indigo-600 hover:shadow-lg hover:shadow-indigo-600/10 transition-all duration-200 overflow-hidden">
            <div class="hidden sm:block absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-indigo-500 to-indigo-700"></div>
            <div class="flex flex-col sm:flex-row items-center sm:gap-3 gap-2 p-3 sm:p-4 sm:pl-5 text-center sm:text-left">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-gradient-to-br from-indigo-500/20 to-indigo-600/30 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform duration-200">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0 w-full sm:w-auto">
                    <h3 class="text-xl sm:text-2xl font-black text-gray-900 leading-none">
                        {{ $stats['active_brands'] }}<span class="text-sm sm:text-base text-gray-400 font-normal">/{{ $stats['total_brands'] }}</span>
                    </h3>
                    <p class="text-xs font-semibold text-gray-500 uppercase mt-1">Brands</p>
                </div>
            </div>
        </div>

        {{-- Active Categories --}}
        <div class="group relative bg-gradient-to-br from-pink-50/30 to-transparent rounded-xl border border-gray-200/60 hover:border-pink-600 hover:shadow-lg hover:shadow-pink-600/10 transition-all duration-200 overflow-hidden">
            <div class="hidden sm:block absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-pink-500 to-pink-700"></div>
            <div class="flex flex-col sm:flex-row items-center sm:gap-3 gap-2 p-3 sm:p-4 sm:pl-5 text-center sm:text-left">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-gradient-to-br from-pink-500/20 to-pink-600/30 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform duration-200">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0 w-full sm:w-auto">
                    <h3 class="text-xl sm:text-2xl font-black text-gray-900 leading-none">
                        {{ $stats['active_categories'] }}<span class="text-sm sm:text-base text-gray-400 font-normal">/{{ $stats['total_categories'] }}</span>
                    </h3>
                    <p class="text-xs font-semibold text-gray-500 uppercase mt-1">Categories</p>
                </div>
            </div>
        </div>

        </div>
    </div>

    {{-- Recent Activity Cards - 3 Column Layout with Navy Accents --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
        {{-- Recent Customers Card --}}
        <div class="bg-white rounded-xl border border-gray-200/60 shadow-sm overflow-hidden hover:border-blue-800/40 hover:shadow-md hover:shadow-blue-900/5 transition-all duration-200">
            <div class="px-5 py-4 border-b border-gray-200/60 bg-gradient-to-r from-blue-50/70 via-blue-900/5 to-transparent">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-600 to-blue-900 flex items-center justify-center shadow-sm shadow-blue-900/20">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold bg-gradient-to-r from-gray-800 to-blue-900 bg-clip-text text-transparent">Recent Customers</h3>
                </div>
            </div>
            <div class="p-4">
                <ul class="space-y-2">
                    @forelse($recentCustomers as $customer)
                        <li class="flex items-center justify-between p-3 rounded-lg hover:bg-blue-50/50 transition-colors duration-150">
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
        <div class="bg-white rounded-xl border border-gray-200/60 shadow-sm overflow-hidden hover:border-blue-800/40 hover:shadow-md hover:shadow-blue-900/5 transition-all duration-200">
            <div class="px-5 py-4 border-b border-gray-200/60 bg-gradient-to-r from-green-50/70 via-blue-900/5 to-transparent">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-green-600 to-blue-900 flex items-center justify-center shadow-sm shadow-blue-900/20">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold bg-gradient-to-r from-gray-800 to-blue-900 bg-clip-text text-transparent">Recent Transactions</h3>
                </div>
            </div>
            <div class="p-4">
                <ul class="space-y-2">
                    @forelse($recentTransactions as $transaction)
                        <li class="p-3 rounded-lg hover:bg-blue-50/50 transition-colors duration-150">
                            <div class="flex items-center justify-between mb-1">
                                <p class="text-xs text-gray-500">{{ $transaction->customer->name ?? 'N/A' }}</p>
                                <p class="text-sm font-bold text-green-600">Rs. {{ number_format($transaction->invoice_amount, 0) }}</p>
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
        <div class="bg-white rounded-xl border border-gray-200/60 shadow-sm overflow-hidden hover:border-blue-800/40 hover:shadow-md hover:shadow-blue-900/5 transition-all duration-200">
            <div class="px-5 py-4 border-b border-gray-200/60 bg-gradient-to-r from-orange-50/70 via-blue-900/5 to-transparent">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-orange-600 to-blue-900 flex items-center justify-center shadow-sm shadow-blue-900/20">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold bg-gradient-to-r from-gray-800 to-blue-900 bg-clip-text text-transparent">Recent Partners</h3>
                </div>
            </div>
            <div class="p-4">
                <ul class="space-y-2">
                    @forelse($recentPartners as $partner)
                        <li class="flex items-center justify-between p-3 rounded-lg hover:bg-blue-50/50 transition-colors duration-150">
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

    {{-- 7-Day Trend Charts with Navy Accents (Compact & Enhanced) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        {{-- Customer Registrations Chart --}}
        <div class="bg-white rounded-xl border border-gray-200/60 shadow-sm overflow-hidden p-4 hover:border-blue-800/40 hover:shadow-lg hover:shadow-blue-600/20 transition-all duration-200">
            <h4 class="text-sm font-semibold bg-gradient-to-r from-gray-700 to-blue-900 bg-clip-text text-transparent mb-3">Customer Registrations (7 Days)</h4>
            <canvas id="customerChart" height="80"></canvas>
        </div>

        {{-- Transactions Chart --}}
        <div class="bg-white rounded-xl border border-gray-200/60 shadow-sm overflow-hidden p-4 hover:border-green-600/40 hover:shadow-lg hover:shadow-green-600/20 transition-all duration-200">
            <h4 class="text-sm font-semibold bg-gradient-to-r from-gray-700 to-green-700 bg-clip-text text-transparent mb-3">Transaction Volume (7 Days)</h4>
            <canvas id="transactionChart" height="80"></canvas>
        </div>

        {{-- Partner Registrations Chart --}}
        <div class="bg-white rounded-xl border border-gray-200/60 shadow-sm overflow-hidden p-4 hover:border-orange-600/40 hover:shadow-lg hover:shadow-orange-600/20 transition-all duration-200">
            <h4 class="text-sm font-semibold bg-gradient-to-r from-gray-700 to-orange-700 bg-clip-text text-transparent mb-3">Partner Registrations (7 Days)</h4>
            <canvas id="partnerChart" height="80"></canvas>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Chart configuration
    const chartLabels = @json($chartLabels);
    const customerData = @json($customerChartData);
    const transactionData = @json($transactionChartData);
    const partnerData = @json($partnerChartData);

    // Common chart options
    const commonOptions = {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: 'rgba(30, 58, 138, 0.95)',
                padding: 12,
                cornerRadius: 8,
                titleFont: {
                    size: 13
                },
                bodyFont: {
                    size: 12
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    precision: 0,
                    font: {
                        size: 11
                    },
                    color: 'rgba(30, 58, 138, 0.6)'
                },
                grid: {
                    color: 'rgba(30, 58, 138, 0.08)'
                }
            },
            x: {
                ticks: {
                    font: {
                        size: 11
                    },
                    color: 'rgba(30, 58, 138, 0.6)'
                },
                grid: {
                    display: false
                }
            }
        }
    };

    // Customer Chart - Navy blue with gradient fill
    const customerCtx = document.getElementById('customerChart').getContext('2d');
    const customerGradient = customerCtx.createLinearGradient(0, 0, 0, 200);
    customerGradient.addColorStop(0, 'rgba(30, 64, 175, 0.4)');
    customerGradient.addColorStop(0.5, 'rgba(30, 64, 175, 0.2)');
    customerGradient.addColorStop(1, 'rgba(30, 64, 175, 0.05)');

    new Chart(customerCtx, {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Customers',
                data: customerData,
                borderColor: 'rgb(30, 64, 175)',
                backgroundColor: customerGradient,
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: 'rgb(30, 64, 175)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
                pointHoverBackgroundColor: 'rgb(30, 58, 138)',
                pointHoverBorderWidth: 3
            }]
        },
        options: commonOptions
    });

    // Transaction Chart - Green gradient bars
    const transactionCtx = document.getElementById('transactionChart').getContext('2d');
    const transactionGradient = transactionCtx.createLinearGradient(0, 0, 0, 200);
    transactionGradient.addColorStop(0, 'rgba(34, 197, 94, 0.9)');
    transactionGradient.addColorStop(0.5, 'rgba(118, 211, 122, 0.8)');
    transactionGradient.addColorStop(1, 'rgba(147, 219, 77, 0.6)');

    new Chart(transactionCtx, {
        type: 'bar',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Amount (Rs.)',
                data: transactionData,
                backgroundColor: transactionGradient,
                borderColor: 'rgba(34, 197, 94, 0.3)',
                borderWidth: 0,
                borderRadius: 8,
                hoverBackgroundColor: 'rgba(30, 64, 175, 0.85)',
                hoverBorderColor: 'rgba(30, 64, 175, 1)',
                hoverBorderWidth: 2
            }]
        },
        options: {
            ...commonOptions,
            plugins: {
                ...commonOptions.plugins,
                tooltip: {
                    ...commonOptions.plugins.tooltip,
                    callbacks: {
                        label: function(context) {
                            return 'Amount: Rs. ' + context.parsed.y.toFixed(2);
                        }
                    }
                }
            }
        }
    });

    // Partner Chart - Orange gradient with accent
    const partnerCtx = document.getElementById('partnerChart').getContext('2d');
    const partnerGradient = partnerCtx.createLinearGradient(0, 0, 0, 200);
    partnerGradient.addColorStop(0, 'rgba(249, 115, 22, 0.4)');
    partnerGradient.addColorStop(0.5, 'rgba(251, 146, 60, 0.25)');
    partnerGradient.addColorStop(1, 'rgba(253, 186, 116, 0.1)');

    new Chart(partnerCtx, {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Partners',
                data: partnerData,
                borderColor: 'rgb(249, 115, 22)',
                backgroundColor: partnerGradient,
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: 'rgb(249, 115, 22)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
                pointHoverBackgroundColor: 'rgb(30, 58, 138)',
                pointHoverBorderWidth: 3
            }]
        },
        options: commonOptions
    });
</script>
@endpush
