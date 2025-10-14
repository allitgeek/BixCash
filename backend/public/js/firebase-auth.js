/**
 * Firebase Phone Authentication Module
 * Handles SMS OTP authentication using Firebase Client SDK
 *
 * @requires firebase-app.js (Firebase SDK v10+)
 * @requires firebase-auth.js (Firebase Auth SDK v10+)
 */

class FirebasePhoneAuth {
    constructor(firebaseConfig) {
        this.firebaseConfig = firebaseConfig;
        this.auth = null;
        this.recaptchaVerifier = null;
        this.confirmationResult = null;
        this.apiBaseUrl = '/api/customer/auth';
    }

    /**
     * Initialize Firebase App and Auth
     */
    async initialize() {
        try {
            // Initialize Firebase
            if (!firebase.apps.length) {
                firebase.initializeApp(this.firebaseConfig);
            }

            this.auth = firebase.auth();

            console.log('Firebase initialized successfully');
            return { success: true };

        } catch (error) {
            console.error('Firebase initialization failed:', error);
            return {
                success: false,
                message: 'Failed to initialize Firebase. Please refresh the page.'
            };
        }
    }

    /**
     * Set up invisible reCAPTCHA verifier
     * @param {string} buttonId - ID of the button element for reCAPTCHA
     */
    setupRecaptcha(buttonId = 'send-otp-button') {
        try {
            // Clear existing verifier if any
            if (this.recaptchaVerifier) {
                this.recaptchaVerifier.clear();
            }

            this.recaptchaVerifier = new firebase.auth.RecaptchaVerifier(buttonId, {
                'size': 'invisible',
                'callback': (response) => {
                    console.log('reCAPTCHA solved');
                },
                'error-callback': (error) => {
                    console.error('reCAPTCHA error:', error);
                }
            });

            console.log('reCAPTCHA verifier set up successfully');
            return { success: true };

        } catch (error) {
            console.error('reCAPTCHA setup failed:', error);
            return {
                success: false,
                message: 'Failed to set up verification. Please refresh the page.'
            };
        }
    }

    /**
     * Send OTP to phone number via Firebase
     * @param {string} phoneNumber - Phone number in format +92XXXXXXXXXX
     * @returns {Promise<Object>} Result object
     */
    async sendOTP(phoneNumber) {
        try {
            // Validate phone number format
            if (!phoneNumber || !phoneNumber.match(/^\+92[0-9]{10}$/)) {
                return {
                    success: false,
                    message: 'Invalid phone number format. Use +92XXXXXXXXXX'
                };
            }

            // Ensure reCAPTCHA is set up
            if (!this.recaptchaVerifier) {
                const recaptchaSetup = this.setupRecaptcha();
                if (!recaptchaSetup.success) {
                    return recaptchaSetup;
                }
            }

            console.log('Sending OTP to:', phoneNumber);

            // Send OTP via Firebase
            this.confirmationResult = await this.auth.signInWithPhoneNumber(
                phoneNumber,
                this.recaptchaVerifier
            );

            console.log('OTP sent successfully');

            return {
                success: true,
                message: 'OTP sent to your phone number. Please check your SMS.',
                confirmationResult: this.confirmationResult
            };

        } catch (error) {
            console.error('Send OTP error:', error);

            // Reset reCAPTCHA on error
            if (this.recaptchaVerifier) {
                this.recaptchaVerifier.clear();
                this.recaptchaVerifier = null;
            }

            // Handle specific Firebase errors
            let errorMessage = 'Failed to send OTP. Please try again.';

            if (error.code === 'auth/too-many-requests') {
                errorMessage = 'Too many requests. Please try again later.';
            } else if (error.code === 'auth/invalid-phone-number') {
                errorMessage = 'Invalid phone number. Please check and try again.';
            } else if (error.code === 'auth/missing-phone-number') {
                errorMessage = 'Phone number is required.';
            } else if (error.code === 'auth/quota-exceeded') {
                errorMessage = 'SMS quota exceeded. Please contact support.';
            }

            return {
                success: false,
                message: errorMessage,
                error: error.code
            };
        }
    }

