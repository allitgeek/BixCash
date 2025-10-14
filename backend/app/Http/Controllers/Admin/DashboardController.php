<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Slide;
use App\Models\SocialMediaLink;
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

    public function settings()
    {
        $socialMediaLinks = SocialMediaLink::ordered()->get();
        return view('admin.dashboard.settings', compact('socialMediaLinks'));
    }

    /**
     * Store a new social media link
     */
    public function storeSocialMedia(Request $request)
    {
        $validated = $request->validate([
            'platform' => 'required|string|max:50',
            'url' => 'required|url|max:255',
            'icon' => 'nullable|string|max:100',
            'order' => 'nullable|integer|min:0',
            'is_enabled' => 'nullable|boolean',
        ]);

        // Set default icon if not provided
        if (empty($validated['icon'])) {
            $validated['icon'] = SocialMediaLink::getDefaultIcon($validated['platform']);
        }

        // Handle checkbox
        $validated['is_enabled'] = $request->has('is_enabled');

        SocialMediaLink::create($validated);

        return redirect()->route('admin.settings')
            ->with('success', 'Social media link added successfully!');
    }

    /**
     * Update an existing social media link
     */
    public function updateSocialMedia(Request $request, SocialMediaLink $socialMedia)
    {
        $validated = $request->validate([
            'platform' => 'required|string|max:50',
            'url' => 'required|url|max:255',
            'icon' => 'nullable|string|max:100',
            'order' => 'nullable|integer|min:0',
            'is_enabled' => 'nullable|boolean',
        ]);

        // Set default icon if not provided
        if (empty($validated['icon'])) {
            $validated['icon'] = SocialMediaLink::getDefaultIcon($validated['platform']);
        }

        // Handle checkbox
        $validated['is_enabled'] = $request->has('is_enabled');

        $socialMedia->update($validated);

        return redirect()->route('admin.settings')
            ->with('success', 'Social media link updated successfully!');
    }

    /**
     * Delete a social media link
     */
    public function destroySocialMedia(SocialMediaLink $socialMedia)
    {
        $socialMedia->delete();

        return redirect()->route('admin.settings')
            ->with('success', 'Social media link deleted successfully!');
    }
}
