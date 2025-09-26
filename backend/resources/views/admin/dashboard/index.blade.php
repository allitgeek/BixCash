<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BixCash Admin Dashboard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f8f9fa;
            line-height: 1.6;
        }
        .header {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 {
            color: #333;
            font-size: 1.5rem;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .logout-btn {
            background: #dc3545;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9rem;
        }
        .logout-btn:hover {
            background: #c82333;
        }
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-card h3 {
            font-size: 2rem;
            color: #667eea;
            margin-bottom: 0.5rem;
        }
        .stat-card p {
            color: #666;
            font-weight: 500;
        }
        .welcome-section {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .welcome-section h2 {
            color: #333;
            margin-bottom: 1rem;
        }
        .role-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            background: #667eea;
            color: white;
            border-radius: 20px;
            font-size: 0.8rem;
            text-transform: capitalize;
        }
        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 5px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .recent-section {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .recent-section h3 {
            color: #333;
            margin-bottom: 1rem;
        }
        .user-list {
            list-style: none;
        }
        .user-list li {
            padding: 0.75rem;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .user-list li:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>BixCash Admin Panel</h1>
        <div class="user-info">
            <span>Welcome, {{ $user->name }}</span>
            <span class="role-badge">{{ $user->role->display_name ?? 'Admin' }}</span>
            <form method="POST" action="{{ route('admin.logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>

    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="welcome-section">
            <h2>Dashboard Overview</h2>
            <p>Welcome to the BixCash admin panel. Here you can manage users, content, brands, and system settings.</p>
            @if($user->adminProfile)
                <p><strong>Department:</strong> {{ $user->adminProfile->department }}</p>
                <p><strong>Last Login:</strong> {{ $user->last_login_at ? $user->last_login_at->format('M j, Y \a\t g:i A') : 'Never' }}</p>
            @endif
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>{{ $stats['total_users'] }}</h3>
                <p>Total Users</p>
            </div>
            <div class="stat-card">
                <h3>{{ $stats['admin_users'] }}</h3>
                <p>Admin Users</p>
            </div>
            <div class="stat-card">
                <h3>{{ $stats['customer_users'] }}</h3>
                <p>Customers</p>
            </div>
            <div class="stat-card">
                <h3>{{ $stats['partner_users'] }}</h3>
                <p>Partners</p>
            </div>
            <div class="stat-card">
                <h3>{{ $stats['active_brands'] }}/{{ $stats['total_brands'] }}</h3>
                <p>Active Brands</p>
            </div>
            <div class="stat-card">
                <h3>{{ $stats['active_categories'] }}/{{ $stats['total_categories'] }}</h3>
                <p>Active Categories</p>
            </div>
            <div class="stat-card">
                <h3>{{ $stats['active_slides'] }}/{{ $stats['total_slides'] }}</h3>
                <p>Active Slides</p>
            </div>
        </div>

        <div class="recent-section">
            <h3>Recent Users</h3>
            <ul class="user-list">
                @forelse($recentUsers as $recentUser)
                    <li>
                        <div>
                            <strong>{{ $recentUser->name }}</strong>
                            <br>
                            <small>{{ $recentUser->email }}</small>
                        </div>
                        <div>
                            <span class="role-badge">{{ $recentUser->role->display_name ?? 'No Role' }}</span>
                        </div>
                    </li>
                @empty
                    <li>No recent users found.</li>
                @endforelse
            </ul>
        </div>
    </div>
</body>
</html>