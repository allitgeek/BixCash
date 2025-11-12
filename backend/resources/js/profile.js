// ===== BANK FORM TOGGLE =====
function toggleBankEdit() {
    const form = document.getElementById('bankEditForm');
    form.classList.toggle('hidden');
}

// ===== AUTO-HIDE SUCCESS/ERROR MESSAGES =====
setTimeout(() => {
    const messages = document.querySelectorAll('[id$="Message"]');
    messages.forEach(msg => {
        msg.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => msg.remove(), 300);
    });
}, 3000);

// ===== FIREBASE OTP IMPLEMENTATION =====

// Firebase Config - will be populated by Laravel
const firebaseConfig = {
    apiKey: window.FIREBASE_CONFIG?.apiKey || '',
    authDomain: window.FIREBASE_CONFIG?.authDomain || '',
    projectId: window.FIREBASE_CONFIG?.projectId || '',
    storageBucket: window.FIREBASE_CONFIG?.storageBucket || '',
    messagingSenderId: window.FIREBASE_CONFIG?.messagingSenderId || '',
    appId: window.FIREBASE_CONFIG?.appId || ''
};

// Initialize Firebase
let app, auth, confirmationResult, timerInterval;
if (firebase.apps.length === 0) {
    app = firebase.initializeApp(firebaseConfig);
} else {
    app = firebase.app();
}
auth = firebase.auth();

// Auto-show OTP modal if session flag is set
if (window.SHOW_OTP_MODAL) {
    document.addEventListener('DOMContentLoaded', function() {
        showOtpModal();
    });
}

function showOtpModal() {
    const modal = document.getElementById('otpModal');
    const phoneNumber = window.USER_PHONE || '';

    if (!phoneNumber) {
        showError('Phone number not found. Please update your profile.');
        return;
    }

    // Show modal
    modal.classList.remove('hidden');
    document.getElementById('otpInputForm').classList.remove('hidden');
    document.getElementById('otpLoading').classList.add('hidden');

    // Initialize reCAPTCHA and send OTP
    initializeRecaptchaAndSendOtp(phoneNumber);

    // Start countdown timer
    startOtpTimer();

    // Focus on OTP input
    setTimeout(() => document.getElementById('otpCode').focus(), 300);
}

function initializeRecaptchaAndSendOtp(phoneNumber) {
    const loadingEl = document.getElementById('otpLoading');
    const formEl = document.getElementById('otpInputForm');

    // Show loading
    loadingEl.classList.remove('hidden');
    formEl.classList.add('hidden');

    // Setup reCAPTCHA
    window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container', {
        'size': 'invisible',
        'callback': function(response) {
            console.log('reCAPTCHA solved');
        },
        'expired-callback': function() {
            showOtpError('reCAPTCHA expired. Please try again.');
        }
    });

    // Send OTP
    auth.signInWithPhoneNumber(phoneNumber, window.recaptchaVerifier)
        .then(function(result) {
            confirmationResult = result;
            console.log('OTP sent successfully');

            // Hide loading, show form
            loadingEl.classList.add('hidden');
            formEl.classList.remove('hidden');

            showNotification('Verification code sent to ' + phoneNumber, 'success');
        })
        .catch(function(error) {
            console.error('Error sending OTP:', error);
            showOtpError('Failed to send verification code: ' + error.message);

            // Hide loading, show form
            loadingEl.classList.add('hidden');
            formEl.classList.remove('hidden');
        });
}

function verifyOtp() {
    const otpCode = document.getElementById('otpCode').value.trim();
    const verifyBtn = document.getElementById('verifyBtn');

    if (otpCode.length !== 6) {
        showOtpError('Please enter a valid 6-digit code');
        return;
    }

    // Disable button and show loading
    verifyBtn.disabled = true;
    verifyBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

    // Verify OTP with Firebase
    confirmationResult.confirm(otpCode)
        .then(function(result) {
            // User signed in successfully
            const user = result.user;
            console.log('Phone verified successfully:', user.phoneNumber);

            // Send verification to backend
            submitBankDetailsToBackend(otpCode);
        })
        .catch(function(error) {
            console.error('Error verifying OTP:', error);
            showOtpError('Invalid verification code. Please try again.');

            // Re-enable button
            verifyBtn.disabled = false;
            verifyBtn.textContent = 'Verify';
        });
}

