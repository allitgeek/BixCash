@extends('layouts.admin')

@section('title', 'Commission Batches - BixCash Admin')
@section('page-title', 'Commission Batches')

@section('content')
    <!-- Filters -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6">
        <div class="p-6">
            <form method="GET" action="{{ route('admin.commissions.batches.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors">
                        <option value="">All Statuses</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search Period</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="e.g., 2025-11"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors">
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-[#021c47] text-white py-2 px-4 rounded-lg font-medium hover:bg-[#93db4d] hover:text-[#021c47] transition-colors">
                        Filter
                    </button>
                    <a href="{{ route('admin.commissions.batches.index') }}" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                        Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Batches Table -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h3 class="text-lg font-semibold text-[#021c47]">All Commission Batches</h3>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-[#021c47] text-white">
                    {{ $batches->total() }} Total
                </span>
                <a href="{{ route('admin.commissions.export.batches', request()->query()) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-[#93db4d] text-[#021c47] rounded-lg font-medium hover:bg-[#7bc62e] transition-colors"
                   onclick="return confirm('Export {{ $batches->total() }} batch(es) to Excel?\n\nCurrent filters will be applied to the export.');">
                    Export to Excel
                    @if(request()->hasAny(['status', 'search']))
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-yellow-400 text-yellow-900">Filtered</span>
                    @endif
                </a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-[#021c47] text-white">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Batch ID</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Period</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Partners</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Transactions</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Total Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Commission</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Completed</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($batches as $batch)
                        <tr class="hover:bg-[#93db4d]/5 transition-colors">
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-700">#{{ $batch->id }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="font-semibold text-gray-900">{{ $batch->formatted_period }}</span><br>
                                <span class="text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($batch->period_start)->format('M d') }} -
                                    {{ \Carbon\Carbon::parse($batch->period_end)->format('M d, Y') }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ number_format($batch->total_partners) }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ number_format($batch->total_transactions) }}</td>
                            <td class="px-4 py-3 text-gray-700">Rs {{ number_format($batch->total_transaction_amount, 2) }}</td>
                            <td class="px-4 py-3">
                                <span class="font-semibold text-[#021c47]">Rs {{ number_format($batch->total_commission_calculated, 2) }}</span>
                            </td>
                            <td class="px-4 py-3">
                                @if($batch->status === 'completed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#93db4d]/20 text-[#5a8a2e]">Completed</span>
                                @elseif($batch->status === 'processing')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">Processing</span>
                                @elseif($batch->status === 'failed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Failed</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">{{ ucfirst($batch->status) }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($batch->completed_at)
                                    <span class="text-gray-900">{{ $batch->completed_at->format('M d, Y') }}</span><br>
                                    <span class="text-sm text-gray-500">{{ $batch->completed_at->format('h:i A') }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.commissions.batches.show', $batch->id) }}"
                                   class="inline-flex items-center px-3 py-1.5 border border-[#021c47] text-[#021c47] rounded-lg text-sm font-medium hover:bg-[#021c47] hover:text-white transition-colors">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-12 text-center">
                                <p class="text-gray-500 mb-4">No commission batches found</p>
                                <a href="{{ route('admin.commissions.index') }}" class="inline-flex items-center px-4 py-2 bg-[#021c47] text-white rounded-lg font-medium hover:bg-[#93db4d] hover:text-[#021c47] transition-colors">
                                    Go to Dashboard
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($batches->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $batches->links() }}
            </div>
        @endif
    </div>
@endsection
