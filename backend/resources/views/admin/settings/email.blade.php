@extends('layouts.admin')

@section('title', 'Email Settings - BixCash Admin')
@section('page-title', 'Email Settings')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-[#021c47]">Email Settings</h1>
        <p class="text-gray-500 mt-1">Configure SMTP for sending customer notifications</p>
    </div>

    <!-- Gmail Help -->
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
        <h3 class="font-semibold text-blue-900 mb-2">📧 Using Gmail? Important</h3>
        <p class="text-blue-700 text-sm mb-2"><strong>Gmail requires an App-Specific Password</strong>, not your regular password.</p>
        <ol class="text-blue-700 text-sm ml-4 list-decimal space-y-1">
            <li>Go to <a href="https://myaccount.google.com/security" target="_blank" class="underline">Google Account Security</a></li>
            <li>Enable <strong>2-Step Verification</strong></li>
            <li>Go to <a href="https://myaccount.google.com/apppasswords" target="_blank" class="underline">App Passwords</a></li>
            <li>Create App Password for "Mail" and use that below</li>
        </ol>
        <p class="text-blue-600 text-xs mt-2"><strong>Gmail SMTP:</strong> Host: smtp.gmail.com, Port: 587, Encryption: TLS</p>
    </div>

    <form method="POST" action="{{ route('admin.settings.email.update') }}" class="space-y-6">
        @csrf @method('PUT')

        <!-- SMTP Settings -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <h2 class="font-semibold text-[#021c47] mb-4 pb-2 border-b border-gray-200">SMTP Server Settings</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">SMTP Host <span class="text-red-500">*</span></label>
                    <input type="text" name="smtp_host" value="{{ $settings['smtp_host']->value ?? '' }}" required placeholder="smtp.gmail.com"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">SMTP Port <span class="text-red-500">*</span></label>
                    <input type="number" name="smtp_port" value="{{ $settings['smtp_port']->value ?? '587' }}" required min="1" max="65535" placeholder="587"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">SMTP Username <span class="text-red-500">*</span></label>
                    <input type="text" name="smtp_username" value="{{ $settings['smtp_username']->value ?? '' }}" required placeholder="your-email@gmail.com"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">SMTP Password <span class="text-red-500">*</span></label>
                    <input type="password" name="smtp_password" value="{{ $settings['smtp_password']->value ?? '' }}" placeholder="••••••••"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20">
                    <p class="mt-1 text-xs text-red-600">⚠️ Gmail: Use App Password, not regular password</p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Encryption <span class="text-red-500">*</span></label>
                    <select name="smtp_encryption" required class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20">
                        <option value="tls" {{ ($settings['smtp_encryption']->value ?? 'tls') == 'tls' ? 'selected' : '' }}>TLS (Recommended)</option>
                        <option value="ssl" {{ ($settings['smtp_encryption']->value ?? '') == 'ssl' ? 'selected' : '' }}>SSL</option>
                        <option value="none" {{ ($settings['smtp_encryption']->value ?? '') == 'none' ? 'selected' : '' }}>None</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Email Info -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <h2 class="font-semibold text-[#021c47] mb-4 pb-2 border-b border-gray-200">Email Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">From Email <span class="text-red-500">*</span></label>
                    <input type="email" name="from_address" value="{{ $settings['from_address']->value ?? '' }}" required placeholder="noreply@bixcash.com"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">From Name <span class="text-red-500">*</span></label>
                    <input type="text" name="from_name" value="{{ $settings['from_name']->value ?? 'BixCash' }}" required placeholder="BixCash"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Admin Email <span class="text-red-500">*</span></label>
                    <input type="email" name="admin_email" value="{{ $settings['admin_email']->value ?? '' }}" required placeholder="admin@bixcash.com"
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20">
                    <p class="mt-1 text-xs text-gray-500">Receives customer query notifications</p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-4">
            <button type="submit" class="px-6 py-2.5 bg-[#021c47] text-white rounded-lg hover:bg-[#93db4d] hover:text-[#021c47] transition-colors font-medium">Save Email Settings</button>
            <button type="button" onclick="document.getElementById('testForm').classList.toggle('hidden')" class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">Test Configuration</button>
        </div>
    </form>

    <!-- Test Email -->
    <div id="testForm" class="hidden bg-amber-50 border border-amber-200 rounded-xl p-4">
        <h3 class="font-semibold text-amber-900 mb-2">Test Email Configuration</h3>
        <form method="POST" action="{{ route('admin.settings.email.test') }}" class="flex gap-4 items-end">
            @csrf
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Test Email Address</label>
                <input type="email" name="test_email" required placeholder="test@example.com" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20">
            </div>
            <button type="submit" class="px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition-colors font-medium">Send Test</button>
        </form>
    </div>
</div>
@endsection
