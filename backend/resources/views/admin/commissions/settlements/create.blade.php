@extends('layouts.admin')

@section('title', 'Process Settlement')
@section('page-title', 'Process Commission Settlement')

@section('content')
    <div class="card">
        <div class="card-header" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 1.5rem;">
            <h4 style="margin: 0;">💵 Settlement for {{ $ledger->partner->name }}</h4>
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
