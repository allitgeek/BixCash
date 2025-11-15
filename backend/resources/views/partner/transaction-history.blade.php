<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#021c47">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Transaction History - BixCash</title>
    @vite(['resources/css/app.css'])
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-neutral-50 min-h-screen pb-32 pt-0 px-0" style="margin: 0;">

    {{-- Clean Header --}}
    <header class="bg-white border-b border-neutral-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 py-3">
            {{-- Top Row: Logo + Title + Back Button --}}
            <div class="flex items-center justify-between gap-3 mb-3">
                <div class="flex items-center gap-3 flex-1 min-w-0">
                    {{-- Partner Brand Logo --}}
                    <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-xl bg-white flex items-center justify-center shadow-md flex-shrink-0 overflow-hidden border-2 border-gray-200">
                        @if($partnerProfile->logo)
                        <img src="{{ asset('storage/' . $partnerProfile->logo) }}" alt="Logo" class="w-full h-full object-cover rounded-xl">
                        @else
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        @endif
                    </div>
                    {{-- Title --}}
                    <div class="flex-1 min-w-0">
                        <h1 class="text-base sm:text-lg font-bold bg-gradient-to-r from-blue-900 to-blue-700 bg-clip-text text-transparent truncate">
                            Transaction History
                        </h1>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $partnerProfile->business_name }}</p>
                    </div>
                </div>
                {{-- Back Button --}}
                <a href="{{ route('partner.dashboard') }}" class="px-3 sm:px-4 py-2 rounded-full bg-gradient-to-r from-blue-600 to-blue-700 text-white text-xs font-semibold shadow-md shadow-blue-500/30 hover:shadow-lg hover:shadow-blue-500/40 hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-1.5 flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span class="hidden sm:inline">Back</span>
                </a>
            </div>

            {{-- Stats Row: Clean Minimal Cards --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                {{-- Total Transactions --}}
                <div class="bg-white border border-neutral-200 rounded-lg p-3 hover:border-neutral-300 hover:shadow-sm transition-all duration-200">
                    <p class="text-xs text-neutral-500 font-medium mb-1">Total</p>
                    <p class="text-xl font-semibold text-neutral-900">{{ $stats['total_transactions'] }}</p>
                </div>

                {{-- Total Amount --}}
                <div class="bg-white border border-neutral-200 rounded-lg p-3 hover:border-emerald-300 hover:shadow-sm transition-all duration-200">
                    <p class="text-xs text-neutral-500 font-medium mb-1">Amount</p>
                    <p class="text-base font-semibold text-neutral-900">Rs {{ number_format($stats['total_amount'], 0) }}</p>
                </div>

                {{-- Confirmed --}}
                <div class="bg-white border border-neutral-200 rounded-lg p-3 hover:border-blue-300 hover:shadow-sm transition-all duration-200">
                    <p class="text-xs text-neutral-500 font-medium mb-1">Confirmed</p>
                    <p class="text-xl font-semibold text-neutral-900">{{ $stats['confirmed_count'] }}</p>
                </div>

                {{-- Pending --}}
                <div class="bg-white border border-neutral-200 rounded-lg p-3 hover:border-amber-300 hover:shadow-sm transition-all duration-200">
                    <p class="text-xs text-neutral-500 font-medium mb-1">Pending</p>
                    <p class="text-xl font-semibold text-neutral-900">{{ $stats['pending_count'] }}</p>
                </div>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <div class="max-w-7xl mx-auto px-4 py-6">

        {{-- Transaction List Card with Navy Accents --}}
        <div class="bg-white rounded-xl border border-gray-200/60 shadow-sm overflow-hidden hover:border-blue-800/40 hover:shadow-md hover:shadow-blue-900/5 transition-all duration-200">

            {{-- Card Header with Navy Gradient --}}
            <div class="px-5 py-4 border-b border-gray-200/60 bg-gradient-to-r from-blue-50/70 via-blue-900/5 to-transparent">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-600 to-blue-900 flex items-center justify-center shadow-sm shadow-blue-900/20">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold bg-gradient-to-r from-gray-800 to-blue-900 bg-clip-text text-transparent">All Transactions</h3>
                </div>
            </div>

            {{-- Transaction Items --}}
            <div class="divide-y divide-gray-200/60">
                @forelse($transactions as $transaction)
                <div class="group p-4 hover:bg-blue-50/50 transition-colors duration-150">
                    <div class="flex items-center justify-between gap-4">
                        {{-- Transaction Info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <h4 class="text-sm font-semibold text-gray-900 truncate">{{ $transaction->customer->name }}</h4>
                                @if($transaction->status === 'confirmed')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-gradient-to-r bg-green-50 text-green-700 border border-green-200/60 shadow-sm shadow-green-900/5">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    Confirmed
                                </span>
                                @elseif($transaction->status === 'pending_confirmation')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-gradient-to-r from-yellow-50 to-orange-50 text-orange-700 border border-orange-200/60 shadow-sm shadow-orange-900/5">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                    </svg>
                                    Pending
                                </span>
                                @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-gradient-to-r bg-gray-50 text-gray-700 border border-gray-200/60 shadow-sm">
                                    {{ ucfirst(str_replace('_', ' ', $transaction->status)) }}
                                </span>
                                @endif
                            </div>
                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <span class="font-mono">{{ $transaction->transaction_code }}</span>
                                <span class="text-gray-400">•</span>
                                <span>{{ $transaction->created_at->format('M d, Y h:i A') }}</span>
                            </div>
                        </div>

                        {{-- Transaction Amount --}}
                        <div class="text-right">
                            <div class="text-lg font-bold text-gray-900">
                                Rs {{ number_format($transaction->invoice_amount, 0) }}
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-16">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-blue-50 flex items-center justify-center">
                        <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <p class="text-gray-400 text-sm">No transactions yet</p>
                    <p class="text-gray-400 text-xs mt-1">Your transaction history will appear here</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Pagination with Modern Styling --}}
        @if($transactions->hasPages())
        <div class="mt-6">
            <div class="bg-white/90 backdrop-blur-xl rounded-xl border border-gray-200/60 shadow-sm px-4 py-3">
                {{ $transactions->links() }}
            </div>
        </div>
        @endif
    </div>

    {{-- Bottom Navigation --}}
    <nav class="fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-xl shadow-lg shadow-blue-900/10 border-t border-gray-200/60 z-50">
        <div class="grid grid-cols-5 max-w-7xl mx-auto place-items-center">
            {{-- Dashboard --}}
            <a href="{{ route('partner.dashboard') }}" class="flex flex-col items-center justify-center py-3 px-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50/50 transition-all duration-200">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="text-xs font-medium">Home</span>
            </a>

            {{-- Transactions (Active) --}}
            <a href="{{ route('partner.transactions') }}" class="flex flex-col items-center justify-center py-3 px-2 w-full text-white bg-gradient-to-r from-blue-600 to-blue-900 border-t-2 border-blue-500 transition-all duration-200">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <span class="text-xs font-bold">Transactions</span>
            </a>

            {{-- Wallet --}}
            <a href="{{ route('partner.wallet') }}" class="flex flex-col items-center justify-center py-3 px-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50/50 transition-all duration-200">
                <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-xs font-medium">Wallet</span>
            </a>

            {{-- Commissions --}}
            <a href="{{ route('partner.commissions') }}" class="flex flex-col items-center justify-center py-3 px-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50/50 transition-all duration-200">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-xs font-medium">Commissions</span>
            </a>

            {{-- Profile --}}
            <a href="{{ route('partner.profile') }}" class="flex flex-col items-center justify-center py-3 px-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50/50 transition-all duration-200">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="text-xs font-medium">Profile</span>
            </a>
        </div>
    </nav>

</body>
</html>
