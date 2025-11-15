<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Slide;
use App\Models\SocialMediaLink;
use App\Models\PartnerTransaction;
use App\Models\SystemSetting;
use App\Models\ProfitSharingDistribution;
use App\Models\Wallet;
use App\Models\WithdrawalRequest;
use App\Models\CommissionLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        // Get recent customers (last 5 with phone numbers)
        $recentCustomers = User::with('role', 'customerProfile')
            ->whereHas('role', function ($q) {
                $q->where('name', 'customer');
            })
            ->latest()
            ->limit(5)
            ->get();

        // Get recent transactions (last 5 with customer, partner, and amount)
        $recentTransactions = PartnerTransaction::with(['customer', 'partner.partnerProfile'])
            ->latest()
            ->limit(5)
            ->get();

        // Get recent partners (last 5 with business name and type)
        $recentPartners = User::with('role', 'partnerProfile')
            ->whereHas('role', function ($q) {
                $q->where('name', 'partner');
            })
            ->whereHas('partnerProfile')
            ->latest()
            ->limit(5)
            ->get();

        // Get recent withdrawal requests (last 5 with all statuses)
        $recentWithdrawals = WithdrawalRequest::with('user')
            ->latest()
            ->limit(5)
            ->get();

        // Get pending commission ledgers (last 5 with partner info)
        $pendingLedgers = CommissionLedger::with('partner.partnerProfile')
            ->where(function ($query) {
                $query->where('status', 'pending')
                      ->orWhere('status', 'partial');
            })
            ->orderBy('batch_period', 'desc')
            ->limit(5)
            ->get();

        // Get 7-day data for charts
        $last7Days = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $last7Days->push([
                'date' => $date,
                'label' => now()->subDays($i)->format('M j'),
            ]);
        }

        // Customer registrations per day (last 7 days)
        $customerChartData = $last7Days->map(function ($day) {
            return User::whereHas('role', function ($q) {
                $q->where('name', 'customer');
            })
            ->whereDate('created_at', $day['date'])
            ->count();
        });

        // Transaction amounts per day (last 7 days)
        $transactionChartData = $last7Days->map(function ($day) {
            return PartnerTransaction::whereDate('created_at', $day['date'])
                ->sum('invoice_amount');
        });

        // Partner registrations per day (last 7 days)
        $partnerChartData = $last7Days->map(function ($day) {
            return User::whereHas('role', function ($q) {
                $q->where('name', 'partner');
            })
            ->whereDate('created_at', $day['date'])
            ->count();
        });

        $chartLabels = $last7Days->pluck('label');

        return view('admin.dashboard.index', compact(
            'stats',
            'recentCustomers',
            'recentTransactions',
            'recentPartners',
            'recentWithdrawals',
            'pendingLedgers',
            'user',
            'chartLabels',
            'customerChartData',
            'transactionChartData',
            'partnerChartData'
        ));
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

    public function profitSharing()
    {
        // Get current year and month for criteria checking
        $currentYear = now()->year;
        $currentMonth = now()->month;

        // Get criteria settings
        $minSpending = floatval(SystemSetting::get('active_customer_min_spending', 0));
        $minCustomers = intval(SystemSetting::get('active_partner_min_customers', 0));
        $minAmount = floatval(SystemSetting::get('active_partner_min_amount', 0));

        // Get level statistics
        $levels = [];
        for ($i = 1; $i <= 7; $i++) {
            // Get all users in this level
            $usersInLevel = User::where('profit_sharing_level', $i)
                ->with(['role', 'customerProfile', 'partnerProfile'])
                ->orderBy('profit_sharing_qualified_at', 'ASC')
                ->get();

            // Calculate active/inactive counts
            $activeCount = 0;
            $inactiveCount = 0;

            foreach ($usersInLevel as $user) {
                // Check if user still meets criteria
                $meetsCriteria = false;

                if ($user->isCustomer()) {
                    $totalSpending = PartnerTransaction::where('customer_id', $user->id)
                        ->where('status', 'confirmed')
                        ->whereYear('transaction_date', $currentYear)
                        ->whereMonth('transaction_date', $currentMonth)
                        ->sum('invoice_amount');

                    $meetsCriteria = floatval($totalSpending) >= $minSpending;
                } elseif ($user->isPartner()) {
                    $stats = PartnerTransaction::where('partner_id', $user->id)
                        ->where('status', 'confirmed')
                        ->whereYear('transaction_date', $currentYear)
                        ->whereMonth('transaction_date', $currentMonth)
                        ->selectRaw('COUNT(DISTINCT customer_id) as unique_customers, COALESCE(SUM(invoice_amount), 0) as total_amount')
                        ->first();

                    $uniqueCustomers = intval($stats->unique_customers ?? 0);
                    $totalAmount = floatval($stats->total_amount ?? 0);
                    $meetsCriteria = ($uniqueCustomers >= $minCustomers) && ($totalAmount >= $minAmount);
                }

                if ($meetsCriteria) {
                    $activeCount++;
                } else {
                    $inactiveCount++;
                }
            }

            $threshold = intval(SystemSetting::get("customer_threshold_level_{$i}", 0));

            $levels[$i] = [
                'level' => $i,
                'threshold' => $threshold,
                'total' => $usersInLevel->count(),
                'active' => $activeCount,
                'inactive' => $inactiveCount,
                'users' => $usersInLevel,
            ];
        }

        return view('admin.dashboard.profit-sharing', compact('levels'));
    }

    /**
     * Run profit sharing level assignment command manually
     */
    public function runProfitSharingAssignment()
    {
        try {
            // Check if command exists
            $commands = \Artisan::all();
            if (!isset($commands['profit-sharing:assign-levels'])) {
                throw new \Exception('Command not registered. Please run: php artisan optimize:clear');
            }

            // Run the assignment command
            $exitCode = \Artisan::call('profit-sharing:assign-levels');

            // Get command output
            $output = \Artisan::output();

            if ($exitCode !== 0) {
                throw new \Exception('Command failed with exit code: ' . $exitCode);
            }

            return response()->json([
                'success' => true,
                'message' => 'Profit sharing levels recalculated successfully! Please refresh the page to see updated data.',
                'output' => $output
            ]);

        } catch (\Exception $e) {
            \Log::error('Profit sharing assignment failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to run level assignment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Disperse profit sharing to qualified active users
     */
    public function disperseProfitSharing(Request $request)
    {
        try {
            // Validate incoming data
            $validated = $request->validate([
                'distribution_month' => 'required|string',
                'total_amount' => 'required|numeric|min:0',
                'levels' => 'required|array|size:7',
                'levels.*.profit' => 'required|numeric|min:0',
                'levels.*.per_customer' => 'required|numeric|min:0',
                'levels.*.percentage' => 'required|numeric|min:0',
            ]);

            // Check for duplicate distribution
            $existing = ProfitSharingDistribution::where('distribution_month', $validated['distribution_month'])
                ->where('status', 'dispersed')
                ->first();

            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'A distribution for this month has already been dispersed!'
                ], 400);
            }

            DB::beginTransaction();

            // Get criteria for checking active users
            $currentYear = now()->year;
            $currentMonth = now()->month;
            $minSpending = floatval(SystemSetting::get('active_customer_min_spending', 0));
            $minCustomers = intval(SystemSetting::get('active_partner_min_customers', 0));
            $minAmount = floatval(SystemSetting::get('active_partner_min_amount', 0));

            // Create distribution record
            $distributionData = [
                'distribution_month' => $validated['distribution_month'],
                'total_amount' => $validated['total_amount'],
                'status' => 'pending',
                'created_by_admin_id' => Auth::id(),
            ];

            // Add level data
            for ($i = 1; $i <= 7; $i++) {
                $distributionData["level_{$i}_amount"] = $validated['levels'][$i - 1]['profit'];
                $distributionData["level_{$i}_per_customer"] = $validated['levels'][$i - 1]['per_customer'];
                $distributionData["level_{$i}_percentage"] = $validated['levels'][$i - 1]['percentage'];
            }

            $distribution = ProfitSharingDistribution::create($distributionData);

            // Disperse to each level
            $totalRecipients = 0;

            for ($level = 1; $level <= 7; $level++) {
                $levelAmount = $validated['levels'][$level - 1]['profit'];
                $perCustomer = $validated['levels'][$level - 1]['per_customer'];

                if ($levelAmount <= 0 || $perCustomer <= 0) {
                    continue; // Skip this level if no profit allocated
                }

                // Get all users in this level
                $usersInLevel = User::where('profit_sharing_level', $level)
                    ->with('wallet')
                    ->get();

                $levelRecipients = 0;

                foreach ($usersInLevel as $user) {
                    // Check if user is ACTIVE (meets criteria)
                    $isActive = false;

                    if ($user->isCustomer()) {
                        $totalSpending = PartnerTransaction::where('customer_id', $user->id)
                            ->where('status', 'confirmed')
                            ->whereYear('transaction_date', $currentYear)
                            ->whereMonth('transaction_date', $currentMonth)
                            ->sum('invoice_amount');

                        $isActive = floatval($totalSpending) >= $minSpending;
                    } elseif ($user->isPartner()) {
                        $stats = PartnerTransaction::where('partner_id', $user->id)
                            ->where('status', 'confirmed')
                            ->whereYear('transaction_date', $currentYear)
                            ->whereMonth('transaction_date', $currentMonth)
                            ->selectRaw('COUNT(DISTINCT customer_id) as unique_customers, COALESCE(SUM(invoice_amount), 0) as total_amount')
                            ->first();

                        $uniqueCustomers = intval($stats->unique_customers ?? 0);
                        $totalAmount = floatval($stats->total_amount ?? 0);
                        $isActive = ($uniqueCustomers >= $minCustomers) && ($totalAmount >= $minAmount);
                    }

                    // Only disperse to ACTIVE users
                    if (!$isActive) {
                        continue;
                    }

                    // Ensure user has a wallet
                    if (!$user->wallet) {
                        Wallet::create([
                            'user_id' => $user->id,
                            'balance' => 0.00,
                            'total_earned' => 0.00,
                            'total_withdrawn' => 0.00,
                        ]);
                        $user->load('wallet');
                    }

                    // Credit the wallet
                    $user->wallet->credit(
                        $perCustomer,
                        'profit_sharing',
                        $distribution->id,
                        "Profit sharing distribution for {$validated['distribution_month']} (Level {$level})"
                    );

                    $levelRecipients++;
                    $totalRecipients++;
                }

                // Update distribution record with actual recipients for this level
                $distribution->{"level_{$level}_recipients"} = $levelRecipients;
            }

            // Mark distribution as dispersed
            $distribution->status = 'dispersed';
            $distribution->total_recipients = $totalRecipients;
            $distribution->dispersed_at = now();
            $distribution->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Successfully dispersed Rs. " . number_format($validated['total_amount']) . " to {$totalRecipients} active users!",
                'recipients' => $totalRecipients,
                'distribution_id' => $distribution->id
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation error: ' . implode(', ', $e->errors())
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Profit sharing disperse failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to disperse profit sharing: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display profit sharing distribution history
     */
    public function profitSharingHistory()
    {
        // Get all distributions with admin user info, ordered by newest first
        $distributions = ProfitSharingDistribution::with('createdByAdmin')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Calculate summary stats for dispersed distributions only
        $totalDispersed = ProfitSharingDistribution::where('status', 'dispersed')
            ->sum('total_amount');

        $totalRecipients = ProfitSharingDistribution::where('status', 'dispersed')
            ->sum('total_recipients');

        return view('admin.dashboard.profit-sharing-history', compact(
            'distributions',
            'totalDispersed',
            'totalRecipients'
        ));
    }

    /**
     * Display detailed breakdown for a specific distribution
     */
    public function profitSharingHistoryDetail(ProfitSharingDistribution $distribution)
    {
        // Load related data
        $distribution->load('createdByAdmin', 'walletTransactions.user');

        // Calculate level breakdown
        $levelBreakdown = [];
        for ($i = 1; $i <= 7; $i++) {
            $levelBreakdown[$i] = [
                'amount' => $distribution->{"level_{$i}_amount"},
                'per_customer' => $distribution->{"level_{$i}_per_customer"},
                'percentage' => $distribution->{"level_{$i}_percentage"},
                'recipients' => $distribution->{"level_{$i}_recipients"},
            ];
        }

        // Get wallet transactions for this distribution
        $transactions = $distribution->walletTransactions()
            ->with(['user.customerProfile', 'user.partnerProfile'])
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('admin.dashboard.profit-sharing-history-detail', compact(
            'distribution',
            'levelBreakdown',
            'transactions'
        ));
    }

    public function settings()
    {
        $socialMediaLinks = SocialMediaLink::ordered()->get();

        // Get current Firebase configuration
        $firebaseConfig = [
            'project_id' => env('FIREBASE_PROJECT_ID', ''),
            'database_url' => env('FIREBASE_DATABASE_URL', ''),
            'storage_bucket' => env('FIREBASE_STORAGE_BUCKET', ''),
            'web_api_key' => env('FIREBASE_WEB_API_KEY', ''),
            'auth_domain' => env('FIREBASE_AUTH_DOMAIN', ''),
            'messaging_sender_id' => env('FIREBASE_MESSAGING_SENDER_ID', ''),
            'app_id' => env('FIREBASE_APP_ID', ''),
            'credentials_exists' => file_exists(storage_path('app/firebase/service-account.json'))
        ];

        return view('admin.dashboard.settings', compact('socialMediaLinks', 'firebaseConfig'));
    }

    public function adminSettings()
    {
        // Load active criteria settings
        $customerCriteria = SystemSetting::get('active_customer_min_spending', 0);
        $partnerMinCustomers = SystemSetting::get('active_partner_min_customers', 0);
        $partnerMinAmount = SystemSetting::get('active_partner_min_amount', 0);

        // Load customer threshold levels
        $customerThresholds = [];
        for ($i = 1; $i <= 7; $i++) {
            $customerThresholds[$i] = SystemSetting::get("customer_threshold_level_{$i}", 0);
        }

        return view('admin.dashboard.admin-settings', compact(
            'customerCriteria',
            'partnerMinCustomers',
            'partnerMinAmount',
            'customerThresholds'
        ));
    }

    /**
     * Update active criteria settings
     */
    public function updateActiveCriteria(Request $request)
    {
        // Build validation rules including threshold levels
        $validationRules = [
            'active_customer_min_spending' => 'nullable|string',
            'active_partner_min_customers' => 'nullable|integer|min:0',
            'active_partner_min_amount' => 'nullable|string',
        ];

        // Add validation for customer threshold levels
        for ($i = 1; $i <= 7; $i++) {
            $validationRules["customer_threshold_level_{$i}"] = 'nullable|integer|min:0';
        }

        $validated = $request->validate($validationRules);

        // Clean and convert customer spending amount (default to 0 if empty)
        $customerSpending = !empty($validated['active_customer_min_spending'])
            ? preg_replace('/[^\d]/', '', $validated['active_customer_min_spending'])
            : '0';

        // Save customer criteria
        SystemSetting::set(
            'active_customer_min_spending',
            $customerSpending,
            'number',
            'criteria',
            'Minimum spending amount for a customer to be considered active'
        );

        // Save partner minimum customers (default to 0 if empty)
        $partnerMinCustomers = $validated['active_partner_min_customers'] ?? 0;

        SystemSetting::set(
            'active_partner_min_customers',
            $partnerMinCustomers,
            'number',
            'criteria',
            'Minimum number of customers/orders for a partner to be considered active'
        );

        // Clean and save partner minimum amount (default to 0 if empty)
        $partnerMinAmount = !empty($validated['active_partner_min_amount'])
            ? preg_replace('/[^\d]/', '', $validated['active_partner_min_amount'])
            : '0';

        SystemSetting::set(
            'active_partner_min_amount',
            $partnerMinAmount,
            'number',
            'criteria',
            'Minimum transaction amount for a partner to be considered active'
        );

        // Save customer threshold levels
        for ($i = 1; $i <= 7; $i++) {
            $key = "customer_threshold_level_{$i}";
            $value = $request->input($key, 0);

            SystemSetting::set(
                $key,
                $value,
                'number',
                'thresholds',
                "Number of customers required for Level {$i}"
            );
        }

        return redirect()->route('admin.settings.admin')
            ->with('success', 'Settings updated successfully!');
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
                'firebase_web_api_key' => 'required|string|max:255',
                'firebase_auth_domain' => 'required|string|max:255',
                'firebase_messaging_sender_id' => 'required|string|max:255',
                'firebase_app_id' => 'required|string|max:255',
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
                'FIREBASE_WEB_API_KEY' => $validated['firebase_web_api_key'],
                'FIREBASE_AUTH_DOMAIN' => $validated['firebase_auth_domain'],
                'FIREBASE_MESSAGING_SENDER_ID' => $validated['firebase_messaging_sender_id'],
                'FIREBASE_APP_ID' => $validated['firebase_app_id'],
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
