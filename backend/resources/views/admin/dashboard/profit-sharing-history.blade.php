@extends('layouts.admin')

@section('title', 'Distribution History - BixCash Admin')
@section('page-title', 'Distribution History')

@section('content')
    <div class="max-w-7xl mx-auto">
        {{-- Page Header --}}
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Profit Distribution History</h2>
                <p class="text-gray-600">View past profit sharing distributions and their details</p>
            </div>
            <a
                href="{{ route('admin.profit-sharing') }}"
                class="px-5 py-2.5 bg-gradient-to-r from-gray-600 to-gray-900 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg hover:from-gray-700 hover:to-gray-950 transition-all duration-200 hover:-translate-y-0.5"
            >
                ← Back to Profit Sharing
            </a>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            {{-- Total Distributions --}}
            <div class="bg-white rounded-xl border border-gray-200/60 p-6 shadow-sm hover:shadow-lg transition-all duration-200 hover:-translate-y-1">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-600 to-blue-900 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-sm font-medium text-gray-500 mb-1">Total Distributions</h3>
                <p class="text-3xl font-bold text-gray-900">{{ $distributions->total() }}</p>
            </div>

            {{-- Total Amount Dispersed --}}
            <div class="bg-white rounded-xl border border-gray-200/60 p-6 shadow-sm hover:shadow-lg transition-all duration-200 hover:-translate-y-1">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-green-600 to-green-900 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-sm font-medium text-gray-500 mb-1">Total Dispersed</h3>
                <p class="text-3xl font-bold text-gray-900">Rs. {{ number_format($totalDispersed, 2) }}</p>
            </div>

            {{-- Total Recipients --}}
            <div class="bg-white rounded-xl border border-gray-200/60 p-6 shadow-sm hover:shadow-lg transition-all duration-200 hover:-translate-y-1">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-purple-600 to-purple-900 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-sm font-medium text-gray-500 mb-1">Total Recipients</h3>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($totalRecipients) }}</p>
            </div>
        </div>

        {{-- Distributions Table --}}
        <div class="bg-white rounded-xl border border-gray-200/60 shadow-lg overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-blue-50/70 via-blue-900/5 to-transparent">
                <h3 class="text-xl font-bold bg-gradient-to-r from-gray-800 to-blue-900 bg-clip-text text-transparent">
                    Distribution Records
                </h3>
                <p class="text-sm text-gray-500 mt-0.5">Click on a row to view detailed breakdown</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-blue-600 to-blue-900">
                            <th class="px-6 py-4 text-left text-sm font-bold text-white uppercase tracking-wider border-r border-blue-500/30">
                                Date
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-white uppercase tracking-wider border-r border-blue-500/30">
                                Month
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-white uppercase tracking-wider border-r border-blue-500/30">
                                Total Amount
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-white uppercase tracking-wider border-r border-blue-500/30">
                                Recipients
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-white uppercase tracking-wider border-r border-blue-500/30">
                                Status
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-white uppercase tracking-wider border-r border-blue-500/30">
                                Created By
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-white uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($distributions as $distribution)
                            <tr class="hover:bg-blue-50/50 transition-colors duration-150 cursor-pointer" onclick="viewDistributionDetail({{ $distribution->id }})">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-r border-gray-200">
                                    {{ $distribution->created_at->format('M d, Y') }}
                                    <div class="text-xs text-gray-500">{{ $distribution->created_at->format('h:i A') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 border-r border-gray-200">
                                    {{ \Carbon\Carbon::parse($distribution->distribution_month . '-01')->format('F Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 border-r border-gray-200">
                                    Rs. {{ number_format($distribution->total_amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-r border-gray-200">
                                    <div class="font-semibold">{{ number_format($distribution->total_recipients) }} users</div>
                                    <div class="text-xs text-gray-500">
                                        @php
                                            $activeRecipients = collect(range(1, 7))->sum(fn($i) => $distribution->{"level_{$i}_recipients"} ?? 0);
                                        @endphp
                                        {{ $activeRecipients }} active recipients
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-200">
                                    @if($distribution->status === 'dispersed')
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            ✓ Dispersed
                                        </span>
                                        <div class="text-xs text-gray-500 mt-1">{{ $distribution->dispersed_at->format('M d, Y') }}</div>
                                    @elseif($distribution->status === 'pending')
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            ⏳ Pending
                                        </span>
                                    @else
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            ✗ Cancelled
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-r border-gray-200">
                                    <div class="font-medium">{{ $distribution->createdByAdmin->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $distribution->createdByAdmin->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    <button
                                        onclick="event.stopPropagation(); viewDistributionDetail({{ $distribution->id }})"
                                        class="text-blue-600 hover:text-blue-800 transition-colors duration-150 font-medium"
                                    >
                                        View Details →
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="text-gray-400">
                                        <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="text-lg font-medium text-gray-700">No distribution records found</p>
                                        <p class="text-sm text-gray-500 mt-1">Start by creating a new profit distribution</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($distributions->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $distributions->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function viewDistributionDetail(id) {
        window.location.href = `{{ route('admin.profit-sharing.history') }}/${id}`;
    }
</script>
@endpush
