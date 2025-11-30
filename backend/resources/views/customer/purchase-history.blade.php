<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#1a5928">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Purchase History - BixCash</title>
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <style>
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
</head>
<body class="bg-gray-50 min-h-screen pb-24" style="margin: 0; padding: 0;">

    {{-- Header - Integrated Stats Design (Like Dashboard) --}}
    <header class="text-white px-4 py-4 shadow-lg" style="background: linear-gradient(to bottom right, rgba(0,0,0,0.15), rgba(0,0,0,0.25)), #76d37a;">
        <div class="max-w-7xl mx-auto">
            {{-- Row 1: Logo + Title + Back Button --}}
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    {{-- BixCash Logo - Links to Main Website --}}
                    <a href="https://bixcash.com" class="flex-shrink-0 hover:opacity-80 transition-opacity" target="_blank" rel="noopener noreferrer">
                        <img src="/images/logos/logos-01.png" alt="BixCash Logo" class="h-10 w-auto brightness-0 invert">
                    </a>
                    <h1 class="text-base sm:text-lg font-bold whitespace-nowrap">Purchase History</h1>
                </div>
                {{-- Back to Dashboard Button --}}
                <a href="{{ route('customer.dashboard') }}" class="w-10 h-10 rounded-full bg-white flex items-center justify-center hover:shadow-xl hover:scale-110 transition-all duration-200 shadow-md flex-shrink-0">
                    <svg class="w-5 h-5 text-[#76d37a]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
            </div>

            {{-- Row 2: Stats (Total Purchases + Total Spent) --}}
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-white/10 backdrop-blur border border-white/20 text-white px-4 py-3 rounded-2xl text-center hover:bg-white/15 transition-all duration-200">
                    <p class="text-2xl font-bold mb-1">{{ $purchases->total() }}</p>
                    <p class="text-xs text-white/80 uppercase tracking-wide">Total Purchases</p>
                </div>
                <div class="bg-white/10 backdrop-blur border border-white/20 text-white px-4 py-3 rounded-2xl text-center hover:bg-white/15 transition-all duration-200">
                    <p class="text-2xl font-bold mb-1">Rs {{ number_format($totalSpent, 0) }}</p>
                    <p class="text-xs text-white/80 uppercase tracking-wide">Total Spent</p>
                </div>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <div class="max-w-7xl mx-auto px-4 pt-6 pb-20">

        {{-- Purchase List Card --}}
        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-md overflow-hidden">

            {{-- Card Header --}}
            <div class="px-4 py-3 border-b border-gray-200/60 bg-gradient-to-r from-green-50/70 via-green-900/5 to-transparent">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#76d37a] to-[#93db4d] flex items-center justify-center shadow-sm">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold bg-gradient-to-r from-gray-800 to-green-900 bg-clip-text text-transparent">All Purchases</h3>
                </div>
            </div>

            {{-- Purchase Items --}}
            <div class="divide-y divide-gray-200/60">
                @forelse($purchases as $purchase)
                <div class="group p-3 hover:bg-green-50/50 transition-colors duration-150">
                    <div class="flex items-center justify-between gap-4">
                        {{-- Purchase Info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <h4 class="text-sm font-semibold text-gray-900 truncate">
                                    @if($purchase->partnerTransaction?->partner?->partnerProfile?->business_name)
                                        {{ $purchase->partnerTransaction->partner->partnerProfile->business_name }}
                                    @elseif($purchase->brand?->name)
                                        {{ $purchase->brand->name }}
                                    @else
                                        Unknown Partner
                                    @endif
                                </h4>
                                @if($purchase->status === 'confirmed')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-gradient-to-r from-green-50 to-emerald-50 text-green-700 border border-green-200/60 shadow-sm shadow-green-900/5">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    Confirmed
                                </span>
                                @elseif($purchase->status === 'pending')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-gradient-to-r from-blue-50 to-cyan-50 text-blue-700 border border-blue-200/60 shadow-sm shadow-blue-900/5">
                                    <svg class="w-3 h-3 mr-1 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Processing
                                </span>
                                @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-gradient-to-r from-red-50 to-rose-50 text-red-700 border border-red-200/60 shadow-sm">
                                    {{ ucfirst($purchase->status) }}
                                </span>
                                @endif
                            </div>
                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <span class="font-mono">Order #{{ $purchase->order_id }}</span>
                                <span class="text-gray-400">•</span>
                                <span>{{ $purchase->purchase_date->format('M d, Y h:i A') }}</span>
                            </div>
                        </div>

                        {{-- Purchase Amount --}}
                        <div class="text-right">
                            <div class="text-lg font-bold text-gray-900">
                                Rs {{ number_format($purchase->amount, 0) }}
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-16">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-blue-50 flex items-center justify-center">
                        <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <p class="text-gray-400 text-sm">No purchases yet</p>
                    <p class="text-gray-400 text-xs mt-1">Start shopping with our partner brands and earn monthly profit sharing!</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Pagination with Modern Styling --}}
        @if($purchases->hasPages())
        <div class="mt-6">
            <div class="bg-white/90 backdrop-blur-xl rounded-xl border border-gray-200/60 shadow-sm px-4 py-3">
                {{ $purchases->links() }}
            </div>
        </div>
        @endif
    </div>

    {{-- Bottom Navigation --}}
    <nav class="fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-xl shadow-lg shadow-green-900/10 border-t border-gray-200/60 z-50">
        <div class="grid grid-cols-5 max-w-7xl mx-auto">
            {{-- Home --}}
            <a href="{{ route('customer.dashboard') }}" class="flex flex-col items-center py-3 px-2 text-gray-500 hover:text-[#76d37a] hover:bg-green-50/50 transition-all duration-200">
                <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                </svg>
                <span class="text-xs font-medium">Home</span>
            </a>

            {{-- Wallet --}}
            <a href="{{ route('customer.wallet') }}" class="flex flex-col items-center py-3 px-2 text-gray-500 hover:text-[#76d37a] hover:bg-green-50/50 transition-all duration-200">
                <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-xs font-medium">Wallet</span>
            </a>

            {{-- Purchases (Active) --}}
            <a href="{{ route('customer.purchases') }}" class="flex flex-col items-center py-3 px-2 text-white bg-gradient-to-r from-[#76d37a] to-[#93db4d] border-t-2 border-[#76d37a] transition-all duration-200">
                <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path>
                </svg>
                <span class="text-xs font-bold">Purchases</span>
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

    <!-- Mobile Bottom Navigation -->
    <nav class="mobile-bottom-nav">
        <a href="{{ route('customer.dashboard') }}" class="bottom-nav-item">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('customer.wallet') }}" class="bottom-nav-item">
            <i class="fas fa-wallet"></i>
            <span>Wallet</span>
        </a>
        <a href="{{ route('customer.purchases') }}" class="bottom-nav-item active">
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
