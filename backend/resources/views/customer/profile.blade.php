<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - BixCash</title>
    @vite(['resources/css/app.css'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --primary: #93db4d;
            --primary-dark: #7bc33a;
            --secondary: #021c47;
            --text-dark: #1a202c;
            --text-light: #718096;
            --border: #e2e8f0;
            --bg-light: #f7fafc;
        }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif; background: var(--bg-light); padding-bottom: 80px; }
        .header { background: var(--secondary); color: white; padding: 1.5rem 1rem; }
        .header-content { max-width: 1200px; margin: 0 auto; display: flex; align-items: center; gap: 1rem; }
        .back-btn { color: white; text-decoration: none; font-size: 1.5rem; }
        .header-title { font-size: 1.25rem; font-weight: 700; }
        .content { max-width: 800px; margin: 0 auto; padding: 1.5rem 1rem; }
        .section { background: white; border-radius: 16px; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .section-title { font-size: 1.125rem; font-weight: 700; margin-bottom: 1rem; color: var(--text-dark); }
        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-dark); }
        .form-input { width: 100%; padding: 0.75rem 1rem; border: 2px solid var(--border); border-radius: 12px; font-size: 1rem; }
        .form-input:focus { outline: none; border-color: var(--primary); }
        .btn { padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 600; border: none; cursor: pointer; transition: all 0.3s ease; }
        .btn-primary { background: var(--primary); color: white; width: 100%; }
        .btn-primary:hover { background: var(--primary-dark); }
        .bottom-nav { position: fixed; bottom: 0; left: 0; right: 0; background: white; border-top: 1px solid var(--border); padding: 0.75rem 0; }
        .nav-items { display: flex; justify-content: space-around; max-width: 600px; margin: 0 auto; }
        .nav-item { display: flex; flex-direction: column; align-items: center; gap: 0.25rem; color: var(--text-light); text-decoration: none; padding: 0.5rem 1rem; }
        .nav-item.active { color: var(--primary); }
        .nav-item svg { width: 24px; height: 24px; }
        .nav-item span { font-size: 0.7rem; font-weight: 600; }
    </style>
