@extends('layouts.admin')

@section('title', 'Settlement History - BixCash Admin')
@section('page-title', 'Commission Settlement History')

@section('content')
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
        <div></div>
        <a href="{{ route('admin.commissions.settlements.proof-gallery') }}" class="btn btn-outline-primary">
            🖼️ View Proof Gallery
        </a>
    </div>

    <!-- Filters -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-body">
            <form method="GET">
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr auto; gap: 1rem; align-items: end; margin-bottom: 1rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Partner</label>
                        <select name="partner_id" style="width: 100%; padding: 0.5rem; border: 1px solid #dee2e6; border-radius: 4px;">
                            <option value="">All Partners</option>
                            @foreach($partners as $p)
                                <option value="{{ $p->id }}" {{ request('partner_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->name }} ({{ $p->partnerProfile->business_name ?? 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Payment Method</label>
                        <select name="payment_method" style="width: 100%; padding: 0.5rem; border: 1px solid #dee2e6; border-radius: 4px;">
                            <option value="">All Methods</option>
                            <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="wallet_deduction" {{ request('payment_method') == 'wallet_deduction' ? 'selected' : '' }}>Wallet Deduction</option>
                            <option value="adjustment" {{ request('payment_method') == 'adjustment' ? 'selected' : '' }}>Adjustment</option>
                            <option value="other" {{ request('payment_method') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">From Date</label>
                        <input type="date" name="from_date" value="{{ request('from_date') }}"
                               style="width: 100%; padding: 0.5rem; border: 1px solid #dee2e6; border-radius: 4px;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">To Date</label>
                        <input type="date" name="to_date" value="{{ request('to_date') }}"
                               style="width: 100%; padding: 0.5rem; border: 1px solid #dee2e6; border-radius: 4px;">
                    </div>
                    <div style="display: flex; gap: 0.5rem;">
                        <button type="submit" class="btn btn-primary">🔍 Filter</button>
                        <a href="{{ route('admin.commissions.settlements.history') }}" class="btn btn-outline-secondary">Clear</a>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem; padding: 0.75rem; background: #f8f9fa; border-radius: 8px;">
                    <label style="display: flex; align-items: center; gap: 0.5rem; margin: 0; cursor: pointer;">
                        <input type="checkbox" name="show_voided" value="1" {{ request('show_voided') ? 'checked' : '' }}
                               onchange="this.form.submit()"
                               style="width: 18px; height: 18px; cursor: pointer;">
                        <span style="font-weight: 500; color: #6c757d;">👁️ Show Voided Settlements</span>
                    </label>
                    @if(request('show_voided'))
                        <span class="badge bg-danger" style="font-size: 0.875rem;">Voided settlements visible</span>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Settlements Table -->
    <div class="card">
        <div class="card-header" style="background: white; border-bottom: 2px solid #f8f9fa; padding: 1rem;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h5 style="margin: 0;">💵 All Commission Settlements</h5>
                <a href="{{ route('admin.commissions.export.settlements', request()->query()) }}"
                   class="btn btn-success btn-sm"
                   style="padding: 0.5rem 1rem; display: flex; align-items: center; gap: 0.5rem;"
                   title="Export with current filters: {{ request()->hasAny(['partner_id', 'payment_method', 'from_date', 'to_date', 'show_voided']) ? 'Filtered' : 'All data' }}"
                   onclick="return confirm('Export settlements to Excel?\n\nCurrent filters will be applied to the export.');">
                    📊 Export to Excel
                    @if(request()->hasAny(['partner_id', 'payment_method', 'from_date', 'to_date']))
                        <span class="badge bg-warning text-dark" style="font-size: 0.7rem;">Filtered</span>
                    @endif
                </a>
            </div>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="table table-hover" style="margin: 0;">
                    <thead style="background: #f8f9fa;">
                        <tr>
                            <th style="padding: 0.75rem;">ID</th>
                            <th style="padding: 0.75rem;">Date & Time</th>
                            <th style="padding: 0.75rem;">Partner</th>
                            <th style="padding: 0.75rem;">Period</th>
                            <th style="padding: 0.75rem;">Amount</th>
                            <th style="padding: 0.75rem;">Method</th>
                            <th style="padding: 0.75rem;">Reference</th>
                            <th style="padding: 0.75rem;">Processed By</th>
                            <th style="padding: 0.75rem;">Proof</th>
                            <th style="padding: 0.75rem;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($settlements as $settlement)
                            <tr style="{{ $settlement->isVoided() ? 'background: #ffe6e6; text-decoration: line-through; opacity: 0.7;' : '' }}">
                                <td style="padding: 0.75rem;">
                                    <span class="badge {{ $settlement->isVoided() ? 'bg-danger' : 'bg-secondary' }}">#{{ $settlement->id }}</span>
                                    @if($settlement->isVoided())
                                        <br><span class="badge bg-danger" style="font-size: 0.7rem; margin-top: 0.25rem;">VOIDED</span>
                                    @endif
                                </td>
                                <td style="padding: 0.75rem;">
                                    {{ $settlement->processed_at->format('M d, Y') }}<br>
                                    <small style="color: #6c757d;">{{ $settlement->processed_at->format('h:i A') }}</small>
                                    @if($settlement->isVoided())
                                        <br><small style="color: #dc3545; font-weight: 500;">Voided: {{ $settlement->voided_at->format('M d, Y') }}</small>
                                    @endif
                                </td>
                                <td style="padding: 0.75rem;">
                                    <strong>{{ $settlement->partner->partnerProfile->business_name ?? $settlement->partner->name }}</strong><br>
                                    <small style="color: #6c757d;">{{ $settlement->partner->name }}</small>
                                </td>
                                <td style="padding: 0.75rem;">
                                    {{ $settlement->ledger->formatted_period }}
                                </td>
                                <td style="padding: 0.75rem;">
                                    <strong style="color: {{ $settlement->isVoided() ? '#dc3545' : '#00f2fe' }};">Rs {{ number_format($settlement->amount_settled, 2) }}</strong>
                                </td>
                                <td style="padding: 0.75rem;">
                                    <span class="badge bg-info">{{ $settlement->formatted_payment_method }}</span>
                                    @if($settlement->adjustment_type)
                                        <br>
                                        <span class="badge bg-{{ $settlement->adjustment_type_badge['color'] }}" style="margin-top: 0.25rem;">
                                            {{ $settlement->adjustment_type_badge['icon'] }} {{ $settlement->adjustment_type_badge['label'] }}
                                        </span>
                                    @endif
                                </td>
                                <td style="padding: 0.75rem;">
                                    {{ $settlement->settlement_reference ?? '-' }}
                                </td>
                                <td style="padding: 0.75rem;">
                                    {{ $settlement->processedByUser->name ?? 'N/A' }}
                                    @if($settlement->isVoided())
                                        <br><small style="color: #dc3545;">Voided by: {{ $settlement->voidedByUser->name ?? 'N/A' }}</small>
                                    @endif
                                </td>
                                <td style="padding: 0.75rem;">
                                    @if($settlement->isVoided())
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="alert('Void Reason: {{ addslashes($settlement->void_reason) }}')">
                                            📝 Reason
                                        </button>
                                    @elseif($settlement->proof_of_payment)
                                        <a href="{{ $settlement->proof_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            View
                                        </a>
                                    @else
                                        <span style="color: #6c757d;">-</span>
                                    @endif
                                </td>
                                <td style="padding: 0.75rem;">
                                    @if($settlement->canBeVoided())
                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="openVoidModal({{ $settlement->id }}, {{ $settlement->amount_settled }})"
                                                style="display: flex; align-items: center; gap: 0.25rem; font-size: 0.875rem;">
                                            🗑️ Void
                                        </button>
                                    @else
                                        <span style="color: #6c757d; font-size: 0.875rem;">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" style="padding: 3rem; text-align: center; color: #6c757d;">
                                    No settlements found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($settlements->hasPages())
            <div class="card-footer" style="background: white; padding: 1rem;">
                {{ $settlements->links() }}
            </div>
        @endif
    </div>

    <!-- Void Settlement Modal -->
    <div id="voidModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
        <div style="background: white; border-radius: 12px; width: 90%; max-width: 500px; box-shadow: 0 10px 40px rgba(0,0,0,0.3);">
            <!-- Modal Header -->
            <div style="background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%); color: white; padding: 1.5rem; border-radius: 12px 12px 0 0;">
                <h5 style="margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                    ⚠️ Void Settlement
                </h5>
            </div>

            <!-- Modal Body -->
            <div style="padding: 1.5rem;">
                <div style="background: #fff3cd; border: 1px solid #ffc107; border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem;">
                    <p style="margin: 0; color: #856404; font-weight: 500;">
                        ⚠️ Warning: This action will reverse the settlement and refund <strong>Rs <span id="voidAmount">0</span></strong> to the ledger.
                    </p>
                </div>

                <form id="voidForm" method="POST">
                    @csrf
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">
                            Void Reason <span style="color: red;">*</span>
                        </label>
                        <textarea name="void_reason" id="voidReason" rows="4"
                                  class="form-control"
                                  style="padding: 0.75rem; width: 100%; border: 1px solid #dee2e6; border-radius: 4px;"
                                  placeholder="Explain why this settlement needs to be voided (required for audit trail)"
                                  required></textarea>
                        <small style="color: #6c757d;">This action is irreversible and will be logged.</small>
                    </div>

                    <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                        <button type="button" onclick="closeVoidModal()" class="btn btn-outline-secondary">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-danger">
                            🗑️ Confirm Void
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
            document.getElementById('voidModal').style.display = 'flex';
        }

        function closeVoidModal() {
            document.getElementById('voidModal').style.display = 'none';
            currentVoidSettlementId = null;
        }

        // Close modal on background click
        document.getElementById('voidModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeVoidModal();
            }
        });

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && document.getElementById('voidModal').style.display === 'flex') {
                closeVoidModal();
            }
        });
    </script>
@endsection
