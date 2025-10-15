<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\CustomerProfile;
use App\Services\FirebaseOtpService;
use App\Services\SecurityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class CustomerAuthController extends Controller
{
    protected $otpService;

    public function __construct(FirebaseOtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * Check if phone number has PIN set up
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkPhone(Request $request)
    {
        try {
            $request->validate([
                'phone' => ['required', 'string', 'regex:/^\+92[0-9]{10}$/']
            ]);

            $user = User::where('phone', $request->phone)->first();

            return response()->json([
                'success' => true,
                'data' => [
                    'user_exists' => !is_null($user),
                    'has_pin_set' => $user ? !is_null($user->pin_hash) : false,
                    'phone_verified' => $user ? $user->hasVerifiedPhone() : false
                ]
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Check phone failed', [
                'error' => $e->getMessage(),
                'phone' => $request->phone ?? null
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to check phone number.'
            ], 500);
        }
    }

    /**
     * Send OTP to customer's phone number
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendOtp(Request $request)
    {
        try {
            $request->validate([
                'phone' => ['required', 'string', 'regex:/^\+92[0-9]{10}$/'],
                'purpose' => ['nullable', 'in:login,reset_pin']
            ]);

            $phone = $request->phone;
            $purpose = $request->purpose ?? 'login';

            // Send OTP
            $result = $this->otpService->sendOtp(
                $phone,
                $purpose,
                $request->ip(),
                $request->userAgent()
            );

            if (!$result['success']) {
                // Log excessive OTP requests
                if (strpos($result['message'], 'rate limit') !== false) {
                    SecurityLogService::logExcessiveOtpRequests($phone, 0);
                }

                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 429);
            }

            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'expires_in_minutes' => $result['expires_in_minutes']
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Send OTP failed', [
                'error' => $e->getMessage(),
                'phone' => $request->phone ?? null
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP. Please try again later.'
            ], 500);
        }
    }

    /**
     * Verify OTP and login/register customer
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyOtp(Request $request)
    {
        try {
            $request->validate([
                'phone' => ['required', 'string', 'regex:/^\+92[0-9]{10}$/'],
                'otp' => ['required', 'string', 'size:6'],
                'purpose' => ['nullable', 'in:login,reset_pin']
            ]);

            $phone = $request->phone;
            $otp = $request->otp;
            $purpose = $request->purpose ?? 'login';

            // Verify OTP
            $result = $this->otpService->verifyOtp($phone, $otp, $purpose);

            if (!$result['success']) {
                // Log failed OTP verification attempts
                SecurityLogService::logFailedLogin($phone, 'Invalid OTP: ' . $result['message']);

                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 400);
            }

            // Find or create user
            $user = User::where('phone', $phone)->first();

            if (!$user) {
                // Create new customer
                DB::beginTransaction();
                try {
                    // Get customer role
                    $customerRole = Role::where('name', 'customer')->first();

                    if (!$customerRole) {
                        throw new \Exception('Customer role not found in database');
                    }

                    // Create user
                    $user = User::create([
                        'phone' => $phone,
                        'phone_verified_at' => now(),
                        'role_id' => $customerRole->id,
                        'is_active' => true,
                        'name' => 'Customer', // Temporary name, will be updated in profile
                    ]);

                    // Create customer profile
                    CustomerProfile::create([
                        'user_id' => $user->id,
                        'phone' => $phone,
                        'phone_verified' => true,
                    ]);

                    DB::commit();

                    $isNewUser = true;
                } catch (\Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
            } else {
                // Mark phone as verified if not already
                if (!$user->hasVerifiedPhone()) {
                    $user->markPhoneAsVerified();
                }

                $isNewUser = false;
            }

            // Update last login
            $user->last_login_at = now();
            $user->save();

            // Create web session login (for accessing customer dashboard routes)
            auth()->login($user, true); // true = remember me

            // Create token
            $token = $user->createToken('customer-auth')->plainTextToken;

            // Check if PIN is set
            $hasPinSet = !is_null($user->pin_hash);

            return response()->json([
                'success' => true,
                'message' => $isNewUser ? 'Account created successfully' : 'Login successful',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'phone' => $user->phone,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone_verified' => $user->hasVerifiedPhone(),
                    ],
                    'token' => $token,
                    'is_new_user' => $isNewUser,
                    'has_pin_set' => $hasPinSet,
                    'profile_completed' => !is_null($user->email) && $user->name !== 'Customer'
                ]
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Verify OTP failed', [
                'error' => $e->getMessage(),
                'phone' => $request->phone ?? null
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Verification failed. Please try again.'
            ], 500);
        }
    }

    /**
     * Set up 4-digit PIN for customer
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setupPin(Request $request)
    {
        try {
            $request->validate([
                'pin' => ['required', 'string', 'regex:/^[0-9]{4}$/'],
                'pin_confirmation' => ['required', 'same:pin']
            ]);

            $user = $request->user();

            if (!$user || !$user->isCustomer()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            // Set PIN
            $user->setPin($request->pin);

            return response()->json([
                'success' => true,
                'message' => 'PIN set successfully. You can now use it for quick login.'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Setup PIN failed', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id ?? null
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to set PIN. Please try again.'
            ], 500);
        }
    }

    /**
     * Login with phone number and PIN
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginWithPin(Request $request)
    {
        try {
            $request->validate([
                'phone' => ['required', 'string', 'regex:/^\+92[0-9]{10}$/'],
                'pin' => ['required', 'string', 'regex:/^[0-9]{4}$/']
            ]);

            $user = User::where('phone', $request->phone)
                ->whereNotNull('pin_hash')
                ->first();

            if (!$user) {
                SecurityLogService::logFailedLogin($request->phone, 'Phone not found or PIN not set');

                return response()->json([
                    'success' => false,
                    'message' => 'Invalid phone number or PIN not set. Please use OTP login.'
                ], 401);
            }

            // Check if PIN is locked
            if ($user->isPinLocked()) {
                $remainingMinutes = $user->getPinLockoutTimeRemaining();
                SecurityLogService::logPinLockout($request->phone, $user->pin_attempts);

                return response()->json([
                    'success' => false,
                    'message' => "PIN is locked. Please try again in {$remainingMinutes} minutes or use OTP login.",
                    'locked_until_minutes' => $remainingMinutes
                ], 423);
            }

            // Verify PIN
            if (!$user->verifyPin($request->pin)) {
                $attemptsRemaining = config('firebase.pin.max_attempts', 5) - $user->pin_attempts;

                // Log failed PIN attempt
                SecurityLogService::logFailedLogin($request->phone, "Invalid PIN attempt. Attempts: {$user->pin_attempts}");

                // If PIN is now locked, log lockout
                if ($user->isPinLocked()) {
                    SecurityLogService::logPinLockout($request->phone, $user->pin_attempts);
                }

                return response()->json([
                    'success' => false,
                    'message' => "Invalid PIN. {$attemptsRemaining} attempts remaining.",
                    'attempts_remaining' => $attemptsRemaining
                ], 401);
            }

            // Update last login
            $user->last_login_at = now();
            $user->save();

            // Create web session login (for accessing customer dashboard routes)
            auth()->login($user, true); // true = remember me

            // Create token
            $token = $user->createToken('customer-auth')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'phone' => $user->phone,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone_verified' => $user->hasVerifiedPhone(),
                    ],
                    'token' => $token
                ]
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('PIN login failed', [
                'error' => $e->getMessage(),
                'phone' => $request->phone ?? null
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Login failed. Please try again.'
            ], 500);
        }
    }

    /**
     * Request OTP for PIN reset
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPinRequest(Request $request)
    {
        try {
            $request->validate([
                'phone' => ['required', 'string', 'regex:/^\+92[0-9]{10}$/']
            ]);

            $user = User::where('phone', $request->phone)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Phone number not registered. Please register first.'
                ], 404);
            }

            // Send OTP for PIN reset
            $result = $this->otpService->sendOtp(
                $request->phone,
                'reset_pin',
                $request->ip(),
                $request->userAgent()
            );

            if (!$result['success']) {
                // Log excessive OTP requests
                if (strpos($result['message'], 'rate limit') !== false) {
                    SecurityLogService::logExcessiveOtpRequests($request->phone, 0);
                }

                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 429);
            }

            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'expires_in_minutes' => $result['expires_in_minutes']
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Reset PIN request failed', [
                'error' => $e->getMessage(),
                'phone' => $request->phone ?? null
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP. Please try again.'
            ], 500);
        }
    }

    /**
     * Reset PIN after OTP verification
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPin(Request $request)
    {
        try {
            $request->validate([
                'phone' => ['required', 'string', 'regex:/^\+92[0-9]{10}$/'],
                'otp' => ['required', 'string', 'size:6'],
                'new_pin' => ['required', 'string', 'regex:/^[0-9]{4}$/'],
                'new_pin_confirmation' => ['required', 'same:new_pin']
            ]);

            // Verify OTP (allow already-verified OTPs for reset_pin flow)
            $result = $this->otpService->verifyOtp($request->phone, $request->otp, 'reset_pin', true);

            if (!$result['success']) {
                // Log failed OTP verification for PIN reset
                SecurityLogService::logFailedLogin($request->phone, 'Invalid OTP during PIN reset: ' . $result['message']);

                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 400);
            }

            // Find user
            $user = User::where('phone', $request->phone)->first();

            if (!$user) {
                SecurityLogService::logFailedLogin($request->phone, 'User not found during PIN reset');

                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            // Reset PIN
            $user->resetPin($request->new_pin);

            return response()->json([
                'success' => true,
                'message' => 'PIN reset successfully. You can now login with your new PIN.'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Reset PIN failed', [
                'error' => $e->getMessage(),
                'phone' => $request->phone ?? null
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to reset PIN. Please try again.'
            ], 500);
        }
    }

    /**
     * Verify Firebase ID Token and login/register customer
     * This endpoint is used when Firebase Phone Auth is handled on the client-side
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyFirebaseToken(Request $request)
    {
        try {
            $request->validate([
                'id_token' => ['required', 'string']
            ]);

            // Verify Firebase ID token
            $verificationResult = $this->otpService->verifyFirebaseIdToken($request->id_token);

            if (!$verificationResult['success']) {
                SecurityLogService::logFailedLogin(
                    'Unknown',
                    'Firebase token verification failed: ' . $verificationResult['message']
                );

                return response()->json([
                    'success' => false,
                    'message' => $verificationResult['message']
                ], 401);
            }

            $firebaseData = $verificationResult['data'];
            $phone = $firebaseData['phone'];

            // Find or create user
            $user = User::where('phone', $phone)->first();

            if (!$user) {
                // Create new customer
                DB::beginTransaction();
                try {
                    // Get customer role
                    $customerRole = Role::where('name', 'customer')->first();

                    if (!$customerRole) {
                        throw new \Exception('Customer role not found in database');
                    }

                    // Create user with Firebase data
                    $user = User::create([
                        'phone' => $phone,
                        'email' => $firebaseData['email'] ?? null,
                        'name' => $firebaseData['display_name'] ?? 'Customer',
                        'phone_verified_at' => now(),
                        'email_verified_at' => ($firebaseData['email_verified'] ?? false) ? now() : null,
                        'role_id' => $customerRole->id,
                        'is_active' => true,
                        'firebase_uid' => $firebaseData['firebase_uid'],
                    ]);

                    // Create customer profile
                    CustomerProfile::create([
                        'user_id' => $user->id,
                        'phone' => $phone,
                        'phone_verified' => true,
                        'avatar' => $firebaseData['photo_url'] ?? null,
                    ]);

                    DB::commit();

                    $isNewUser = true;
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('Failed to create user from Firebase token', [
                        'error' => $e->getMessage(),
                        'firebase_uid' => $firebaseData['firebase_uid'] ?? null
                    ]);
                    throw $e;
                }
            } else {
                // Update existing user with Firebase data if needed
                $updates = [];

                // Mark phone as verified if not already
                if (!$user->hasVerifiedPhone()) {
                    $user->markPhoneAsVerified();
                }

                // Update Firebase UID if not set
                if (empty($user->firebase_uid)) {
                    $updates['firebase_uid'] = $firebaseData['firebase_uid'];
                }

                // Update email if provided and not set
                if (!empty($firebaseData['email']) && empty($user->email)) {
                    $updates['email'] = $firebaseData['email'];
                    if ($firebaseData['email_verified'] ?? false) {
                        $updates['email_verified_at'] = now();
                    }
                }

                // Update name if it's still default and Firebase has one
                if ($user->name === 'Customer' && !empty($firebaseData['display_name'])) {
                    $updates['name'] = $firebaseData['display_name'];
                }

                if (!empty($updates)) {
                    $user->update($updates);
                }

                $isNewUser = false;
            }

            // Update last login
            $user->last_login_at = now();
            $user->save();

            // Create web session login (for accessing customer dashboard routes)
            auth()->login($user, true); // true = remember me

            // Create Laravel Sanctum token for API access
            $token = $user->createToken('customer-firebase-auth')->plainTextToken;

            // Check if PIN is set
            $hasPinSet = !is_null($user->pin_hash);

            return response()->json([
                'success' => true,
                'message' => $isNewUser ? 'Account created successfully' : 'Login successful',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'phone' => $user->phone,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone_verified' => $user->hasVerifiedPhone(),
                        'email_verified' => !is_null($user->email_verified_at),
                    ],
                    'token' => $token,
                    'is_new_user' => $isNewUser,
                    'has_pin_set' => $hasPinSet,
                    'profile_completed' => !is_null($user->email) && $user->name !== 'Customer',
                    'auth_method' => 'firebase_phone'
                ]
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Firebase token verification failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Authentication failed. Please try again.'
            ], 500);
        }
    }

    /**
     * Logout customer (revoke token)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Logout failed', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id ?? null
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Logout failed'
            ], 500);
        }
    }
}
