@extends('layouts.admin')

@section('title', ($partner->partnerProfile->business_name ?? $partner->name) . ' - Commissions')
@section('page-title', 'Partner Commission Details')

@section('content')
    <!-- Partner Header -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6">
        <div class="p-6">
            <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-[#021c47] mb-2">{{ $partner->partnerProfile->business_name ?? $partner->name }}</h2>
                    <p class="text-gray-600">
                        {{ $partner->name }} |
                        {{ $partner->phone }} |
                        Commission Rate: <span class="font-semibold text-[#021c47]">{{ number_format($partner->partnerProfile->commission_rate ?? 0, 2) }}%</span>
                    </p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.commissions.export.partner', $partner->id) }}"
                       class="inline-flex items-center px-4 py-2 bg-[#93db4d] text-[#021c47] rounded-lg font-medium hover:bg-[#7bc62e] transition-colors"
                       onclick="return confirm('Export complete commission report for {{ $partner->partnerProfile->business_name ?? $partner->name }} to Excel?\n\nThis includes all ledgers, transactions, and settlement history.');">
                        Export Report
                    </a>
                    <a href="{{ route('admin.partners.show', $partner->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-200 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                        View Partner Profile →
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-5 border-l-4 border-[#021c47]">
                <p class="text-sm font-medium text-gray-500 mb-1">Total Commission Owed</p>
                <p class="text-2xl font-bold text-[#021c47]">Rs {{ number_format($totalCommissionOwed, 2) }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-5 border-l-4 border-[#93db4d]">
                <p class="text-sm font-medium text-gray-500 mb-1">Total Paid</p>
                <p class="text-2xl font-bold text-[#93db4d]">Rs {{ number_format($totalPaid, 2) }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-5 border-l-4 border-red-500">
                <p class="text-sm font-medium text-gray-500 mb-1">Outstanding</p>
                <p class="text-2xl font-bold text-red-600">Rs {{ number_format($totalOutstanding, 2) }}</p>
            </div>
        </div>
    </div>

    <!-- Ledgers Table -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-[#021c47]">Commission Ledgers by Period</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-[#021c47] text-white">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Period</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Rate</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Transactions</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Invoice Total</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Commission</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Paid</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Outstanding</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($ledgers as $ledger)
                        <tr class="hover:bg-[#93db4d]/5 transition-colors">
                            <td class="px-4 py-3">
                                <span class="font-semibold text-gray-900">{{ $ledger->formatted_period }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">{{ number_format($ledger->commission_rate_used, 2) }}%</span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ number_format($ledger->total_transactions) }}</td>
                            <td class="px-4 py-3 text-gray-700">Rs {{ number_format($ledger->total_invoice_amount, 2) }}</td>
                            <td class="px-4 py-3">
                                <span class="font-semibold text-[#021c47]">Rs {{ number_format($ledger->commission_owed, 2) }}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">Rs {{ number_format($ledger->amount_paid, 2) }}</td>
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
                                    @if($ledger->amount_outstanding > 0)
                                        <a href="{{ route('admin.commissions.settlements.create', $ledger->id) }}"
                                           class="inline-flex items-center px-3 py-1.5 bg-[#93db4d] text-[#021c47] rounded-lg text-sm font-medium hover:bg-[#7bc62e] transition-colors">
                                            Settle
                                        </a>
                                    @endif
                                    <a href="{{ route('admin.commissions.invoice.download', $ledger->id) }}"
                                       class="inline-flex items-center px-3 py-1.5 border border-gray-200 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors" target="_blank">
                                        Invoice
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Settlement History -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-[#021c47]">Settlement History</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-[#021c47] text-white">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Period</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Method</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Reference</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Processed By</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Notes</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($settlements as $settlement)
                        <tr class="hover:bg-[#93db4d]/5 transition-colors">
                            <td class="px-4 py-3">
                                <span class="text-gray-900">{{ $settlement->processed_at->format('M d, Y') }}</span><br>
                                <span class="text-sm text-gray-500">{{ $settlement->processed_at->format('h:i A') }}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $settlement->ledger->formatted_period }}</td>
                            <td class="px-4 py-3">
                                <span class="font-semibold text-[#93db4d]">Rs {{ number_format($settlement->amount_settled, 2) }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">{{ $settlement->formatted_payment_method }}</span>
                                @if($settlement->adjustment_type)
                                    <span class="block mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $settlement->adjustment_type_badge['color'] }}-100 text-{{ $settlement->adjustment_type_badge['color'] }}-700">
                                        {{ $settlement->adjustment_type_badge['label'] }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $settlement->settlement_reference ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $settlement->processedByUser->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3">
                                @if($settlement->admin_notes)
                                    <span class="text-sm text-gray-600">{{ Str::limit($settlement->admin_notes, 50) }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($settlement->canBeVoided())
                                    <button type="button"
                                            class="inline-flex items-center px-3 py-1.5 border border-red-500 text-red-500 rounded-lg text-sm font-medium hover:bg-red-500 hover:text-white transition-colors"
                                            onclick="openVoidModal({{ $settlement->id }}, {{ $settlement->amount_settled }})">
                                        Void
                                    </button>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-gray-500">No settlements recorded yet</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('admin.commissions.partners.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-200 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
            ← Back to Partners
        </a>
    </div>

    <!-- Void Settlement Modal -->
    <div id="voidModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl w-full max-w-md shadow-2xl">
            <!-- Modal Header -->
            <div class="bg-red-500 px-6 py-4 rounded-t-xl">
                <h3 class="text-lg font-semibold text-white">Void Settlement</h3>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <p class="text-yellow-800 font-medium">
                        Warning: This action will reverse the settlement and refund <strong>Rs <span id="voidAmount">0</span></strong> to the ledger.
                    </p>
                </div>

                <form id="voidForm" method="POST">
                    @csrf
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Void Reason <span class="text-red-500">*</span>
                        </label>
                        <textarea name="void_reason" id="voidReason" rows="4"
                                  class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors"
                                  placeholder="Explain why this settlement needs to be voided (required for audit trail)"
                                  required></textarea>
                        <p class="mt-1 text-sm text-gray-500">This action is irreversible and will be logged.</p>
                    </div>

                    <div class="flex gap-3 justify-end">
                        <button type="button" onclick="closeVoidModal()" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg font-medium hover:bg-red-600 transition-colors">
                            Confirm Void
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let currentVoidSettlementId = null;

        function openVoidModal(settlementId, amount) {
            currentVoidSettlementId = settlementId;
            document.getElementById('voidAmount').textContent = parseFloat(amount).toFixed(2);
            document.getElementById('voidForm').action = `/admin/commissions/settlements/${settlementId}/void`;
            document.getElementById('voidReason').value = '';
            document.getElementById('voidModal').classList.remove('hidden');
        }

        function closeVoidModal() {
            document.getElementById('voidModal').classList.add('hidden');
            currentVoidSettlementId = null;
        }

        document.getElementById('voidModal').addEventListener('click', function(e) {
            if (e.target === this) closeVoidModal();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('voidModal').classList.contains('hidden')) {
                closeVoidModal();
            }
        });
    </script>
@endsection
