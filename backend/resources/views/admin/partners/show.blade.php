@extends('layouts.admin')

@section('title', 'Partner Details - BixCash Admin')
@section('page-title', 'Partner Details')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $partner->partnerProfile->business_name ?? $partner->name }}</h3>
            <div style="display: flex; gap: 0.5rem;">
                <a href="{{ route('admin.partners.index') }}" class="btn btn-secondary">Back to Partners</a>
            </div>
        </div>
        <div class="card-body">

            <!-- Status and Quick Actions -->
            <div style="background: #f8f9fa; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                    <div>
                        <strong>Status:</strong>
                        @if($partner->partnerProfile)
                            @if($partner->partnerProfile->status === 'approved')
                                <span class="badge-success">Approved</span>
                            @elseif($partner->partnerProfile->status === 'pending')
                                <span class="badge-warning">Pending Review</span>
                            @else
                                <span style="background: #e74c3c; color: white; padding: 0.25rem 0.5rem; border-radius: 3px;">Rejected</span>
                            @endif
                        @endif
                        &nbsp;
                        <strong>Account:</strong>
                        @if($partner->is_active)
                            <span class="badge-success">Active</span>
                        @else
                            <span style="background: #e74c3c; color: white; padding: 0.25rem 0.5rem; border-radius: 3px;">Inactive</span>
                        @endif
                    </div>

                    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                        @if($partner->partnerProfile && $partner->partnerProfile->status === 'pending')
                            <form method="POST" action="{{ route('admin.partners.approve', $partner) }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-success" onclick="return confirm('Approve this partner application?')">
                                    Approve Partner
                                </button>
                            </form>
                            <button type="button" class="btn btn-danger" onclick="document.getElementById('reject-modal').style.display='block'">
                                Reject Application
                            </button>
                        @endif

                        <form method="POST" action="{{ route('admin.partners.update-status', $partner) }}" style="display: inline;">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="is_active" value="{{ $partner->is_active ? 0 : 1 }}">
                            <button type="submit" class="btn {{ $partner->is_active ? 'btn-warning' : 'btn-success' }}">
                                {{ $partner->is_active ? 'Deactivate Account' : 'Activate Account' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
                <div style="padding: 1rem; background: #e3f2fd; border-radius: 8px;">
                    <div style="font-size: 0.9rem; color: #1976d2;">Total Transactions</div>
                    <div style="font-size: 1.8rem; font-weight: 600; color: #0d47a1;">{{ $stats['total_transactions'] }}</div>
                </div>
                <div style="padding: 1rem; background: #e8f5e9; border-radius: 8px;">
                    <div style="font-size: 0.9rem; color: #388e3c;">Total Revenue</div>
                    <div style="font-size: 1.8rem; font-weight: 600; color: #1b5e20;">Rs. {{ number_format($stats['total_revenue'], 2) }}</div>
                </div>
                <div style="padding: 1rem; background: #fff3e0; border-radius: 8px;">
                    <div style="font-size: 0.9rem; color: #f57c00;">Partner Profit</div>
                    <div style="font-size: 1.8rem; font-weight: 600; color: #e65100;">Rs. {{ number_format($stats['total_profit'], 2) }}</div>
                </div>
                <div style="padding: 1rem; background: #fce4ec; border-radius: 8px;">
                    <div style="font-size: 0.9rem; color: #c2185b;">Pending</div>
                    <div style="font-size: 1.8rem; font-weight: 600; color: #880e4f;">{{ $stats['pending_confirmations'] }}</div>
                </div>
            </div>

            <!-- Partner Information -->
            <h4 style="margin-top: 2rem; margin-bottom: 1rem;">Business Information</h4>
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 2rem;">
                <tr style="border-bottom: 1px solid #dee2e6;">
                    <td style="padding: 0.75rem; font-weight: 600; width: 30%;">Business Name:</td>
                    <td style="padding: 0.75rem;">{{ $partner->partnerProfile->business_name ?? '-' }}</td>
                </tr>
                <tr style="border-bottom: 1px solid #dee2e6;">
                    <td style="padding: 0.75rem; font-weight: 600;">Business Type:</td>
                    <td style="padding: 0.75rem;">{{ $partner->partnerProfile->business_type ?? '-' }}</td>
                </tr>
                <tr style="border-bottom: 1px solid #dee2e6;">
                    <td style="padding: 0.75rem; font-weight: 600;">Business Address:</td>
                    <td style="padding: 0.75rem;">{{ $partner->partnerProfile->business_address ?? '-' }}</td>
                </tr>
                <tr style="border-bottom: 1px solid #dee2e6;">
                    <td style="padding: 0.75rem; font-weight: 600;">Contact Name:</td>
                    <td style="padding: 0.75rem;">{{ $partner->name }}</td>
                </tr>
                <tr style="border-bottom: 1px solid #dee2e6;">
                    <td style="padding: 0.75rem; font-weight: 600;">Phone:</td>
                    <td style="padding: 0.75rem;">{{ $partner->phone }}</td>
                </tr>
                <tr style="border-bottom: 1px solid #dee2e6;">
                    <td style="padding: 0.75rem; font-weight: 600;">Email:</td>
                    <td style="padding: 0.75rem;">{{ $partner->email ?? '-' }}</td>
                </tr>
                <tr style="border-bottom: 1px solid #dee2e6;">
                    <td style="padding: 0.75rem; font-weight: 600;">Registered:</td>
                    <td style="padding: 0.75rem;">{{ $partner->created_at->format('F j, Y g:i A') }}</td>
                </tr>
                @if($partner->partnerProfile && $partner->partnerProfile->status === 'approved')
                <tr style="border-bottom: 1px solid #dee2e6;">
                    <td style="padding: 0.75rem; font-weight: 600;">Approved At:</td>
                    <td style="padding: 0.75rem;">{{ $partner->partnerProfile->approved_at ? $partner->partnerProfile->approved_at->format('F j, Y g:i A') : '-' }}</td>
                </tr>
                @endif
            </table>

            <!-- Recent Transactions -->
            <h4 style="margin-bottom: 1rem;">Recent Transactions</h4>
            @if($recentTransactions->count() > 0)
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                                <th style="padding: 0.75rem; text-align: left;">Transaction Code</th>
                                <th style="padding: 0.75rem; text-align: left;">Customer</th>
                                <th style="padding: 0.75rem; text-align: right;">Amount</th>
                                <th style="padding: 0.75rem; text-align: right;">Profit</th>
                                <th style="padding: 0.75rem; text-align: center;">Status</th>
                                <th style="padding: 0.75rem; text-align: left;">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentTransactions as $transaction)
                                <tr style="border-bottom: 1px solid #dee2e6;">
                                    <td style="padding: 0.75rem;">{{ $transaction->transaction_code }}</td>
                                    <td style="padding: 0.75rem;">{{ $transaction->customer->name ?? '-' }}</td>
                                    <td style="padding: 0.75rem; text-align: right;">Rs. {{ number_format($transaction->invoice_amount, 2) }}</td>
                                    <td style="padding: 0.75rem; text-align: right;">Rs. {{ number_format($transaction->partner_profit_share, 2) }}</td>
                                    <td style="padding: 0.75rem; text-align: center;">
                                        @if($transaction->status === 'confirmed')
                                            <span class="badge-success">Confirmed</span>
                                        @elseif($transaction->status === 'pending_confirmation')
                                            <span class="badge-warning">Pending</span>
                                        @else
                                            <span class="badge-secondary">{{ ucfirst($transaction->status) }}</span>
                                        @endif
                                    </td>
                                    <td style="padding: 0.75rem;">{{ $transaction->transaction_date->format('M j, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div style="margin-top: 1rem; text-align: center;">
                    <a href="{{ route('admin.partners.transactions', $partner) }}" class="btn btn-primary">
                        View All Transactions
                    </a>
                </div>
            @else
                <p style="color: #666; padding: 1rem; background: #f8f9fa; border-radius: 5px;">No transactions yet.</p>
            @endif

        </div>
    </div>

    <!-- Reject Modal -->
    <div id="reject-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
        <div style="background: white; padding: 2rem; border-radius: 10px; max-width: 500px; width: 90%;">
            <h3 style="margin-bottom: 1rem;">Reject Partner Application</h3>
            <form method="POST" action="{{ route('admin.partners.reject', $partner) }}">
                @csrf
                <div class="form-group">
                    <label for="rejection_notes">Rejection Reason *</label>
                    <textarea id="rejection_notes" name="rejection_notes" class="form-control" rows="4" required
                              placeholder="Please provide a reason for rejection..."></textarea>
                </div>
                <div style="display: flex; gap: 0.5rem; justify-content: flex-end; margin-top: 1rem;">
                    <button type="button" class="btn btn-secondary" onclick="document.getElementById('reject-modal').style.display='none'">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-danger">
                        Confirm Rejection
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Show reject modal with flex display
    window.onclick = function(event) {
        const modal = document.getElementById('reject-modal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
</script>
@endpush
