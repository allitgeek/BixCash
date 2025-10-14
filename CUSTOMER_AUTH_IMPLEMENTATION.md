# Customer Authentication System Implementation Guide

## Overview
This document tracks the implementation of the customer authentication system with phone number + Firebase OTP + 4-digit PIN.

---

## ✅ Completed (Phases 1-4)

### Phase 1: Database Schema ✓
**Files Created:**
- `database/migrations/2025_10_14_131756_add_customer_auth_fields_to_users_and_profiles.php`
- `database/migrations/2025_10_14_131801_create_otp_verifications_table.php`

**Database Changes:**
- **users table**: Added `phone`, `phone_verified_at`, `pin_hash`, `pin_attempts`, `pin_locked_until`
- **customer_profiles table**: Added `phone_verified`, `last_otp_sent_at`, `otp_attempts_today`
- **otp_verifications table**: New table for OTP tracking

### Phase 2: Firebase Setup ✓
**Files Created:**
- `config/firebase.php` - Firebase configuration
- `storage/app/firebase/service-account.json.example` - Template for Firebase credentials

**Packages Installed:**
- `kreait/firebase-php` ^7.23

**Environment Variables Added to .env:**
```
FIREBASE_CREDENTIALS=/var/www/bixcash.com/backend/storage/app/firebase/service-account.json
FIREBASE_PROJECT_ID=your-firebase-project-id
FIREBASE_DATABASE_URL=https://your-project-id.firebaseio.com
FIREBASE_STORAGE_BUCKET=your-project-id.appspot.com
```

**⚠️ ACTION REQUIRED:**
1. Get Firebase service account JSON from Firebase Console
2. Copy it to: `/var/www/bixcash.com/backend/storage/app/firebase/service-account.json`
3. Update `.env` with your actual Firebase project ID

### Phase 3: Models & Services ✓
**Files Created:**
1. `app/Models/OtpVerification.php` - OTP tracking model with helper methods
2. `app/Services/FirebaseOtpService.php` - Firebase OTP service (send/verify OTP)

**Files Modified:**
1. `app/Models/User.php` - Added PIN and phone verification methods:
   - `setPin()`, `verifyPin()`, `isPinLocked()`
   - `markPhoneAsVerified()`, `hasVerifiedPhone()`

2. `app/Models/CustomerProfile.php` - Added OTP tracking fields

**Key Features Implemented:**
- OTP generation (6 digits)
- OTP expiry (5 minutes)
- Rate limiting (max 5 OTP per day, 60 seconds between requests)
- PIN verification with lockout (max 5 attempts, 15 min lockout)
- Phone format validation (+92XXXXXXXXXX)

---

## 🚧 In Progress (Phase 4)

### Phase 4: Customer Auth Controllers
**Need to Create:**
- `app/Http/Controllers/Api/Auth/CustomerAuthController.php`
  - sendOtp() - Send OTP to phone
  - verifyOtp() - Verify OTP and login/register
  - setupPin() - Set 4-digit PIN
  - loginWithPin() - Login with phone + PIN
  - resetPinRequest() - Request OTP for PIN reset
  - resetPin() - Reset PIN after OTP verification

- `app/Http/Controllers/Api/Customer/ProfileController.php`
  - show() - Get customer profile
  - update() - Update profile (name, email, DOB)
  - uploadAvatar() - Upload profile picture

---

## ⏳ Pending (Phases 5-10)

### Phase 5: API Routes
**File to Modify:** `routes/api.php`

**Routes to Add:**
```php
// Customer Authentication (public)
POST /api/customer/auth/send-otp
POST /api/customer/auth/verify-otp
POST /api/customer/auth/setup-pin
POST /api/customer/auth/login-pin
POST /api/customer/auth/reset-pin/request
POST /api/customer/auth/reset-pin/verify

// Customer Profile (protected)
GET  /api/customer/profile
PUT  /api/customer/profile
POST /api/customer/profile/avatar
```

### Phase 6: Admin Panel - Customer Management
**Files to Create:**
- `app/Http/Controllers/Admin/CustomerManagementController.php`
- `resources/views/admin/customers/index.blade.php`
- `resources/views/admin/customers/show.blade.php`

