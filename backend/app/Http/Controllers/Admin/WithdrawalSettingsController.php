<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WithdrawalSettings;
use Illuminate\Http\Request;

class WithdrawalSettingsController extends Controller
{
    /**
     * Display the withdrawal settings form
     */
    public function index()
    {
        $settings = WithdrawalSettings::getSettings();
        
        return view('admin.settings.withdrawals', compact('settings'));
    }

    /**
     * Update the withdrawal settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'min_amount' => 'required|numeric|min:0',
            'max_per_withdrawal' => 'required|numeric|min:0|gte:min_amount',
            'max_per_day' => 'required|numeric|min:0|gte:max_per_withdrawal',
            'max_per_month' => 'required|numeric|min:0|gte:max_per_day',
            'min_gap_hours' => 'required|integer|min:0|max:168',
            'enabled' => 'sometimes|boolean',
            'processing_message' => 'nullable|string|max:1000',
        ]);

        // Convert enabled checkbox to boolean (unchecked = false)
        $validated['enabled'] = $request->has('enabled');

        $settings = WithdrawalSettings::getSettings();
        $settings->update($validated);

        return redirect()
            ->route('admin.settings.withdrawals')
            ->with('success', 'Withdrawal settings updated successfully.');
    }
}
