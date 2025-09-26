<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BixCash Admin Panel')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f8f9fa;
            line-height: 1.6;
        }

        /* Layout Structure */
        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: #2c3e50;
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
        }
        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid #34495e;
            text-align: center;
        }
        .sidebar-header h2 {
            color: #ecf0f1;
            font-size: 1.5rem;
            font-weight: 600;
        }
        .sidebar-header p {
            color: #bdc3c7;
            font-size: 0.9rem;
            margin-top: 0.25rem;
        }

        /* Navigation Menu */
        .nav-menu {
            list-style: none;
            margin-top: 1rem;
        }
        .nav-item {
            border-bottom: 1px solid #34495e;
        }
        .nav-link {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            color: #ecf0f1;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .nav-link:hover, .nav-link.active {
            background: #34495e;
            color: #3498db;
        }
        .nav-icon {
            width: 20px;
            height: 20px;
            margin-right: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 260px;
            display: flex;
            flex-direction: column;
        }

        /* Header */
        .main-header {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
        }
        .page-title {
            color: #2c3e50;
            font-size: 1.5rem;
            font-weight: 600;
        }
        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .role-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            background: #3498db;
            color: white;
            border-radius: 20px;
            font-size: 0.8rem;
            text-transform: capitalize;
        }
        .logout-btn {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9rem;
            transition: background 0.3s;
        }
        .logout-btn:hover {
            background: #c0392b;
        }

        /* Content Area */
        .content-area {
            flex: 1;
            padding: 2rem;
        }

        /* Cards and Components */
        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .card-header {
            background: #f8f9fa;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .card-title {
            color: #2c3e50;
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0;
        }
        .card-body {
            padding: 1.5rem;
        }

        /* Buttons */
        .btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            border: none;
            font-size: 0.9rem;
            transition: all 0.3s;
        }
        .btn-primary {
            background: #3498db;
            color: white;
        }
        .btn-primary:hover {
            background: #2980b9;
        }
        .btn-success {
            background: #27ae60;
            color: white;
        }
        .btn-success:hover {
            background: #229954;
        }
        .btn-warning {
            background: #f39c12;
            color: white;
        }
        .btn-warning:hover {
            background: #e67e22;
        }
        .btn-danger {
            background: #e74c3c;
            color: white;
        }
        .btn-danger:hover {
            background: #c0392b;
        }

        /* Alerts */
        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 5px;
            border: 1px solid transparent;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }
        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border-color: #ffeaa7;
        }
        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border-color: #bee5eb;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            .sidebar.mobile-open {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
            .main-header {
                padding: 1rem;
            }
            .content-area {
                padding: 1rem;
            }
        }

        /* Icons (Font Awesome alternative using CSS) */
        .icon-dashboard::before { content: "📊"; }
        .icon-users::before { content: "👥"; }
        .icon-slides::before { content: "🖼️"; }
        .icon-categories::before { content: "📂"; }
        .icon-brands::before { content: "🏪"; }
        .icon-analytics::before { content: "📈"; }
        .icon-reports::before { content: "📄"; }
        .icon-settings::before { content: "⚙️"; }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-header">
                <h2>BixCash</h2>
                <p>Admin Panel</p>
            </div>

            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <span class="nav-icon icon-dashboard"></span>
                        Dashboard
                    </a>
                </li>

                @if(auth()->user() && auth()->user()->hasPermission('manage_users'))
                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <span class="nav-icon icon-users"></span>
                        User Management
                    </a>
                </li>
                @endif

                @if(auth()->user() && auth()->user()->hasPermission('manage_content'))
                <li class="nav-item">
                    <a href="{{ route('admin.slides.index') }}" class="nav-link {{ request()->routeIs('admin.slides.*') ? 'active' : '' }}">
                        <span class="nav-icon icon-slides"></span>
                        Hero Slides
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <span class="nav-icon icon-categories"></span>
                        Categories
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.brands.index') }}" class="nav-link {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                        <span class="nav-icon icon-brands"></span>
                        Brands
                    </a>
                </li>
                @endif

                @if(auth()->user() && auth()->user()->hasPermission('view_analytics'))
                <li class="nav-item">
                    <a href="{{ route('admin.analytics') }}" class="nav-link {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                        <span class="nav-icon icon-analytics"></span>
                        Analytics
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.reports') }}" class="nav-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                        <span class="nav-icon icon-reports"></span>
                        Reports
                    </a>
                </li>
                @endif

                @if(auth()->user() && auth()->user()->hasPermission('manage_settings'))
                <li class="nav-item">
                    <a href="{{ route('admin.settings') }}" class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                        <span class="nav-icon icon-settings"></span>
                        Settings
                    </a>
                </li>
                @endif
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <header class="main-header">
                <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>

                <div class="header-actions">
                    <div class="user-info">
                        <span>{{ auth()->user()->name }}</span>
                        <span class="role-badge">{{ auth()->user()->role->display_name ?? 'Admin' }}</span>
                    </div>
                    <form method="POST" action="{{ route('admin.logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="logout-btn">Logout</button>
                    </form>
                </div>
            </header>

            <!-- Content Area -->
            <main class="content-area">
                <!-- Flash Messages -->
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if (session('warning'))
                    <div class="alert alert-warning">
                        {{ session('warning') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul style="margin: 0; padding-left: 1.5rem;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Page Content -->
                @yield('content')
            </main>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Mobile menu toggle (if needed)
        document.addEventListener('DOMContentLoaded', function() {
            // Add mobile menu functionality if needed
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>

    @stack('scripts')
</body>
</html>