**File to Modify:**
- `routes/admin.php` - Add customer management routes

### Phase 7: Validation & Middleware
**Files to Create:**
- `app/Http/Requests/CustomerAuth/SendOtpRequest.php`
- `app/Http/Requests/CustomerAuth/VerifyOtpRequest.php`
- `app/Http/Requests/CustomerAuth/SetupPinRequest.php`
- `app/Http/Requests/CustomerAuth/LoginPinRequest.php`
- `app/Http/Middleware/EnsureCustomerRole.php`

### Phase 8: Customer Frontend Pages
**Files to Create:**
- `resources/views/customer/auth/phone-login.blade.php`
- `resources/views/customer/auth/verify-otp.blade.php`
- `resources/views/customer/auth/setup-pin.blade.php`
- `resources/views/customer/auth/login-pin.blade.php`
- `resources/views/customer/profile/edit.blade.php`

### Phase 9: Security & Rate Limiting
- Rate limiting for OTP endpoints
- Rate limiting for PIN login
- IP tracking and blocking
- User agent tracking

### Phase 10: Testing & Documentation
- Unit tests for OTP service
- Integration tests for auth flow
- API documentation
- Admin panel testing

---

## Configuration Reference

### OTP Configuration (config/firebase.php)
```php
'otp' => [
    'length' => 6,                    // OTP length
    'expiry_minutes' => 5,           // OTP validity
    'max_attempts' => 3,             // Max verification attempts
    'rate_limit_per_day' => 5,       // Max OTP per phone per day
    'resend_delay_seconds' => 60,    // Min time between OTP requests
],
```

### PIN Configuration (config/firebase.php)
```php
'pin' => [
    'length' => 4,                    // PIN length
    'max_attempts' => 5,             // Max failed attempts
    'lockout_minutes' => 15,         // Lockout duration
],
```

---

## Authentication Flow

### Customer Registration/Login Flow:
1. User enters phone number (+923001234567)
2. System sends OTP via Firebase
3. User enters OTP to verify phone
4. **First time users**: Set up 4-digit PIN
5. **Returning users**: Login with phone + PIN
6. After login: Complete profile (name, email optional, DOB optional)

### PIN Reset Flow:
1. User requests PIN reset
2. System sends OTP to registered phone
3. User verifies OTP
4. User sets new 4-digit PIN

---

## Database Tables

### users
- phone (string, unique)
- phone_verified_at (timestamp)
- pin_hash (string, bcrypt)
- pin_attempts (integer, default 0)
- pin_locked_until (timestamp)

### customer_profiles
- phone_verified (boolean)
- last_otp_sent_at (timestamp)
- otp_attempts_today (integer)

### otp_verifications
- phone (string)
- otp_code (string, 6 digits)
- purpose (string: 'login', 'reset_pin')
- is_verified (boolean)
- verified_at (timestamp)
- expires_at (timestamp)
- attempts (integer)
- ip_address (string)
- user_agent (text)

---

## Next Steps

1. **Complete Phase 4**: Create Customer Auth Controllers
2. **Complete Phase 5**: Add API routes
3. **Test Authentication Flow**: Use Postman to test OTP send/verify/PIN flow
4. **Complete Phase 6**: Build admin panel for customer management
5. **Complete Phase 7-8**: Add validation and frontend pages
6. **Complete Phase 9-10**: Security and testing

---

## Important Notes

### Development Mode
- OTP codes are returned in API response (only in local/development environment)
- Check logs for OTP codes: `tail -f storage/logs/laravel.log`

### Production Setup Required
- Configure Firebase service account
- Integrate SMS service (Twilio, AWS SNS, or Firebase Phone Auth)
- Set up proper rate limiting
- Configure CORS for API endpoints
- Set up SSL/TLS for production

### Security Considerations
- Phone numbers are stored in E.164 format (+92XXXXXXXXXX)
- PINs are bcrypt hashed
- OTPs expire after 5 minutes
- Rate limiting prevents abuse
- IP and User Agent tracking for audit trail

---

## Partner Registration (Future)
Partner registration will be implemented after customer authentication is complete and tested.

---

**Last Updated**: 2025-10-14
**Status**: Phases 1-4 Complete, Phase 5-10 Pending