    /**
     * Verify OTP code and get Firebase ID token
     * @param {string} otpCode - 6-digit OTP code
     * @returns {Promise<Object>} Result object with ID token
     */
    async verifyOTP(otpCode) {
        try {
            if (!this.confirmationResult) {
                return {
                    success: false,
                    message: 'No OTP request found. Please request OTP first.'
                };
            }

            if (!otpCode || otpCode.length !== 6) {
                return {
                    success: false,
                    message: 'Please enter a valid 6-digit OTP code.'
                };
            }

            console.log('Verifying OTP...');

            // Verify OTP with Firebase
            const result = await this.confirmationResult.confirm(otpCode);

            // Get Firebase ID token
            const idToken = await result.user.getIdToken();

            console.log('OTP verified successfully');

            return {
                success: true,
                message: 'Phone number verified successfully',
                idToken: idToken,
                user: result.user
            };

        } catch (error) {
            console.error('Verify OTP error:', error);

            // Handle specific Firebase errors
            let errorMessage = 'Invalid OTP code. Please try again.';

            if (error.code === 'auth/invalid-verification-code') {
                errorMessage = 'Invalid OTP code. Please check and try again.';
            } else if (error.code === 'auth/code-expired') {
                errorMessage = 'OTP code has expired. Please request a new one.';
            }

            return {
                success: false,
                message: errorMessage,
                error: error.code
            };
        }
    }

    /**
     * Send Firebase ID token to backend for verification and login
     * @param {string} idToken - Firebase ID token
     * @returns {Promise<Object>} Backend response with user data and app token
     */
    async loginWithFirebaseToken(idToken) {
        try {
            const response = await fetch(`${this.apiBaseUrl}/verify-firebase-token`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    id_token: idToken
                })
            });

            const data = await response.json();

            if (!response.ok) {
                return {
                    success: false,
                    message: data.message || 'Login failed. Please try again.',
                    errors: data.errors
                };
            }

            // Store app token in localStorage
            if (data.success && data.data && data.data.token) {
                localStorage.setItem('auth_token', data.data.token);
                localStorage.setItem('user', JSON.stringify(data.data.user));
            }

            return data;

        } catch (error) {
            console.error('Login error:', error);
            return {
                success: false,
                message: 'Network error. Please check your connection and try again.'
            };
        }
    }

    /**
     * Complete authentication flow: send OTP, verify, and login
     * This is a convenience method that combines all steps
     */
    async authenticateWithPhone(phoneNumber, otpCode = null) {
        // If OTP code is provided, verify it
        if (otpCode) {
            const verifyResult = await this.verifyOTP(otpCode);
            if (!verifyResult.success) {
                return verifyResult;
            }

            // Login with Firebase token
            return await this.loginWithFirebaseToken(verifyResult.idToken);
        }

        // Otherwise, send OTP
        return await this.sendOTP(phoneNumber);
    }

    /**
     * Logout and clear Firebase session
     */
    async logout() {
        try {
            await this.auth.signOut();
            localStorage.removeItem('auth_token');
            localStorage.removeItem('user');

            return {
                success: true,
                message: 'Logged out successfully'
            };
        } catch (error) {
            console.error('Logout error:', error);
            return {
                success: false,
                message: 'Logout failed'
            };
        }
    }

    /**
     * Get current Firebase user
     */
    getCurrentUser() {
        return this.auth ? this.auth.currentUser : null;
    }

    /**
     * Check if user is authenticated
     */
    isAuthenticated() {
        return !!this.getCurrentUser() && !!localStorage.getItem('auth_token');
    }
}

// Export for use in other scripts
window.FirebasePhoneAuth = FirebasePhoneAuth;