function submitBankDetailsToBackend(verificationCode) {
    const verifyOtpUrl = window.VERIFY_OTP_URL || '';
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    fetch(verifyOtpUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            verification_code: verificationCode
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message || 'Bank details updated successfully!', 'success');
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showOtpError(data.message || 'Failed to update bank details');
            document.getElementById('verifyBtn').disabled = false;
            document.getElementById('verifyBtn').textContent = 'Verify';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showOtpError('An error occurred. Please try again.');
        document.getElementById('verifyBtn').disabled = false;
        document.getElementById('verifyBtn').textContent = 'Verify';
    });
}

function resendOtp() {
    const phoneNumber = window.USER_PHONE || '';
    document.getElementById('otpCode').value = '';
    hideOtpError();

    // Reset reCAPTCHA
    if (window.recaptchaVerifier) {
        window.recaptchaVerifier.clear();
    }

    // Reinitialize and send OTP
    initializeRecaptchaAndSendOtp(phoneNumber);

    // Restart timer
    startOtpTimer();
}

function startOtpTimer() {
    let timeLeft = 120; // 2 minutes
    const timerValueEl = document.getElementById('timerValue');
    const resendBtn = document.getElementById('resendBtn');

    // Disable resend button
    resendBtn.disabled = true;

    // Clear existing timer
    if (timerInterval) {
        clearInterval(timerInterval);
    }

    timerInterval = setInterval(() => {
        timeLeft--;

        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        timerValueEl.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;

        if (timeLeft <= 0) {
            clearInterval(timerInterval);
            timerValueEl.textContent = 'Expired';
            resendBtn.disabled = false;
        }
    }, 1000);
}

function closeOtpModal() {
    const modal = document.getElementById('otpModal');
    const cancelOtpUrl = window.CANCEL_OTP_URL || '';
    modal.classList.add('hidden');

    // Clear interval
    if (timerInterval) {
        clearInterval(timerInterval);
    }

    // Clear reCAPTCHA
    if (window.recaptchaVerifier) {
        window.recaptchaVerifier.clear();
    }

    // Reset form
    document.getElementById('otpCode').value = '';
    hideOtpError();
    document.getElementById('verifyBtn').disabled = false;
    document.getElementById('verifyBtn').textContent = 'Verify';

    // Redirect to clear session flag
    window.location.href = cancelOtpUrl;
}

function showOtpError(message) {
    const errorEl = document.getElementById('otpError');
    const errorTextEl = document.getElementById('otpErrorText');
    errorTextEl.textContent = message;
    errorEl.classList.remove('hidden');
}

function hideOtpError() {
    document.getElementById('otpError').classList.add('hidden');
}

function showNotification(message, type = 'success') {
    const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
    const notification = document.createElement('div');
    notification.className = `fixed top-5 right-5 ${bgColor} text-white px-6 py-4 rounded-xl shadow-2xl z-[2000] flex items-center gap-3 animate-slideIn`;
    notification.innerHTML = `
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
        ${message}
    `;
    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

function showError(message) {
    showNotification(message, 'error');
}

// ===== MEMBERSHIP NUMBER COPY =====
function copyMembershipNumber() {
    const membershipNumber = document.getElementById('membershipNumber').textContent;

    // Use modern Clipboard API
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(membershipNumber)
            .then(() => {
                showNotification('Membership number copied to clipboard!', 'success');
            })
            .catch(err => {
                console.error('Failed to copy:', err);
                fallbackCopyTextToClipboard(membershipNumber);
            });
    } else {
        fallbackCopyTextToClipboard(membershipNumber);
    }
}

// Fallback for older browsers
function fallbackCopyTextToClipboard(text) {
    const textArea = document.createElement('textarea');
    textArea.value = text;
    textArea.style.position = 'fixed';
    textArea.style.left = '-999999px';
    document.body.appendChild(textArea);
    textArea.select();

    try {
        document.execCommand('copy');
        showNotification('Membership number copied to clipboard!', 'success');
    } catch (err) {
        console.error('Fallback: Could not copy text:', err);
        showError('Failed to copy membership number');
    }

    document.body.removeChild(textArea);
}

