<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#021c47">
    <title>Sign Up - BixCash</title>

    <!-- Stylesheets -->
    <link rel="stylesheet" href="/build/assets/app-Bx26URkf.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- Auth Page Specific Styles -->
    <style>
        /* Auth Page Layout */
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

        .create-account-btn {
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
        }

        .create-account-btn:hover {
            background: #85c441;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(147, 219, 77, 0.3);
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

        .signin-link {
            display: inline-block;
            background: var(--bix-dark-blue);
            color: var(--bix-white);
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

        .signin-link:hover {
            background: #032a5a;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(2, 42, 90, 0.3);
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

            .auth-subtitle {
                font-size: 1rem;
                margin-bottom: 2.5rem;
            }

            .signin-link {
                font-size: 0.85rem;
                padding: 0.9rem 1.5rem;
                white-space: nowrap;
            }
        }

        @media (max-width: 380px) {
            .signin-link {
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
    <header class="auth-header">
        <a href="/" class="logo">
            <img src="/images/logos/logos-01.png" alt="BixCash">
            <span class="tagline">Shop to Earn</span>
        </a>

        <nav>
            <ul>
                <li><a href="/" class="active">Home</a></li>
                <li><a href="/#partner">Partner with us</a></li>
                <li><a href="/#brands">Brands</a></li>
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
            <h1 class="auth-title">New customer?</h1>
            <p class="auth-subtitle">Please create your account first.</p>

            <form action="#" method="POST">
                @csrf

                <div class="form-group">
                    <label for="mobile" class="form-label">Enter Mobile Number</label>
                    <input
                        type="tel"
                        id="mobile"
                        name="mobile"
                        class="form-input"
                        placeholder=""
                        required
                    >
                </div>

                <button type="submit" class="create-account-btn">
                    Create Your Account
                </button>

                <p class="terms-text">
                    By clicking "Create Your Account", you agree to our
                    <a href="#">Terms & Conditions</a> and <a href="#">Privacy Policy</a>.
                </p>

                <a href="{{ route('login') }}" class="signin-link">
                    Have an account? Sign in
                </a>
            </form>
        </div>
    </div>

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