@extends('layouts.admin')

@section('title', 'Create New Partner - BixCash Admin')
@section('page-title', 'Create New Partner')

@section('content')
    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route('admin.partners.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-all duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Partners
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-[#021c47]">Add New Partner</h3>
            <p class="text-sm text-gray-500 mt-1">Create a new business partner account</p>
        </div>
        
        <div class="p-6">
            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg mb-6">
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

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    {{-- Business Name --}}
                    <div>
                        <label for="business_name" class="block text-sm font-medium text-[#021c47] mb-2">
                            Business Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="business_name" name="business_name" required
                               value="{{ old('business_name') }}" placeholder="e.g., KFC Lahore"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all">
                    </div>

                    {{-- Mobile Number --}}
                    <div>
                        <label for="phone" class="block text-sm font-medium text-[#021c47] mb-2">
                            Mobile Number <span class="text-red-500">*</span>
                        </label>
                        <div class="flex gap-2">
                            <div class="px-4 py-2.5 bg-gray-100 border border-gray-200 rounded-lg font-medium text-gray-600">+92</div>
                            <input type="text" id="phone" name="phone" required maxlength="10" pattern="[0-9]{10}"
                                   value="{{ old('phone') }}" placeholder="3001234567"
                                   class="flex-1 px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all">
                        </div>
                        <div class="flex gap-2 mt-2">
                            <button type="button" id="sendOtpBtn" onclick="sendPartnerOtp()"
                                    class="flex-1 px-4 py-2 bg-[#021c47] text-white font-medium rounded-lg hover:bg-[#93db4d] hover:text-[#021c47] transition-all flex items-center justify-center gap-2">
                                <span id="otpBtnIcon">📤</span>
                                <span id="otpBtnText">Send OTP</span>
                            </button>
                            <button type="button" id="setPinBtn" onclick="openSetPinModal()"
                                    class="flex-1 px-4 py-2 bg-[#93db4d] text-[#021c47] font-medium rounded-lg hover:bg-[#7fc93d] transition-all flex items-center justify-center gap-2">
                                <span id="pinBtnIcon">🔐</span>
                                <span id="pinBtnText">Set PIN</span>
                            </button>
                        </div>
                        <p class="mt-1 text-xs text-gray-400">Enter 10-digit mobile number without +92</p>
                    </div>

                    {{-- Contact Person Name --}}
                    <div>
                        <label for="contact_person_name" class="block text-sm font-medium text-[#021c47] mb-2">
                            Contact Person Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="contact_person_name" name="contact_person_name" required
                               value="{{ old('contact_person_name') }}" placeholder="Full name"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all">
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-[#021c47] mb-2">Email (Optional)</label>
                        <input type="email" id="email" name="email"
                               value="{{ old('email') }}" placeholder="partner@email.com"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all">
                    </div>

                    {{-- Business Type --}}
                    <div>
                        <label for="business_type" class="block text-sm font-medium text-[#021c47] mb-2">
                            Business Type <span class="text-red-500">*</span>
                        </label>
                        <select id="business_type" name="business_type" required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all">
                            <option value="">Select business type</option>
                            @foreach(['Restaurant', 'Retail', 'Cafe', 'Grocery', 'Fashion', 'Electronics', 'Salon', 'Pharmacy', 'Services', 'Other'] as $type)
                                <option value="{{ $type }}" {{ old('business_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- City --}}
                    <div>
                        <label for="city" class="block text-sm font-medium text-[#021c47] mb-2">City (Optional)</label>
                        <input type="text" id="city" name="city"
                               value="{{ old('city') }}" placeholder="e.g., Lahore"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all">
                    </div>

                    {{-- Commission Rate --}}
                    <div>
                        <label for="commission_rate" class="block text-sm font-medium text-[#021c47] mb-2">Commission Rate (%)</label>
                        <input type="number" id="commission_rate" name="commission_rate"
                               value="{{ old('commission_rate', 0) }}" placeholder="0.00" min="0" max="100" step="0.01"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all">
                        <p class="mt-1 text-xs text-gray-400">Percentage the partner pays BixCash (0-100%)</p>
                    </div>

                    {{-- Logo --}}
                    <div>
                        <label for="logo" class="block text-sm font-medium text-[#021c47] mb-2">Business Logo (Optional)</label>
                        <input type="file" id="logo" name="logo" accept="image/jpeg,image/jpg,image/png" onchange="previewLogo(event)"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d]">
                        <p class="mt-1 text-xs text-gray-400">JPG or PNG, max 2MB</p>
                        <div id="logoPreview" class="mt-2 hidden">
                            <img id="logoPreviewImg" src="" alt="Preview" class="w-16 h-16 rounded-lg object-cover border-2 border-gray-200">
                        </div>
                    </div>
                </div>

                {{-- Business Address --}}
                <div class="mb-6">
                    <label for="business_address" class="block text-sm font-medium text-[#021c47] mb-2">Business Address (Optional)</label>
                    <input type="text" id="business_address" name="business_address"
                           value="{{ old('business_address') }}" placeholder="Complete business address"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all">
                </div>

                {{-- Auto-Approve --}}
                <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-400 rounded-r-lg">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="auto_approve" value="1" {{ old('auto_approve', '1') ? 'checked' : '' }}
                               class="w-5 h-5 text-[#93db4d] rounded focus:ring-[#93db4d]">
                        <span class="text-sm font-medium text-[#021c47]">Auto-approve this partner (Partner will be immediately active)</span>
                    </label>
                    <p class="mt-1 ml-8 text-xs text-gray-500">If unchecked, partner will be created with "Pending" status.</p>
                </div>

                {{-- Hidden PIN Field --}}
                <input type="hidden" id="partner_pin" name="partner_pin" value="">

                {{-- Submit --}}
                <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.partners.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-all duration-200">
                        Cancel
                    </a>
                    <button type="submit" class="px-5 py-2.5 bg-[#021c47] text-white font-medium rounded-lg hover:bg-[#93db4d] hover:text-[#021c47] transition-all duration-200">
                        Create Partner
                    </button>
                </div>
            </form>
        </div>
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
