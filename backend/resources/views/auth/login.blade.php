<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#021c47">
    <title>BixCash - Shop to Earn</title>

    <!-- Stylesheets -->
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- Auth Page Specific Styles -->
    <style>
        /* Auth Page Layout Override */
        body {
            background: #f5f5f5 !important;
            min-height: 100vh;
        }

        /* Auth-specific header styles removed - using main website header */

        /* Main Content - adjusted for fixed header from main CSS */
        .main-content {
            min-height: calc(100vh - 120px);
            display: flex;
            flex-direction: column;
            padding-top: 0;
        }

        /* Auth Form Container */
        .auth-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            box-sizing: border-box;
        }

        .auth-form-wrapper {
            background: transparent;
            border-radius: 0;
            box-shadow: none;
            padding: 2rem 1rem;
            width: 100%;
            max-width: 450px;
            text-align: center;
            border: none;
        }

        /* Steps */
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

        .auth-title {
            font-size: 1.75rem;
            font-weight: 400;
            color: var(--bix-dark-blue);
            margin-bottom: 0.5rem;
        }

        .auth-subtitle {
            font-size: 1rem;
            color: var(--bix-medium-gray);
            margin-bottom: 3rem;
            font-weight: 400;
            line-height: 1.5;
        }

        /* Phone Input */
        .phone-input-group {
            margin-bottom: 3rem;
            text-align: center;
        }

        .phone-label {
            display: block;
            font-size: 0.9rem;
            color: var(--bix-medium-gray);
            margin-bottom: 1.5rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .phone-input-wrapper {
            position: relative;
            background: var(--bix-white);
            border: 1px solid #e1e5e9;
            border-radius: 8px;
            max-width: 500px;
            width: 90%;
            margin: 0 auto;
            padding: 0;
        }

        .phone-prefix {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            font-weight: 500;
            color: var(--bix-dark-blue);
            white-space: nowrap;
            pointer-events: none;
            z-index: 2;
        }

        .phone-input {
            width: 100%;
            padding: 1rem 1rem 1rem 3.5rem;
            border: none;
            font-size: 1rem;
            font-family: inherit;
            background: transparent;
            color: var(--bix-dark-blue);
            outline: none;
            box-sizing: border-box;
        }

        .phone-input::placeholder {
            color: #9ca3af;
        }

        .phone-input-wrapper:focus-within {
            border-color: var(--bix-light-green);
            box-shadow: 0 0 0 1px rgba(147, 219, 77, 0.2);
        }

        /* PIN/OTP Input */
        .pin-input-group {
            margin-bottom: 3rem;
            text-align: center;
        }

        .pin-inputs {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-bottom: 2rem;
        }

        .pin-input {
            width: 50px;
            height: 50px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            text-align: center;
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--bix-dark-blue);
            background: var(--bix-white);
            outline: none;
            transition: all 0.3s ease;
        }

        .pin-input:focus {
            border-color: var(--bix-light-green);
            box-shadow: 0 0 0 3px rgba(147, 219, 77, 0.1);
        }

        .pin-input.filled {
            border-color: var(--bix-light-green);
            background: rgba(147, 219, 77, 0.05);
        }

        /* Buttons */
        .continue-btn {
            width: 90%;
            max-width: 500px;
            background: #93db4d !important;
            color: #ffffff !important;
            border: none;
            padding: 1rem 2rem;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 2rem;
        }

        .continue-btn:hover {
            background: #85c441 !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(147, 219, 77, 0.3);
        }

        .continue-btn:disabled {
            background: #d1d5db !important;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .secondary-btn, .signup-btn {
            display: inline-block;
            background: #93db4d !important;
            color: #ffffff !important;
            padding: 1rem 2rem;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 1rem;
            border: none;
            cursor: pointer;
            max-width: 500px;
            width: 90%;
        }

        .secondary-btn:hover, .signup-btn:hover {
            background: #85c441 !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(147, 219, 77, 0.3);
        }

        .signin-btn {
            display: inline-block;
            background: var(--bix-dark-blue);
            color: var(--bix-white);
            padding: 0.8rem 2rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            border: none;
            cursor: pointer;
        }

        .signin-btn:hover {
            background: #032a5a;
            transform: translateY(-1px);
        }

        /* Helper Links */
        .helper-link {
            color: var(--bix-light-green);
            text-decoration: none;
            font-size: 0.9rem;
            margin: 1rem 0;
            display: inline-block;
        }

        .helper-link:hover {
            text-decoration: underline;
        }

        /* Timer */
        .timer-text {
            font-size: 0.9rem;
            color: var(--bix-medium-gray);
            margin: 1rem 0;
        }

        /* Footer styles removed - using main website footer */

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .auth-container {
                padding: 1rem;
            }

            .auth-form-wrapper {
                padding: 1rem;
            }

            .auth-title {
                font-size: 1.5rem;
            }

            .auth-subtitle {
                font-size: 0.95rem;
                margin-bottom: 2.5rem;
            }

            .pin-inputs {
                gap: 0.8rem;
            }

            .pin-input {
                width: 45px;
                height: 45px;
                font-size: 1.1rem;
            }

            .secondary-btn, .signup-btn {
                font-size: 0.85rem;
                padding: 0.9rem 1.5rem;
                white-space: nowrap;
            }

            .continue-btn {
                font-size: 0.85rem;
                padding: 0.9rem 1.5rem;
            }
        }

        @media (max-width: 380px) {
            .secondary-btn, .signup-btn {
                font-size: 0.78rem;
                padding: 0.85rem 1.2rem;
            }

            .continue-btn {
                font-size: 0.78rem;
                padding: 0.85rem 1.2rem;
            }
        }

        /* Mobile Bottom Navigation */
        .mobile-bottom-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            padding: 8px 0;
            padding-bottom: calc(8px + env(safe-area-inset-bottom));
        }

        @media (max-width: 768px) {
            .mobile-bottom-nav {
                display: flex;
                justify-content: space-around;
                align-items: center;
            }
            body {
                padding-bottom: 70px !important;
            }
        }

        .bottom-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #666;
            font-size: 10px;
            padding: 4px 12px;
            transition: color 0.2s ease;
            min-width: 50px;
        }

        .bottom-nav-item i {
            font-size: 20px;
            margin-bottom: 4px;
        }

        .bottom-nav-item span {
            white-space: nowrap;
        }

        .bottom-nav-item.active,
        .bottom-nav-item:hover {
            color: var(--bix-dark-blue, #1a365d) !important;
        }

        .bottom-nav-item.active i,
        .bottom-nav-item.active svg {
            color: var(--bix-light-green, #76d37a) !important;
            transform: scale(1.1);
            transition: transform 0.15s ease, color 0.15s ease;
        }

        .bottom-nav-item.active span {
            color: var(--bix-light-green, #76d37a) !important;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="main-header">
        <a href="/" class="logo">
            <img src="/images/logos/logos-01.png" alt="BixCash Logo">
        </a>

        <!-- Desktop Navigation -->
        <nav class="desktop-nav">
            <ul>
                <li><a href="/#home">Home</a></li>
                <li><a href="/#brands">Brands</a></li>
                <li><a href="/#how-it-works">How It Works</a></li>
                <li><a href="/#partner">Partner with us</a></li>
                <li><a href="/#promotions">Promotions</a></li>
                <li><a href="/#contact">Contact Us</a></li>
            </ul>
        </nav>

        <!-- Mobile Menu Button -->
        <button class="mobile-menu-btn" id="mobile-menu-btn" aria-label="Toggle mobile menu">
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
        </button>
    </header>

    <!-- Mobile Navigation Overlay -->
    <div class="mobile-nav-overlay" id="mobile-nav-overlay">
        <div class="mobile-nav-content">
            <div class="mobile-nav-header">
                <img src="/images/logos/logos-01.png" alt="BixCash Logo" class="mobile-nav-logo">
                <button class="mobile-nav-close" id="mobile-nav-close" aria-label="Close mobile menu">
                    <span>&times;</span>
                </button>
            </div>
            <nav class="mobile-nav">
                <ul>
                    <li><a href="/#home" class="mobile-nav-link">Home</a></li>
                    <li><a href="/#brands" class="mobile-nav-link">Brands</a></li>
                    <li><a href="/#how-it-works" class="mobile-nav-link">How It Works</a></li>
                    <li><a href="/#partner" class="mobile-nav-link">Partner with us</a></li>
                    <li><a href="/#promotions" class="mobile-nav-link">Promotions</a></li>
                    <li><a href="/#contact" class="mobile-nav-link">Contact Us</a></li>
                </ul>
                <div class="mobile-nav-auth">
                    @auth
                        <a href="{{ route('customer.dashboard') }}" class="mobile-auth-btn">
                            <svg fill="currentColor" viewBox="0 0 20 20" style="width: 16px; height: 16px; display: inline-block; vertical-align: middle; margin-right: 0.5rem;"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                            {{ Auth::user()->name }}
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="mobile-auth-btn">Sign In</a>
                    @endauth
                </div>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="auth-container">
            <div class="auth-form-wrapper">

                <!-- Step 1: Mobile Number Input -->
                <div class="auth-step active" id="step-mobile">
                    <h1 class="auth-title">Welcome back.</h1>
                    <p class="auth-subtitle">Please sign in with your account.</p>

                    <div class="phone-input-group">
                        <label class="phone-label">Mobile Number</label>
                        <div class="phone-input-wrapper">
                            <div class="phone-prefix">+92</div>
                            <input
                                type="tel"
                                id="mobile-input"
                                class="phone-input"
                                placeholder="3xxxxxxxxx"
                                maxlength="10"
                            >
                        </div>
                    </div>

                    <button type="button" class="continue-btn" id="mobile-continue">
                        Continue
                    </button>

                    <button type="button" class="signup-btn" id="signup-link">
                        Don't have an account? Sign up
                    </button>
                </div>

                <!-- Step 2A: T-Pin Input (Existing Users) -->
                <div class="auth-step" id="step-tpin">
                    <h1 class="auth-title">Enter your T-Pin</h1>
                    <p class="auth-subtitle">Only the T-Pin will be required to login, next time</p>

                    <div class="pin-input-group">
                        <div class="pin-inputs" id="tpin-inputs">
                            <input type="tel" class="pin-input" maxlength="1" data-index="0">
                            <input type="tel" class="pin-input" maxlength="1" data-index="1">
                            <input type="tel" class="pin-input" maxlength="1" data-index="2">
                            <input type="tel" class="pin-input" maxlength="1" data-index="3">
                        </div>
                        <a href="#" class="helper-link">Forgot T-Pin?</a>
                    </div>

                    <button type="button" class="continue-btn" id="tpin-continue" disabled>
                        Continue
                    </button>
                </div>

                <!-- Step 2B: OTP Verification (New Users) -->
                <div class="auth-step" id="step-otp">
                    <h1 class="auth-title">Enter your OTP</h1>
                    <p class="auth-subtitle">One time password (OTP) send on your number</p>

                    <div class="pin-input-group">
                        <div class="pin-inputs" id="otp-inputs">
                            <input type="tel" class="pin-input" maxlength="1" data-index="0">
                            <input type="tel" class="pin-input" maxlength="1" data-index="1">
                            <input type="tel" class="pin-input" maxlength="1" data-index="2">
                            <input type="tel" class="pin-input" maxlength="1" data-index="3">
                            <input type="tel" class="pin-input" maxlength="1" data-index="4">
                            <input type="tel" class="pin-input" maxlength="1" data-index="5">
                        </div>
                        <div class="timer-text" id="otp-timer">Resend OTP in <span id="timer-count">60</span>s</div>
                        <a href="#" class="helper-link" id="resend-otp" style="display: none;">Resend OTP</a>
                    </div>

                    <button type="button" class="continue-btn" id="otp-continue" disabled>
                        Continue
                    </button>
                </div>

                <!-- Step 3: Set T-Pin (New Users) -->
                <div class="auth-step" id="step-set-tpin">
                    <h1 class="auth-title">New customer?</h1>
                    <p class="auth-subtitle">Please create your account first.</p>

                    <div class="phone-input-group">
                        <label class="phone-label">Enter Mobile Number</label>
                        <div class="phone-input-wrapper">
                            <div class="phone-prefix">+92</div>
                            <input
                                type="tel"
                                id="signup-mobile-input"
                                class="phone-input"
                                placeholder="3xxxxxxxxx"
                                maxlength="10"
                            >
                        </div>
                    </div>

                    <button type="button" class="continue-btn" id="create-account-btn" disabled>
                        Create Your Account
                    </button>

                    <p class="auth-subtitle" style="font-size: 0.85rem; margin-top: 1rem;">
                        By clicking "Create Your Account", you agree to our
                        <a href="#" class="helper-link">Terms & Conditions</a> and <a href="#" class="helper-link">Privacy Policy</a>.
                    </p>

                    <button type="button" class="secondary-btn" id="signin-link">
                        Have an account? Sign in
                    </button>
                </div>

                <!-- Step 4: Reset T-Pin (Forgot T-Pin Flow) -->
                <div class="auth-step" id="step-reset-tpin">
                    <h1 class="auth-title">Set New T-Pin</h1>
                    <p class="auth-subtitle">Please create a new 4-digit T-Pin for your account</p>

                    <div class="pin-input-group">
                        <div class="pin-inputs" id="new-tpin-inputs">
                            <input type="tel" class="pin-input" maxlength="1" data-index="0">
                            <input type="tel" class="pin-input" maxlength="1" data-index="1">
                            <input type="tel" class="pin-input" maxlength="1" data-index="2">
                            <input type="tel" class="pin-input" maxlength="1" data-index="3">
                        </div>
                        <p class="auth-subtitle" style="font-size: 0.85rem; margin-top: 1rem;">
                            Remember this T-Pin - you'll use it for future logins
                        </p>
                    </div>

                    <button type="button" class="continue-btn" id="save-new-tpin" disabled>
                        Save New T-Pin
                    </button>
                </div>

                <!-- Success Step -->
                <div class="auth-step" id="step-success">
                    <h1 class="auth-title">Welcome to BixCash!</h1>
                    <p class="auth-subtitle">Your account has been successfully verified.</p>

                    <button type="button" class="continue-btn" onclick="window.location.href='/customer/dashboard'">
                        Continue to Dashboard
                    </button>
                </div>

            </div>
        </div>

        <!-- Footer -->
        <footer class="footer-section">
            <div class="footer-container">
                <div class="footer-content">
                    <!-- Left Side - Logo and Social Media -->
                    <div class="footer-brand">
                        <div class="footer-logo">
                            <img src="/images/logos/logos-01.png" alt="BixCash Logo" class="footer-logo-img">
                        </div>
                        <div class="footer-social">
                            <a href="#" class="social-link linkedin">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                </svg>
                            </a>
                            <a href="#" class="social-link instagram">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                            </a>
                            <a href="#" class="social-link twitter">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                </svg>
                            </a>
                            <a href="#" class="social-link youtube">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                </svg>
                            </a>
                            <a href="#" class="social-link tiktok">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Footer Links -->
                    <div class="footer-links">
                        <!-- About Us Column -->
                        <div class="footer-column">
                            <h3 class="footer-column-title">About Us</h3>
                            <ul class="footer-menu">
                                <li><a href="/#home" class="footer-link">Home</a></li>
                                <li><a href="/#partner" class="footer-link">Partner with us</a></li>
                                <li><a href="/#promotions" class="footer-link">Promotions</a></li>
                            </ul>
                        </div>

                        <!-- Brands Column -->
                        <div class="footer-column">
                            <h3 class="footer-column-title">Brands</h3>
                            <ul class="footer-menu">
                                <li><a href="/#brands" class="footer-link">Clothing</a></li>
                                <li><a href="/#brands" class="footer-link">Home Appliances</a></li>
                                <li><a href="/#brands" class="footer-link">Entertainment</a></li>
                                <li><a href="/#brands" class="footer-link">Food</a></li>
                            </ul>
                        </div>

                        <!-- Contact Us Column -->
                        <div class="footer-column">
                            <h3 class="footer-column-title">Contact Us</h3>
                            <div class="footer-contact">
                                <p class="contact-item">021 111 222 333</p>
                                <p class="contact-item">info@bixcash.com</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Firebase SDK -->
    <script src="https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.7.1/firebase-auth-compat.js"></script>

    <!-- JavaScript -->
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

        // Auth System State Management with Firebase Phone Auth
        class BixCashAuth {
            constructor() {
                this.currentStep = 'mobile';
                this.userPhone = '';
                this.authToken = null;
                this.otpTimer = null;
                this.otpCountdown = 60;
                this.isResettingTpin = false;
                this.verifiedOtp = null;
                this.apiBaseUrl = '/api/customer/auth';
                this.confirmationResult = null; // Firebase confirmation result
                this.recaptchaVerifier = null;
                this.useFirebaseSMS = true; // Flag to use Firebase SMS instead of database OTP
                this.useBackendOTP = false; // Flag for Ufone bypass - use backend OTP instead of Firebase
                this.isSettingInitialPin = false; // Flag for initial PIN setup vs reset

                this.initFirebase();
                this.initEventListeners();
                this.initPinInputs();
                this.initButtonStates();
            }

            initFirebase() {
                // Set up reCAPTCHA for Firebase Phone Auth
                try {
                    this.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('mobile-continue', {
                        'size': 'invisible',
                        'callback': (response) => {
                            console.log('reCAPTCHA verified');
                        },
                        'expired-callback': () => {
                            console.log('reCAPTCHA expired');
                            this.recaptchaVerifier.render();
                        }
                    });
                } catch (error) {
                    console.error('Error initializing reCAPTCHA:', error);
                }
            }

            /**
             * Check if phone number is Ufone (92333-92339)
             * Temporary bypass for Ufone network SMS blocking issue
             */
            isUfoneNumber(phone) {
                const ufoneRanges = ['+92333', '+92334', '+92335', '+92336', '+92337', '+92338', '+92339'];
                return ufoneRanges.some(range => phone.startsWith(range));
            }

            initEventListeners() {
                // Mobile input validation
                const mobileInput = document.getElementById('mobile-input');
                const signupMobileInput = document.getElementById('signup-mobile-input');

                [mobileInput, signupMobileInput].forEach(input => {
                    if (input) {
                        input.addEventListener('input', (e) => this.handlePhoneInput(e));
                        input.addEventListener('keypress', (e) => this.handlePhoneKeypress(e));
                    }
                });

                // Button click handlers
                document.getElementById('mobile-continue').addEventListener('click', () => this.handleMobileContinue());
                document.getElementById('tpin-continue').addEventListener('click', () => this.handleTpinContinue());
                document.getElementById('otp-continue').addEventListener('click', () => this.handleOtpContinue());
                document.getElementById('create-account-btn').addEventListener('click', () => this.handleCreateAccount());
                document.getElementById('save-new-tpin').addEventListener('click', () => this.handleSaveNewTpin());

                // Navigation links
                document.getElementById('signup-link').addEventListener('click', (e) => {
                    e.preventDefault();
                    this.showStep('set-tpin');
                });

                document.getElementById('signin-link').addEventListener('click', (e) => {
                    e.preventDefault();
                    this.showStep('mobile');
                });

                // Resend OTP
                document.getElementById('resend-otp').addEventListener('click', async (e) => {
                    e.preventDefault();
                    await this.resendOtp();
                });

                // Forgot T-Pin link
                document.querySelector('.helper-link').addEventListener('click', (e) => {
                    e.preventDefault();
                    this.handleForgotTpin();
                });
            }

            initPinInputs() {
                // T-Pin inputs
                const tpinInputs = document.querySelectorAll('#tpin-inputs .pin-input');
                this.setupPinInputBehavior(tpinInputs, 'tpin-continue');

                // OTP inputs
                const otpInputs = document.querySelectorAll('#otp-inputs .pin-input');
                this.setupPinInputBehavior(otpInputs, 'otp-continue');

                // New T-Pin inputs (for reset)
                const newTpinInputs = document.querySelectorAll('#new-tpin-inputs .pin-input');
                this.setupPinInputBehavior(newTpinInputs, 'save-new-tpin');
            }

            initButtonStates() {
                // Set buttons to match mockup - Continue button should be active (green) by default
                document.getElementById('mobile-continue').disabled = false;
                const createBtn = document.getElementById('create-account-btn');
                if (createBtn) {
                    createBtn.disabled = true;
                }
            }

            setupPinInputBehavior(inputs, buttonId) {
                inputs.forEach((input, index) => {
                    input.addEventListener('input', (e) => {
                        const value = e.target.value;

                        // Only allow numbers
                        if (!/^\d*$/.test(value)) {
                            e.target.value = '';
                            return;
                        }

                        // Auto-focus next input
                        if (value && index < inputs.length - 1) {
                            inputs[index + 1].focus();
                        }

                        // Update visual state
                        e.target.classList.toggle('filled', value.length > 0);

                        // Check if all inputs are filled
                        const allFilled = Array.from(inputs).every(inp => inp.value.length > 0);
                        document.getElementById(buttonId).disabled = !allFilled;
                    });

                    input.addEventListener('keydown', (e) => {
                        // Handle backspace
                        if (e.key === 'Backspace' && !e.target.value && index > 0) {
                            inputs[index - 1].focus();
                        }
                    });

                    input.addEventListener('paste', (e) => {
                        e.preventDefault();
                        const pastedData = e.clipboardData.getData('text');
                        const digits = pastedData.replace(/\D/g, '').slice(0, inputs.length);

                        digits.split('').forEach((digit, i) => {
                            if (inputs[i]) {
                                inputs[i].value = digit;
                                inputs[i].classList.add('filled');
                            }
                        });

                        // Check if all inputs are filled
                        const allFilled = Array.from(inputs).every(inp => inp.value.length > 0);
                        document.getElementById(buttonId).disabled = !allFilled;
                    });
                });
            }

            handlePhoneInput(e) {
                let value = e.target.value.replace(/\D/g, ''); // Remove non-digits

                // Limit to 10 digits and must start with 3
                if (value.length > 0 && !value.startsWith('3')) {
                    value = '';
                }

                if (value.length > 10) {
                    value = value.slice(0, 10);
                }

                e.target.value = value;

                // For mobile-input, keep continue button always enabled to match mockup
                // For signup-mobile-input, enable create-account-btn only with valid input
                if (e.target.id === 'signup-mobile-input') {
                    const isValid = value.length === 10 && value.startsWith('3');
                    const button = document.getElementById('create-account-btn');
                    if (button) {
                        button.disabled = !isValid;
                    }
                }
                // mobile-input keeps mobile-continue always enabled (matches mockup)
            }

            handlePhoneKeypress(e) {
                // Only allow digits
                if (!/\d/.test(e.key) && !['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab'].includes(e.key)) {
                    e.preventDefault();
                }
            }

            async handleMobileContinue() {
                const mobileInput = document.getElementById('mobile-input');
                const mobileValue = mobileInput.value;

                if (!mobileValue || mobileValue.length !== 10 || !mobileValue.startsWith('3')) {
                    this.showError('Please enter a valid 10-digit mobile number starting with 3');
                    return;
                }

                this.userPhone = '+92' + mobileValue;

                // Disable button and show loading
                const btn = document.getElementById('mobile-continue');
                btn.disabled = true;
                btn.textContent = 'Please wait...';

                try {
                    // First, check if user exists and has PIN set
                    const checkResponse = await this.apiCall(`${this.apiBaseUrl}/check-phone`, 'POST', {
                        phone: this.userPhone
                    });

                    if (checkResponse.success) {
                        const { user_exists, has_pin_set } = checkResponse.data;

                        // If user has PIN set and NOT resetting, go directly to PIN screen
                        if (has_pin_set && !this.isResettingTpin) {
                            this.showStep('tpin');
                            btn.disabled = false;
                            btn.textContent = 'Continue';
                            return;
                        }

                        // Otherwise, send OTP via Firebase (real SMS)
                        await this.sendFirebaseOTP();
                    } else {
                        this.showError('Failed to check phone number');
                    }
                } catch (error) {
                    console.error('Error in handleMobileContinue:', error);
                    this.showError(error.message || 'Network error. Please try again.');
                } finally {
                    btn.disabled = false;
                    btn.textContent = 'Continue';
                }
            }

            async sendFirebaseOTP() {
                try {
                    // Check if this is Ufone - use backend OTP bypass
                    if (this.isUfoneNumber(this.userPhone)) {
                        console.log('Ufone number detected, using backend OTP');
                        return await this.sendBackendOTP();
                    }

                    // Otherwise use Firebase (Jazz/Telenor)
                    // Send SMS via Firebase
                    this.confirmationResult = await auth.signInWithPhoneNumber(this.userPhone, this.recaptchaVerifier);

                    // SMS sent successfully
                    console.log('Firebase SMS sent successfully');
                    this.showSuccess('OTP sent to your mobile number');
                    this.showStep('otp');
                    this.startOtpTimer();
                } catch (error) {
                    console.error('Firebase SMS error:', error);

                    // Handle specific Firebase errors
                    let errorMessage = 'Failed to send OTP. Please try again.';

                    if (error.code === 'auth/invalid-phone-number') {
                        errorMessage = 'Invalid phone number format';
                    } else if (error.code === 'auth/too-many-requests') {
                        errorMessage = 'Too many requests. Please try again later.';
                    } else if (error.code === 'auth/quota-exceeded') {
                        errorMessage = 'SMS quota exceeded. Please contact support.';
                    }

                    this.showError(errorMessage);
                    throw error;
                }
            }

            /**
             * Send OTP via backend API (for Ufone bypass)
             */
            async sendBackendOTP() {
                try {
                    const purpose = this.isResettingTpin ? 'reset_pin' : 'login';
                    const response = await this.apiCall(`${this.apiBaseUrl}/send-otp`, 'POST', {
                        phone: this.userPhone,
                        purpose: purpose
                    });

                    if (response.success) {
                        // Check if this is Ufone bypass with OTP code
                        if (response.is_ufone_bypass && response.otp_code) {
                            alert(`Your OTP is: ${response.otp_code}\n\nPlease enter this code to continue.`);
                            this.autoFillOtp(response.otp_code);
                        } else {
                            this.showSuccess(response.message);
                        }

                        // Set flag to use backend verification
                        this.useBackendOTP = true;

                        // Show OTP step
                        this.showStep('otp');
                        this.startOtpTimer();
                    } else {
                        throw new Error(response.message || 'Failed to send OTP');
                    }
                } catch (error) {
                    console.error('Backend OTP error:', error);
                    throw error;
                }
            }

            /**
             * Auto-fill OTP input fields (for Ufone bypass user experience)
             */
            autoFillOtp(otpCode) {
                const otpInputs = document.querySelectorAll('#otp-inputs .pin-input');
                const digits = otpCode.split('');

                digits.forEach((digit, index) => {
                    if (otpInputs[index]) {
                        otpInputs[index].value = digit;
                        otpInputs[index].classList.add('filled');
                    }
                });

                // Enable the continue button
                document.getElementById('otp-continue').disabled = false;
            }

            async handleTpinContinue() {
                const tpinInputs = document.querySelectorAll('#tpin-inputs .pin-input');
                const enteredPin = Array.from(tpinInputs).map(input => input.value).join('');

                if (enteredPin.length !== 4) {
                    this.showError('Please enter a 4-digit PIN');
                    return;
                }

                const btn = document.getElementById('tpin-continue');
                btn.disabled = true;
                btn.textContent = 'Verifying...';

                try {
                    const response = await this.apiCall(`${this.apiBaseUrl}/login-pin`, 'POST', {
                        phone: this.userPhone,
                        pin: enteredPin
                    });

                    if (response.success) {
                        this.authToken = response.data.token;
                        localStorage.setItem('bixcash_token', this.authToken);
                        localStorage.setItem('bixcash_user', JSON.stringify(response.data.user));

                        this.showSuccess('Login successful!');

                        // Redirect based on user role
                        const userRole = response.data.user.role || 'customer';
                        if (userRole === 'partner' || response.data.user.is_partner) {
                            window.location.href = '/partner/dashboard';
                        } else {
                            window.location.href = '/customer/dashboard';
                        }
                    } else {
                        this.showError(response.message || 'Invalid PIN');
                        this.clearPinInputs(tpinInputs);
                    }
                } catch (error) {
                    this.showError(error.message || 'Login failed');
                    this.clearPinInputs(tpinInputs);
                } finally {
                    btn.disabled = true; // Keep disabled until all fields filled again
                    btn.textContent = 'Continue';
                }
            }

            async handleOtpContinue() {
                const otpInputs = document.querySelectorAll('#otp-inputs .pin-input');
                const enteredOtp = Array.from(otpInputs).map(input => input.value).join('');

                if (enteredOtp.length !== 6) {
                    this.showError('Please enter a 6-digit OTP');
                    return;
                }

                const btn = document.getElementById('otp-continue');
                btn.disabled = true;
                btn.textContent = 'Verifying...';

                try {
                    // Check if we should use backend OTP verification (for Ufone)
                    if (this.useBackendOTP) {
                        return await this.verifyBackendOTP(enteredOtp);
                    }

                    // Otherwise use Firebase verification (existing code)
                    // Verify OTP with Firebase
                    const userCredential = await this.confirmationResult.confirm(enteredOtp);
                    console.log('Firebase OTP verified successfully');

                    // Get Firebase ID token
                    const idToken = await userCredential.user.getIdToken();
                    console.log('Firebase ID token received');

                    // Send ID token to backend for verification and user creation
                    const response = await this.apiCall(`${this.apiBaseUrl}/verify-firebase-token`, 'POST', {
                        id_token: idToken
                    });

                    if (response.success) {
                        this.clearOtpTimer();
                        this.authToken = response.data.token;
                        localStorage.setItem('bixcash_token', this.authToken);
                        localStorage.setItem('bixcash_user', JSON.stringify(response.data.user));

                        // Check if user needs to set PIN
                        if (!response.data.has_pin_set) {
                            // Show beautiful PIN setup screen
                            this.showInitialPinSetup();
                            return; // Don't redirect yet, wait for PIN to be set
                        }

                        this.showSuccess('Welcome to BixCash!');
                        window.location.href = '/customer/dashboard';
                    } else {
                        this.showError(response.message || 'Authentication failed');
                        this.clearPinInputs(otpInputs);
                    }
                } catch (error) {
                    console.error('OTP verification error:', error);

                    let errorMessage = 'Invalid OTP. Please try again.';

                    if (error.code === 'auth/invalid-verification-code') {
                        errorMessage = 'Invalid OTP code. Please check and try again.';
                    } else if (error.code === 'auth/code-expired') {
                        errorMessage = 'OTP has expired. Please request a new one.';
                    }

                    this.showError(errorMessage);
                    this.clearPinInputs(otpInputs);
                } finally {
                    btn.disabled = true;
                    btn.textContent = 'Continue';
                }
            }

            /**
             * Verify OTP via backend API (for Ufone bypass)
             */
            async verifyBackendOTP(otpCode) {
                const otpInputs = document.querySelectorAll('#otp-inputs .pin-input');

                try {
                    const purpose = this.isResettingTpin ? 'reset_pin' : 'login';
                    const response = await this.apiCall(`${this.apiBaseUrl}/verify-otp`, 'POST', {
                        phone: this.userPhone,
                        otp: otpCode,
                        purpose: purpose
                    });

                    if (response.success) {
                        this.clearOtpTimer();
                        this.authToken = response.data.token;
                        localStorage.setItem('bixcash_token', this.authToken);
                        localStorage.setItem('bixcash_user', JSON.stringify(response.data.user));

                        // Check if user needs to set PIN
                        if (!response.data.has_pin_set) {
                            // Show beautiful PIN setup screen
                            this.showInitialPinSetup();
                            return; // Don't redirect yet, wait for PIN to be set
                        }

                        this.showSuccess('Welcome to BixCash!');
                        window.location.href = '/customer/dashboard';
                    } else {
                        throw new Error(response.message || 'Verification failed');
                    }
                } catch (error) {
                    console.error('Backend OTP verification error:', error);
                    this.showError(error.message || 'Invalid OTP. Please try again.');
                    this.clearPinInputs(otpInputs);
                    throw error;
                }
            }

            async handleCreateAccount() {
                const signupMobileInput = document.getElementById('signup-mobile-input');
                const mobileValue = signupMobileInput.value;

                if (!mobileValue || mobileValue.length !== 10 || !mobileValue.startsWith('3')) {
                    this.showError('Please enter a valid 10-digit mobile number');
                    return;
                }

                this.userPhone = '+92' + mobileValue;

                const btn = document.getElementById('create-account-btn');
                btn.disabled = true;
                btn.textContent = 'Please wait...';

                try {
                    // First, check if user already exists
                    const checkResponse = await this.apiCall(`${this.apiBaseUrl}/check-phone`, 'POST', {
                        phone: this.userPhone
                    });

                    if (checkResponse.success) {
                        const { user_exists, has_pin_set } = checkResponse.data;

                        // If user already exists
                        if (user_exists) {
                            if (has_pin_set) {
                                // User has PIN, redirect to login with PIN
                                this.showError('This number is already registered. Please use Sign In instead.');
                                // Automatically switch to sign-in step with phone pre-filled
                                document.getElementById('mobile-input').value = mobileValue;
                                this.showStep('mobile');
                                return;
                            } else {
                                // User exists but no PIN set, send OTP to complete registration
                                this.showSuccess('Account found. Sending verification code...');
                            }
                        }

                        // Send OTP via Firebase (for new users or existing users without PIN)
                        await this.sendFirebaseOTP();
                    } else {
                        this.showError('Failed to verify phone number');
                    }
                } catch (error) {
                    this.showError(error.message || 'Failed to send OTP');
                } finally {
                    btn.disabled = true;
                    btn.textContent = 'Create Your Account';
                }
            }

            async setupPin(pin, pinConfirmation) {
                try {
                    const response = await this.apiCall(`${this.apiBaseUrl}/setup-pin`, 'POST', {
                        pin: pin,
                        pin_confirmation: pinConfirmation
                    }, this.authToken);

                    if (response.success) {
                        this.showSuccess('PIN set successfully!');
                        return true;
                    } else {
                        this.showError(response.message || 'Failed to set PIN');
                        return false;
                    }
                } catch (error) {
                    this.showError(error.message || 'Failed to set PIN');
                    return false;
                }
            }

            handleForgotTpin() {
                // Set flag for forgot T-Pin flow
                this.isResettingTpin = true;

                // Reset user phone to start fresh verification
                this.userPhone = '';

                // Go back to mobile number input for identity verification
                this.showStep('mobile');

                // Update UI to show it's for T-Pin reset
                document.querySelector('#step-mobile .auth-title').textContent = 'Reset T-Pin';
                document.querySelector('#step-mobile .auth-subtitle').textContent = 'Enter your mobile number to verify your identity';
            }

            async handleSaveNewTpin() {
                const newTpinInputs = document.querySelectorAll('#new-tpin-inputs .pin-input');
                const newTpin = Array.from(newTpinInputs).map(input => input.value).join('');

                if (newTpin.length !== 4) {
                    this.showError('Please enter a 4-digit PIN');
                    return;
                }

                const btn = document.getElementById('save-new-tpin');
                btn.disabled = true;
                btn.textContent = 'Saving...';

                try {
                    // Check if this is initial PIN setup or reset flow
                    if (this.isSettingInitialPin) {
                        // Initial PIN setup flow - use setup-pin endpoint
                        const success = await this.setupPin(newTpin, newTpin);
                        if (success) {
                            this.isSettingInitialPin = false;
                            this.showSuccess('PIN set successfully!');
                            window.location.href = '/customer/dashboard';
                        } else {
                            this.clearPinInputs(newTpinInputs);
                        }
                    } else {
                        // Reset PIN flow - use reset-pin/verify endpoint
                        if (!this.verifiedOtp) {
                            this.showError('OTP verification required. Please start over.');
                            this.showStep('mobile');
                            return;
                        }

                        const response = await this.apiCall(`${this.apiBaseUrl}/reset-pin/verify`, 'POST', {
                            phone: this.userPhone,
                            otp: this.verifiedOtp,
                            new_pin: newTpin,
                            new_pin_confirmation: newTpin
                        });

                        if (response.success) {
                            this.isResettingTpin = false;
                            this.verifiedOtp = null; // Clear verified OTP
                            this.showSuccess('PIN reset successfully!');
                            window.location.href = '/customer/dashboard';
                        } else {
                            this.showError(response.message || 'Failed to reset PIN');
                            this.clearPinInputs(newTpinInputs);
                        }
                    }
                } catch (error) {
                    this.showError(error.message || (this.isSettingInitialPin ? 'Failed to set PIN' : 'Failed to reset PIN'));
                    this.clearPinInputs(newTpinInputs);
                } finally {
                    btn.disabled = true;
                    btn.textContent = 'Save New T-Pin';
                }
            }

            // API Call Helper Method
            async apiCall(url, method = 'GET', body = null, token = null) {
                const headers = {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                };

                if (token) {
                    headers['Authorization'] = `Bearer ${token}`;
                }

                const options = {
                    method: method,
                    headers: headers,
                    credentials: 'include' // Include cookies for session handling
                };

                if (body && method !== 'GET') {
                    options.body = JSON.stringify(body);
                }

                try {
                    const response = await fetch(url, options);
                    const data = await response.json();

                    if (!response.ok && !data.success) {
                        throw new Error(data.message || `HTTP error! status: ${response.status}`);
                    }

                    return data;
                } catch (error) {
                    console.error('API call error:', error);
                    throw error;
                }
            }

            async resendOtp() {
                try {
                    // Resend SMS via Firebase
                    await this.sendFirebaseOTP();
                    this.showSuccess('OTP resent to your mobile number');
                } catch (error) {
                    this.showError(error.message || 'Failed to resend OTP');
                }
            }

            startOtpTimer() {
                this.clearOtpTimer();
                this.otpCountdown = 60;

                const timerElement = document.getElementById('timer-count');
                const resendLink = document.getElementById('resend-otp');
                const timerText = document.getElementById('otp-timer');

                timerText.style.display = 'block';
                resendLink.style.display = 'none';

                this.otpTimer = setInterval(() => {
                    this.otpCountdown--;
                    timerElement.textContent = this.otpCountdown;

                    if (this.otpCountdown <= 0) {
                        this.clearOtpTimer();
                        timerText.style.display = 'none';
                        resendLink.style.display = 'inline-block';
                    }
                }, 1000);
            }

            clearOtpTimer() {
                if (this.otpTimer) {
                    clearInterval(this.otpTimer);
                    this.otpTimer = null;
                }
            }

            showStep(stepName) {
                // Hide all steps
                document.querySelectorAll('.auth-step').forEach(step => {
                    step.classList.remove('active');
                });

                // Show target step
                document.getElementById(`step-${stepName}`).classList.add('active');
                this.currentStep = stepName;

                // Reset UI text to default when going back to mobile step
                if (stepName === 'mobile' && !this.isResettingTpin) {
                    document.querySelector('#step-mobile .auth-title').textContent = 'Welcome back.';
                    document.querySelector('#step-mobile .auth-subtitle').textContent = 'Please sign in with your account.';
                }

                // Clear any previous inputs when switching steps
                if (stepName === 'tpin') {
                    this.clearPinInputs(document.querySelectorAll('#tpin-inputs .pin-input'));
                    document.getElementById('tpin-continue').disabled = true;
                } else if (stepName === 'otp') {
                    this.clearPinInputs(document.querySelectorAll('#otp-inputs .pin-input'));
                    document.getElementById('otp-continue').disabled = true;
                } else if (stepName === 'reset-tpin') {
                    this.clearPinInputs(document.querySelectorAll('#new-tpin-inputs .pin-input'));
                    document.getElementById('save-new-tpin').disabled = true;
                } else if (stepName === 'success') {
                    // Reset success message to default if not in reset flow
                    if (!this.isResettingTpin) {
                        document.querySelector('#step-success .auth-title').textContent = 'Welcome to BixCash!';
                        document.querySelector('#step-success .auth-subtitle').textContent = 'Your account has been successfully verified.';
                    }
                }
            }

            /**
             * Show initial PIN setup screen (for new users)
             * This uses the same UI as reset-tpin but for initial setup
             */
            showInitialPinSetup() {
                this.isSettingInitialPin = true;

                // Update UI text for initial setup
                const stepTitle = document.querySelector('#step-reset-tpin .auth-title');
                const stepSubtitle = document.querySelector('#step-reset-tpin .auth-subtitle');

                stepTitle.textContent = 'Set Your T-Pin';
                stepSubtitle.textContent = 'Create a 4-digit T-Pin to secure your account';

                // Show the step
                this.showStep('reset-tpin');
            }

            clearPinInputs(inputs) {
                inputs.forEach(input => {
                    input.value = '';
                    input.classList.remove('filled');
                });
            }

            showError(message) {
                alert('❌ ' + message);
            }

            showSuccess(message) {
                // Simple success notification
                console.log('✅ ' + message);
                // You can enhance this with a toast notification
            }
        }

        // Initialize the authentication system when page loads
        document.addEventListener('DOMContentLoaded', () => {
            new BixCashAuth();
            initializeMobileNavigation();
        });

        // =================================
        // MOBILE NAVIGATION SYSTEM
        // =================================
        function initializeMobileNavigation() {
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const mobileNavOverlay = document.getElementById('mobile-nav-overlay');
            const mobileNavClose = document.getElementById('mobile-nav-close');
            const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');
            const body = document.body;

            // Check if elements exist
            if (!mobileMenuBtn || !mobileNavOverlay || !mobileNavClose) {
                console.log('Mobile navigation elements not found');
                return;
            }

            // Open mobile menu
            function openMobileMenu() {
                mobileNavOverlay.classList.add('active');
                mobileMenuBtn.classList.add('active');
                body.classList.add('mobile-menu-open');

                // Focus management for accessibility
                setTimeout(() => {
                    mobileNavClose.focus();
                }, 100);
            }

            // Close mobile menu
            function closeMobileMenu() {
                mobileNavOverlay.classList.remove('active');
                mobileMenuBtn.classList.remove('active');
                body.classList.remove('mobile-menu-open');

                // Return focus to menu button
                setTimeout(() => {
                    mobileMenuBtn.focus();
                }, 100);
            }

            // Toggle menu
            mobileMenuBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                if (mobileNavOverlay.classList.contains('active')) {
                    closeMobileMenu();
                } else {
                    openMobileMenu();
                }
            });

            // Close menu
            mobileNavClose.addEventListener('click', () => {
                closeMobileMenu();
            });

            // Close on link click
            mobileNavLinks.forEach(link => {
                link.addEventListener('click', () => {
                    closeMobileMenu();
                });
            });

            // Close when clicking on overlay background
            mobileNavOverlay.addEventListener('click', (e) => {
                if (e.target === mobileNavOverlay) {
                    closeMobileMenu();
                }
            });

            // Keyboard support - ESC to close
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && mobileNavOverlay.classList.contains('active')) {
                    closeMobileMenu();
                }
            });
        }
    </script>

    <!-- Mobile Bottom Navigation -->
    <nav class="mobile-bottom-nav">
        <a href="/#home" class="bottom-nav-item" data-section="home">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>
        <a href="/#brands" class="bottom-nav-item" data-section="brands">
            <i class="fas fa-store"></i>
            <span>Brands</span>
        </a>
        <a href="{{ route('login') }}" class="bottom-nav-item active">
            <i class="fas fa-user"></i>
            <span>Account</span>
        </a>
        <a href="/partner/register" class="bottom-nav-item">
            <i class="fas fa-handshake"></i>
            <span>Partner</span>
        </a>
        <a href="/#promotions" class="bottom-nav-item" data-section="promotions">
            <i class="fas fa-gift"></i>
            <span>Promos</span>
        </a>
    </nav>

</body>
</html>