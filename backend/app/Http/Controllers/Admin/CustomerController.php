<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\CustomerProfile;
use App\Models\PartnerTransaction;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Get criteria setting
        $minSpending = floatval(SystemSetting::get('active_customer_min_spending', 0));

        // Get current year and month for criteria calculation
        $currentYear = now()->year;
        $currentMonth = now()->month;

        $query = User::with(['role', 'customerProfile', 'wallet'])
            ->whereHas('role', function ($q) {
                $q->where('name', 'customer');
            });

        // Add transaction statistics (current month only)
        $query->addSelect(['users.*'])
            ->addSelect([
                'total_spending' => PartnerTransaction::selectRaw('COALESCE(SUM(invoice_amount), 0)')
                    ->whereColumn('customer_id', 'users.id')
                    ->where('status', 'confirmed')
                    ->whereYear('transaction_date', $currentYear)
                    ->whereMonth('transaction_date', $currentMonth),
                'last_transaction_date' => PartnerTransaction::selectRaw('MAX(transaction_date)')
                    ->whereColumn('customer_id', 'users.id')
                    ->where('status', 'confirmed')
                    ->whereYear('transaction_date', $currentYear)
                    ->whereMonth('transaction_date', $currentMonth)
            ]);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status);
        }

        // Filter by phone verification
        if ($request->has('phone_verified') && $request->phone_verified !== '') {
            if ($request->phone_verified == '1') {
                $query->whereNotNull('phone_verified_at');
            } else {
                $query->whereNull('phone_verified_at');
            }
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $customers = $query->paginate(20);

        return view('admin.customers.index', compact('customers', 'minSpending'));
    }

    /**
     * Display the specified customer
     *
     * @param User $customer
     * @return \Illuminate\View\View
     */
    public function show(User $customer)
    {
        // Ensure this is a customer
        if (!$customer->isCustomer()) {
            abort(404, 'Customer not found');
        }

        $customer->load(['role', 'customerProfile']);

        return view('admin.customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified customer
     *
     * @param User $customer
     * @return \Illuminate\View\View
     */
    public function edit(User $customer)
    {
        // Ensure this is a customer
        if (!$customer->isCustomer()) {
            abort(404, 'Customer not found');
        }

        $customer->load(['role', 'customerProfile']);

        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer
     *
     * @param Request $request
     * @param User $customer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $customer)
    {
        // Ensure this is a customer
        if (!$customer->isCustomer()) {
            abort(404, 'Customer not found');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,' . $customer->id,
            'phone' => 'required|string|regex:/^\+92[0-9]{10}$/|unique:users,phone,' . $customer->id,
            'is_active' => 'required|boolean',

            // Profile fields
            'full_name' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'cnic' => 'nullable|string|regex:/^[0-9]{5}-[0-9]{7}-[0-9]{1}$/',
        ]);

        try {
            DB::beginTransaction();

            // Update user
            $customer->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'is_active' => $validated['is_active'],
            ]);

            // Update or create profile
            $profile = $customer->customerProfile;
            if (!$profile) {
                $profile = new CustomerProfile(['user_id' => $customer->id]);
            }

            $profile->full_name = $validated['full_name'] ?? $profile->full_name;
            $profile->date_of_birth = $validated['date_of_birth'] ?? $profile->date_of_birth;
            $profile->gender = $validated['gender'] ?? $profile->gender;
            $profile->address = $validated['address'] ?? $profile->address;
            $profile->city = $validated['city'] ?? $profile->city;
            $profile->postal_code = $validated['postal_code'] ?? $profile->postal_code;
            $profile->cnic = $validated['cnic'] ?? $profile->cnic;
            $profile->phone = $validated['phone'];

            $profile->save();

            DB::commit();

            return redirect()->route('admin.customers.show', $customer)
                ->with('success', 'Customer updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Admin customer update failed', [
                'error' => $e->getMessage(),
                'customer_id' => $customer->id
            ]);

            return redirect()->back()
                ->with('error', 'Failed to update customer')
                ->withInput();
        }
    }

    /**
     * Toggle customer active status
     *
     * @param User $customer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleStatus(User $customer)
    {
        // Ensure this is a customer
        if (!$customer->isCustomer()) {
            abort(404, 'Customer not found');
        }

        try {
            $customer->is_active = !$customer->is_active;
            $customer->save();

            $status = $customer->is_active ? 'activated' : 'deactivated';

            return redirect()->back()
                ->with('success', "Customer {$status} successfully");

        } catch (\Exception $e) {
            Log::error('Admin customer toggle status failed', [
                'error' => $e->getMessage(),
                'customer_id' => $customer->id
            ]);

            return redirect()->back()
                ->with('error', 'Failed to toggle customer status');
        }
    }

    /**
     * Reset customer PIN
     *
     * @param User $customer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetPin(User $customer)
    {
        // Ensure this is a customer
        if (!$customer->isCustomer()) {
            abort(404, 'Customer not found');
        }

        try {
            $customer->pin_hash = null;
            $customer->pin_attempts = 0;
            $customer->pin_locked_until = null;
            $customer->save();

            return redirect()->back()
                ->with('success', 'Customer PIN reset successfully. Customer will need to set a new PIN.');

        } catch (\Exception $e) {
            Log::error('Admin customer PIN reset failed', [
                'error' => $e->getMessage(),
                'customer_id' => $customer->id
            ]);

            return redirect()->back()
                ->with('error', 'Failed to reset customer PIN');
        }
    }

    /**
     * Unlock customer PIN
     *
     * @param User $customer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unlockPin(User $customer)
    {
        // Ensure this is a customer
        if (!$customer->isCustomer()) {
            abort(404, 'Customer not found');
        }

        try {
            $customer->pin_attempts = 0;
            $customer->pin_locked_until = null;
            $customer->save();

            return redirect()->back()
                ->with('success', 'Customer PIN unlocked successfully');

        } catch (\Exception $e) {
            Log::error('Admin customer PIN unlock failed', [
                'error' => $e->getMessage(),
                'customer_id' => $customer->id
            ]);

            return redirect()->back()
                ->with('error', 'Failed to unlock customer PIN');
        }
    }

    /**
     * Delete the specified customer
     *
     * @param User $customer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $customer)
    {
        // Ensure this is a customer
        if (!$customer->isCustomer()) {
            abort(404, 'Customer not found');
        }

        try {
            DB::beginTransaction();

            // Delete customer profile
            if ($customer->customerProfile) {
                $customer->customerProfile->delete();
            }

            // Delete customer
            $customer->delete();

            DB::commit();

            return redirect()->route('admin.customers.index')
                ->with('success', 'Customer deleted successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Admin customer delete failed', [
                'error' => $e->getMessage(),
                'customer_id' => $customer->id
            ]);

            return redirect()->back()
                ->with('error', 'Failed to delete customer');
        }
    }

    /**
     * View all transactions for a customer
     *
     * @param User $customer
     * @return \Illuminate\View\View
     */
    public function transactions(User $customer)
    {
        // Ensure this is a customer
        if (!$customer->isCustomer()) {
            abort(404, 'Customer not found');
        }

        $transactions = PartnerTransaction::where('customer_id', $customer->id)
            ->with(['partner.partnerProfile', 'brand'])
            ->latest()
            ->paginate(30);

        return view('admin.customers.transactions', compact('customer', 'transactions'));
    }

    /**
     * Manually verify customer's phone after calling to confirm identity
     *
     * @param User $customer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyPhone(User $customer)
    {
        // Ensure this is a customer
        if (!$customer->isCustomer()) {
            abort(404, 'Customer not found');
        }

        try {
            $profile = $customer->customerProfile;

            if (!$profile) {
                return redirect()->back()
                    ->with('error', 'Customer profile not found');
            }

            if ($profile->is_verified) {
                return redirect()->back()
                    ->with('info', 'Customer is already verified');
            }

            // Mark as manually verified
            $profile->is_verified = true;
            $profile->verified_at = now();
            $profile->verified_by = auth()->id(); // Track which admin verified
            $profile->save();

            return redirect()->back()
                ->with('success', 'Customer phone verified successfully. Confirmation call completed.');

        } catch (\Exception $e) {
            Log::error('Admin customer phone verification failed', [
                'error' => $e->getMessage(),
                'customer_id' => $customer->id,
                'admin_id' => auth()->id()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to verify customer phone');
        }
    }
}
