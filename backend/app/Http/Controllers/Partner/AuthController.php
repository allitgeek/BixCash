<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PartnerProfile;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show partner registration form
     */
    public function showRegistrationForm()
    {
        return view('partner.register');
    }

    /**
     * Handle partner registration submission
     */
    public function register(Request $request)
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
            'agree_terms' => 'required|accepted',
        ]);

        // Format phone number to E.164
        $phone = '+92' . $validated['phone'];

        // Check if phone number already exists
        $existingUser = User::where('phone', $phone)->first();

        if ($existingUser) {
            $roleName = $existingUser->role->name ?? 'user';
            throw ValidationException::withMessages([
                'phone' => "This phone number is already registered as a {$roleName}."
            ]);
        }

        // Get partner role
        $partnerRole = Role::where('name', 'partner')->first();

        if (!$partnerRole) {
            return back()->withErrors(['error' => 'Partner role not found. Please contact administrator.']);
        }

        // Create user account (inactive until approved)
        $user = User::create([
            'name' => $validated['contact_person_name'],
            'phone' => $phone,
            'email' => $validated['email'] ?? null,
            'role_id' => $partnerRole->id,
            'is_active' => false, // Inactive until approved
        ]);

        // Create partner profile
        PartnerProfile::create([
            'user_id' => $user->id,
            'business_name' => $validated['business_name'],
            'contact_person_name' => $validated['contact_person_name'],
            'business_type' => $validated['business_type'],
            'business_phone' => $phone,
            'business_address' => $validated['business_address'] ?? null,
            'business_city' => $validated['city'] ?? null,
            'status' => 'pending',
            'registration_date' => now(),
        ]);

        // Redirect with success message
        return redirect()->route('partner.register')
            ->with('success', 'Thank you! Your partner application has been submitted. You will receive an SMS once approved by our team.');
    }

    /**
     * Show pending approval page
     */
    public function pendingApproval()
    {
        $user = Auth::user();

        if (!$user->isPartner()) {
            abort(403, 'Access denied.');
        }

        $partnerProfile = $user->partnerProfile;

        if (!$partnerProfile) {
            abort(404, 'Partner profile not found.');
        }

        // If already approved, redirect to dashboard
        if ($partnerProfile->status === 'approved') {
            return redirect()->route('partner.dashboard');
        }

        return view('partner.pending-approval', compact('partnerProfile'));
    }

    /**
     * Logout partner
     */
    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect('/')->with('success', 'Logged out successfully!');
    }
}
