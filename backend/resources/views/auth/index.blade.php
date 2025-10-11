<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#021c47">
    <title>Authentication - BixCash</title>

    <!-- Stylesheets -->
    <link rel="stylesheet" href="/build/assets/app-Bx26URkf.css" />

    <!-- Auth Page Specific Styles -->
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            min-height: 100vh;
        }

        .auth-header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 2rem;
            box-sizing: border-box;
            background-color: var(--bix-white);
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .auth-header .logo {
            display: flex;
            align-items: center;
            text-decoration: none;
            gap: 0.5rem;
        }

        .auth-header .logo img {
            height: 60px;
        }

        .auth-header .tagline {
            font-size: 1.1rem;
            color: var(--bix-light-green);
            font-weight: 500;
        }

        .auth-header nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .auth-header nav a {
            color: var(--bix-medium-gray);
            text-decoration: none;
            font-weight: 500;
            font-size: 1rem;
            transition: color 0.3s ease;
        }

        .auth-header nav a.active,
        .auth-header nav a:hover {
            color: var(--bix-black);
        }

        .user-icon {
            width: 40px;
            height: 40px;
            background: var(--bix-light-gray-2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--bix-medium-gray);
        }

        /* Auth Form Container */
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 140px 2rem 2rem;
            box-sizing: border-box;
            position: relative;
        }

        .auth-form-wrapper {
            background: var(--bix-white);
            border-radius: 0;
            box-shadow: none;
            padding: 4rem 3rem;
            width: 100%;
            max-width: 450px;
            text-align: center;
            border: none;
            position: relative;
            overflow: hidden;
        }

        /* Form Container for Smooth Transitions */
        .forms-container {
            position: relative;
            width: 100%;
        }

        .auth-form {
            transition: opacity 0.4s ease, transform 0.4s ease;
            width: 100%;
        }

        .auth-form.hidden {
            opacity: 0;
            transform: translateX(30px);
            position: absolute;
            top: 0;
            left: 0;
            pointer-events: none;
        }

        .auth-form.visible {
            opacity: 1;
            transform: translateX(0);
            position: relative;
            pointer-events: all;
        }

        .auth-title {
            font-size: 1.5rem;
            font-weight: 400;
            color: var(--bix-dark-blue);
            margin-bottom: 0.5rem;
        }

        .auth-subtitle {
            font-size: 1.1rem;
            color: var(--bix-medium-gray);
            margin-bottom: 3rem;
            font-weight: 400;
        }

        /* Multi-step authentication specific styles */
        .step-container {
            min-height: 300px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .step-subtitle {
            font-size: 0.95rem;
            color: var(--bix-medium-gray);
            margin-bottom: 2rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .otp-input-group {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin: 2rem 0;
        }

        .otp-digit {
            width: 50px;
            height: 50px;
            text-align: center;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--bix-dark-blue);
            transition: border-color 0.3s ease;
            font-family: inherit;
        }

        .otp-digit:focus {
            outline: none;
            border-color: var(--bix-light-green);
            box-shadow: 0 0 0 3px rgba(147, 219, 77, 0.1);
        }

        .form-group {
            margin-bottom: 2rem;
            text-align: left;
        }

        .form-label {
            display: block;
            font-size: 0.9rem;
            color: var(--bix-medium-gray);
            margin-bottom: 0.8rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-input {
            width: 100%;
            padding: 1rem 1.5rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
            font-family: inherit;
            background: var(--bix-white);
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            box-sizing: border-box;
            color: var(--bix-dark-blue);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--bix-light-green);
            box-shadow: 0 0 0 3px rgba(147, 219, 77, 0.1);
        }

        .form-input::placeholder {
            color: #9ca3af;
        }

        /* Primary Action Button (Green) */
        .primary-btn {
            width: 100%;
            background: var(--bix-light-green);
            color: var(--bix-white);
            border: none;
            padding: 1rem 2rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2rem;
            font-family: inherit;
        }

        .primary-btn:hover {
            background: #85c441;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(147, 219, 77, 0.3);
        }

        /* Secondary Action Button (Dark Blue) */
        .secondary-btn {
            display: inline-block;
            background: var(--bix-dark-blue);
            color: var(--bix-white);
            padding: 0.8rem 2rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            cursor: pointer;
            border: none;
            font-family: inherit;
            width: 100%;
            box-sizing: border-box;
        }

        .secondary-btn:hover {
            background: #032a5a;
            transform: translateY(-1px);
        }

        .terms-text {
            font-size: 0.9rem;
            color: var(--bix-medium-gray);
            margin-bottom: 2rem;
            line-height: 1.5;
        }

        .terms-text a {
            color: var(--bix-light-green);
            text-decoration: none;
        }

        .terms-text a:hover {
            text-decoration: underline;
        }

        .resend-link {
            color: var(--bix-light-green);
            text-decoration: none;
            font-size: 0.9rem;
            margin: 1rem 0;
            display: inline-block;
        }

        .resend-link:hover {
            text-decoration: underline;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .auth-header {
                padding: 1rem 1.5rem;
            }

            .auth-header nav ul {
                gap: 1rem;
            }

            .auth-header nav a {
                font-size: 0.9rem;
            }

            .auth-container {
                padding: 120px 1rem 1rem;
            }

            .auth-form-wrapper {
                padding: 3rem 2rem;
            }

            .auth-title {
                font-size: 1.3rem;
            }

            .auth-subtitle, .step-subtitle {
                font-size: 1rem;
                margin-bottom: 2.5rem;
            }

            .otp-digit {
                width: 45px;
                height: 45px;
                font-size: 1.3rem;
            }

            .otp-input-group {
                gap: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="auth-header">
        <a href="/" class="logo">
            <img src="/images/logos/logos-01.png" alt="BixCash">
            <span class="tagline">Shop to Earn</span>
        </a>

        <nav>
            <ul>
                <li><a href="/" class="active">Home</a></li>
                <li><a href="/#brands">Brands</a></li>
                <li><a href="/#how-it-works">How It Works</a></li>
                <li><a href="/#partner">Partner with us</a></li>
                <li><a href="/#promotions">Promotions</a></li>
                <li><a href="/#contact">Contact Us</a></li>
            </ul>
        </nav>

        <div class="user-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
            </svg>
        </div>
    </header>

    <!-- Main Content -->
    <div class="auth-container">
        <div class="auth-form-wrapper">
            <div class="forms-container">
                <!-- Sign Up Form (Primary, matches mockup) -->
                <div class="auth-form visible" id="signup-form">
                    <h1 class="auth-title">New customer?</h1>
                    <p class="auth-subtitle">Please create your account first.</p>

                    <form action="#" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="signup-mobile" class="form-label">Enter Mobile Number</label>
                            <input
                                type="tel"
                                id="signup-mobile"
                                name="mobile"
                                class="form-input"
                                placeholder=""
                                required
                            >
                        </div>

                        <button type="submit" class="primary-btn">
                            Create Your Account
                        </button>

                        <p class="terms-text">
                            By clicking "Create Your Account", you agree to our
                            <a href="#">Terms & Conditions</a> and <a href="#">Privacy Policy</a>.
                        </p>

                        <button type="button" class="secondary-btn" onclick="switchToLogin()">
                            Have an account? Sign in
                        </button>
                    </form>
                </div>

                <!-- Sign In Form (Step 1: Mobile Number) -->
                <div class="auth-form hidden" id="login-form">
                    <div class="step-container">
                        <h1 class="auth-title">Welcome back.</h1>
                        <p class="auth-subtitle">Please sign in with your account.</p>

                        <form id="mobile-form" onsubmit="proceedToTPin(event)">
                            @csrf

                            <div class="form-group">
                                <label for="login-mobile" class="form-label">Mobile Number</label>
                                <input
                                    type="tel"
                                    id="login-mobile"
                                    name="mobile"
                                    class="form-input"
                                    placeholder=""
                                    required
                                >
                            </div>

                            <button type="submit" class="primary-btn">
                                Continue
                            </button>

                            <button type="button" class="secondary-btn" onclick="switchToSignup()">
                                Don't have an account? Sign up
                            </button>
                        </form>
                    </div>
                </div>

                <!-- T-Pin Entry Form (Step 2) -->
                <div class="auth-form hidden" id="tpin-form">
                    <div class="step-container">
                        <h1 class="auth-title">Enter your T-Pin</h1>
                        <p class="step-subtitle">Only the T-Pin will be required to login, next time</p>

                        <form id="tpin-entry-form" onsubmit="proceedToOTP(event)">
                            <div class="otp-input-group">
                                <input type="password" class="otp-digit" maxlength="1" oninput="moveToNext(this, 0)">
                                <input type="password" class="otp-digit" maxlength="1" oninput="moveToNext(this, 1)">
                                <input type="password" class="otp-digit" maxlength="1" oninput="moveToNext(this, 2)">
                                <input type="password" class="otp-digit" maxlength="1" oninput="moveToNext(this, 3)">
                            </div>

                            <a href="#" class="resend-link">Forgot T-Pin?</a>

                            <button type="submit" class="primary-btn">
                                Continue
                            </button>
                        </form>
                    </div>
                </div>

                <!-- OTP Verification Form (Step 3) -->
                <div class="auth-form hidden" id="otp-form">
                    <div class="step-container">
                        <h1 class="auth-title">Enter your OTP</h1>
                        <p class="step-subtitle">One time password (OTP) send on your number</p>

                        <form id="otp-entry-form" onsubmit="completeLogin(event)">
                            <div class="otp-input-group">
                                <input type="text" class="otp-digit" maxlength="1" oninput="moveToNextOTP(this, 0)">
                                <input type="text" class="otp-digit" maxlength="1" oninput="moveToNextOTP(this, 1)">
                                <input type="text" class="otp-digit" maxlength="1" oninput="moveToNextOTP(this, 2)">
                                <input type="text" class="otp-digit" maxlength="1" oninput="moveToNextOTP(this, 3)">
                            </div>

                            <a href="#" class="resend-link">Resend OTP</a>

                            <button type="submit" class="primary-btn">
                                Continue
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Form switching functions
        function switchToLogin() {
            hideAllForms();
            showForm('login-form');
            document.title = 'Sign In - BixCash';
        }

        function switchToSignup() {
            hideAllForms();
            showForm('signup-form');
            document.title = 'Sign Up - BixCash';
        }

        function proceedToTPin(event) {
            event.preventDefault();
            const mobile = document.getElementById('login-mobile').value;
            if (mobile) {
                hideAllForms();
                showForm('tpin-form');
                document.title = 'T-Pin - BixCash';
            }
        }

        function proceedToOTP(event) {
            event.preventDefault();
            const tpinInputs = document.querySelectorAll('#tpin-form .otp-digit');
            const tpin = Array.from(tpinInputs).map(input => input.value).join('');

            if (tpin.length === 4) {
                hideAllForms();
                showForm('otp-form');
                document.title = 'OTP Verification - BixCash';
            }
        }

        function completeLogin(event) {
            event.preventDefault();
            const otpInputs = document.querySelectorAll('#otp-form .otp-digit');
            const otp = Array.from(otpInputs).map(input => input.value).join('');

            if (otp.length === 4) {
                // Redirect to dashboard or home
                window.location.href = '/';
            }
        }

        // Helper functions
        function hideAllForms() {
            const forms = document.querySelectorAll('.auth-form');
            forms.forEach(form => {
                form.classList.remove('visible');
                form.classList.add('hidden');
            });
        }

        function showForm(formId) {
            setTimeout(() => {
                const form = document.getElementById(formId);
                form.classList.remove('hidden');
                form.classList.add('visible');
            }, 200);
        }

        // T-Pin input navigation
        function moveToNext(currentInput, currentIndex) {
            if (currentInput.value.length === 1) {
                const nextInput = document.querySelectorAll('#tpin-form .otp-digit')[currentIndex + 1];
                if (nextInput) {
                    nextInput.focus();
                }
            }
        }

        // OTP input navigation
        function moveToNextOTP(currentInput, currentIndex) {
            if (currentInput.value.length === 1) {
                const nextInput = document.querySelectorAll('#otp-form .otp-digit')[currentIndex + 1];
                if (nextInput) {
                    nextInput.focus();
                }
            }
        }

        // Handle backspace navigation for both T-Pin and OTP
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Backspace') {
                const activeElement = document.activeElement;
                if (activeElement.classList.contains('otp-digit') && activeElement.value === '') {
                    const allInputs = Array.from(document.querySelectorAll('.auth-form.visible .otp-digit'));
                    const currentIndex = allInputs.indexOf(activeElement);
                    if (currentIndex > 0) {
                        allInputs[currentIndex - 1].focus();
                    }
                }
            }
        });

        // Handle browser back/forward navigation
        window.addEventListener('popstate', function(event) {
            if (event.state && event.state.form) {
                if (event.state.form === 'login') {
                    switchToLogin();
                } else {
                    switchToSignup();
                }
            }
        });

        // Set initial state
        history.replaceState({form: 'signup'}, '', window.location.href);
    </script>
</body>
</html>