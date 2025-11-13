@extends('layouts.admin')

@section('title', 'Create New Partner - BixCash Admin')
@section('page-title', 'Create New Partner')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Add New Partner</h3>
            <a href="{{ route('admin.partners.index') }}" class="btn btn-secondary">Back to Partners</a>
        </div>
        <div class="card-body">
            @if($errors->any())
            <div style="background: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border-left: 4px solid #ef4444;">
                <strong>Please fix the following errors:</strong>
                <ul style="list-style: disc; padding-left: 1.25rem; margin-top: 0.5rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('admin.partners.store') }}" id="createPartnerForm" enctype="multipart/form-data">
                @csrf

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                    <!-- Business Name -->
                    <div class="form-group">
                        <label for="business_name" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">
                            Business Name <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" id="business_name" name="business_name" class="form-control" required
                               value="{{ old('business_name') }}" placeholder="e.g., KFC Lahore">
                    </div>

                    <!-- Mobile Number -->
                    <div class="form-group">
                        <label for="phone" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">
                            Mobile Number <span style="color: #ef4444;">*</span>
                        </label>
                        <div style="display: flex; gap: 0.5rem;">
                            <div style="padding: 0.5rem 1rem; background: #f7fafc; border: 1px solid #e2e8f0; border-radius: 5px; font-weight: 600;">+92</div>
                            <input type="text" id="phone" name="phone" class="form-control" required maxlength="10" pattern="[0-9]{10}"
                                   value="{{ old('phone') }}" placeholder="3001234567" style="flex: 1;">
                        </div>
                        <div style="display: flex; gap: 0.5rem; margin-top: 0.5rem;">
                            <button type="button" id="sendOtpBtn" onclick="sendPartnerOtp()"
                                    style="flex: 1; padding: 0.5rem 1rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                                           color: white; border: none; border-radius: 6px; font-weight: 500; cursor: pointer;
                                           transition: all 0.2s ease; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                                <span id="otpBtnIcon">📤</span>
                                <span id="otpBtnText">Send OTP</span>
                            </button>
                            <button type="button" id="setPinBtn" onclick="openSetPinModal()"
                                    style="flex: 1; padding: 0.5rem 1rem; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
                                           color: white; border: none; border-radius: 6px; font-weight: 500; cursor: pointer;
                                           transition: all 0.2s ease; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                                <span id="pinBtnIcon">🔐</span>
                                <span id="pinBtnText">Set PIN</span>
                            </button>
                        </div>
                        <small style="color: #718096; font-size: 0.75rem;">Enter 10-digit mobile number without +92</small>
                    </div>

                    <!-- Contact Person Name -->
                    <div class="form-group">
                        <label for="contact_person_name" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">
                            Contact Person Name <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" id="contact_person_name" name="contact_person_name" class="form-control" required
                               value="{{ old('contact_person_name') }}" placeholder="Full name">
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">
                            Email (Optional)
                        </label>
                        <input type="email" id="email" name="email" class="form-control"
                               value="{{ old('email') }}" placeholder="partner@email.com">
                    </div>

                    <!-- Business Type -->
                    <div class="form-group">
                        <label for="business_type" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">
                            Business Type <span style="color: #ef4444;">*</span>
                        </label>
                        <select id="business_type" name="business_type" class="form-control" required>
                            <option value="">Select business type</option>
                            <option value="Restaurant" {{ old('business_type') == 'Restaurant' ? 'selected' : '' }}>Restaurant</option>
                            <option value="Retail" {{ old('business_type') == 'Retail' ? 'selected' : '' }}>Retail Store</option>
                            <option value="Cafe" {{ old('business_type') == 'Cafe' ? 'selected' : '' }}>Cafe</option>
                            <option value="Grocery" {{ old('business_type') == 'Grocery' ? 'selected' : '' }}>Grocery Store</option>
                            <option value="Fashion" {{ old('business_type') == 'Fashion' ? 'selected' : '' }}>Fashion & Clothing</option>
                            <option value="Electronics" {{ old('business_type') == 'Electronics' ? 'selected' : '' }}>Electronics</option>
                            <option value="Salon" {{ old('business_type') == 'Salon' ? 'selected' : '' }}>Salon & Spa</option>
                            <option value="Pharmacy" {{ old('business_type') == 'Pharmacy' ? 'selected' : '' }}>Pharmacy</option>
                            <option value="Services" {{ old('business_type') == 'Services' ? 'selected' : '' }}>Services</option>
                            <option value="Other" {{ old('business_type') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <!-- City -->
                    <div class="form-group">
                        <label for="city" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">
                            City (Optional)
                        </label>
                        <input type="text" id="city" name="city" class="form-control"
                               value="{{ old('city') }}" placeholder="e.g., Lahore">
                    </div>

                    <!-- Commission Rate -->
                    <div class="form-group">
                        <label for="commission_rate" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">
                            Commission Rate (%) <small style="color: #718096; font-weight: 400;">(Optional)</small>
                        </label>
                        <input type="number" id="commission_rate" name="commission_rate" class="form-control"
                               value="{{ old('commission_rate', 0) }}" placeholder="0.00" min="0" max="100" step="0.01">
                        <small style="color: #718096; font-size: 0.75rem;">Percentage the partner pays BixCash (0-100%)</small>
                    </div>

                    <!-- Logo -->
                    <div class="form-group">
                        <label for="logo" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">
                            Business Logo (Optional)
                        </label>
                        <input type="file" id="logo" name="logo" class="form-control" accept="image/jpeg,image/jpg,image/png"
                               onchange="previewLogo(event)">
                        <small style="color: #718096; font-size: 0.75rem;">JPG or PNG, max 2MB</small>
                        <div id="logoPreview" style="margin-top: 0.75rem; display: none;">
                            <img id="logoPreviewImg" src="" alt="Logo Preview" style="width: 64px; height: 64px; object-fit: cover; border-radius: 8px; border: 2px solid #e2e8f0;">
                        </div>
                    </div>
                </div>

                <!-- Business Address (Full Width) -->
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="business_address" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">
                        Business Address (Optional)
                    </label>
                    <input type="text" id="business_address" name="business_address" class="form-control"
                           value="{{ old('business_address') }}" placeholder="Complete business address">
                </div>

                <!-- Auto-Approve Option -->
                <div class="form-group" style="margin-bottom: 1.5rem; padding: 1rem; background: #e3f2fd; border-radius: 8px;">
                    <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer;">
                        <input type="checkbox" name="auto_approve" value="1" {{ old('auto_approve', '1') ? 'checked' : '' }}
                               style="width: 18px; height: 18px;">
                        <span style="font-weight: 600; color: #1976d2;">
                            Auto-approve this partner (Partner will be immediately active)
                        </span>
                    </label>
                    <small style="color: #718096; margin-left: 2rem; display: block; margin-top: 0.25rem;">
                        If unchecked, partner will be created with "Pending" status and require manual approval.
                    </small>
                </div>

                <!-- Hidden Fields for OTP & PIN -->
                <input type="hidden" id="partner_pin" name="partner_pin" value="">

                <!-- Submit Buttons -->
                <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                    <a href="{{ route('admin.partners.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Create Partner</button>
                </div>
            </form>
        </div>
    </div>

    <!-- OTP Verification Modal -->
    <div id="otpModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
        <div style="background: white; border-radius: 12px; padding: 2rem; max-width: 400px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
            <h3 style="margin: 0 0 0.5rem; font-size: 1.25rem; font-weight: 600;">Verify Phone Number</h3>
            <p style="color: #718096; margin-bottom: 1.5rem; font-size: 0.875rem;">Enter the 6-digit code sent to <strong id="otpPhoneDisplay"></strong></p>

            <div style="display: flex; gap: 0.5rem; margin-bottom: 1.5rem; justify-content: center;">
                <input type="text" id="otp1" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 1.5rem; font-weight: 600; border: 2px solid #e2e8f0; border-radius: 8px;" onkeyup="moveOtpFocus(event, 1)" onkeydown="handleOtpBackspace(event, 1)">
                <input type="text" id="otp2" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 1.5rem; font-weight: 600; border: 2px solid #e2e8f0; border-radius: 8px;" onkeyup="moveOtpFocus(event, 2)" onkeydown="handleOtpBackspace(event, 2)">
                <input type="text" id="otp3" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 1.5rem; font-weight: 600; border: 2px solid #e2e8f0; border-radius: 8px;" onkeyup="moveOtpFocus(event, 3)" onkeydown="handleOtpBackspace(event, 3)">
                <input type="text" id="otp4" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 1.5rem; font-weight: 600; border: 2px solid #e2e8f0; border-radius: 8px;" onkeyup="moveOtpFocus(event, 4)" onkeydown="handleOtpBackspace(event, 4)">
                <input type="text" id="otp5" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 1.5rem; font-weight: 600; border: 2px solid #e2e8f0; border-radius: 8px;" onkeyup="moveOtpFocus(event, 5)" onkeydown="handleOtpBackspace(event, 5)">
                <input type="text" id="otp6" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 1.5rem; font-weight: 600; border: 2px solid #e2e8f0; border-radius: 8px;" onkeyup="moveOtpFocus(event, 6)" onkeydown="handleOtpBackspace(event, 6)">
            </div>

            <div id="otpError" style="display: none; background: #fee2e2; color: #991b1b; padding: 0.75rem; border-radius: 6px; margin-bottom: 1rem; font-size: 0.875rem;"></div>

            <div style="display: flex; gap: 0.75rem;">
                <button type="button" onclick="closeOtpModal()" style="flex: 1; padding: 0.75rem; background: #e2e8f0; color: #4a5568; border: none; border-radius: 6px; font-weight: 500; cursor: pointer;">Cancel</button>
                <button type="button" id="verifyOtpBtn" onclick="verifyPartnerOtp()" style="flex: 1; padding: 0.75rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 6px; font-weight: 500; cursor: pointer;">Verify OTP</button>
            </div>

            <div style="text-align: center; margin-top: 1rem;">
                <button type="button" id="resendOtpBtn" onclick="resendPartnerOtp()" style="background: none; border: none; color: #667eea; font-size: 0.875rem; cursor: pointer; text-decoration: underline;">Resend OTP</button>
            </div>
        </div>
    </div>

    <!-- PIN Setup Modal -->
    <div id="pinModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
        <div style="background: white; border-radius: 12px; padding: 2rem; max-width: 400px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
            <h3 style="margin: 0 0 0.5rem; font-size: 1.25rem; font-weight: 600;">Set Partner PIN</h3>
            <p style="color: #718096; margin-bottom: 1.5rem; font-size: 0.875rem;">Create a 4-digit PIN for partner authentication</p>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 500; margin-bottom: 0.5rem; font-size: 0.875rem;">Enter PIN</label>
                <div style="display: flex; gap: 0.5rem; justify-content: center;">
                    <input type="password" id="pin1" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 1.5rem; font-weight: 600; border: 2px solid #e2e8f0; border-radius: 8px;" onkeyup="movePinFocus(event, 1)" onkeydown="handlePinBackspace(event, 1)">
                    <input type="password" id="pin2" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 1.5rem; font-weight: 600; border: 2px solid #e2e8f0; border-radius: 8px;" onkeyup="movePinFocus(event, 2)" onkeydown="handlePinBackspace(event, 2)">
                    <input type="password" id="pin3" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 1.5rem; font-weight: 600; border: 2px solid #e2e8f0; border-radius: 8px;" onkeyup="movePinFocus(event, 3)" onkeydown="handlePinBackspace(event, 3)">
                    <input type="password" id="pin4" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 1.5rem; font-weight: 600; border: 2px solid #e2e8f0; border-radius: 8px;" onkeyup="movePinFocus(event, 4)" onkeydown="handlePinBackspace(event, 4)">
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 500; margin-bottom: 0.5rem; font-size: 0.875rem;">Confirm PIN</label>
                <div style="display: flex; gap: 0.5rem; justify-content: center;">
                    <input type="password" id="pinConfirm1" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 1.5rem; font-weight: 600; border: 2px solid #e2e8f0; border-radius: 8px;" onkeyup="movePinConfirmFocus(event, 1)" onkeydown="handlePinConfirmBackspace(event, 1)">
                    <input type="password" id="pinConfirm2" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 1.5rem; font-weight: 600; border: 2px solid #e2e8f0; border-radius: 8px;" onkeyup="movePinConfirmFocus(event, 2)" onkeydown="handlePinConfirmBackspace(event, 2)">
                    <input type="password" id="pinConfirm3" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 1.5rem; font-weight: 600; border: 2px solid #e2e8f0; border-radius: 8px;" onkeyup="movePinConfirmFocus(event, 3)" onkeydown="handlePinConfirmBackspace(event, 3)">
                    <input type="password" id="pinConfirm4" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 1.5rem; font-weight: 600; border: 2px solid #e2e8f0; border-radius: 8px;" onkeyup="movePinConfirmFocus(event, 4)" onkeydown="handlePinConfirmBackspace(event, 4)">
                </div>
            </div>

            <div id="pinError" style="display: none; background: #fee2e2; color: #991b1b; padding: 0.75rem; border-radius: 6px; margin-bottom: 1rem; font-size: 0.875rem;"></div>

            <div style="display: flex; gap: 0.75rem;">
                <button type="button" onclick="closePinModal()" style="flex: 1; padding: 0.75rem; background: #e2e8f0; color: #4a5568; border: none; border-radius: 6px; font-weight: 500; cursor: pointer;">Cancel</button>
                <button type="button" id="setPinSubmitBtn" onclick="setPartnerPin()" style="flex: 1; padding: 0.75rem; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border: none; border-radius: 6px; font-weight: 500; cursor: pointer;">Set PIN</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Phone number validation
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '').substring(0, 10);
        });
    }

    // Logo preview function
    function previewLogo(event) {
        const file = event.target.files[0];
        if (!file) return;

        // Validate file size (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('File size must be less than 2MB');
            event.target.value = '';
            return;
        }

        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!allowedTypes.includes(file.type)) {
            alert('Only JPG and PNG images are allowed');
            event.target.value = '';
            return;
        }

        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewDiv = document.getElementById('logoPreview');
            const previewImg = document.getElementById('logoPreviewImg');
            previewImg.src = e.target.result;
            previewDiv.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }

    // Form submission handler
    const form = document.getElementById('createPartnerForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.textContent = 'Creating...';
            submitBtn.disabled = true;
        });
    }

    // ========== OTP & PIN Functions ==========

    let currentPhone = '';

    // Send OTP to partner's phone
    async function sendPartnerOtp() {
        const phoneInput = document.getElementById('phone');
        const phone = phoneInput.value.trim();

        if (phone.length !== 10) {
            alert('Please enter a valid 10-digit mobile number');
            return;
        }

        currentPhone = phone;
        const sendOtpBtn = document.getElementById('sendOtpBtn');
        const otpBtnText = document.getElementById('otpBtnText');
        const originalText = otpBtnText.textContent;

        try {
            sendOtpBtn.disabled = true;
            otpBtnText.textContent = 'Sending...';

            const response = await fetch('{{ route('admin.partners.send-registration-otp') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
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
            sendOtpBtn.disabled = false;
            otpBtnText.textContent = originalText;
        }
    }

    // Open OTP modal
    function openOtpModal() {
        document.getElementById('otpModal').style.display = 'flex';
        clearOtpInputs();
        document.getElementById('otp1').focus();
    }

    // Close OTP modal
    function closeOtpModal() {
        document.getElementById('otpModal').style.display = 'none';
        clearOtpInputs();
    }

    // Clear OTP inputs
    function clearOtpInputs() {
        for (let i = 1; i <= 6; i++) {
            document.getElementById('otp' + i).value = '';
        }
        document.getElementById('otpError').style.display = 'none';
    }

    // Move focus for OTP inputs
    function moveOtpFocus(event, position) {
        const input = event.target;
        if (input.value.length === 1 && position < 6) {
            document.getElementById('otp' + (position + 1)).focus();
        }
        // Check if all 6 digits entered
        checkOtpComplete();
    }

    // Handle backspace for OTP
    function handleOtpBackspace(event, position) {
        if (event.key === 'Backspace' && position > 1 && event.target.value === '') {
            document.getElementById('otp' + (position - 1)).focus();
        } else if (event.key === 'Enter') {
            verifyPartnerOtp();
        }
    }

    // Check if OTP is complete
    function checkOtpComplete() {
        let complete = true;
        for (let i = 1; i <= 6; i++) {
            if (document.getElementById('otp' + i).value === '') {
                complete = false;
                break;
            }
        }
        document.getElementById('verifyOtpBtn').disabled = !complete;
    }

    // Verify OTP
    async function verifyPartnerOtp() {
        let otp = '';
        for (let i = 1; i <= 6; i++) {
            otp += document.getElementById('otp' + i).value;
        }

        if (otp.length !== 6) {
            showOtpError('Please enter all 6 digits');
            return;
        }

        const verifyBtn = document.getElementById('verifyOtpBtn');
        const originalText = verifyBtn.textContent;

        try {
            verifyBtn.disabled = true;
            verifyBtn.textContent = 'Verifying...';

            const response = await fetch('{{ route('admin.partners.verify-registration-otp') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    phone: currentPhone,
                    otp: otp
                })
            });

            const data = await response.json();

            if (data.success) {
                closeOtpModal();
                updateOtpButtonSuccess();
                alert('Phone verified successfully!');
            } else {
                showOtpError(data.message || 'Invalid OTP');
            }
        } catch (error) {
            showOtpError('Error verifying OTP: ' + error.message);
        } finally {
            verifyBtn.disabled = false;
            verifyBtn.textContent = originalText;
        }
    }

    // Resend OTP
    async function resendPartnerOtp() {
        await sendPartnerOtp();
    }

    // Show OTP error
    function showOtpError(message) {
        const errorDiv = document.getElementById('otpError');
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
    }

    // Update OTP button to show success
    function updateOtpButtonSuccess() {
        const icon = document.getElementById('otpBtnIcon');
        const text = document.getElementById('otpBtnText');
        icon.textContent = '✅';
        text.textContent = 'Verified';
        document.getElementById('sendOtpBtn').style.background = 'linear-gradient(135deg, #48bb78 0%, #38a169 100%)';
        document.getElementById('sendOtpBtn').disabled = true;
    }

    // ========== PIN Functions ==========

    // Open PIN modal
    function openSetPinModal() {
        document.getElementById('pinModal').style.display = 'flex';
        clearPinInputs();
        document.getElementById('pin1').focus();
    }

    // Close PIN modal
    function closePinModal() {
        document.getElementById('pinModal').style.display = 'none';
        clearPinInputs();
    }

    // Clear PIN inputs
    function clearPinInputs() {
        for (let i = 1; i <= 4; i++) {
            document.getElementById('pin' + i).value = '';
            document.getElementById('pinConfirm' + i).value = '';
        }
        document.getElementById('pinError').style.display = 'none';
    }

    // Move focus for PIN inputs
    function movePinFocus(event, position) {
        const input = event.target;
        if (input.value.length === 1 && position < 4) {
            document.getElementById('pin' + (position + 1)).focus();
        }
    }

    // Handle backspace for PIN
    function handlePinBackspace(event, position) {
        if (event.key === 'Backspace' && position > 1 && event.target.value === '') {
            document.getElementById('pin' + (position - 1)).focus();
        } else if (event.key === 'Enter' && position === 4) {
            document.getElementById('pinConfirm1').focus();
        }
    }

    // Move focus for PIN confirmation inputs
    function movePinConfirmFocus(event, position) {
        const input = event.target;
        if (input.value.length === 1 && position < 4) {
            document.getElementById('pinConfirm' + (position + 1)).focus();
        }
    }

    // Handle backspace for PIN confirmation
    function handlePinConfirmBackspace(event, position) {
        if (event.key === 'Backspace' && position > 1 && event.target.value === '') {
            document.getElementById('pinConfirm' + (position - 1)).focus();
        } else if (event.key === 'Enter' && position === 4) {
            setPartnerPin();
        }
    }

    // Set partner PIN
    function setPartnerPin() {
        let pin = '';
        let pinConfirm = '';

        for (let i = 1; i <= 4; i++) {
            pin += document.getElementById('pin' + i).value;
            pinConfirm += document.getElementById('pinConfirm' + i).value;
        }

        if (pin.length !== 4) {
            showPinError('Please enter a 4-digit PIN');
            return;
        }

        if (pinConfirm.length !== 4) {
            showPinError('Please confirm your PIN');
            return;
        }

        if (pin !== pinConfirm) {
            showPinError('PINs do not match');
            return;
        }

        // Validate numeric
        if (!/^\d{4}$/.test(pin)) {
            showPinError('PIN must be 4 digits (0-9)');
            return;
        }

        // Store PIN in hidden field
        document.getElementById('partner_pin').value = pin;

        // Update button
        updatePinButtonSuccess();

        // Close modal
        closePinModal();

        alert('PIN set successfully!');
    }

    // Show PIN error
    function showPinError(message) {
        const errorDiv = document.getElementById('pinError');
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
    }

    // Update PIN button to show success
    function updatePinButtonSuccess() {
        const icon = document.getElementById('pinBtnIcon');
        const text = document.getElementById('pinBtnText');
        icon.textContent = '✅';
        text.textContent = 'PIN Set';
        document.getElementById('setPinBtn').style.background = 'linear-gradient(135deg, #48bb78 0%, #38a169 100%)';
    }

    // Expose functions to global scope for inline handlers
    window.sendPartnerOtp = sendPartnerOtp;
    window.openSetPinModal = openSetPinModal;
    window.closeOtpModal = closeOtpModal;
    window.closePinModal = closePinModal;
    window.verifyPartnerOtp = verifyPartnerOtp;
    window.resendPartnerOtp = resendPartnerOtp;
    window.setPartnerPin = setPartnerPin;
    window.moveOtpFocus = moveOtpFocus;
    window.handleOtpBackspace = handleOtpBackspace;
    window.movePinFocus = movePinFocus;
    window.handlePinBackspace = handlePinBackspace;
    window.movePinConfirmFocus = movePinConfirmFocus;
    window.handlePinConfirmBackspace = handlePinConfirmBackspace;
</script>
@endpush
