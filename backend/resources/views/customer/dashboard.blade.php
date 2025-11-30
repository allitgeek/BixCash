<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#1a5928">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - BixCash</title>
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    {{-- Swiper CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

    <style>
        /* Hide scrollbar for brands carousel */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Professional Brand Cards */
        .brand-card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(229, 231, 235, 0.6);
            height: 180px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            will-change: transform;
        }

        .brand-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border-color: rgba(118, 211, 122, 0.3);
        }

        .brand-card img {
            max-width: 100%;
            max-height: 80px;
            object-fit: contain;
            filter: grayscale(20%);
            transition: filter 0.3s ease;
        }

        .brand-card:hover img {
            filter: grayscale(0%);
        }

        /* Brand Card Text Truncation */
        .brand-card p {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            max-height: 3rem;
        }

        /* Brand Swiper Customization */
        .brands-swiper {
            padding: 1rem 3rem;
            margin: -1rem -3rem;
        }

        .brands-swiper .swiper-slide {
            height: auto;
        }

        @media (max-width: 640px) {
            .brands-swiper {
                padding: 1rem 2.5rem;
                margin: -1rem -2.5rem;
            }
        }

        /* Animated Gradient Background */
        @keyframes gradient {
            0%, 100% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
        }

        .animate-gradient {
            background-size: 200% 200%;
            animation: gradient 15s ease infinite;
        }

        /* Professional Promotion Cards */
        .promotion-card {
            background: white;
            border-radius: 1.25rem;
            overflow: hidden;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            border: 2px solid transparent;
            will-change: transform;
        }

        .promotion-card:hover {
            transform: translateY(-12px) scale(1.03);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            border-color: rgba(255, 255, 255, 0.5);
        }

        .promotion-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(118, 211, 122, 0.1) 0%, rgba(147, 219, 77, 0.1) 100%);
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .promotion-card:hover::before {
            opacity: 1;
        }

        .promotion-logo {
            padding: 1.5rem;
            background: linear-gradient(to bottom, #ffffff, #f9fafb);
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            min-height: 120px;
            position: relative;
            overflow: hidden;
        }

        .promotion-logo::after {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at center, transparent 0%, rgba(118, 211, 122, 0.05) 100%);
        }

        .promotion-logo img {
            max-width: 100%;
            max-height: 80px;
            object-fit: contain;
            display: block;
            margin: 0 auto;
            position: relative;
            z-index: 1;
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .promotion-card:hover .promotion-logo img {
            transform: scale(1.1);
        }

        .promotion-discount {
            background: linear-gradient(135deg, #76d37a 0%, #93db4d 100%);
            padding: 0.875rem 1rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .promotion-discount::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.3), transparent);
            transform: rotate(45deg);
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% {
                transform: translateX(-100%) rotate(45deg);
            }
            100% {
                transform: translateX(100%) rotate(45deg);
            }
        }

        .discount-text {
            font-size: 1.125rem;
            font-weight: 700;
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 1;
            display: block;
        }

        /* Responsive Adjustments */
        @media (max-width: 640px) {
            .promotion-logo {
                min-height: 100px;
                padding: 1rem;
            }

            .promotion-logo img {
                max-height: 60px;
            }

            .discount-text {
                font-size: 0.875rem;
            }
        }

        /* Accessibility: Reduce motion for users who prefer it */
        @media (prefers-reduced-motion: reduce) {
            .brand-card,
            .promotion-card,
            .brand-card img,
            .promotion-logo img {
                transition: none;
                animation: none;
            }

            .brand-card:hover,
            .promotion-card:hover {
                transform: none;
            }

            .brand-card:hover img,
            .promotion-card:hover .promotion-logo img {
                transform: none;
            }

            .animate-gradient,
            .promotion-discount::before {
                animation: none;
            }
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
    </style>
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
                    <input type="text" name="name" class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl text-base focus:outline-none focus:border-[#76d37a] focus:ring-4 focus:ring-green-500/10 transition-all" required placeholder="Enter your full name">
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email (Optional)</label>
                    <input type="email" name="email" class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl text-base focus:outline-none focus:border-[#76d37a] focus:ring-4 focus:ring-green-500/10 transition-all" placeholder="your@email.com">
                    <p class="text-xs text-gray-500 mt-1">We'll use this for important updates</p>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Date of Birth (Optional)</label>
                    <input type="date" name="date_of_birth" class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl text-base focus:outline-none focus:border-[#76d37a] focus:ring-4 focus:ring-green-500/10 transition-all" max="{{ date('Y-m-d') }}">
                </div>

                <button type="submit" class="w-full px-4 py-3 bg-gradient-to-r from-[#76d37a] to-[#93db4d] text-white font-semibold rounded-2xl hover:from-[#5cb85c] hover:to-[#76d37a] hover:-translate-y-0.5 transition-all duration-200 shadow-sm shadow-green-500/30 hover:shadow-md hover:shadow-green-500/40">
                    Complete Profile
                </button>
            </form>
            <p class="text-center text-sm text-gray-500 mt-4">
                Please complete your profile to continue using the dashboard
            </p>
        </div>
    </div>
    @endif

    {{-- Minimalist Header - Brand Green with Dark Overlay --}}
    <header class="text-white px-4 py-4 shadow-lg" style="background: linear-gradient(to bottom right, rgba(0,0,0,0.15), rgba(0,0,0,0.25)), #76d37a;">
        <div class="max-w-7xl mx-auto">
            {{-- Single Row: Greeting + Balance --}}
            <div class="flex items-center justify-between mb-4">
                {{-- Left: BixCash Logo + Greeting --}}
                <div class="flex items-center gap-3">
                    {{-- BixCash Logo - Links to Main Website --}}
                    <a href="https://bixcash.com" class="flex-shrink-0 hover:opacity-80 transition-opacity" target="_blank" rel="noopener noreferrer">
                        <img src="/images/logos/logos-01.png" alt="BixCash Logo" class="h-10 w-auto brightness-0 invert">
                    </a>
                    <div>
                        <h1 class="text-base sm:text-lg font-bold whitespace-nowrap">Hello, {{ explode(' ', $user->name)[0] }}! 👋</h1>
                        <p class="text-white/80 text-xs">Shop & Earn Rewards</p>
                    </div>
                </div>

                {{-- Right: Balance Prominently --}}
                <div class="text-right">
                    <p class="text-white/80 text-xs mb-1">Your Balance</p>
                    <p class="text-3xl font-bold">Rs {{ number_format($wallet->balance, 0) }}</p>
                </div>
            </div>

            {{-- Quick Actions: 2 Buttons --}}
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('customer.wallet') }}" class="bg-white text-green-700 px-4 py-3 rounded-2xl font-semibold text-center hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                    💰 Withdraw
                </a>
                <a href="{{ route('customer.purchases') }}" class="bg-white/10 backdrop-blur border-2 border-white/30 text-white px-4 py-3 rounded-2xl font-semibold text-center hover:bg-white/20 transition-all duration-200">
                    📜 History
                </a>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="max-w-7xl mx-auto px-4 pt-6 pb-20">

        {{-- Pending Transactions --}}
        @if(isset($pendingTransactions) && $pendingTransactions->count() > 0)
        <div class="bg-gradient-to-r from-orange-50 to-yellow-50 rounded-2xl border-2 border-orange-300 p-4 sm:p-6 mb-6 shadow-lg animate-pulse">
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
            <div class="transaction-confirm-card bg-white rounded-2xl p-4 mb-3 last:mb-0 shadow-md" data-transaction-id="{{ $transaction->id }}" data-deadline="{{ $transaction->confirmation_deadline->timestamp }}">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <div class="text-xl font-bold text-gray-800 mb-1">Rs {{ number_format($transaction->invoice_amount, 0) }}</div>
                        <div class="text-sm text-gray-600">at {{ $transaction->partner->partnerProfile->business_name ?? 'Unknown Partner' }}</div>
                        <div class="text-xs text-gray-500 mt-1">Code: {{ $transaction->transaction_code }}</div>
                    </div>
                    <div class="countdown-timer text-center bg-red-500 text-white px-3 py-2 rounded-2xl min-w-[80px]">
                        <div class="text-2xl font-bold timer-display">60</div>
                        <div class="text-[10px] uppercase tracking-wide">seconds</div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <button onclick="confirmTransaction({{ $transaction->id }})" class="btn-confirm bg-green-500 text-white px-4 py-3 rounded-2xl font-semibold hover:bg-green-600 transition-all duration-200 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="confirm-spinner hidden animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="confirm-text">✓ Confirm</span>
                    </button>
                    <button onclick="showRejectModal({{ $transaction->id }})" class="btn-reject bg-red-500 text-white px-4 py-3 rounded-2xl font-semibold hover:bg-red-600 transition-all duration-200 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
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

        {{-- Brands Section - Professional Design --}}
        <div class="bg-white rounded-2xl p-6 sm:p-8 mb-6 shadow-xl border border-gray-100/60">
            {{-- Content --}}
            <div class="relative z-10">
                <div class="mb-6">
                    <h2 class="text-2xl sm:text-3xl font-bold mb-2">
                        <span class="bg-gradient-to-r from-[#76d37a] to-[#93db4d] bg-clip-text text-transparent">Shop at</span>
                        <span class="text-gray-800"> Our Partner Brands</span>
                    </h2>
                    <p class="text-sm sm:text-base text-gray-600">Earn profit share when you shop at these exclusive partner brands</p>
                </div>

                {{-- Brand Carousel with Swiper --}}
                <div id="brands-carousel" class="relative">
                    <div class="swiper brands-swiper">
                        <div class="swiper-wrapper">
                            @if($brands && $brands->count() > 0)
                                @foreach($brands as $brand)
                                    <div class="swiper-slide">
                                        <div class="brand-card">
                                            @if($brand->logo_path)
                                                <img src="{{ $brand->logo_path }}"
                                                     alt="{{ $brand->name }}"
                                                     loading="lazy"
                                                     onerror="this.src='data:image/svg+xml,{{ rawurlencode('<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'100\' height=\'100\'><rect width=\'100\' height=\'100\' fill=\'#f3f4f6\'/><text x=\'50%\' y=\'50%\' dominant-baseline=\'middle\' text-anchor=\'middle\' font-family=\'Arial\' font-size=\'40\' fill=\'#6b7280\'>'. strtoupper(substr($brand->name, 0, 1)) .'</text></svg>') }}';">
                                            @else
                                                <div class="w-20 h-20 bg-gray-100 rounded-lg flex items-center justify-center text-3xl font-bold text-gray-600">
                                                    {{ strtoupper(substr($brand->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <p class="text-sm font-semibold text-gray-800 text-center mt-3">{{ $brand->name }}</p>
                                            @if($brand->category)
                                                <p class="text-xs text-gray-500 text-center mt-1">{{ $brand->category->name }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="swiper-slide w-full">
                                    <div class="text-center py-8 text-gray-500">No brands available at the moment</div>
                                </div>
                            @endif
                        </div>
                        {{-- Navigation Arrows --}}
                        <div class="swiper-button-next !w-12 !h-12 !bg-white !rounded-full !shadow-xl after:!text-[#76d37a] after:!text-xl after:!font-bold hover:!scale-110 !transition-all"></div>
                        <div class="swiper-button-prev !w-12 !h-12 !bg-white !rounded-full !shadow-xl after:!text-[#76d37a] after:!text-xl after:!font-bold hover:!scale-110 !transition-all"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Promotions Section - Professional Design --}}
        <div class="relative rounded-2xl mb-6 shadow-xl bg-white border border-gray-100/60">
            {{-- Content --}}
            <div class="relative z-10 p-6 sm:p-8">
                <div class="text-center mb-8">
                    <div class="inline-block mb-3">
                        <span class="inline-flex items-center px-4 py-2 rounded-full bg-gradient-to-r from-[#76d37a] to-[#93db4d] text-white text-sm font-semibold shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                                <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                            </svg>
                            Limited Time Offers
                        </span>
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-800 mb-3">
                        Special Promotions
                    </h2>
                    <p class="text-base sm:text-lg text-gray-600 max-w-2xl mx-auto">
                        Enjoy up to <span class="text-2xl font-bold text-[#76d37a]">60% OFF</span> on your favorite brands nationwide, all year long
                    </p>
                </div>

                {{-- Promotions Grid --}}
                <div id="promotions-grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                    @if($promotions && $promotions->count() > 0)
                        @foreach($promotions as $promotion)
                            <div class="promotion-card">
                                <div class="promotion-logo">
                                    <img src="{{ $promotion->logo_url ?? 'https://via.placeholder.com/300x200/ffffff/76d37a?text=' . urlencode($promotion->brand_name ?? 'Promotion') }}"
                                         alt="{{ $promotion->brand_name ?? 'Promotion' }}"
                                         loading="lazy"
                                         onerror="this.src='https://via.placeholder.com/300x200/ffffff/76d37a?text={{ urlencode($promotion->brand_name ?? 'Promotion') }}';">
                                </div>
                                <div class="promotion-discount">
                                    <span class="discount-text">{{ $promotion->discount_text }}</span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-span-full text-center py-8">
                            <div class="bg-gray-50 rounded-2xl p-6">
                                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 6a2 2 0 012-2h12a2 2 0 012 2v2a2 2 0 100 4v2a2 2 0 01-2 2H4a2 2 0 01-2-2v-2a2 2 0 100-4V6z" />
                                </svg>
                                <p class="text-gray-800 font-semibold text-lg mb-2">No Promotions Available</p>
                                <p class="text-gray-600 text-sm">Check back regularly for exclusive deals and offers</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
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
    <div id="successMessage" class="fixed top-5 right-5 bg-green-500 text-white px-6 py-4 rounded-2xl shadow-2xl z-[2000] flex items-center gap-3 animate-slideIn">
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

            <textarea id="rejectReason" class="w-full px-4 py-3 border-2 border-gray-200 rounded-2xl text-base min-h-[120px] resize-y focus:outline-none focus:border-red-500 focus:ring-4 focus:ring-red-500/10 transition-all" placeholder="Enter reason..."></textarea>

            <div class="grid grid-cols-2 gap-3 mt-6">
                <button onclick="closeRejectModal()" class="px-4 py-3 bg-gray-200 text-gray-800 font-semibold rounded-2xl hover:bg-gray-300 transition-colors">
                    Cancel
                </button>
                <button onclick="submitRejection()" class="px-4 py-3 bg-red-500 text-white font-semibold rounded-2xl hover:bg-red-600 transition-colors flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed" id="rejectSubmitBtn">
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
                        // Silent auto-confirmation - no popups, no alerts
                        const transactionId = card.dataset.transactionId;

                        // Send confirmation request (don't wait for response)
                        fetch(`/customer/confirm-transaction/${transactionId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                            }
                        }).catch(() => {
                            // Silent failure - backend cron job will handle it as failsafe
                        });

                        // Immediately start card removal animation
                        setTimeout(() => {
                            card.style.animation = 'fadeOut 0.5s ease';
                            setTimeout(() => {
                                card.remove();
                                // Only reload when all transaction cards are gone
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

    {{-- Swiper JS - Load before initialization --}}
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    <script>
        // ===== BRANDS CAROUSEL INITIALIZATION =====

        // Initialize Swiper carousel for brands (server-side rendered)
        @if($brands && $brands->count() > 0)
        const brandsSwiper = new Swiper('.brands-swiper', {
            slidesPerView: 2,
            spaceBetween: 16,
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                640: {
                    slidesPerView: 3,
                    spaceBetween: 20,
                },
                768: {
                    slidesPerView: 4,
                    spaceBetween: 24,
                },
                1024: {
                    slidesPerView: 5,
                    spaceBetween: 28,
                },
            },
        });
        @endif
    </script>

    <!-- Mobile Bottom Navigation -->
    <nav class="mobile-bottom-nav">
        <a href="/#home" class="bottom-nav-item" data-section="home">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>
        <a href="/#brands" class="bottom-nav-item" data-section="brands">
            <i class="fas fa-store"></i>
            <span>Brands</span>
        </a>
        <a href="{{ route('customer.dashboard') }}" class="bottom-nav-item active">
            <i class="fas fa-user"></i>
            <span>Account</span>
        </a>
        <a href="/partner/register" class="bottom-nav-item">
            <i class="fas fa-handshake"></i>
            <span>Partner</span>
        </a>
        <a href="/#promotions" class="bottom-nav-item" data-section="promotions">
            <i class="fas fa-gift"></i>
            <span>Promos</span>
        </a>
    </nav>

</body>
</html>
