<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard - BixCash</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite(['resources/css/app.css', 'resources/css/dashboard.css'])
</head>
<body class="dashboard-body">

    <div class="dashboard-container">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <h1 class="dashboard-title">
                <span class="title-green">Customer</span>
                <span class="title-blue">Dashboard</span>
            </h1>
        </div>

        <!-- Main Dashboard Content -->
        <div class="dashboard-main">

            <!-- Left Panel: Main Dashboard -->
            <div class="dashboard-left">

                <!-- User Profile Section -->
                <div class="user-profile-section">
                    <div class="user-avatar-container">
                        <div class="user-avatar">
                            <div class="avatar-circle">
                                <svg class="avatar-icon" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="user-rating">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="star {{ $i <= $dashboardData['user']['rating'] ? 'filled' : '' }}">★</span>
                            @endfor
                        </div>
                    </div>

                    <!-- Chat/Activity Elements -->
                    <div class="activity-elements">
                        <div class="chat-bubble chat-bubble-1">
                            <div class="dots">
                                <span></span><span></span><span></span>
                            </div>
                        </div>
                        <div class="activity-list">
                            <div class="activity-item">
                                <span class="activity-dot"></span>
                                <span class="activity-text">Recent purchase</span>
                            </div>
                            <div class="activity-item">
                                <span class="activity-dot"></span>
                                <span class="activity-text">Reward earned</span>
                            </div>
                            <div class="activity-item">
                                <span class="activity-dot"></span>
                                <span class="activity-text">Cash withdrawal</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="charts-section">
                    <!-- Line Chart -->
                    <div class="chart-container chart-line">
                        <canvas id="earningsChart"></canvas>
                    </div>

                    <!-- Bar Chart -->
                    <div class="chart-container chart-bar">
                        <canvas id="transactionsChart"></canvas>
                    </div>

                    <!-- Calendar Widget -->
                    <div class="chart-container chart-calendar">
                        <div class="calendar-widget">
                            <div class="calendar-header">January 2024</div>
                            <div class="calendar-grid">
                                @for($day = 1; $day <= 31; $day++)
                                    <div class="calendar-day {{ $day == 15 ? 'active' : '' }}">{{ $day }}</div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Panel: Action Cards -->
            <div class="dashboard-right">

                <!-- Grow Your Money Card -->
                <div class="action-card grow-money-card">
                    <div class="card-content">
                        <h3>Grow your Money</h3>
                        <div class="card-coins">
                            <div class="coin coin-1">💰</div>
                            <div class="coin coin-2">💰</div>
                            <div class="sparkle sparkle-1">✨</div>
                            <div class="sparkle sparkle-2">✨</div>
                            <div class="sparkle sparkle-3">✨</div>
                        </div>
                    </div>
                </div>

                <!-- Rewards Card -->
                <div class="action-card rewards-card">
                    <div class="card-content">
                        <div class="card-icon">🏆</div>
                        <h3>Rewards</h3>
                        <div class="rewards-sparkles">
                            <div class="sparkle sparkle-1">✨</div>
                            <div class="sparkle sparkle-2">✨</div>
                        </div>
                    </div>
                </div>

                <!-- Shop & Earn Card -->
                <div class="action-card shop-earn-card">
                    <div class="card-content">
                        <div class="card-icon">🛍️</div>
                        <h3>Shop & Earn</h3>
                        <div class="shop-sparkles">
                            <div class="sparkle sparkle-1">✨</div>
                            <div class="sparkle sparkle-2">✨</div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <!-- Bottom Navigation -->
        <div class="bottom-navigation">
            <div class="nav-item">
                <div class="nav-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </div>
                <span class="nav-label">CASH BACK</span>
            </div>

            <div class="nav-item">
                <div class="nav-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M21 18v1c0 1.1-.9 2-2 2H5c-1.1 0-2-.9-2-2V5c0-1.1.9-2 2-2h14c1.1 0 2 .9 2 2v13zM12 7c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                    </svg>
                </div>
                <span class="nav-label">WALLET</span>
            </div>

            <div class="nav-item">
                <div class="nav-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M7 4V2C7 1.45 7.45 1 8 1H16C16.55 1 17 1.45 17 2V4H20C20.55 4 21 4.45 21 5S20.55 6 20 6H19V19C19 20.1 18.1 21 17 21H7C5.9 21 5 20.1 5 19V6H4C3.45 6 3 5.55 3 5S3.45 4 4 4H7Z"/>
                    </svg>
                </div>
                <span class="nav-label">TRANSACTION</span>
            </div>

            <div class="nav-item">
                <div class="nav-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                    </svg>
                </div>
                <span class="nav-label">RECEIPT</span>
            </div>

            <div class="nav-item">
                <div class="nav-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12,1L8,5H11V14H13V5H16M18,23H6C4.89,23 4,22.1 4,21V9C4,7.89 4.89,7 6,7H7V9H6V21H18V9H17V7H18C19.1,7 20,7.89 20,9V21C20,22.1 19.1,23 18,23Z"/>
                    </svg>
                </div>
                <span class="nav-label">WITHDRAWAL</span>
            </div>
        </div>

    </div>

    <!-- Charts JavaScript -->
    <script>
        // Sample data from controller
        const chartData = @json($dashboardData['chartData']);

        // Line Chart for Earnings
        const earningsCtx = document.getElementById('earningsChart').getContext('2d');
        new Chart(earningsCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    data: chartData.earnings,
                    borderColor: '#76d37a',
                    backgroundColor: 'rgba(118, 211, 122, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    x: { display: false },
                    y: { display: false }
                }
            }
        });

        // Bar Chart for Transactions
        const transactionsCtx = document.getElementById('transactionsChart').getContext('2d');
        new Chart(transactionsCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    data: chartData.transactions,
                    backgroundColor: '#76d37a',
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    x: { display: false },
                    y: { display: false }
                }
            }
        });
    </script>

</body>
</html>