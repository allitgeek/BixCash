<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BixCash - Firebase Phone Authentication Demo</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 40px;
            max-width: 450px;
            width: 100%;
        }
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo h1 {
            color: #667eea;
            font-size: 32px;
            font-weight: 700;
        }
        .logo p {
            color: #666;
            font-size: 14px;
            margin-top: 5px;
        }
        .auth-step {
            display: none;
        }
        .auth-step.active {
            display: block;
            animation: fadeIn 0.3s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 14px;
        }
        input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e1e8ed;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s;
        }
        input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .btn {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .btn-primary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        .btn-secondary {
            background: #f7f9fc;
            color: #667eea;
        }
        .btn-secondary:hover:not(:disabled) {
            background: #e1e8ed;
        }
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        .message {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            display: none;
        }
        .message.show {
            display: block;
            animation: slideDown 0.3s ease;
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .message.info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        .loader {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .help-text {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        .user-info {
            background: #f7f9fc;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }
        .user-info h3 {
            color: #333;
            margin-bottom: 15px;
        }
        .user-info p {
            color: #666;
            margin-bottom: 8px;
            font-size: 14px;
        }
        .user-info strong {
            color: #333;
        }
        #recaptcha-container {
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <h1>BixCash</h1>
            <p>Firebase Phone Authentication</p>
        </div>

        <div id="message" class="message"></div>

        <!-- Step 1: Enter Phone Number -->
        <div id="step-phone" class="auth-step active">
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input
                    type="tel"
                    id="phone"
                    placeholder="+923001234567"
                    maxlength="13"
                >
                <div class="help-text">Enter your Pakistan mobile number with +92</div>
            </div>
            <button id="send-otp-button" class="btn btn-primary" onclick="sendOTP()">
                Send OTP
            </button>
        </div>

        <!-- Step 2: Enter OTP -->
        <div id="step-otp" class="auth-step">
            <div class="form-group">
                <label for="otp">Enter OTP Code</label>
                <input
                    type="text"
                    id="otp"
                    placeholder="123456"
                    maxlength="6"
                    inputmode="numeric"
                >
                <div class="help-text">Enter the 6-digit code sent to your phone</div>
            </div>
            <button class="btn btn-primary" onclick="verifyOTP()">
                Verify & Login
            </button>
            <button class="btn btn-secondary" onclick="backToPhone()">
                Change Phone Number
            </button>
        </div>

        <!-- Step 3: Authenticated -->
        <div id="step-authenticated" class="auth-step">
            <div class="user-info" id="user-info">
                <!-- User data will be displayed here -->
            </div>
            <button class="btn btn-primary" onclick="logout()">
                Logout
            </button>
        </div>

        <div id="recaptcha-container"></div>
    </div>

    <!-- Firebase SDK (version 10.x - compatibility mode) -->
    <script src="https://www.gstatic.com/firebasejs/10.13.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.13.0/firebase-auth-compat.js"></script>

    <!-- Our Firebase Auth Module -->
    <script src="/js/firebase-auth.js"></script>

    <script>
        // Firebase Configuration - This will be populated from backend
        const firebaseConfig = {
            apiKey: "{{ config('firebase.web.api_key') }}",
            authDomain: "{{ config('firebase.web.auth_domain') }}",
            projectId: "{{ config('firebase.web.project_id') }}",
            storageBucket: "{{ config('firebase.web.storage_bucket') }}",
            messagingSenderId: "{{ config('firebase.web.messaging_sender_id') }}",
            appId: "{{ config('firebase.web.app_id') }}"
        };

        // Initialize Firebase Phone Auth
        let phoneAuth;

        // Initialize on page load
        window.addEventListener('DOMContentLoaded', async () => {
            phoneAuth = new FirebasePhoneAuth(firebaseConfig);
            const initResult = await phoneAuth.initialize();

            if (!initResult.success) {
                showMessage('Failed to initialize. Please refresh the page.', 'error');
            }

            // Set up reCAPTCHA
            phoneAuth.setupRecaptcha('send-otp-button');

            // Check if already authenticated
            if (phoneAuth.isAuthenticated()) {
                showAuthenticated();
            }
        });

        function sendOTP() {
            const phoneInput = document.getElementById('phone');
            const phone = phoneInput.value.trim();
            const button = document.getElementById('send-otp-button');

            if (!phone) {
                showMessage('Please enter your phone number', 'error');
                return;
            }

            button.disabled = true;
            button.innerHTML = '<span class="loader"></span> Sending...';

            phoneAuth.sendOTP(phone)
                .then(result => {
                    if (result.success) {
                        showMessage(result.message, 'success');
                        showStep('step-otp');
                    } else {
                        showMessage(result.message, 'error');
                    }
                })
                .catch(error => {
                    showMessage('An error occurred. Please try again.', 'error');
                })
                .finally(() => {
                    button.disabled = false;
                    button.innerHTML = 'Send OTP';
                });
        }

        function verifyOTP() {
            const otpInput = document.getElementById('otp');
            const otp = otpInput.value.trim();

            if (!otp || otp.length !== 6) {
                showMessage('Please enter a valid 6-digit OTP', 'error');
                return;
            }

            const button = event.target;
            button.disabled = true;
            button.innerHTML = '<span class="loader"></span> Verifying...';

            phoneAuth.verifyOTP(otp)
                .then(async result => {
                    if (result.success) {
                        showMessage('OTP verified! Logging you in...', 'info');

                        // Login with Firebase token
                        const loginResult = await phoneAuth.loginWithFirebaseToken(result.idToken);

                        if (loginResult.success) {
                            showMessage('Login successful!', 'success');
                            displayUserInfo(loginResult.data);
                            showStep('step-authenticated');
                        } else {
                            showMessage(loginResult.message, 'error');
                            button.disabled = false;
                            button.innerHTML = 'Verify & Login';
                        }
                    } else {
                        showMessage(result.message, 'error');
                        button.disabled = false;
                        button.innerHTML = 'Verify & Login';
                    }
                })
                .catch(error => {
                    showMessage('Verification failed. Please try again.', 'error');
                    button.disabled = false;
                    button.innerHTML = 'Verify & Login';
                });
        }

        function backToPhone() {
            document.getElementById('otp').value = '';
            showStep('step-phone');
            showMessage('', '');
        }

        function logout() {
            phoneAuth.logout().then(() => {
                showMessage('Logged out successfully', 'success');
                document.getElementById('phone').value = '';
                document.getElementById('otp').value = '';
                showStep('step-phone');
            });
        }

        function showStep(stepId) {
            document.querySelectorAll('.auth-step').forEach(step => {
                step.classList.remove('active');
            });
            document.getElementById(stepId).classList.add('active');
        }

        function showMessage(message, type) {
            const messageEl = document.getElementById('message');
            if (!message) {
                messageEl.classList.remove('show');
                return;
            }

            messageEl.className = `message ${type} show`;
            messageEl.textContent = message;
        }

        function displayUserInfo(data) {
            const userInfoEl = document.getElementById('user-info');
            const user = data.user;

            userInfoEl.innerHTML = `
                <h3>Welcome!</h3>
                <p><strong>Phone:</strong> ${user.phone}</p>
                <p><strong>Name:</strong> ${user.name}</p>
                ${user.email ? `<p><strong>Email:</strong> ${user.email}</p>` : ''}
                <p><strong>Phone Verified:</strong> ${user.phone_verified ? 'Yes' : 'No'}</p>
                <p><strong>New User:</strong> ${data.is_new_user ? 'Yes' : 'No'}</p>
                <p><strong>Auth Method:</strong> ${data.auth_method}</p>
            `;
        }

        function showAuthenticated() {
            const user = JSON.parse(localStorage.getItem('user') || '{}');
            displayUserInfo({ user, is_new_user: false, auth_method: 'firebase_phone' });
            showStep('step-authenticated');
        }
    </script>
</body>
</html>
