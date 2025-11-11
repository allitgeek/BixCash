@extends('layouts.admin')

@section('title', 'Withdrawal Settings - BixCash Admin')
@section('page-title', 'Withdrawal Settings')

@section('content')
    <div class="alert alert-info" style="margin-bottom: 1.5rem; background: #e3f2fd; border-left: 4px solid #2196f3; padding: 1.5rem;">
        <h4 style="margin-bottom: 1rem; color: #1565c0;">💰 Withdrawal Limit Configuration</h4>
        <p style="margin-bottom: 0.5rem;">Configure global limits for customer wallet withdrawals. These limits apply to all customers.</p>
        <ul style="margin-left: 1.5rem; line-height: 1.8; margin-top: 1rem;">
            <li><strong>Min Amount:</strong> Minimum amount per withdrawal request</li>
            <li><strong>Max Per Withdrawal:</strong> Maximum amount for a single withdrawal</li>
            <li><strong>Max Per Day:</strong> Total withdrawal limit per customer per day</li>
            <li><strong>Max Per Month:</strong> Total withdrawal limit per customer per month</li>
            <li><strong>Min Gap Hours:</strong> Minimum time gap between withdrawal requests</li>
        </ul>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Configure Withdrawal Limits</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.settings.withdrawals.update') }}">
                @csrf

                <!-- Enable/Disable Toggle -->
                <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem;">
                    <label style="display: flex; align-items: center; cursor: pointer;">
                        <input type="checkbox" name="enabled" value="1" {{ $settings->enabled ? 'checked' : '' }}
                               style="width: 20px; height: 20px; margin-right: 0.75rem; cursor: pointer;">
                        <span style="font-size: 1.1rem; font-weight: 500;">
                            Enable Withdrawals System-wide
                        </span>
                    </label>
                    <small style="color: #666; margin-left: 2rem; display: block; margin-top: 0.5rem;">
                        Uncheck to temporarily disable all withdrawal requests
                    </small>
                </div>

                <!-- Withdrawal Limits -->
                <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem;">
                    <h4 style="margin-bottom: 1.5rem; color: #333;">Withdrawal Limits (PKR)</h4>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                        <div>
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Minimum Amount *</label>
                            <input type="number" name="min_amount" value="{{ old('min_amount', $settings->min_amount) }}"
                                   required min="0" step="0.01" placeholder="100"
                                   style="width: 100%; padding: 0.75rem; border: 1px solid #dee2e6; border-radius: 4px;">
                            <small style="color: #666;">Minimum withdrawal amount (e.g., Rs. 100)</small>
                        </div>

                        <div>
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Maximum Per Withdrawal *</label>
                            <input type="number" name="max_per_withdrawal" value="{{ old('max_per_withdrawal', $settings->max_per_withdrawal) }}"
                                   required min="0" step="0.01" placeholder="50000"
                                   style="width: 100%; padding: 0.75rem; border: 1px solid #dee2e6; border-radius: 4px;">
                            <small style="color: #666;">Maximum amount per single withdrawal</small>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div>
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Maximum Per Day *</label>
                            <input type="number" name="max_per_day" value="{{ old('max_per_day', $settings->max_per_day) }}"
                                   required min="0" step="0.01" placeholder="100000"
                                   style="width: 100%; padding: 0.75rem; border: 1px solid #dee2e6; border-radius: 4px;">
                            <small style="color: #666;">Total daily withdrawal limit per customer</small>
                        </div>

                        <div>
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Maximum Per Month *</label>
                            <input type="number" name="max_per_month" value="{{ old('max_per_month', $settings->max_per_month) }}"
                                   required min="0" step="0.01" placeholder="500000"
                                   style="width: 100%; padding: 0.75rem; border: 1px solid #dee2e6; border-radius: 4px;">
                            <small style="color: #666;">Total monthly withdrawal limit per customer</small>
                        </div>
                    </div>
                </div>

                <!-- Time Gap Settings -->
                <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem;">
                    <h4 style="margin-bottom: 1.5rem; color: #333;">Time Gap Settings</h4>

                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Minimum Gap Between Withdrawals (Hours) *</label>
                        <input type="number" name="min_gap_hours" value="{{ old('min_gap_hours', $settings->min_gap_hours) }}"
                               required min="0" max="168" step="1" placeholder="6"
                               style="width: 100%; padding: 0.75rem; border: 1px solid #dee2e6; border-radius: 4px;">
                        <small style="color: #666;">Minimum hours customers must wait between withdrawal requests (0-168 hours / 7 days)</small>
                    </div>
                </div>

                <!-- Processing Message -->
                <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem;">
                    <h4 style="margin-bottom: 1.5rem; color: #333;">Customer Communication</h4>

                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Processing Time Message</label>
                        <textarea name="processing_message" rows="3" placeholder="e.g., Withdrawal requests are typically processed within 24-48 business hours."
                                  style="width: 100%; padding: 0.75rem; border: 1px solid #dee2e6; border-radius: 4px; resize: vertical;">{{ old('processing_message', $settings->processing_message) }}</textarea>
                        <small style="color: #666;">This message will be shown to customers on the wallet page</small>
                    </div>
                </div>

                <!-- Submit Button -->
                <div style="display: flex; gap: 1rem;">
                    <button type="submit" style="background: #28a745; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 4px; font-weight: 500; cursor: pointer;">
                        💾 Save Settings
                    </button>
                    <a href="{{ route('admin.withdrawals.index') }}" style="background: #6c757d; color: white; padding: 0.75rem 1.5rem; border-radius: 4px; text-decoration: none; display: inline-block;">
                        View Withdrawal Requests
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
