@extends('layouts.admin')

@section('title', 'WhatsApp OTP Settings - BixCash Admin')
@section('page-title', 'WhatsApp OTP Settings')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-[#021c47]">WhatsApp OTP Settings</h1>
        <p class="text-gray-500 mt-1">Configure WhatsApp OTP verification for enhanced security</p>
    </div>

    <!-- Info -->
    <div class="bg-green-50 border border-green-200 rounded-xl p-4">
        <h3 class="font-semibold text-green-900 mb-2">📱 WhatsApp OTP Configuration</h3>
        <p class="text-green-700 text-sm mb-2">OTPs can be sent via WhatsApp for:</p>
        <ul class="text-green-700 text-sm ml-4 list-disc space-y-1">
            <li><strong>User Registration:</strong> Verify phone numbers during sign-up</li>
            <li><strong>Login 2FA:</strong> Additional security layer for logins</li>
            <li><strong>Transaction Verification:</strong> Confirm high-value transactions</li>
        </ul>
    </div>

    <form method="POST" action="{{ route('admin.settings.whatsapp.save') }}" class="space-y-6">
        @csrf

        <!-- API Config -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <h2 class="font-semibold text-[#021c47] mb-4 pb-2 border-b border-gray-200">API Configuration</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp API Key</label>
                    <div class="flex gap-2">
                        <input type="password" name="whatsapp_api_key" id="whatsapp_api_key"
                               value="{{ $settings['api_key_set'] ? '••••••••••••••••••••••••' : '' }}"
                               placeholder="Enter your WhatsApp API key"
                               class="flex-1 px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20">
                        <button type="button" onclick="toggleApiKeyVisibility()" class="px-3 py-2 border border-gray-200 rounded-lg hover:bg-gray-50">
                            <svg id="eye-icon" class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Get your API key from <a href="https://whatsapp.fimm.app" target="_blank" class="text-[#021c47] underline">whatsapp.fimm.app</a></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">API Base URL</label>
                    <input type="url" name="whatsapp_api_url" value="{{ old('whatsapp_api_url', $settings['api_url']) }}"
                           placeholder="https://whatsapp.fimm.app/api" required
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20">
                    <p class="mt-1 text-xs text-gray-500">Default: https://whatsapp.fimm.app/api</p>
                </div>
            </div>
        </div>

        <!-- Enable Toggle -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="whatsapp_otp_enabled" value="1" {{ $settings['enabled'] ? 'checked' : '' }}
                       class="w-5 h-5 rounded border-gray-300 text-[#93db4d] focus:ring-[#93db4d]">
                <div>
                    <span class="text-lg font-semibold text-[#021c47]">Enable WhatsApp OTP Service</span>
                    <p class="text-sm text-gray-500">Enable to allow sending OTPs via WhatsApp</p>
                </div>
            </label>
        </div>

        <!-- Primary Provider -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <h2 class="font-semibold text-[#021c47] mb-4 pb-2 border-b border-gray-200">Primary OTP Provider</h2>
            <div class="flex gap-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="primary_otp_provider" value="firebase" {{ ($settings['primary_provider'] ?? 'firebase') == 'firebase' ? 'checked' : '' }}
                           class="w-4 h-4 text-[#93db4d] border-gray-300 focus:ring-[#93db4d]">
                    <span class="text-sm text-gray-700">Firebase SMS (Default)</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="primary_otp_provider" value="whatsapp" {{ ($settings['primary_provider'] ?? 'firebase') == 'whatsapp' ? 'checked' : '' }}
                           class="w-4 h-4 text-[#93db4d] border-gray-300 focus:ring-[#93db4d]">
                    <span class="text-sm text-gray-700">WhatsApp</span>
                </label>
            </div>
            <p class="mt-2 text-xs text-gray-500">Choose which provider to use as default for OTP delivery</p>
        </div>

        <!-- OTP Settings -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <h2 class="font-semibold text-[#021c47] mb-4 pb-2 border-b border-gray-200">OTP Settings</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">OTP Expiry (minutes)</label>
                    <input type="number" name="otp_expiry_minutes" value="{{ old('otp_expiry_minutes', $settings['otp_expiry'] ?? 5) }}"
                           min="1" max="30" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Max Attempts</label>
                    <input type="number" name="max_otp_attempts" value="{{ old('max_otp_attempts', $settings['max_attempts'] ?? 3) }}"
                           min="1" max="10" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20">
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-4">
            <button type="submit" class="px-6 py-2.5 bg-[#021c47] text-white rounded-lg hover:bg-[#93db4d] hover:text-[#021c47] transition-colors font-medium">Save Settings</button>
            <button type="button" onclick="testWhatsAppConnection()" class="px-6 py-2.5 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors font-medium">Test Connection</button>
        </div>
    </form>
</div>

<script>
function toggleApiKeyVisibility() {
    const input = document.getElementById('whatsapp_api_key');
    input.type = input.type === 'password' ? 'text' : 'password';
}
function testWhatsAppConnection() {
    alert('Test connection functionality would go here');
}
</script>
@endsection
