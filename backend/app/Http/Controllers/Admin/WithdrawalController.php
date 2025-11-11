<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WithdrawalRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WithdrawalController extends Controller
{
    /**
     * Display listing of all withdrawal requests with filters
     */
    public function index(Request $request)
    {
        $query = WithdrawalRequest::with(['user.customerProfile', 'processedBy'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search by customer name or phone
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        $withdrawals = $query->paginate(20);

        // Quick stats
        $stats = [
            'pending_count' => WithdrawalRequest::where('status', 'pending')->count(),
            'pending_amount' => WithdrawalRequest::where('status', 'pending')->sum('amount'),
            'processing_count' => WithdrawalRequest::where('status', 'processing')->count(),
            'processing_amount' => WithdrawalRequest::where('status', 'processing')->sum('amount'),
            'completed_today_count' => WithdrawalRequest::where('status', 'completed')
                ->whereDate('updated_at', Carbon::today())->count(),
            'completed_today_amount' => WithdrawalRequest::where('status', 'completed')
                ->whereDate('updated_at', Carbon::today())->sum('amount'),
        ];

        return view('admin.withdrawals.index', compact('withdrawals', 'stats'));
    }

    /**
     * Display details of a specific withdrawal request
     */
    public function show($id)
    {
        $withdrawal = WithdrawalRequest::with(['user.customerProfile', 'user.wallet', 'processedBy'])
            ->findOrFail($id);

        // Get customer's withdrawal history
        $withdrawalHistory = WithdrawalRequest::where('user_id', $withdrawal->user_id)
            ->where('id', '!=', $id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Account age in days
        $accountAge = Carbon::parse($withdrawal->user->created_at)->diffInDays(Carbon::now());

        return view('admin.withdrawals.show', compact('withdrawal', 'withdrawalHistory', 'accountAge'));
    }

    /**
     * Approve a withdrawal request
     */
    public function approve(Request $request, $id)
    {
        $validated = $request->validate([
            'bank_reference' => 'required|string|max:255',
            'payment_date' => 'required|date|before_or_equal:today',
            'proof_of_payment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $withdrawal = WithdrawalRequest::where('status', 'pending')
            ->orWhere('status', 'processing')
            ->findOrFail($id);

        DB::beginTransaction();
        try {
            // Handle proof of payment upload
            if ($request->hasFile('proof_of_payment')) {
                $file = $request->file('proof_of_payment');
                $filename = 'withdrawal_' . $withdrawal->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('withdrawal-proofs', $filename, 'public');
                $validated['proof_of_payment'] = $path;
            }

            // Update withdrawal request
            $withdrawal->update([
                'status' => 'completed',
                'bank_reference' => $validated['bank_reference'],
                'payment_date' => $validated['payment_date'],
                'proof_of_payment' => $validated['proof_of_payment'] ?? null,
                'admin_notes' => $validated['admin_notes'] ?? null,
                'processed_by' => Auth::id(),
                'processed_at' => now(),
            ]);

            // Create wallet transaction for completed withdrawal
            $withdrawal->user->wallet->transactions()->create([
                'type' => 'withdrawal_completed',
                'amount' => -$withdrawal->amount,
                'balance_after' => $withdrawal->user->wallet->balance,
                'reference_type' => 'App\Models\WithdrawalRequest',
                'reference_id' => $withdrawal->id,
                'description' => "Withdrawal completed - Bank Ref: {$validated['bank_reference']}",
            ]);

            DB::commit();

            // Send email notification to customer
            try {
                if ($withdrawal->user->email) {
                    \Mail::to($withdrawal->user->email)->send(new \App\Mail\WithdrawalApprovedMail($withdrawal));
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send withdrawal approved email: ' . $e->getMessage());
            }

            return redirect()
                ->route('admin.withdrawals.show', $withdrawal->id)
                ->with('success', 'Withdrawal approved successfully. Customer has been notified via email.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to approve withdrawal: ' . $e->getMessage());
        }
    }

    /**
     * Reject a withdrawal request and refund the amount
     */
    public function reject(Request $request, $id)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $withdrawal = WithdrawalRequest::where('status', 'pending')
            ->orWhere('status', 'processing')
            ->findOrFail($id);

        DB::beginTransaction();
        try {
            // REFUND the wallet balance
            $wallet = $withdrawal->user->wallet;
            $wallet->credit(
                $withdrawal->amount,
                'withdrawal_rejected',
                'App\Models\WithdrawalRequest',
                $withdrawal->id,
                "Withdrawal rejected: {$validated['rejection_reason']}"
            );

            // Update withdrawal request
            $withdrawal->update([
                'status' => 'rejected',
                'rejection_reason' => $validated['rejection_reason'],
                'admin_notes' => $validated['admin_notes'] ?? null,
                'processed_by' => Auth::id(),
                'processed_at' => now(),
            ]);

            DB::commit();

            // Send email notification to customer
            try {
                if ($withdrawal->user->email) {
                    \Mail::to($withdrawal->user->email)->send(new \App\Mail\WithdrawalRejectedMail($withdrawal));
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send withdrawal rejected email: ' . $e->getMessage());
            }

            return redirect()
                ->route('admin.withdrawals.show', $withdrawal->id)
                ->with('success', 'Withdrawal rejected and amount refunded to customer wallet. Customer has been notified via email.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to reject withdrawal: ' . $e->getMessage());
        }
    }

    /**
     * Mark withdrawal as processing (optional intermediate status)
     */
    public function markProcessing($id)
    {
        $withdrawal = WithdrawalRequest::where('status', 'pending')->findOrFail($id);
        
        $withdrawal->update([
            'status' => 'processing',
            'processed_by' => Auth::id(),
        ]);

        return back()->with('success', 'Withdrawal marked as processing.');
    }

    /**
     * Bulk approve multiple withdrawal requests
     */
    public function bulkApprove(Request $request)
    {
        $validated = $request->validate([
            'withdrawal_ids' => 'required|array|min:1',
            'withdrawal_ids.*' => 'required|integer|exists:withdrawal_requests,id',
            'bank_reference_prefix' => 'required|string|max:50',
            'payment_date' => 'required|date|before_or_equal:today',
        ]);

        $successCount = 0;
        $failCount = 0;

        DB::beginTransaction();
        try {
            foreach ($validated['withdrawal_ids'] as $index => $id) {
                $withdrawal = WithdrawalRequest::whereIn('status', ['pending', 'processing'])->find($id);

                if (!$withdrawal) {
                    $failCount++;
                    continue;
                }

                // Generate unique bank reference
                $bankRef = $validated['bank_reference_prefix'] . '-' . str_pad(($index + 1), 4, '0', STR_PAD_LEFT);

                $withdrawal->update([
                    'status' => 'completed',
                    'bank_reference' => $bankRef,
                    'payment_date' => $validated['payment_date'],
                    'processed_by' => Auth::id(),
                    'processed_at' => now(),
                ]);

                // Create wallet transaction
                $withdrawal->user->wallet->transactions()->create([
                    'type' => 'withdrawal_completed',
                    'amount' => -$withdrawal->amount,
                    'balance_after' => $withdrawal->user->wallet->balance,
                    'reference_type' => 'App\Models\WithdrawalRequest',
                    'reference_id' => $withdrawal->id,
                    'description' => "Withdrawal completed - Bank Ref: {$bankRef}",
                ]);

                // Send email
                try {
                    if ($withdrawal->user->email) {
                        \Mail::to($withdrawal->user->email)->send(new \App\Mail\WithdrawalApprovedMail($withdrawal));
                    }
                } catch (\Exception $e) {
                    \Log::error('Failed to send bulk approval email: ' . $e->getMessage());
                }

                $successCount++;
            }

            DB::commit();

            $message = "Bulk approval completed: {$successCount} approved";
            if ($failCount > 0) {
                $message .= ", {$failCount} failed (already processed or not found)";
            }

            return redirect()->route('admin.withdrawals.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Bulk approval failed: ' . $e->getMessage());
        }
    }

    /**
     * Bulk reject multiple withdrawal requests
     */
    public function bulkReject(Request $request)
    {
        $validated = $request->validate([
            'withdrawal_ids' => 'required|array|min:1',
            'withdrawal_ids.*' => 'required|integer|exists:withdrawal_requests,id',
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $successCount = 0;
        $failCount = 0;

        DB::beginTransaction();
        try {
            foreach ($validated['withdrawal_ids'] as $id) {
                $withdrawal = WithdrawalRequest::whereIn('status', ['pending', 'processing'])->find($id);

                if (!$withdrawal) {
                    $failCount++;
                    continue;
                }

                // Refund wallet
                $withdrawal->user->wallet->credit(
                    $withdrawal->amount,
                    'withdrawal_rejected',
                    'App\Models\WithdrawalRequest',
                    $withdrawal->id,
                    "Withdrawal rejected: {$validated['rejection_reason']}"
                );

                $withdrawal->update([
                    'status' => 'rejected',
                    'rejection_reason' => $validated['rejection_reason'],
                    'processed_by' => Auth::id(),
                    'processed_at' => now(),
                ]);

                // Send email
                try {
                    if ($withdrawal->user->email) {
                        \Mail::to($withdrawal->user->email)->send(new \App\Mail\WithdrawalRejectedMail($withdrawal));
                    }
                } catch (\Exception $e) {
                    \Log::error('Failed to send bulk rejection email: ' . $e->getMessage());
                }

                $successCount++;
            }

            DB::commit();

            $message = "Bulk rejection completed: {$successCount} rejected";
            if ($failCount > 0) {
                $message .= ", {$failCount} failed (already processed or not found)";
            }

            return redirect()->route('admin.withdrawals.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Bulk rejection failed: ' . $e->getMessage());
        }
    }

    /**
     * Export withdrawal data to CSV
     */
    public function export(Request $request)
    {
        $query = WithdrawalRequest::with(['user', 'processedBy'])->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $withdrawals = $query->get();

        $filename = 'withdrawals_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($withdrawals) {
            $file = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($file, [
                'ID',
                'Customer Name',
                'Phone',
                'Email',
                'Amount',
                'Status',
                'Bank Name',
                'Account Number',
                'Account Title',
                'IBAN',
                'Bank Reference',
                'Payment Date',
                'Requested Date',
                'Processed Date',
                'Processed By',
                'Rejection Reason',
                'Fraud Score',
            ]);

            // Data rows
            foreach ($withdrawals as $w) {
                fputcsv($file, [
                    $w->id,
                    $w->user->name,
                    $w->user->phone,
                    $w->user->email ?? 'N/A',
                    number_format($w->amount, 2),
                    ucfirst($w->status),
                    $w->bank_name,
                    $w->account_number,
                    $w->account_title,
                    $w->iban ?? 'N/A',
                    $w->bank_reference ?? 'N/A',
                    $w->payment_date ? \Carbon\Carbon::parse($w->payment_date)->format('Y-m-d') : 'N/A',
                    $w->created_at->format('Y-m-d H:i:s'),
                    $w->processed_at ? $w->processed_at->format('Y-m-d H:i:s') : 'N/A',
                    $w->processedBy->name ?? 'N/A',
                    $w->rejection_reason ?? 'N/A',
                    $w->fraud_score,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
