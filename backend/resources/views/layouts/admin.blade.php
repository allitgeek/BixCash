<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BixCash Admin Panel')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* BixCash Brand Colors - CSS Variables */
        :root {
            --bix-dark-blue: #021c47;
            --bix-navy: #021c47;
            --bix-green: #76d37a;
            --bix-light-green: #93db4d;
            --bix-white: #ffffff;
            --bix-light-gray-1: #f8f8f8;
            --bix-light-gray-2: #eef2f5;
            --bix-medium-gray: #707070;
            --bix-black: #000000;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--bix-light-gray-2);
            line-height: 1.6;
            color: var(--bix-dark-blue);
        }

        /* Layout Structure */
        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: var(--bix-dark-blue);
            color: var(--bix-white);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
        }
        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }
        .sidebar-header h2 {
            color: var(--bix-white);
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }
        .sidebar-header p {
            color: rgba(255,255,255,0.7);
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Navigation Menu */
        .nav-menu {
            list-style: none;
            margin-top: 1rem;
        }
        .nav-item {
            margin: 0.25rem 0;
        }
        .nav-link {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: var(--bix-light-green);
            transform: translateX(5px);
        }
        .nav-link.active {
            background: var(--bix-green);
            color: var(--bix-dark-blue);
            font-weight: 600;
        }
        .nav-icon {
            width: 20px;
            height: 20px;
            margin-right: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 260px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Header */
        .main-header {
            background: var(--bix-white);
            box-shadow: 0 2px 8px rgba(2, 28, 71, 0.1);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
            border-bottom: 2px solid var(--bix-light-gray-2);
        }
        .page-title {
            color: var(--bix-dark-blue);
            font-size: 1.5rem;
            font-weight: 700;
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
            color: var(--bix-dark-blue);
            font-weight: 600;
        }
        .role-badge {
            display: inline-block;
            padding: 0.35rem 0.85rem;
            background: var(--bix-green);
            color: var(--bix-dark-blue);
            border-radius: 25px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .logout-btn {
            background: var(--bix-dark-blue);
            color: var(--bix-white);
            border: none;
            padding: 0.65rem 1.25rem;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .logout-btn:hover {
            background: var(--bix-green);
            color: var(--bix-dark-blue);
            transform: translateY(-2px);
        }

        /* Content Area */
        .content-area {
            flex: 1;
            padding: 2rem;
            background: var(--bix-light-gray-2);
        }

        /* Cards and Components */
        .card {
            background: var(--bix-white);
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(2, 28, 71, 0.08);
            overflow: hidden;
            border: 1px solid rgba(2, 28, 71, 0.05);
        }
        .card-header {
            background: var(--bix-light-gray-1);
            padding: 1.25rem 1.5rem;
            border-bottom: 2px solid var(--bix-light-gray-2);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .card-title {
            color: var(--bix-dark-blue);
            font-size: 1.2rem;
            font-weight: 700;
            margin: 0;
        }
        .card-body {
            padding: 1.5rem;
        }

        /* Buttons - BixCash Theme */
        .btn {
            display: inline-block;
            padding: 0.65rem 1.25rem;
            border-radius: 8px;
            text-decoration: none;
            cursor: pointer;
            border: none;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.3s ease;
            margin: 0.25rem;
        }
        .btn-primary {
            background: var(--bix-green);
            color: var(--bix-dark-blue);
        }
        .btn-primary:hover {
            background: var(--bix-light-green);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(118, 211, 122, 0.3);
        }
        .btn-success {
            background: var(--bix-green);
            color: var(--bix-dark-blue);
        }
        .btn-success:hover {
            background: var(--bix-light-green);
            transform: translateY(-2px);
        }
        .btn-warning {
            background: #f39c12;
            color: var(--bix-white);
        }
        .btn-warning:hover {
            background: #e67e22;
            transform: translateY(-2px);
        }
        .btn-danger {
            background: #e74c3c;
            color: var(--bix-white);
        }
        .btn-danger:hover {
            background: #c0392b;
            transform: translateY(-2px);
        }
        .btn-secondary {
            background: var(--bix-medium-gray);
            color: var(--bix-white);
        }
        .btn-secondary:hover {
            background: var(--bix-dark-blue);
            transform: translateY(-2px);
        }
        .btn-info {
            background: var(--bix-dark-blue);
            color: var(--bix-white);
        }
        .btn-info:hover {
            background: var(--bix-green);
            color: var(--bix-dark-blue);
            transform: translateY(-2px);
        }

        /* Alerts - BixCash Theme */
        .alert {
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            border-radius: 10px;
            border: 1px solid transparent;
            font-weight: 500;
        }
        .alert-success {
            background: rgba(118, 211, 122, 0.15);
            color: var(--bix-dark-blue);
            border-color: var(--bix-green);
        }
        .alert-danger {
            background: rgba(231, 76, 60, 0.15);
            color: #721c24;
            border-color: #e74c3c;
        }
        .alert-warning {
            background: rgba(243, 156, 18, 0.15);
            color: #856404;
            border-color: #f39c12;
        }
        .alert-info {
            background: rgba(2, 28, 71, 0.15);
            color: var(--bix-dark-blue);
            border-color: var(--bix-dark-blue);
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--bix-dark-blue);
            font-weight: 600;
        }
        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid var(--bix-light-gray-2);
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: var(--bix-white);
        }
        .form-control:focus {
            outline: none;
            border-color: var(--bix-green);
            box-shadow: 0 0 0 3px rgba(118, 211, 122, 0.1);
        }
        .form-control.is-invalid {
            border-color: #e74c3c;
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

        /* Icons - BixCash Style */
        .icon-dashboard::before { content: "📊"; }
        .icon-users::before { content: "👥"; }
        .icon-slides::before { content: "🖼️"; }
        .icon-categories::before { content: "📂"; }
        .icon-brands::before { content: "🏪"; }
        .icon-promotions::before { content: "🎁"; }
        .icon-analytics::before { content: "📈"; }
        .icon-reports::before { content: "📄"; }
        .icon-settings::before { content: "⚙️"; }

        /* Submenu Styles */
        .nav-item.has-submenu > .nav-link {
            cursor: pointer;
            position: relative;
        }
        .nav-item.has-submenu > .nav-link::after {
            content: "▼";
            position: absolute;
            right: 1.5rem;
            font-size: 0.7rem;
            transition: transform 0.3s ease;
        }
        .nav-item.has-submenu.open > .nav-link::after {
            transform: rotate(180deg);
        }
        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            background: rgba(0, 0, 0, 0.2);
        }
        .nav-item.has-submenu.open .submenu {
            max-height: 500px;
        }
        .submenu .nav-link {
            padding: 0.75rem 1.5rem 0.75rem 3rem;
            font-size: 0.9rem;
        }

        /* Table Styles */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }
        .table th,
        .table td {
            padding: 0.75rem;
            border-bottom: 1px solid var(--bix-light-gray-2);
            text-align: left;
        }
        .table th {
            background: var(--bix-light-gray-1);
            font-weight: 600;
            color: var(--bix-dark-blue);
        }

        /* Badge Styles */
        .badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .badge-success {
            background: var(--bix-green);
            color: var(--bix-dark-blue);
        }
        .badge-secondary {
            background: var(--bix-medium-gray);
            color: var(--bix-white);
        }
        .badge-warning {
            background: #f39c12;
            color: var(--bix-white);
        }
        .badge-primary {
            background: var(--bix-dark-blue);
            color: var(--bix-white);
        }

        /* Layout Utilities */
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -0.75rem;
        }
        .col-md-4,
        .col-md-6,
        .col-md-8,
        .col-md-12 {
            padding: 0 0.75rem;
            box-sizing: border-box;
        }
        .col-md-4 { flex: 0 0 33.333333%; max-width: 33.333333%; }
        .col-md-6 { flex: 0 0 50%; max-width: 50%; }
        .col-md-8 { flex: 0 0 66.666667%; max-width: 66.666667%; }
        .col-md-12 { flex: 0 0 100%; max-width: 100%; }

        /* Form Actions */
        .form-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
            padding-top: 1.5rem;
            margin-top: 1.5rem;
            border-top: 2px solid var(--bix-light-gray-2);
        }

        /* Text Utilities */
        .text-center { text-align: center; }
        .text-muted { color: var(--bix-medium-gray); }
        .text-sm { font-size: 0.875rem; }
        .small { font-size: 0.875rem; }

        /* Spacing Utilities */
        .mb-1 { margin-bottom: 0.25rem; }
        .mb-2 { margin-bottom: 0.5rem; }
        .mb-3 { margin-bottom: 1rem; }
        .mb-4 { margin-bottom: 1.5rem; }
        .mt-2 { margin-top: 0.5rem; }
        .mt-3 { margin-top: 1rem; }
        .mt-4 { margin-top: 1.5rem; }
        .py-5 { padding: 3rem 0; }
        .d-block { display: block; }
        .d-grid { display: grid; }
        .gap-2 { gap: 0.5rem; }

        /* Button Sizes */
        .btn-sm {
            padding: 0.45rem 0.85rem;
            font-size: 0.8rem;
        }
        .w-100 { width: 100%; }

        /* Invalid Feedback */
        .invalid-feedback {
            display: block;
            color: #e74c3c;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            font-weight: 500;
        }

        /* Additional Form Elements */
        .form-check {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        .form-check-input {
            margin-right: 0.5rem;
            width: 16px;
            height: 16px;
        }
        .form-check-label {
            margin-bottom: 0;
            font-weight: 500;
        }

        /* List Styles */
        .list-unstyled {
            list-style: none;
            padding-left: 0;
        }

        /* Button Groups */
        .btn + .btn {
            margin-left: 0.5rem;
        }

        /* Mobile Responsive Columns */
        @media (max-width: 768px) {
            .col-md-4,
            .col-md-6,
            .col-md-8,
            .col-md-12 {
                flex: 0 0 100%;
                max-width: 100%;
                margin-bottom: 1rem;
            }
            .form-actions {
                flex-direction: column;
                align-items: stretch;
            }
            .form-actions .btn {
                width: 100%;
                margin: 0.25rem 0;
            }
        }
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
                <li class="nav-item">
                    <a href="{{ route('admin.promotions.index') }}" class="nav-link {{ request()->routeIs('admin.promotions.*') ? 'active' : '' }}">
                        <span class="nav-icon icon-promotions"></span>
                        Promotions
                    </a>
                </li>
                @endif

                <li class="nav-item">
                    <a href="{{ route('admin.queries.index') }}" class="nav-link {{ request()->routeIs('admin.queries.*') ? 'active' : '' }}">
                        <span class="nav-icon">📧</span>
                        Customer Queries
                    </a>
                </li>

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
                <li class="nav-item has-submenu {{ request()->routeIs('admin.settings*') ? 'open' : '' }}">
                    <a class="nav-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}" onclick="toggleSubmenu(this)">
                        <span class="nav-icon icon-settings"></span>
                        Settings
                    </a>
                    <ul class="submenu">
                        <li class="nav-item">
                            <a href="{{ route('admin.settings') }}" class="nav-link {{ request()->routeIs('admin.settings') && !request()->routeIs('admin.settings.email') ? 'active' : '' }}">
                                General Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.settings.email') }}" class="nav-link {{ request()->routeIs('admin.settings.email') ? 'active' : '' }}">
                                Email Settings
                            </a>
                        </li>
                    </ul>
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
        // Submenu toggle function
        function toggleSubmenu(element) {
            const navItem = element.closest('.nav-item.has-submenu');
            navItem.classList.toggle('open');
        }

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