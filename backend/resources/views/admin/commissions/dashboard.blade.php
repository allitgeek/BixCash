@extends('layouts.admin')

@section('title', 'Commissions Dashboard - BixCash Admin')
@section('page-title', 'Commissions Dashboard')

@section('content')
    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-5 border-l-4 border-[#021c47]">
                <p class="text-sm font-medium text-gray-500 mb-1">Total Outstanding</p>
                <p class="text-2xl font-bold text-[#021c47]">Rs {{ number_format($totalOutstanding, 2) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-5 border-l-4 border-[#93db4d]">
                <p class="text-sm font-medium text-gray-500 mb-1">This Month</p>
                <p class="text-2xl font-bold text-[#021c47]">Rs {{ number_format($thisMonthCommission, 2) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-5 border-l-4 border-yellow-400">
                <p class="text-sm font-medium text-gray-500 mb-1">Pending Settlements</p>
                <p class="text-2xl font-bold text-[#021c47]">{{ number_format($pendingCount) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-5 border-l-4 border-emerald-500">
                <p class="text-sm font-medium text-gray-500 mb-1">Total Settled</p>
                <p class="text-2xl font-bold text-[#021c47]">Rs {{ number_format($totalSettled, 2) }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Recent Batches -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-[#021c47]">Recent Batches</h3>
                <a href="{{ route('admin.commissions.batches.index') }}" class="text-sm font-medium text-[#021c47] hover:text-[#93db4d] transition-colors">View All →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-[#021c47] text-white">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Period</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Partners</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Transactions</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Commission</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($recentBatches as $batch)
                            <tr class="hover:bg-[#93db4d]/5 transition-colors">
                                <td class="px-4 py-3">
                                    <span class="font-semibold text-gray-900">{{ $batch->formatted_period }}</span>
                                </td>
                                <td class="px-4 py-3 text-gray-600">{{ $batch->total_partners }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ number_format($batch->total_transactions) }}</td>
                                <td class="px-4 py-3">
                                    <span class="font-semibold text-[#021c47]">Rs {{ number_format($batch->total_commission_calculated, 2) }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    @if($batch->status === 'completed')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#93db4d]/20 text-[#5a8a2e]">Completed</span>
                                    @elseif($batch->status === 'processing')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">Processing</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">{{ ucfirst($batch->status) }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('admin.commissions.batches.show', $batch->id) }}" class="text-sm font-medium text-[#021c47] hover:text-[#93db4d] transition-colors">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500">No batches calculated yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-[#021c47]">Quick Actions</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.commissions.calculate') }}" method="POST" class="mb-6">
                    @csrf
                    <label class="block text-sm font-medium text-gray-700 mb-2">Calculate Commissions</label>
                    <input type="month" name="period" value="{{ date('Y-m', strtotime('-1 month')) }}"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors mb-3" required>

                    <label class="flex items-center gap-2 mb-4 cursor-pointer">
                        <input type="checkbox" name="force" value="1" class="w-4 h-4 rounded border-gray-300 text-[#93db4d] focus:ring-[#93db4d]">
                        <span class="text-sm text-gray-600">Force recalculate (if batch exists)</span>
                    </label>

                    <button type="submit" class="w-full bg-[#021c47] text-white py-2.5 px-4 rounded-lg font-medium hover:bg-[#93db4d] hover:text-[#021c47] transition-colors">
                        Calculate Now
                    </button>
                </form>

                <hr class="my-6 border-gray-200">

                <a href="{{ route('admin.commissions.partners.index') }}" class="block w-full text-center py-2.5 px-4 border border-gray-200 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors mb-3">
                    View All Partners
                </a>
                <a href="{{ route('admin.commissions.settlements.history') }}" class="block w-full text-center py-2.5 px-4 border border-gray-200 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                    Settlement History
                </a>
            </div>
        </div>
    </div>

    <!-- Top Outstanding Partners -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-[#021c47]">Top Outstanding Partners</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-[#021c47] text-white">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Partner</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Phone</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Outstanding Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($topOutstandingPartners as $item)
                        @php
                            $partner = $item->partner;
                            $profile = $partner->partnerProfile;
                        @endphp
                        <tr class="hover:bg-[#93db4d]/5 transition-colors">
                            <td class="px-4 py-3">
                                <span class="font-semibold text-gray-900">{{ $profile->business_name ?? $partner->name }}</span><br>
                                <span class="text-sm text-gray-500">{{ $partner->name }}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $partner->phone }}</td>
                            <td class="px-4 py-3">
                                <span class="font-semibold text-red-600">Rs {{ number_format($item->total_outstanding, 2) }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.commissions.partners.show', $partner->id) }}" class="text-sm font-medium text-[#021c47] hover:text-[#93db4d] transition-colors">View Details</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">No outstanding commissions</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Commission Trend Chart -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-[#021c47]">Commission Trend (Last 12 Months)</h3>
            </div>
            <div class="p-6">
                <canvas id="commissionTrendChart" class="max-h-72"></canvas>
            </div>
        </div>

        <!-- Status Breakdown Chart -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-[#021c47]">Settlement Status</h3>
            </div>
            <div class="p-6 flex justify-center items-center">
                <canvas id="statusBreakdownChart" class="max-w-[250px] max-h-[250px]"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Settlements -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-[#021c47]">Recent Settlements</h3>
            <a href="{{ route('admin.commissions.settlements.history') }}" class="text-sm font-medium text-[#021c47] hover:text-[#93db4d] transition-colors">View All →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-[#021c47] text-white">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Partner</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Period</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Method</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Processed By</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($recentSettlements as $settlement)
                        <tr class="hover:bg-[#93db4d]/5 transition-colors">
                            <td class="px-4 py-3">
                                <span class="text-gray-900">{{ $settlement->processed_at->format('M d, Y') }}</span><br>
                                <span class="text-sm text-gray-500">{{ $settlement->processed_at->format('h:i A') }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="font-semibold text-gray-900">{{ $settlement->partner->partnerProfile->business_name ?? $settlement->partner->name }}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $settlement->ledger->formatted_period }}</td>
                            <td class="px-4 py-3">
                                <span class="font-semibold text-[#93db4d]">Rs {{ number_format($settlement->amount_settled, 2) }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">{{ $settlement->formatted_payment_method }}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $settlement->processedByUser->name ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">No settlements yet</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
    // Commission Trend Chart (Line Chart)
    const trendCtx = document.getElementById('commissionTrendChart').getContext('2d');
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: @json($commissionTrend['labels']),
            datasets: [{
                label: 'Commission Amount (Rs)',
                data: @json($commissionTrend['data']),
                borderColor: '#021c47',
                backgroundColor: 'rgba(2, 28, 71, 0.1)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#021c47',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#021c47',
                    padding: 12,
                    callbacks: {
                        label: function(context) {
                            return 'Rs ' + context.parsed.y.toLocaleString('en-PK', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) { return 'Rs ' + value.toLocaleString('en-PK'); }
                    },
                    grid: { color: 'rgba(0, 0, 0, 0.05)' }
                },
                x: { grid: { display: false } }
            }
        }
    });

    // Status Breakdown Chart (Doughnut Chart)
    const statusCtx = document.getElementById('statusBreakdownChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Partial', 'Settled'],
            datasets: [{
                data: [
                    @json($statusBreakdown['pending']),
                    @json($statusBreakdown['partial']),
                    @json($statusBreakdown['settled'])
                ],
                backgroundColor: ['rgba(239, 68, 68, 0.8)', 'rgba(251, 191, 36, 0.8)', 'rgba(147, 219, 77, 0.8)'],
                borderColor: ['#ef4444', '#fbbf24', '#93db4d'],
                borderWidth: 2,
                hoverOffset: 15
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { padding: 15, usePointStyle: true, pointStyle: 'circle' }
                },
                tooltip: {
                    backgroundColor: '#021c47',
                    padding: 12,
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
