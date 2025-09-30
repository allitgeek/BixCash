<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#021c47">
    <title>Sign In - BixCash</title>

    <!-- Stylesheets -->
    <link rel="stylesheet" href="/build/assets/app-Bx26URkf.css" />

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

        .signin-btn {
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

        .signin-btn:hover {
            background: #85c441;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(147, 219, 77, 0.3);
        }

        .signup-link {
            display: inline-block;
            background: var(--bix-dark-blue);
            color: var(--bix-white);
            padding: 0.8rem 2rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .signup-link:hover {
            background: #032a5a;
            transform: translateY(-1px);
        }

        .forgot-password {
            color: var(--bix-light-green);
            text-decoration: none;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
            display: inline-block;
        }

        .forgot-password:hover {
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

            .auth-subtitle {
                font-size: 1rem;
                margin-bottom: 2.5rem;
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
            <h1 class="auth-title">Welcome back!</h1>
            <p class="auth-subtitle">Please sign in to your account.</p>

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

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-input"
                        placeholder=""
                        required
                    >
                </div>

                <a href="#" class="forgot-password">Forgot your password?</a>

                <button type="submit" class="signin-btn">
                    Sign In
                </button>

                <a href="{{ route('signup') }}" class="signup-link">
                    New customer? Create account
                </a>
            </form>
        </div>
    </div>
</body>
</html>