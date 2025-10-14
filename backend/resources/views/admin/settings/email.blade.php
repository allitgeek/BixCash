@extends('layouts.admin')

@section('title', 'Email Settings - BixCash Admin')
@section('page-title', 'Email Settings')

@section('content')
    <!-- Gmail App Password Help -->
    <div class="alert alert-info" style="margin-bottom: 1.5rem; background: #e3f2fd; border-left: 4px solid #2196f3; padding: 1.5rem;">
        <h4 style="margin-bottom: 1rem; color: #1565c0;">📧 Using Gmail? Important Information</h4>
        <p style="margin-bottom: 0.5rem;"><strong>Gmail requires an App-Specific Password, not your regular password.</strong></p>
        <p style="margin-bottom: 1rem;">Follow these steps to create an App Password:</p>
        <ol style="margin-left: 1.5rem; line-height: 1.8;">
            <li>Go to your <a href="https://myaccount.google.com/security" target="_blank" style="color: #2196f3; text-decoration: underline;">Google Account Security</a></li>
            <li>Enable <strong>2-Step Verification</strong> if not already enabled</li>
            <li>Go to <a href="https://myaccount.google.com/apppasswords" target="_blank" style="color: #2196f3; text-decoration: underline;">App Passwords</a></li>
            <li>Select "Mail" and "Other (Custom name)" → Enter "BixCash"</li>
            <li>Click "Generate" → Copy the 16-character password</li>
            <li>Use this App Password in the "SMTP Password" field below (not your regular Gmail password)</li>
        </ol>
        <p style="margin-top: 1rem; color: #666;"><strong>SMTP Settings for Gmail:</strong> Host: <code>smtp.gmail.com</code>, Port: <code>587</code>, Encryption: <code>TLS</code></p>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Configure Email Settings</h3>
        </div>
        <div class="card-body">
            <p style="margin-bottom: 1.5rem; color: #666;">
                Configure SMTP settings for sending customer query notifications and confirmation emails.
            </p>

            <form method="POST" action="{{ route('admin.settings.email.update') }}">
                @csrf
                @method('PUT')

                <!-- SMTP Configuration -->
                <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem;">
                    <h4 style="margin-bottom: 1rem; color: #333;">SMTP Server Settings</h4>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                        <div>
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">SMTP Host *</label>
                            <input type="text" name="smtp_host" value="{{ $settings['smtp_host']->value ?? '' }}"
                                   required placeholder="smtp.gmail.com"
                                   style="width: 100%; padding: 0.75rem; border: 1px solid #dee2e6; border-radius: 4px;">
                            <small style="color: #666;">Example: smtp.gmail.com, smtp.mailtrap.io</small>
                        </div>

                        <div>
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">SMTP Port *</label>
                            <input type="number" name="smtp_port" value="{{ $settings['smtp_port']->value ?? '587' }}"
                                   required min="1" max="65535" placeholder="587"
                                   style="width: 100%; padding: 0.75rem; border: 1px solid #dee2e6; border-radius: 4px;">
                            <small style="color: #666;">Common: 587 (TLS), 465 (SSL), 25</small>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                        <div>
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">SMTP Username *</label>
                            <input type="text" name="smtp_username" value="{{ $settings['smtp_username']->value ?? '' }}"
                                   required placeholder="your-email@example.com"
                                   style="width: 100%; padding: 0.75rem; border: 1px solid #dee2e6; border-radius: 4px;">
                        </div>

                        <div>
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">SMTP Password *</label>
                            <input type="password" name="smtp_password" value="{{ $settings['smtp_password']->value ?? '' }}"
                                   placeholder="••••••••"
                                   style="width: 100%; padding: 0.75rem; border: 1px solid #dee2e6; border-radius: 4px;">
                            <small style="color: #e74c3c; font-weight: 500;">⚠️ Gmail users: Use App Password, not regular password</small><br>
                            <small style="color: #666;">Leave blank to keep current password</small>
                        </div>
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Encryption *</label>
                        <select name="smtp_encryption" required style="width: 100%; padding: 0.75rem; border: 1px solid #dee2e6; border-radius: 4px;">
                            <option value="tls" {{ ($settings['smtp_encryption']->value ?? 'tls') == 'tls' ? 'selected' : '' }}>TLS (Recommended)</option>
                            <option value="ssl" {{ ($settings['smtp_encryption']->value ?? '') == 'ssl' ? 'selected' : '' }}>SSL</option>
                            <option value="none" {{ ($settings['smtp_encryption']->value ?? '') == 'none' ? 'selected' : '' }}>None</option>
                        </select>
                    </div>
                </div>

                <!-- Email Configuration -->
                <div style="background: #f0f4ff; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem;">
                    <h4 style="margin-bottom: 1rem; color: #333;">Email Information</h4>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                        <div>
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">From Email Address *</label>
                            <input type="email" name="from_address" value="{{ $settings['from_address']->value ?? '' }}"
                                   required placeholder="noreply@bixcash.com"
                                   style="width: 100%; padding: 0.75rem; border: 1px solid #dee2e6; border-radius: 4px;">
                            <small style="color: #666;">Email address shown in "From" field</small>
                        </div>

                        <div>
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">From Name *</label>
                            <input type="text" name="from_name" value="{{ $settings['from_name']->value ?? 'BixCash' }}"
                                   required placeholder="BixCash"
                                   style="width: 100%; padding: 0.75rem; border: 1px solid #dee2e6; border-radius: 4px;">
                            <small style="color: #666;">Name shown in "From" field</small>
                        </div>
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Admin Email Address *</label>
                        <input type="email" name="admin_email" value="{{ $settings['admin_email']->value ?? '' }}"
                               required placeholder="admin@bixcash.com"
                               style="width: 100%; padding: 0.75rem; border: 1px solid #dee2e6; border-radius: 4px;">
                        <small style="color: #666;">Email address to receive customer query notifications</small>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem;">
                        Save Email Settings
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="document.getElementById('testEmailForm').style.display = 'block';" style="padding: 0.75rem 2rem;">
                        Test Email Configuration
                    </button>
                </div>
            </form>

            <!-- Test Email Form (Hidden by default) -->
            <div id="testEmailForm" style="display: none; margin-top: 2rem; padding: 1.5rem; background: #fff3cd; border-radius: 8px; border-left: 4px solid #f39c12;">
                <h5 style="margin-bottom: 1rem;">Test Email Configuration</h5>
                <form method="POST" action="{{ route('admin.settings.email.test') }}">
                    @csrf
                    <div style="display: flex; gap: 1rem; align-items: end;">
                        <div style="flex: 1;">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Test Email Address</label>
                            <input type="email" name="test_email" required placeholder="test@example.com"
                                   style="width: 100%; padding: 0.75rem; border: 1px solid #dee2e6; border-radius: 4px;">
                        </div>
                        <button type="submit" class="btn btn-warning" style="padding: 0.75rem 1.5rem;">
                            Send Test Email
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="document.getElementById('testEmailForm').style.display = 'none';" style="padding: 0.75rem 1rem;">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div style="position: fixed; top: 20px; right: 20px; background: #27ae60; color: white; padding: 1rem 1.5rem; border-radius: 5px; box-shadow: 0 2px 8px rgba(0,0,0,0.2); z-index: 9999;" id="successMessage">
            {{ session('success') }}
        </div>
        <script>
            setTimeout(() => {
                const msg = document.getElementById('successMessage');
                if (msg) msg.remove();
            }, 5000);
        </script>
    @endif

    @if(session('error'))
        <div style="position: fixed; top: 20px; right: 20px; background: #e74c3c; color: white; padding: 1rem 1.5rem; border-radius: 5px; box-shadow: 0 2px 8px rgba(0,0,0,0.2); z-index: 9999;" id="errorMessage">
            {{ session('error') }}
        </div>
        <script>
            setTimeout(() => {
                const msg = document.getElementById('errorMessage');
                if (msg) msg.remove();
            }, 8000);
        </script>
    @endif

    @if($errors->any())
        <div style="position: fixed; top: 20px; right: 20px; background: #e74c3c; color: white; padding: 1rem 1.5rem; border-radius: 5px; box-shadow: 0 2px 8px rgba(0,0,0,0.2); z-index: 9999;" id="errorMessage">
            <strong>Validation Errors:</strong>
            <ul style="margin: 0.5rem 0 0 0; padding-left: 1.5rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <script>
            setTimeout(() => {
                const msg = document.getElementById('errorMessage');
                if (msg) msg.remove();
            }, 8000);
        </script>
    @endif
@endsection
