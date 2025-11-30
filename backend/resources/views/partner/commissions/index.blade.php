<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#021c47">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Commissions - BixCash Partner</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><circle cx='50' cy='50' r='45' fill='%2376d37a'/><text x='50' y='68' font-size='55' font-weight='bold' fill='white' text-anchor='middle' font-family='Arial'>B</text></svg>">
    @vite(['resources/css/app.css'])

    {{-- Inter Font from Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            font-feature-settings: 'cv11', 'ss01';
            font-optical-sizing: auto;
        }
    </style>
</head>
<body class="bg-neutral-50 min-h-screen pb-32 pt-0 px-0" style="margin: 0;">

    {{-- Header --}}
    <header class="bg-white/80 backdrop-blur-xl shadow-lg shadow-blue-900/5 border-b border-gray-200/60 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('partner.dashboard') }}" class="text-neutral-500 hover:text-blue-600 hover:scale-[1.02] transition-[transform,colors] duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <h1 class="text-xl font-semibold text-neutral-900">Commission History</h1>
            </div>
        </div>
    </header>

    {{-- Commission Stats Cards --}}
    <div class="max-w-7xl mx-auto px-4 mt-6">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Commission Rate --}}
            <div class="stat-card bg-white/90 backdrop-blur-sm border border-neutral-200 rounded-xl p-4 hover:border-purple-300 hover:shadow-md hover:scale-[1.02] transition-[transform,shadow,border-color] duration-200">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-xs font-medium text-neutral-500 uppercase tracking-wide mb-1">Commission Rate</p>
                <p class="text-2xl font-semibold text-neutral-900">{{ number_format($commissionRate, 2) }}%</p>
            </div>

            {{-- Total Owed --}}
            <div class="stat-card bg-white/90 backdrop-blur-sm border border-neutral-200 rounded-xl p-4 hover:border-pink-300 hover:shadow-md hover:scale-[1.02] transition-[transform,shadow,border-color] duration-200">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-8 h-8 rounded-lg bg-pink-50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-pink-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-xs font-medium text-neutral-500 uppercase tracking-wide mb-1">Total Owed</p>
                <p class="text-2xl font-semibold text-neutral-900">Rs {{ number_format($totalCommissionOwed, 0) }}</p>
            </div>

            {{-- Total Paid --}}
            <div class="stat-card bg-white/90 backdrop-blur-sm border border-neutral-200 rounded-xl p-4 hover:border-cyan-300 hover:shadow-md hover:scale-[1.02] transition-[transform,shadow,border-color] duration-200">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-8 h-8 rounded-lg bg-cyan-50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-cyan-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-xs font-medium text-neutral-500 uppercase tracking-wide mb-1">Total Paid</p>
                <p class="text-2xl font-semibold text-neutral-900">Rs {{ number_format($totalPaid, 0) }}</p>
            </div>

            {{-- Outstanding --}}
            <div class="stat-card bg-white/90 backdrop-blur-sm border border-neutral-200 rounded-xl p-4 hover:border-yellow-300 hover:shadow-md hover:scale-[1.02] transition-[transform,shadow,border-color] duration-200">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-8 h-8 rounded-lg bg-yellow-50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-xs font-medium text-neutral-500 uppercase tracking-wide mb-1">Outstanding</p>
                <p class="text-2xl font-semibold text-neutral-900">Rs {{ number_format($totalOutstanding, 0) }}</p>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <main class="page-content max-w-7xl mx-auto px-4 mt-6">

        {{-- Outstanding Alert --}}
        @if($totalOutstanding > 0)
        <div class="bg-yellow-50/80 rounded-xl border-l-4 border-yellow-600 p-4 sm:p-5 mb-6 shadow-sm">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-lg bg-yellow-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-yellow-900 mb-2">Outstanding Commission</p>
                    <p class="text-sm text-yellow-800 leading-relaxed">
                        You have Rs {{ number_format($totalOutstanding, 0) }} in outstanding commission.
                        This amount represents commission you owe to BixCash based on your transactions.
                        You can continue withdrawing from your wallet - this debt is tracked separately.
                    </p>
                </div>
            </div>
        </div>
        @endif

        {{-- Commission Ledgers Table --}}
        <div class="bg-white rounded-xl border border-gray-200/60 shadow-lg shadow-blue-900/5 overflow-hidden mb-6">
            <div class="px-5 py-4 border-b border-gray-200/60 bg-gradient-to-r from-blue-50/70 via-blue-900/5 to-transparent">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-600 to-blue-900 flex items-center justify-center shadow-sm">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                        </svg>
                    </div>
                    <h2 class="text-base font-bold bg-gradient-to-r from-gray-800 to-blue-900 bg-clip-text text-transparent">Commission by Period</h2>
                </div>
            </div>

            <div class="overflow-x-auto">
                @if($ledgers->count() > 0)
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-gray-200 bg-gray-50">
                            <th class="text-left text-xs text-gray-500 uppercase tracking-wide pb-3 pt-3 px-5">Period</th>
                            <th class="text-left text-xs text-gray-500 uppercase tracking-wide pb-3 pt-3 px-3">Rate</th>
                            <th class="text-left text-xs text-gray-500 uppercase tracking-wide pb-3 pt-3 px-3">Transactions</th>
                            <th class="text-left text-xs text-gray-500 uppercase tracking-wide pb-3 pt-3 px-3">Invoice Total</th>
                            <th class="text-left text-xs text-gray-500 uppercase tracking-wide pb-3 pt-3 px-3">Commission</th>
                            <th class="text-left text-xs text-gray-500 uppercase tracking-wide pb-3 pt-3 px-3">Paid</th>
                            <th class="text-left text-xs text-gray-500 uppercase tracking-wide pb-3 pt-3 px-3">Outstanding</th>
                            <th class="text-left text-xs text-gray-500 uppercase tracking-wide pb-3 pt-3 px-3">Status</th>
                            <th class="text-left text-xs text-gray-500 uppercase tracking-wide pb-3 pt-3 px-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach($ledgers as $ledger)
                        <tr class="ledger-row border-b border-gray-100 hover:bg-blue-50/50 transition-colors">
                            <td class="py-4 px-5">
                                <strong class="text-gray-800">{{ $ledger->formatted_period }}</strong>
                            </td>
                            <td class="py-4 px-3">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                    {{ number_format($ledger->commission_rate_used, 2) }}%
                                </span>
                            </td>
                            <td class="py-4 px-3 text-gray-600">{{ number_format($ledger->total_transactions) }}</td>
                            <td class="py-4 px-3 text-gray-600">Rs {{ number_format($ledger->total_invoice_amount, 0) }}</td>
                            <td class="py-4 px-3">
                                <strong class="text-gray-800">Rs {{ number_format($ledger->commission_owed, 0) }}</strong>
                            </td>
                            <td class="py-4 px-3 text-gray-600">Rs {{ number_format($ledger->amount_paid, 0) }}</td>
                            <td class="py-4 px-3">
                                @if($ledger->amount_outstanding > 0)
                                    <strong class="text-pink-600">Rs {{ number_format($ledger->amount_outstanding, 0) }}</strong>
                                @else
                                    <span class="text-green-600 font-medium">Settled</span>
                                @endif
                            </td>
                            <td class="py-4 px-3">
                                @if($ledger->status === 'settled')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Settled</span>
                                @elseif($ledger->status === 'partial')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">Partial</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">Pending</span>
                                @endif
                            </td>
                            <td class="py-4 px-3">
                                <div class="flex gap-2">
                                    <a href="{{ route('partner.commissions.show', $ledger->id) }}"
                                       class="px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 hover:scale-[1.05] transition-[transform,background-color] duration-200 active:scale-95">
                                        Details
                                    </a>
                                    <a href="{{ route('partner.commissions.invoice', $ledger->id) }}"
                                       class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 hover:scale-[1.05] transition-[transform,background-color] duration-200 active:scale-95"
                                       target="_blank">
                                        Invoice
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <p class="text-gray-500 mb-2">No commission records yet</p>
                    <p class="text-sm text-gray-400">Commission is calculated monthly based on your confirmed transactions</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Recent Settlements Table --}}
        @if($settlements->count() > 0)
        <div class="bg-white rounded-xl border border-gray-200/60 shadow-lg shadow-blue-900/5 overflow-hidden mb-6">
            <div class="px-5 py-4 border-b border-gray-200/60 bg-gradient-to-r from-green-50/70 via-green-900/5 to-transparent">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-green-600 to-green-900 flex items-center justify-center shadow-sm">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                            <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h2 class="text-base font-bold bg-gradient-to-r from-gray-800 to-green-900 bg-clip-text text-transparent">Recent Settlements</h2>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-gray-200 bg-gray-50">
                            <th class="text-left text-xs text-gray-500 uppercase tracking-wide pb-3 pt-3 px-5">Date</th>
                            <th class="text-left text-xs text-gray-500 uppercase tracking-wide pb-3 pt-3 px-3">Period</th>
                            <th class="text-left text-xs text-gray-500 uppercase tracking-wide pb-3 pt-3 px-3">Amount</th>
                            <th class="text-left text-xs text-gray-500 uppercase tracking-wide pb-3 pt-3 px-3">Method</th>
                            <th class="text-left text-xs text-gray-500 uppercase tracking-wide pb-3 pt-3 px-3">Reference</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach($settlements as $settlement)
                        <tr class="settlement-row border-b border-gray-100 hover:bg-green-50/30 transition-colors">
                            <td class="py-4 px-5">
                                <div class="text-gray-800 font-medium">{{ $settlement->processed_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $settlement->processed_at->format('h:i A') }}</div>
                            </td>
                            <td class="py-4 px-3 text-gray-600">{{ $settlement->ledger->formatted_period }}</td>
                            <td class="py-4 px-3">
                                <strong class="text-cyan-600">Rs {{ number_format($settlement->amount_settled, 0) }}</strong>
                            </td>
                            <td class="py-4 px-3">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                    {{ $settlement->formatted_payment_method }}
                                </span>
                            </td>
                            <td class="py-4 px-3 text-gray-600">{{ $settlement->settlement_reference ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </main>

    {{-- Bottom Navigation --}}
    <nav class="fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-xl shadow-lg shadow-blue-900/10 border-t border-gray-200/60 z-50">
        <div class="grid grid-cols-5 max-w-7xl mx-auto">
            {{-- Home --}}
            <a href="{{ route('partner.dashboard') }}" class="flex flex-col items-center justify-center py-3 px-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50/50 transition-all duration-200 active:scale-95">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="text-xs font-medium">Home</span>
            </a>

            {{-- Transactions --}}
            <a href="{{ route('partner.transactions') }}" class="flex flex-col items-center justify-center py-3 px-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50/50 transition-all duration-200 active:scale-95">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <span class="text-xs font-medium">Trans</span>
            </a>

            {{-- Wallet --}}
            <a href="{{ route('partner.wallet') }}" class="flex flex-col items-center justify-center py-3 px-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50/50 transition-all duration-200 active:scale-95">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
                <span class="text-xs font-medium">Wallet</span>
            </a>

            {{-- Commissions (Active) --}}
            <a href="{{ route('partner.commissions') }}" class="flex flex-col items-center justify-center py-3 px-2 text-white bg-gradient-to-r from-blue-600 to-blue-900 border-t-2 border-blue-500 transition-all duration-200 active:scale-95">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-xs font-bold">Comm</span>
            </a>

            {{-- Profile --}}
            <a href="{{ route('partner.profile') }}" class="flex flex-col items-center justify-center py-3 px-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50/50 transition-all duration-200 active:scale-95">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="text-xs font-medium">Profile</span>
            </a>
        </div>
    </nav>

    {{-- Animations --}}
    <style>
        /* Page entrance animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Fade in animation for rows */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Apply animations */
        .page-content {
            animation: fadeInUp 0.6s ease-out both;
        }

        .stat-card {
            animation: fadeInUp 0.6s ease-out both;
        }
        .stat-card:nth-child(1) { animation-delay: 0.1s; }
        .stat-card:nth-child(2) { animation-delay: 0.2s; }
        .stat-card:nth-child(3) { animation-delay: 0.3s; }
        .stat-card:nth-child(4) { animation-delay: 0.4s; }

        .ledger-row {
            animation: fadeIn 0.4s ease-out both;
        }
        .ledger-row:nth-child(1) { animation-delay: 0.1s; }
        .ledger-row:nth-child(2) { animation-delay: 0.15s; }
        .ledger-row:nth-child(3) { animation-delay: 0.2s; }
        .ledger-row:nth-child(4) { animation-delay: 0.25s; }
        .ledger-row:nth-child(5) { animation-delay: 0.3s; }

        .settlement-row {
            animation: fadeIn 0.4s ease-out both;
        }
        .settlement-row:nth-child(1) { animation-delay: 0.1s; }
        .settlement-row:nth-child(2) { animation-delay: 0.15s; }
        .settlement-row:nth-child(3) { animation-delay: 0.2s; }
        .settlement-row:nth-child(4) { animation-delay: 0.25s; }
        .settlement-row:nth-child(5) { animation-delay: 0.3s; }
    </style>

</body>
</html>
