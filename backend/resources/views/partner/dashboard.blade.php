<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#021c47">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Partner Dashboard - BixCash</title>
    @vite(['resources/css/app.css'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --primary: #93db4d;
            --secondary: #021c47;
            --text-dark: #1a202c;
            --text-light: #718096;
            --border: #e2e8f0;
            --bg-light: #f7fafc;
            --success: #48bb78;
            --danger: #f56565;
            --warning: #f59e0b;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: linear-gradient(135deg, var(--secondary) 0%, #0a2f5f 100%);
            min-height: 100vh;
            padding-bottom: 80px;
        }
        .header {
            background: white;
            padding: 1.5rem 1rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .business-name {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--secondary);
        }
        .partner-badge {
            background: var(--primary);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .stats-grid {
            max-width: 1200px;
            margin: 2rem auto;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            padding: 0 1rem;
        }
        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .stat-label {
            font-size: 0.875rem;
            color: var(--text-light);
            margin-bottom: 0.5rem;
        }
        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-dark);
        }
        .stat-value.success {
            color: var(--success);
        }
        .stat-value.warning {
            color: var(--warning);
        }
        .quick-actions {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .section-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: white;
            margin-bottom: 1rem;
        }
        .new-transaction-btn {
            width: 100%;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 20px;
            padding: 1.5rem;
            font-size: 1.125rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(147, 219, 77, 0.3);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }
        .new-transaction-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(147, 219, 77, 0.4);
        }
        .recent-transactions {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .transaction-list {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .transaction-item {
            padding: 1rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .transaction-item:last-child {
            border-bottom: none;
        }
        .transaction-info h4 {
            font-size: 0.95rem;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
        }
        .transaction-info p {
            font-size: 0.75rem;
            color: var(--text-light);
        }
        .transaction-amount {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--text-dark);
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
        .status-rejected {
            background: #fee2e2;
            color: #991b1b;
        }
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--text-light);
        }
        .view-all-btn {
            display: block;
            width: 100%;
            text-align: center;
            padding: 1rem;
            background: var(--bg-light);
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
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
            z-index: 100;
        }
        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0.75rem 0.5rem;
            text-decoration: none;
            color: var(--text-light);
            font-size: 0.7rem;
            transition: all 0.3s ease;
        }
        .nav-item.active {
            color: var(--primary);
        }
        .nav-icon {
            font-size: 1.5rem;
            margin-bottom: 0.25rem;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.7);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .modal.active {
            display: flex;
        }
        .modal-content {
            background: white;
            border-radius: 20px;
            max-width: 500px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
        }
        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .modal-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-dark);
        }
        .modal-close {
            font-size: 1.5rem;
            color: var(--text-light);
            cursor: pointer;
            background: none;
            border: none;
        }
        .modal-body {
            padding: 1.5rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }
        .form-input {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid var(--border);
            border-radius: 12px;
            font-size: 1rem;
        }
        .form-input:focus {
            outline: none;
            border-color: var(--primary);
        }
        .btn-primary {
            width: 100%;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 1rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-primary:disabled {
            background: var(--border);
            cursor: not-allowed;
        }
        .alert {
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1rem;
            font-size: 0.875rem;
        }
        .alert-success {
            background: #d1fae5;
            color: #065f46;
        }
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
        }
        .customer-info-box {
            background: var(--bg-light);
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        .customer-info-box h4 {
            font-size: 1rem;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }
        .customer-info-box p {
            font-size: 0.875rem;
            color: var(--text-light);
            margin: 0.25rem 0;
        }
        @media (max-width: 768px) {
            .stats-grid {
                gap: 0.75rem;
            }
            .stat-card {
                padding: 1rem;
            }
            .stat-value {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <div>
                <div class="business-name">{{ $partnerProfile->business_name }}</div>
                <div style="font-size: 0.875rem; color: var(--text-light); margin-top: 0.25rem;">
                    {{ $partnerProfile->business_type }}
                </div>
            </div>
            <div class="partner-badge">Partner</div>
        </div>
    </div>
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total Revenue</div>
            <div class="stat-value success">Rs {{ number_format($stats['total_revenue'], 0) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Your Profit</div>
            <div class="stat-value success">Rs {{ number_format($stats['total_profit'], 0) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Transactions</div>
            <div class="stat-value">{{ $stats['total_transactions'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Pending</div>
            <div class="stat-value warning">{{ $stats['pending_confirmations'] }}</div>
        </div>
    </div>
    <div class="quick-actions">
        <button class="new-transaction-btn" onclick="openTransactionModal()">
            <span style="font-size: 1.5rem;">+</span>
            New Transaction
        </button>
        @if($nextBatchDate)
        <div style="text-align: center; margin-top: 1rem; color: white; font-size: 0.875rem;">
            Next Profit Distribution: {{ $nextBatchDate->format('M d, Y') }}
        </div>
        @endif
    </div>
    <div class="recent-transactions">
        <h3 class="section-title">Recent Transactions</h3>
        <div class="transaction-list">
            @forelse($recentTransactions as $transaction)
            <div class="transaction-item">
                <div class="transaction-info">
                    <h4>{{ $transaction->customer->name }}</h4>
                    <p>{{ $transaction->transaction_code }} • {{ $transaction->created_at->format('M d, h:i A') }}</p>
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
                <p style="font-size: 0.75rem; margin-top: 0.5rem;">Create your first transaction above!</p>
            </div>
            @endforelse
            @if($recentTransactions->count() > 0)
            <a href="{{ route('partner.transactions') }}" class="view-all-btn">View All Transactions</a>
            @endif
        </div>
    </div>
    <div class="bottom-nav">
        <a href="{{ route('partner.dashboard') }}" class="nav-item active">
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
        <a href="{{ route('partner.profile') }}" class="nav-item">
            <div class="nav-icon">👤</div>
            <div>Profile</div>
        </a>
    </div>
    <div class="modal" id="transactionModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">New Transaction</h3>
                <button class="modal-close" onclick="closeTransactionModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div id="alertContainer"></div>
                <div id="step1" class="step-content">
                    <div class="form-group">
                        <label class="form-label">Customer Phone Number</label>
                        <div style="display: flex; gap: 0.5rem;">
                            <div style="padding: 0.875rem 1rem; background: var(--bg-light); border: 2px solid var(--border); border-radius: 12px; font-weight: 600;">+92</div>
                            <input type="text" id="customerPhone" class="form-input" placeholder="3001234567" maxlength="10" pattern="[0-9]{10}">
                        </div>
                    </div>
                    <button class="btn-primary" onclick="searchCustomer()">Search Customer</button>
                </div>
                <div id="step2" class="step-content" style="display: none;">
                    <div class="customer-info-box" id="customerInfoBox"></div>
                    <div class="form-group">
                        <label class="form-label">Invoice Amount (Rs)</label>
                        <input type="number" id="invoiceAmount" class="form-input" placeholder="0" min="1" step="0.01">
                    </div>
                    <button class="btn-primary" onclick="createTransaction()">Create Transaction</button>
                    <button class="btn-primary" onclick="backToStep1()" style="background: var(--border); color: var(--text-dark); margin-top: 0.5rem;">Back</button>
                </div>
                <div id="step3" class="step-content" style="display: none; text-align: center;">
                    <div style="font-size: 4rem; margin: 1rem 0;">✅</div>
                    <h3 style="color: var(--success); margin-bottom: 1rem;">Transaction Created!</h3>
                    <div id="transactionSuccessInfo"></div>
                    <button class="btn-primary" onclick="closeTransactionModal(); location.reload();" style="margin-top: 1.5rem;">Done</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        let selectedCustomer = null;
        function openTransactionModal() {
            document.getElementById('transactionModal').classList.add('active');
            resetModal();
        }
        function closeTransactionModal() {
            document.getElementById('transactionModal').classList.remove('active');
            resetModal();
        }
        function resetModal() {
            showStep(1);
            document.getElementById('customerPhone').value = '';
            document.getElementById('invoiceAmount').value = '';
            document.getElementById('alertContainer').innerHTML = '';
            selectedCustomer = null;
        }
        function showStep(step) {
            document.getElementById('step1').style.display = step === 1 ? 'block' : 'none';
            document.getElementById('step2').style.display = step === 2 ? 'block' : 'none';
            document.getElementById('step3').style.display = step === 3 ? 'block' : 'none';
        }
        function backToStep1() {
            showStep(1);
            selectedCustomer = null;
        }
        function showAlert(message, type = 'error') {
            const alertHtml = `<div class="alert alert-${type}">${message}</div>`;
            document.getElementById('alertContainer').innerHTML = alertHtml;
        }
        async function searchCustomer() {
            const phone = document.getElementById('customerPhone').value.trim();
            if (!/^[0-9]{10}$/.test(phone)) {
                showAlert('Please enter a valid 10-digit phone number');
                return;
            }
            try {
                const response = await fetch('{{ route('partner.search-customer') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ phone })
                });
                const data = await response.json();
                if (data.success) {
                    selectedCustomer = data.customer;
                    displayCustomerInfo(data.customer);
                    showStep(2);
                    document.getElementById('alertContainer').innerHTML = '';
                } else {
                    showAlert(data.message);
                }
            } catch (error) {
                showAlert('Network error. Please try again.');
            }
        }
        function displayCustomerInfo(customer) {
            const html = `
                <h4>${customer.name}</h4>
                <p>Phone: ${customer.phone}</p>
                <p>Total Purchases: ${customer.stats.total_purchases}</p>
                <p>Total Spent: Rs ${parseFloat(customer.stats.total_spent || 0).toFixed(0)}</p>
            `;
            document.getElementById('customerInfoBox').innerHTML = html;
        }
        async function createTransaction() {
            const amount = document.getElementById('invoiceAmount').value;
            if (!amount || amount <= 0) {
                showAlert('Please enter a valid invoice amount');
                return;
            }
            if (!selectedCustomer) {
                showAlert('Customer not selected');
                return;
            }
            try {
                const response = await fetch('{{ route('partner.create-transaction') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        customer_id: selectedCustomer.id,
                        invoice_amount: amount
                    })
                });
                const data = await response.json();
                if (data.success) {
                    displaySuccess(data.transaction);
                    showStep(3);
                } else {
                    showAlert(data.message);
                }
            } catch (error) {
                showAlert('Network error. Please try again.');
            }
        }
        function displaySuccess(transaction) {
            const html = `
                <p style="color: var(--text-dark); margin-bottom: 0.5rem;">Transaction Code</p>
                <p style="font-size: 1.5rem; font-weight: 700; color: var(--primary); margin-bottom: 1rem;">${transaction.transaction_code}</p>
                <p style="color: var(--text-dark); margin-bottom: 0.5rem;">Amount: Rs ${transaction.invoice_amount}</p>
                <p style="color: var(--text-light); font-size: 0.875rem; margin-top: 1rem;">Customer: ${transaction.customer_name}</p>
                <p style="color: var(--warning); font-size: 0.875rem; margin-top: 0.5rem;">⏱️ Customer has 60 seconds to confirm</p>
            `;
            document.getElementById('transactionSuccessInfo').innerHTML = html;
        }
        document.getElementById('customerPhone')?.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '').substring(0, 10);
        });
    </script>
</body>
</html>
