@extends('layouts.admin')

@section('title', 'Process Settlement')
@section('page-title', 'Process Commission Settlement')

@section('content')
    <div class="card">
        <div class="card-header" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 1.5rem;">
            <h4 style="margin: 0;">💵 Settlement for {{ $ledger->partner->partnerProfile->business_name ?? $ledger->partner->name }}</h4>
            <p style="margin: 0.5rem 0 0 0; opacity: 0.9;">Period: {{ $ledger->formatted_period }}</p>
        </div>
        <div class="card-body">
            <!-- Ledger Summary -->
            <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem;">
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem;">
                    <div>
                        <small style="color: #6c757d; display: block;">Commission Owed</small>
                        <strong style="font-size: 1.25rem;">Rs {{ number_format($ledger->commission_owed, 2) }}</strong>
                    </div>
                    <div>
                        <small style="color: #6c757d; display: block;">Already Paid</small>
                        <strong style="font-size: 1.25rem; color: #00f2fe;">Rs {{ number_format($ledger->amount_paid, 2) }}</strong>
                    </div>
                    <div>
                        <small style="color: #6c757d; display: block;">Outstanding</small>
                        <strong style="font-size: 1.25rem; color: #f5576c;">Rs {{ number_format($ledger->amount_outstanding, 2) }}</strong>
                    </div>
                    <div>
                        <small style="color: #6c757d; display: block;">Transactions</small>
                        <strong style="font-size: 1.25rem;">{{ number_format($ledger->total_transactions) }}</strong>
                    </div>
                </div>
            </div>

            <!-- Settlement Form -->
            <form action="{{ route('admin.commissions.settlements.store', $ledger->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6" style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Amount to Settle <span style="color: red;">*</span></label>
                        <input type="number" name="amount_settled" step="0.01" min="0.01" max="{{ $ledger->amount_outstanding }}" 
                               value="{{ old('amount_settled', $ledger->amount_outstanding) }}" 
                               class="form-control @error('amount_settled') is-invalid @enderror" 
                               style="padding: 0.75rem;" required>
                        @error('amount_settled')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small style="color: #6c757d;">Maximum: Rs {{ number_format($ledger->amount_outstanding, 2) }}</small>
                    </div>

                    <div class="col-md-6" style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Payment Method <span style="color: red;">*</span></label>
                        <select name="payment_method" class="form-control @error('payment_method') is-invalid @enderror" 
                                style="padding: 0.75rem;" required>
                            <option value="">Select Method</option>
                            <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="wallet_deduction" {{ old('payment_method') == 'wallet_deduction' ? 'selected' : '' }}>Wallet Deduction</option>
                            <option value="adjustment" {{ old('payment_method') == 'adjustment' ? 'selected' : '' }}>Adjustment</option>
                            <option value="other" {{ old('payment_method') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('payment_method')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        {{-- Wallet Balance Info --}}
                        @if($ledger->partner->wallet)
                            <div id="wallet-balance-info" style="display: none; margin-top: 0.75rem; padding: 0.75rem; background: #d1ecf1; border: 1px solid #bee5eb; border-radius: 4px;">
                                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.25rem;">
                                    <svg style="width: 16px; height: 16px; fill: #0c5460;" viewBox="0 0 20 20">
                                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                                        <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    <strong style="color: #0c5460; font-size: 0.9rem;">Partner's Wallet Balance</strong>
                                </div>
                                <div style="font-size: 1.1rem; color: #0c5460; font-weight: 600;">
                                    Rs {{ number_format($ledger->partner->wallet->balance, 2) }}
                                </div>
                                @if($ledger->partner->wallet->balance < $ledger->amount_outstanding)
                                    <small style="color: #856404; display: block; margin-top: 0.5rem;">
                                        ⚠️ Insufficient balance for full settlement. Available: Rs {{ number_format($ledger->partner->wallet->balance, 2) }}
                                    </small>
                                @endif
                            </div>
                        @else
                            <div id="wallet-unavailable-info" style="display: none; margin-top: 0.75rem; padding: 0.75rem; background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 4px;">
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <svg style="width: 16px; height: 16px; fill: #721c24;" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    <strong style="color: #721c24; font-size: 0.9rem;">Partner does not have a wallet</strong>
                                </div>
                                <small style="color: #721c24; display: block; margin-top: 0.25rem;">
                                    Please use another payment method.
                                </small>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6" style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Settlement Reference</label>
                        <input type="text" name="settlement_reference" value="{{ old('settlement_reference') }}" 
                               class="form-control @error('settlement_reference') is-invalid @enderror" 
                               style="padding: 0.75rem;" 
                               placeholder="Transaction ID, Check #, etc.">
                        @error('settlement_reference')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6" style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Proof of Payment</label>
                        <input type="file" name="proof_of_payment" 
                               class="form-control @error('proof_of_payment') is-invalid @enderror" 
                               accept=".jpg,.jpeg,.png,.pdf">
                        @error('proof_of_payment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small style="color: #6c757d;">JPG, PNG, PDF (Max 5MB)</small>
                    </div>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Admin Notes</label>
                    <textarea name="admin_notes" rows="3" 
                              class="form-control @error('admin_notes') is-invalid @enderror" 
                              style="padding: 0.75rem;" 
                              placeholder="Any notes about this settlement...">{{ old('admin_notes') }}</textarea>
                    @error('admin_notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror>
                </div>

                <hr style="margin: 2rem 0;">

                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-success" style="padding: 0.75rem 2rem;">
                        ✅ Process Settlement
                    </button>
                    <a href="{{ route('admin.commissions.partners.show', $ledger->partner_id) }}" class="btn btn-secondary" style="padding: 0.75rem 2rem;">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethodSelect = document.querySelector('select[name="payment_method"]');
    const walletBalanceInfo = document.getElementById('wallet-balance-info');
    const walletUnavailableInfo = document.getElementById('wallet-unavailable-info');

    function toggleWalletInfo() {
        const isWalletDeduction = paymentMethodSelect.value === 'wallet_deduction';

        if (walletBalanceInfo) {
            walletBalanceInfo.style.display = isWalletDeduction ? 'block' : 'none';
        }

        if (walletUnavailableInfo) {
            walletUnavailableInfo.style.display = isWalletDeduction ? 'block' : 'none';
        }
    }

    // Toggle on change
    paymentMethodSelect.addEventListener('change', toggleWalletInfo);

    // Check initial state (in case of validation errors and old input)
    toggleWalletInfo();
});
</script>
@endpush
