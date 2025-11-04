@extends('layouts.admin')

@section('title', 'Distribution Detail - BixCash Admin')
@section('page-title', 'Distribution Detail')

@section('content')
    <div class="max-w-7xl mx-auto">
        {{-- Page Header --}}
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">
                    Distribution Detail - {{ \Carbon\Carbon::parse($distribution->distribution_month . '-01')->format('F Y') }}
                </h2>
                <p class="text-gray-600">View detailed breakdown of this profit distribution</p>
            </div>
            <a
                href="{{ route('admin.profit-sharing.history') }}"
                class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-900 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg hover:from-blue-700 hover:to-blue-950 transition-all duration-200 hover:-translate-y-0.5"
            >
                ← Back to History
            </a>
        </div>

        {{-- Distribution Summary Card --}}
        <div class="bg-white rounded-xl border border-gray-200/60 shadow-lg overflow-hidden mb-6">
            <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-blue-50/70 via-blue-900/5 to-transparent">
                <h3 class="text-xl font-bold bg-gradient-to-r from-gray-800 to-blue-900 bg-clip-text text-transparent">
                    Distribution Summary
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    {{-- Total Amount --}}
                    <div>
                        <label class="text-sm font-medium text-gray-500 block mb-2">Total Amount</label>
                        <p class="text-2xl font-bold text-gray-900">Rs. {{ number_format($distribution->total_amount, 2) }}</p>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="text-sm font-medium text-gray-500 block mb-2">Status</label>
                        @if($distribution->status === 'dispersed')
                            <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-lg bg-green-100 text-green-800">
                                ✓ Dispersed
                            </span>
                        @elseif($distribution->status === 'pending')
                            <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-lg bg-yellow-100 text-yellow-800">
                                ⏳ Pending
                            </span>
                        @else
                            <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-lg bg-red-100 text-red-800">
                                ✗ Cancelled
                            </span>
                        @endif
                    </div>

                    {{-- Total Recipients --}}
                    <div>
                        <label class="text-sm font-medium text-gray-500 block mb-2">Total Recipients</label>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($distribution->total_recipients) }}</p>
                        <p class="text-xs text-gray-500 mt-1">Active users who received funds</p>
                    </div>

                    {{-- Created Date --}}
                    <div>
                        <label class="text-sm font-medium text-gray-500 block mb-2">Created Date</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $distribution->created_at->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $distribution->created_at->format('h:i A') }}</p>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Created By --}}
                        <div>
                            <label class="text-sm font-medium text-gray-500 block mb-2">Created By Admin</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $distribution->createdByAdmin->name }}</p>
                            <p class="text-xs text-gray-500">{{ $distribution->createdByAdmin->email }}</p>
                        </div>

                        {{-- Dispersed Date --}}
                        @if($distribution->status === 'dispersed' && $distribution->dispersed_at)
                            <div>
                                <label class="text-sm font-medium text-gray-500 block mb-2">Dispersed Date</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $distribution->dispersed_at->format('M d, Y') }}</p>
                                <p class="text-xs text-gray-500">{{ $distribution->dispersed_at->format('h:i A') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Level Breakdown Table --}}
        <div class="bg-white rounded-xl border border-gray-200/60 shadow-lg overflow-hidden mb-6">
            <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-purple-50/70 via-purple-900/5 to-transparent">
                <h3 class="text-xl font-bold bg-gradient-to-r from-gray-800 to-purple-900 bg-clip-text text-transparent">
                    Level Breakdown
                </h3>
                <p class="text-sm text-gray-500 mt-0.5">Distribution across 7 levels</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-purple-600 to-purple-900">
                            <th class="px-6 py-4 text-left text-sm font-bold text-white uppercase tracking-wider border-r border-purple-500/30">
                                Level
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-white uppercase tracking-wider border-r border-purple-500/30">
                                Amount
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-white uppercase tracking-wider border-r border-purple-500/30">
                                Percentage
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-white uppercase tracking-wider border-r border-purple-500/30">
                                Per Customer
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-white uppercase tracking-wider">
                                Recipients
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($levelBreakdown as $level => $data)
                            @php
                                $hasData = $data['amount'] > 0 || $data['recipients'] > 0;
                                $rowClass = $level % 2 === 0 ? 'bg-gray-50/50' : '';
                            @endphp
                            <tr class="{{ $rowClass }} {{ $hasData ? 'hover:bg-purple-50/50' : 'opacity-50' }} transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 border-r border-gray-200">
                                    Level {{ $level }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 border-r border-gray-200">
                                    Rs. {{ number_format($data['amount'], 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-r border-gray-200">
                                    {{ number_format($data['percentage'], 2) }}%
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-r border-gray-200">
                                    Rs. {{ number_format($data['per_customer'], 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ number_format($data['recipients']) }}
                                    @if($data['recipients'] > 0)
                                        <span class="text-green-600">✓</span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-gradient-to-r from-gray-100 to-gray-200 border-t-2 border-gray-300">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 border-r border-gray-300">
                                Total:
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 border-r border-gray-300">
                                Rs. {{ number_format($distribution->total_amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 border-r border-gray-300">
                                {{ number_format(collect($levelBreakdown)->sum('percentage'), 2) }}%
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 border-r border-gray-300">
                                —
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                {{ number_format($distribution->total_recipients) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Transactions List --}}
        <div class="bg-white rounded-xl border border-gray-200/60 shadow-lg overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-green-50/70 via-green-900/5 to-transparent">
                <h3 class="text-xl font-bold bg-gradient-to-r from-gray-800 to-green-900 bg-clip-text text-transparent">
                    Recipients & Transactions
                </h3>
                <p class="text-sm text-gray-500 mt-0.5">All users who received funds from this distribution</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-green-600 to-green-900">
                            <th class="px-6 py-4 text-left text-sm font-bold text-white uppercase tracking-wider border-r border-green-500/30">
                                User
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-white uppercase tracking-wider border-r border-green-500/30">
                                Type
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-white uppercase tracking-wider border-r border-green-500/30">
                                Amount Received
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-white uppercase tracking-wider border-r border-green-500/30">
                                Level
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-white uppercase tracking-wider">
                                Date/Time
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($transactions as $transaction)
                            <tr class="hover:bg-green-50/50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap border-r border-gray-200">
                                    <div class="text-sm font-semibold text-gray-900">{{ $transaction->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $transaction->user->email }}</div>
                                    @if($transaction->user->customerProfile)
                                        <div class="text-xs text-gray-500">Phone: {{ $transaction->user->customerProfile->phone }}</div>
                                    @elseif($transaction->user->partnerProfile)
                                        <div class="text-xs text-gray-500">Business: {{ $transaction->user->partnerProfile->business_name }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-200">
                                    @if($transaction->user->isCustomer())
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Customer
                                        </span>
                                    @elseif($transaction->user->isPartner())
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                            Partner
                                        </span>
                                    @else
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            Other
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600 border-r border-gray-200">
                                    + Rs. {{ number_format($transaction->amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border-r border-gray-200">
                                    @php
                                        // Extract level from description
                                        preg_match('/Level (\d+)/', $transaction->description, $matches);
                                        $level = $matches[1] ?? '—';
                                    @endphp
                                    Level {{ $level }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $transaction->created_at->format('M d, Y') }}
                                    <div class="text-xs text-gray-500">{{ $transaction->created_at->format('h:i A') }}</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="text-gray-400">
                                        <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="text-lg font-medium text-gray-700">No transactions found</p>
                                        <p class="text-sm text-gray-500 mt-1">This distribution has no recipient transactions</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($transactions->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
