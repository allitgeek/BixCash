<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#021c47">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Purchase History - BixCash</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50 min-h-screen pb-24" style="margin: 0; padding: 0;">

    {{-- Header --}}
    <header class="bg-gradient-to-br from-blue-900 via-blue-950 to-gray-900 text-white px-4 py-6 shadow-xl">
        <div class="max-w-7xl mx-auto flex items-center gap-3">
            <a href="{{ route('customer.dashboard') }}" class="text-white hover:text-blue-200 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-2xl font-bold">Purchase History</h1>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="max-w-7xl mx-auto px-4 mt-6">

        {{-- Stats Overview --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-6">
            {{-- Total Purchases --}}
            <div class="bg-white rounded-xl p-4 shadow-md hover:shadow-lg hover:-translate-y-1 transition-all duration-200 border border-gray-200/60">
                <div class="text-xs text-gray-500 uppercase tracking-wide mb-2">Total Purchases</div>
                <div class="text-2xl font-bold text-gray-800">{{ $purchases->total() }}</div>
            </div>

            {{-- Total Spent --}}
            <div class="bg-white rounded-xl p-4 shadow-md hover:shadow-lg hover:-translate-y-1 transition-all duration-200 border border-gray-200/60">
                <div class="text-xs text-gray-500 uppercase tracking-wide mb-2">Total Spent</div>
                <div class="text-2xl font-bold text-gray-800">Rs {{ number_format($totalSpent, 0) }}</div>
            </div>

            {{-- Total Cashback --}}
            <div class="bg-white rounded-xl p-4 shadow-md hover:shadow-lg hover:-translate-y-1 transition-all duration-200 border border-gray-200/60 col-span-2 sm:col-span-1">
                <div class="text-xs text-gray-500 uppercase tracking-wide mb-2">Total Cashback</div>
                <div class="text-2xl font-bold text-green-600">Rs {{ number_format($totalCashback, 0) }}</div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="flex gap-2 mb-6 overflow-x-auto pb-2 -mx-4 px-4 sm:mx-0 sm:px-0">
            <button class="filter-btn px-4 py-2 rounded-xl font-semibold transition-all duration-200 whitespace-nowrap border-2 bg-gradient-to-r from-blue-600 to-blue-900 text-white border-blue-900 shadow-sm" data-status="all" onclick="filterPurchases('all')">
                All
            </button>
            <button class="filter-btn px-4 py-2 rounded-xl font-semibold transition-all duration-200 whitespace-nowrap border-2 border-gray-200 bg-white text-gray-700 hover:border-blue-300 hover:bg-blue-50" data-status="confirmed" onclick="filterPurchases('confirmed')">
                Confirmed
            </button>
            <button class="filter-btn px-4 py-2 rounded-xl font-semibold transition-all duration-200 whitespace-nowrap border-2 border-gray-200 bg-white text-gray-700 hover:border-blue-300 hover:bg-blue-50" data-status="pending" onclick="filterPurchases('pending')">
                Pending
            </button>
            <button class="filter-btn px-4 py-2 rounded-xl font-semibold transition-all duration-200 whitespace-nowrap border-2 border-gray-200 bg-white text-gray-700 hover:border-blue-300 hover:bg-blue-50" data-status="cancelled" onclick="filterPurchases('cancelled')">
                Cancelled
            </button>
        </div>

        {{-- Purchase List --}}
        @if($purchases->count() > 0)
            <div id="purchase-list" class="space-y-4 mb-6">
                @foreach($purchases as $purchase)
                <div class="purchase-card bg-white rounded-xl border-2 border-gray-200/60 p-4 sm:p-5 shadow-sm hover:border-blue-300 hover:shadow-lg transition-all duration-200" data-status="{{ $purchase->status }}">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-4">
                        {{-- Brand Info --}}
                        <div class="flex items-center gap-3 flex-1">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center flex-shrink-0 border border-blue-200/50">
                                @if($purchase->brand && $purchase->brand->logo)
                                    <img src="{{ asset('storage/' . $purchase->brand->logo) }}" alt="{{ $purchase->brand->name }}" class="w-full h-full object-cover rounded-xl">
                                @else
                                    <span class="text-xl font-bold text-blue-600">{{ $purchase->brand ? strtoupper(substr($purchase->brand->name, 0, 1)) : '?' }}</span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-bold text-gray-800 truncate">{{ $purchase->brand->name ?? 'Unknown Brand' }}</div>
                                <div class="text-xs text-gray-500">Order #{{ $purchase->order_id }}</div>
                            </div>
                        </div>

                        {{-- Amount --}}
                        <div class="sm:text-right">
                            <div class="text-xl font-bold text-gray-800">Rs {{ number_format($purchase->amount, 0) }}</div>
                            @if($purchase->cashback_amount > 0)
                                <div class="text-sm text-green-600 font-semibold">+Rs {{ number_format($purchase->cashback_amount, 0) }} cashback</div>
                            @endif
                        </div>
                    </div>

                    {{-- Purchase Details --}}
                    <div class="grid grid-cols-3 gap-4 pt-4 border-t border-gray-200/60">
                        <div>
                            <div class="text-xs text-gray-500 uppercase tracking-wide mb-1">Date</div>
                            <div class="text-sm font-semibold text-gray-800">{{ $purchase->purchase_date->format('M d, Y') }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 uppercase tracking-wide mb-1">Rate</div>
                            <div class="text-sm font-semibold text-gray-800">{{ number_format($purchase->cashback_percentage, 1) }}%</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 uppercase tracking-wide mb-1">Status</div>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                @if($purchase->status === 'confirmed') bg-green-100 text-green-700
                                @elseif($purchase->status === 'pending') bg-yellow-100 text-yellow-700
                                @else bg-red-100 text-red-700 @endif">
                                {{ ucfirst($purchase->status) }}
                            </span>
                        </div>
                    </div>

                    @if($purchase->description)
                    <div class="mt-4 pt-4 border-t border-gray-200/60">
                        <div class="text-xs text-gray-500 uppercase tracking-wide mb-1">Description</div>
                        <div class="text-sm text-gray-700">{{ $purchase->description }}</div>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($purchases->hasPages())
            <div class="bg-white rounded-xl border border-gray-200/60 shadow-sm p-4">
                {{ $purchases->links() }}
            </div>
            @endif
        @else
            {{-- Empty State --}}
            <div class="bg-white rounded-xl border border-gray-200/60 shadow-lg shadow-blue-900/5 p-12 text-center">
                <svg class="w-24 h-24 mx-auto text-gray-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <h3 class="text-xl font-bold text-gray-800 mb-2">No Purchases Yet</h3>
                <p class="text-gray-500 mb-6">Start shopping with our partner brands to earn cashback!</p>
                <a href="{{ route('customer.dashboard') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-900 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-blue-950 hover:-translate-y-0.5 transition-all duration-200 shadow-sm shadow-blue-500/30">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                    </svg>
                    Go to Dashboard
                </a>
            </div>
        @endif

    </main>

    {{-- Bottom Navigation (Matching Dashboard) --}}
    <nav class="fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-xl shadow-lg shadow-blue-900/10 border-t border-gray-200/60 z-50">
        <div class="grid grid-cols-5 max-w-7xl mx-auto">
            {{-- Home --}}
            <a href="{{ route('customer.dashboard') }}" class="flex flex-col items-center py-3 px-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50/50 transition-all duration-200">
                <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                </svg>
                <span class="text-xs font-medium">Home</span>
            </a>

            {{-- Wallet --}}
            <a href="{{ route('customer.wallet') }}" class="flex flex-col items-center py-3 px-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50/50 transition-all duration-200">
                <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-xs font-medium">Wallet</span>
            </a>

            {{-- Purchases (Active) --}}
            <a href="{{ route('customer.purchases') }}" class="flex flex-col items-center py-3 px-2 text-white bg-gradient-to-r from-blue-600 to-blue-900 border-t-2 border-blue-500 transition-all duration-200">
                <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path>
                </svg>
                <span class="text-xs font-bold">Purchases</span>
            </a>

            {{-- Profile --}}
            <a href="{{ route('customer.profile') }}" class="flex flex-col items-center py-3 px-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50/50 transition-all duration-200">
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

    <script>
        function filterPurchases(status) {
            // Update active button
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('bg-gradient-to-r', 'from-blue-600', 'to-blue-900', 'text-white', 'border-blue-900', 'shadow-sm');
                btn.classList.add('border-gray-200', 'bg-white', 'text-gray-700');
            });

            event.target.classList.remove('border-gray-200', 'bg-white', 'text-gray-700');
            event.target.classList.add('bg-gradient-to-r', 'from-blue-600', 'to-blue-900', 'text-white', 'border-blue-900', 'shadow-sm');

            // Filter cards
            const cards = document.querySelectorAll('.purchase-card');
            let visibleCount = 0;

            cards.forEach(card => {
                if (status === 'all' || card.dataset.status === status) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Show/hide empty message if needed
            const purchaseList = document.getElementById('purchase-list');
            if (visibleCount === 0 && purchaseList) {
                // Could add dynamic empty state here
            }
        }
    </script>

</body>
</html>
