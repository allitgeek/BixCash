<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#021c47">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Partner Dashboard - BixCash</title>
    @vite(['resources/css/app.css'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Inter+Display:wght@700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        /* Premium Typography */
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            font-feature-settings: 'cv02', 'cv03', 'cv04', 'cv11';
        }

        /* Fast, lightweight transitions */
        * {
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
</head>
<body class="relative bg-neutral-50 min-h-screen pb-32 pt-0 px-0 antialiased" style="margin: 0;">

    {{-- Enhanced Modern Header with Glassmorphism --}}
    <header class="bg-white/80 backdrop-blur-xl shadow-lg shadow-blue-900/5 border-b border-gray-200/60 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 py-3">
            <div class="flex items-center justify-between gap-3">
                {{-- Left: Logo + Business Info --}}
                <div class="flex items-center gap-3 flex-1 min-w-0">
                    {{-- Logo (64px) --}}
                    <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-xl bg-white flex items-center justify-center shadow-md flex-shrink-0 overflow-hidden border-2 border-gray-200">
                        @if($partnerProfile->logo)
                        <img src="{{ asset('storage/' . $partnerProfile->logo) }}" alt="Logo" class="w-full h-full object-cover rounded-xl">
                        @else
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        @endif
                    </div>

                    {{-- Business Info --}}
                    <div class="flex-1 min-w-0">
                        <div class="hidden sm:block">
                            <p class="text-xs font-semibold text-gray-700 mb-0.5">Good {{ $greeting }}, {{ $partnerProfile->contact_person_name }}! 👋</p>
                        </div>
                        <h1 class="text-base sm:text-lg font-bold bg-gradient-to-r from-blue-900 to-blue-700 bg-clip-text text-transparent truncate">
                            {{ $partnerProfile->business_name }}
                        </h1>
                        <div class="flex items-center gap-2 text-xs">
                            <span class="text-gray-600 capitalize">{{ $partnerProfile->business_type }}</span>
                            <span class="text-gray-400">•</span>
                            <div class="flex items-center gap-1">
                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                                <span class="text-green-700 font-semibold">Active</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right: Stats + Actions --}}
                <div class="flex items-center gap-1.5 sm:gap-2">
                    {{-- Today's Revenue Pill --}}
                    <div class="hidden md:flex items-center gap-1.5 px-3 py-1.5 bg-green-50 border border-green-200 rounded-full shadow-sm">
                        <svg class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-xs font-bold text-green-700">Rs {{ number_format($stats['today_revenue'], 0) }}</span>
                    </div>

                    {{-- Pending Badge (only if > 0) --}}
                    @if($stats['pending_confirmations'] > 0)
                    <div class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 bg-orange-50 border border-orange-200 rounded-full shadow-sm animate-pulse">
                        <svg class="w-3.5 h-3.5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-xs font-bold text-orange-700">{{ $stats['pending_confirmations'] }}</span>
                    </div>
                    @endif

                    {{-- Notification Bell --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="relative p-2 rounded-lg hover:bg-gray-100 transition-colors">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            @if($stats['notification_count'] > 0)
                            <span class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">{{ $stats['notification_count'] }}</span>
                            @endif
                        </button>

                        {{-- Notification Dropdown --}}
                        <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-[calc(100vw-2rem)] sm:w-80 bg-white rounded-xl shadow-2xl border border-gray-200 py-2 z-50">
                            <div class="px-4 py-2 border-b border-gray-200">
                                <p class="text-sm font-bold text-gray-800">Pending Confirmations</p>
                            </div>
                            @if($pendingTransactions->count() > 0)
                                @foreach($pendingTransactions as $transaction)
                                <div class="px-4 py-2.5 hover:bg-gray-50 transition-colors overflow-hidden">
                                    <p class="text-sm font-semibold text-gray-800 truncate">{{ $transaction->customer->name }}</p>
                                    <p class="text-xs text-gray-500">Rs {{ number_format($transaction->invoice_amount, 0) }} • {{ $transaction->transaction_code }}</p>
                                </div>
                                @endforeach
                            @else
                                <div class="px-4 py-6 text-center text-sm text-gray-500">No pending confirmations</div>
                            @endif
                        </div>
                    </div>

                    {{-- Profile Dropdown --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center gap-1.5 px-2 py-1.5 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="w-7 h-7 rounded-full bg-gradient-to-br from-blue-600 to-blue-900 text-white text-xs font-bold flex items-center justify-center">
                                {{ strtoupper(substr($partnerProfile->contact_person_name, 0, 1)) }}
                            </div>
                            <svg class="w-3 h-3 text-gray-600 hidden sm:block" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        {{-- Profile Menu --}}
                        <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-[calc(100vw-2rem)] sm:w-52 bg-white rounded-xl shadow-2xl border border-gray-200 py-2 z-50">
                            <a href="{{ route('partner.profile') }}" class="flex items-center gap-3 px-4 py-2.5 hover:bg-blue-50 transition-colors">
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="text-sm font-medium text-gray-700">View Profile</span>
                            </a>
                            <hr class="my-2 border-gray-200">
                            <form method="POST" action="{{ route('partner.logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-red-50 text-red-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    <span class="text-sm font-medium">Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- New Transaction Button --}}
                    <button onclick="openTransactionModal()" class="px-3 sm:px-4 py-2 rounded-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white text-sm font-semibold shadow-md shadow-green-500/30 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-2 whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        <span class="hidden sm:inline">New Transaction</span>
                        <span class="sm:hidden">New</span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-6 py-8 sm:px-8 sm:py-12 space-y-10 sm:space-y-12">

        {{-- Clean Stats Grid - Lightweight & Refined --}}
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">

            {{-- Revenue Card - Clean & Minimal --}}
            <div class="bg-white border border-neutral-200 rounded-xl p-6 hover:border-blue-300 hover:shadow-sm transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-green-600 bg-green-50 px-2 py-0.5 rounded">
                        +12%
                    </span>
                </div>

                <p class="text-xs font-medium text-neutral-500 uppercase tracking-wide mb-1">
                    Total Revenue
                </p>

                <p class="text-3xl font-semibold text-neutral-900 mb-3">
                    Rs {{ number_format($stats['total_revenue'], 0) }}
                </p>

                {{-- Mini sparkline --}}
                <div class="flex items-end gap-0.5 h-8">
                    <div class="w-full bg-blue-100 rounded-sm" style="height: 50%"></div>
                    <div class="w-full bg-blue-100 rounded-sm" style="height: 65%"></div>
                    <div class="w-full bg-blue-100 rounded-sm" style="height: 45%"></div>
                    <div class="w-full bg-blue-100 rounded-sm" style="height: 70%"></div>
                    <div class="w-full bg-blue-200 rounded-sm" style="height: 100%"></div>
                    <div class="w-full bg-blue-200 rounded-sm" style="height: 85%"></div>
                    <div class="w-full bg-blue-200 rounded-sm" style="height: 95%"></div>
                </div>
            </div>

            {{-- Profit Card --}}
            <div class="bg-white border border-neutral-200 rounded-xl p-6 hover:border-purple-300 hover:shadow-sm transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-purple-600 bg-purple-50 px-2 py-0.5 rounded">
                        Earned
                    </span>
                </div>

                <p class="text-xs font-medium text-neutral-500 uppercase tracking-wide mb-1">
                    Your Profit
                </p>

                <p class="text-3xl font-semibold text-neutral-900 mb-3">
                    Rs {{ number_format($stats['total_earned'], 0) }}
                </p>

                {{-- Simple progress bar --}}
                <div class="h-1.5 bg-neutral-100 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-purple-400 to-purple-600 rounded-full" style="width: 67%"></div>
                </div>
            </div>

            {{-- Orders Card --}}
            <div class="bg-white border border-neutral-200 rounded-xl p-6 hover:border-emerald-300 hover:shadow-sm transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded">
                        Today
                    </span>
                </div>

                <p class="text-xs font-medium text-neutral-500 uppercase tracking-wide mb-1">
                    Total Orders
                </p>

                <p class="text-3xl font-semibold text-neutral-900 mb-3">
                    {{ $stats['total_transactions'] }}
                </p>

                {{-- Simple dot indicator --}}
                <div class="flex items-center gap-1.5">
                    <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></div>
                    <span class="text-xs text-neutral-500">Active transactions</span>
                </div>
            </div>

            {{-- Pending Confirmations Card --}}
            <div class="bg-white border border-neutral-200 rounded-xl p-6 hover:border-amber-300 hover:shadow-sm transition-all duration-200 @if($stats['pending_confirmations'] > 0) ring-2 ring-amber-400/20 @endif">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    @if($stats['pending_confirmations'] > 0)
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                    </span>
                    @endif
                </div>

                <p class="text-xs font-medium text-neutral-500 uppercase tracking-wide mb-1">
                    Pending
                </p>

                <p class="text-3xl font-semibold text-neutral-900 mb-3">
                    {{ $stats['pending_confirmations'] }}
                </p>

                {{-- Status text --}}
                <div class="flex items-center gap-1.5">
                    @if($stats['pending_confirmations'] > 0)
                    <span class="text-xs text-amber-600 font-medium">Requires attention</span>
                    @else
                    <span class="text-xs text-neutral-400">All clear</span>
                    @endif
                </div>
            </div>

        </div>

        {{-- Next Profit Distribution --}}
        @if($nextBatchDate)
        <div class="space-y-4">
            <div class="flex items-center justify-center gap-3 text-sm bg-blue-50 border border-blue-200 rounded-xl py-3 px-5 shadow-sm">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="text-gray-700 font-medium">Next Profit Distribution:</span>
                <span class="font-bold bg-gradient-to-r from-blue-700 to-blue-900 bg-clip-text text-transparent">{{ $nextBatchDate->format('M d, Y') }}</span>
            </div>
        </div>
        @endif

        {{-- Recent Transactions Card --}}
        <div class="bg-white rounded-xl border border-gray-200/60 shadow-lg shadow-blue-900/5 overflow-hidden hover:border-blue-800/40 hover:shadow-xl hover:shadow-blue-900/10 transition-all duration-300">
            <div class="px-5 py-4 border-b border-gray-200/60 bg-gradient-to-r from-blue-50/70 via-blue-900/5 to-transparent">
                <div class="flex items-center gap-2">
                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-blue-600 to-blue-900 flex items-center justify-center shadow-sm shadow-blue-900/20">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold bg-gradient-to-r from-gray-800 to-blue-900 bg-clip-text text-transparent">Recent Transactions</h3>
                </div>
            </div>

            <div class="divide-y divide-gray-200/60">
                @forelse($recentTransactions as $transaction)
                <div class="p-4 hover:bg-blue-50/50 transition-all duration-200 flex items-center justify-between animate-fadeIn">
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-semibold text-gray-900 truncate">{{ $transaction->customer->name }}</h4>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $transaction->transaction_code }} • {{ $transaction->created_at->format('M d, h:i A') }}</p>
                        @if($transaction->status === 'confirmed')
                        <span class="inline-flex items-center mt-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-200/60 shadow-sm shadow-green-900/5">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            Confirmed
                        </span>
                        @elseif($transaction->status === 'pending_confirmation')
                        <span class="inline-flex items-center mt-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-orange-50 text-orange-700 border border-orange-200/60 shadow-sm shadow-orange-900/5">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                            </svg>
                            Pending
                        </span>
                        @else
                        <span class="inline-flex items-center mt-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-red-50 to-rose-50 text-red-700 border border-red-200/60 shadow-sm">
                            {{ ucfirst(str_replace('_', ' ', $transaction->status)) }}
                        </span>
                        @endif
                    </div>
                    <div class="text-right ml-4">
                        <p class="text-base font-bold text-gray-900">Rs {{ number_format($transaction->invoice_amount, 0) }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-12 px-4">
                    <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center animate-bounce">
                        <svg class="w-10 h-10 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h4 class="text-gray-900 font-bold text-base mb-2">Ready to Get Started?</h4>
                    <p class="text-gray-500 text-sm mb-4">Create your first transaction using the button above</p>
                    <button onclick="openTransactionModal()" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white text-sm font-semibold shadow-lg shadow-green-500/30 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Create First Transaction
                    </button>
                </div>
                @endforelse
            </div>

            @if($recentTransactions->count() > 0)
            <a href="{{ route('partner.transactions') }}" class="block w-full text-center py-3 bg-blue-50/50 hover:bg-blue-100/50 text-blue-700 font-bold text-sm border-t border-gray-200/60 transition-all duration-200">
                View All Transactions →
            </a>
            @endif
        </div>
    </div>

    {{-- Glassmorphism Bottom Navigation --}}
    <nav class="fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-xl shadow-lg shadow-blue-900/10 border-t border-gray-200/60 z-50">
        <div class="grid grid-cols-5 max-w-7xl mx-auto">
            <a href="{{ route('partner.dashboard') }}" class="flex flex-col items-center py-3 px-2 text-white bg-gradient-to-r from-blue-600 to-blue-900 border-t-2 border-blue-500 transition-all duration-200">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="text-xs font-bold">Home</span>
            </a>
            <a href="{{ route('partner.transactions') }}" class="flex flex-col items-center py-3 px-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50/50 transition-all duration-200">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <span class="text-xs font-medium">Transactions</span>
            </a>
            <a href="{{ route('partner.wallet') }}" class="flex flex-col items-center py-3 px-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50/50 transition-all duration-200">
                <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-xs font-medium">Wallet</span>
            </a>
            {{-- Profits menu item - HIDDEN --}}
            {{-- <a href="{{ route('partner.profits') }}" class="flex flex-col items-center py-3 px-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50/50 transition-all duration-200">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-xs font-medium">Profits</span>
            </a> --}}
            <a href="{{ route('partner.profile') }}" class="flex flex-col items-center py-3 px-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50/50 transition-all duration-200">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="text-xs font-medium">Profile</span>
            </a>
        </div>
    </nav>

    {{-- Transaction Modal with Accessibility --}}
    <div id="transactionModal" role="dialog" aria-modal="true" aria-labelledby="modalTitle" class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md sm:max-w-lg w-full max-h-[90vh] overflow-y-auto shadow-2xl">
            <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between bg-gradient-to-r from-blue-50/70 to-transparent">
                <h3 id="modalTitle" class="text-xl font-bold bg-gradient-to-r from-blue-900 to-blue-700 bg-clip-text text-transparent">New Transaction</h3>
                <button onclick="closeTransactionModal()" aria-label="Close modal" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="p-6">
                <div id="alertContainer"></div>

                {{-- Step 1: Search Customer --}}
                <div id="step1" class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Customer Phone Number</label>
                        <div class="flex gap-2">
                            <div class="px-4 py-3 bg-gray-100 border-2 border-gray-200 rounded-xl font-semibold text-gray-700">+92</div>
                            <input type="text" id="customerPhone" class="flex-1 px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition-all" placeholder="3001234567" maxlength="10" pattern="[0-9]{10}">
                        </div>
                    </div>
                    <button id="searchBtn" onclick="searchCustomer()" class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-xl py-3 min-h-[44px] font-semibold shadow-lg shadow-green-500/30 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none flex items-center justify-center gap-2">
                        <svg id="searchSpinner" class="hidden animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span id="searchBtnText">Search Customer</span>
                    </button>
                </div>

                {{-- Step 2: Create Transaction --}}
                <div id="step2" class="hidden space-y-4">
                    <div id="customerInfoBox" class="bg-blue-50 border border-blue-200 rounded-xl p-4"></div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Invoice Amount (Rs)</label>
                        <input type="number" id="invoiceAmount" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition-all" placeholder="0" min="1" step="0.01">
                    </div>
                    <button id="createBtn" onclick="createTransaction()" class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-xl py-3 min-h-[44px] font-semibold shadow-lg shadow-green-500/30 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none flex items-center justify-center gap-2">
                        <svg id="createSpinner" class="hidden animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span id="createBtnText">Create Transaction</span>
                    </button>
                    <button onclick="backToStep1()" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl py-3 min-h-[44px] font-semibold transition-colors duration-200">
                        Back
                    </button>
                </div>

                {{-- Step 3: Success --}}
                <div id="step3" class="hidden text-center space-y-4">
                    <div class="text-6xl mb-4">✅</div>
                    <h3 class="text-2xl font-bold text-green-600">Transaction Created!</h3>
                    <div id="transactionSuccessInfo" class="bg-green-50 border border-green-200 rounded-xl p-4 text-left"></div>
                    <button onclick="closeTransactionModal(); location.reload();" class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-xl py-3 min-h-[44px] font-semibold shadow-lg shadow-green-500/30 hover:shadow-xl transition-all duration-200">
                        Done
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let selectedCustomer = null;

        function openTransactionModal() {
            document.getElementById('transactionModal').classList.remove('hidden');
            resetModal();
            // Focus first input
            setTimeout(() => document.getElementById('customerPhone')?.focus(), 100);
        }

        function closeTransactionModal() {
            document.getElementById('transactionModal').classList.add('hidden');
            resetModal();
        }

        // ESC key to close modal
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !document.getElementById('transactionModal').classList.contains('hidden')) {
                closeTransactionModal();
            }
        });

        function resetModal() {
            showStep(1);
            document.getElementById('customerPhone').value = '';
            document.getElementById('invoiceAmount').value = '';
            document.getElementById('alertContainer').innerHTML = '';
            selectedCustomer = null;
        }

        function showStep(step) {
            document.getElementById('step1').classList.toggle('hidden', step !== 1);
            document.getElementById('step2').classList.toggle('hidden', step !== 2);
            document.getElementById('step3').classList.toggle('hidden', step !== 3);
        }

        function backToStep1() {
            showStep(1);
            selectedCustomer = null;
        }

        function showAlert(message, type = 'error') {
            const bgColor = type === 'error' ? 'bg-red-50 border-red-200 text-red-700' : 'bg-green-50 border-green-200 text-green-700';
            const alertHtml = `<div class="mb-4 px-4 py-3 rounded-xl border ${bgColor} text-sm font-medium">${message}</div>`;
            document.getElementById('alertContainer').innerHTML = alertHtml;
        }

        async function searchCustomer() {
            const phone = document.getElementById('customerPhone').value.trim();
            if (!/^[0-9]{10}$/.test(phone)) {
                showAlert('Please enter a valid 10-digit phone number');
                return;
            }

            // Show loading state
            const btn = document.getElementById('searchBtn');
            const btnText = document.getElementById('searchBtnText');
            const spinner = document.getElementById('searchSpinner');
            btn.disabled = true;
            btnText.textContent = 'Searching...';
            spinner.classList.remove('hidden');

            try {
                const response = await fetch('{{ route('partner.search-customer') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ phone })
                });

                const data = await response.json();
                if (data.success) {
                    selectedCustomer = data.customer;
                    displayCustomerInfo(data.customer);
                    showStep(2);
                    document.getElementById('alertContainer').innerHTML = '';
                } else {
                    showAlert(data.message);
                }
            } catch (error) {
                showAlert('Network error. Please try again.');
            } finally {
                // Hide loading state
                btn.disabled = false;
                btnText.textContent = 'Search Customer';
                spinner.classList.add('hidden');
            }
        }

        function displayCustomerInfo(customer) {
            const html = `
                <h4 class="font-bold text-gray-900 mb-2">${customer.name}</h4>
                <p class="text-sm text-gray-600"><span class="font-medium">Phone:</span> ${customer.phone}</p>
                <p class="text-sm text-gray-600"><span class="font-medium">Total Purchases:</span> ${customer.stats.total_purchases}</p>
                <p class="text-sm text-gray-600"><span class="font-medium">Total Spent:</span> Rs ${parseFloat(customer.stats.total_spent || 0).toFixed(0)}</p>
            `;
            document.getElementById('customerInfoBox').innerHTML = html;
        }

        async function createTransaction() {
            const amount = document.getElementById('invoiceAmount').value;
            if (!amount || amount <= 0) {
                showAlert('Please enter a valid invoice amount');
                return;
            }
            if (!selectedCustomer) {
                showAlert('Customer not selected');
                return;
            }

            // Show loading state
            const btn = document.getElementById('createBtn');
            const btnText = document.getElementById('createBtnText');
            const spinner = document.getElementById('createSpinner');
            btn.disabled = true;
            btnText.textContent = 'Creating...';
            spinner.classList.remove('hidden');

            try {
                const response = await fetch('{{ route('partner.create-transaction') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        customer_id: selectedCustomer.id,
                        invoice_amount: amount
                    })
                });

                const data = await response.json();
                if (data.success) {
                    displaySuccess(data.transaction);
                    showStep(3);
                } else {
                    showAlert(data.message);
                }
            } catch (error) {
                showAlert('Network error. Please try again.');
            } finally {
                // Hide loading state
                btn.disabled = false;
                btnText.textContent = 'Create Transaction';
                spinner.classList.add('hidden');
            }
        }

        function displaySuccess(transaction) {
            const html = `
                <p class="text-sm text-gray-600 mb-1">Transaction Code</p>
                <p class="text-2xl font-bold text-green-600 mb-3">${transaction.transaction_code}</p>
                <p class="text-sm text-gray-600 mb-1"><span class="font-medium">Amount:</span> Rs ${transaction.invoice_amount}</p>
                <p class="text-sm text-gray-600 mb-1"><span class="font-medium">Customer:</span> ${transaction.customer_name}</p>
                <p class="text-xs text-orange-600 font-medium mt-3">⏱️ Customer has 60 seconds to confirm</p>
            `;
            document.getElementById('transactionSuccessInfo').innerHTML = html;
        }

        // Phone input validation
        document.getElementById('customerPhone')?.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '').substring(0, 10);
        });

        // Enter key to search customer in step 1
        document.getElementById('customerPhone')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchCustomer();
            }
        });

        // Enter key to create transaction in step 2
        document.getElementById('invoiceAmount')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                createTransaction();
            }
        });

        // Close modal on outside click
        document.getElementById('transactionModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeTransactionModal();
            }
        });

        // Focus trap within modal
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Tab' && !document.getElementById('transactionModal').classList.contains('hidden')) {
                const modal = document.getElementById('transactionModal');
                const focusableElements = modal.querySelectorAll('button, input, select, textarea, a[href]');
                const firstElement = focusableElements[0];
                const lastElement = focusableElements[focusableElements.length - 1];

                if (e.shiftKey && document.activeElement === firstElement) {
                    e.preventDefault();
                    lastElement.focus();
                } else if (!e.shiftKey && document.activeElement === lastElement) {
                    e.preventDefault();
                    firstElement.focus();
                }
            }
        });
    </script>
</body>
</html>
