<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#1a5928">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Wallet - BixCash</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><circle cx='50' cy='50' r='45' fill='%2376d37a'/><text x='50' y='68' font-size='55' font-weight='bold' fill='white' text-anchor='middle' font-family='Arial'>B</text></svg>">
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
</head>
<body class="bg-gray-50 min-h-screen pb-24" style="margin: 0; padding: 0;">

    {{-- Header - Integrated Balance Design (Like Dashboard) --}}
    <header class="text-white px-4 py-4 shadow-lg" style="background: linear-gradient(to bottom right, rgba(0,0,0,0.15), rgba(0,0,0,0.25)), #76d37a;">
        <div class="max-w-7xl mx-auto">
            {{-- Row 1: Logo + Title + Balance + Back Button --}}
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    {{-- BixCash Logo - Links to Main Website --}}
                    <a href="https://bixcash.com" class="flex-shrink-0 hover:opacity-80 transition-opacity" target="_blank" rel="noopener noreferrer">
                        <img src="/images/logos/logos-01.png" alt="BixCash Logo" class="h-10 w-auto brightness-0 invert">
                    </a>
                    <h1 class="text-base sm:text-lg font-bold whitespace-nowrap">My Wallet</h1>
                </div>

                <div class="flex items-center gap-3">
                    {{-- Available Balance (Integrated in Header) --}}
                    <div class="text-right">
                        <p class="text-white/80 text-xs mb-1">Available Balance</p>
                        <p class="text-3xl font-bold">Rs {{ number_format($wallet->balance, 0) }}</p>
                    </div>

                    {{-- Back to Dashboard Button --}}
                    <a href="{{ route('customer.dashboard') }}" class="w-10 h-10 rounded-full bg-white flex items-center justify-center hover:shadow-xl hover:scale-110 transition-all duration-200 shadow-md flex-shrink-0">
                        <svg class="w-5 h-5 text-[#76d37a]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Row 2: Stats (Total Earned + Total Withdrawn) --}}
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-white/10 backdrop-blur border border-white/20 text-white px-4 py-3 rounded-2xl text-center hover:bg-white/15 transition-all duration-200">
                    <p class="text-2xl font-bold mb-1">Rs {{ number_format($wallet->total_earned, 0) }}</p>
                    <p class="text-xs text-white/80 uppercase tracking-wide">Total Earned</p>
                </div>
                <div class="bg-white/10 backdrop-blur border border-white/20 text-white px-4 py-3 rounded-2xl text-center hover:bg-white/15 transition-all duration-200">
                    <p class="text-2xl font-bold mb-1">Rs {{ number_format($wallet->total_withdrawn, 0) }}</p>
                    <p class="text-xs text-white/80 uppercase tracking-wide">Total Withdrawn</p>
                </div>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="max-w-7xl mx-auto px-4 pt-6 pb-20">

        @php
            $profile = Auth::user()->customerProfile;
            $isLocked = $profile && $profile->withdrawal_locked_until && $profile->withdrawal_locked_until > now();
        @endphp

        {{-- Lock Warning --}}
        @if($isLocked)
        <div class="bg-gradient-to-r from-orange-50 to-yellow-50 rounded-2xl border-2 border-orange-300 p-4 sm:p-5 mb-6 shadow-md">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-lg bg-orange-500 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-orange-900 mb-2">🔒 Withdrawals Temporarily Locked</p>
                    <p class="text-sm text-orange-800 leading-relaxed">
                        For security, withdrawals are locked for 24 hours after changing bank details.
                        <br>
                        <strong class="font-semibold">Unlock Time:</strong> {{ $profile->withdrawal_locked_until->format('M d, Y h:i A') }}
                    </p>
                </div>
            </div>
        </div>
        @endif

        {{-- Withdrawal Limits Info --}}
        @if($settings->enabled)
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl border-2 border-blue-200 p-4 mb-6 shadow-md">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-lg bg-blue-500 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-blue-900 mb-2">💡 Withdrawal Limits</p>
                    <div class="grid grid-cols-2 gap-3 text-xs text-blue-800">
                        <div>
                            <strong>Min Amount:</strong> Rs. {{ number_format($settings->min_amount, 0) }}
                        </div>
                        <div>
                            <strong>Max Per Withdrawal:</strong> Rs. {{ number_format($settings->max_per_withdrawal, 0) }}
                        </div>
                        <div>
                            <strong>Daily Remaining:</strong> Rs. {{ number_format($remainingDaily, 0) }}
                        </div>
                        <div>
                            <strong>Monthly Remaining:</strong> Rs. {{ number_format($remainingMonthly, 0) }}
                        </div>
                    </div>
                    @if($settings->processing_message)
                    <p class="text-xs text-blue-700 mt-3 leading-relaxed">
                        <strong>Processing Time:</strong> {{ $settings->processing_message }}
                    </p>
                    @endif
                </div>
            </div>
        </div>
        @endif

        {{-- Request Withdrawal Section --}}
        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-md overflow-hidden mb-6">
            <div class="px-4 py-3 border-b border-gray-200/60 bg-gradient-to-r from-green-50/70 via-green-900/5 to-transparent">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#76d37a] to-[#93db4d] flex items-center justify-center shadow-sm">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-base font-bold bg-gradient-to-r from-gray-800 to-green-900 bg-clip-text text-transparent">Request Withdrawal</h2>
                </div>
            </div>

            <div class="p-4">
                @if(!$settings->enabled)
                    <div class="bg-yellow-50 border-2 border-yellow-300 rounded-2xl p-4 text-center">
                        <p class="text-yellow-800 font-semibold">⚠️ Withdrawals are temporarily disabled</p>
                        <p class="text-yellow-700 text-sm mt-2">Please try again later.</p>
                    </div>
                @else
                    <form method="POST" action="{{ route('customer.wallet.withdraw') }}" id="withdrawalForm">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Amount (Rs.) *</label>
                            <input type="number" name="amount" class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl text-base focus:outline-none focus:border-[#76d37a] focus:ring-4 focus:ring-green-500/10 transition-all"
                                   required min="{{ $settings->min_amount }}" max="{{ $settings->max_per_withdrawal }}" step="0.01"
                                   placeholder="Min: Rs. {{ number_format($settings->min_amount, 0) }} | Max: Rs. {{ number_format($settings->max_per_withdrawal, 0) }}">
                            <p class="text-xs text-gray-500 mt-2">
                                Amount will be deducted immediately from your wallet upon request.
                            </p>
                        </div>
                        <button type="submit" class="w-full px-4 py-3 bg-gradient-to-r from-[#76d37a] to-[#93db4d] text-white font-semibold rounded-2xl hover:from-[#5cb85c] hover:to-[#76d37a] hover:-translate-y-0.5 transition-all duration-200 shadow-sm shadow-green-500/30 hover:shadow-md hover:shadow-green-500/40 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="withdraw-spinner hidden animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="withdraw-text">Request Withdrawal</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- Withdrawal History --}}
        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-md overflow-hidden mb-6">
            <div class="px-4 py-3 border-b border-gray-200/60 bg-gradient-to-r from-green-50/70 via-green-900/5 to-transparent">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#76d37a] to-[#93db4d] flex items-center justify-center shadow-sm">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h2 class="text-base font-bold bg-gradient-to-r from-gray-800 to-green-900 bg-clip-text text-transparent">Withdrawal History</h2>
                </div>
            </div>

            <div class="p-4">
                @if($withdrawals->count() > 0)
                    <div class="space-y-3">
                        @foreach($withdrawals as $withdrawal)
                        <div class="border-2 border-gray-200 rounded-2xl p-4 hover:border-green-200 transition-colors">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <p class="text-lg font-bold text-gray-800">Rs {{ number_format($withdrawal->amount, 2) }}</p>
                                    <p class="text-xs text-gray-500">{{ $withdrawal->created_at->format('M d, Y h:i A') }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                        @if($withdrawal->status === 'completed') bg-green-100 text-green-700
                                        @elseif($withdrawal->status === 'pending') bg-yellow-100 text-yellow-700
                                        @elseif($withdrawal->status === 'processing') bg-blue-100 text-blue-700
                                        @elseif($withdrawal->status === 'cancelled') bg-gray-100 text-gray-700
                                        @else bg-red-100 text-red-700 @endif">
                                        {{ ucfirst($withdrawal->status) }}
                                    </span>
                                </div>
                            </div>

                            @if($withdrawal->status === 'completed' && $withdrawal->bank_reference)
                                <div class="bg-green-50 rounded-xl p-3 mt-2">
                                    <p class="text-xs text-green-800">
                                        <strong>✅ Bank Reference:</strong> {{ $withdrawal->bank_reference }}
                                    </p>
                                    <p class="text-xs text-green-700 mt-1">
                                        <strong>Payment Date:</strong> {{ \Carbon\Carbon::parse($withdrawal->payment_date)->format('M d, Y') }}
                                    </p>
                                </div>
                            @elseif($withdrawal->status === 'rejected' && $withdrawal->rejection_reason)
                                <div class="bg-red-50 rounded-xl p-3 mt-2">
                                    <p class="text-xs font-semibold text-red-800 mb-1">❌ Rejection Reason:</p>
                                    <p class="text-xs text-red-700">{{ $withdrawal->rejection_reason }}</p>
                                </div>
                            @elseif($withdrawal->status === 'pending')
                                <div class="mt-3">
                                    <form method="POST" action="{{ route('customer.wallet.cancel', $withdrawal->id) }}" onsubmit="return confirm('Cancel this withdrawal? Amount will be refunded to your wallet.');">
                                        @csrf
                                        <button type="submit" class="w-full px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white text-sm font-semibold rounded-xl hover:from-red-600 hover:to-red-700 transition-all duration-200 shadow-sm">
                                            🚫 Cancel Withdrawal
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                        @endforeach
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

    {{-- Bottom Navigation (Matching Dashboard) --}}
    <nav class="fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-xl shadow-lg shadow-green-900/10 border-t border-gray-200/60 z-50">
        <div class="grid grid-cols-5 max-w-7xl mx-auto">
            {{-- Home --}}
            <a href="{{ route('customer.dashboard') }}" class="flex flex-col items-center py-3 px-2 text-gray-500 hover:text-[#76d37a] hover:bg-green-50/50 transition-all duration-200">
                <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                </svg>
                <span class="text-xs font-medium">Home</span>
            </a>

            {{-- Wallet (Active) --}}
            <a href="{{ route('customer.wallet') }}" class="flex flex-col items-center py-3 px-2 text-white bg-gradient-to-r from-[#76d37a] to-[#93db4d] border-t-2 border-[#76d37a] transition-all duration-200">
                <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-xs font-bold">Wallet</span>
            </a>

            {{-- Purchases --}}
            <a href="{{ route('customer.purchases') }}" class="flex flex-col items-center py-3 px-2 text-gray-500 hover:text-[#76d37a] hover:bg-green-50/50 transition-all duration-200">
                <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path>
                </svg>
                <span class="text-xs font-medium">Purchases</span>
            </a>

            {{-- Profile --}}
            <a href="{{ route('customer.profile') }}" class="flex flex-col items-center py-3 px-2 text-gray-500 hover:text-[#76d37a] hover:bg-green-50/50 transition-all duration-200">
                <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-xs font-medium">Profile</span>
            </a>

            {{-- Logout --}}
            <form method="POST" action="{{ route('customer.logout') }}" class="contents" onsubmit="return confirm('Are you sure you want to logout?');">
                @csrf
                <button type="submit" class="flex flex-col items-center py-3 px-2 text-gray-500 hover:text-red-600 hover:bg-red-50/50 transition-all duration-200">
                    <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-xs font-medium">Logout</span>
                </button>
            </form>
        </div>
    </nav>

    {{-- Success Message --}}
    @if(session('success'))
    <div id="successMessage" class="fixed top-5 right-5 bg-green-500 text-white px-6 py-4 rounded-2xl shadow-2xl z-[2000] flex items-center gap-3 animate-slideIn">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Error Message --}}
    @if(session('error'))
    <div id="errorMessage" class="fixed top-5 right-5 bg-red-500 text-white px-6 py-4 rounded-2xl shadow-2xl z-[2000] flex items-center gap-3 animate-slideIn">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
        </svg>
        {{ session('error') }}
    </div>
    @endif

    {{-- Animations --}}
    <style>
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
        .animate-slideIn {
            animation: slideIn 0.3s ease;
        }

        /* Mobile Bottom Navigation */
        .mobile-bottom-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            padding: 8px 0;
            padding-bottom: calc(8px + env(safe-area-inset-bottom));
        }

        @media (max-width: 768px) {
            .mobile-bottom-nav {
                display: flex;
                justify-content: space-around;
                align-items: center;
            }
            body {
                padding-bottom: 70px !important;
            }
        }

        .bottom-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #666;
            font-size: 10px;
            padding: 4px 12px;
            transition: color 0.2s ease;
            min-width: 50px;
        }

        .bottom-nav-item i {
            font-size: 20px;
            margin-bottom: 4px;
        }

        .bottom-nav-item span {
            white-space: nowrap;
        }

        .bottom-nav-item.active,
        .bottom-nav-item:hover {
            color: var(--bix-dark-blue, #1a365d) !important;
        }

        .bottom-nav-item.active i,
        .bottom-nav-item.active svg {
            color: var(--bix-light-green, #76d37a) !important;
            transform: scale(1.1);
            transition: transform 0.15s ease, color 0.15s ease;
        }

        .bottom-nav-item.active span {
            color: var(--bix-light-green, #76d37a) !important;
            font-weight: 600;
        }

        /* Logout button styling */
        .bottom-nav-item-form {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .bottom-nav-item.logout-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-family: inherit;
        }

        .bottom-nav-item.logout-btn:hover i,
        .bottom-nav-item.logout-btn:hover span {
            color: #dc2626 !important;
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

    <!-- Mobile Bottom Navigation -->
    <nav class="mobile-bottom-nav">
        <a href="{{ route('customer.dashboard') }}" class="bottom-nav-item">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('customer.wallet') }}" class="bottom-nav-item active">
            <i class="fas fa-wallet"></i>
            <span>Wallet</span>
        </a>
        <a href="{{ route('customer.purchases') }}" class="bottom-nav-item">
            <i class="fas fa-shopping-cart"></i>
            <span>Purchases</span>
        </a>
        <a href="{{ route('customer.profile') }}" class="bottom-nav-item">
            <i class="fas fa-user"></i>
            <span>Profile</span>
        </a>
        <form method="POST" action="{{ route('customer.logout') }}" class="bottom-nav-item-form" onsubmit="return confirm('Are you sure you want to logout?');">
            @csrf
            <button type="submit" class="bottom-nav-item logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </form>
    </nav>

</body>
</html>
