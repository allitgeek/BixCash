<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BixCash Admin Panel')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased">
    <div class="flex h-screen bg-gradient-to-br from-gray-50 via-blue-50/30 to-purple-50/20 overflow-hidden">
        {{-- Sidebar --}}
        <nav class="hidden lg:flex lg:flex-shrink-0">
            <div class="flex flex-col w-72">
                {{-- Sidebar content --}}
                <div class="flex flex-col flex-grow bg-gradient-to-b from-[#021c47] to-[#032a6b] overflow-y-auto border-r border-white/10 shadow-2xl">
                    {{-- Logo/Brand --}}
                    <div class="flex items-center flex-shrink-0 px-6 py-8 border-b border-white/10 bg-white/5 backdrop-blur-sm">
                        <div class="w-full text-center">
                            <h2 class="text-3xl font-bold text-white tracking-tight mb-1">BixCash</h2>
                            <p class="text-sm font-medium text-blue-200/80 uppercase tracking-wider">Admin Panel</p>
                        </div>
                    </div>

                    {{-- Navigation --}}
                    <div class="flex-1 flex flex-col px-3 py-4 space-y-1">
                        {{-- Dashboard --}}
                        <a href="{{ route('admin.dashboard') }}"
                           class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-[#76d37a] text-[#021c47] shadow-lg shadow-green-500/30' : 'text-white/80 hover:bg-white/10 hover:text-white hover:translate-x-1' }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Dashboard
                        </a>

                        @if(auth()->user() && auth()->user()->hasPermission('manage_users'))
                        {{-- Users --}}
                        <a href="{{ route('admin.users.index') }}"
                           class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-[#76d37a] text-[#021c47] shadow-lg shadow-green-500/30' : 'text-white/80 hover:bg-white/10 hover:text-white hover:translate-x-1' }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            Users
                        </a>

                        {{-- Customers --}}
                        <a href="{{ route('admin.customers.index') }}"
                           class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('admin.customers.*') ? 'bg-[#76d37a] text-[#021c47] shadow-lg shadow-green-500/30' : 'text-white/80 hover:bg-white/10 hover:text-white hover:translate-x-1' }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Customers
                        </a>

                        {{-- Partners --}}
                        <a href="{{ route('admin.partners.index') }}"
                           class="group flex items-center justify-between px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('admin.partners.*') ? 'bg-[#76d37a] text-[#021c47] shadow-lg shadow-green-500/30' : 'text-white/80 hover:bg-white/10 hover:text-white hover:translate-x-1' }}">
                            <div class="flex items-center">
                                <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Partners
                            </div>
                            @php
                                $pendingPartnersCount = \App\Models\User::whereHas('role', function($q) {
                                    $q->where('name', 'partner');
                                })->whereHas('partnerProfile', function($q) {
                                    $q->where('status', 'pending');
                                })->count();
                            @endphp
                            @if($pendingPartnersCount > 0)
                                <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-500 rounded-full min-w-[20px] shadow-lg shadow-red-500/50">{{ $pendingPartnersCount }}</span>
                            @endif
                        </a>
                        @endif

                        @if(auth()->user() && auth()->user()->hasPermission('manage_content'))
                        {{-- Hero Slides --}}
                        <a href="{{ route('admin.slides.index') }}"
                           class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('admin.slides.*') ? 'bg-[#76d37a] text-[#021c47] shadow-lg shadow-green-500/30' : 'text-white/80 hover:bg-white/10 hover:text-white hover:translate-x-1' }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Hero Slides
                        </a>

                        {{-- Categories --}}
                        <a href="{{ route('admin.categories.index') }}"
                           class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('admin.categories.*') ? 'bg-[#76d37a] text-[#021c47] shadow-lg shadow-green-500/30' : 'text-white/80 hover:bg-white/10 hover:text-white hover:translate-x-1' }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            Categories
                        </a>

                        {{-- Brands --}}
                        <a href="{{ route('admin.brands.index') }}"
                           class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('admin.brands.*') ? 'bg-[#76d37a] text-[#021c47] shadow-lg shadow-green-500/30' : 'text-white/80 hover:bg-white/10 hover:text-white hover:translate-x-1' }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            Brands
                        </a>

                        {{-- Promotions --}}
                        <a href="{{ route('admin.promotions.index') }}"
                           class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('admin.promotions.*') ? 'bg-[#76d37a] text-[#021c47] shadow-lg shadow-green-500/30' : 'text-white/80 hover:bg-white/10 hover:text-white hover:translate-x-1' }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                            </svg>
                            Promotions
                        </a>
                        @endif

                        {{-- Customer Queries --}}
                        <a href="{{ route('admin.queries.index') }}"
                           class="group flex items-center justify-between px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('admin.queries.*') ? 'bg-[#76d37a] text-[#021c47] shadow-lg shadow-green-500/30' : 'text-white/80 hover:bg-white/10 hover:text-white hover:translate-x-1' }}">
                            <div class="flex items-center">
                                <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Customer Queries
                            </div>
                            @php
                                $unreadCount = \App\Models\CustomerQuery::whereNull('read_at')->count();
                            @endphp
                            @if($unreadCount > 0)
                                <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-500 rounded-full min-w-[20px] shadow-lg shadow-red-500/50">{{ $unreadCount }}</span>
                            @endif
                        </a>

                        @if(auth()->user() && auth()->user()->hasPermission('view_analytics'))
                        {{-- Analytics --}}
                        <a href="{{ route('admin.analytics') }}"
                           class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('admin.analytics') ? 'bg-[#76d37a] text-[#021c47] shadow-lg shadow-green-500/30' : 'text-white/80 hover:bg-white/10 hover:text-white hover:translate-x-1' }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            Analytics
                        </a>

                        {{-- Reports --}}
                        <a href="{{ route('admin.reports') }}"
                           class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('admin.reports') ? 'bg-[#76d37a] text-[#021c47] shadow-lg shadow-green-500/30' : 'text-white/80 hover:bg-white/10 hover:text-white hover:translate-x-1' }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Reports
                        </a>
                        @endif

                        @if(auth()->user() && auth()->user()->hasPermission('manage_settings'))
                        {{-- Settings (with submenu) --}}
                        <div x-data="{ open: {{ request()->routeIs('admin.settings*') ? 'true' : 'false' }} }" class="space-y-1">
                            <button @click="open = !open"
                                    class="w-full group flex items-center justify-between px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('admin.settings*') ? 'bg-[#76d37a] text-[#021c47] shadow-lg shadow-green-500/30' : 'text-white/80 hover:bg-white/10 hover:text-white hover:translate-x-1' }}">
                                <div class="flex items-center">
                                    <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Settings
                                </div>
                                <svg class="h-4 w-4 transform transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open" x-collapse class="ml-11 space-y-1">
                                <a href="{{ route('admin.settings') }}"
                                   class="block px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.settings') && !request()->routeIs('admin.settings.email') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                                    General Settings
                                </a>
                                <a href="{{ route('admin.settings.email') }}"
                                   class="block px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.settings.email') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                                    Email Settings
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        {{-- Main Content Area --}}
        <div class="flex-1 overflow-auto focus:outline-none">
            {{-- Top Header --}}
            <header class="bg-white/80 backdrop-blur-xl border-b border-gray-200/50 sticky top-0 z-40 shadow-sm">
                <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between h-16">
                        {{-- Page Title --}}
                        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">@yield('page-title', 'Dashboard')</h1>

                        {{-- Right Side: User Info & Logout --}}
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-3 px-4 py-2 bg-gradient-to-r from-blue-50 to-purple-50 rounded-full border border-blue-200/50">
                                <span class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-gradient-to-r from-[#76d37a] to-[#93db4d] text-[#021c47] shadow-md">
                                    {{ auth()->user()->role->display_name ?? 'Admin' }}
                                </span>
                            </div>
                            <form method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-semibold rounded-lg text-white bg-gradient-to-r from-[#021c47] to-[#032a6b] hover:from-[#76d37a] hover:to-[#93db4d] hover:text-[#021c47] transition-all duration-200 shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Main Content --}}
            <main class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
                {{-- Flash Messages --}}
                @if (session('success'))
                    <div class="mb-6 rounded-xl bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 p-4 shadow-lg shadow-green-500/10">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-sm font-semibold text-green-900">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 rounded-xl bg-gradient-to-r from-red-50 to-pink-50 border border-red-200 p-4 shadow-lg shadow-red-500/10">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-sm font-semibold text-red-900">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                @if (session('warning'))
                    <div class="mb-6 rounded-xl bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-200 p-4 shadow-lg shadow-yellow-500/10">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <p class="text-sm font-semibold text-yellow-900">{{ session('warning') }}</p>
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 rounded-xl bg-gradient-to-r from-red-50 to-pink-50 border border-red-200 p-4 shadow-lg shadow-red-500/10">
                        <div class="flex">
                            <svg class="h-5 w-5 text-red-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <ul class="text-sm font-semibold text-red-900 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                {{-- Page Content --}}
                @yield('content')
            </main>
        </div>
    </div>

    {{-- Alpine.js for dropdown functionality --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Auto-hide alerts --}}
    <script>
        setTimeout(function() {
            const alerts = document.querySelectorAll('[class*="from-green-50"], [class*="from-red-50"], [class*="from-yellow-50"]');
            alerts.forEach(alert => {
                if (alert.parentElement.tagName === 'MAIN') {
                    alert.style.transition = 'opacity 0.5s, transform 0.5s';
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-10px)';
                    setTimeout(() => alert.remove(), 500);
                }
            });
        }, 5000);
    </script>

    @stack('scripts')
</body>
</html>
