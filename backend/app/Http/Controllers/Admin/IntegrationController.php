<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiIntegration;
use App\Models\PartnerTransaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class IntegrationController extends Controller
{
    public function index()
    {
        $integrations = ApiIntegration::with('partner')->get();

        $totalApiTransactions = PartnerTransaction::fromApi()->count();
        $totalApiVolume = PartnerTransaction::fromApi()->sum('invoice_amount');

        return view('admin.integrations.index', [
            'integrations' => $integrations,
            'totalApiTransactions' => $totalApiTransactions,
            'totalApiVolume' => $totalApiVolume,
        ]);
    }

    public function show(ApiIntegration $integration, Request $request)
    {
        $integration->load('partner');

        $totalTransactions = PartnerTransaction::where('partner_id', $integration->partner_id)
            ->fromApi()->count();
        $totalAmount = PartnerTransaction::where('partner_id', $integration->partner_id)
            ->fromApi()->sum('invoice_amount');
        $todayCount = PartnerTransaction::where('partner_id', $integration->partner_id)
            ->fromApi()->whereDate('created_at', today())->count();
        $monthCount = PartnerTransaction::where('partner_id', $integration->partner_id)
            ->fromApi()->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)->count();

        $query = PartnerTransaction::where('partner_id', $integration->partner_id)
            ->fromApi()
            ->with('customer');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('external_order_id', 'like', "%{$search}%")
                    ->orWhere('transaction_code', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }

        $transactions = $query->latest()->paginate(20)->withQueryString();

        return view('admin.integrations.show', [
            'integration' => $integration,
            'totalTransactions' => $totalTransactions,
            'totalAmount' => $totalAmount,
            'todayCount' => $todayCount,
            'monthCount' => $monthCount,
            'transactions' => $transactions,
        ]);
    }

    public function create()
    {
        $existingPartnerIds = ApiIntegration::pluck('partner_id');

        $partners = User::whereHas('role', fn($q) => $q->where('name', 'partner'))
            ->whereHas('partnerProfile', fn($q) => $q->where('status', 'approved'))
            ->whereNotIn('id', $existingPartnerIds)
            ->get();

        return view('admin.integrations.create', [
            'partners' => $partners,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'partner_id' => 'required|exists:users,id|unique:api_integrations,partner_id',
            'name' => 'required|string|max:255',
            'allowed_ips' => 'nullable|string',
            'rate_limit_per_minute' => 'nullable|integer|min:1|max:1000',
        ]);

        $credentials = ApiIntegration::generateCredentials();

        $allowedIps = null;
        if ($request->filled('allowed_ips')) {
            $allowedIps = array_map('trim', explode(',', $request->allowed_ips));
            $allowedIps = array_filter($allowedIps);
        }

        $integration = ApiIntegration::create([
            'partner_id' => $request->partner_id,
            'name' => $request->name,
            'api_key' => $credentials['key'],
            'api_secret' => Hash::make($credentials['secret']),
            'allowed_ips' => $allowedIps,
            'rate_limit_per_minute' => $request->rate_limit_per_minute ?? 60,
        ]);

        return redirect()
            ->route('admin.integrations.show', $integration)
            ->with('success', 'Integration created successfully.')
            ->with('api_key', $credentials['key'])
            ->with('api_secret', $credentials['secret']);
    }

    public function regenerateKeys(ApiIntegration $integration)
    {
        $credentials = ApiIntegration::generateCredentials();

        $integration->update([
            'api_key' => $credentials['key'],
            'api_secret' => Hash::make($credentials['secret']),
        ]);

        return redirect()
            ->route('admin.integrations.show', $integration)
            ->with('success', 'API credentials regenerated successfully.')
            ->with('api_key', $credentials['key'])
            ->with('api_secret', $credentials['secret']);
    }

    public function toggleStatus(ApiIntegration $integration)
    {
        $integration->update(['is_active' => !$integration->is_active]);

        $status = $integration->is_active ? 'activated' : 'deactivated';

        return redirect()->back()->with('success', "Integration {$status} successfully.");
    }

    public function transactions(ApiIntegration $integration, Request $request)
    {
        $query = PartnerTransaction::where('partner_id', $integration->partner_id)
            ->fromApi()
            ->with('customer');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('external_order_id', 'like', "%{$search}%")
                    ->orWhere('transaction_code', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }

        $transactions = $query->latest()->paginate(20)->withQueryString();

        return response()->json([
            'html' => view('admin.integrations._transactions_table', [
                'transactions' => $transactions,
            ])->render(),
            'pagination' => $transactions->links()->toHtml(),
        ]);
    }
}
