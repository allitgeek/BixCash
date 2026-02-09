@extends('layouts.admin')

@section('title', 'Batch Details - BixCash Admin')
@section('page-title', 'Commission Batch: ' . $batch->formatted_period)

@section('content')
    <!-- Batch Summary -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-6">
        <div class="bg-[#021c47] px-6 py-5">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-xl font-bold text-white mb-1">Batch #{{ $batch->id }} - {{ $batch->formatted_period }}</h2>
                    <p class="text-white/80">
                        {{ \Carbon\Carbon::parse($batch->period_start)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($batch->period_end)->format('M d, Y') }}
                    </p>
                </div>
                @if($batch->status === 'completed' && $ledgers->count() > 0)
                    <form action="{{ route('admin.commissions.batches.notify-all', $batch->id) }}" method="POST"
                          onsubmit="return confirm('Send commission notification emails to all {{ $ledgers->count() }} partners in this batch?');">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-white text-[#021c47] rounded-lg font-medium hover:bg-[#93db4d] transition-colors">
                            Notify All Partners
                        </button>
                    </form>
                @endif
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-6">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Status</p>
                    @if($batch->status === 'completed')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-[#93db4d]/20 text-[#5a8a2e]">Completed</span>
                    @elseif($batch->status === 'processing')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-700">Processing</span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-600">{{ ucfirst($batch->status) }}</span>
                    @endif
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Partners</p>
                    <p class="text-2xl font-bold text-[#021c47]">{{ number_format($batch->total_partners) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Transactions</p>
                    <p class="text-2xl font-bold text-[#021c47]">{{ number_format($batch->total_transactions) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total Amount</p>
                    <p class="text-xl font-bold text-[#021c47]">Rs {{ number_format($batch->total_transaction_amount, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Commission</p>
                    <p class="text-xl font-bold text-red-600">Rs {{ number_format($batch->total_commission_calculated, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Partner Ledgers -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-[#021c47]">Partner Commission Ledgers</h3>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-[#021c47] text-white">
                {{ $ledgers->count() }} Partners
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-[#021c47] text-white">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Partner</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Phone</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Rate</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Transactions</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Invoice Total</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Commission Owed</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Paid</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Outstanding</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($ledgers as $ledger)
                        @php
                            $partner = $ledger->partner;
                            $profile = $partner->partnerProfile;
                        @endphp
                        <tr class="hover:bg-[#93db4d]/5 transition-colors">
                            <td class="px-4 py-3">
                                <span class="font-semibold text-gray-900">{{ $profile->business_name ?? $partner->name }}</span><br>
                                <span class="text-sm text-gray-500">{{ $partner->name }}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $partner->phone }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">{{ number_format($ledger->commission_rate_used, 2) }}%</span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ number_format($ledger->total_transactions) }}</td>
                            <td class="px-4 py-3 text-gray-700">Rs {{ number_format($ledger->total_invoice_amount, 2) }}</td>
                            <td class="px-4 py-3">
                                <span class="font-semibold text-[#021c47]">Rs {{ number_format($ledger->commission_owed, 2) }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-[#93db4d]">Rs {{ number_format($ledger->amount_paid, 2) }}</span>
                            </td>
                            <td class="px-4 py-3">
                                @if($ledger->amount_outstanding > 0)
                                    <span class="font-semibold text-red-600">Rs {{ number_format($ledger->amount_outstanding, 2) }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($ledger->status === 'settled')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#93db4d]/20 text-[#5a8a2e]">Settled</span>
                                @elseif($ledger->status === 'partial')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">Partial</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Pending</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.commissions.partners.show', $partner->id) }}"
                                       class="inline-flex items-center px-3 py-1.5 border border-[#021c47] text-[#021c47] rounded-lg text-sm font-medium hover:bg-[#021c47] hover:text-white transition-colors">
                                        View
                                    </a>
                                    @if($ledger->amount_outstanding > 0)
                                        <a href="{{ route('admin.commissions.settlements.create', $ledger->id) }}"
                                           class="inline-flex items-center px-3 py-1.5 bg-[#93db4d] text-[#021c47] rounded-lg text-sm font-medium hover:bg-[#7bc62e] transition-colors">
                                            Settle
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50 font-semibold">
                    <tr>
                        <td colspan="4" class="px-4 py-3 text-right text-gray-700">TOTALS:</td>
                        <td class="px-4 py-3 text-gray-900">Rs {{ number_format($ledgers->sum('total_invoice_amount'), 2) }}</td>
                        <td class="px-4 py-3 text-[#021c47]">Rs {{ number_format($ledgers->sum('commission_owed'), 2) }}</td>
                        <td class="px-4 py-3 text-[#93db4d]">Rs {{ number_format($ledgers->sum('amount_paid'), 2) }}</td>
                        <td class="px-4 py-3 text-red-600" colspan="3">Rs {{ number_format($ledgers->sum('amount_outstanding'), 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('admin.commissions.batches.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-200 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
            ← Back to Batches
        </a>
    </div>
@endsection
