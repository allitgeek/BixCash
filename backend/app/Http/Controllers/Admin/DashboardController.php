<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get dashboard statistics
        $stats = [
            'total_users' => User::count(),
            'admin_users' => User::whereHas('role', function ($q) {
                $q->whereIn('name', ['super_admin', 'admin', 'moderator']);
            })->count(),
            'customer_users' => User::whereHas('role', function ($q) {
                $q->where('name', 'customer');
            })->count(),
            'partner_users' => User::whereHas('role', function ($q) {
                $q->where('name', 'partner');
            })->count(),
            'total_brands' => Brand::count(),
            'active_brands' => Brand::active()->count(),
            'total_categories' => Category::count(),
            'active_categories' => Category::active()->count(),
            'total_slides' => Slide::count(),
            'active_slides' => Slide::active()->count(),
        ];

        // Get recent activities (for now, just recent users)
        $recentUsers = User::with('role')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard.index', compact('stats', 'recentUsers', 'user'));
    }

    public function analytics()
    {
        // Placeholder for analytics page
        return view('admin.dashboard.analytics');
    }

    public function reports()
    {
        // Placeholder for reports page
        return view('admin.dashboard.reports');
    }
}
