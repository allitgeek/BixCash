<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#021c47">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Partner Dashboard - BixCash</title>
    @vite(['resources/css/app.css'])
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-50 min-h-screen pb-20">

    {{-- Clean Professional Header --}}
    <header class="bg-white border-b border-gray-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 py-3.5">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <h1 class="text-lg font-bold text-gray-900">
                        {{ $partnerProfile->business_name }}
                    </h1>
                    <p class="text-xs text-gray-500 mt-0.5 capitalize">{{ $partnerProfile->business_type }}</p>
                </div>
                <div class="px-3 py-1 rounded-full bg-green-50 text-green-700 text-xs font-semibold border border-green-200">
                    Partner
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 py-6 space-y-6">

        {{-- Compact Stats Grid --}}
        <div class="grid grid-cols-2 gap-3">
            {{-- Total Revenue Card --}}
            <div class="bg-white rounded-lg border border-gray-200 p-3 hover:border-gray-300 hover:shadow-sm transition-all duration-200">
                <div class="flex items-center gap-2 mb-1.5">
                    <div class="w-7 h-7 rounded-md bg-green-50 flex items-center justify-center text-green-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-xs text-gray-600 font-medium">Total Revenue</p>
                </div>
                <h3 class="text-xl font-bold text-gray-900">Rs {{ number_format($stats['total_revenue'], 0) }}</h3>
            </div>

            {{-- Your Profit Card --}}
            <div class="bg-white rounded-lg border border-gray-200 p-3 hover:border-gray-300 hover:shadow-sm transition-all duration-200">
                <div class="flex items-center gap-2 mb-1.5">
                    <div class="w-7 h-7 rounded-md bg-blue-50 flex items-center justify-center text-blue-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                    </div>
                    <p class="text-xs text-gray-600 font-medium">Your Profit</p>
                </div>
                <h3 class="text-xl font-bold text-gray-900">Rs {{ number_format($stats['total_profit'], 0) }}</h3>
            </div>

            {{-- Transactions Card --}}
            <div class="bg-white rounded-lg border border-gray-200 p-3 hover:border-gray-300 hover:shadow-sm transition-all duration-200">
                <div class="flex items-center gap-2 mb-1.5">
                    <div class="w-7 h-7 rounded-md bg-purple-50 flex items-center justify-center text-purple-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <p class="text-xs text-gray-600 font-medium">Transactions</p>
                </div>
                <h3 class="text-xl font-bold text-gray-900">{{ $stats['total_transactions'] }}</h3>
            </div>

            {{-- Pending Card --}}
            <div class="bg-white rounded-lg border border-gray-200 p-3 hover:border-gray-300 hover:shadow-sm transition-all duration-200">
                <div class="flex items-center gap-2 mb-1.5">
                    <div class="w-7 h-7 rounded-md bg-orange-50 flex items-center justify-center text-orange-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-xs text-gray-600 font-medium">Pending</p>
                </div>
                <h3 class="text-xl font-bold text-gray-900">{{ $stats['pending_confirmations'] }}</h3>
            </div>
        </div>

        {{-- Refined Action Section --}}
        <div class="space-y-3">
            <button onclick="openTransactionModal()" class="w-full bg-green-600 hover:bg-green-700 text-white rounded-lg py-2.5 px-4 font-semibold text-sm shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                New Transaction
            </button>

            @if($nextBatchDate)
            <div class="text-center text-sm bg-blue-50 border border-blue-200 rounded-lg py-2 px-4">
                <span class="text-gray-700">Next Profit Distribution:</span> <span class="font-semibold text-blue-700">{{ $nextBatchDate->format('M d, Y') }}</span>
            </div>
            @endif
        </div>

        {{-- Recent Transactions Card --}}
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-200">
                <h3 class="text-sm font-bold text-gray-900">Recent Transactions</h3>
            </div>

            <div class="divide-y divide-gray-200">
                @forelse($recentTransactions as $transaction)
                <div class="p-3 hover:bg-gray-50 transition-colors duration-150 flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-semibold text-gray-900 truncate">{{ $transaction->customer->name }}</h4>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $transaction->transaction_code }} • {{ $transaction->created_at->format('M d, h:i A') }}</p>
                        <span class="inline-block mt-1 px-2 py-0.5 rounded-md text-xs font-medium
                            @if($transaction->status === 'confirmed') bg-green-50 text-green-700 border border-green-200
                            @elseif($transaction->status === 'pending_confirmation') bg-yellow-50 text-yellow-700 border border-yellow-200
                            @else bg-red-50 text-red-700 border border-red-200 @endif">
                            {{ ucfirst(str_replace('_', ' ', $transaction->status)) }}
                        </span>
                    </div>
                    <div class="text-right ml-4">
                        <p class="text-base font-bold text-gray-900">Rs {{ number_format($transaction->invoice_amount, 0) }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-10">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-gray-500 font-medium text-sm">No transactions yet</p>
                    <p class="text-xs text-gray-400 mt-1">Create your first transaction above!</p>
                </div>
                @endforelse
            </div>

            @if($recentTransactions->count() > 0)
            <a href="{{ route('partner.transactions') }}" class="block w-full text-center py-2.5 bg-gray-50 hover:bg-gray-100 text-blue-600 font-medium text-xs border-t border-gray-200 transition-colors duration-150">
                View All Transactions
            </a>
            @endif
        </div>
    </div>

    {{-- Clean Bottom Navigation --}}
    <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-50">
        <div class="grid grid-cols-4 max-w-7xl mx-auto">
            <a href="{{ route('partner.dashboard') }}" class="flex flex-col items-center py-2.5 text-blue-600 bg-blue-50">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="text-xs font-semibold">Home</span>
            </a>
            <a href="{{ route('partner.transactions') }}" class="flex flex-col items-center py-2.5 text-gray-500 hover:text-gray-900 hover:bg-gray-50 transition-colors duration-150">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <span class="text-xs font-medium">History</span>
            </a>
            <a href="{{ route('partner.profits') }}" class="flex flex-col items-center py-2.5 text-gray-500 hover:text-gray-900 hover:bg-gray-50 transition-colors duration-150">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-xs font-medium">Profits</span>
            </a>
            <a href="{{ route('partner.profile') }}" class="flex flex-col items-center py-2.5 text-gray-500 hover:text-gray-900 hover:bg-gray-50 transition-colors duration-150">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="text-xs font-medium">Profile</span>
            </a>
        </div>
    </nav>

    {{-- Transaction Modal --}}
    <div id="transactionModal" class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full max-h-[90vh] overflow-y-auto shadow-2xl">
            <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between bg-gradient-to-r from-blue-50/70 to-transparent">
                <h3 class="text-xl font-bold bg-gradient-to-r from-blue-900 to-blue-700 bg-clip-text text-transparent">New Transaction</h3>
                <button onclick="closeTransactionModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="p-6">
                <div id="alertContainer"></div>

                {{-- Step 1: Search Customer --}}
                <div id="step1" class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Customer Phone Number</label>
                        <div class="flex gap-2">
                            <div class="px-4 py-3 bg-gray-100 border-2 border-gray-200 rounded-xl font-semibold text-gray-700">+92</div>
                            <input type="text" id="customerPhone" class="flex-1 px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition-all" placeholder="3001234567" maxlength="10" pattern="[0-9]{10}">
                        </div>
                    </div>
                    <button onclick="searchCustomer()" class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-xl py-3 font-semibold shadow-lg shadow-green-500/30 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200">
                        Search Customer
                    </button>
                </div>

                {{-- Step 2: Create Transaction --}}
                <div id="step2" class="hidden space-y-4">
                    <div id="customerInfoBox" class="bg-blue-50 border border-blue-200 rounded-xl p-4"></div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Invoice Amount (Rs)</label>
                        <input type="number" id="invoiceAmount" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition-all" placeholder="0" min="1" step="0.01">
                    </div>
                    <button onclick="createTransaction()" class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-xl py-3 font-semibold shadow-lg shadow-green-500/30 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200">
                        Create Transaction
                    </button>
                    <button onclick="backToStep1()" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl py-3 font-semibold transition-colors duration-200">
                        Back
                    </button>
                </div>

                {{-- Step 3: Success --}}
                <div id="step3" class="hidden text-center space-y-4">
                    <div class="text-6xl mb-4">✅</div>
                    <h3 class="text-2xl font-bold text-green-600">Transaction Created!</h3>
                    <div id="transactionSuccessInfo" class="bg-green-50 border border-green-200 rounded-xl p-4 text-left"></div>
                    <button onclick="closeTransactionModal(); location.reload();" class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-xl py-3 font-semibold shadow-lg shadow-green-500/30 hover:shadow-xl transition-all duration-200">
                        Done
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let selectedCustomer = null;

        function openTransactionModal() {
            document.getElementById('transactionModal').classList.remove('hidden');
            resetModal();
        }

        function closeTransactionModal() {
            document.getElementById('transactionModal').classList.add('hidden');
            resetModal();
        }

        function resetModal() {
            showStep(1);
            document.getElementById('customerPhone').value = '';
            document.getElementById('invoiceAmount').value = '';
            document.getElementById('alertContainer').innerHTML = '';
            selectedCustomer = null;
        }

        function showStep(step) {
            document.getElementById('step1').classList.toggle('hidden', step !== 1);
            document.getElementById('step2').classList.toggle('hidden', step !== 2);
            document.getElementById('step3').classList.toggle('hidden', step !== 3);
        }

        function backToStep1() {
            showStep(1);
            selectedCustomer = null;
        }

        function showAlert(message, type = 'error') {
            const bgColor = type === 'error' ? 'bg-red-50 border-red-200 text-red-700' : 'bg-green-50 border-green-200 text-green-700';
            const alertHtml = `<div class="mb-4 px-4 py-3 rounded-xl border ${bgColor} text-sm font-medium">${message}</div>`;
            document.getElementById('alertContainer').innerHTML = alertHtml;
        }

        async function searchCustomer() {
            const phone = document.getElementById('customerPhone').value.trim();
            if (!/^[0-9]{10}$/.test(phone)) {
                showAlert('Please enter a valid 10-digit phone number');
                return;
            }

            try {
                const response = await fetch('{{ route('partner.search-customer') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ phone })
                });

                const data = await response.json();
                if (data.success) {
                    selectedCustomer = data.customer;
                    displayCustomerInfo(data.customer);
                    showStep(2);
                    document.getElementById('alertContainer').innerHTML = '';
                } else {
                    showAlert(data.message);
                }
            } catch (error) {
                showAlert('Network error. Please try again.');
            }
        }

        function displayCustomerInfo(customer) {
            const html = `
                <h4 class="font-bold text-gray-900 mb-2">${customer.name}</h4>
                <p class="text-sm text-gray-600"><span class="font-medium">Phone:</span> ${customer.phone}</p>
                <p class="text-sm text-gray-600"><span class="font-medium">Total Purchases:</span> ${customer.stats.total_purchases}</p>
                <p class="text-sm text-gray-600"><span class="font-medium">Total Spent:</span> Rs ${parseFloat(customer.stats.total_spent || 0).toFixed(0)}</p>
            `;
            document.getElementById('customerInfoBox').innerHTML = html;
        }

        async function createTransaction() {
            const amount = document.getElementById('invoiceAmount').value;
            if (!amount || amount <= 0) {
                showAlert('Please enter a valid invoice amount');
                return;
            }
            if (!selectedCustomer) {
                showAlert('Customer not selected');
                return;
            }

            try {
                const response = await fetch('{{ route('partner.create-transaction') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        customer_id: selectedCustomer.id,
                        invoice_amount: amount
                    })
                });

                const data = await response.json();
                if (data.success) {
                    displaySuccess(data.transaction);
                    showStep(3);
                } else {
                    showAlert(data.message);
                }
            } catch (error) {
                showAlert('Network error. Please try again.');
            }
        }

        function displaySuccess(transaction) {
            const html = `
                <p class="text-sm text-gray-600 mb-1">Transaction Code</p>
                <p class="text-2xl font-bold text-green-600 mb-3">${transaction.transaction_code}</p>
                <p class="text-sm text-gray-600 mb-1"><span class="font-medium">Amount:</span> Rs ${transaction.invoice_amount}</p>
                <p class="text-sm text-gray-600 mb-1"><span class="font-medium">Customer:</span> ${transaction.customer_name}</p>
                <p class="text-xs text-orange-600 font-medium mt-3">⏱️ Customer has 60 seconds to confirm</p>
            `;
            document.getElementById('transactionSuccessInfo').innerHTML = html;
        }

        // Phone input validation
        document.getElementById('customerPhone')?.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '').substring(0, 10);
        });

        // Close modal on outside click
        document.getElementById('transactionModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeTransactionModal();
            }
        });
    </script>
</body>
</html>
