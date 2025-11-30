<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use App\Services\WhatsAppOtpService;
use Illuminate\Http\Request;

class WhatsAppSettingsController extends Controller
{
    /**
     * Display the WhatsApp OTP settings form
     */
    public function index()
    {
        $settings = [
            'api_key_set' => !empty(SystemSetting::get('whatsapp_api_key')),
            'api_url' => SystemSetting::get('whatsapp_api_url', 'https://whatsapp.fimm.app/api'),
            'enabled' => (bool) SystemSetting::get('whatsapp_otp_enabled', false),
            'primary_provider' => SystemSetting::get('primary_otp_provider', 'firebase'),
            'login_2fa_enabled' => (bool) SystemSetting::get('login_2fa_enabled', false),
            'transaction_otp_enabled' => (bool) SystemSetting::get('transaction_otp_enabled', false),
            'transaction_otp_threshold' => SystemSetting::get('transaction_otp_threshold', 0),
        ];

        return view('admin.settings.whatsapp', compact('settings'));
    }

    /**
     * Save WhatsApp OTP settings
     */
    public function save(Request $request)
    {
        $validated = $request->validate([
            'whatsapp_api_key' => 'nullable|string|max:255',
            'whatsapp_api_url' => 'required|url|max:255',
            'whatsapp_otp_enabled' => 'sometimes|boolean',
            'primary_otp_provider' => 'required|in:firebase,whatsapp',
            'login_2fa_enabled' => 'sometimes|boolean',
            'transaction_otp_enabled' => 'sometimes|boolean',
            'transaction_otp_threshold' => 'nullable|numeric|min:0',
        ]);

        // Save API key (encrypted) - only if a new key is provided
        if ($request->filled('whatsapp_api_key') && !str_contains($request->whatsapp_api_key, '•')) {
            SystemSetting::set(
                'whatsapp_api_key',
                encrypt($request->whatsapp_api_key),
                'encrypted',
                'whatsapp',
                'WhatsApp OTP API key'
            );
        }

        // Save API URL
        SystemSetting::set(
            'whatsapp_api_url',
            $validated['whatsapp_api_url'],
            'text',
            'whatsapp',
            'WhatsApp OTP API base URL'
        );

        // Save enabled status
        SystemSetting::set(
            'whatsapp_otp_enabled',
            $request->has('whatsapp_otp_enabled') ? '1' : '0',
            'boolean',
            'whatsapp',
            'WhatsApp OTP enabled'
        );

        // Save primary provider
        SystemSetting::set(
            'primary_otp_provider',
            $validated['primary_otp_provider'],
            'text',
            'otp',
            'Primary OTP provider (firebase or whatsapp)'
        );

        // Save Login 2FA setting
        SystemSetting::set(
            'login_2fa_enabled',
            $request->has('login_2fa_enabled') ? '1' : '0',
            'boolean',
            'otp',
            'Enable 2FA for all logins'
        );

        // Save Transaction OTP settings
        SystemSetting::set(
            'transaction_otp_enabled',
            $request->has('transaction_otp_enabled') ? '1' : '0',
            'boolean',
            'otp',
            'Enable OTP for transactions above threshold'
        );

        SystemSetting::set(
            'transaction_otp_threshold',
            $validated['transaction_otp_threshold'] ?? 0,
            'number',
            'otp',
            'Transaction amount threshold for OTP verification'
        );

        return redirect()
            ->route('admin.settings.whatsapp')
            ->with('success', 'WhatsApp OTP settings saved successfully.');
    }

    /**
     * Test WhatsApp API connection
     */
    public function testConnection()
    {
        $service = app(WhatsAppOtpService::class);
        $result = $service->testConnection();

        return response()->json($result);
    }
}
