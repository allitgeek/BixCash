<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profit History - BixCash</title>
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
        .profit-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }
        .profit-card {
            background: white;
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .profit-card h3 {
            color: #021c47;
            margin-bottom: 1rem;
        }
        .profit-amount {
            font-size: 2rem;
            font-weight: 700;
            color: #48bb78;
            margin-bottom: 1rem;
        }
        .profit-info p {
            color: #718096;
            font-size: 0.875rem;
            margin: 0.5rem 0;
        }
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: white;
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
        <h1>Profit History</h1>
        @forelse($profitBatches as $batch)
        <div class="profit-grid">
            <div class="profit-card">
                <h3>{{ $batch->batch_period }}</h3>
                <div class="profit-amount">Rs {{ number_format($batch->transactions->sum('partner_profit_share'), 0) }}</div>
                <div class="profit-info">
                    <p>Transactions: {{ $batch->transactions->count() }}</p>
                    <p>Processed: {{ $batch->processed_at->format('M d, Y') }}</p>
                    <p>Status: {{ ucfirst($batch->status) }}</p>
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <p style="font-size: 1.5rem;">📊</p>
            <p style="margin-top: 1rem;">No profit batches yet</p>
            <p style="font-size: 0.875rem; margin-top: 0.5rem;">Profit distribution happens monthly</p>
        </div>
        @endforelse
        @if($profitBatches->hasPages())
        <div style="margin-top: 2rem;">
            {{ $profitBatches->links() }}
        </div>
        @endif
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
        <a href="{{ route('partner.profits') }}" class="nav-item active">
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
