@extends('layouts.partner')

@section('title', 'Commission Details - ' . $ledger->formatted_period)
@section('page-title', 'Commission Details: ' . $ledger->formatted_period)

@section('content')
    <!-- Ledger Summary -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 1.5rem;">
            <h4 style="margin: 0;">📊 {{ $ledger->formatted_period }} Commission</h4>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 1.5rem;">
                <div>
                    <small style="color: #6c757d; display: block; margin-bottom: 0.25rem;">Rate Used</small>
                    <span class="badge bg-info" style="font-size: 1.25rem; padding: 0.5rem 1rem;">
                        {{ number_format($ledger->commission_rate_used, 2) }}%
                    </span>
                </div>
                <div>
                    <small style="color: #6c757d; display: block; margin-bottom: 0.25rem;">Transactions</small>
                    <strong style="font-size: 1.5rem;">{{ number_format($ledger->total_transactions) }}</strong>
                </div>
                <div>
                    <small style="color: #6c757d; display: block; margin-bottom: 0.25rem;">Invoice Total</small>
                    <strong style="font-size: 1.25rem;">Rs {{ number_format($ledger->total_invoice_amount, 2) }}</strong>
                </div>
                <div>
                    <small style="color: #6c757d; display: block; margin-bottom: 0.25rem;">Commission</small>
                    <strong style="font-size: 1.25rem; color: #f5576c;">Rs {{ number_format($ledger->commission_owed, 2) }}</strong>
                </div>
                <div>
                    <small style="color: #6c757d; display: block; margin-bottom: 0.25rem;">Status</small>
                    @if($ledger->status === 'settled')
                        <span class="badge bg-success" style="font-size: 1rem; padding: 0.5rem 1rem;">Settled</span>
                    @elseif($ledger->status === 'partial')
                        <span class="badge bg-warning" style="font-size: 1rem; padding: 0.5rem 1rem;">Partial</span>
                    @else
                        <span class="badge bg-danger" style="font-size: 1rem; padding: 0.5rem 1rem;">Pending</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction Breakdown -->
    <div class="card">
        <div class="card-header" style="background: white; border-bottom: 2px solid #f8f9fa; padding: 1rem;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h5 style="margin: 0;">📜 Transaction Breakdown</h5>
                <a href="{{ route('partner.commissions.invoice', $ledger->id) }}" 
                   class="btn btn-sm btn-primary" target="_blank">
                    📄 Download Invoice
                </a>
            </div>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="table table-striped" style="margin: 0;">
                    <thead style="background: #f8f9fa;">
                        <tr>
                            <th style="padding: 0.75rem;">Date</th>
                            <th style="padding: 0.75rem;">Transaction Code</th>
                            <th style="padding: 0.75rem;">Customer</th>
                            <th style="padding: 0.75rem;">Invoice Amount</th>
                            <th style="padding: 0.75rem;">Rate</th>
                            <th style="padding: 0.75rem;">Commission</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                            @php
                                $partnerTxn = $transaction->partnerTransaction;
                            @endphp
                            <tr>
                                <td style="padding: 0.75rem;">
                                    {{ \Carbon\Carbon::parse($partnerTxn->transaction_date)->format('M d, Y') }}<br>
                                    <small style="color: #6c757d;">{{ \Carbon\Carbon::parse($partnerTxn->transaction_date)->format('h:i A') }}</small>
                                </td>
                                <td style="padding: 0.75rem;">
                                    <span class="badge bg-secondary">{{ $transaction->transaction_code }}</span>
                                </td>
                                <td style="padding: 0.75rem;">
                                    {{ $partnerTxn->customer->name ?? 'N/A' }}
                                </td>
                                <td style="padding: 0.75rem;">
                                    Rs {{ number_format($transaction->invoice_amount, 2) }}
                                </td>
                                <td style="padding: 0.75rem;">
                                    <span class="badge bg-info">{{ number_format($transaction->commission_rate, 2) }}%</span>
                                </td>
                                <td style="padding: 0.75rem;">
                                    <strong style="color: #f5576c;">Rs {{ number_format($transaction->commission_amount, 2) }}</strong>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot style="background: #f8f9fa; font-weight: bold;">
                        <tr>
                            <td colspan="3" style="padding: 0.75rem; text-align: right;">TOTAL:</td>
                            <td style="padding: 0.75rem;">Rs {{ number_format($transactions->sum('invoice_amount'), 2) }}</td>
                            <td style="padding: 0.75rem;">-</td>
                            <td style="padding: 0.75rem;">Rs {{ number_format($transactions->sum('commission_amount'), 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        @if($transactions->hasPages())
            <div class="card-footer" style="background: white; padding: 1rem;">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>

    <!-- Settlement Information -->
    @if($ledger->settlements->count() > 0)
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header" style="background: white; border-bottom: 2px solid #f8f9fa; padding: 1rem;">
                <h5 style="margin: 0;">💵 Settlement History</h5>
            </div>
            <div class="card-body" style="padding: 0;">
                <div class="table-responsive">
                    <table class="table" style="margin: 0;">
                        <thead style="background: #f8f9fa;">
                            <tr>
                                <th style="padding: 0.75rem;">Date</th>
                                <th style="padding: 0.75rem;">Amount</th>
                                <th style="padding: 0.75rem;">Method</th>
                                <th style="padding: 0.75rem;">Reference</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ledger->settlements as $settlement)
                                <tr>
                                    <td style="padding: 0.75rem;">
                                        {{ $settlement->processed_at->format('M d, Y') }}<br>
                                        <small style="color: #6c757d;">{{ $settlement->processed_at->format('h:i A') }}</small>
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

    <div style="margin-top: 1.5rem;">
        <a href="{{ route('partner.commissions') }}" class="btn btn-secondary">← Back to Commissions</a>
    </div>
@endsection
