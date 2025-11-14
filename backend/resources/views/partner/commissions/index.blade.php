@extends('layouts.partner')

@section('title', 'My Commissions')
@section('page-title', 'Commission History')

@section('content')
    <!-- Summary Stats -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
        <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <div class="card-body">
                <h6 style="margin-bottom: 0.5rem; opacity: 0.9;">Commission Rate</h6>
                <h2 style="margin: 0;">{{ number_format($commissionRate, 2) }}%</h2>
            </div>
        </div>
        <div class="card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
            <div class="card-body">
                <h6 style="margin-bottom: 0.5rem; opacity: 0.9;">Total Owed</h6>
                <h2 style="margin: 0;">Rs {{ number_format($totalCommissionOwed, 2) }}</h2>
            </div>
        </div>
        <div class="card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
            <div class="card-body">
                <h6 style="margin-bottom: 0.5rem; opacity: 0.9;">Total Paid</h6>
                <h2 style="margin: 0;">Rs {{ number_format($totalPaid, 2) }}</h2>
            </div>
        </div>
        <div class="card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: white;">
            <div class="card-body">
                <h6 style="margin-bottom: 0.5rem; opacity: 0.9;">Outstanding</h6>
                <h2 style="margin: 0;">Rs {{ number_format($totalOutstanding, 2) }}</h2>
            </div>
        </div>
    </div>

    @if($totalOutstanding > 0)
        <div class="alert alert-warning" style="margin-bottom: 1.5rem;">
            <strong>📌 Note:</strong> You have Rs {{ number_format($totalOutstanding, 2) }} in outstanding commission. 
            This amount represents commission you owe to BixCash based on your transactions. 
            You can continue withdrawing from your wallet - this debt is tracked separately.
        </div>
    @endif

    <!-- Commission Ledgers -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-header" style="background: white; border-bottom: 2px solid #f8f9fa; padding: 1rem;">
            <h5 style="margin: 0;">📊 Commission by Period</h5>
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
                        @forelse($ledgers as $ledger)
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
                                        <span style="color: #28a745;">Settled</span>
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
                                        <a href="{{ route('partner.commissions.show', $ledger->id) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            Details
                                        </a>
                                        <a href="{{ route('partner.commissions.invoice', $ledger->id) }}" 
                                           class="btn btn-sm btn-outline-secondary" target="_blank">
                                            Invoice
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" style="padding: 3rem; text-align: center; color: #6c757d;">
                                    <p style="margin-bottom: 1rem;">No commission records yet</p>
                                    <small>Commission is calculated monthly based on your confirmed transactions</small>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Settlements -->
    @if($settlements->count() > 0)
        <div class="card">
            <div class="card-header" style="background: white; border-bottom: 2px solid #f8f9fa; padding: 1rem;">
                <h5 style="margin: 0;">💵 Recent Settlements</h5>
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
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($settlements as $settlement)
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
                                    </td>
                                    <td style="padding: 0.75rem;">
                                        {{ $settlement->settlement_reference ?? '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection
