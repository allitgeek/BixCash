@extends('layouts.admin')

@section('title', 'Batch Details - BixCash Admin')
@section('page-title', 'Commission Batch: ' . $batch->formatted_period)

@section('content')
    <!-- Batch Summary -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 1.5rem;">
            <div style="display: flex; justify-content: between; align-items: center;">
                <div>
                    <h3 style="margin: 0 0 0.5rem 0;">📦 Batch #{{ $batch->id }} - {{ $batch->formatted_period }}</h3>
                    <p style="margin: 0; opacity: 0.9;">
                        {{ \Carbon\Carbon::parse($batch->period_start)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($batch->period_end)->format('M d, Y') }}
                    </p>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 1.5rem;">
                <div>
                    <small style="color: #6c757d; display: block; margin-bottom: 0.25rem;">Status</small>
                    @if($batch->status === 'completed')
                        <span class="badge bg-success" style="font-size: 1rem; padding: 0.5rem 1rem;">✅ Completed</span>
                    @elseif($batch->status === 'processing')
                        <span class="badge bg-warning" style="font-size: 1rem; padding: 0.5rem 1rem;">🔄 Processing</span>
                    @else
                        <span class="badge bg-secondary" style="font-size: 1rem; padding: 0.5rem 1rem;">{{ ucfirst($batch->status) }}</span>
                    @endif
                </div>
                <div>
                    <small style="color: #6c757d; display: block; margin-bottom: 0.25rem;">Partners</small>
                    <strong style="font-size: 1.5rem;">{{ number_format($batch->total_partners) }}</strong>
                </div>
                <div>
                    <small style="color: #6c757d; display: block; margin-bottom: 0.25rem;">Transactions</small>
                    <strong style="font-size: 1.5rem;">{{ number_format($batch->total_transactions) }}</strong>
                </div>
                <div>
                    <small style="color: #6c757d; display: block; margin-bottom: 0.25rem;">Total Amount</small>
                    <strong style="font-size: 1.25rem;">Rs {{ number_format($batch->total_transaction_amount, 2) }}</strong>
                </div>
                <div>
                    <small style="color: #6c757d; display: block; margin-bottom: 0.25rem;">Commission</small>
                    <strong style="font-size: 1.25rem; color: #f5576c;">Rs {{ number_format($batch->total_commission_calculated, 2) }}</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Partner Ledgers -->
    <div class="card">
        <div class="card-header" style="background: white; border-bottom: 2px solid #f8f9fa; padding: 1rem;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h5 style="margin: 0;">👥 Partner Commission Ledgers</h5>
                <span class="badge bg-primary" style="font-size: 1rem; padding: 0.5rem 1rem;">
                    {{ $ledgers->count() }} Partners
                </span>
            </div>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="table table-hover" style="margin: 0;">
                    <thead style="background: #f8f9fa;">
                        <tr>
                            <th style="padding: 0.75rem;">Partner</th>
                            <th style="padding: 0.75rem;">Business Name</th>
                            <th style="padding: 0.75rem;">Rate</th>
                            <th style="padding: 0.75rem;">Transactions</th>
                            <th style="padding: 0.75rem;">Invoice Total</th>
                            <th style="padding: 0.75rem;">Commission Owed</th>
                            <th style="padding: 0.75rem;">Paid</th>
                            <th style="padding: 0.75rem;">Outstanding</th>
                            <th style="padding: 0.75rem;">Status</th>
                            <th style="padding: 0.75rem;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ledgers as $ledger)
                            @php
                                $partner = $ledger->partner;
                                $profile = $partner->partnerProfile;
                            @endphp
                            <tr>
                                <td style="padding: 0.75rem;">
                                    <strong>{{ $partner->name }}</strong><br>
                                    <small style="color: #6c757d;">{{ $partner->phone }}</small>
                                </td>
                                <td style="padding: 0.75rem;">
                                    {{ $profile->business_name ?? 'N/A' }}
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
                                    <span style="color: #00f2fe;">Rs {{ number_format($ledger->amount_paid, 2) }}</span>
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
                                        <a href="{{ route('admin.commissions.partners.show', $partner->id) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            View
                                        </a>
                                        @if($ledger->amount_outstanding > 0)
                                            <a href="{{ route('admin.commissions.settlements.create', $ledger->id) }}" 
                                               class="btn btn-sm btn-success">
                                                Settle
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot style="background: #f8f9fa; font-weight: bold;">
                        <tr>
                            <td colspan="4" style="padding: 0.75rem; text-align: right;">TOTALS:</td>
                            <td style="padding: 0.75rem;">Rs {{ number_format($ledgers->sum('total_invoice_amount'), 2) }}</td>
                            <td style="padding: 0.75rem;">Rs {{ number_format($ledgers->sum('commission_owed'), 2) }}</td>
                            <td style="padding: 0.75rem;">Rs {{ number_format($ledgers->sum('amount_paid'), 2) }}</td>
                            <td style="padding: 0.75rem;" colspan="3">Rs {{ number_format($ledgers->sum('amount_outstanding'), 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div style="margin-top: 1.5rem;">
        <a href="{{ route('admin.commissions.batches.index') }}" class="btn btn-secondary">← Back to Batches</a>
    </div>
@endsection
