<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#021c47">
    <title>Partner Registration - BixCash</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --bix-green: #76d37a;
            --bix-light-green: #93db4d;
            --bix-dark-blue: #021c47;
            --bix-white: #ffffff;
            --bix-light-gray-1: #f8fafc;
            --bix-light-gray-2: #e5e7eb;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        /* ============================
           HEADER STYLES
           ============================ */
        .main-header {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
        }

        .main-header nav.desktop-nav {
            display: flex;
            margin-left: auto;
            margin-right: 2rem;
        }

        /* Auth Button - Green Gradient */
        .auth-btn {
            background: linear-gradient(135deg, #93db4d 0%, #76d37a 100%);
            color: white;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.8rem;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            box-shadow: 0 2px 8px rgba(147, 219, 77, 0.25);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .auth-btn:hover {
            background: linear-gradient(135deg, #85c441 0%, #68c26a 100%);
            box-shadow: 0 4px 15px rgba(147, 219, 77, 0.4);
            transform: translateY(-1px);
        }

        .auth-btn:active {
            transform: translateY(0);
            box-shadow: 0 2px 8px rgba(147, 219, 77, 0.25);
        }

        /* Navigation active state */
        nav a.active {
            color: var(--bix-green);
            font-weight: 600;
            position: relative;
        }

        nav a.active::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--bix-green);
            border-radius: 1px;
        }

        /* Desktop navigation visible by default */
        .desktop-nav,
        .desktop-auth {
            display: flex;
        }

        /* ============================
           MOBILE MENU BUTTON
           ============================ */
        .mobile-menu-btn {
            display: none;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 44px;
            height: 44px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            transition: background-color 0.3s ease;
            position: relative;
            z-index: 1001;
            min-width: 44px;
            min-height: 44px;
            touch-action: manipulation;
        }

        .mobile-menu-btn:hover {
            background-color: rgba(2, 28, 71, 0.1);
        }

        .mobile-menu-btn:focus {
            outline: 2px solid var(--bix-green);
            outline-offset: 2px;
        }

        /* Hamburger Lines */
        .hamburger-line {
            display: block;
            width: 24px;
            height: 3px;
            background-color: var(--bix-dark-blue);
            margin: 3px 0;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        /* Hamburger Animation - X when active */
        .mobile-menu-btn.active .hamburger-line:nth-child(1) {
            transform: rotate(45deg) translate(6px, 6px);
        }

        .mobile-menu-btn.active .hamburger-line:nth-child(2) {
            opacity: 0;
            transform: scale(0);
        }

        .mobile-menu-btn.active .hamburger-line:nth-child(3) {
            transform: rotate(-45deg) translate(6px, -6px);
        }

        /* ============================
           MOBILE NAVIGATION OVERLAY
           ============================ */
        .mobile-nav-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            background: rgba(2, 28, 71, 0.95);
            backdrop-filter: blur(10px);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .mobile-nav-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* Mobile Navigation Content */
        .mobile-nav-content {
            position: relative;
            width: 90%;
            max-width: 400px;
            max-height: 90vh;
            background: var(--bix-white);
            border-radius: 20px;
            padding: 0;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            transform: scale(0.95);
            transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .mobile-nav-overlay.active .mobile-nav-content {
            transform: scale(1);
        }

        /* Mobile Navigation Header */
        .mobile-nav-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 1.5rem 1rem;
            border-bottom: 2px solid var(--bix-light-gray-2);
        }

        .mobile-nav-logo {
            height: 40px;
            width: auto;
        }

        .mobile-nav-close {
            width: 44px;
            height: 44px;
            background: none;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: background-color 0.3s ease;
            font-size: 24px;
            color: var(--bix-dark-blue);
        }

        .mobile-nav-close:hover {
            background-color: var(--bix-light-gray-2);
        }

        .mobile-nav-close:focus {
            outline: 2px solid var(--bix-green);
            outline-offset: 2px;
        }

        /* Mobile Navigation Menu */
        .mobile-nav {
            padding: 1rem 0 2rem;
        }

        .mobile-nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .mobile-nav li {
            margin: 0;
        }

        .mobile-nav-link {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            color: var(--bix-dark-blue);
            text-decoration: none;
            font-size: 1.1rem;
            font-weight: 600;
            transition: color 0.2s ease, background-color 0.2s ease;
            border-radius: 0;
            position: relative;
        }

        /* Only apply hover effects on devices with mouse/trackpad */
        @media (hover: hover) and (pointer: fine) {
            .mobile-nav-link:hover {
                background-color: var(--bix-light-gray-1);
                color: var(--bix-green);
            }
        }

        /* Brief active state for touch devices */
        .mobile-nav-link:active {
            background-color: var(--bix-light-gray-1);
            color: var(--bix-green);
        }

        /* Active class styling (for current page indicator if needed) */
        .mobile-nav-link.active {
            color: var(--bix-green);
        }

        /* Mobile Authentication Button */
        .mobile-nav-auth {
            padding: 1rem 1.5rem 0;
            border-top: 2px solid var(--bix-light-gray-2);
            margin-top: 1rem;
        }

        .mobile-auth-btn {
            display: block;
            width: 100%;
            background: linear-gradient(135deg, #93db4d 0%, #76d37a 100%);
            color: white;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            padding: 1rem;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(147, 219, 77, 0.3);
            transition: all 0.3s ease;
            min-height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .mobile-auth-btn:hover {
            background: linear-gradient(135deg, #85c441 0%, #68c26a 100%);
            box-shadow: 0 6px 20px rgba(147, 219, 77, 0.4);
            transform: translateY(-2px);
        }

        /* ============================
           RESPONSIVE NAVIGATION
           ============================ */

        /* Hide mobile elements on desktop */
        @media (min-width: 769px) {
            .mobile-menu-btn,
            .mobile-nav-overlay {
                display: none;
            }
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            /* Show mobile menu button */
            .mobile-menu-btn {
                display: flex;
            }

            /* Hide desktop navigation */
            .desktop-nav,
            .desktop-auth,
            .main-header nav.desktop-nav {
                display: none !important;
            }

            /* Enable mobile navigation overlay */
            .mobile-nav-overlay {
                display: flex;
            }

            /* Adjust header padding on mobile */
            .main-header {
                padding: 1rem 1.5rem;
            }

            /* Smaller logo on mobile */
            .main-header .logo img {
                height: 45px;
            }

            .auth-btn {
                font-size: 0.7rem;
                padding: 0.4rem 0.8rem;
            }
        }

        /* Ultra-small mobile adjustments */
        @media (max-width: 480px) {
            .mobile-nav-content {
                width: 95%;
                margin: 1rem;
            }

            .mobile-nav-header {
                padding: 1rem 1rem 0.5rem;
            }

            .mobile-nav-logo {
                height: 35px;
            }

            .mobile-nav-link {
                padding: 0.8rem 1rem;
                font-size: 1rem;
            }

            .mobile-auth-btn {
                font-size: 1rem;
                padding: 0.8rem;
            }
        }

        /* ============================
           FORM STYLES
           ============================ */
        .form-input {
            display: block;
            width: 100%;
            border-radius: 0.75rem;
            border: 2px solid #e5e7eb;
            background-color: #fff;
            padding: 0.875rem 1rem;
            font-size: 0.95rem;
            color: #1f2937;
            transition: all 0.2s;
        }
        .form-input::placeholder {
            color: #9ca3af;
        }
        .form-input:focus {
            border-color: #76d37a;
            outline: none;
            box-shadow: 0 0 0 3px rgba(118, 211, 122, 0.2);
        }
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #021c47;
        }
        .register-section {
            min-height: calc(100vh - 200px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 4rem 1rem;
            background: #ffffff;
        }
        @media (max-width: 768px) {
            .register-section {
                padding: 2rem 1rem;
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
    {{-- Header --}}
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
                <li><a href="/partner/register">Partner with us</a></li>
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

        @auth
            <a href="{{ route('customer.dashboard') }}" class="auth-btn desktop-auth" style="display: flex; align-items: center; gap: 0.5rem;">
                <svg fill="currentColor" viewBox="0 0 20 20" style="width: 20px; height: 20px;"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                {{ Auth::user()->name }}
            </a>
        @else
            <a href="{{ route('login') }}" class="auth-btn desktop-auth">Sign In</a>
        @endauth
    </header>

    <!-- Mobile Navigation Overlay -->
    <div class="mobile-nav-overlay" id="mobile-nav-overlay">
        <div class="mobile-nav-content">
            <div class="mobile-nav-header">
                <a href="/" class="mobile-nav-logo">
                    <img src="/images/logos/logos-01.png" alt="BixCash Logo">
                </a>
                <button class="mobile-nav-close" id="mobile-nav-close" aria-label="Close mobile menu">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 28px; height: 28px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <nav class="mobile-nav">
                <ul>
                    <li><a href="/#home" class="mobile-nav-link">Home</a></li>
                    <li><a href="/#brands" class="mobile-nav-link">Brands</a></li>
                    <li><a href="/#how-it-works" class="mobile-nav-link">How It Works</a></li>
                    <li><a href="/partner/register" class="mobile-nav-link">Partner with us</a></li>
                    <li><a href="/#promotions" class="mobile-nav-link">Promotions</a></li>
                    <li><a href="/#contact" class="mobile-nav-link">Contact Us</a></li>
                </ul>
            </nav>
            <div class="mobile-nav-auth">
                @auth
                    <a href="{{ route('customer.dashboard') }}" class="mobile-auth-btn">
                        <svg fill="currentColor" viewBox="0 0 20 20" style="width: 20px; height: 20px;"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                        {{ Auth::user()->name }}
                    </a>
                @else
                    <a href="{{ route('login') }}" class="mobile-auth-btn">Sign In</a>
                @endauth
            </div>
        </div>
    </div>

    {{-- Main Content - Registration Form --}}
    <section class="register-section">
        <div class="w-full max-w-xl px-4">
            {{-- Form Header --}}
            <div class="text-center mb-8">
                <h1 class="text-3xl sm:text-4xl font-extrabold text-[#021c47] mb-3">Become a Partner</h1>
                <p class="text-gray-500 text-lg">Join our network and grow your business</p>
            </div>

            {{-- Success Message --}}
            @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            {{-- Error Messages --}}
            @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                <p class="text-sm font-semibold text-red-800 mb-2">Please fix the following errors:</p>
                <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Form Card --}}
            <div class="bg-white rounded-2xl shadow-xl p-6 sm:p-8">
                <form method="POST" action="{{ route('partner.register.submit') }}" id="partnerRegisterForm" class="space-y-5">
                    @csrf

                    {{-- Business Name --}}
                    <div>
                        <label class="form-label" for="business_name">Business Name <span class="text-red-500">*</span></label>
                        <input class="form-input" id="business_name" name="business_name" placeholder="e.g., KFC Lahore" required type="text" value="{{ old('business_name') }}">
                    </div>

                    {{-- Two Column Grid --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        {{-- Contact Person Name --}}
                        <div>
                            <label class="form-label" for="contact_person_name">Contact Person <span class="text-red-500">*</span></label>
                            <input class="form-input" id="contact_person_name" name="contact_person_name" placeholder="Full name" required type="text" value="{{ old('contact_person_name') }}">
                        </div>

                        {{-- Mobile Number --}}
                        <div>
                            <label class="form-label" for="phone">Mobile Number <span class="text-red-500">*</span></label>
                            <div class="flex">
                                <span class="inline-flex items-center rounded-l-xl border-2 border-r-0 border-gray-200 bg-gray-50 px-3.5 text-gray-600 text-sm font-semibold">+92</span>
                                <input class="form-input !rounded-l-none" id="phone" name="phone" placeholder="3001234567" required type="text" maxlength="10" pattern="[0-9]{10}" value="{{ old('phone') }}">
                            </div>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="form-label" for="email">Email <span class="text-gray-400 font-normal">(Optional)</span></label>
                        <input class="form-input" id="email" name="email" placeholder="your@email.com" type="email" value="{{ old('email') }}">
                    </div>

                    {{-- Two Column Grid --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        {{-- Business Type --}}
                        <div>
                            <label class="form-label" for="business_type">Business Type <span class="text-red-500">*</span></label>
                            <select class="form-input" id="business_type" name="business_type" required>
                                <option value="">Select type</option>
                                <option value="Restaurant" {{ old('business_type') == 'Restaurant' ? 'selected' : '' }}>Restaurant</option>
                                <option value="Retail" {{ old('business_type') == 'Retail' ? 'selected' : '' }}>Retail Store</option>
                                <option value="Cafe" {{ old('business_type') == 'Cafe' ? 'selected' : '' }}>Cafe</option>
                                <option value="Grocery" {{ old('business_type') == 'Grocery' ? 'selected' : '' }}>Grocery Store</option>
                                <option value="Fashion" {{ old('business_type') == 'Fashion' ? 'selected' : '' }}>Fashion & Clothing</option>
                                <option value="Electronics" {{ old('business_type') == 'Electronics' ? 'selected' : '' }}>Electronics</option>
                                <option value="Salon" {{ old('business_type') == 'Salon' ? 'selected' : '' }}>Salon & Spa</option>
                                <option value="Pharmacy" {{ old('business_type') == 'Pharmacy' ? 'selected' : '' }}>Pharmacy</option>
                                <option value="Services" {{ old('business_type') == 'Services' ? 'selected' : '' }}>Services</option>
                                <option value="Other" {{ old('business_type') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        {{-- City --}}
                        <div>
                            <label class="form-label" for="city">City <span class="text-gray-400 font-normal">(Optional)</span></label>
                            <input class="form-input" id="city" name="city" placeholder="e.g., Lahore" type="text" value="{{ old('city') }}">
                        </div>
                    </div>

                    {{-- Business Address --}}
                    <div>
                        <label class="form-label" for="business_address">Business Address <span class="text-gray-400 font-normal">(Optional)</span></label>
                        <input class="form-input" id="business_address" name="business_address" placeholder="Complete address" type="text" value="{{ old('business_address') }}">
                    </div>

                    {{-- Terms & Conditions --}}
                    <div class="flex items-start gap-3 pt-2">
                        <input class="mt-1 h-5 w-5 rounded border-2 border-gray-300 text-[#76d37a] focus:ring-2 focus:ring-[#76d37a] focus:ring-offset-2 cursor-pointer" id="agree_terms" name="agree_terms" required type="checkbox" {{ old('agree_terms') ? 'checked' : '' }}>
                        <label class="text-sm text-gray-600 leading-relaxed" for="agree_terms">
                            I agree to BixCash <a class="font-semibold text-[#021c47] hover:text-[#76d37a] transition" href="#">Terms & Conditions</a> and confirm that all information provided is accurate.
                        </label>
                    </div>

                    {{-- Submit Button --}}
                    <button class="w-full flex items-center justify-center gap-2 rounded-xl bg-[#76d37a] px-6 py-4 text-base font-bold text-[#021c47] shadow-lg shadow-[#76d37a]/30 transition-all hover:bg-[#93db4d] hover:shadow-xl hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-[#76d37a] focus:ring-offset-2" type="submit">
                        <span>Submit Application</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="footer-section">
        <div class="footer-container">
            <div class="footer-content">
                <!-- Left Side - Logo and Social Media -->
                <div class="footer-brand">
                    <div class="footer-logo">
                        <img src="/images/logos/logos-01.png" alt="BixCash Logo" class="footer-logo-img">
                    </div>
                    <div class="footer-social">
                        @php
                            $socialMediaLinks = \App\Models\SocialMediaLink::enabled()->ordered()->get();
                        @endphp
                        @foreach($socialMediaLinks as $socialLink)
                            <a href="{{ $socialLink->url }}" target="_blank" rel="noopener noreferrer" class="social-link {{ strtolower($socialLink->platform) }}" title="{{ ucfirst($socialLink->platform) }}">
                                @if($socialLink->icon_file)
                                    <img src="{{ asset('storage/' . $socialLink->icon_file) }}" alt="{{ $socialLink->platform }}" style="width: 32px; height: 32px; object-fit: contain;">
                                @else
                                    <i class="{{ $socialLink->icon }}" style="font-size: 1.2rem;"></i>
                                @endif
                            </a>
                        @endforeach
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
                            <li><a href="#" class="footer-link">Clothing</a></li>
                            <li><a href="#" class="footer-link">Home Appliances</a></li>
                            <li><a href="#" class="footer-link">Entertainment</a></li>
                            <li><a href="#" class="footer-link">Food</a></li>
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

    <script>
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileNavOverlay = document.getElementById('mobile-nav-overlay');
        const mobileNavClose = document.getElementById('mobile-nav-close');

        if (mobileMenuBtn && mobileNavOverlay) {
            mobileMenuBtn.addEventListener('click', function() {
                mobileNavOverlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            });
        }

        if (mobileNavClose && mobileNavOverlay) {
            mobileNavClose.addEventListener('click', function() {
                mobileNavOverlay.classList.remove('active');
                document.body.style.overflow = '';
            });
        }

        // Close on overlay click
        if (mobileNavOverlay) {
            mobileNavOverlay.addEventListener('click', function(e) {
                if (e.target === mobileNavOverlay) {
                    mobileNavOverlay.classList.remove('active');
                    document.body.style.overflow = '';
                }
            });
        }

        // Close on link click
        document.querySelectorAll('.mobile-nav-link').forEach(link => {
            link.addEventListener('click', function() {
                mobileNavOverlay.classList.remove('active');
                document.body.style.overflow = '';
            });
        });

        // Phone number validation - only allow numbers
        const phoneInput = document.querySelector('input[name="phone"]');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '').substring(0, 10);
            });
        }

        // Form submission - disable button to prevent double submit
        const form = document.getElementById('partnerRegisterForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span>Submitting...</span>';
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-70', 'cursor-not-allowed');
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
        <a href="{{ route('login') }}" class="bottom-nav-item">
            <i class="fas fa-user"></i>
            <span>Account</span>
        </a>
        <a href="/partner/register" class="bottom-nav-item active">
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
