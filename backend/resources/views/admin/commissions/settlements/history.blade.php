@extends('layouts.admin')

@section('title', 'Settlement History - BixCash Admin')
@section('page-title', 'Commission Settlement History')

@section('content')
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div></div>
        <a href="{{ route('admin.commissions.settlements.proof-gallery') }}" class="inline-flex items-center px-4 py-2 border border-[#021c47] text-[#021c47] rounded-lg font-medium hover:bg-[#021c47] hover:text-white transition-colors">
            View Proof Gallery
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6">
        <div class="p-6">
            <form method="GET">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end mb-4">
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                        <select name="payment_method" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors">
                            <option value="">All Methods</option>
                            <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="wallet_deduction" {{ request('payment_method') == 'wallet_deduction' ? 'selected' : '' }}>Wallet Deduction</option>
                            <option value="adjustment" {{ request('payment_method') == 'adjustment' ? 'selected' : '' }}>Adjustment</option>
                            <option value="other" {{ request('payment_method') == 'other' ? 'selected' : '' }}>Other</option>
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
                        <a href="{{ route('admin.commissions.settlements.history') }}" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                            Clear
                        </a>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="show_voided" value="1" {{ request('show_voided') ? 'checked' : '' }}
                               onchange="this.form.submit()"
                               class="w-4 h-4 rounded border-gray-300 text-[#93db4d] focus:ring-[#93db4d]">
                        <span class="text-sm font-medium text-gray-600">Show Voided Settlements</span>
                    </label>
                    @if(request('show_voided'))
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">Voided settlements visible</span>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Settlements Table -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-[#021c47]">All Commission Settlements</h3>
            <a href="{{ route('admin.commissions.export.settlements', request()->query()) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-[#93db4d] text-[#021c47] rounded-lg font-medium hover:bg-[#7bc62e] transition-colors"
               onclick="return confirm('Export settlements to Excel?\n\nCurrent filters will be applied to the export.');">
                Export to Excel
                @if(request()->hasAny(['partner_id', 'payment_method', 'from_date', 'to_date']))
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-yellow-400 text-yellow-900">Filtered</span>
                @endif
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-[#021c47] text-white">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Date & Time</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Partner</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Period</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Method</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Reference</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Processed By</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Proof</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($settlements as $settlement)
                        <tr class="{{ $settlement->isVoided() ? 'bg-red-50 line-through opacity-70' : 'hover:bg-[#93db4d]/5' }} transition-colors">
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ $settlement->isVoided() ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700' }}">#{{ $settlement->id }}</span>
                                @if($settlement->isVoided())
                                    <span class="block mt-1 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">VOIDED</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-gray-900">{{ $settlement->processed_at->format('M d, Y') }}</span><br>
                                <span class="text-sm text-gray-500">{{ $settlement->processed_at->format('h:i A') }}</span>
                                @if($settlement->isVoided())
                                    <span class="block text-sm text-red-600 font-medium">Voided: {{ $settlement->voided_at->format('M d, Y') }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="font-semibold text-gray-900">{{ $settlement->partner->partnerProfile->business_name ?? $settlement->partner->name }}</span><br>
                                <span class="text-sm text-gray-500">{{ $settlement->partner->name }}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $settlement->ledger->formatted_period }}</td>
                            <td class="px-4 py-3">
                                <span class="font-semibold {{ $settlement->isVoided() ? 'text-red-600' : 'text-[#93db4d]' }}">Rs {{ number_format($settlement->amount_settled, 2) }}</span>
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
                            <td class="px-4 py-3">
                                <span class="text-gray-600">{{ $settlement->processedByUser->name ?? 'N/A' }}</span>
                                @if($settlement->isVoided())
                                    <span class="block text-sm text-red-600">Voided by: {{ $settlement->voidedByUser->name ?? 'N/A' }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($settlement->isVoided())
                                    <button type="button" class="text-sm text-red-600 hover:text-red-800 font-medium" onclick="alert('Void Reason: {{ addslashes($settlement->void_reason) }}')">
                                        Reason
                                    </button>
                                @elseif($settlement->proof_of_payment)
                                    <a href="{{ $settlement->proof_url }}" target="_blank" class="text-sm text-[#021c47] hover:text-[#93db4d] font-medium">View</a>
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
                            <td colspan="10" class="px-4 py-12 text-center text-gray-500">No settlements found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($settlements->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $settlements->links() }}
            </div>
        @endif
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
