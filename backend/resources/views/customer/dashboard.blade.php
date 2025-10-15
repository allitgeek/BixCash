<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#021c47">
    <title>Dashboard - BixCash</title>
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
            --success: #48bb78;
            --warning: #ed8936;
            --danger: #f56565;
            --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: var(--bg-light);
            color: var(--text-dark);
            padding-bottom: 80px;
        }

        /* Header */
        .dashboard-header {
            background: linear-gradient(135deg, var(--secondary) 0%, #0a2f5f 100%);
            color: white;
            padding: 1.5rem 1rem;
            box-shadow: var(--shadow-lg);
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
        }

        .user-greeting {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .greeting-text h1 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .greeting-text p {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
        }

        /* Wallet Card */
        .wallet-card {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-radius: 20px;
            padding: 1.5rem;
            color: white;
            box-shadow: var(--shadow-lg);
        }

        .wallet-balance {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-bottom: 0.5rem;
        }

        .balance-amount {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .wallet-actions {
            display: flex;
            gap: 0.75rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-white {
            background: white;
            color: var(--primary);
        }

        .btn-white:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .btn-outline {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn-outline:hover {
            background: white;
            color: var(--primary);
        }

        /* Main Content */
        .dashboard-content {
            max-width: 1200px;
            margin: -40px auto 0;
            padding: 0 1rem;
            position: relative;
            z-index: 1;
        }

        /* Quick Stats */
        .quick-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.25rem;
            box-shadow: var(--shadow-md);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
        }

        .stat-label {
            font-size: 0.75rem;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        /* Section Cards */
        .section {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-sm);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .section-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        .section-link {
            color: var(--primary);
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
        }

        /* Table */
        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            text-align: left;
            font-size: 0.75rem;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--border);
        }

        .table td {
            padding: 1rem 0;
            border-bottom: 1px solid var(--border);
        }

        .brand-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .brand-logo {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: var(--bg-light);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: var(--text-light);
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-pending { background: #fef3c7; color: #92400e; }
        .status-completed { background: #d1fae5; color: #065f46; }
        .status-processing { background: #dbeafe; color: #1e40af; }
        .status-rejected { background: #fee2e2; color: #991b1b; }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--text-light);
        }

        .empty-state svg {
            width: 64px;
            height: 64px;
            margin-bottom: 1rem;
            opacity: 0.3;
        }

        /* Bottom Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            border-top: 1px solid var(--border);
            padding: 0.75rem 0;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
            z-index: 100;
        }

        .nav-items {
            display: flex;
            justify-content: space-around;
            align-items: center;
            max-width: 600px;
            margin: 0 auto;
        }

        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.25rem;
            color: var(--text-light);
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .nav-item.active {
            color: var(--primary);
            background: rgba(147, 219, 77, 0.1);
        }

        .nav-item svg {
            width: 24px;
            height: 24px;
        }

        .nav-item span {
            font-size: 0.7rem;
            font-weight: 600;
        }

        /* Profile Complete Modal */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            padding: 1rem;
        }

        .modal {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            max-width: 500px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .modal-subtitle {
            color: var(--text-light);
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text-dark);
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid var(--border);
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(147, 219, 77, 0.1);
        }

        .form-hint {
            font-size: 0.75rem;
            color: var(--text-light);
            margin-top: 0.25rem;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            width: 100%;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        @media (max-width: 768px) {
            .balance-amount { font-size: 2rem; }
            .wallet-actions { flex-direction: column; }
            .btn { width: 100%; }
            .table { font-size: 0.875rem; }
        }
    </style>
</head>
<body>

    @if(!$profileComplete)
    <!-- Profile Completion Modal -->
    <div class="modal-overlay" id="profileModal">
        <div class="modal">
            <h2 class="modal-title">Complete Your Profile</h2>
            <p class="modal-subtitle">Welcome! Let's set up your account</p>

            <form method="POST" action="{{ route('customer.complete-profile') }}" id="profileForm">
                @csrf
                <div class="form-group">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-input" required placeholder="Enter your full name" value="{{ $user->name }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Email (Optional)</label>
                    <input type="email" name="email" class="form-input" placeholder="your@email.com" value="{{ $user->email }}">
                    <div class="form-hint">We'll use this for important updates</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Date of Birth (Optional)</label>
                    <input type="date" name="date_of_birth" class="form-input" max="{{ date('Y-m-d') }}">
                </div>

                <button type="submit" class="btn btn-primary">Complete Profile</button>
            </form>
            <p style="text-align: center; font-size: 0.85rem; color: var(--text-light); margin-top: 1rem;">
                Please complete your profile to continue using the dashboard
            </p>
        </div>
    </div>
    @endif

    <!-- Header -->
    <header class="dashboard-header">
        <div class="header-content">
            <div class="user-greeting">
                <div class="greeting-text">
                    <h1>Hello, {{ explode(' ', $user->name)[0] }}! 👋</h1>
                    <p>Welcome back to your dashboard</p>
                </div>
                <div class="user-avatar">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            </div>

            <!-- Wallet Card -->
            <div class="wallet-card">
                <div class="wallet-balance">Your Balance</div>
                <div class="balance-amount">Rs. {{ number_format($wallet->balance, 2) }}</div>
                <div class="wallet-actions">
                    <a href="{{ route('customer.wallet') }}" class="btn btn-white">Withdraw</a>
                    <a href="{{ route('customer.purchases') }}" class="btn btn-outline">History</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="dashboard-content">

        <!-- Quick Stats -->
        <div class="quick-stats">
            <div class="stat-card">
                <div class="stat-label">Total Earned</div>
                <div class="stat-value">Rs. {{ number_format($wallet->total_earned, 0) }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Withdrawn</div>
                <div class="stat-value">Rs. {{ number_format($wallet->total_withdrawn, 0) }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Purchases</div>
                <div class="stat-value">{{ $recentPurchases->count() }}</div>
            </div>
        </div>

        <!-- Recent Purchases -->
        <div class="section">
            <div class="section-header">
                <h2 class="section-title">Recent Purchases</h2>
                <a href="{{ route('customer.purchases') }}" class="section-link">View All →</a>
            </div>

            @if($recentPurchases->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Brand</th>
                            <th>Amount</th>
                            <th>Cashback</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentPurchases as $purchase)
                        <tr>
                            <td>
                                <div class="brand-info">
                                    <div class="brand-logo">{{ substr($purchase->brand->name ?? 'N/A', 0, 1) }}</div>
                                    <span>{{ $purchase->brand->name ?? 'Unknown' }}</span>
                                </div>
                            </td>
                            <td>Rs. {{ number_format($purchase->amount, 2) }}</td>
                            <td style="color: var(--success); font-weight: 600;">+Rs. {{ number_format($purchase->cashback_amount, 2) }}</td>
                            <td>
                                <span class="status-badge status-{{ $purchase->status }}">
                                    {{ ucfirst($purchase->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <p>No purchases yet</p>
                </div>
            @endif
        </div>

        <!-- Recent Withdrawals -->
        <div class="section">
            <div class="section-header">
                <h2 class="section-title">Recent Withdrawals</h2>
                <a href="{{ route('customer.wallet') }}" class="section-link">View All →</a>
            </div>

            @if($recentWithdrawals->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentWithdrawals as $withdrawal)
                        <tr>
                            <td style="font-weight: 600;">Rs. {{ number_format($withdrawal->amount, 2) }}</td>
                            <td>{{ $withdrawal->created_at->format('M d, Y') }}</td>
                            <td>
                                <span class="status-badge status-{{ $withdrawal->status }}">
                                    {{ ucfirst($withdrawal->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p>No withdrawals yet</p>
                </div>
            @endif
        </div>

    </main>

    <!-- Bottom Navigation -->
    <nav class="bottom-nav">
        <div class="nav-items">
            <a href="{{ route('customer.dashboard') }}" class="nav-item active">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                </svg>
                <span>Home</span>
            </a>

            <a href="{{ route('customer.wallet') }}" class="nav-item">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                </svg>
                <span>Wallet</span>
            </a>

            <a href="{{ route('customer.purchases') }}" class="nav-item">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path>
                </svg>
                <span>Purchases</span>
            </a>

            <a href="{{ route('customer.profile') }}" class="nav-item">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                </svg>
                <span>Profile</span>
            </a>

            <form method="POST" action="{{ route('customer.logout') }}" style="display: contents;" onsubmit="return confirm('Are you sure you want to logout?');">
                @csrf
                <button type="submit" class="nav-item" style="background: none; border: none; cursor: pointer; color: var(--text-light);">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"></path>
                    </svg>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </nav>

    @if(session('success'))
    <div style="position: fixed; top: 20px; right: 20px; background: var(--success); color: white; padding: 1rem 1.5rem; border-radius: 12px; box-shadow: var(--shadow-lg); z-index: 2000; animation: slideIn 0.3s ease;">
        {{ session('success') }}
    </div>
    @endif

    <style>
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    </style>

    <script>
        // Handle form submission
        document.addEventListener('DOMContentLoaded', function() {
            const profileForm = document.getElementById('profileForm');
            if (profileForm) {
                profileForm.addEventListener('submit', function(e) {
                    // Show loading state
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.textContent;
                    submitBtn.textContent = 'Saving...';
                    submitBtn.disabled = true;
                });
            }
        });

        // Auto-hide success message after 3 seconds
        @if(session('success'))
        setTimeout(() => {
            const successMsg = document.querySelector('[style*="position: fixed"]');
            if (successMsg) {
                successMsg.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => successMsg.remove(), 300);
            }
        }, 3000);

        // If profile was completed successfully, hide modal
        const modal = document.getElementById('profileModal');
        if (modal) {
            modal.style.animation = 'fadeOut 0.3s ease';
            setTimeout(() => modal.style.display = 'none', 300);
        }
        @endif
    </script>

    <style>
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }

        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }
    </style>

</body>
</html>
