@extends('layouts.admin')

@section('title', 'Admin Dashboard - BixCash')
@section('page-title', 'Dashboard')

@section('content')
    <div class="welcome-section card">
        <div class="card-body">
            <h2>Dashboard Overview</h2>
            <p>Welcome to the BixCash admin panel. Here you can manage users, content, brands, and system settings.</p>
            @if($user->adminProfile)
                <p><strong>Department:</strong> {{ $user->adminProfile->department }}</p>
                <p><strong>Last Login:</strong> {{ $user->last_login_at ? $user->last_login_at->format('M j, Y \\a\\t g:i A') : 'Never' }}</p>
            @endif
        </div>
    </div>

    <div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <div class="stat-card card">
            <div class="card-body" style="text-align: center;">
                <h3 style="font-size: 2rem; color: #3498db; margin-bottom: 0.5rem;">{{ $stats['total_users'] }}</h3>
                <p style="color: #666; font-weight: 500;">Total Users</p>
            </div>
        </div>
        <div class="stat-card card">
            <div class="card-body" style="text-align: center;">
                <h3 style="font-size: 2rem; color: #3498db; margin-bottom: 0.5rem;">{{ $stats['admin_users'] }}</h3>
                <p style="color: #666; font-weight: 500;">Admin Users</p>
            </div>
        </div>
        <div class="stat-card card">
            <div class="card-body" style="text-align: center;">
                <h3 style="font-size: 2rem; color: #3498db; margin-bottom: 0.5rem;">{{ $stats['customer_users'] }}</h3>
                <p style="color: #666; font-weight: 500;">Customers</p>
            </div>
        </div>
        <div class="stat-card card">
            <div class="card-body" style="text-align: center;">
                <h3 style="font-size: 2rem; color: #3498db; margin-bottom: 0.5rem;">{{ $stats['partner_users'] }}</h3>
                <p style="color: #666; font-weight: 500;">Partners</p>
            </div>
        </div>
        <div class="stat-card card">
            <div class="card-body" style="text-align: center;">
                <h3 style="font-size: 2rem; color: #3498db; margin-bottom: 0.5rem;">{{ $stats['active_brands'] }}/{{ $stats['total_brands'] }}</h3>
                <p style="color: #666; font-weight: 500;">Active Brands</p>
            </div>
        </div>
        <div class="stat-card card">
            <div class="card-body" style="text-align: center;">
                <h3 style="font-size: 2rem; color: #3498db; margin-bottom: 0.5rem;">{{ $stats['active_categories'] }}/{{ $stats['total_categories'] }}</h3>
                <p style="color: #666; font-weight: 500;">Active Categories</p>
            </div>
        </div>
        <div class="stat-card card">
            <div class="card-body" style="text-align: center;">
                <h3 style="font-size: 2rem; color: #3498db; margin-bottom: 0.5rem;">{{ $stats['active_slides'] }}/{{ $stats['total_slides'] }}</h3>
                <p style="color: #666; font-weight: 500;">Active Slides</p>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Recent Users</h3>
        </div>
        <div class="card-body">
            <ul style="list-style: none;">
                @forelse($recentUsers as $recentUser)
                    <li style="padding: 0.75rem; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <strong>{{ $recentUser->name }}</strong>
                            <br>
                            <small style="color: #666;">{{ $recentUser->email }}</small>
                        </div>
                        <div>
                            <span class="role-badge">{{ $recentUser->role->display_name ?? 'No Role' }}</span>
                        </div>
                    </li>
                @empty
                    <li style="padding: 1rem; text-align: center; color: #666;">No recent users found.</li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection