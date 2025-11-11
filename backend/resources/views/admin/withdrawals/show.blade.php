@extends('layouts.admin')

@section('title', 'Withdrawal Request #' . $withdrawal->id . ' - BixCash Admin')
@section('page-title', 'Withdrawal Request #' . $withdrawal->id)

@section('content')
    <div style="margin-bottom: 1rem;">
        <a href="{{ route('admin.withdrawals.index') }}" style="color: #007bff; text-decoration: none;">
            ← Back to Withdrawals
        </a>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
        <!-- Left Column -->
        <div>
            <!-- Customer Details -->
            <div class="card" style="margin-bottom: 1.5rem;">
                <div class="card-header">
                    <h3 class="card-title">👤 Customer Information</h3>
                </div>
                <div class="card-body">
                    <table style="width: 100%;">
                        <tr>
                            <td style="padding: 0.75rem 0; font-weight: 500; width: 150px;">Name:</td>
                            <td style="padding: 0.75rem 0;">{{ $withdrawal->user->name }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 0.75rem 0; font-weight: 500;">Phone:</td>
                            <td style="padding: 0.75rem 0;">{{ $withdrawal->user->phone }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 0.75rem 0; font-weight: 500;">Email:</td>
                            <td style="padding: 0.75rem 0;">{{ $withdrawal->user->email ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 0.75rem 0; font-weight: 500;">Account Age:</td>
                            <td style="padding: 0.75rem 0;">{{ $accountAge }} days</td>
                        </tr>
                        <tr>
                            <td style="padding: 0.75rem 0; font-weight: 500;">Current Balance:</td>
                            <td style="padding: 0.75rem 0;"><strong>Rs. {{ number_format($withdrawal->user->wallet->balance ?? 0, 2) }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Bank Details -->
            <div class="card" style="margin-bottom: 1.5rem;">
                <div class="card-header">
                    <h3 class="card-title">🏦 Bank Details</h3>
                </div>
                <div class="card-body">
                    <table style="width: 100%;">
                        <tr>
                            <td style="padding: 0.75rem 0; font-weight: 500; width: 150px;">Bank Name:</td>
                            <td style="padding: 0.75rem 0;">{{ $withdrawal->bank_name }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 0.75rem 0; font-weight: 500;">Account Title:</td>
                            <td style="padding: 0.75rem 0;">{{ $withdrawal->account_title }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 0.75rem 0; font-weight: 500;">Account Number:</td>
                            <td style="padding: 0.75rem 0; font-family: monospace; font-size: 1.1rem;"><strong>{{ $withdrawal->account_number }}</strong></td>
                        </tr>
                        @if($withdrawal->iban)
                        <tr>
                            <td style="padding: 0.75rem 0; font-weight: 500;">IBAN:</td>
                            <td style="padding: 0.75rem 0; font-family: monospace;">{{ $withdrawal->iban }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            <!-- Withdrawal History -->
            @if($withdrawalHistory->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">📜 Previous Withdrawals (Last 10)</h3>
                </div>
                <div class="card-body" style="padding: 0;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead style="background: #f8f9fa;">
                            <tr>
                                <th style="padding: 0.75rem; text-align: left; border-bottom: 2px solid #dee2e6;">Date</th>
                                <th style="padding: 0.75rem; text-align: right; border-bottom: 2px solid #dee2e6;">Amount</th>
                                <th style="padding: 0.75rem; text-align: center; border-bottom: 2px solid #dee2e6;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($withdrawalHistory as $history)
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 0.75rem;">{{ $history->created_at->format('M d, Y') }}</td>
                                <td style="padding: 0.75rem; text-align: right;">Rs. {{ number_format($history->amount, 2) }}</td>
                                <td style="padding: 0.75rem; text-align: center;">
                                    @if($history->status === 'completed')
                                        <span style="background: #28a745; color: #fff; padding: 0.25rem 0.5rem; border-radius: 8px; font-size: 0.75rem;">✅</span>
                                    @elseif($history->status === 'rejected')
                                        <span style="background: #dc3545; color: #fff; padding: 0.25rem 0.5rem; border-radius: 8px; font-size: 0.75rem;">❌</span>
                                    @else
                                        <span style="background: #ffc107; color: #000; padding: 0.25rem 0.5rem; border-radius: 8px; font-size: 0.75rem;">⏳</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column -->
        <div>
            <!-- Transaction Details -->
            <div class="card" style="margin-bottom: 1.5rem;">
                <div class="card-header">
                    <h3 class="card-title">💰 Transaction Details</h3>
                </div>
                <div class="card-body">
                    <div style="text-align: center; padding: 1rem 0; border-bottom: 1px solid #dee2e6; margin-bottom: 1rem;">
                        <div style="color: #666; font-size: 0.875rem; margin-bottom: 0.5rem;">Withdrawal Amount</div>
                        <div style="font-size: 2rem; font-weight: bold; color: #dc3545;">Rs. {{ number_format($withdrawal->amount, 2) }}</div>
                    </div>

                    <table style="width: 100%;">
                        <tr>
                            <td style="padding: 0.5rem 0; font-weight: 500;">Status:</td>
                            <td style="padding: 0.5rem 0; text-align: right;">
                                @if($withdrawal->status === 'pending')
                                    <span style="background: #ffc107; color: #000; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.875rem; font-weight: 500;">⏳ Pending</span>
                                @elseif($withdrawal->status === 'processing')
                                    <span style="background: #17a2b8; color: #fff; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.875rem; font-weight: 500;">🔄 Processing</span>
                                @elseif($withdrawal->status === 'completed')
                                    <span style="background: #28a745; color: #fff; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.875rem; font-weight: 500;">✅ Completed</span>
                                @elseif($withdrawal->status === 'rejected')
                                    <span style="background: #dc3545; color: #fff; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.875rem; font-weight: 500;">❌ Rejected</span>
                                @else
                                    <span style="background: #6c757d; color: #fff; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.875rem; font-weight: 500;">🚫 Cancelled</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 0.5rem 0; font-weight: 500;">Requested:</td>
                            <td style="padding: 0.5rem 0; text-align: right;">{{ $withdrawal->created_at->format('M d, Y h:i A') }}</td>
                        </tr>
                        @if($withdrawal->fraud_score > 0)
                        <tr>
                            <td style="padding: 0.5rem 0; font-weight: 500;">Fraud Score:</td>
                            <td style="padding: 0.5rem 0; text-align: right;">
                                <span style="background: {{ $withdrawal->fraud_score >= 50 ? '#ff6b6b' : '#ffa502' }}; color: white; padding: 0.25rem 0.75rem; border-radius: 12px; font-weight: 600;">
                                    {{ $withdrawal->fraud_score }}/100
                                </span>
                            </td>
                        </tr>
                        @endif
                    </table>

                    @if($withdrawal->fraud_flags && count(json_decode($withdrawal->fraud_flags, true)) > 0)
                    <div style="margin-top: 1rem; padding: 1rem; background: #fff3cd; border-left: 4px solid #ffa502; border-radius: 4px;">
                        <strong style="color: #856404;">🚩 Fraud Flags:</strong>
                        <ul style="margin: 0.5rem 0 0 1rem; color: #856404;">
                            @foreach(json_decode($withdrawal->fraud_flags, true) as $flag)
                                <li>{{ ucwords(str_replace('_', ' ', $flag)) }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Approval/Rejection Forms -->
            @if($withdrawal->status === 'pending' || $withdrawal->status === 'processing')
            <div class="card" style="margin-bottom: 1.5rem; border: 2px solid #28a745;">
                <div class="card-header" style="background: #28a745; color: white;">
                    <h3 class="card-title">✅ Approve Withdrawal</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.withdrawals.approve', $withdrawal->id) }}" enctype="multipart/form-data">
                        @csrf
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Bank Reference *</label>
                            <input type="text" name="bank_reference" required placeholder="e.g., TXN123456789" style="width: 100%; padding: 0.5rem; border: 1px solid #dee2e6; border-radius: 4px;">
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Payment Date *</label>
                            <input type="date" name="payment_date" required max="{{ date('Y-m-d') }}" style="width: 100%; padding: 0.5rem; border: 1px solid #dee2e6; border-radius: 4px;">
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Proof of Payment</label>
                            <input type="file" name="proof_of_payment" accept=".jpg,.jpeg,.png,.pdf" style="width: 100%;">
                            <small style="color: #666;">Optional: Upload receipt (JPG, PNG, PDF, max 5MB)</small>
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Admin Notes</label>
                            <textarea name="admin_notes" rows="2" placeholder="Internal notes..." style="width: 100%; padding: 0.5rem; border: 1px solid #dee2e6; border-radius: 4px;"></textarea>
                        </div>
                        <button type="submit" onclick="return confirm('Approve this withdrawal? Customer has already been debited.')" style="width: 100%; background: #28a745; color: white; padding: 0.75rem; border: none; border-radius: 4px; font-weight: 500; cursor: pointer;">
                            ✅ Approve Withdrawal
                        </button>
                    </form>
                </div>
            </div>

            <div class="card" style="border: 2px solid #dc3545;">
                <div class="card-header" style="background: #dc3545; color: white;">
                    <h3 class="card-title">❌ Reject Withdrawal</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.withdrawals.reject', $withdrawal->id) }}">
                        @csrf
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Rejection Reason *</label>
                            <textarea name="rejection_reason" rows="3" required placeholder="Explain why this withdrawal is being rejected..." style="width: 100%; padding: 0.5rem; border: 1px solid #dee2e6; border-radius: 4px;"></textarea>
                            <small style="color: #666;">This will be shown to the customer</small>
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Admin Notes</label>
                            <textarea name="admin_notes" rows="2" placeholder="Internal notes..." style="width: 100%; padding: 0.5rem; border: 1px solid #dee2e6; border-radius: 4px;"></textarea>
                        </div>
                        <button type="submit" onclick="return confirm('Reject this withdrawal? Amount will be refunded to customer wallet.')" style="width: 100%; background: #dc3545; color: white; padding: 0.75rem; border: none; border-radius: 4px; font-weight: 500; cursor: pointer;">
                            ❌ Reject & Refund
                        </button>
                    </form>
                </div>
            </div>
            @else
            <!-- Completed/Rejected Info -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">ℹ️ Processing Information</h3>
                </div>
                <div class="card-body">
                    <table style="width: 100%;">
                        <tr>
                            <td style="padding: 0.5rem 0; font-weight: 500;">Processed By:</td>
                            <td style="padding: 0.5rem 0; text-align: right;">{{ $withdrawal->processedBy->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 0.5rem 0; font-weight: 500;">Processed At:</td>
                            <td style="padding: 0.5rem 0; text-align: right;">{{ $withdrawal->processed_at ? $withdrawal->processed_at->format('M d, Y h:i A') : 'N/A' }}</td>
                        </tr>
                        @if($withdrawal->status === 'completed')
                        <tr>
                            <td style="padding: 0.5rem 0; font-weight: 500;">Bank Reference:</td>
                            <td style="padding: 0.5rem 0; text-align: right; font-family: monospace;"><strong>{{ $withdrawal->bank_reference }}</strong></td>
                        </tr>
                        <tr>
                            <td style="padding: 0.5rem 0; font-weight: 500;">Payment Date:</td>
                            <td style="padding: 0.5rem 0; text-align: right;">{{ $withdrawal->payment_date ? \Carbon\Carbon::parse($withdrawal->payment_date)->format('M d, Y') : 'N/A' }}</td>
                        </tr>
                        @if($withdrawal->proof_of_payment)
                        <tr>
                            <td colspan="2" style="padding: 0.5rem 0;">
                                <a href="{{ Storage::url($withdrawal->proof_of_payment) }}" target="_blank" style="color: #007bff; text-decoration: none;">
                                    📎 View Proof of Payment
                                </a>
                            </td>
                        </tr>
                        @endif
                        @endif
                        @if($withdrawal->status === 'rejected' && $withdrawal->rejection_reason)
                        <tr>
                            <td colspan="2" style="padding: 0.5rem 0;">
                                <div style="background: #f8d7da; padding: 0.75rem; border-radius: 4px; border-left: 4px solid #dc3545;">
                                    <strong>Rejection Reason:</strong><br>
                                    {{ $withdrawal->rejection_reason }}
                                </div>
                            </td>
                        </tr>
                        @endif
                        @if($withdrawal->admin_notes)
                        <tr>
                            <td colspan="2" style="padding: 0.5rem 0;">
                                <div style="background: #e7f3ff; padding: 0.75rem; border-radius: 4px; border-left: 4px solid #007bff;">
                                    <strong>Admin Notes:</strong><br>
                                    {{ $withdrawal->admin_notes }}
                                </div>
                            </td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection
