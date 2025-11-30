@extends('layouts.admin')

@section('title', 'WhatsApp OTP Settings - BixCash Admin')
@section('page-title', 'WhatsApp OTP Settings')

@section('content')
    <div class="alert alert-info" style="margin-bottom: 1.5rem; background: #e8f5e9; border-left: 4px solid #25d366; padding: 1.5rem;">
        <h4 style="margin-bottom: 1rem; color: #1b5e20;">
            <i class="fab fa-whatsapp" style="color: #25d366;"></i> WhatsApp OTP Configuration
        </h4>
        <p style="margin-bottom: 0.5rem;">Configure WhatsApp OTP verification for enhanced security. OTPs can be sent via WhatsApp for:</p>
        <ul style="margin-left: 1.5rem; line-height: 1.8; margin-top: 1rem;">
            <li><strong>User Registration:</strong> Verify phone numbers during sign-up</li>
            <li><strong>Login 2FA:</strong> Additional security layer for all logins</li>
            <li><strong>Transaction Verification:</strong> Confirm high-value transactions</li>
        </ul>
    </div>

    <form method="POST" action="{{ route('admin.settings.whatsapp.save') }}" id="whatsapp-settings-form">
        @csrf

        <!-- API Configuration Card -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-key"></i> API Configuration
                </h3>
            </div>
            <div class="card-body">
                <!-- API Key -->
                <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">WhatsApp API Key</label>
                    <div style="display: flex; gap: 0.5rem;">
                        <input type="password" name="whatsapp_api_key" id="whatsapp_api_key"
                               value="{{ $settings['api_key_set'] ? '••••••••••••••••••••••••' : '' }}"
                               placeholder="Enter your WhatsApp API key"
                               style="flex: 1; padding: 0.75rem; border: 1px solid #dee2e6; border-radius: 4px;">
                        <button type="button" onclick="toggleApiKeyVisibility()"
                                style="padding: 0.75rem 1rem; border: 1px solid #dee2e6; border-radius: 4px; background: white; cursor: pointer;">
                            <i class="fas fa-eye" id="eye-icon"></i>
                        </button>
                    </div>
                    <small style="color: #666; display: block; margin-top: 0.5rem;">
                        Get your API key from <a href="https://whatsapp.fimm.app" target="_blank">whatsapp.fimm.app</a>
                    </small>
                </div>

                <!-- API URL -->
                <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">API Base URL</label>
                    <input type="url" name="whatsapp_api_url"
                           value="{{ old('whatsapp_api_url', $settings['api_url']) }}"
                           placeholder="https://whatsapp.fimm.app/api"
                           required
                           style="width: 100%; padding: 0.75rem; border: 1px solid #dee2e6; border-radius: 4px;">
                    <small style="color: #666; display: block; margin-top: 0.5rem;">
                        Default: https://whatsapp.fimm.app/api
                    </small>
                </div>

                <!-- Enable WhatsApp OTP -->
                <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; margin-bottom: 1.5rem;">
                    <label style="display: flex; align-items: center; cursor: pointer;">
                        <input type="checkbox" name="whatsapp_otp_enabled" value="1"
                               {{ $settings['enabled'] ? 'checked' : '' }}
                               style="width: 20px; height: 20px; margin-right: 0.75rem; cursor: pointer;">
                        <span style="font-size: 1.1rem; font-weight: 500;">
                            Enable WhatsApp OTP Service
                        </span>
                    </label>
                    <small style="color: #666; margin-left: 2rem; display: block; margin-top: 0.5rem;">
                        Enable to allow sending OTPs via WhatsApp
                    </small>
                </div>

                <!-- Primary OTP Provider -->
                <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 1rem; font-weight: 500;">Primary OTP Provider</label>
                    <div style="display: flex; gap: 2rem;">
                        <label style="display: flex; align-items: center; cursor: pointer;">
                            <input type="radio" name="primary_otp_provider" value="firebase"
                                   {{ $settings['primary_provider'] === 'firebase' ? 'checked' : '' }}
                                   style="width: 18px; height: 18px; margin-right: 0.5rem;">
                            <span>
                                <i class="fas fa-fire" style="color: #FFA000;"></i> Firebase (SMS)
                            </span>
                        </label>
                        <label style="display: flex; align-items: center; cursor: pointer;">
                            <input type="radio" name="primary_otp_provider" value="whatsapp"
                                   {{ $settings['primary_provider'] === 'whatsapp' ? 'checked' : '' }}
                                   style="width: 18px; height: 18px; margin-right: 0.5rem;">
                            <span>
                                <i class="fab fa-whatsapp" style="color: #25d366;"></i> WhatsApp
                            </span>
                        </label>
                    </div>
                    <small style="color: #666; display: block; margin-top: 0.75rem;">
                        Select the primary method for sending OTPs. WhatsApp will fallback to Firebase if recipient doesn't have WhatsApp.
                    </small>
                </div>

                <!-- Connection Status & Test Button -->
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <button type="button" onclick="testWhatsAppConnection()"
                            style="background: #25d366; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 4px; cursor: pointer; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-plug"></i> Test Connection
                    </button>
                    <div id="connection-status">
                        <span style="background: #6c757d; color: white; padding: 0.4rem 0.75rem; border-radius: 4px; font-size: 0.875rem;">
                            Not tested
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2FA Settings Card -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-shield-alt"></i> Two-Factor Authentication (2FA)
                </h3>
            </div>
            <div class="card-body">
                <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px;">
                    <label style="display: flex; align-items: center; cursor: pointer;">
                        <input type="checkbox" name="login_2fa_enabled" value="1"
                               {{ $settings['login_2fa_enabled'] ? 'checked' : '' }}
                               style="width: 20px; height: 20px; margin-right: 0.75rem; cursor: pointer;">
                        <span style="font-size: 1.1rem; font-weight: 500;">
                            Enable Login 2FA for All Users
                        </span>
                    </label>
                    <small style="color: #666; margin-left: 2rem; display: block; margin-top: 0.5rem;">
                        When enabled, all users will be required to verify OTP after entering their credentials
                    </small>
                </div>
            </div>
        </div>

        <!-- Transaction OTP Settings Card -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-receipt"></i> Transaction OTP Verification
                </h3>
            </div>
            <div class="card-body">
                <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; margin-bottom: 1.5rem;">
                    <label style="display: flex; align-items: center; cursor: pointer;">
                        <input type="checkbox" name="transaction_otp_enabled" value="1"
                               {{ $settings['transaction_otp_enabled'] ? 'checked' : '' }}
                               style="width: 20px; height: 20px; margin-right: 0.75rem; cursor: pointer;"
                               onchange="toggleThresholdInput(this)">
                        <span style="font-size: 1.1rem; font-weight: 500;">
                            Enable OTP for High-Value Transactions
                        </span>
                    </label>
                    <small style="color: #666; margin-left: 2rem; display: block; margin-top: 0.5rem;">
                        Require OTP verification for transactions above the threshold amount
                    </small>
                </div>

                <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px;" id="threshold-section">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">
                        Transaction Amount Threshold (PKR)
                    </label>
                    <input type="number" name="transaction_otp_threshold"
                           value="{{ old('transaction_otp_threshold', $settings['transaction_otp_threshold']) }}"
                           placeholder="10000"
                           min="0" step="100"
                           style="width: 100%; padding: 0.75rem; border: 1px solid #dee2e6; border-radius: 4px;">
                    <small style="color: #666; display: block; margin-top: 0.5rem;">
                        Transactions above this amount will require OTP verification (e.g., Rs. 10,000)
                    </small>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div style="display: flex; gap: 1rem;">
            <button type="submit" style="background: #28a745; color: white; padding: 0.75rem 2rem; border: none; border-radius: 4px; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-save"></i> Save Settings
            </button>
            <a href="{{ route('admin.dashboard') }}" style="background: #6c757d; color: white; padding: 0.75rem 1.5rem; border-radius: 4px; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </form>

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                alert('{{ session('success') }}');
            });
        </script>
    @endif
@endsection

@push('scripts')
<script>
function toggleApiKeyVisibility() {
    const input = document.getElementById('whatsapp_api_key');
    const icon = document.getElementById('eye-icon');

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

function testWhatsAppConnection() {
    const statusDiv = document.getElementById('connection-status');
    statusDiv.innerHTML = '<span style="background: #ffc107; color: #212529; padding: 0.4rem 0.75rem; border-radius: 4px; font-size: 0.875rem;"><i class="fas fa-spinner fa-spin"></i> Testing...</span>';

    fetch('{{ route("admin.settings.whatsapp.test") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            statusDiv.innerHTML = '<span style="background: #28a745; color: white; padding: 0.4rem 0.75rem; border-radius: 4px; font-size: 0.875rem;"><i class="fas fa-check"></i> Connected</span>';
        } else {
            statusDiv.innerHTML = '<span style="background: #dc3545; color: white; padding: 0.4rem 0.75rem; border-radius: 4px; font-size: 0.875rem;"><i class="fas fa-times"></i> ' + (data.message || 'Connection failed') + '</span>';
        }
    })
    .catch(error => {
        statusDiv.innerHTML = '<span style="background: #dc3545; color: white; padding: 0.4rem 0.75rem; border-radius: 4px; font-size: 0.875rem;"><i class="fas fa-times"></i> Error: ' + error.message + '</span>';
    });
}

function toggleThresholdInput(checkbox) {
    const section = document.getElementById('threshold-section');
    section.style.opacity = checkbox.checked ? '1' : '0.5';
}

// Initialize threshold section opacity on page load
document.addEventListener('DOMContentLoaded', function() {
    const checkbox = document.querySelector('input[name="transaction_otp_enabled"]');
    if (checkbox) {
        toggleThresholdInput(checkbox);
    }
});
</script>
@endpush
