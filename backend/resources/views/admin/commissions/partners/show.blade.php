@extends('layouts.admin')

@section('title', $partner->name . ' - Commissions')
@section('page-title', 'Partner Commission Details')

@section('content')
    <!-- Partner Header -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-body">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <h3 style="margin: 0 0 0.5rem 0;">{{ $partner->name }}</h3>
                    <p style="margin: 0; color: #6c757d;">
                        {{ $partner->partnerProfile->business_name ?? 'N/A' }} |
                        {{ $partner->phone }} |
                        Commission Rate: <strong>{{ number_format($partner->partnerProfile->commission_rate ?? 0, 2) }}%</strong>
                    </p>
                </div>
                <div style="display: flex; gap: 0.75rem;">
                    <a href="{{ route('admin.commissions.export.partner', $partner->id) }}"
                       class="btn btn-success"
                       style="display: flex; align-items: center; gap: 0.5rem;"
                       title="Export complete commission history for {{ $partner->name }}"
                       onclick="return confirm('Export complete commission report for {{ $partner->name }} to Excel?\n\nThis includes all ledgers, transactions, and settlement history.');">
                        📊 Export Report
                    </a>
                    <a href="{{ route('admin.partners.show', $partner->id) }}" class="btn btn-outline-secondary">
                        View Partner Profile →
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Stats -->
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
        <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <div class="card-body">
                <h5 style="margin-bottom: 0.5rem; opacity: 0.9;">Total Commission Owed</h5>
                <h2 style="margin: 0;">Rs {{ number_format($totalCommissionOwed, 2) }}</h2>
            </div>
        </div>
        <div class="card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
            <div class="card-body">
                <h5 style="margin-bottom: 0.5rem; opacity: 0.9;">Total Paid</h5>
                <h2 style="margin: 0;">Rs {{ number_format($totalPaid, 2) }}</h2>
            </div>
        </div>
        <div class="card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
            <div class="card-body">
                <h5 style="margin-bottom: 0.5rem; opacity: 0.9;">Outstanding</h5>
                <h2 style="margin: 0;">Rs {{ number_format($totalOutstanding, 2) }}</h2>
            </div>
        </div>
    </div>

    <!-- Ledgers Table -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-header" style="background: white; border-bottom: 2px solid #f8f9fa; padding: 1rem;">
            <h5 style="margin: 0;">📊 Commission Ledgers by Period</h5>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="table table-hover" style="margin: 0;">
                    <thead style="background: #f8f9fa;">
                        <tr>
                            <th style="padding: 0.75rem;">Period</th>
                            <th style="padding: 0.75rem;">Rate</th>
                            <th style="padding: 0.75rem;">Transactions</th>
                            <th style="padding: 0.75rem;">Invoice Total</th>
                            <th style="padding: 0.75rem;">Commission</th>
                            <th style="padding: 0.75rem;">Paid</th>
                            <th style="padding: 0.75rem;">Outstanding</th>
                            <th style="padding: 0.75rem;">Status</th>
                            <th style="padding: 0.75rem;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ledgers as $ledger)
                            <tr>
                                <td style="padding: 0.75rem;">
                                    <strong>{{ $ledger->formatted_period }}</strong>
                                </td>
                                <td style="padding: 0.75rem;">
                                    <span class="badge bg-info">{{ number_format($ledger->commission_rate_used, 2) }}%</span>
                                </td>
                                <td style="padding: 0.75rem;">{{ number_format($ledger->total_transactions) }}</td>
                                <td style="padding: 0.75rem;">Rs {{ number_format($ledger->total_invoice_amount, 2) }}</td>
                                <td style="padding: 0.75rem;">
                                    <strong>Rs {{ number_format($ledger->commission_owed, 2) }}</strong>
                                </td>
                                <td style="padding: 0.75rem;">
                                    Rs {{ number_format($ledger->amount_paid, 2) }}
                                </td>
                                <td style="padding: 0.75rem;">
                                    @if($ledger->amount_outstanding > 0)
                                        <strong style="color: #f5576c;">Rs {{ number_format($ledger->amount_outstanding, 2) }}</strong>
                                    @else
                                        <span style="color: #6c757d;">-</span>
                                    @endif
                                </td>
                                <td style="padding: 0.75rem;">
                                    @if($ledger->status === 'settled')
                                        <span class="badge bg-success">Settled</span>
                                    @elseif($ledger->status === 'partial')
                                        <span class="badge bg-warning">Partial</span>
                                    @else
                                        <span class="badge bg-danger">Pending</span>
                                    @endif
                                </td>
                                <td style="padding: 0.75rem;">
                                    <div style="display: flex; gap: 0.5rem;">
                                        @if($ledger->amount_outstanding > 0)
                                            <a href="{{ route('admin.commissions.settlements.create', $ledger->id) }}" 
                                               class="btn btn-sm btn-success">
                                                Settle
                                            </a>
                                        @endif
                                        <a href="{{ route('admin.commissions.invoice.download', $ledger->id) }}" 
                                           class="btn btn-sm btn-outline-secondary" target="_blank">
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
    </div>

    <!-- Settlement History -->
    <div class="card">
        <div class="card-header" style="background: white; border-bottom: 2px solid #f8f9fa; padding: 1rem;">
            <h5 style="margin: 0;">💵 Settlement History</h5>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="table" style="margin: 0;">
                    <thead style="background: #f8f9fa;">
                        <tr>
                            <th style="padding: 0.75rem;">Date</th>
                            <th style="padding: 0.75rem;">Period</th>
                            <th style="padding: 0.75rem;">Amount</th>
                            <th style="padding: 0.75rem;">Method</th>
                            <th style="padding: 0.75rem;">Reference</th>
                            <th style="padding: 0.75rem;">Processed By</th>
                            <th style="padding: 0.75rem;">Notes</th>
                            <th style="padding: 0.75rem;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($settlements as $settlement)
                            <tr>
                                <td style="padding: 0.75rem;">
                                    {{ $settlement->processed_at->format('M d, Y') }}<br>
                                    <small style="color: #6c757d;">{{ $settlement->processed_at->format('h:i A') }}</small>
                                </td>
                                <td style="padding: 0.75rem;">
                                    {{ $settlement->ledger->formatted_period }}
                                </td>
                                <td style="padding: 0.75rem;">
                                    <strong style="color: #00f2fe;">Rs {{ number_format($settlement->amount_settled, 2) }}</strong>
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
                                </td>
                                <td style="padding: 0.75rem;">
                                    @if($settlement->admin_notes)
                                        <small>{{ Str::limit($settlement->admin_notes, 50) }}</small>
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
                                <td colspan="8" style="padding: 2rem; text-align: center; color: #6c757d;">
                                    No settlements recorded yet
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div style="margin-top: 1.5rem;">
        <a href="{{ route('admin.commissions.partners.index') }}" class="btn btn-secondary">← Back to Partners</a>
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