</head>
<body>

    <header class="header">
        <div class="header-content">
            <a href="{{ route('customer.dashboard') }}" class="back-btn">←</a>
            <h1 class="header-title">My Profile</h1>
        </div>
    </header>

    <main class="content">

        <!-- Personal Information -->
        <div class="section">
            <h2 class="section-title">Personal Information</h2>

            @if ($errors->any())
            <div style="background: #fee2e2; border-left: 4px solid #ef4444; padding: 1rem; margin-bottom: 1.5rem; border-radius: 8px;">
                <p style="font-size: 0.875rem; color: #991b1b; font-weight: 600; margin-bottom: 0.5rem;">Please fix the following errors:</p>
                <ul style="list-style: disc; padding-left: 1.25rem;">
                    @foreach ($errors->all() as $error)
                        <li style="font-size: 0.875rem; color: #991b1b;">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('customer.profile.update') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-input" required value="{{ old('name', $user->name) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input type="text" class="form-input" value="{{ $user->phone }}" disabled style="background: var(--bg-light);">
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" value="{{ old('email', $user->email) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Date of Birth</label>
                    <input type="date" name="date_of_birth" class="form-input" value="{{ old('date_of_birth', $profile && $profile->date_of_birth ? $profile->date_of_birth->format('Y-m-d') : '') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-input" value="{{ old('address', $profile->address ?? '') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">City</label>
                    <input type="text" name="city" class="form-input" value="{{ old('city', $profile->city ?? '') }}">
                </div>
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </form>
        </div>

        <!-- Bank Details -->
        <div class="section">
            <h2 class="section-title">Bank Details</h2>
            <p style="color: var(--text-light); font-size: 0.875rem; margin-bottom: 1rem;">Required for withdrawal requests</p>

            @if($profile && $profile->bank_name)
                <!-- Current Bank Details (Display Only) -->
                <div style="background: var(--bg-light); border-radius: 12px; padding: 1rem; margin-bottom: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                        <h3 style="font-size: 0.875rem; font-weight: 600; color: var(--text-dark);">Current Bank Account</h3>
                        <button type="button" onclick="toggleBankEdit()" class="btn" style="padding: 0.5rem 1rem; font-size: 0.875rem; background: var(--primary); color: white;">Change</button>
                    </div>
                    <div style="display: grid; gap: 0.5rem;">
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: var(--text-light); font-size: 0.875rem;">Bank:</span>
                            <span style="font-weight: 600;">{{ $profile->bank_name }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: var(--text-light); font-size: 0.875rem;">Account Title:</span>
                            <span style="font-weight: 600;">{{ $profile->account_title }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: var(--text-light); font-size: 0.875rem;">Account Number:</span>
                            <span style="font-weight: 600;">{{ maskAccountNumber($profile->account_number) }}</span>
                        </div>
                        @if($profile->iban)
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: var(--text-light); font-size: 0.875rem;">IBAN:</span>
                            <span style="font-weight: 600;">{{ maskIban($profile->iban) }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Edit Bank Details Form (Hidden by Default) -->
                <div id="bankEditForm" style="display: none;">
            @else
                <!-- No Bank Details Yet -->
                <div id="bankEditForm">
            @endif

                    <div style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 1rem; margin-bottom: 1.5rem; border-radius: 8px;">
                        <p style="font-size: 0.875rem; color: #92400e; margin-bottom: 0.5rem;">
                            <strong>⚠️ Security Notice:</strong>
                        </p>
                        <p style="font-size: 0.875rem; color: #92400e;">
                            Changing bank details requires OTP verification and will lock withdrawals for 24 hours for security reasons.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('customer.bank-details.request-otp') }}" id="bankDetailsForm">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Bank Name *</label>
                            <input type="text" name="bank_name" class="form-input" required placeholder="e.g., HBL, UBL, Meezan Bank">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Account Title *</label>
                            <input type="text" name="account_title" class="form-input" required placeholder="Account holder name">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Account Number *</label>
                            <input type="text" name="account_number" class="form-input" required placeholder="Your account number">
                        </div>
                        <div class="form-group">
                            <label class="form-label">IBAN (Optional)</label>
                            <input type="text" name="iban" class="form-input" placeholder="PK36XXXX0000001234567890">
                        </div>
                        <button type="submit" class="btn btn-primary">Request OTP Verification</button>
                        @if($profile && $profile->bank_name)
                        <button type="button" onclick="toggleBankEdit()" class="btn" style="width: 100%; margin-top: 0.5rem; background: transparent; color: var(--text-dark); border: 2px solid var(--border);">Cancel</button>
                        @endif
                    </form>
                </div>
        </div>

    </main>

    <nav class="bottom-nav">
        <div class="nav-items">
            <a href="{{ route('customer.dashboard') }}" class="nav-item">
                <svg fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                <span>Home</span>
            </a>
            <a href="{{ route('customer.wallet') }}" class="nav-item">
                <svg fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path></svg>
                <span>Wallet</span>
            </a>
            <a href="{{ route('customer.purchases') }}" class="nav-item">
                <svg fill="currentColor" viewBox="0 0 20 20"><path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path></svg>
                <span>Purchases</span>
            </a>
            <a href="{{ route('customer.profile') }}" class="nav-item active">
                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                <span>Profile</span>
            </a>
            <form method="POST" action="{{ route('customer.logout') }}" style="display: contents;" onsubmit="return confirm('Are you sure you want to logout?');">
                @csrf
                <button type="submit" class="nav-item" style="background: none; border: none; cursor: pointer; color: var(--text-light);">
                    <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"></path></svg>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </nav>

    @if(session('success'))
    <div style="position: fixed; top: 20px; right: 20px; background: #48bb78; color: white; padding: 1rem 1.5rem; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 2000;">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div style="position: fixed; top: 20px; right: 20px; background: #f56565; color: white; padding: 1rem 1.5rem; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 2000;">
        {{ session('error') }}
    </div>
    @endif

    <!-- OTP Verification Modal (Firebase Phone Auth) -->
    @if(session('show_otp_modal'))
    <div id="otpModal" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 3000; padding: 1rem;">
        <div style="background: white; border-radius: 20px; padding: 2rem; max-width: 500px; width: 100%; position: relative;">
            <!-- Close Button -->
            <button type="button" onclick="closeOtpModal()" style="position: absolute; top: 1rem; right: 1rem; background: transparent; border: none; font-size: 1.5rem; color: var(--text-light); cursor: pointer; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%; transition: all 0.3s;" onmouseover="this.style.background='var(--bg-light)'" onmouseout="this.style.background='transparent'">×</button>

            <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;">Verify Your Phone</h2>
            <p style="color: var(--text-light); margin-bottom: 1rem;" id="otpSubtitle">Enter the 6-digit OTP sent to {{ $user->phone }}</p>

            <div id="otpLoadingMessage" style="display: none; background: #e0f2fe; color: #0369a1; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; text-align: center;">
                <strong>Sending SMS...</strong>
                <div style="font-size: 0.875rem; margin-top: 0.5rem;">Please wait while we send the verification code to your phone.</div>
            </div>

            <div id="otpErrorMessage" style="display: none; background: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; text-align: center;">
                <strong>Error</strong>
                <div id="otpErrorText" style="font-size: 0.875rem; margin-top: 0.5rem;"></div>
            </div>

            <div id="otpInputSection">
                <div id="recaptcha-container" style="display: none;"></div>

                <div style="display: flex; gap: 0.75rem; justify-content: center; margin-bottom: 1.5rem;" id="otpInputs">
                    <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" autofocus style="width: 50px; height: 50px; text-align: center; font-size: 1.5rem; border: 2px solid var(--border); border-radius: 12px;">
                    <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" style="width: 50px; height: 50px; text-align: center; font-size: 1.5rem; border: 2px solid var(--border); border-radius: 12px;">
                    <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" style="width: 50px; height: 50px; text-align: center; font-size: 1.5rem; border: 2px solid var(--border); border-radius: 12px;">
                    <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" style="width: 50px; height: 50px; text-align: center; font-size: 1.5rem; border: 2px solid var(--border); border-radius: 12px;">
                    <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" style="width: 50px; height: 50px; text-align: center; font-size: 1.5rem; border: 2px solid var(--border); border-radius: 12px;">
                    <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" style="width: 50px; height: 50px; text-align: center; font-size: 1.5rem; border: 2px solid var(--border); border-radius: 12px;">
                </div>

                <button type="button" id="verifyOtpBtn" class="btn btn-primary" onclick="verifyFirebaseOtp()" disabled>Verify & Save Bank Details</button>
                <button type="button" onclick="closeOtpModal()" class="btn" style="width: 100%; margin-top: 0.75rem; background: transparent; color: var(--text-dark); border: 2px solid var(--border);">Cancel</button>
            </div>
        </div>
    </div>
    @endif

    <!-- Firebase SDK -->
    <script src="https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.7.1/firebase-auth-compat.js"></script>

    <script>
        // Firebase Configuration
        const firebaseConfig = {
            apiKey: "{{ config('firebase.web.api_key') }}",
            authDomain: "{{ config('firebase.web.auth_domain') }}",
            projectId: "{{ config('firebase.web.project_id') }}",
            storageBucket: "{{ config('firebase.web.storage_bucket') }}",
            messagingSenderId: "{{ config('firebase.web.messaging_sender_id') }}",
            appId: "{{ config('firebase.web.app_id') }}"
        };

        // Initialize Firebase
        firebase.initializeApp(firebaseConfig);
        const auth = firebase.auth();

        let confirmationResult = null;
        let recaptchaVerifier = null;

        function toggleBankEdit() {
            const form = document.getElementById('bankEditForm');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }

        function closeOtpModal() {
            window.location.href = '{{ route('customer.bank-details.cancel-otp') }}';
        }

        // Initialize Firebase OTP when modal is shown
        @if(session('show_otp_modal'))
        document.addEventListener('DOMContentLoaded', function() {
            initializeFirebaseOTP();
        });

        function initializeFirebaseOTP() {
            // Show loading message
            document.getElementById('otpLoadingMessage').style.display = 'block';
            document.getElementById('otpSubtitle').style.display = 'none';

            try {
                // Set up reCAPTCHA
                recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container', {
                    'size': 'invisible',
                    'callback': (response) => {
                        console.log('reCAPTCHA verified');
                    }
                });

                // Send SMS via Firebase
                const phoneNumber = '{{ $user->phone }}';
                auth.signInWithPhoneNumber(phoneNumber, recaptchaVerifier)
                    .then((result) => {
                        confirmationResult = result;
                        console.log('SMS sent successfully');

                        // Hide loading, show OTP inputs
                        document.getElementById('otpLoadingMessage').style.display = 'none';
                        document.getElementById('otpSubtitle').style.display = 'block';
                        document.querySelector('.otp-input').focus();
                    })
                    .catch((error) => {
                        console.error('Firebase SMS error:', error);
                        showOtpError(getFirebaseErrorMessage(error));
                    });
            } catch (error) {
                console.error('Error initializing Firebase:', error);
                showOtpError('Failed to initialize verification. Please refresh and try again.');
            }
        }

        function getFirebaseErrorMessage(error) {
            if (error.code === 'auth/invalid-phone-number') {
                return 'Invalid phone number format.';
            } else if (error.code === 'auth/too-many-requests') {
                return 'Too many requests. Please try again later.';
            } else if (error.code === 'auth/quota-exceeded') {
                return 'SMS quota exceeded. Please contact support.';
            }
            return 'Failed to send OTP. Please try again.';
        }

        function showOtpError(message) {
            document.getElementById('otpLoadingMessage').style.display = 'none';
            document.getElementById('otpErrorMessage').style.display = 'block';
            document.getElementById('otpErrorText').textContent = message;
        }

        async function verifyFirebaseOtp() {
            const otpInputs = document.querySelectorAll('.otp-input');
            const otp = Array.from(otpInputs).map(input => input.value).join('');

            if (otp.length !== 6) {
                showOtpError('Please enter all 6 digits');
                return;
            }

            const btn = document.getElementById('verifyOtpBtn');
            btn.disabled = true;
            btn.textContent = 'Verifying...';

            try {
                // Verify OTP with Firebase
                const userCredential = await confirmationResult.confirm(otp);
                console.log('Firebase OTP verified');

                // Get Firebase ID token
                const idToken = await userCredential.user.getIdToken();

                // Send to backend for verification and bank details update
                const response = await fetch('{{ route('customer.bank-details.verify-otp') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        firebase_token: idToken
                    }),
                    credentials: 'include'
                });

                const data = await response.json();

                if (data.success) {
                    alert('✅ ' + data.message);
                    window.location.href = '{{ route('customer.profile') }}';
                } else {
                    showOtpError(data.message || 'Verification failed');
                    btn.disabled = false;
                    btn.textContent = 'Verify & Save Bank Details';
                }
            } catch (error) {
                console.error('OTP verification error:', error);

                let errorMessage = 'Invalid OTP. Please try again.';
                if (error.code === 'auth/invalid-verification-code') {
                    errorMessage = 'Invalid OTP code. Please check and try again.';
                } else if (error.code === 'auth/code-expired') {
                    errorMessage = 'OTP has expired. Please request a new one.';
                }

                showOtpError(errorMessage);
                btn.disabled = false;
                btn.textContent = 'Verify & Save Bank Details';
            }
        }
        @endif

        // OTP Input Auto-focus
        document.querySelectorAll('.otp-input').forEach((input, index, inputs) => {
            input.addEventListener('input', function(e) {
                // Only allow numbers
                if (!/^\d*$/.test(this.value)) {
                    this.value = '';
                    return;
                }

                if (this.value && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }

                // Check if all inputs are filled
                const allFilled = Array.from(inputs).every(inp => inp.value.length > 0);
                document.getElementById('verifyOtpBtn').disabled = !allFilled;
            });

            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && !this.value && index > 0) {
                    inputs[index - 1].focus();
                }
            });

            // Paste support
            input.addEventListener('paste', function(e) {
                e.preventDefault();
                const pastedData = e.clipboardData.getData('text');
                const digits = pastedData.replace(/\D/g, '').slice(0, 6);

                digits.split('').forEach((digit, i) => {
                    if (inputs[i]) {
                        inputs[i].value = digit;
                    }
                });

                const allFilled = Array.from(inputs).every(inp => inp.value.length > 0);
                document.getElementById('verifyOtpBtn').disabled = !allFilled;
            });
        });

        // Auto-hide messages
        setTimeout(() => {
            const messages = document.querySelectorAll('[style*="position: fixed"]');
            messages.forEach(msg => {
                if (msg.textContent.includes('success') || msg.textContent.includes('error')) {
                    msg.style.animation = 'fadeOut 0.3s ease';
                    setTimeout(() => msg.remove(), 300);
                }
            });
        }, 3000);
    </script>

    <style>
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }
    </style>

</body>
</html>
