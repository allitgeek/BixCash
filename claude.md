# BixCash Development Documentation

**Last Updated**: October 14, 2025
**Project**: BixCash - Shop to Earn Platform
**Environment**: Production (bixcash.com)
**Server**: GCP (34.55.43.43)

---

## Project Overview

BixCash is a cashback rewards platform where customers earn rewards by shopping with partner brands. The platform consists of:
- **Customer App**: Mobile-first web app for customers to browse brands, make purchases, and earn cashback
- **Partner Portal**: Interface for brands to manage their offers and promotions
- **Admin Panel**: Backend management system for platform administration

---

## Technology Stack

### Backend
- **Framework**: Laravel 12
- **PHP**: 8.3
- **Database**: MySQL (bixcash_prod)
- **Authentication**: Laravel Sanctum (API tokens)
- **Queue**: Database driver with Supervisor (2 workers)
- **Scheduler**: Laravel Cron (runs every minute)

### Frontend
- **Build Tool**: Vite
- **CSS**: Custom CSS with CSS variables
- **JavaScript**: Vanilla JS (no framework)

### Infrastructure
- **Web Server**: Apache 2.4 with mod_php
- **SSL**: Let's Encrypt (auto-renewing)
- **Process Manager**: Supervisor for queue workers
- **Domain**: bixcash.com (via Namecheap DNS)

### External Services
- **Firebase**: OTP management and credentials storage
- **Future**: SMS service integration (Twilio or Firebase Phone Auth)

---

## Recent Development Session (October 14, 2025)

### 🎯 Main Achievement: Customer Phone Authentication System

Implemented a complete phone-based authentication system with Firebase OTP and PIN login:

#### Features Implemented

1. **Phone-Based Authentication**
   - E.164 phone format validation (+92XXXXXXXXXX for Pakistan)
   - Firebase OTP generation and verification
   - 6-digit OTP with 5-minute expiry
   - Rate limiting: 30-second resend delay, 10 OTPs per day

2. **PIN Authentication**
   - 4-digit numeric PIN for quick login
   - Bcrypt hashing for security
   - Lockout after 5 failed attempts (15 minutes)
   - Smart login flow: existing users with PIN skip OTP

3. **Smart Login Flow**
   - System checks if phone number has PIN set
   - Users with PIN → direct to PIN login screen
   - Users without PIN → OTP verification flow
   - New users → OTP verification + PIN setup

4. **PIN Reset Flow**
   - "Forgot PIN" triggers OTP verification
   - OTP verified once, then used for PIN reset
   - Allows already-verified OTPs (fixed double-verification bug)

5. **Admin Panel Enhancements**
   - Firebase configuration UI (save service account JSON)
   - Customer management (list, view, edit, block/unblock)
   - Firebase credentials stored in storage/app/firebase/

6. **Security Features**
   - Security event logging (failed logins, lockouts, suspicious activity)
   - IP blocking middleware
   - Rate limiting on all auth endpoints
   - Smart rate limiting: only counts unverified OTPs

---

## API Endpoints

### Public Authentication Endpoints

```
POST /api/customer/auth/check-phone
  - Check if phone number has PIN set
  - Rate limit: 20 requests/min
  - Body: { "phone": "+923023772000" }

POST /api/customer/auth/send-otp
  - Send OTP to phone number
  - Rate limit: 10 requests/min
  - Body: { "phone": "+923023772000", "purpose": "login|reset_pin" }

POST /api/customer/auth/verify-otp
  - Verify OTP code
  - Rate limit: 5 requests/min
  - Body: { "phone": "+923023772000", "otp": "123456", "purpose": "login|reset_pin" }

POST /api/customer/auth/login-pin
  - Login with PIN
  - Rate limit: 5 requests/min
  - Body: { "phone": "+923023772000", "pin": "1234" }

POST /api/customer/auth/reset-pin/request
  - Request OTP for PIN reset
  - Rate limit: 3 requests/min
  - Body: { "phone": "+923023772000" }

POST /api/customer/auth/reset-pin/verify
  - Reset PIN with OTP
  - Rate limit: 5 requests/min
  - Body: { "phone": "+923023772000", "otp": "123456", "new_pin": "1234", "new_pin_confirmation": "1234" }
```

