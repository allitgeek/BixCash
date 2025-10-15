<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wallet - BixCash</title>
    @vite(['resources/css/app.css'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root { --primary: #93db4d; --primary-dark: #7bc33a; --secondary: #021c47; --text-dark: #1a202c; --text-light: #718096; --border: #e2e8f0; --bg-light: #f7fafc; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif; background: var(--bg-light); padding-bottom: 80px; }
        .header { background: var(--secondary); color: white; padding: 1.5rem 1rem; }
        .header-content { max-width: 1200px; margin: 0 auto; display: flex; align-items: center; gap: 1rem; }
        .back-btn { color: white; text-decoration: none; font-size: 1.5rem; }
        .header-title { font-size: 1.25rem; font-weight: 700; }
        .wallet-header { background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); border-radius: 20px; padding: 2rem 1.5rem; margin: 1.5rem 1rem; color: white; text-align: center; box-shadow: 0 10px 25px rgba(147, 219, 77, 0.2); }
        .balance-label { font-size: 0.9rem; opacity: 0.9; margin-bottom: 0.5rem; }
        .balance-amount { font-size: 3rem; font-weight: 700; margin-bottom: 1rem; }
        .wallet-stats { display: flex; justify-content: space-around; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.2); }
        .stat { text-align: center; }
        .stat-value { font-size: 1.25rem; font-weight: 700; }
        .stat-label { font-size: 0.75rem; opacity: 0.8; margin-top: 0.25rem; }
        .content { max-width: 800px; margin: 0 auto; padding: 0 1rem; }
        .withdraw-section { background: white; border-radius: 16px; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .section-title { font-size: 1.125rem; font-weight: 700; margin-bottom: 1rem; }
        .form-group { margin-bottom: 1rem; }
        .form-label { display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.5rem; }
        .form-input { width: 100%; padding: 0.75rem 1rem; border: 2px solid var(--border); border-radius: 12px; font-size: 1rem; }
        .form-input:focus { outline: none; border-color: var(--primary); }
        .btn { padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 600; border: none; cursor: pointer; }
        .btn-primary { background: var(--primary); color: white; width: 100%; }
        .section { background: white; border-radius: 16px; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .table { width: 100%; }
        .table th { text-align: left; font-size: 0.75rem; color: var(--text-light); text-transform: uppercase; padding-bottom: 0.75rem; border-bottom: 2px solid var(--border); }
        .table td { padding: 1rem 0; border-bottom: 1px solid var(--border); }
        .status-badge { padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.75rem; font-weight: 600; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-completed { background: #d1fae5; color: #065f46; }
        .status-processing { background: #dbeafe; color: #1e40af; }
        .status-rejected { background: #fee2e2; color: #991b1b; }
        .bottom-nav { position: fixed; bottom: 0; left: 0; right: 0; background: white; border-top: 1px solid var(--border); padding: 0.75rem 0; }
        .nav-items { display: flex; justify-content: space-around; max-width: 600px; margin: 0 auto; }
        .nav-item { display: flex; flex-direction: column; align-items: center; gap: 0.25rem; color: var(--text-light); text-decoration: none; padding: 0.5rem 1rem; }
        .nav-item.active { color: var(--primary); }
        .nav-item svg { width: 24px; height: 24px; }
        .nav-item span { font-size: 0.7rem; font-weight: 600; }
    </style>
</head>
<body>

    <header class="header">
        <div class="header-content">
            <a href="{{ route('customer.dashboard') }}" class="back-btn">←</a>
            <h1 class="header-title">My Wallet</h1>
        </div>
    </header>

    <div class="wallet-header">
        <div class="balance-label">Available Balance</div>
        <div class="balance-amount">Rs. {{ number_format($wallet->balance, 2) }}</div>
        <div class="wallet-stats">
            <div class="stat">
                <div class="stat-value">Rs. {{ number_format($wallet->total_earned, 0) }}</div>
                <div class="stat-label">Total Earned</div>
            </div>
            <div class="stat">
                <div class="stat-value">Rs. {{ number_format($wallet->total_withdrawn, 0) }}</div>
                <div class="stat-label">Total Withdrawn</div>
            </div>
        </div>
    </div>

    <main class="content">

        @php
            $profile = Auth::user()->customerProfile;
            $isLocked = $profile && $profile->withdrawal_locked_until && $profile->withdrawal_locked_until > now();
        @endphp

        @if($isLocked)
        <div style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 1rem; margin-bottom: 1.5rem; border-radius: 8px;">
            <p style="font-size: 0.875rem; color: #92400e; margin-bottom: 0.5rem;">
                <strong>🔒 Withdrawals Temporarily Locked</strong>
            </p>
            <p style="font-size: 0.875rem; color: #92400e;">
                For security, withdrawals are locked for 24 hours after changing bank details.
                <br>
                <strong>Unlock Time:</strong> {{ $profile->withdrawal_locked_until->format('M d, Y h:i A') }}
            </p>
        </div>
        @endif

        <div class="withdraw-section">
            <h2 class="section-title">Request Withdrawal</h2>
            <form method="POST" action="{{ route('customer.wallet.withdraw') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Amount (Rs.) *</label>
                    <input type="number" name="amount" class="form-input" required min="100" step="0.01" placeholder="Minimum Rs. 100">
                </div>
                <button type="submit" class="btn btn-primary">Request Withdrawal</button>
            </form>
        </div>

        <div class="section">
            <h2 class="section-title">Withdrawal History</h2>
            @if($withdrawals->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($withdrawals as $withdrawal)
                        <tr>
                            <td style="font-weight: 600;">Rs. {{ number_format($withdrawal->amount, 2) }}</td>
                            <td>{{ $withdrawal->created_at->format('M d, Y') }}</td>
                            <td><span class="status-badge status-{{ $withdrawal->status }}">{{ ucfirst($withdrawal->status) }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div style="margin-top: 1rem;">
                    {{ $withdrawals->links() }}
                </div>
            @else
                <p style="text-align: center; color: var(--text-light); padding: 2rem;">No withdrawals yet</p>
            @endif
        </div>

    </main>

    <nav class="bottom-nav">
        <div class="nav-items">
            <a href="{{ route('customer.dashboard') }}" class="nav-item">
                <svg fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                <span>Home</span>
            </a>
            <a href="{{ route('customer.wallet') }}" class="nav-item active">
                <svg fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path></svg>
                <span>Wallet</span>
            </a>
            <a href="{{ route('customer.purchases') }}" class="nav-item">
                <svg fill="currentColor" viewBox="0 0 20 20"><path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path></svg>
                <span>Purchases</span>
            </a>
            <a href="{{ route('customer.profile') }}" class="nav-item">
                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                <span>Profile</span>
            </a>
            <form method="POST" action="{{ route('customer.logout') }}" style="display: contents;" onsubmit="return confirm('Are you sure you want to logout?');">
                @csrf
                <button type="submit" class="nav-item" style="background: none; border: none; cursor: pointer; color: var(--text-light);">
                    <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"></path></svg>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </nav>

    @if(session('success'))
    <div style="position: fixed; top: 20px; right: 20px; background: #48bb78; color: white; padding: 1rem 1.5rem; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 2000;">{{ session('success') }}</div>
    @endif

    @if(session('error'))
    <div style="position: fixed; top: 20px; right: 20px; background: #f56565; color: white; padding: 1rem 1.5rem; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 2000;">{{ session('error') }}</div>
    @endif

</body>
</html>
