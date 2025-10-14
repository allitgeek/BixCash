# Firebase SMS OTP Authentication - Complete Setup Guide

## 📋 Overview

This guide will help you configure Firebase Phone Authentication to enable SMS OTP delivery for BixCash. The implementation uses a **hybrid architecture**:

- **Frontend**: Firebase JavaScript SDK handles SMS sending/receiving
- **Backend**: PHP Laravel verifies Firebase tokens and manages user sessions

---

## 🎯 Prerequisites

- Firebase project already created (Project ID: `bixcash-413b3`)
- Service account JSON already configured
- Access to Firebase Console
- Google Cloud billing account (required for Phone Auth)

---

## 📝 Step-by-Step Setup Instructions

### **Step 1: Enable Phone Authentication in Firebase Console** (5 min)

1. Go to [Firebase Console](https://console.firebase.google.com/)
2. Select your project: **bixcash-413b3**
3. Navigate to **Authentication** → **Sign-in method**
4. Click on **Phone** provider
5. Click **Enable** toggle
6. Click **Save**

### **Step 2: Configure SMS Region Policy for Pakistan** (3 min)

⚠️ **IMPORTANT**: This prevents SMS abuse and ensures Pakistan delivery

1. In Firebase Console, go to **Authentication** → **Settings**
2. Scroll to **SMS region policy**
3. Select **Allow** (radio button)
4. Find and check **Pakistan (+92)**
5. Click **Save**

### **Step 3: Set Up Billing (Required)** (5 min)

Firebase Phone Auth requires Blaze (pay-as-you-go) plan:

1. In Firebase Console, click **Upgrade** (bottom left)
2. Select **Blaze Plan**
3. Link your Google Cloud billing account
4. Accept the pricing:
   - **Cost**: $0.06 per SMS to Pakistan
   - **Example**: 1000 OTPs/month = $60
   - No monthly minimum, pay only for SMS sent

### **Step 4: Get Firebase Web Credentials** (5 min)

1. In Firebase Console, go to **Project Settings** (gear icon)
2. Scroll down to **Your apps** section
3. If you don't have a web app:
   - Click **Add app** → Select **Web** (</> icon)
   - Enter nickname: `BixCash Web`
   - Check **"Also set up Firebase Hosting"** (optional)
   - Click **Register app**
4. You'll see Firebase configuration object:

```javascript
const firebaseConfig = {
  apiKey: "AIzaSy...", // ← Copy this
  authDomain: "bixcash-413b3.firebaseapp.com",
  projectId: "bixcash-413b3",
  storageBucket: "bixcash-413b3.appspot.com",
  messagingSenderId: "123456789", // ← Copy this
  appId: "1:123456789:web:abc123..." // ← Copy this
};
```

5. **Copy these values**:
   - `apiKey` → **FIREBASE_WEB_API_KEY**
   - `messagingSenderId` → **FIREBASE_MESSAGING_SENDER_ID**
   - `appId` → **FIREBASE_APP_ID**

### **Step 5: Update Environment Variables** (3 min)

1. SSH into your server:
   ```bash
   ssh your-server
   ```

2. Edit `.env` file:
   ```bash
   cd /var/www/bixcash.com/backend
   nano .env
   ```

3. Find and replace the placeholders (lines 75-78):
   ```env
   # Firebase Web Client Configuration
   FIREBASE_WEB_API_KEY=AIzaSyXXXXXXXXXXXXXXXXXXXXX  # ← Paste your actual API key
   FIREBASE_AUTH_DOMAIN=bixcash-413b3.firebaseapp.com
   FIREBASE_MESSAGING_SENDER_ID=123456789  # ← Paste your actual sender ID
   FIREBASE_APP_ID=1:123456789:web:abc123def456  # ← Paste your actual app ID
   ```

4. Save and exit (Ctrl+X, then Y, then Enter)

5. Clear config cache:
   ```bash
   php artisan config:clear
   ```

### **Step 6: Add Authorized Domains** (2 min)

1. In Firebase Console → **Authentication** → **Settings**
2. Scroll to **Authorized domains**
3. Click **Add domain**
4. Enter: `bixcash.com`
5. Click **Add**
6. If testing on localhost, add: `localhost`

### **Step 7: Test Phone Authentication** (10 min)

#### **Option A: Using Demo Page**

1. Add route to `routes/web.php`:
   ```php
   Route::get('/auth-demo', function () {
       return view('customer-auth-demo');
   });
   ```

2. Visit: `https://bixcash.com/auth-demo`

3. Enter your Pakistan phone number: `+923XXXXXXXXX`

4. Click **Send OTP**

5. Check your phone for SMS

6. Enter the 6-digit code

7. Click **Verify & Login**

#### **Option B: Using API Directly**

Test the API endpoint:

```bash
curl -X POST https://bixcash.com/api/customer/auth/verify-firebase-token \
  -H "Content-Type: application/json" \
  -d '{
    "id_token": "YOUR_FIREBASE_ID_TOKEN_HERE"
  }'
```

---

## 🏗️ Architecture Overview

### **Frontend Flow:**

1. User enters phone number
2. Firebase SDK sends SMS automatically
3. User receives OTP via SMS
4. User enters OTP code
5. Firebase verifies OTP
6. Firebase returns ID token

### **Backend Flow:**

7. Frontend sends ID token to `/api/customer/auth/verify-firebase-token`
8. Backend verifies ID token with Firebase Admin SDK
9. Backend extracts phone number from verified token
10. Backend creates/updates user in database
11. Backend returns Laravel Sanctum token for API access

---

## 📁 Files Modified/Created

### **Created:**
- `public/js/firebase-auth.js` - Client-side authentication module
- `resources/views/customer-auth-demo.blade.php` - Demo page
- `FIREBASE_SETUP_INSTRUCTIONS.md` - This file

### **Modified:**
- `config/firebase.php` - Added web client configuration
- `app/Services/FirebaseOtpService.php` - Added `verifyFirebaseIdToken()` method
- `app/Http/Controllers/Api/Auth/CustomerAuthController.php` - Added `verifyFirebaseToken()` endpoint
- `routes/api.php` - Added `/verify-firebase-token` route
- `.env` - Added Firebase web credentials

---

## 🔍 Troubleshooting

### **SMS Not Receiving:**

1. Check SMS region policy includes Pakistan
2. Verify billing is enabled (Blaze plan)
3. Check Firebase quotas: Console → Usage & Billing
4. Ensure phone number format is correct: `+92XXXXXXXXXX`

### **reCAPTCHA Issues:**

1. Add your domain to authorized domains
2. Check browser console for errors
3. Try in incognito mode
4. Clear browser cache

### **Token Verification Fails:**

1. Check service account JSON exists: `/var/www/bixcash.com/backend/storage/app/firebase/service-account.json`
2. Verify `FIREBASE_PROJECT_ID` matches: `bixcash-413b3`
3. Check Laravel logs: `tail -f storage/logs/laravel.log`

### **Configuration Not Loading:**

```bash
php artisan config:clear
php artisan config:cache
```

---

## 💰 Cost Management

### **Monitor Usage:**

1. Firebase Console → Usage & Billing
2. Set up budget alerts in Google Cloud Console
3. Track SMS delivery rates

### **Optimize Costs:**

- Implement rate limiting (already done in `routes/api.php`)
- Block suspicious IP addresses
- Monitor for abuse patterns
- Consider test phone numbers for development

### **Expected Costs:**

| Monthly OTPs | Cost      |
|--------------|-----------|
| 100          | $6        |
| 500          | $30       |
| 1,000        | $60       |
| 5,000        | $300      |
| 10,000       | $600      |

---

## 🔐 Security Best Practices

### **Already Implemented:**

✅ Rate limiting on all auth endpoints
✅ IP-based blocking middleware
✅ Security logging for failed attempts
✅ Phone number format validation
✅ Firebase token expiration handling
✅ Laravel Sanctum for API authentication

### **Recommended:**

- Enable 2FA for Firebase Console access
- Regularly review authorized domains
- Monitor security logs
- Set up alerts for unusual activity
- Keep Firebase SDK updated

---

## 📊 Monitoring & Logging

### **Check Firebase Logs:**

- Firebase Console → Authentication → Users
- View recent sign-ins and attempts

### **Check Laravel Logs:**

```bash
tail -f /var/www/bixcash.com/backend/storage/logs/laravel.log
```

### **Monitor API Usage:**

```bash
# Check recent OTP requests
php artisan tinker
>>> \App\Models\OtpVerification::latest()->take(10)->get();
```

---

## 🎉 Testing Checklist

Before going live, verify:

- [ ] Firebase Phone Auth enabled
- [ ] Pakistan added to allowed regions
- [ ] Billing configured and active
- [ ] Web API key added to `.env`
- [ ] Authorized domains configured
- [ ] SMS delivery works to real Pakistan number
- [ ] Token verification endpoint works
- [ ] User creation/login works
- [ ] Rate limiting is functional
- [ ] Error handling works correctly

---

## 📞 Support

### **Firebase Issues:**
- [Firebase Support](https://firebase.google.com/support)
- [Firebase Documentation](https://firebase.google.com/docs/auth/web/phone-auth)

### **Application Issues:**
- Check Laravel logs: `/var/www/bixcash.com/backend/storage/logs/`
- Review Firebase Admin PHP SDK docs: https://firebase-php.readthedocs.io/

---

## 🚀 Next Steps

After setup is complete:

1. Test with multiple phone numbers
2. Monitor costs for first week
3. Integrate into your main frontend application
4. Set up production monitoring
5. Configure backup authentication methods (email, social)

---

**Setup completed by:** Claude Code Assistant
**Date:** 2025-10-15
**Project:** BixCash Firebase SMS OTP Integration