// ===== TPIN AUTHENTICATION FLOW =====

let selectedAuthMethod = window.DEFAULT_AUTH_METHOD || 'otp';

function switchAuthMethod(method) {
    selectedAuthMethod = method;
    const submitBtnText = document.getElementById('submitBtnText');

    if (method === 'otp') {
        submitBtnText.textContent = 'Request OTP';
    } else if (method === 'tpin') {
        submitBtnText.textContent = 'Proceed with TPIN';
    }
}

// Override form submission to handle auth method selection
document.addEventListener('DOMContentLoaded', function() {
    const requestOtpUrl = window.REQUEST_OTP_URL || '';
    const bankDetailsForm = document.querySelector(`form[action="${requestOtpUrl}"]`);

    if (bankDetailsForm) {
        bankDetailsForm.addEventListener('submit', function(e) {
            if (selectedAuthMethod === 'tpin') {
                e.preventDefault(); // Prevent default form submission

                // Validate form fields first
                if (!bankDetailsForm.checkValidity()) {
                    bankDetailsForm.reportValidity();
                    return;
                }

                // Submit form data to store in session
                const formData = new FormData(bankDetailsForm);

                fetch(bankDetailsForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => {
                    // Parse JSON response for error details
                    return response.json().then(data => ({
                        status: response.status,
                        ok: response.ok,
                        data: data
                    }));
                })
                .then(result => {
                    console.log('Response:', result);
                    if (result.ok) {
                        // Data stored in session, now show TPIN modal
                        showTpinModal();
                    } else {
                        const errorMsg = result.data.message || 'Failed to save bank details. Please try again.';
                        console.error('Error status:', result.status, 'Message:', errorMsg);
                        showError(errorMsg);
                    }
                })
                .catch(error => {
                    console.error('Network/Parse Error:', error);
                    showError('An error occurred. Please try again.');
                });
            }
            // If OTP is selected, let form submit naturally to request OTP
        });
    }

    // ===== TPIN ENTER KEY SUPPORT =====
    // Add event listeners to all TPIN inputs for Enter key
    const tpinInputs = document.querySelectorAll('[id^="tpin"]');
    tpinInputs.forEach(input => {
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                const pin = getTpinValue();
                if (pin.length === 4) {
                    e.preventDefault();
                    verifyTpin();
                }
            }
        });
    });

    // ===== OTP INPUT ENHANCEMENTS =====
    const otpInput = document.getElementById('otpCode');
    if (otpInput) {
        // Auto-enable verify button when 6 digits entered
        otpInput.addEventListener('input', function() {
            const verifyBtn = document.getElementById('verifyBtn');
            if (this.value.length === 6 && verifyBtn) {
                verifyBtn.disabled = false;
            } else if (verifyBtn) {
                verifyBtn.disabled = true;
            }
        });

        // Enter key support for OTP
        otpInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && otpInput.value.length === 6) {
                e.preventDefault();
                verifyOtp();
            }
        });
    }
});

function showTpinModal() {
    const modal = document.getElementById('tpinModal');
    modal.classList.remove('hidden');

    // Clear previous inputs and errors
    clearTpinInputs();
    hideTpinError();
    hideTpinLockout();

    // Focus on first PIN input
    setTimeout(() => document.getElementById('tpin1').focus(), 300);
}

function closeTpinModal() {
    const modal = document.getElementById('tpinModal');
    modal.classList.add('hidden');

    // Clear inputs
    clearTpinInputs();
    hideTpinError();
    hideTpinLockout();

    // Reset button
    document.getElementById('verifyTpinBtn').disabled = false;
    document.getElementById('verifyTpinBtn').textContent = 'Verify';
}

function clearTpinInputs() {
    for (let i = 1; i <= 4; i++) {
        document.getElementById('tpin' + i).value = '';
    }
}

