<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase History - BixCash</title>
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
        }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif; background: var(--bg-light); padding-bottom: 80px; }
        .header { background: var(--secondary); color: white; padding: 1.5rem 1rem; }
        .header-content { max-width: 1200px; margin: 0 auto; display: flex; align-items: center; gap: 1rem; }
        .back-btn { color: white; text-decoration: none; font-size: 1.5rem; }
        .header-title { font-size: 1.25rem; font-weight: 700; }
        .content { max-width: 1200px; margin: 0 auto; padding: 1.5rem 1rem; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
        .stat-card { background: white; border-radius: 16px; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .stat-label { font-size: 0.875rem; color: var(--text-light); margin-bottom: 0.5rem; }
        .stat-value { font-size: 1.75rem; font-weight: 700; color: var(--text-dark); }
        .stat-value.primary { color: var(--primary); }
        .section { background: white; border-radius: 16px; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .section-title { font-size: 1.125rem; font-weight: 700; margin-bottom: 1rem; color: var(--text-dark); }
        .filters { display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
        .filter-btn { padding: 0.5rem 1rem; border-radius: 12px; border: 2px solid var(--border); background: white; cursor: pointer; font-weight: 600; transition: all 0.3s ease; }
        .filter-btn.active { background: var(--primary); color: white; border-color: var(--primary); }
        .purchase-card { background: white; border: 2px solid var(--border); border-radius: 16px; padding: 1.25rem; margin-bottom: 1rem; transition: all 0.3s ease; }
        .purchase-card:hover { border-color: var(--primary); box-shadow: 0 4px 12px rgba(147, 219, 77, 0.15); }
        .purchase-header { display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem; }
        .brand-info { display: flex; align-items: center; gap: 1rem; }
        .brand-logo { width: 50px; height: 50px; border-radius: 12px; object-fit: cover; background: var(--bg-light); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: 700; color: var(--primary); }
        .brand-details { flex: 1; }
        .brand-name { font-size: 1rem; font-weight: 700; color: var(--text-dark); margin-bottom: 0.25rem; }
        .order-id { font-size: 0.75rem; color: var(--text-light); }
        .purchase-amount { text-align: right; }
        .amount { font-size: 1.25rem; font-weight: 700; color: var(--text-dark); }
        .cashback { font-size: 0.875rem; color: var(--primary); font-weight: 600; margin-top: 0.25rem; }
        .purchase-details { display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 1rem; padding-top: 1rem; border-top: 1px solid var(--border); }
        .detail-item { }
        .detail-label { font-size: 0.75rem; color: var(--text-light); margin-bottom: 0.25rem; }
        .detail-value { font-size: 0.875rem; font-weight: 600; color: var(--text-dark); }
        .status-badge { padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.75rem; font-weight: 600; display: inline-block; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-confirmed { background: #d1fae5; color: #065f46; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
        .empty-state { text-align: center; padding: 4rem 2rem; }
        .empty-icon { font-size: 4rem; margin-bottom: 1rem; opacity: 0.3; }
        .empty-title { font-size: 1.25rem; font-weight: 700; color: var(--text-dark); margin-bottom: 0.5rem; }
        .empty-text { color: var(--text-light); }
        .bottom-nav { position: fixed; bottom: 0; left: 0; right: 0; background: white; border-top: 1px solid var(--border); padding: 0.75rem 0; }
        .nav-items { display: flex; justify-content: space-around; max-width: 600px; margin: 0 auto; }
        .nav-item { display: flex; flex-direction: column; align-items: center; gap: 0.25rem; color: var(--text-light); text-decoration: none; padding: 0.5rem 1rem; }
        .nav-item.active { color: var(--primary); }
        .nav-item svg { width: 24px; height: 24px; }
        .nav-item span { font-size: 0.7rem; font-weight: 600; }
        .pagination { display: flex; justify-content: center; gap: 0.5rem; margin-top: 1.5rem; }
        .pagination a, .pagination span { padding: 0.5rem 0.75rem; border-radius: 8px; border: 2px solid var(--border); background: white; text-decoration: none; color: var(--text-dark); font-weight: 600; }
        .pagination .active { background: var(--primary); color: white; border-color: var(--primary); }

        @media (max-width: 768px) {
            .stats-grid { grid-template-columns: 1fr 1fr; }
            .purchase-header { flex-direction: column; gap: 1rem; }
            .purchase-amount { text-align: left; }
            .brand-logo { width: 40px; height: 40px; font-size: 1.25rem; }
            .brand-name { font-size: 0.9rem; }
            .amount { font-size: 1.1rem; }
        }
    </style>
</head>
<body>

    <header class="header">
        <div class="header-content">
            <a href="{{ route('customer.dashboard') }}" class="back-btn">←</a>
            <h1 class="header-title">Purchase History</h1>
        </div>
    </header>

    <main class="content">

        <!-- Stats Overview -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Purchases</div>
                <div class="stat-value">{{ $purchases->total() }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Spent</div>
                <div class="stat-value">Rs. {{ number_format($totalSpent, 0) }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Cashback</div>
                <div class="stat-value primary">Rs. {{ number_format($totalCashback, 0) }}</div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters">
            <button class="filter-btn active" onclick="filterPurchases('all')">All</button>
            <button class="filter-btn" onclick="filterPurchases('confirmed')">Confirmed</button>
            <button class="filter-btn" onclick="filterPurchases('pending')">Pending</button>
            <button class="filter-btn" onclick="filterPurchases('cancelled')">Cancelled</button>
        </div>

        <!-- Purchase List -->
        <div class="section">
            @if($purchases->count() > 0)
                <div id="purchase-list">
                    @foreach($purchases as $purchase)
                    <div class="purchase-card" data-status="{{ $purchase->status }}">
                        <div class="purchase-header">
                            <div class="brand-info">
                                <div class="brand-logo">
                                    @if($purchase->brand && $purchase->brand->logo)
                                        <img src="{{ asset('storage/' . $purchase->brand->logo) }}" alt="{{ $purchase->brand->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 12px;">
                                    @else
                                        {{ $purchase->brand ? strtoupper(substr($purchase->brand->name, 0, 1)) : '?' }}
                                    @endif
                                </div>
                                <div class="brand-details">
                                    <div class="brand-name">{{ $purchase->brand->name ?? 'Unknown Brand' }}</div>
                                    <div class="order-id">Order #{{ $purchase->order_id }}</div>
                                </div>
                            </div>
                            <div class="purchase-amount">
                                <div class="amount">Rs. {{ number_format($purchase->amount, 0) }}</div>
                                @if($purchase->cashback_amount > 0)
                                    <div class="cashback">+Rs. {{ number_format($purchase->cashback_amount, 0) }} cashback</div>
                                @endif
                            </div>
                        </div>
                        <div class="purchase-details">
                            <div class="detail-item">
                                <div class="detail-label">Purchase Date</div>
                                <div class="detail-value">{{ $purchase->purchase_date->format('M d, Y') }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Cashback Rate</div>
                                <div class="detail-value">{{ number_format($purchase->cashback_percentage, 1) }}%</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Status</div>
                                <div class="detail-value">
                                    <span class="status-badge status-{{ $purchase->status }}">{{ ucfirst($purchase->status) }}</span>
                                </div>
                            </div>
                        </div>
                        @if($purchase->description)
                        <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border);">
                            <div class="detail-label">Description</div>
                            <div class="detail-value">{{ $purchase->description }}</div>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div style="margin-top: 1.5rem;">
                    {{ $purchases->links() }}
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">🛍️</div>
                    <div class="empty-title">No Purchases Yet</div>
                    <div class="empty-text">Start shopping with our partner brands to earn cashback!</div>
                </div>
            @endif
        </div>

    </main>

    <nav class="bottom-nav">
        <div class="nav-items">
            <a href="{{ route('customer.dashboard') }}" class="nav-item">
                <svg fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                <span>Home</span>
            </a>
            <a href="{{ route('customer.wallet') }}" class="nav-item">
                <svg fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path></svg>
                <span>Wallet</span>
            </a>
            <a href="{{ route('customer.purchases') }}" class="nav-item active">
                <svg fill="currentColor" viewBox="0 0 20 20"><path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path></svg>
                <span>Purchases</span>
            </a>
            <a href="{{ route('customer.profile') }}" class="nav-item">
                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                <span>Profile</span>
            </a>
        </div>
    </nav>

    <script>
        function filterPurchases(status) {
            // Update active button
            document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');

            // Filter cards
            const cards = document.querySelectorAll('.purchase-card');
            cards.forEach(card => {
                if (status === 'all' || card.dataset.status === status) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    </script>

</body>
</html>
