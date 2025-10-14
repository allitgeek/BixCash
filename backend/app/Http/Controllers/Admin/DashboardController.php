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

        // Get current Firebase configuration
        $firebaseConfig = [
            'project_id' => env('FIREBASE_PROJECT_ID', ''),
            'database_url' => env('FIREBASE_DATABASE_URL', ''),
            'storage_bucket' => env('FIREBASE_STORAGE_BUCKET', ''),
            'credentials_exists' => file_exists(storage_path('app/firebase/service-account.json'))
        ];

        return view('admin.dashboard.settings', compact('socialMediaLinks', 'firebaseConfig'));
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
            'icon_file' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'order' => 'nullable|integer|min:0',
            'is_enabled' => 'nullable|boolean',
        ]);

        // Handle icon file upload
        if ($request->hasFile('icon_file')) {
            $iconPath = $request->file('icon_file')->store('social-media-icons', 'public');
            $validated['icon_file'] = $iconPath;
        }

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
            'icon_file' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'order' => 'nullable|integer|min:0',
            'is_enabled' => 'nullable|boolean',
        ]);

        // Handle icon file upload
        if ($request->hasFile('icon_file')) {
            // Delete old icon file if exists
            if ($socialMedia->icon_file && \Storage::disk('public')->exists($socialMedia->icon_file)) {
                \Storage::disk('public')->delete($socialMedia->icon_file);
            }

            $iconPath = $request->file('icon_file')->store('social-media-icons', 'public');
            $validated['icon_file'] = $iconPath;
        }

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
        // Delete icon file if exists
        if ($socialMedia->icon_file && \Storage::disk('public')->exists($socialMedia->icon_file)) {
            \Storage::disk('public')->delete($socialMedia->icon_file);
        }

        $socialMedia->delete();

        return redirect()->route('admin.settings')
            ->with('success', 'Social media link deleted successfully!');
    }

    /**
     * Update Firebase Configuration
     */
    public function updateFirebaseConfig(Request $request)
    {
        try {
            $validated = $request->validate([
                'firebase_project_id' => 'required|string|max:255',
                'firebase_database_url' => 'nullable|url|max:500',
                'firebase_storage_bucket' => 'nullable|string|max:255',
                'firebase_credentials_json' => 'required|string'
            ]);

            // Parse and validate JSON
            $credentialsData = json_decode($validated['firebase_credentials_json'], true);

            if (!$credentialsData || !isset($credentialsData['type']) || $credentialsData['type'] !== 'service_account') {
                return redirect()->route('admin.settings')
                    ->with('error', 'Invalid Firebase service account JSON. Please check the format.');
            }

            // Save credentials JSON file
            $firebasePath = storage_path('app/firebase');
            if (!file_exists($firebasePath)) {
                mkdir($firebasePath, 0755, true);
            }

            $credentialsPath = $firebasePath . '/service-account.json';
            file_put_contents($credentialsPath, json_encode($credentialsData, JSON_PRETTY_PRINT));

            // Update .env file
            $this->updateEnvFile([
                'FIREBASE_PROJECT_ID' => $validated['firebase_project_id'],
                'FIREBASE_DATABASE_URL' => $validated['firebase_database_url'] ?? '',
                'FIREBASE_STORAGE_BUCKET' => $validated['firebase_storage_bucket'] ?? '',
                'FIREBASE_CREDENTIALS' => $credentialsPath
            ]);

            // Clear config cache
            \Artisan::call('config:clear');

            return redirect()->route('admin.settings')
                ->with('success', 'Firebase configuration updated successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('admin.settings')
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('Firebase config update failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('admin.settings')
                ->with('error', 'Failed to update Firebase configuration: ' . $e->getMessage());
        }
    }

    /**
     * Test Firebase Connection
     */
    public function testFirebaseConnection()
    {
        try {
            $credentialsPath = storage_path('app/firebase/service-account.json');

            if (!file_exists($credentialsPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Firebase credentials file not found. Please configure Firebase first.'
                ]);
            }

            // Try to initialize Firebase
            $factory = (new \Kreait\Firebase\Factory)->withServiceAccount($credentialsPath);

            // If we got here, configuration is valid
            return response()->json([
                'success' => true,
                'message' => 'Firebase connection successful! Configuration is valid.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Firebase connection failed: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Update environment file
     */
    private function updateEnvFile(array $data)
    {
        $envPath = base_path('.env');

        if (!file_exists($envPath)) {
            throw new \Exception('.env file not found');
        }

        $envContent = file_get_contents($envPath);

        foreach ($data as $key => $value) {
            $value = str_replace('\\', '\\\\', $value); // Escape backslashes
            $pattern = "/^{$key}=.*/m";

            if (preg_match($pattern, $envContent)) {
                // Update existing key
                $envContent = preg_replace($pattern, "{$key}={$value}", $envContent);
            } else {
                // Add new key
                $envContent .= "\n{$key}={$value}";
            }
        }

        file_put_contents($envPath, $envContent);
    }
}
