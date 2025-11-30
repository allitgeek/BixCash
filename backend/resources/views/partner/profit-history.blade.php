<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#021c47">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Profit History - BixCash Partner</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><circle cx='50' cy='50' r='45' fill='%2376d37a'/><text x='50' y='68' font-size='55' font-weight='bold' fill='white' text-anchor='middle' font-family='Arial'>B</text></svg>">
    @vite(['resources/css/app.css'])
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gradient-to-br from-gray-50 via-blue-50/20 to-gray-50 min-h-screen pb-24" style="margin: 0; padding: 0;">

    {{-- Enhanced Header with Glassmorphism --}}
    <header class="bg-white/80 backdrop-blur-xl shadow-lg shadow-blue-900/5 border-b border-gray-200/60 sticky top-0 z-40">
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
                        <h1 class="text-base sm:text-lg font-bold bg-gradient-to-r from-blue-600 to-purple-900 bg-clip-text text-transparent truncate">
                            Profit Sharing History
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

            {{-- Stats Row: Responsive Grid --}}
            <div class="grid grid-cols-3 gap-2 sm:gap-3">
                {{-- Total Distributions --}}
                <div class="bg-gradient-to-br from-purple-50/80 to-violet-50/50 rounded-lg border-l-4 border-purple-600 p-2 sm:p-3 shadow-sm">
                    <p class="text-xs text-gray-600 font-semibold mb-0.5">Distributions</p>
                    <p class="text-lg sm:text-xl font-bold bg-gradient-to-r from-purple-600 to-violet-600 bg-clip-text text-transparent">{{ $stats['total_distributions'] }}</p>
                </div>

                {{-- Total Earned --}}
                <div class="bg-gradient-to-br from-green-50/80 to-emerald-50/50 rounded-lg border-l-4 border-green-500 p-2 sm:p-3 shadow-sm">
                    <p class="text-xs text-gray-600 font-semibold mb-0.5">Total Earned</p>
                    <p class="text-sm sm:text-base font-bold text-green-600">Rs {{ number_format($stats['total_profit'], 0) }}</p>
                </div>

                {{-- Last Payment --}}
                <div class="bg-gradient-to-br from-orange-50/80 to-amber-50/50 rounded-lg border-l-4 border-orange-600 p-2 sm:p-3 shadow-sm">
                    <p class="text-xs text-gray-600 font-semibold mb-0.5">Last Payment</p>
                    @if($stats['last_payment_date'])
                    <p class="text-[10px] sm:text-xs font-bold text-orange-600">{{ $stats['last_payment_date']->format('M d, Y') }}</p>
                    @else
                    <p class="text-xs font-bold text-orange-600">N/A</p>
                    @endif
                </div>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <div class="max-w-7xl mx-auto px-4 py-6">

        @forelse($profitDistributions as $distribution)
            {{-- Profit Distribution Cards --}}
            @php
                // Extract level from description
                preg_match('/Level (\d+)/', $distribution->description, $matches);
                $level = $matches[1] ?? '—';

                // Extract month from description, fallback to transaction date
                preg_match('/for ([A-Za-z]{4}-\d{2})/', $distribution->description, $monthMatches);
                if (isset($monthMatches[1])) {
                    $monthFormatted = \Carbon\Carbon::parse($monthMatches[1] . '-01')->format('F Y');
                } else {
                    $monthFormatted = $distribution->created_at->format('F Y');
                }
            @endphp

            <div class="mb-4">
                <div class="bg-white rounded-xl border border-gray-200/60 shadow-sm overflow-hidden hover:border-blue-600/40 hover:shadow-lg hover:shadow-blue-900/10 transition-all duration-200">

                    {{-- Card Header with Blue/Purple Gradient --}}
                    <div class="px-5 py-4 border-b border-gray-200/60 bg-gradient-to-r from-blue-50/70 via-purple-900/5 to-transparent">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-600 to-purple-700 flex items-center justify-center shadow-sm shadow-blue-900/20">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold bg-gradient-to-r from-gray-800 to-blue-900 bg-clip-text text-transparent">
                                        {{ $monthFormatted }}
                                    </h3>
                                    <p class="text-xs text-gray-500">Monthly Profit Sharing</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-gradient-to-r from-green-50 to-emerald-50 text-green-700 border border-green-200/60 shadow-sm">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Received
                            </span>
                        </div>
                    </div>

                    {{-- Card Body --}}
                    <div class="p-5">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            {{-- Amount Received --}}
                            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg p-4 border border-green-100">
                                <p class="text-xs font-medium text-gray-600 mb-1">Amount Received</p>
                                <p class="text-3xl font-bold text-green-600">Rs {{ number_format($distribution->amount, 0) }}</p>
                            </div>

                            {{-- Level --}}
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-4 border border-blue-100">
                                <p class="text-xs font-medium text-gray-600 mb-1">Your Level</p>
                                <p class="text-3xl font-bold text-blue-600">Level {{ $level }}</p>
                            </div>

                            {{-- Date Received --}}
                            <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-lg p-4 border border-purple-100">
                                <p class="text-xs font-medium text-gray-600 mb-1">Date Received</p>
                                <p class="text-lg font-bold text-purple-600">{{ $distribution->created_at->format('M d, Y') }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $distribution->created_at->format('h:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            {{-- Empty State --}}
            <div class="bg-white/90 backdrop-blur-xl rounded-xl border border-gray-200/60 shadow-sm overflow-hidden">
                <div class="text-center py-20 px-4">
                    <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gradient-to-br from-blue-50 to-purple-50 flex items-center justify-center">
                        <svg class="w-10 h-10 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-gray-900 text-lg font-semibold mb-2">No profit sharing yet</p>
                    <p class="text-gray-500 text-sm">Monthly profit sharing distributions happen when you qualify for a level</p>
                    <p class="text-gray-400 text-xs mt-1">Your earnings from the 7-level profit sharing system will appear here</p>
                </div>
            </div>
        @endforelse

        {{-- Pagination with Modern Styling --}}
        @if($profitDistributions->hasPages())
        <div class="mt-6">
            <div class="bg-white/90 backdrop-blur-xl rounded-xl border border-gray-200/60 shadow-sm px-4 py-3">
                {{ $profitDistributions->links() }}
            </div>
        </div>
        @endif
    </div>

    {{-- Glassmorphism Bottom Navigation --}}
    <nav class="fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-xl shadow-lg shadow-blue-900/10 border-t border-gray-200/60 z-50">
        <div class="grid grid-cols-5 max-w-7xl mx-auto">
            {{-- Home --}}
            <a href="{{ route('partner.dashboard') }}" class="flex flex-col items-center justify-center py-3 px-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50/50 transition-all duration-200 active:scale-95">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="text-xs font-medium">Home</span>
            </a>

            {{-- Transactions --}}
            <a href="{{ route('partner.transactions') }}" class="flex flex-col items-center justify-center py-3 px-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50/50 transition-all duration-200 active:scale-95">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <span class="text-xs font-medium">Trans</span>
            </a>

            {{-- Wallet --}}
            <a href="{{ route('partner.wallet') }}" class="flex flex-col items-center justify-center py-3 px-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50/50 transition-all duration-200 active:scale-95">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
                <span class="text-xs font-medium">Wallet</span>
            </a>

            {{-- Commissions --}}
            <a href="{{ route('partner.commissions.index') }}" class="flex flex-col items-center justify-center py-3 px-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50/50 transition-all duration-200 active:scale-95">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-xs font-medium">Comm</span>
            </a>

            {{-- Profile --}}
            <a href="{{ route('partner.profile') }}" class="flex flex-col items-center justify-center py-3 px-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50/50 transition-all duration-200 active:scale-95">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="text-xs font-medium">Profile</span>
            </a>
        </div>
    </nav>

</body>
</html>
