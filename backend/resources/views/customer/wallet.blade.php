<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#1a5928">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Wallet - BixCash</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50 min-h-screen pb-24" style="margin: 0; padding: 0;">

    {{-- Header --}}
    <header class="bg-gradient-to-br from-green-900 via-green-950 to-gray-900 text-white px-4 py-8 shadow-xl">
        <div class="max-w-7xl mx-auto flex items-center gap-3">
            <a href="{{ route('customer.dashboard') }}" class="text-white hover:text-green-200 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-2xl font-bold">My Wallet</h1>
        </div>
    </header>

    {{-- Wallet Balance Card --}}
    <div class="max-w-7xl mx-auto px-4 -mt-6 relative z-10">
        <div class="bg-gradient-to-br from-[#76d37a] to-[#93db4d] rounded-2xl p-6 shadow-xl shadow-green-900/20 text-white">
            <div class="text-sm text-green-900 mb-2 text-center">Available Balance</div>
            <div class="text-4xl font-bold text-center mb-6">Rs {{ number_format($wallet->balance, 2) }}</div>

            <div class="flex gap-4 pt-6 border-t border-white/20">
                <div class="flex-1 text-center">
                    <div class="text-2xl font-bold mb-1">Rs {{ number_format($wallet->total_earned, 0) }}</div>
                    <div class="text-xs text-green-900 uppercase tracking-wide">Total Earned</div>
                </div>
                <div class="w-px bg-white/20"></div>
                <div class="flex-1 text-center">
                    <div class="text-2xl font-bold mb-1">Rs {{ number_format($wallet->total_withdrawn, 0) }}</div>
                    <div class="text-xs text-green-900 uppercase tracking-wide">Total Withdrawn</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <main class="max-w-7xl mx-auto px-4 mt-6">

        @php
            $profile = Auth::user()->customerProfile;
            $isLocked = $profile && $profile->withdrawal_locked_until && $profile->withdrawal_locked_until > now();
        @endphp

        {{-- Lock Warning --}}
        @if($isLocked)
        <div class="bg-gradient-to-r from-orange-50 to-yellow-50 rounded-xl border-2 border-orange-300 p-4 sm:p-5 mb-6 shadow-md">
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

        {{-- Request Withdrawal Section --}}
        <div class="bg-white rounded-xl border border-gray-200/60 shadow-lg shadow-green-900/5 overflow-hidden mb-6">
            <div class="px-5 py-4 border-b border-gray-200/60 bg-gradient-to-r from-green-50/70 via-green-900/5 to-transparent">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#76d37a] to-[#93db4d] flex items-center justify-center shadow-sm">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-base font-bold bg-gradient-to-r from-gray-800 to-green-900 bg-clip-text text-transparent">Request Withdrawal</h2>
                </div>
            </div>

            <div class="p-5">
                <form method="POST" action="{{ route('customer.wallet.withdraw') }}" id="withdrawalForm">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Amount (Rs.) *</label>
                        <input type="number" name="amount" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-base focus:outline-none focus:border-[#76d37a] focus:ring-4 focus:ring-green-500/10 transition-all" required min="100" step="0.01" placeholder="Minimum Rs. 100">
                        <p class="text-xs text-gray-500 mt-2">Minimum withdrawal amount is Rs. 100</p>
                    </div>
                    <button type="submit" class="w-full px-4 py-3 bg-gradient-to-r from-[#76d37a] to-[#93db4d] text-white font-semibold rounded-xl hover:from-[#5cb85c] hover:to-[#76d37a] hover:-translate-y-0.5 transition-all duration-200 shadow-sm shadow-green-500/30 hover:shadow-md hover:shadow-green-500/40 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
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
        <div class="bg-white rounded-xl border border-gray-200/60 shadow-lg shadow-green-900/5 overflow-hidden mb-6">
            <div class="px-5 py-4 border-b border-gray-200/60 bg-gradient-to-r from-green-50/70 via-green-900/5 to-transparent">
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
                                <tr class="border-b border-gray-100 hover:bg-green-50/50 transition-colors">
                                    <td class="py-4 font-semibold text-gray-800 px-5 sm:px-0">Rs {{ number_format($withdrawal->amount, 2) }}</td>
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
