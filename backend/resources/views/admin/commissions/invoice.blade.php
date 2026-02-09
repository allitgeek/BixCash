<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Commission Invoice - {{ $ledger->formatted_period }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: '#021c47',
                        lime: '#93db4d',
                    }
                }
            }
        }
    </script>
    <style>
        @media print {
            body { padding: 20px; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="bg-white font-sans p-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-10 pb-6 border-b-4 border-navy">
            <h1 class="text-3xl font-bold text-navy mb-2">BIXCASH COMMISSION INVOICE</h1>
            <p class="text-gray-500">Commission Statement for {{ $ledger->formatted_period }}</p>
        </div>

        <!-- Info Section -->
        <div class="grid grid-cols-2 gap-8 mb-8">
            <div>
                <h3 class="text-xs font-semibold text-navy uppercase tracking-wider mb-3">Partner Information</h3>
                <p class="font-bold text-gray-900">{{ $ledger->partner->partnerProfile->business_name ?? $ledger->partner->name }}</p>
                <p class="text-gray-600">{{ $ledger->partner->name }}</p>
                <p class="text-gray-600">{{ $ledger->partner->phone }}</p>
                <p class="text-gray-600">{{ $ledger->partner->email }}</p>
            </div>
            <div class="text-right">
                <h3 class="text-xs font-semibold text-navy uppercase tracking-wider mb-3">Invoice Details</h3>
                <p class="text-gray-700"><span class="font-medium">Invoice #:</span> INV-{{ str_pad($ledger->id, 6, '0', STR_PAD_LEFT) }}</p>
                <p class="text-gray-700"><span class="font-medium">Period:</span> {{ $ledger->formatted_period }}</p>
                <p class="text-gray-700"><span class="font-medium">Generated:</span> {{ now()->format('M d, Y h:i A') }}</p>
                <p class="mt-2">
                    <span class="font-medium">Status:</span>
                    @if($ledger->status === 'settled')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-lime/20 text-green-800">SETTLED</span>
                    @elseif($ledger->status === 'partial')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">PARTIAL</span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">PENDING</span>
                    @endif
                </p>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-4 gap-4 mb-8">
            <div class="bg-gray-50 rounded-xl p-4 text-center">
                <p class="text-xs text-gray-500 mb-1">Commission Rate</p>
                <p class="text-xl font-bold text-navy">{{ number_format($ledger->commission_rate_used, 2) }}%</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4 text-center">
                <p class="text-xs text-gray-500 mb-1">Total Transactions</p>
                <p class="text-xl font-bold text-navy">{{ number_format($ledger->total_transactions) }}</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4 text-center">
                <p class="text-xs text-gray-500 mb-1">Invoice Total</p>
                <p class="text-xl font-bold text-navy">Rs {{ number_format($ledger->total_invoice_amount, 2) }}</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4 text-center">
                <p class="text-xs text-gray-500 mb-1">Commission Owed</p>
                <p class="text-xl font-bold text-red-600">Rs {{ number_format($ledger->commission_owed, 2) }}</p>
            </div>
        </div>

        <!-- Transaction Breakdown -->
        <div class="mb-8 overflow-hidden rounded-xl border border-gray-200">
            <table class="w-full text-sm">
                <thead class="bg-navy text-white">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">Date</th>
                        <th class="px-4 py-3 text-left font-semibold">Transaction Code</th>
                        <th class="px-4 py-3 text-left font-semibold">Customer</th>
                        <th class="px-4 py-3 text-right font-semibold">Invoice Amount</th>
                        <th class="px-4 py-3 text-center font-semibold">Rate</th>
                        <th class="px-4 py-3 text-right font-semibold">Commission</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($ledger->commissionTransactions as $transaction)
                        @php
                            $partnerTxn = $transaction->partnerTransaction;
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-700">{{ \Carbon\Carbon::parse($partnerTxn->transaction_date)->format('M d, Y') }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $transaction->transaction_code }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $partnerTxn->customer->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-right text-gray-700">Rs {{ number_format($transaction->invoice_amount, 2) }}</td>
                            <td class="px-4 py-3 text-center text-gray-700">{{ number_format($transaction->commission_rate, 2) }}%</td>
                            <td class="px-4 py-3 text-right font-semibold text-gray-900">Rs {{ number_format($transaction->commission_amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50 font-bold">
                    <tr>
                        <td colspan="3" class="px-4 py-3 text-right text-gray-700">TOTAL:</td>
                        <td class="px-4 py-3 text-right text-gray-900">Rs {{ number_format($ledger->total_invoice_amount, 2) }}</td>
                        <td></td>
                        <td class="px-4 py-3 text-right text-red-600 text-lg">Rs {{ number_format($ledger->commission_owed, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Payment Summary -->
        <div class="bg-gray-50 rounded-xl p-6 mb-8">
            <div class="grid grid-cols-3 gap-6 text-center">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total Commission</p>
                    <p class="text-xl font-bold text-navy">Rs {{ number_format($ledger->commission_owed, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Amount Paid</p>
                    <p class="text-xl font-bold text-lime">Rs {{ number_format($ledger->amount_paid, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Outstanding</p>
                    <p class="text-xl font-bold text-red-600">Rs {{ number_format($ledger->amount_outstanding, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Settlement History -->
        @if($ledger->settlements->count() > 0)
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-navy mb-4">Payment History</h3>
                <div class="overflow-hidden rounded-xl border border-gray-200">
                    <table class="w-full text-sm">
                        <thead class="bg-navy text-white">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold">Date</th>
                                <th class="px-4 py-3 text-left font-semibold">Amount</th>
                                <th class="px-4 py-3 text-left font-semibold">Method</th>
                                <th class="px-4 py-3 text-left font-semibold">Reference</th>
                                <th class="px-4 py-3 text-left font-semibold">Processed By</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($ledger->settlements->where('voided_at', null) as $settlement)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-gray-700">{{ $settlement->processed_at->format('M d, Y h:i A') }}</td>
                                    <td class="px-4 py-3 font-semibold text-lime">Rs {{ number_format($settlement->amount_settled, 2) }}</td>
                                    <td class="px-4 py-3 text-gray-700">{{ $settlement->formatted_payment_method }}</td>
                                    <td class="px-4 py-3 text-gray-700">{{ $settlement->settlement_reference ?? '-' }}</td>
                                    <td class="px-4 py-3 text-gray-700">{{ $settlement->processedByUser->name ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Footer -->
        <div class="text-center pt-6 border-t-2 border-gray-200 text-gray-500 text-sm">
            <p class="font-semibold text-navy">BixCash</p>
            <p class="mt-2">This is a computer-generated invoice. For questions, contact BixCash admin.</p>
            <p class="mt-2">Generated on {{ now()->format('F d, Y \a\t h:i A') }}</p>
        </div>

        <!-- Print Button (hidden when printed) -->
        <div class="no-print text-center mt-8">
            <button onclick="window.print()" class="px-8 py-3 bg-navy text-white rounded-xl font-semibold hover:bg-lime hover:text-navy transition-colors">
                Print Invoice
            </button>
            <button onclick="window.history.back()" class="ml-3 px-8 py-3 bg-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-300 transition-colors">
                ← Back
            </button>
        </div>
    </div>
</body>
</html>
