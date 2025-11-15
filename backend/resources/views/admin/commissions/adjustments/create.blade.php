@extends('layouts.admin')

@section('title', 'Create Adjustment')
@section('page-title', 'Create Commission Adjustment')

@section('content')
    <div class="card">
        <div class="card-header" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 1.5rem;">
            <h4 style="margin: 0;">✏️ Adjustment for {{ $ledger->partner->partnerProfile->business_name ?? $ledger->partner->name }}</h4>
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

            <!-- Info Alert -->
            <div style="background: #fff3cd; border: 1px solid #ffc107; border-radius: 8px; padding: 1rem; margin-bottom: 2rem;">
                <div style="display: flex; align-items: start; gap: 0.75rem;">
                    <span style="font-size: 1.5rem;">ℹ️</span>
                    <div style="flex: 1;">
                        <strong style="color: #856404; display: block; margin-bottom: 0.5rem;">About Adjustments</strong>
                        <ul style="margin: 0; padding-left: 1.25rem; color: #856404;">
                            <li><strong>Positive Amount:</strong> Increases commission owed (e.g., correction, penalty)</li>
                            <li><strong>Negative Amount:</strong> Decreases commission owed (e.g., refund, bonus)</li>
                            <li>Adjustments automatically update the ledger balance and status</li>
                            <li>Reason is required for audit trail</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Adjustment Form -->
            <form action="{{ route('admin.commissions.adjustments.store', $ledger->id) }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6" style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Adjustment Amount <span style="color: red;">*</span></label>
                        <input type="number" name="adjustment_amount" step="0.01"
                               value="{{ old('adjustment_amount') }}"
                               class="form-control @error('adjustment_amount') is-invalid @enderror"
                               style="padding: 0.75rem;"
                               placeholder="Enter positive or negative amount"
                               required>
                        @error('adjustment_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small style="color: #6c757d;">
                            Examples: <strong>500</strong> (add Rs 500), <strong>-200</strong> (subtract Rs 200)
                        </small>
                    </div>

                    <div class="col-md-6" style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Adjustment Type <span style="color: red;">*</span></label>
                        <select name="adjustment_type" class="form-control @error('adjustment_type') is-invalid @enderror"
                                style="padding: 0.75rem;" required>
                            <option value="">Select Type</option>
                            <option value="refund" {{ old('adjustment_type') == 'refund' ? 'selected' : '' }}>💸 Refund (Negative)</option>
                            <option value="correction" {{ old('adjustment_type') == 'correction' ? 'selected' : '' }}>✏️ Correction (Positive/Negative)</option>
                            <option value="penalty" {{ old('adjustment_type') == 'penalty' ? 'selected' : '' }}>⚠️ Penalty (Positive)</option>
                            <option value="bonus" {{ old('adjustment_type') == 'bonus' ? 'selected' : '' }}>🎁 Bonus (Negative)</option>
                            <option value="other" {{ old('adjustment_type') == 'other' ? 'selected' : '' }}>📝 Other</option>
                        </select>
                        @error('adjustment_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12" style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Adjustment Reason <span style="color: red;">*</span></label>
                        <textarea name="adjustment_reason" rows="4"
                                  class="form-control @error('adjustment_reason') is-invalid @enderror"
                                  style="padding: 0.75rem;"
                                  placeholder="Explain why this adjustment is needed (required for audit trail)"
                                  required>{{ old('adjustment_reason') }}</textarea>
                        @error('adjustment_reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small style="color: #6c757d;">Maximum 1000 characters</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12" style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Settlement Reference (Optional)</label>
                        <input type="text" name="settlement_reference" value="{{ old('settlement_reference') }}"
                               class="form-control @error('settlement_reference') is-invalid @enderror"
                               style="padding: 0.75rem;"
                               placeholder="Reference number, ticket ID, etc.">
                        @error('settlement_reference')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div style="display: flex; gap: 1rem; justify-content: flex-end; border-top: 1px solid #dee2e6; padding-top: 1.5rem; margin-top: 1rem;">
                    <a href="{{ route('admin.commissions.partners.show', $ledger->partner_id) }}" class="btn btn-outline-secondary" style="padding: 0.75rem 1.5rem;">
                        ← Cancel
                    </a>
                    <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem;">
                        ✅ Create Adjustment
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Example Scenarios -->
    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header" style="background: #f8f9fa; padding: 1rem;">
            <h5 style="margin: 0;">💡 Common Adjustment Scenarios</h5>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
                <div style="padding: 1rem; background: #fff3cd; border-radius: 8px;">
                    <strong style="color: #856404; display: block; margin-bottom: 0.5rem;">Scenario 1: Transaction Refunded</strong>
                    <p style="margin: 0; color: #856404; font-size: 0.9rem;">
                        Partner processed a Rs 10,000 transaction (Rs 300 commission). Customer got refund.<br>
                        <strong>Action:</strong> Amount: <code>-300</code>, Type: <code>Refund</code>
                    </p>
                </div>
                <div style="padding: 1rem; background: #d1ecf1; border-radius: 8px;">
                    <strong style="color: #0c5460; display: block; margin-bottom: 0.5rem;">Scenario 2: Calculation Error</strong>
                    <p style="margin: 0; color: #0c5460; font-size: 0.9rem;">
                        System calculated Rs 5,000 but should be Rs 5,500.<br>
                        <strong>Action:</strong> Amount: <code>500</code>, Type: <code>Correction</code>
                    </p>
                </div>
                <div style="padding: 1rem; background: #f8d7da; border-radius: 8px;">
                    <strong style="color: #721c24; display: block; margin-bottom: 0.5rem;">Scenario 3: Late Payment Penalty</strong>
                    <p style="margin: 0; color: #721c24; font-size: 0.9rem;">
                        Partner delayed payment by 60 days. Rs 1,000 late fee.<br>
                        <strong>Action:</strong> Amount: <code>1000</code>, Type: <code>Penalty</code>
                    </p>
                </div>
                <div style="padding: 1rem; background: #d4edda; border-radius: 8px;">
                    <strong style="color: #155724; display: block; margin-bottom: 0.5rem;">Scenario 4: Early Payment Bonus</strong>
                    <p style="margin: 0; color: #155724; font-size: 0.9rem;">
                        Partner paid within 7 days. Rs 500 discount.<br>
                        <strong>Action:</strong> Amount: <code>-500</code>, Type: <code>Bonus</code>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
