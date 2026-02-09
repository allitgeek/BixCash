@extends('layouts.admin')

@section('title', 'Commission Adjustments - BixCash Admin')
@section('page-title', 'Commission Adjustments History')

@section('content')
    <!-- Filters -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6">
        <div class="p-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Partner</label>
                    <select name="partner_id" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors">
                        <option value="">All Partners</option>
                        @foreach($partners as $p)
                            <option value="{{ $p->id }}" {{ request('partner_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->name }} ({{ $p->partnerProfile->business_name ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Adjustment Type</label>
                    <select name="adjustment_type" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors">
                        <option value="">All Types</option>
                        <option value="refund" {{ request('adjustment_type') == 'refund' ? 'selected' : '' }}>Refund</option>
                        <option value="correction" {{ request('adjustment_type') == 'correction' ? 'selected' : '' }}>Correction</option>
                        <option value="penalty" {{ request('adjustment_type') == 'penalty' ? 'selected' : '' }}>Penalty</option>
                        <option value="bonus" {{ request('adjustment_type') == 'bonus' ? 'selected' : '' }}>Bonus</option>
                        <option value="other" {{ request('adjustment_type') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-[#021c47] text-white py-2 px-4 rounded-lg font-medium hover:bg-[#93db4d] hover:text-[#021c47] transition-colors">
                        Filter
                    </button>
                    <a href="{{ route('admin.commissions.adjustments.index') }}" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                        Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Adjustments Table -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-[#021c47]">All Commission Adjustments</h3>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-[#021c47] text-white">
                {{ $adjustments->total() }} Total
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-[#021c47] text-white">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Partner</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Period</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Reason</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Processed By</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($adjustments as $adjustment)
                        <tr class="hover:bg-[#93db4d]/5 transition-colors">
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-700">#{{ $adjustment->id }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-gray-900">{{ $adjustment->processed_at->format('M d, Y') }}</span><br>
                                <span class="text-sm text-gray-500">{{ $adjustment->processed_at->format('h:i A') }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.commissions.partners.show', $adjustment->partner_id) }}" class="hover:text-[#93db4d] transition-colors">
                                    <span class="font-semibold text-gray-900">{{ $adjustment->partner->partnerProfile->business_name ?? $adjustment->partner->name }}</span><br>
                                    <span class="text-sm text-gray-500">{{ $adjustment->partner->name }}</span>
                                </a>
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.commissions.batches.show', $adjustment->ledger->batch_id) }}" class="text-[#021c47] hover:text-[#93db4d] transition-colors font-medium">
                                    {{ $adjustment->ledger->formatted_period }}
                                </a>
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $typeConfig = [
                                        'refund' => ['color' => 'red', 'label' => 'Refund'],
                                        'correction' => ['color' => 'yellow', 'label' => 'Correction'],
                                        'penalty' => ['color' => 'gray', 'label' => 'Penalty'],
                                        'bonus' => ['color' => 'green', 'label' => 'Bonus'],
                                        'other' => ['color' => 'gray', 'label' => 'Other'],
                                    ];
                                    $config = $typeConfig[$adjustment->adjustment_type] ?? $typeConfig['other'];
                                @endphp
                                @if($config['color'] === 'red')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">{{ $config['label'] }}</span>
                                @elseif($config['color'] === 'yellow')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">{{ $config['label'] }}</span>
                                @elseif($config['color'] === 'green')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#93db4d]/20 text-[#5a8a2e]">{{ $config['label'] }}</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">{{ $config['label'] }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="font-semibold {{ $adjustment->amount_settled >= 0 ? 'text-[#93db4d]' : 'text-red-600' }}">
                                    Rs {{ number_format($adjustment->amount_settled, 2) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 max-w-xs">
                                <span class="text-sm text-gray-600">{{ Str::limit($adjustment->adjustment_reason, 60) }}</span>
                                @if(strlen($adjustment->adjustment_reason) > 60)
                                    <button type="button"
                                            class="text-sm text-[#021c47] hover:text-[#93db4d] font-medium"
                                            onclick="alert('{{ addslashes($adjustment->adjustment_reason) }}')">
                                        Read more
                                    </button>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $adjustment->processedByUser->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.commissions.partners.show', $adjustment->partner_id) }}"
                                   class="inline-flex items-center px-3 py-1.5 border border-[#021c47] text-[#021c47] rounded-lg text-sm font-medium hover:bg-[#021c47] hover:text-white transition-colors">
                                    View Ledger
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-12 text-center text-gray-500">No adjustments found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($adjustments->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $adjustments->links() }}
            </div>
        @endif
    </div>
@endsection