### Protected Endpoints (Require Sanctum Token)

```
POST /api/customer/auth/setup-pin
  - Set up PIN after OTP verification
  - Body: { "pin": "1234", "pin_confirmation": "1234" }

POST /api/customer/auth/logout
  - Revoke current access token

GET /api/customer/profile
  - Get customer profile

PUT /api/customer/profile
  - Update customer profile
  - Body: { "name": "John", "email": "john@example.com", etc. }

POST /api/customer/profile/avatar
  - Upload profile avatar
  - Body: multipart/form-data with "avatar" file

DELETE /api/customer/profile/avatar
  - Delete profile avatar

GET /api/customer/me
  - Get authenticated user data
```

---

## Database Schema

### New Tables

#### otp_verifications
- `id`: Primary key
- `phone`: Phone number (+92XXXXXXXXXX)
- `otp_code`: 6-digit OTP
- `purpose`: login | reset_pin
- `expires_at`: OTP expiry timestamp
- `is_verified`: Boolean
- `attempts`: Verification attempt count
- `ip_address`: Request IP
- `user_agent`: Browser/device info
- `created_at`, `updated_at`

#### security_logs
- `id`: Primary key
- `event_type`: failed_login, pin_lockout, excessive_otp, etc.
- `phone`: Phone number (nullable)
- `ip_address`: Request IP (nullable)
- `user_agent`: Browser/device info (nullable)
- `metadata`: JSON with additional context
- `created_at`

### Modified Tables

#### users
- Added `phone`: Unique phone number (+92XXXXXXXXXX)
- Added `phone_verified_at`: Phone verification timestamp
- Added `pin_hash`: Bcrypt hashed PIN
- Added `pin_attempts`: Failed PIN attempt counter
- Added `pin_locked_until`: PIN lockout expiry timestamp
- Added `last_login_at`: Last successful login
- Made `email` nullable (phone-only registration)
- Made `password` nullable (phone-only registration)

#### customer_profiles
- Added `last_otp_sent_at`: Last OTP request timestamp
- Added `otp_attempts_today`: Daily OTP request counter

---

## Configuration Files

### config/firebase.php
```php
'credentials' => [
    'file' => env('FIREBASE_CREDENTIALS', storage_path('app/firebase/service-account.json')),
],
'otp' => [
    'length' => 6,
    'expiry_minutes' => 5,
    'max_attempts' => 3,
    'rate_limit_per_day' => 10,
    'resend_delay_seconds' => 30,
],
'pin' => [
    'length' => 4,
    'max_attempts' => 5,
    'lockout_minutes' => 15,
],
```

### .env Variables
```
FIREBASE_CREDENTIALS=/var/www/bixcash.com/backend/storage/app/firebase/service-account.json
FIREBASE_PROJECT_ID=bixcash-413b3
FIREBASE_DATABASE_URL=https://your-project-id.firebaseio.com
FIREBASE_STORAGE_BUCKET=your-project-id.appspot.com
```

---

## Key Files & Locations

### Controllers
- `app/Http/Controllers/Api/Auth/CustomerAuthController.php` - Main auth logic
- `app/Http/Controllers/Api/CustomerProfileController.php` - Profile management
- `app/Http/Controllers/Admin/CustomerController.php` - Admin customer management
- `app/Http/Controllers/Admin/DashboardController.php` - Settings page

### Services
- `app/Services/FirebaseOtpService.php` - OTP generation, verification, rate limiting
- `app/Services/SecurityLogService.php` - Security event logging

### Models
- `app/Models/User.php` - User model with PIN methods (setPin, verifyPin, isPinLocked)
- `app/Models/OtpVerification.php` - OTP records with scopes (forPhone, valid)
- `app/Models/CustomerProfile.php` - Customer profile data

### Middleware
- `app/Http/Middleware/EnsureCustomerRole.php` - Customer-only route protection
- `app/Http/Middleware/CheckBlockedIp.php` - IP blocking
- `app/Providers/RateLimitServiceProvider.php` - Custom rate limiting

