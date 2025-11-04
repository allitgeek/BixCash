@extends('layouts.admin')

@section('title', 'Customer Transactions - BixCash Admin')
@section('page-title', 'Customer Transactions')

@section('content')
    <div class="card">
        <div class="card-header">
            <div>
                <h3 class="card-title">Transactions: {{ $customer->name }}</h3>
                <p style="color: #666; margin-top: 0.25rem;">{{ $customer->phone }}</p>
            </div>
            <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-secondary">Back to Customer Details</a>
        </div>
        <div class="card-body">

            @if($transactions->count() > 0)
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                                <th style="padding: 0.75rem; text-align: left;">Transaction Code</th>
                                <th style="padding: 0.75rem; text-align: left;">Partner</th>
                                <th style="padding: 0.75rem; text-align: right;">Amount</th>
                                <th style="padding: 0.75rem; text-align: right;">Customer Profit</th>
                                <th style="padding: 0.75rem; text-align: center;">Status</th>
                                <th style="padding: 0.75rem; text-align: left;">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $transaction)
                                <tr style="border-bottom: 1px solid #dee2e6;">
                                    <td style="padding: 0.75rem;">
                                        <strong>{{ $transaction->transaction_code }}</strong>
                                    </td>
                                    <td style="padding: 0.75rem;">
                                        {{ $transaction->partner->partnerProfile->business_name ?? $transaction->partner->name ?? '-' }}
                                        <br>
                                        <small style="color: #666;">{{ $transaction->partner->phone ?? '' }}</small>
                                    </td>
                                    <td style="padding: 0.75rem; text-align: right;">
                                        Rs. {{ number_format($transaction->invoice_amount, 0) }}
                                    </td>
                                    <td style="padding: 0.75rem; text-align: right;">
                                        Rs. {{ number_format($transaction->customer_profit_share, 0) }}
                                    </td>
                                    <td style="padding: 0.75rem; text-align: center;">
                                        @if($transaction->status === 'confirmed')
                                            <span class="badge-success">Confirmed</span>
                                        @elseif($transaction->status === 'pending_confirmation')
                                            <span class="badge-warning">Pending</span>
                                        @elseif($transaction->status === 'rejected')
                                            <span style="background: #e74c3c; color: white; padding: 0.25rem 0.5rem; border-radius: 3px; font-size: 0.75rem;">Rejected</span>
                                        @else
                                            <span class="badge-secondary">{{ ucfirst($transaction->status) }}</span>
                                        @endif
                                    </td>
                                    <td style="padding: 0.75rem;">
                                        {{ $transaction->transaction_date->format('M j, Y') }}
                                        <br>
                                        <small style="color: #999;">{{ $transaction->transaction_date->format('g:i A') }}</small>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div style="margin-top: 1.5rem; display: flex; justify-content: center;">
                    {{ $transactions->links() }}
                </div>
            @else
                <div style="text-align: center; padding: 3rem; color: #666;">
                    <h4>No transactions found</h4>
                    <p>This customer hasn't made any purchases yet.</p>
                </div>
            @endif

        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paginationLinks = document.querySelectorAll('.pagination a, .pagination span');
        paginationLinks.forEach(link => {
            link.style.cssText = 'padding: 0.5rem 0.75rem; margin: 0 0.25rem; border: 1px solid #dee2e6; border-radius: 3px; text-decoration: none; color: #495057;';
            if (link.classList.contains('active')) {
                link.style.cssText += 'background: #3498db; color: white; border-color: #3498db;';
            }
        });
    });
</script>
@endpush
