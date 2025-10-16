<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partner Profile - BixCash</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #021c47 0%, #0a2f5f 100%);
            min-height: 100vh;
            padding: 2rem 1rem 100px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        h1 {
            color: #021c47;
            margin-bottom: 2rem;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 1rem 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .info-label {
            color: #718096;
            font-weight: 600;
        }
        .info-value {
            color: #1a202c;
            font-weight: 500;
        }
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            display: grid;
            grid-template-columns: repeat(4, 1fr);
        }
        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0.75rem 0.5rem;
            text-decoration: none;
            color: #718096;
            font-size: 0.7rem;
        }
        .nav-item.active {
            color: #93db4d;
        }
        .nav-icon {
            font-size: 1.5rem;
            margin-bottom: 0.25rem;
        }
        .btn-logout {
            background: #f56565;
            color: white;
            border: none;
            border-radius: 12px;
            padding: 1rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            margin-top: 2rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Partner Profile</h1>
        <div class="info-row">
            <span class="info-label">Business Name</span>
            <span class="info-value">{{ $partnerProfile->business_name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Business Type</span>
            <span class="info-value">{{ $partnerProfile->business_type }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Contact Person</span>
            <span class="info-value">{{ $partnerProfile->contact_person_name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Phone</span>
            <span class="info-value">{{ $partner->phone }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Email</span>
            <span class="info-value">{{ $partner->email ?? 'Not provided' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Address</span>
            <span class="info-value">{{ $partnerProfile->business_address ?? 'Not provided' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">City</span>
            <span class="info-value">{{ $partnerProfile->business_city ?? 'Not provided' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Status</span>
            <span class="info-value">{{ ucfirst($partnerProfile->status) }}</span>
        </div>
        <form method="POST" action="{{ route('partner.logout') }}">
            @csrf
            <button type="submit" class="btn-logout">Logout</button>
        </form>
    </div>
    <div class="bottom-nav">
        <a href="{{ route('partner.dashboard') }}" class="nav-item">
            <div class="nav-icon">🏠</div>
            <div>Home</div>
        </a>
        <a href="{{ route('partner.transactions') }}" class="nav-item">
            <div class="nav-icon">📋</div>
            <div>History</div>
        </a>
        <a href="{{ route('partner.profits') }}" class="nav-item">
            <div class="nav-icon">💰</div>
            <div>Profits</div>
        </a>
        <a href="{{ route('partner.profile') }}" class="nav-item active">
            <div class="nav-icon">👤</div>
            <div>Profile</div>
        </a>
    </div>
</body>
</html>