### Views
- `resources/views/auth/login.blade.php` - Customer login UI (smart PIN/OTP flow)
- `resources/views/admin/dashboard/settings.blade.php` - Firebase config UI
- `resources/views/admin/customers/*.blade.php` - Customer management UI

### Migrations
- `2025_10_14_131756_add_customer_auth_fields_to_users_and_profiles.php`
- `2025_10_14_131801_create_otp_verifications_table.php`
- `2025_10_14_135456_create_security_logs_table.php`
- `2025_10_14_145050_make_email_nullable_in_users_table.php`
- `2025_10_14_145436_make_password_nullable_in_users_table.php`

### Routes
- `routes/api.php` - Customer API routes with rate limiting
- `routes/admin.php` - Admin panel routes

---

## Development Commands

### Artisan Commands

```bash
# Get latest OTP for testing
php artisan otp:latest [phone]

# Clear caches
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Run migrations
php artisan migrate --force

# Check queue workers
sudo supervisorctl status

# View logs
tail -f storage/logs/laravel.log
```

### Git Workflow

```bash
# Check status
git status

# Add changes
git add .

# Commit with detailed message
git commit -m "Description"

# Push to remote
git push
```

---

## Bugs Fixed Today

### 1. HTTP 500 - Site Down (RateLimiter Issue)
**Problem**: RateLimiter::for() called in bootstrap/app.php middleware closure before facade ready
**Solution**: Created RateLimitServiceProvider and moved rate limiter config to boot() method
**Files**: `bootstrap/app.php`, `app/Providers/RateLimitServiceProvider.php`

### 2. Firebase Config Permission Denied
**Problem**: Web server couldn't write to .env file
**Solution**: Changed ownership and permissions (chown www-data:www-data, chmod 664)
**Files**: `.env`, `storage/app/firebase/`

### 3. OTP Rate Limiting Too Aggressive
**Problem**: Old verified OTPs blocking new requests
**Solution**: Only count unverified OTPs in rate limiting, hourly limit instead of daily
**Files**: `app/Services/FirebaseOtpService.php`

### 4. User Creation Failed - Email Required
**Problem**: Database required email field but phone-only registration didn't provide it
**Solution**: Created migration to make email field nullable
**Files**: `database/migrations/2025_10_14_145050_make_email_nullable_in_users_table.php`

### 5. User Creation Failed - Password Required
**Problem**: Database required password field but phone-only registration didn't provide it
**Solution**: Created migration to make password field nullable
**Files**: `database/migrations/2025_10_14_145436_make_password_nullable_in_users_table.php`

### 6. OTP Verification Failed - createToken() Undefined
**Problem**: User model missing HasApiTokens trait for Sanctum
**Solution**: Added `use Laravel\Sanctum\HasApiTokens;` trait to User model
**Files**: `app/Models/User.php`

### 7. PIN Reset - OTP Already Verified Error
**Problem**: OTP verified on first screen, then re-verified on PIN save (fails because already used)
**Solution**: Added `allowAlreadyVerified` parameter to verifyOtp() to accept already-verified OTPs
**Files**: `app/Services/FirebaseOtpService.php`, `app/Http/Controllers/Api/Auth/CustomerAuthController.php`

### 8. Frontend Not Storing Verified OTP
**Problem**: Frontend verified OTP but didn't pass it to save PIN request
**Solution**: Added `this.verifiedOtp` variable to store OTP after verification
**Files**: `resources/views/auth/login.blade.php`

---

## Testing Credentials

### Test Phone Number
- Phone: `+923023772000` (or enter as `3023772000`)
- Current PIN: `1234`

### Admin Access
- URL: https://bixcash.com/admin/login
- Use your existing admin credentials

### Development OTP Retrieval
```bash
# Get latest OTP for any phone
php artisan otp:latest

# Get latest OTP for specific phone
php artisan otp:latest +923023772000
```

---

## Known Limitations

1. **SMS Not Integrated**: OTPs are stored in database only (no SMS sent)
   - Development mode: Use `php artisan otp:latest` to retrieve OTPs
   - Production ready: Need to integrate Twilio or Firebase Phone Auth

2. **Rate Limiting**: Some endpoints have aggressive rate limiting
   - If you hit "Too Many Attempts", wait 1-2 minutes
   - Rate limits: 5-20 requests/minute depending on endpoint

