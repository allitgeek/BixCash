<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#1a5928">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - BixCash</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50 min-h-screen pb-24" style="margin: 0; padding: 0;">

    @if(!$profileComplete)
    {{-- Profile Completion Modal --}}
    <div id="profileModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-[1000] p-4" role="dialog" aria-modal="true" aria-labelledby="profileModalTitle">
        <div class="bg-white rounded-2xl p-6 sm:p-8 max-w-md w-full max-h-[90vh] overflow-y-auto shadow-2xl">
            <h2 id="profileModalTitle" class="text-2xl font-bold text-gray-800 mb-2">Complete Your Profile</h2>
            <p class="text-gray-500 mb-6">Welcome! Let's set up your account</p>

            <form method="POST" action="{{ route('customer.complete-profile') }}" id="profileForm">
                @csrf
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name *</label>
                    <input type="text" name="name" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-base focus:outline-none focus:border-[#76d37a] focus:ring-4 focus:ring-green-500/10 transition-all" required placeholder="Enter your full name">
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email (Optional)</label>
                    <input type="email" name="email" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-base focus:outline-none focus:border-[#76d37a] focus:ring-4 focus:ring-green-500/10 transition-all" placeholder="your@email.com">
                    <p class="text-xs text-gray-500 mt-1">We'll use this for important updates</p>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Date of Birth (Optional)</label>
                    <input type="date" name="date_of_birth" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-base focus:outline-none focus:border-[#76d37a] focus:ring-4 focus:ring-green-500/10 transition-all" max="{{ date('Y-m-d') }}">
                </div>

                <button type="submit" class="w-full px-4 py-3 bg-gradient-to-r from-[#76d37a] to-[#93db4d] text-white font-semibold rounded-xl hover:from-[#5cb85c] hover:to-[#76d37a] hover:-translate-y-0.5 transition-all duration-200 shadow-sm shadow-green-500/30 hover:shadow-md hover:shadow-green-500/40">
                    Complete Profile
                </button>
            </form>
            <p class="text-center text-sm text-gray-500 mt-4">
                Please complete your profile to continue using the dashboard
            </p>
        </div>
    </div>
    @endif

    {{-- Header with Wallet Card --}}
    <header class="bg-gradient-to-br from-green-900 via-green-950 to-gray-900 text-white px-4 py-6 shadow-xl">
        <div class="max-w-7xl mx-auto">
            {{-- User Greeting --}}
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold mb-1">Hello, {{ explode(' ', $user->name)[0] }}! 👋</h1>
                    <p class="text-green-100 text-sm">Welcome back to your dashboard</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-[#76d37a] flex items-center justify-center text-xl font-bold shadow-lg flex-shrink-0">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            </div>

            {{-- Wallet Card - Green Gradient (BixCash Brand) --}}
            <div class="bg-gradient-to-br from-[#76d37a] to-[#93db4d] rounded-2xl p-6 shadow-xl shadow-green-900/20">
                <div class="text-sm text-green-900 mb-2">Your Balance</div>
                <div class="text-4xl font-bold text-white mb-4">Rs {{ number_format($wallet->balance, 0) }}</div>
                <div class="flex gap-3">
                    <a href="{{ route('customer.wallet') }}" class="flex-1 px-4 py-3 bg-white text-[#76d37a] font-semibold rounded-xl hover:-translate-y-1 hover:shadow-lg transition-all duration-200 text-center">
                        Withdraw
                    </a>
                    <a href="{{ route('customer.purchases') }}" class="flex-1 px-4 py-3 bg-transparent border-2 border-white text-white font-semibold rounded-xl hover:bg-white hover:text-[#76d37a] transition-all duration-200 text-center">
                        History
                    </a>
                </div>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="max-w-7xl mx-auto px-4 -mt-10 relative z-10">

        {{-- Quick Stats --}}
        <div class="grid grid-cols-3 gap-3 mb-6">
            {{-- Total Earned - Green Theme --}}
            <div class="bg-white rounded-xl p-3 sm:p-4 shadow-md hover:shadow-lg hover:-translate-y-1 transition-all duration-200 border border-gray-200/60">
                <div class="text-[10px] sm:text-xs text-gray-500 uppercase tracking-wide mb-1">Total Earned</div>
                <div class="text-lg sm:text-2xl font-bold text-gray-800">Rs {{ number_format($wallet->total_earned, 0) }}</div>
            </div>

            {{-- Withdrawn - Blue Theme --}}
            <div class="bg-white rounded-xl p-3 sm:p-4 shadow-md hover:shadow-lg hover:-translate-y-1 transition-all duration-200 border border-gray-200/60">
                <div class="text-[10px] sm:text-xs text-gray-500 uppercase tracking-wide mb-1">Withdrawn</div>
                <div class="text-lg sm:text-2xl font-bold text-gray-800">Rs {{ number_format($wallet->total_withdrawn, 0) }}</div>
            </div>

            {{-- Purchases - Purple Theme --}}
            <div class="bg-white rounded-xl p-3 sm:p-4 shadow-md hover:shadow-lg hover:-translate-y-1 transition-all duration-200 border border-gray-200/60">
                <div class="text-[10px] sm:text-xs text-gray-500 uppercase tracking-wide mb-1">Purchases</div>
                <div class="text-lg sm:text-2xl font-bold text-gray-800">{{ $recentPurchases->count() }}</div>
            </div>
        </div>

        {{-- Pending Transactions --}}
        @if(isset($pendingTransactions) && $pendingTransactions->count() > 0)
        <div class="bg-gradient-to-r from-orange-50 to-yellow-50 rounded-xl border-2 border-orange-300 p-4 sm:p-6 mb-6 shadow-lg animate-pulse">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-orange-900 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                    </svg>
                    Confirm Purchase
                </h2>
                <span class="text-sm text-orange-900 font-semibold px-3 py-1 bg-orange-200 rounded-full">{{ $pendingTransactions->count() }} pending</span>
            </div>

            @foreach($pendingTransactions as $transaction)
            <div class="transaction-confirm-card bg-white rounded-xl p-4 mb-3 last:mb-0 shadow-md" data-transaction-id="{{ $transaction->id }}" data-deadline="{{ $transaction->confirmation_deadline->timestamp }}">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <div class="text-xl font-bold text-gray-800 mb-1">Rs {{ number_format($transaction->invoice_amount, 0) }}</div>
                        <div class="text-sm text-gray-600">at {{ $transaction->partner->partnerProfile->business_name ?? 'Unknown Partner' }}</div>
                        <div class="text-xs text-gray-500 mt-1">Code: {{ $transaction->transaction_code }}</div>
                    </div>
                    <div class="countdown-timer text-center bg-red-500 text-white px-3 py-2 rounded-xl min-w-[80px]">
                        <div class="text-2xl font-bold timer-display">60</div>
                        <div class="text-[10px] uppercase tracking-wide">seconds</div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <button onclick="confirmTransaction({{ $transaction->id }})" class="btn-confirm bg-green-500 text-white px-4 py-3 rounded-xl font-semibold hover:bg-green-600 transition-all duration-200 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="confirm-spinner hidden animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="confirm-text">✓ Confirm</span>
                    </button>
                    <button onclick="showRejectModal({{ $transaction->id }})" class="btn-reject bg-red-500 text-white px-4 py-3 rounded-xl font-semibold hover:bg-red-600 transition-all duration-200 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span>✗ Reject</span>
                    </button>
                </div>
            </div>
            @endforeach

            <div class="text-center text-xs text-orange-900 mt-4 flex items-center justify-center gap-1">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                Transactions auto-confirm after 60 seconds
            </div>
        </div>
        @endif

        {{-- Recent Purchases --}}
        <div class="bg-white rounded-xl p-4 sm:p-6 mb-6 shadow-sm border border-gray-200/60">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold bg-gradient-to-r from-gray-800 to-green-900 bg-clip-text text-transparent">Recent Purchases</h2>
                <a href="{{ route('customer.purchases') }}" class="text-sm text-[#76d37a] font-semibold hover:text-[#5cb85c] transition-colors">View All →</a>
            </div>

            @if($recentPurchases->count() > 0)
                <div class="overflow-x-auto -mx-4 sm:mx-0">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b-2 border-gray-200">
                                <th class="text-left text-xs text-gray-500 uppercase tracking-wide pb-3 px-4 sm:px-0">Brand</th>
                                <th class="text-left text-xs text-gray-500 uppercase tracking-wide pb-3">Amount</th>
                                <th class="text-left text-xs text-gray-500 uppercase tracking-wide pb-3">Cashback</th>
                                <th class="text-left text-xs text-gray-500 uppercase tracking-wide pb-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @foreach($recentPurchases as $purchase)
                            <tr class="border-b border-gray-100 hover:bg-green-50/50 transition-colors">
                                <td class="py-4 px-4 sm:px-0">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center font-semibold text-gray-600 flex-shrink-0">
                                            {{ substr($purchase->brand->name ?? 'N', 0, 1) }}
                                        </div>
                                        <span class="font-medium text-gray-800">{{ $purchase->brand->name ?? 'Unknown' }}</span>
                                    </div>
                                </td>
                                <td class="py-4 font-medium text-gray-800">Rs {{ number_format($purchase->amount, 0) }}</td>
                                <td class="py-4 font-semibold text-green-600">+Rs {{ number_format($purchase->cashback_amount, 0) }}</td>
                                <td class="py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                        @if($purchase->status === 'completed') bg-green-100 text-green-700
                                        @elseif($purchase->status === 'pending') bg-yellow-100 text-yellow-700
                                        @elseif($purchase->status === 'processing') bg-green-100 text-green-700
                                        @else bg-red-100 text-red-700 @endif">
                                        {{ ucfirst($purchase->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <p class="text-gray-500">No purchases yet</p>
                </div>
            @endif
        </div>

        {{-- Recent Withdrawals --}}
        <div class="bg-white rounded-xl p-4 sm:p-6 mb-6 shadow-sm border border-gray-200/60">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold bg-gradient-to-r from-gray-800 to-green-900 bg-clip-text text-transparent">Recent Withdrawals</h2>
                <a href="{{ route('customer.wallet') }}" class="text-sm text-[#76d37a] font-semibold hover:text-[#5cb85c] transition-colors">View All →</a>
            </div>

            @if($recentWithdrawals->count() > 0)
                <div class="overflow-x-auto -mx-4 sm:mx-0">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b-2 border-gray-200">
                                <th class="text-left text-xs text-gray-500 uppercase tracking-wide pb-3 px-4 sm:px-0">Amount</th>
                                <th class="text-left text-xs text-gray-500 uppercase tracking-wide pb-3">Date</th>
                                <th class="text-left text-xs text-gray-500 uppercase tracking-wide pb-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @foreach($recentWithdrawals as $withdrawal)
                            <tr class="border-b border-gray-100 hover:bg-green-50/50 transition-colors">
                                <td class="py-4 font-semibold text-gray-800 px-4 sm:px-0">Rs {{ number_format($withdrawal->amount, 0) }}</td>
                                <td class="py-4 text-gray-600">{{ $withdrawal->created_at->format('M d, Y') }}</td>
                                <td class="py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                        @if($withdrawal->status === 'completed') bg-green-100 text-green-700
                                        @elseif($withdrawal->status === 'pending') bg-yellow-100 text-yellow-700
                                        @elseif($withdrawal->status === 'processing') bg-green-100 text-green-700
                                        @else bg-red-100 text-red-700 @endif">
                                        {{ ucfirst($withdrawal->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-gray-500">No withdrawals yet</p>
                </div>
            @endif
        </div>

    </main>

    {{-- Bottom Navigation --}}
    <nav class="fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-xl shadow-lg shadow-green-900/10 border-t border-gray-200/60 z-50">
        <div class="grid grid-cols-5 max-w-7xl mx-auto">
            {{-- Home (Active) --}}
            <a href="{{ route('customer.dashboard') }}" class="flex flex-col items-center py-3 px-2 text-white bg-gradient-to-r from-[#76d37a] to-[#93db4d] border-t-2 border-[#76d37a] transition-all duration-200">
                <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                </svg>
                <span class="text-xs font-bold">Home</span>
            </a>

            {{-- Wallet --}}
            <a href="{{ route('customer.wallet') }}" class="flex flex-col items-center py-3 px-2 text-gray-500 hover:text-[#76d37a] hover:bg-green-50/50 transition-all duration-200">
                <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-xs font-medium">Wallet</span>
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
    <div id="successMessage" class="fixed top-5 right-5 bg-green-500 text-white px-6 py-4 rounded-xl shadow-2xl z-[2000] flex items-center gap-3 animate-slideIn">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Rejection Modal --}}
    <div id="rejectModal" class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm z-[2000] items-center justify-center p-4" role="dialog" aria-modal="true" aria-labelledby="rejectModalTitle">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 sm:p-8 shadow-2xl">
            <h3 id="rejectModalTitle" class="text-2xl font-bold text-gray-800 mb-2">Reject Transaction</h3>
            <p class="text-gray-500 mb-6">Please provide a reason for rejecting this transaction:</p>

            <textarea id="rejectReason" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-base min-h-[120px] resize-y focus:outline-none focus:border-red-500 focus:ring-4 focus:ring-red-500/10 transition-all" placeholder="Enter reason..."></textarea>

            <div class="grid grid-cols-2 gap-3 mt-6">
                <button onclick="closeRejectModal()" class="px-4 py-3 bg-gray-200 text-gray-800 font-semibold rounded-xl hover:bg-gray-300 transition-colors">
                    Cancel
                </button>
                <button onclick="submitRejection()" class="px-4 py-3 bg-red-500 text-white font-semibold rounded-xl hover:bg-red-600 transition-colors flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed" id="rejectSubmitBtn">
                    <svg class="reject-spinner hidden animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="reject-text">Reject Transaction</span>
                </button>
            </div>
        </div>
    </div>

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
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        .animate-slideIn {
            animation: slideIn 0.3s ease;
        }
    </style>

    <script>
        // Profile form loading state
        document.addEventListener('DOMContentLoaded', function() {
            const profileForm = document.getElementById('profileForm');
            if (profileForm) {
                profileForm.addEventListener('submit', function(e) {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    submitBtn.textContent = 'Saving...';
                    submitBtn.disabled = true;
                });
            }
        });

        // Auto-hide success message
        @if(session('success'))
        setTimeout(() => {
            const successMsg = document.getElementById('successMessage');
            if (successMsg) {
                successMsg.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => successMsg.remove(), 300);
            }
        }, 3000);

        // Hide profile modal if completed
        const modal = document.getElementById('profileModal');
        if (modal) {
            modal.style.animation = 'fadeOut 0.3s ease';
            setTimeout(() => modal.style.display = 'none', 300);
        }
        @endif

        // Countdown timers
        function updateCountdowns() {
            const cards = document.querySelectorAll('.transaction-confirm-card');
            cards.forEach(card => {
                const deadline = parseInt(card.dataset.deadline);
                const now = Math.floor(Date.now() / 1000);
                const secondsRemaining = Math.max(0, deadline - now);

                const timerDisplay = card.querySelector('.timer-display');
                if (timerDisplay) {
                    timerDisplay.textContent = secondsRemaining;

                    // Change color based on urgency
                    const timerEl = card.querySelector('.countdown-timer');
                    if (secondsRemaining <= 10) {
                        timerEl.classList.remove('bg-orange-500');
                        timerEl.classList.add('bg-red-500');
                        timerEl.style.animation = 'pulse 1s infinite';
                    } else if (secondsRemaining <= 30) {
                        timerEl.classList.remove('bg-red-500');
                        timerEl.classList.add('bg-orange-500');
                    }

                    // Auto-remove if expired
                    if (secondsRemaining === 0) {
                        setTimeout(() => {
                            card.style.animation = 'fadeOut 0.5s ease';
                            setTimeout(() => {
                                card.remove();
                                if (document.querySelectorAll('.transaction-confirm-card').length === 0) {
                                    location.reload();
                                }
                            }, 500);
                        }, 1000);
                    }
                }
            });
        }

        // Start countdown timers
        if (document.querySelector('.transaction-confirm-card')) {
            updateCountdowns();
            setInterval(updateCountdowns, 1000);
        }

        // Confirm transaction with loading state
        async function confirmTransaction(transactionId) {
            if (!confirm('Confirm this purchase?')) return;

            const btn = event.target.closest('.btn-confirm');
            const spinner = btn.querySelector('.confirm-spinner');
            const text = btn.querySelector('.confirm-text');

            btn.disabled = true;
            spinner.classList.remove('hidden');
            text.textContent = 'Confirming...';

            try {
                const response = await fetch(`/customer/confirm-transaction/${transactionId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    alert('✓ Transaction confirmed successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                    btn.disabled = false;
                    spinner.classList.add('hidden');
                    text.textContent = '✓ Confirm';
                }
            } catch (error) {
                alert('Network error. Please try again.');
                btn.disabled = false;
                spinner.classList.add('hidden');
                text.textContent = '✓ Confirm';
            }
        }

        // Rejection modal functions
        let rejectingTransactionId = null;

        function showRejectModal(transactionId) {
            rejectingTransactionId = transactionId;
            const modal = document.getElementById('rejectModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.getElementById('rejectReason').value = '';
            document.getElementById('rejectReason').focus();
        }

        function closeRejectModal() {
            const modal = document.getElementById('rejectModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            rejectingTransactionId = null;
        }

        async function submitRejection() {
            const reason = document.getElementById('rejectReason').value.trim();

            if (!reason) {
                alert('Please provide a reason for rejection');
                return;
            }

            if (!rejectingTransactionId) return;

            const btn = document.getElementById('rejectSubmitBtn');
            const spinner = btn.querySelector('.reject-spinner');
            const text = btn.querySelector('.reject-text');

            btn.disabled = true;
            spinner.classList.remove('hidden');
            text.textContent = 'Rejecting...';

            try {
                const response = await fetch(`/customer/reject-transaction/${rejectingTransactionId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ reason })
                });

                const data = await response.json();

                if (data.success) {
                    alert('✓ Transaction rejected');
                    closeRejectModal();
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                    btn.disabled = false;
                    spinner.classList.add('hidden');
                    text.textContent = 'Reject Transaction';
                }
            } catch (error) {
                alert('Network error. Please try again.');
                btn.disabled = false;
                spinner.classList.add('hidden');
                text.textContent = 'Reject Transaction';
            }
        }

        // Close modal on background click
        document.getElementById('rejectModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeRejectModal();
            }
        });

        // Keyboard support - ESC to close modals
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                const rejectModal = document.getElementById('rejectModal');
                if (rejectModal && !rejectModal.classList.contains('hidden')) {
                    closeRejectModal();
                }
            }
        });
    </script>

</body>
</html>
