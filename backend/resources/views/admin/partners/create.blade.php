@extends('layouts.admin')

@section('title', 'Create New Partner - BixCash Admin')
@section('page-title', 'Create New Partner')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[#021c47]">Create New Partner</h1>
                <p class="text-gray-500 mt-1">Add a new business partner account</p>
            </div>
            <a href="{{ route('admin.partners.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-200 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to Partners
            </a>
        </div>

        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg">
                <p class="text-sm text-red-700 font-medium">Please fix the following errors:</p>
                <ul class="list-disc pl-5 mt-2 text-sm text-red-600">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.partners.store') }}" id="createPartnerForm" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left: Form (2 cols) -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Business Details Card -->
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-5 py-3 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-sm font-semibold text-[#021c47] uppercase tracking-wider">Business Details</h3>
                        </div>
                        <div class="p-5 space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="business_name" class="block text-sm font-medium text-gray-700 mb-1">Business Name <span class="text-red-500">*</span></label>
                                    <input type="text" id="business_name" name="business_name" required
                                           value="{{ old('business_name') }}" placeholder="e.g., KFC Lahore"
                                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors">
                                </div>
                                <div>
                                    <label for="business_type" class="block text-sm font-medium text-gray-700 mb-1">Business Type <span class="text-red-500">*</span></label>
                                    <select id="business_type" name="business_type" required
                                            class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors">
                                        <option value="">Select business type</option>
                                        @foreach(['Restaurant', 'Retail', 'Cafe', 'Grocery', 'Fashion', 'Electronics', 'Salon', 'Pharmacy', 'Services', 'Other'] as $type)
                                            <option value="{{ $type }}" {{ old('business_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="commission_rate" class="block text-sm font-medium text-gray-700 mb-1">Commission Rate (%)</label>
                                    <input type="number" id="commission_rate" name="commission_rate"
                                           value="{{ old('commission_rate', 0) }}" placeholder="0.00" min="0" max="100" step="0.01"
                                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors">
                                    <p class="mt-1 text-xs text-gray-400">Percentage the partner pays BixCash (0-100%)</p>
                                </div>
                                <div>
                                    <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                    <input type="text" id="city" name="city"
                                           value="{{ old('city') }}" placeholder="e.g., Lahore"
                                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact & Verification Card -->
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-5 py-3 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-sm font-semibold text-[#021c47] uppercase tracking-wider">Contact & Verification</h3>
                        </div>
                        <div class="p-5 space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="contact_person_name" class="block text-sm font-medium text-gray-700 mb-1">Contact Person <span class="text-red-500">*</span></label>
                                    <input type="text" id="contact_person_name" name="contact_person_name" required
                                           value="{{ old('contact_person_name') }}" placeholder="Full name"
                                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors">
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <input type="email" id="email" name="email"
                                           value="{{ old('email') }}" placeholder="partner@email.com"
                                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors">
                                </div>
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Mobile Number <span class="text-red-500">*</span></label>
                                <div class="flex gap-2">
                                    <div class="px-3 py-2 bg-gray-100 border border-gray-200 rounded-lg font-medium text-gray-600 text-sm">+92</div>
                                    <input type="text" id="phone" name="phone" required maxlength="10" pattern="[0-9]{10}"
                                           value="{{ old('phone') }}" placeholder="3001234567"
                                           class="flex-1 px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors">
                                </div>
                                <div class="flex gap-2 mt-2">
                                    <button type="button" id="sendOtpBtn" onclick="sendPartnerOtp()"
                                            class="flex-1 px-3 py-2 bg-[#021c47] text-white font-medium rounded-lg hover:bg-[#93db4d] hover:text-[#021c47] transition-colors flex items-center justify-center gap-2 text-sm">
                                        <span id="otpBtnIcon">📤</span>
                                        <span id="otpBtnText">Send OTP</span>
                                    </button>
                                    <button type="button" id="setPinBtn" onclick="openSetPinModal()"
                                            class="flex-1 px-3 py-2 bg-[#93db4d] text-[#021c47] font-medium rounded-lg hover:bg-[#7fc93d] transition-colors flex items-center justify-center gap-2 text-sm">
                                        <span id="pinBtnIcon">🔐</span>
                                        <span id="pinBtnText">Set PIN</span>
                                    </button>
                                </div>
                                <p class="mt-1 text-xs text-gray-400">Enter 10-digit mobile number without +92</p>
                            </div>

                            <div>
                                <label for="business_address" class="block text-sm font-medium text-gray-700 mb-1">Business Address</label>
                                <input type="text" id="business_address" name="business_address"
                                       value="{{ old('business_address') }}" placeholder="Complete business address"
                                       class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors">
                            </div>
                        </div>
                    </div>

                    <!-- Logo Card -->
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-5 py-3 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-sm font-semibold text-[#021c47] uppercase tracking-wider">Logo</h3>
                        </div>
                        <div class="p-5 space-y-4">
                            <div>
                                <label for="logo" class="block text-sm font-medium text-gray-700 mb-1">Business Logo</label>
                                <input type="file" id="logo" name="logo" accept="image/jpeg,image/jpg,image/png" onchange="previewLogo(event)"
                                       class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-[#021c47] file:text-white hover:file:bg-[#93db4d] hover:file:text-[#021c47] file:cursor-pointer file:transition-colors border border-gray-200 rounded-lg">
                                <p class="mt-1 text-xs text-gray-400">JPG or PNG, max 2MB</p>
                                <div id="logoPreview" class="mt-2 hidden">
                                    <img id="logoPreviewImg" src="" alt="Preview" class="w-16 h-16 rounded-lg object-cover border-2 border-gray-200">
                                </div>
                            </div>

                            <!-- Auto-approve -->
                            <label class="flex items-center gap-3 cursor-pointer pt-2 border-t border-gray-100">
                                <input type="checkbox" name="auto_approve" value="1" {{ old('auto_approve', '1') ? 'checked' : '' }}
                                       class="w-4 h-4 rounded border-gray-300 text-green-500 focus:ring-green-400">
                                <span class="text-sm font-medium text-gray-700">Auto-approve partner <span class="text-gray-400 font-normal">(immediately active)</span></span>
                            </label>
                        </div>
                    </div>

                    <!-- Hidden PIN Field -->
                    <input type="hidden" id="partner_pin" name="partner_pin" value="">

                    <!-- Submit -->
                    <div class="flex gap-3">
                        <button type="submit" class="px-6 py-2.5 bg-[#021c47] text-white rounded-lg font-medium hover:bg-[#93db4d] hover:text-[#021c47] transition-colors">
                            Create Partner
                        </button>
                        <a href="{{ route('admin.partners.index') }}" class="px-6 py-2.5 border border-gray-200 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                            Cancel
                        </a>
                    </div>
                </div>

                <!-- Right: Sidebar (1 col) -->
                <div class="space-y-6">
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden sticky top-4">
                        <div class="px-5 py-3 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-sm font-semibold text-[#021c47] uppercase tracking-wider">What Happens Next?</h3>
                        </div>
                        <div class="p-5">
                            <div class="space-y-4 text-sm">
                                <div class="flex gap-3">
                                    <div class="flex-shrink-0 w-6 h-6 bg-[#021c47] text-white rounded-full flex items-center justify-center text-xs font-bold">1</div>
                                    <p class="text-gray-600">Partner account is created with the details you provide.</p>
                                </div>
                                <div class="flex gap-3">
                                    <div class="flex-shrink-0 w-6 h-6 bg-[#021c47] text-white rounded-full flex items-center justify-center text-xs font-bold">2</div>
                                    <p class="text-gray-600">If auto-approved, partner can log in immediately. Otherwise, requires manual approval.</p>
                                </div>
                                <div class="flex gap-3">
                                    <div class="flex-shrink-0 w-6 h-6 bg-[#021c47] text-white rounded-full flex items-center justify-center text-xs font-bold">3</div>
                                    <p class="text-gray-600">Partner can start accepting transactions from customers.</p>
                                </div>
                            </div>

                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <p class="text-xs text-gray-400">OTP verification and PIN setup are optional but recommended for security.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- OTP Modal --}}
    <div id="otpModal" class="hidden fixed inset-0 bg-black/50 z-50 items-center justify-center">
        <div class="bg-white rounded-xl shadow-xl max-w-sm w-full mx-4 p-6">
            <h3 class="text-lg font-bold text-[#021c47] mb-1">Verify Phone Number</h3>
            <p class="text-sm text-gray-500 mb-4">Enter the 6-digit code sent to <strong id="otpPhoneDisplay"></strong></p>
            <div class="flex gap-2 justify-center mb-4">
                @for($i = 1; $i <= 6; $i++)
                    <input type="text" id="otp{{ $i }}" maxlength="1"
                           class="w-10 h-12 text-center text-xl font-bold border-2 border-gray-200 rounded-lg focus:border-[#93db4d] focus:outline-none"
                           onkeyup="moveOtpFocus(event, {{ $i }})" onkeydown="handleOtpBackspace(event, {{ $i }})">
                @endfor
            </div>
            <div id="otpError" class="hidden bg-red-50 text-red-600 p-3 rounded-lg text-sm mb-4"></div>
            <div class="flex gap-3">
                <button type="button" onclick="closeOtpModal()" class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200">Cancel</button>
                <button type="button" id="verifyOtpBtn" onclick="verifyPartnerOtp()" class="flex-1 px-4 py-2.5 bg-[#021c47] text-white font-medium rounded-lg hover:bg-[#93db4d] hover:text-[#021c47]">Verify</button>
            </div>
            <div class="text-center mt-3">
                <button type="button" onclick="resendPartnerOtp()" class="text-sm text-[#021c47] hover:underline">Resend OTP</button>
            </div>
        </div>
    </div>

    {{-- PIN Modal --}}
    <div id="pinModal" class="hidden fixed inset-0 bg-black/50 z-50 items-center justify-center">
        <div class="bg-white rounded-xl shadow-xl max-w-sm w-full mx-4 p-6">
            <h3 class="text-lg font-bold text-[#021c47] mb-1">Set Partner PIN</h3>
            <p class="text-sm text-gray-500 mb-4">Create a 4-digit PIN for authentication</p>
            <div class="mb-4">
                <label class="block text-sm font-medium text-[#021c47] mb-2">Enter PIN</label>
                <div class="flex gap-2 justify-center">
                    @for($i = 1; $i <= 4; $i++)
                        <input type="password" id="pin{{ $i }}" maxlength="1"
                               class="w-12 h-14 text-center text-2xl font-bold border-2 border-gray-200 rounded-lg focus:border-[#93db4d] focus:outline-none"
                               onkeyup="movePinFocus(event, {{ $i }})" onkeydown="handlePinBackspace(event, {{ $i }})">
                    @endfor
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-[#021c47] mb-2">Confirm PIN</label>
                <div class="flex gap-2 justify-center">
                    @for($i = 1; $i <= 4; $i++)
                        <input type="password" id="pinConfirm{{ $i }}" maxlength="1"
                               class="w-12 h-14 text-center text-2xl font-bold border-2 border-gray-200 rounded-lg focus:border-[#93db4d] focus:outline-none"
                               onkeyup="movePinConfirmFocus(event, {{ $i }})" onkeydown="handlePinConfirmBackspace(event, {{ $i }})">
                    @endfor
                </div>
            </div>
            <div id="pinError" class="hidden bg-red-50 text-red-600 p-3 rounded-lg text-sm mb-4"></div>
            <div class="flex gap-3">
                <button type="button" onclick="closePinModal()" class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200">Cancel</button>
                <button type="button" onclick="setPartnerPin()" class="flex-1 px-4 py-2.5 bg-[#93db4d] text-[#021c47] font-medium rounded-lg hover:bg-[#7fc93d]">Set PIN</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '').substring(0, 10);
        });
    }

    function previewLogo(event) {
        const file = event.target.files[0];
        if (!file) return;
        if (file.size > 2 * 1024 * 1024) { alert('File size must be less than 2MB'); event.target.value = ''; return; }
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!allowedTypes.includes(file.type)) { alert('Only JPG and PNG images are allowed'); event.target.value = ''; return; }
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('logoPreviewImg').src = e.target.result;
            document.getElementById('logoPreview').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }

    let currentPhone = '';

    async function sendPartnerOtp() {
        const phone = document.getElementById('phone').value.trim();
        if (phone.length !== 10) { alert('Please enter a valid 10-digit mobile number'); return; }
        currentPhone = phone;
        const btn = document.getElementById('sendOtpBtn');
        const txt = document.getElementById('otpBtnText');
        try {
            btn.disabled = true; txt.textContent = 'Sending...';
            const response = await fetch('{{ route('admin.partners.send-registration-otp') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ phone: phone })
            });
            const data = await response.json();
            if (data.success) {
                document.getElementById('otpPhoneDisplay').textContent = data.phone;
                openOtpModal();
            } else {
                alert('Error: ' + (data.message || 'Failed to send OTP'));
            }
        } catch (error) {
            alert('Error sending OTP: ' + error.message);
        } finally {
            btn.disabled = false; txt.textContent = 'Send OTP';
        }
    }

    function openOtpModal() {
        document.getElementById('otpModal').style.display = 'flex';
        clearOtpInputs();
        document.getElementById('otp1').focus();
    }
    function closeOtpModal() { document.getElementById('otpModal').style.display = 'none'; clearOtpInputs(); }
    function clearOtpInputs() { for (let i = 1; i <= 6; i++) document.getElementById('otp' + i).value = ''; document.getElementById('otpError').classList.add('hidden'); }

    function moveOtpFocus(event, position) {
        if (event.target.value.length === 1 && position < 6) document.getElementById('otp' + (position + 1)).focus();
    }
    function handleOtpBackspace(event, position) {
        if (event.key === 'Backspace' && position > 1 && event.target.value === '') document.getElementById('otp' + (position - 1)).focus();
        else if (event.key === 'Enter') verifyPartnerOtp();
    }

    async function verifyPartnerOtp() {
        let otp = '';
        for (let i = 1; i <= 6; i++) otp += document.getElementById('otp' + i).value;
        if (otp.length !== 6) { showOtpError('Please enter all 6 digits'); return; }
        const btn = document.getElementById('verifyOtpBtn');
        try {
            btn.disabled = true; btn.textContent = 'Verifying...';
            const response = await fetch('{{ route('admin.partners.verify-registration-otp') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ phone: currentPhone, otp: otp })
            });
            const data = await response.json();
            if (data.success) {
                closeOtpModal();
                document.getElementById('otpBtnIcon').textContent = '✅';
                document.getElementById('otpBtnText').textContent = 'Verified';
                document.getElementById('sendOtpBtn').classList.remove('bg-[#021c47]', 'hover:bg-[#93db4d]', 'hover:text-[#021c47]');
                document.getElementById('sendOtpBtn').classList.add('bg-green-500');
                document.getElementById('sendOtpBtn').disabled = true;
                alert('Phone verified successfully!');
            } else {
                showOtpError(data.message || 'Invalid OTP');
            }
        } catch (error) {
            showOtpError('Error verifying OTP: ' + error.message);
        } finally {
            btn.disabled = false; btn.textContent = 'Verify';
        }
    }
    function resendPartnerOtp() { sendPartnerOtp(); }
    function showOtpError(message) { const el = document.getElementById('otpError'); el.textContent = message; el.classList.remove('hidden'); }

    function openSetPinModal() {
        document.getElementById('pinModal').style.display = 'flex';
        clearPinInputs();
        document.getElementById('pin1').focus();
    }
    function closePinModal() { document.getElementById('pinModal').style.display = 'none'; clearPinInputs(); }
    function clearPinInputs() { for (let i = 1; i <= 4; i++) { document.getElementById('pin' + i).value = ''; document.getElementById('pinConfirm' + i).value = ''; } document.getElementById('pinError').classList.add('hidden'); }

    function movePinFocus(event, position) { if (event.target.value.length === 1 && position < 4) document.getElementById('pin' + (position + 1)).focus(); }
    function handlePinBackspace(event, position) { if (event.key === 'Backspace' && position > 1 && event.target.value === '') document.getElementById('pin' + (position - 1)).focus(); else if (event.key === 'Enter' && position === 4) document.getElementById('pinConfirm1').focus(); }
    function movePinConfirmFocus(event, position) { if (event.target.value.length === 1 && position < 4) document.getElementById('pinConfirm' + (position + 1)).focus(); }
    function handlePinConfirmBackspace(event, position) { if (event.key === 'Backspace' && position > 1 && event.target.value === '') document.getElementById('pinConfirm' + (position - 1)).focus(); else if (event.key === 'Enter' && position === 4) setPartnerPin(); }

    function setPartnerPin() {
        let pin = '', pinConfirm = '';
        for (let i = 1; i <= 4; i++) { pin += document.getElementById('pin' + i).value; pinConfirm += document.getElementById('pinConfirm' + i).value; }
        if (pin.length !== 4) { showPinError('Please enter a 4-digit PIN'); return; }
        if (pinConfirm.length !== 4) { showPinError('Please confirm your PIN'); return; }
        if (pin !== pinConfirm) { showPinError('PINs do not match'); return; }
        if (!/^\d{4}$/.test(pin)) { showPinError('PIN must be 4 digits (0-9)'); return; }
        document.getElementById('partner_pin').value = pin;
        document.getElementById('pinBtnIcon').textContent = '✅';
        document.getElementById('pinBtnText').textContent = 'PIN Set';
        document.getElementById('setPinBtn').classList.remove('bg-[#93db4d]', 'hover:bg-[#7fc93d]');
        document.getElementById('setPinBtn').classList.add('bg-green-500', 'text-white');
        closePinModal();
        alert('PIN set successfully!');
    }
    function showPinError(message) { const el = document.getElementById('pinError'); el.textContent = message; el.classList.remove('hidden'); }

    // Close modals on outside click
    document.getElementById('otpModal')?.addEventListener('click', function(e) { if (e.target === this) closeOtpModal(); });
    document.getElementById('pinModal')?.addEventListener('click', function(e) { if (e.target === this) closePinModal(); });
</script>
@endpush