function moveTpinFocus(currentInput, nextInputId) {
    // Only allow numbers
    currentInput.value = currentInput.value.replace(/[^0-9]/g, '');

    if (currentInput.value.length === 1 && nextInputId) {
        document.getElementById(nextInputId).focus();
    } else if (currentInput.value.length === 1 && !nextInputId) {
        // Last digit entered - check if all 4 digits are filled
        const pin = getTpinValue();
        if (pin.length === 4) {
            const verifyBtn = document.getElementById('verifyTpinBtn');
            if (verifyBtn) {
                verifyBtn.disabled = false;
                // Optional: auto-submit after a brief delay
                // setTimeout(() => verifyTpin(), 300);
            }
        }
    }
}

function handleTpinBackspace(event, currentInput, prevInputId) {
    if (event.key === 'Backspace' && currentInput.value === '' && prevInputId) {
        event.preventDefault();
        const prevInput = document.getElementById(prevInputId);
        prevInput.focus();
        prevInput.value = '';
    }
}

// Expose functions to global scope for inline event handlers
window.copyMembershipNumber = copyMembershipNumber;
window.toggleBankEdit = toggleBankEdit;
window.switchAuthMethod = switchAuthMethod;
window.moveTpinFocus = moveTpinFocus;
window.handleTpinBackspace = handleTpinBackspace;
window.verifyOtp = verifyOtp;
window.verifyTpin = verifyTpin;
window.resendOtp = resendOtp;
window.closeOtpModal = closeOtpModal;

function getTpinValue() {
    let pin = '';
    for (let i = 1; i <= 4; i++) {
        pin += document.getElementById('tpin' + i).value;
    }
    return pin;
}

function verifyTpin() {
    const pin = getTpinValue();
    const verifyBtn = document.getElementById('verifyTpinBtn');
    const verifyTpinUrl = window.VERIFY_TPIN_URL || '';
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    // Validate PIN length
    if (pin.length !== 4) {
        showTpinError('Please enter all 4 digits');
        return;
    }

    // Disable button and show loading
    verifyBtn.disabled = true;
    verifyBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

    // Send TPIN to backend
    fetch(verifyTpinUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ pin: pin })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message || 'Bank details updated successfully using TPIN!', 'success');
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            // Handle different error types
            if (data.locked) {
                showTpinLockout(data.message, data.minutes_remaining);
                verifyBtn.disabled = true;
            } else if (data.attempts_remaining !== undefined) {
                showTpinError(data.message);
                clearTpinInputs();
                document.getElementById('tpin1').focus();
            } else {
                showTpinError(data.message || 'Verification failed. Please try again.');
            }

            // Re-enable button if not locked
            if (!data.locked) {
                verifyBtn.disabled = false;
                verifyBtn.textContent = 'Verify';
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showTpinError('An error occurred. Please try again.');
        verifyBtn.disabled = false;
        verifyBtn.textContent = 'Verify';
    });
}

function showTpinError(message) {
    const errorEl = document.getElementById('tpinError');
    const errorTextEl = document.getElementById('tpinErrorText');
    errorTextEl.textContent = message;
    errorEl.classList.remove('hidden');
}

function hideTpinError() {
    document.getElementById('tpinError').classList.add('hidden');
}

function showTpinLockout(message, minutesRemaining) {
    const lockoutEl = document.getElementById('tpinLockout');
    const lockoutTextEl = document.getElementById('tpinLockoutText');
    lockoutTextEl.textContent = message;
    lockoutEl.classList.remove('hidden');

    // Hide error message when showing lockout
    hideTpinError();

    // Optionally start a countdown
    if (minutesRemaining) {
        let timeLeft = minutesRemaining * 60;
        const countdownInterval = setInterval(() => {
            timeLeft--;
            const mins = Math.floor(timeLeft / 60);
            const secs = timeLeft % 60;
            lockoutTextEl.textContent = `Account locked. Try again in ${mins}:${secs.toString().padStart(2, '0')}`;

            if (timeLeft <= 0) {
                clearInterval(countdownInterval);
                lockoutTextEl.textContent = 'Lockout expired. You can try again now.';
                document.getElementById('verifyTpinBtn').disabled = false;
                document.getElementById('verifyTpinBtn').textContent = 'Verify';
            }
        }, 1000);
    }
}

function hideTpinLockout() {
    document.getElementById('tpinLockout').classList.add('hidden');
}