3. **Firebase Configuration**: Service account JSON must be valid
   - Stored in `storage/app/firebase/service-account.json`
   - Configure via Admin Settings page

---

## Next Steps / TODO

### Phase 11: SMS Integration (Not Started)
- [ ] Choose SMS provider (Twilio vs Firebase Phone Auth)
- [ ] Integrate SMS sending in FirebaseOtpService
- [ ] Test SMS delivery in production
- [ ] Add SMS delivery logging

### Phase 12: Customer App Features (Not Started)
- [ ] Browse brands and categories
- [ ] Make purchases and track cashback
- [ ] Wallet and transaction history
- [ ] Withdrawal requests

### Phase 13: Partner Portal (Not Started)
- [ ] Partner registration and onboarding
- [ ] Offer creation and management
- [ ] Analytics and reporting
- [ ] Commission settings

### Phase 14: Testing & QA (Ongoing)
- [x] Unit tests for auth services
- [ ] Integration tests for API endpoints
- [ ] E2E tests for customer flows
- [ ] Security audit

---

## Important Notes

### Security Considerations
- All OTP operations are logged to security_logs table
- Failed login attempts trigger security logging
- PIN lockouts are logged with attempt counts
- IP addresses can be blocked via CheckBlockedIp middleware

### Performance Optimizations
- Config/route/view caching enabled in production
- Composer autoloader optimized
- Queue workers handle background jobs
- Database indexes on phone numbers and timestamps

### Maintenance
- Queue workers monitored by Supervisor (auto-restart)
- Laravel scheduler runs via cron every minute
- SSL certificates auto-renew via Certbot
- Logs rotated by Laravel (daily rotation)

---

## Troubleshooting

### Issue: 500 Error on Login
**Check**:
1. Laravel logs: `tail -f storage/logs/laravel.log`
2. Apache logs: `sudo tail -f /var/log/apache2/error.log`
3. Clear caches: `php artisan config:clear && php artisan route:clear`

### Issue: OTP Not Generating
**Check**:
1. Firebase credentials: `ls -la storage/app/firebase/`
2. Firebase config: `php artisan tinker` → `config('firebase.credentials.file')`
3. OTP service: `php artisan tinker` → `app(App\Services\FirebaseOtpService::class)`

### Issue: Rate Limit Errors
**Solution**:
1. Wait 1-2 minutes for rate limit to reset
2. Clear OTP records: `php artisan tinker` → `App\Models\OtpVerification::truncate();`
3. Check rate limit config: `config/firebase.php`

### Issue: Queue Jobs Not Processing
**Check**:
1. Queue workers: `sudo supervisorctl status`
2. Restart workers: `sudo supervisorctl restart laravel-worker:*`
3. Check failed jobs: `php artisan queue:failed`

---

## Contact & Support

**Developer**: Working with Claude Code
**Last Session**: October 14, 2025
**Commit Hash**: c61f574
**Branch**: master

For questions or issues, refer to:
- CUSTOMER_AUTH_IMPLEMENTATION.md (detailed implementation guide)
- FIREBASE_CONFIG_GUIDE.md (Firebase setup instructions)
- This file (claude.md) for session history

---

## Change Log

### October 14, 2025
- ✅ Implemented complete customer phone authentication system
- ✅ Added Firebase OTP integration
- ✅ Added PIN authentication with lockout
- ✅ Created smart login flow (PIN vs OTP)
- ✅ Added PIN reset flow with OTP verification
- ✅ Created admin Firebase configuration UI
- ✅ Created customer management in admin panel
- ✅ Fixed 8 critical bugs
- ✅ Added security logging and rate limiting
- ✅ Made email/password nullable for phone-only registration
- ✅ Committed and pushed all changes to GitHub

### October 11, 2025 (Previous Session)
- ✅ Production deployment to bixcash.com
- ✅ SSL certificate installation
- ✅ Queue workers with Supervisor
- ✅ Laravel scheduler setup
- ✅ Mobile responsiveness improvements
- ✅ Hero slider image fixes
- ✅ Customer dashboard optimizations

---

**End of Documentation**
