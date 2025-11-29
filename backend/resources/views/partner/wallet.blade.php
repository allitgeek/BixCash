<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#021c47">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Wallet - BixCash Partner</title>
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
                <h1 class="text-xl font-semibold text-neutral-900">My Wallet</h1>
            </div>
        </div>
    </header>

    {{-- Wallet Stats Cards - Clean & Minimal --}}
    <div class="max-w-7xl mx-auto px-4 mt-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            {{-- Available Balance --}}
            <div class="stat-card bg-white/90 backdrop-blur-sm border border-neutral-200 rounded-xl p-6 hover:border-blue-300 hover:shadow-md hover:scale-[1.02] transition-[transform,shadow,border-color] duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                            <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                </div>
                <p class="text-xs font-medium text-neutral-500 uppercase tracking-wide mb-1">Available Balance</p>
                <p class="text-2xl sm:text-3xl font-semibold text-neutral-900">Rs {{ number_format($wallet->balance, 0) }}</p>
            </div>

            {{-- Total Earned --}}
            <div class="stat-card bg-white/90 backdrop-blur-sm border border-neutral-200 rounded-xl p-6 hover:border-emerald-300 hover:shadow-md hover:scale-[1.02] transition-[transform,shadow,border-color] duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded">
                        Total
                    </span>
                </div>
                <p class="text-xs font-medium text-neutral-500 uppercase tracking-wide mb-1">Total Earned</p>
                <p class="text-2xl sm:text-3xl font-semibold text-neutral-900">Rs {{ number_format($wallet->total_earned, 0) }}</p>
            </div>

            {{-- Total Withdrawn --}}
            <div class="stat-card bg-white/90 backdrop-blur-sm border border-neutral-200 rounded-xl p-6 hover:border-orange-300 hover:shadow-md hover:scale-[1.02] transition-[transform,shadow,border-color] duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-8 h-8 rounded-lg bg-orange-50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-orange-600 bg-orange-50 px-2 py-0.5 rounded">
                        Paid
                    </span>
                </div>
                <p class="text-xs font-medium text-neutral-500 uppercase tracking-wide mb-1">Total Withdrawn</p>
                <p class="text-2xl sm:text-3xl font-semibold text-neutral-900">Rs {{ number_format($wallet->total_withdrawn, 0) }}</p>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <main class="page-content max-w-7xl mx-auto px-4 mt-6">

        @php
            $profile = $partnerProfile;
            $hasBankDetails = $profile && $profile->bank_name && $profile->account_number;
        @endphp

        {{-- Bank Details Warning --}}
        @if(!$hasBankDetails)
        <div class="bg-orange-50/80 rounded-xl border-l-4 border-orange-600 p-4 sm:p-5 mb-6 shadow-sm">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-lg bg-orange-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-orange-900 mb-2">Bank Details Required</p>
                    <p class="text-sm text-orange-800 leading-relaxed">
                        Please add your bank details in your <a href="{{ route('partner.profile') }}" class="underline font-semibold">profile</a> before requesting withdrawals.
                    </p>
                </div>
            </div>
        </div>
        @endif

        {{-- Withdrawal Limits Info (if bank details exist) --}}
        @if($hasBankDetails && isset($settings))
        <div class="bg-blue-50/80 rounded-xl border-l-4 border-blue-600 p-4 sm:p-5 mb-6 shadow-sm">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-lg bg-blue-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-blue-900 mb-2">Withdrawal Limits</p>
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <span class="text-blue-700 font-medium">Min Amount:</span>
                            <span class="text-blue-900 font-bold">Rs {{ number_format($settings->min_amount, 0) }}</span>
                        </div>
                        <div>
                            <span class="text-blue-700 font-medium">Max per Request:</span>
                            <span class="text-blue-900 font-bold">Rs {{ number_format($settings->max_per_withdrawal, 0) }}</span>
                        </div>
                        <div>
                            <span class="text-blue-700 font-medium">Daily Remaining:</span>
                            <span class="text-blue-900 font-bold">Rs {{ number_format($dailyRemaining, 0) }}</span>
                        </div>
                        <div>
                            <span class="text-blue-700 font-medium">Monthly Remaining:</span>
                            <span class="text-blue-900 font-bold">Rs {{ number_format($monthlyRemaining, 0) }}</span>
                        </div>
                    </div>
                    @if($settings->processing_message)
                    <p class="text-xs text-blue-700 mt-3 leading-relaxed">
                        <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                        {{ $settings->processing_message }}
                    </p>
                    @endif
                </div>
            </div>
        </div>
        @endif

        {{-- Withdrawals Disabled Warning --}}
        @if(isset($settings) && !$settings->enabled)
        <div class="bg-red-50/80 rounded-xl border-l-4 border-red-600 p-4 sm:p-5 mb-6 shadow-sm">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-lg bg-red-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-red-900 mb-2">Withdrawals Temporarily Disabled</p>
                    <p class="text-sm text-red-800 leading-relaxed">
                        Withdrawal requests are currently disabled. Please check back later.
                    </p>
                </div>
            </div>
        </div>
        @endif

        {{-- Request Withdrawal Section --}}
        <div class="bg-white rounded-xl border border-gray-200/60 shadow-lg shadow-blue-900/5 overflow-hidden mb-6">
            <div class="px-5 py-4 border-b border-gray-200/60 bg-gradient-to-r from-blue-50/70 via-blue-900/5 to-transparent">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-600 to-blue-900 flex items-center justify-center shadow-sm">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-base font-bold bg-gradient-to-r from-gray-800 to-blue-900 bg-clip-text text-transparent">Request Withdrawal</h2>
                </div>
            </div>

            <div class="p-5">
                <form method="POST" action="{{ route('partner.wallet.withdraw') }}" id="withdrawalForm">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Amount (Rs.) *</label>
                        <input type="number" name="amount" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-base focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-500/10 transition-all" required min="{{ $settings->min_amount ?? 100 }}" max="{{ $settings->max_per_withdrawal ?? 50000 }}" step="1" placeholder="Min Rs. {{ number_format($settings->min_amount ?? 100, 0) }}" @if(!$hasBankDetails || (isset($settings) && !$settings->enabled)) disabled @endif>
                        <p class="text-xs text-gray-500 mt-2">Minimum withdrawal amount is Rs. {{ number_format($settings->min_amount ?? 100, 0) }}</p>
                    </div>
                    <button type="submit" class="w-full px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-blue-800 hover:-translate-y-0.5 transition-all duration-200 shadow-sm shadow-blue-500/30 hover:shadow-md hover:shadow-blue-500/40 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed" @if(!$hasBankDetails || (isset($settings) && !$settings->enabled)) disabled @endif>
                        <svg class="withdraw-spinner hidden animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="withdraw-text">Request Withdrawal</span>
                    </button>
                </form>
            </div>
        </div>

        {{-- Withdrawal History --}}
        <div class="bg-white rounded-xl border border-gray-200/60 shadow-lg shadow-blue-900/5 overflow-hidden mb-6">
            <div class="px-5 py-4 border-b border-gray-200/60 bg-gradient-to-r from-blue-50/70 via-blue-900/5 to-transparent">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-600 to-blue-900 flex items-center justify-center shadow-sm">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h2 class="text-base font-bold bg-gradient-to-r from-gray-800 to-blue-900 bg-clip-text text-transparent">Withdrawal History</h2>
                </div>
            </div>

            <div class="p-5">
                @if($withdrawals->count() > 0)
                    <div class="overflow-x-auto -mx-5 sm:mx-0">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b-2 border-gray-200">
                                    <th class="text-left text-xs text-gray-500 uppercase tracking-wide pb-3 px-5 sm:px-0">Amount</th>
                                    <th class="text-left text-xs text-gray-500 uppercase tracking-wide pb-3">Date</th>
                                    <th class="text-left text-xs text-gray-500 uppercase tracking-wide pb-3">Status</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                @foreach($withdrawals as $withdrawal)
                                <tr class="withdrawal-row border-b border-gray-100 hover:bg-blue-50/50 transition-colors">
                                    <td class="py-4 font-semibold text-gray-800 px-5 sm:px-0">Rs {{ number_format($withdrawal->amount, 0) }}</td>
                                    <td class="py-4 text-gray-600">{{ $withdrawal->created_at->format('M d, Y') }}</td>
                                    <td class="py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                            @if($withdrawal->status === 'completed') bg-green-100 text-green-700
                                            @elseif($withdrawal->status === 'pending') bg-yellow-100 text-yellow-700
                                            @elseif($withdrawal->status === 'processing') bg-blue-100 text-blue-700
                                            @else bg-red-100 text-red-700 @endif">
                                            {{ ucfirst($withdrawal->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($withdrawals->hasPages())
                    <div class="mt-6 pt-4 border-t border-gray-200/60">
                        {{ $withdrawals->links() }}
                    </div>
                    @endif
                @else
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <p class="text-gray-500">No withdrawals yet</p>
                        <p class="text-sm text-gray-400 mt-2">Your withdrawal history will appear here</p>
                    </div>
                @endif
            </div>
        </div>

    </main>

    {{-- Bottom Navigation --}}
    <nav class="fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-xl shadow-lg shadow-blue-900/10 border-t border-gray-200/60 z-50">
        <div class="grid grid-cols-5 max-w-7xl mx-auto">
            {{-- Home --}}
            <a href="{{ route('partner.dashboard') }}" class="flex flex-col items-center justify-center py-3 px-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50/50 hover:scale-[1.05] transition-[transform,colors,background-color] duration-200 active:scale-95">
                <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                </svg>
                <span class="text-xs font-medium">Home</span>
            </a>

            {{-- Transactions --}}
            <a href="{{ route('partner.transactions') }}" class="flex flex-col items-center justify-center py-3 px-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50/50 hover:scale-[1.05] transition-[transform,colors,background-color] duration-200 active:scale-95">
                <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path>
                </svg>
                <span class="text-xs font-medium">Transactions</span>
            </a>

            {{-- Wallet (Active) --}}
            <a href="{{ route('partner.wallet') }}" class="flex flex-col items-center justify-center py-3 px-2 text-white bg-gradient-to-r from-blue-600 to-blue-900 border-t-2 border-blue-500 hover:scale-[1.05] transition-[transform,colors,background-color] duration-200 active:scale-95">
                <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-xs font-bold">Wallet</span>
            </a>

            {{-- Commissions --}}
            <a href="{{ route('partner.commissions') }}" class="flex flex-col items-center justify-center py-3 px-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50/50 hover:scale-[1.05] transition-[transform,colors,background-color] duration-200 active:scale-95">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-xs font-medium">Commissions</span>
            </a>

            {{-- Profile --}}
            <a href="{{ route('partner.profile') }}" class="flex flex-col items-center justify-center py-3 px-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50/50 hover:scale-[1.05] transition-[transform,colors,background-color] duration-200 active:scale-95">
                <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-xs font-medium">Profile</span>
            </a>
        </div>
    </nav>

    {{-- Success Message --}}
    @if(session('success'))
    <div id="successMessage" class="fixed top-5 right-5 bg-green-500 text-white px-6 py-4 rounded-xl shadow-2xl z-[2000] flex items-center gap-3 animate-slideIn">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Error Message --}}
    @if(session('error'))
    <div id="errorMessage" class="fixed top-5 right-5 bg-red-500 text-white px-6 py-4 rounded-xl shadow-2xl z-[2000] flex items-center gap-3 animate-slideIn">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
        </svg>
        {{ session('error') }}
    </div>
    @endif

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

        /* Toast notifications */
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
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

        .withdrawal-row {
            animation: fadeIn 0.4s ease-out both;
        }
        .withdrawal-row:nth-child(1) { animation-delay: 0.1s; }
        .withdrawal-row:nth-child(2) { animation-delay: 0.15s; }
        .withdrawal-row:nth-child(3) { animation-delay: 0.2s; }
        .withdrawal-row:nth-child(4) { animation-delay: 0.25s; }
        .withdrawal-row:nth-child(5) { animation-delay: 0.3s; }

        .animate-slideIn {
            animation: slideIn 0.3s ease;
        }
    </style>

    <script>
        // Withdrawal form loading state
        document.getElementById('withdrawalForm')?.addEventListener('submit', function(e) {
            const btn = this.querySelector('button[type="submit"]');
            const spinner = btn.querySelector('.withdraw-spinner');
            const text = btn.querySelector('.withdraw-text');

            btn.disabled = true;
            spinner.classList.remove('hidden');
            text.textContent = 'Processing...';
        });

        // Auto-hide success/error messages
        @if(session('success') || session('error'))
        setTimeout(() => {
            const message = document.getElementById('{{ session('success') ? 'successMessage' : 'errorMessage' }}');
            if (message) {
                message.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => message.remove(), 300);
            }
        }, 4000);
        @endif
    </script>

</body>
</html>
