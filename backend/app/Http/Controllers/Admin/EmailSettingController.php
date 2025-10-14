<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailSetting;
use Illuminate\Http\Request;

class EmailSettingController extends Controller
{
    /**
     * Display email settings form
     */
    public function index()
    {
        $settings = EmailSetting::all()->keyBy('key');

        return view('admin.settings.email', compact('settings'));
    }

    /**
     * Update email settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'smtp_host' => 'required|string|max:255',
            'smtp_port' => 'required|numeric|min:1|max:65535',
            'smtp_username' => 'required|string|max:255',
            'smtp_password' => 'nullable|string|max:255',
            'smtp_encryption' => 'required|in:tls,ssl,none',
            'from_address' => 'required|email|max:255',
            'from_name' => 'required|string|max:255',
            'admin_email' => 'required|email|max:255',
        ]);

        foreach ($validated as $key => $value) {
            $type = in_array($key, ['smtp_port']) ? 'number' : 'text';
            $type = in_array($key, ['smtp_password']) ? 'password' : $type;

            EmailSetting::set($key, $value, $type, 'smtp');
        }

        // Clear config cache to apply new settings
        \Artisan::call('config:clear');

        // Restart queue workers to pick up new email configuration
        try {
            exec('sudo supervisorctl restart bixcash-worker:* 2>&1', $output, $returnCode);
        } catch (\Exception $e) {
            // Queue workers restart failed, but settings were saved
        }

        return back()->with('success', 'Email settings updated successfully! Queue workers restarted.');
    }

    /**
     * Test email configuration
     */
    public function test(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        try {
            // Apply current settings
            EmailSetting::applyToConfig();

            // Send test email
            \Mail::raw('This is a test email from BixCash. If you receive this, your email settings are configured correctly!', function($message) use ($request) {
                $message->to($request->test_email)
                        ->subject('BixCash Email Configuration Test');
            });

            return back()->with('success', 'Test email sent successfully to ' . $request->test_email);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send test email: ' . $e->getMessage());
        }
    }
}
