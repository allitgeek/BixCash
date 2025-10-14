# Firebase Configuration Guide - Admin Panel

## 🎉 New Feature: Firebase Settings in Admin Panel

You can now configure Firebase directly from the BixCash admin panel without manually editing files!

---

## How to Access

1. Login to admin panel: `https://bixcash.com/admin/login`
2. Navigate to: **Settings** (in the sidebar or main menu)
3. Scroll to: **Firebase Configuration (Customer Authentication)** section

---

## Configuration Steps

### Step 1: Get Firebase Service Account JSON

1. Go to [Firebase Console](https://console.firebase.google.com/)
2. Select your project (or create a new one)
3. Click the **⚙️ gear icon** → **Project Settings**
4. Go to **Service Accounts** tab
5. Click **"Generate New Private Key"** button
6. Download the JSON file
7. Open the file and **copy all the JSON content**

### Step 2: Fill in the Form

In the admin panel Firebase configuration section, enter:

#### **Firebase Project ID** (Required)
- Found in Firebase Console → Project Settings → General
- Example: `bixcash-prod-a1b2c`

#### **Firebase Database URL** (Optional)
- Only needed if using Firebase Realtime Database
- Example: `https://bixcash-prod-a1b2c.firebaseio.com`
- Leave empty if not using

#### **Firebase Storage Bucket** (Optional)
- Only needed if using Firebase Storage
- Example: `bixcash-prod-a1b2c.appspot.com`
- Leave empty if not using

#### **Service Account JSON** (Required)
- Paste the **entire JSON content** from the downloaded file
- Example structure:
```json
{
  "type": "service_account",
  "project_id": "bixcash-prod",
  "private_key_id": "abc123...",
  "private_key": "-----BEGIN PRIVATE KEY-----\n...\n-----END PRIVATE KEY-----\n",
  "client_email": "firebase-adminsdk@bixcash-prod.iam.gserviceaccount.com",
  "client_id": "123456789",
  ...
}
```

### Step 3: Test Connection

1. Click **"Test Connection"** button
2. Wait for the response
3. If successful, you'll see: ✓ "Firebase connection successful! Configuration is valid."
4. If failed, check:
   - JSON format is correct
   - Project ID matches
   - Service account has proper permissions

### Step 4: Save Configuration

1. Click **"Save Configuration"** button
2. System will:
   - ✅ Validate the JSON format
   - ✅ Save credentials to `/storage/app/firebase/service-account.json`
   - ✅ Update `.env` file automatically
   - ✅ Clear Laravel config cache

3. You'll see success message: "Firebase configuration updated successfully!"

---

## What Happens Behind the Scenes

When you save Firebase configuration:

1. **JSON File Created**:
   - Path: `/var/www/bixcash.com/backend/storage/app/firebase/service-account.json`
   - Format: Pretty-printed JSON
   - Permissions: Secure (only readable by application)

2. **.env File Updated**:
   ```env
   FIREBASE_PROJECT_ID=your-project-id
   FIREBASE_DATABASE_URL=https://your-project-id.firebaseio.com
   FIREBASE_STORAGE_BUCKET=your-project-id.appspot.com
   FIREBASE_CREDENTIALS=/var/www/bixcash.com/backend/storage/app/firebase/service-account.json
   ```

3. **Config Cache Cleared**:
   - Runs `php artisan config:clear`
   - Ensures new configuration is loaded immediately

---

## Status Indicator

At the top of the Firebase section, you'll see:

- **✓ Configured** (Green) - Firebase is set up and ready
- **⚠ Not Configured** (Red) - Firebase needs to be configured

---

## Troubleshooting

### "Invalid Firebase service account JSON"
- Make sure you pasted the **complete JSON** including opening `{` and closing `}`
- Verify the JSON has `"type": "service_account"` field
- Check for any copy-paste errors or missing quotes

### "Firebase connection failed"
- Project ID doesn't match the service account
- Service account doesn't have proper permissions
- Private key is corrupted or incomplete

### "Failed to update Firebase configuration"
- Check file permissions on `/storage/app/firebase/` directory
- Ensure `.env` file is writable
- Check Laravel logs: `tail -f storage/logs/laravel.log`

---

## Security Notes

- ✅ Service account JSON is stored securely in `/storage/app/` (not publicly accessible)
- ✅ `.env` file is never committed to git (.gitignore)
- ✅ Only Super Admins can access Firebase settings
- ✅ JSON validation prevents invalid credentials from being saved
- ⚠️ Keep your service account JSON secret - never share it publicly!

---

## Next Steps

Once Firebase is configured:

1. ✅ Customer authentication with OTP will be enabled
2. ✅ Customers can register using phone number (+92XXXXXXXXXX)
3. ✅ OTP verification for phone number
4. ✅ 4-digit PIN setup for quick login
5. ✅ PIN reset via OTP

---

## Need Help?

If you encounter any issues:

1. Check the **Test Connection** button first
2. Review error messages carefully
3. Check Laravel logs: `/storage/logs/laravel.log`
4. Verify Firebase Console settings
5. Ensure service account has **"Firebase Admin SDK Administrator Service Agent"** role

---

**Last Updated**: 2025-10-14
**Feature Added**: Phase 4.5 - Firebase Admin Panel Configuration
