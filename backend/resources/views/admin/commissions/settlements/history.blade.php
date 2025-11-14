@extends('layouts.admin')

@section('title', 'Settlement History - BixCash Admin')
@section('page-title', 'Commission Settlement History')

@section('content')
    <!-- Filters -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-body">
            <form method="GET" style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr auto; gap: 1rem; align-items: end;">
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
            </form>
        </div>
    </div>

    <!-- Settlements Table -->
    <div class="card">
        <div class="card-header" style="background: white; border-bottom: 2px solid #f8f9fa; padding: 1rem;">
            <h5 style="margin: 0;">💵 All Commission Settlements</h5>
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
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($settlements as $settlement)
                            <tr>
                                <td style="padding: 0.75rem;">
                                    <span class="badge bg-secondary">#{{ $settlement->id }}</span>
                                </td>
                                <td style="padding: 0.75rem;">
                                    {{ $settlement->processed_at->format('M d, Y') }}<br>
                                    <small style="color: #6c757d;">{{ $settlement->processed_at->format('h:i A') }}</small>
                                </td>
                                <td style="padding: 0.75rem;">
                                    <strong>{{ $settlement->partner->name }}</strong><br>
                                    <small style="color: #6c757d;">{{ $settlement->partner->partnerProfile->business_name ?? 'N/A' }}</small>
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
                                <td style="padding: 0.75rem;">
                                    {{ $settlement->processedByUser->name ?? 'N/A' }}
                                </td>
                                <td style="padding: 0.75rem;">
                                    @if($settlement->proof_of_payment)
                                        <a href="{{ $settlement->proof_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            View
                                        </a>
                                    @else
                                        <span style="color: #6c757d;">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" style="padding: 3rem; text-align: center; color: #6c757d;">
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
@endsection
