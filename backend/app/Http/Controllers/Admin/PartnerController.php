<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PartnerProfile;
use App\Models\PartnerTransaction;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PartnerController extends Controller
{
    /**
     * Show create partner form
     */
    public function create()
    {
        return view('admin.partners.create');
    }

    /**
     * Store new partner
     */
    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'phone' => 'required|string|regex:/^[0-9]{10}$/',
            'contact_person_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'business_type' => 'required|string|max:100',
            'business_address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'auto_approve' => 'nullable|boolean',
            'logo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048', // Max 2MB
        ]);

        // Format phone number to E.164
        $phone = '+92' . $validated['phone'];

        // Check if phone number already exists
        $existingUser = User::where('phone', $phone)->first();

        if ($existingUser) {
            $roleName = $existingUser->role->name ?? 'user';
            return back()->withInput()->withErrors([
                'phone' => "This phone number is already registered as a {$roleName}."
            ]);
        }

        // Get partner role
        $partnerRole = Role::where('name', 'partner')->first();

        if (!$partnerRole) {
            return back()->withErrors(['error' => 'Partner role not found. Please contact system administrator.']);
        }

        // Determine if auto-approve
        $autoApprove = $request->input('auto_approve', false);
        $status = $autoApprove ? 'approved' : 'pending';
        $isActive = $autoApprove ? true : false;

        // Handle logo upload
        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('partner-logos', 'public');
        }

        // Create user account
        $user = User::create([
            'name' => $validated['contact_person_name'],
            'phone' => $phone,
            'email' => $validated['email'] ?? null,
            'role_id' => $partnerRole->id,
            'is_active' => $isActive,
        ]);

        // Create partner profile
        $partnerProfile = PartnerProfile::create([
            'user_id' => $user->id,
            'business_name' => $validated['business_name'],
            'contact_person_name' => $validated['contact_person_name'],
            'logo' => $logoPath,
            'business_type' => $validated['business_type'],
            'business_phone' => $phone,
            'business_address' => $validated['business_address'] ?? null,
            'business_city' => $validated['city'] ?? null,
            'status' => $status,
            'registration_date' => now(),
            'approved_at' => $autoApprove ? now() : null,
        ]);

        $message = $autoApprove
            ? "Partner '{$partnerProfile->business_name}' created and approved successfully!"
            : "Partner '{$partnerProfile->business_name}' created successfully! Status: Pending approval.";

        return redirect()->route('admin.partners.show', $user->id)
            ->with('success', $message);
    }

    /**
     * Display all partners
     */
    public function index(Request $request)
    {
        $query = User::whereHas('role', function($q) {
            $q->where('name', 'partner');
        })->with('partnerProfile');

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->whereHas('partnerProfile', function($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        // Search by name, phone, or business name
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhereHas('partnerProfile', function($pq) use ($search) {
                      $pq->where('business_name', 'like', "%{$search}%");
                  });
            });
        }

        $partners = $query->latest()->paginate(20);

        return view('admin.partners.index', compact('partners'));
    }

    /**
     * Display pending partner applications
     */
    public function pendingApplications()
    {
        $applications = User::whereHas('role', function($q) {
            $q->where('name', 'partner');
        })->whereHas('partnerProfile', function($q) {
            $q->where('status', 'pending');
        })->with('partnerProfile')
        ->latest()
        ->paginate(20);

        return view('admin.partners.pending', compact('applications'));
    }

    /**
     * Show partner details
     */
    public function show($id)
    {
        $partner = User::with('partnerProfile')->findOrFail($id);

        if (!$partner->isPartner()) {
            abort(404, 'Partner not found');
        }

        // Get partner statistics
        $stats = [
            'total_transactions' => PartnerTransaction::where('partner_id', $id)->count(),
            'total_revenue' => PartnerTransaction::where('partner_id', $id)
                ->where('status', 'confirmed')
                ->sum('invoice_amount'),
            'total_profit' => PartnerTransaction::where('partner_id', $id)
                ->where('status', 'confirmed')
                ->sum('partner_profit_share'),
            'pending_confirmations' => PartnerTransaction::where('partner_id', $id)
                ->where('status', 'pending_confirmation')
                ->count(),
        ];

        // Recent transactions
        $recentTransactions = PartnerTransaction::where('partner_id', $id)
            ->with('customer')
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.partners.show', compact('partner', 'stats', 'recentTransactions'));
    }

    /**
     * Approve partner application
     */
    public function approve(Request $request, $id)
    {
        $request->validate([
            'approval_notes' => 'nullable|string|max:500',
        ]);

        $partner = User::with('partnerProfile')->findOrFail($id);

        if (!$partner->isPartner()) {
            return back()->withErrors(['error' => 'Invalid partner account']);
        }

        $partnerProfile = $partner->partnerProfile;

        if (!$partnerProfile) {
            return back()->withErrors(['error' => 'Partner profile not found']);
        }

        if ($partnerProfile->status === 'approved') {
            return back()->with('info', 'Partner is already approved');
        }

        // Approve partner
        $partnerProfile->status = 'approved';
        $partnerProfile->approval_notes = $request->approval_notes;
        $partnerProfile->approved_at = now();
        $partnerProfile->save();

        // Activate user account
        $partner->is_active = true;
        $partner->save();

        // TODO: Send SMS notification to partner

        return redirect()->route('admin.partners.show', $id)
            ->with('success', "Partner '{$partnerProfile->business_name}' approved successfully!");
    }

    /**
     * Reject partner application
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_notes' => 'required|string|max:500',
        ]);

        $partner = User::with('partnerProfile')->findOrFail($id);

        if (!$partner->isPartner()) {
            return back()->withErrors(['error' => 'Invalid partner account']);
        }

        $partnerProfile = $partner->partnerProfile;

        if (!$partnerProfile) {
            return back()->withErrors(['error' => 'Partner profile not found']);
        }

        // Reject partner
        $partnerProfile->status = 'rejected';
        $partnerProfile->rejection_notes = $request->rejection_notes;
        $partnerProfile->save();

        // Deactivate user account
        $partner->is_active = false;
        $partner->save();

        // TODO: Send SMS notification to partner

        return redirect()->route('admin.partners.pending')
            ->with('success', "Partner application rejected");
    }

    /**
     * View all transactions for a partner
     */
    public function transactions($id)
    {
        $partner = User::with('partnerProfile')->findOrFail($id);

        if (!$partner->isPartner()) {
            abort(404, 'Partner not found');
        }

        $transactions = PartnerTransaction::where('partner_id', $id)
            ->with('customer')
            ->latest()
            ->paginate(30);

        return view('admin.partners.transactions', compact('partner', 'transactions'));
    }

    /**
     * Update partner status (activate/deactivate)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $partner = User::findOrFail($id);

        if (!$partner->isPartner()) {
            return back()->withErrors(['error' => 'Invalid partner account']);
        }

        $partner->is_active = $request->is_active;
        $partner->save();

        $status = $request->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Partner account {$status} successfully");
    }

    /**
     * Set PIN for partner
     */
    public function setPin(Request $request, $id)
    {
        $request->validate([
            'pin' => ['required', 'string', 'regex:/^[0-9]{4}$/', 'confirmed'],
            'pin_confirmation' => ['required', 'same:pin']
        ]);

        $partner = User::findOrFail($id);

        if (!$partner->isPartner()) {
            return back()->withErrors(['error' => 'Invalid partner account']);
        }

        if ($partner->pin_hash) {
            return back()->withErrors(['error' => 'Partner already has a PIN set. Use Reset PIN instead.']);
        }

        // Set PIN
        $partner->setPin($request->pin);

        return back()->with('success', "PIN set successfully for partner '{$partner->name}'");
    }

    /**
     * Reset PIN for partner
     */
    public function resetPin(Request $request, $id)
    {
        $request->validate([
            'new_pin' => ['required', 'string', 'regex:/^[0-9]{4}$/', 'confirmed'],
            'new_pin_confirmation' => ['required', 'same:new_pin']
        ]);

        $partner = User::findOrFail($id);

        if (!$partner->isPartner()) {
            return back()->withErrors(['error' => 'Invalid partner account']);
        }

        if (!$partner->pin_hash) {
            return back()->withErrors(['error' => 'Partner does not have a PIN set. Use Set PIN instead.']);
        }

        // Reset PIN
        $partner->resetPin($request->new_pin);

        return back()->with('success', "PIN reset successfully for partner '{$partner->name}'");
    }

    /**
     * Update partner logo
     */
    public function updateLogo(Request $request, $id)
    {
        $request->validate([
            'logo' => 'required|image|mimes:jpeg,jpg,png|max:2048', // Max 2MB
        ]);

        $partner = User::with('partnerProfile')->findOrFail($id);

        if (!$partner->isPartner()) {
            return back()->withErrors(['error' => 'Invalid partner account']);
        }

        $partnerProfile = $partner->partnerProfile;

        if (!$partnerProfile) {
            return back()->withErrors(['error' => 'Partner profile not found']);
        }

        // Delete old logo if exists
        if ($partnerProfile->logo) {
            $oldLogoPath = storage_path('app/public/' . $partnerProfile->logo);
            if (file_exists($oldLogoPath)) {
                unlink($oldLogoPath);
            }
        }

        // Store new logo
        $logoPath = $request->file('logo')->store('partner-logos', 'public');
        $partnerProfile->update(['logo' => $logoPath]);

        return back()->with('success', 'Business logo updated successfully!');
    }
}
