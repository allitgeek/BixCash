@extends('layouts.admin')

@section('title', 'Partner Transactions - BixCash Admin')
@section('page-title', 'Partner Transactions')

@section('content')
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-[#021c47]">{{ $partner->partnerProfile->business_name ?? $partner->name }}</h3>
                <p class="text-sm text-gray-500 mt-1">{{ $partner->phone }}</p>
            </div>
            <a href="{{ route('admin.partners.show', $partner) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Details
            </a>
        </div>
        
        <div class="p-6">
            @if($transactions->count() > 0)
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-[#021c47] text-white">
                                <th class="px-4 py-3 text-left text-sm font-semibold">Transaction Code</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold">Customer</th>
                                <th class="px-4 py-3 text-right text-sm font-semibold">Amount</th>
                                <th class="px-4 py-3 text-right text-sm font-semibold">Partner Profit</th>
                                <th class="px-4 py-3 text-center text-sm font-semibold">Status</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($transactions as $transaction)
                                <tr class="hover:bg-[#93db4d]/5 transition-colors">
                                    <td class="px-4 py-3">
                                        <span class="font-semibold text-[#021c47]">{{ $transaction->transaction_code }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-sm text-[#021c47]">{{ $transaction->customer->name ?? '-' }}</div>
                                        @if($transaction->customer->phone ?? null)
                                            <div class="text-xs text-gray-400 mt-0.5">{{ $transaction->customer->phone }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <span class="font-medium text-[#021c47]">Rs. {{ number_format($transaction->invoice_amount, 0) }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <span class="font-semibold text-[#93db4d]">Rs. {{ number_format($transaction->partner_profit_share, 0) }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if($transaction->status === 'confirmed')
                                            <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full bg-[#93db4d]/20 text-[#65a030]">Confirmed</span>
                                        @elseif($transaction->status === 'pending_confirmation')
                                            <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">Pending</span>
                                        @elseif($transaction->status === 'rejected')
                                            <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-600">Rejected</span>
                                        @else
                                            <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">{{ ucfirst($transaction->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-sm text-[#021c47]">{{ $transaction->transaction_date->format('M j, Y') }}</div>
                                        <div class="text-xs text-gray-400 mt-0.5">{{ $transaction->transaction_date->format('g:i A') }}</div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($transactions->hasPages())
                    <div class="mt-6 flex justify-center">
                        {{ $transactions->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    <h4 class="text-lg font-semibold text-[#021c47] mb-2">No transactions found</h4>
                    <p class="text-gray-500">This partner hasn't created any transactions yet.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
