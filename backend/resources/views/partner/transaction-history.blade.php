<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History - BixCash</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #021c47 0%, #0a2f5f 100%);
            min-height: 100vh;
            padding: 2rem 1rem 100px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        h1 {
            color: white;
            margin-bottom: 2rem;
        }
        .transaction-list {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .transaction-item {
            padding: 1rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .transaction-item:last-child {
            border-bottom: none;
        }
        .transaction-info h4 {
            color: #1a202c;
            margin-bottom: 0.25rem;
        }
        .transaction-info p {
            font-size: 0.75rem;
            color: #718096;
        }
        .transaction-amount {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1a202c;
            text-align: right;
        }
        .transaction-status {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
            border-radius: 8px;
            font-weight: 600;
            display: inline-block;
            margin-top: 0.25rem;
        }
        .status-confirmed {
            background: #d1fae5;
            color: #065f46;
        }
        .status-pending_confirmation {
            background: #fef3c7;
            color: #92400e;
        }
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #718096;
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Transaction History</h1>
        <div class="transaction-list">
            @forelse($transactions as $transaction)
            <div class="transaction-item">
                <div class="transaction-info">
                    <h4>{{ $transaction->customer->name }}</h4>
                    <p>{{ $transaction->transaction_code }} • {{ $transaction->created_at->format('M d, Y h:i A') }}</p>
                    <span class="transaction-status status-{{ strtolower($transaction->status) }}">
                        {{ ucfirst(str_replace('_', ' ', $transaction->status)) }}
                    </span>
                </div>
                <div class="transaction-amount">
                    Rs {{ number_format($transaction->invoice_amount, 0) }}
                </div>
            </div>
            @empty
            <div class="empty-state">
                <p>No transactions yet</p>
            </div>
            @endforelse
        </div>
        @if($transactions->hasPages())
        <div style="margin-top: 2rem;">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
    <div class="bottom-nav">
        <a href="{{ route('partner.dashboard') }}" class="nav-item">
            <div class="nav-icon">🏠</div>
            <div>Home</div>
        </a>
        <a href="{{ route('partner.transactions') }}" class="nav-item active">
            <div class="nav-icon">📋</div>
            <div>History</div>
        </a>
        <a href="{{ route('partner.profits') }}" class="nav-item">
            <div class="nav-icon">💰</div>
            <div>Profits</div>
        </a>
        <a href="{{ route('partner.profile') }}" class="nav-item">
            <div class="nav-icon">👤</div>
            <div>Profile</div>
        </a>
    </div>
</body>
</html>
