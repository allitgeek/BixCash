<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#021c47">
    <title>Application Under Review - BixCash</title>
    @vite(['resources/css/app.css'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --primary: #93db4d;
            --secondary: #021c47;
            --text-dark: #1a202c;
            --text-light: #718096;
            --bg-light: #f7fafc;
            --warning: #f59e0b;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: linear-gradient(135deg, var(--secondary) 0%, #0a2f5f 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .container {
            max-width: 500px;
            width: 100%;
            background: white;
            border-radius: 20px;
            padding: 3rem 2.5rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            text-align: center;
        }

        .icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }

        .subtitle {
            color: var(--text-light);
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .info-card {
            background: var(--bg-light);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            text-align: left;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-size: 0.875rem;
            color: var(--text-light);
            font-weight: 500;
        }

        .info-value {
            font-size: 0.875rem;
            color: var(--text-dark);
            font-weight: 600;
        }

        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            background: #fef3c7;
            color: #92400e;
        }

        .btn {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            background: var(--primary);
            color: white;
            text-decoration: none;
            display: inline-block;
        }

        .btn:hover {
            background: var(--primary);
            opacity: 0.9;
            transform: translateY(-2px);
        }

        .logout-form {
            margin-top: 1rem;
        }

        .logout-btn {
            background: transparent;
            color: var(--text-light);
            border: 2px solid var(--text-light);
        }

        .logout-btn:hover {
            background: var(--text-light);
            color: white;
            opacity: 1;
        }

        @media (max-width: 768px) {
            .container {
                padding: 2rem 1.5rem;
            }

            h1 {
                font-size: 1.5rem;
            }

            .icon {
                font-size: 3rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">⏳</div>

        <h1>Application Under Review</h1>

        <p class="subtitle">
            Your partner application is being reviewed by our team.
            You'll receive an SMS once approved.
        </p>

        <div class="info-card">
            <div class="info-row">
                <span class="info-label">Business Name</span>
                <span class="info-value">{{ $partnerProfile->business_name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Contact Person</span>
                <span class="info-value">{{ $partnerProfile->contact_person_name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Submitted On</span>
                <span class="info-value">{{ $partnerProfile->registration_date ? $partnerProfile->registration_date->format('M d, Y') : 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Status</span>
                <span class="status-badge">{{ ucfirst($partnerProfile->status) }}</span>
            </div>
        </div>

        <form method="POST" action="{{ route('partner.logout') }}" class="logout-form">
            @csrf
            <button type="submit" class="btn logout-btn">Logout</button>
        </form>
    </div>
</body>
</html>
