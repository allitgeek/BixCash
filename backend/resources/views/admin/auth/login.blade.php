<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BixCash Admin Login</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(-45deg, #021c47, #032a6b, #1e3a8a, #4f46e5, #7c3aed);
            background-size: 400% 400%;
            animation: gradient-shift 15s ease infinite;
            overflow: hidden;
            position: relative;
        }

        @keyframes gradient-shift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        @keyframes particle-float {
            0%, 100% { transform: translate(0, 0) scale(1); opacity: 0.2; }
            50% { transform: translate(100px, -100px) scale(1.3); opacity: 0.4; }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
        }

        @keyframes fade-in {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Floating particles */
        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            animation: particle-float 20s infinite;
            pointer-events: none;
        }

        .particle:nth-child(1) { width: 80px; height: 80px; left: 10%; animation-delay: 0s; }
        .particle:nth-child(2) { width: 60px; height: 60px; left: 30%; animation-delay: 2s; }
        .particle:nth-child(3) { width: 100px; height: 100px; left: 50%; animation-delay: 4s; }
        .particle:nth-child(4) { width: 70px; height: 70px; left: 70%; animation-delay: 6s; }
        .particle:nth-child(5) { width: 90px; height: 90px; left: 90%; animation-delay: 8s; }

        .login-container {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            padding: 3rem 2.5rem;
            border-radius: 24px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2), 0 0 0 1px rgba(255, 255, 255, 0.5);
            width: 100%;
            max-width: 440px;
            position: relative;
            z-index: 10;
            animation: fade-in 0.6s ease;
        }

        .logo {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .logo-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto 1.5rem;
            background: white;
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(2, 28, 71, 0.15);
            animation: float 3s ease-in-out infinite;
            padding: 1rem;
            border: 3px solid rgba(118, 211, 122, 0.2);
        }

        .logo-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .logo h1 {
            color: #021c47;
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            letter-spacing: -0.02em;
        }

        .logo .tagline {
            color: #76d37a;
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .logo p {
            color: #6b7280;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.75rem;
            color: #374151;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            pointer-events: none;
        }

        .form-control {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 3rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 0.9375rem;
            transition: all 0.2s ease;
            background: white;
        }

        .form-control:focus {
            outline: none;
            border-color: #76d37a;
            box-shadow: 0 0 0 4px rgba(118, 211, 122, 0.1);
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .checkbox-group input {
            width: 18px;
            height: 18px;
            margin-right: 0.75rem;
            accent-color: #76d37a;
            cursor: pointer;
        }

        .checkbox-group label {
            color: #6b7280;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
        }

        .btn-primary {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #021c47, #032a6b);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(2, 28, 71, 0.3);
            position: relative;
            overflow: hidden;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #76d37a, #93db4d);
            transition: left 0.3s ease;
        }

        .btn-primary span {
            position: relative;
            z-index: 1;
        }

        .btn-primary:hover::before {
            left: 0;
        }

        .btn-primary:hover {
            box-shadow: 0 8px 25px rgba(118, 211, 122, 0.4);
            transform: translateY(-2px);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .powered-by {
            text-align: center;
            margin-top: 1.25rem;
            padding-top: 1.25rem;
            border-top: 1px solid #e5e7eb;
        }

        .powered-by p {
            font-size: 0.8125rem;
            color: #6b7280;
            font-weight: 500;
        }

        .powered-by a {
            color: #021c47;
            font-weight: 700;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .powered-by a:hover {
            color: #76d37a;
        }

        .alert {
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 500;
            animation: fade-in 0.4s ease;
        }

        .alert-danger {
            background: linear-gradient(135deg, #fef2f2, #fee2e2);
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .alert-success {
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 2rem 1.5rem;
                margin: 1rem;
            }

            .logo h1 {
                font-size: 2rem;
            }

            .logo-icon {
                width: 70px;
                height: 70px;
            }
        }
    </style>
</head>
<body>
    <!-- Floating particles -->
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>

    <div class="login-container">
        <div class="logo">
            <div class="logo-icon">
                <img src="{{ asset('images/logos/logos-01.png') }}" alt="BixCash Logo">
            </div>
            <h1>BixCash</h1>
            <p class="tagline">Shop to Earn Platform</p>
            <p>Admin Panel</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email Address</label>
                <div class="input-wrapper">
                    <svg class="input-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <input type="email" id="email" name="email" class="form-control"
                           value="{{ old('email') }}" required autofocus placeholder="admin@bixcash.com">
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <svg class="input-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <input type="password" id="password" name="password" class="form-control" required placeholder="Enter your password">
                </div>
            </div>

            <div class="checkbox-group">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember me for 30 days</label>
            </div>

            <button type="submit" class="btn-primary">
                <span>Sign In to Dashboard</span>
            </button>

            <div class="powered-by">
                <p>Powered by <a href="https://fimm.live" target="_blank">FIMM Digital Transformation</a></p>
            </div>
        </form>
    </div>
</body>
</html>
