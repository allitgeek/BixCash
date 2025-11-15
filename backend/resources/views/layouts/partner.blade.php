<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BixCash Partner')</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>💰</text></svg>">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50">
    {{-- Top Header --}}
    <div class="bg-gradient-to-r from-blue-600 via-blue-700 to-blue-900 text-white py-4 px-4 sm:px-6 shadow-lg sticky top-0 z-40">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold">💰 BixCash</h1>
                <p class="text-xs sm:text-sm text-blue-100">@yield('page-title', 'Partner Portal')</p>
            </div>
            <form method="POST" action="{{ route('partner.logout') }}">
                @csrf
                <button type="submit" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-all duration-200 text-sm font-medium">
                    Logout
                </button>
            </form>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 mb-20">
        @yield('content')
    </div>

    {{-- Bottom Navigation --}}
    <nav class="fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-xl shadow-lg shadow-blue-900/10 border-t border-gray-200/60 z-50">
        <div class="grid grid-cols-5 max-w-7xl mx-auto">
            <a href="{{ route('partner.dashboard') }}" class="flex flex-col items-center justify-center py-3 px-2 {{ request()->routeIs('partner.dashboard') ? 'text-white bg-gradient-to-r from-blue-600 to-blue-900 border-t-2 border-blue-500' : 'text-gray-500 hover:text-blue-600 hover:bg-blue-50/50' }} transition-all duration-200">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="text-xs {{ request()->routeIs('partner.dashboard') ? 'font-bold' : 'font-medium' }}">Home</span>
            </a>
            <a href="{{ route('partner.transactions') }}" class="flex flex-col items-center justify-center py-3 px-2 {{ request()->routeIs('partner.transactions') ? 'text-white bg-gradient-to-r from-blue-600 to-blue-900 border-t-2 border-blue-500' : 'text-gray-500 hover:text-blue-600 hover:bg-blue-50/50' }} transition-all duration-200">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <span class="text-xs {{ request()->routeIs('partner.transactions') ? 'font-bold' : 'font-medium' }}">Transactions</span>
            </a>
            <a href="{{ route('partner.wallet') }}" class="flex flex-col items-center justify-center py-3 px-2 {{ request()->routeIs('partner.wallet') ? 'text-white bg-gradient-to-r from-blue-600 to-blue-900 border-t-2 border-blue-500' : 'text-gray-500 hover:text-blue-600 hover:bg-blue-50/50' }} transition-all duration-200">
                <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-xs {{ request()->routeIs('partner.wallet') ? 'font-bold' : 'font-medium' }}">Wallet</span>
            </a>
            <a href="{{ route('partner.commissions') }}" class="flex flex-col items-center justify-center py-3 px-2 {{ request()->routeIs('partner.commissions*') ? 'text-white bg-gradient-to-r from-blue-600 to-blue-900 border-t-2 border-blue-500' : 'text-gray-500 hover:text-blue-600 hover:bg-blue-50/50' }} transition-all duration-200">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-xs {{ request()->routeIs('partner.commissions*') ? 'font-bold' : 'font-medium' }}">Commissions</span>
            </a>
            <a href="{{ route('partner.profile') }}" class="flex flex-col items-center justify-center py-3 px-2 {{ request()->routeIs('partner.profile') ? 'text-white bg-gradient-to-r from-blue-600 to-blue-900 border-t-2 border-blue-500' : 'text-gray-500 hover:text-blue-600 hover:bg-blue-50/50' }} transition-all duration-200">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="text-xs {{ request()->routeIs('partner.profile') ? 'font-bold' : 'font-medium' }}">Profile</span>
            </a>
        </div>
    </nav>
</body>
</html>
