<?php

use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    if (Auth::check()) {
        $user = Auth::user();

        // Smart routing based on user role
        if ($user->isPartner()) {
            $partnerProfile = $user->partnerProfile;

            if ($partnerProfile && $partnerProfile->status === 'pending') {
                return redirect()->route('partner.pending-approval');
            } elseif ($partnerProfile && $partnerProfile->status === 'approved') {
                return redirect()->route('partner.dashboard');
            } else {
                // Rejected or inactive
                Auth::logout();

                return redirect()->route('login')->withErrors(['phone' => 'Your partner account is not active.']);
            }
        } elseif ($user->isCustomer()) {
            return redirect()->route('customer.dashboard');
        } elseif ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
    }

    return view('auth.login');
})->name('login');

Route::get('/signup', function () {
    return view('auth.signup');
})->name('signup');

// Contact Form
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Firebase Phone Auth Demo (Testing)
Route::get('/auth-demo', function () {
    return view('customer-auth-demo');
})->name('auth.demo');

// Customer Dashboard Routes
use App\Http\Controllers\Customer\DashboardController as CustomerDashboard;

Route::prefix('customer')->name('customer.')->middleware(['auth', 'customer.role'])->group(function () {
    Route::get('/dashboard', [CustomerDashboard::class, 'index'])->name('dashboard');
    Route::post('/complete-profile', [CustomerDashboard::class, 'completeProfile'])->name('complete-profile');

    // Profile
    Route::get('/profile', [CustomerDashboard::class, 'profile'])->name('profile');
    Route::post('/profile', [CustomerDashboard::class, 'updateProfile'])->name('profile.update');

    // Bank Details (Rate Limited)
    Route::post('/profile/bank-details/request-otp', [CustomerDashboard::class, 'requestBankDetailsOtp'])
        ->name('bank-details.request-otp')
        ->middleware('throttle:3,60'); // 3 attempts per hour
    Route::post('/profile/bank-details/resend-otp', [CustomerDashboard::class, 'resendBankDetailsOtp'])
        ->name('bank-details.resend-otp')
        ->middleware('throttle:5,60'); // 5 resend attempts per hour
    Route::post('/profile/bank-details/verify-otp', [CustomerDashboard::class, 'verifyBankDetailsOtp'])
        ->name('bank-details.verify-otp')
        ->middleware('throttle:5,60'); // 5 verification attempts per hour
    Route::post('/profile/bank-details/verify-tpin', [CustomerDashboard::class, 'verifyBankDetailsTpin'])
        ->name('bank-details.verify-tpin')
        ->middleware('throttle:5,60'); // 5 TPIN verification attempts per hour
    Route::get('/profile/bank-details/cancel-otp', [CustomerDashboard::class, 'cancelBankDetailsOtp'])
        ->name('bank-details.cancel-otp');

    // Wallet
    Route::get('/wallet', [CustomerDashboard::class, 'wallet'])->name('wallet');
    Route::post('/wallet/withdraw', [CustomerDashboard::class, 'requestWithdrawal'])->name('wallet.withdraw');
    Route::post('/wallet/withdraw/{id}/cancel', [CustomerDashboard::class, 'cancelWithdrawal'])->name('wallet.cancel');

    // Purchase History
    Route::get('/purchases', [CustomerDashboard::class, 'purchaseHistory'])->name('purchases');

    // Partner Transaction Confirmation
    Route::get('/pending-transactions', [CustomerDashboard::class, 'getPendingTransactions'])->name('pending-transactions');
    Route::post('/confirm-transaction/{id}', [CustomerDashboard::class, 'confirmTransaction'])->name('confirm-transaction');
    Route::post('/reject-transaction/{id}', [CustomerDashboard::class, 'rejectTransaction'])->name('reject-transaction');

    // Logout
    Route::post('/logout', [CustomerDashboard::class, 'logout'])->name('logout');
});

// Partner Registration (Public)
use App\Http\Controllers\Partner\AuthController as PartnerAuth;

Route::get('/partner/register', [PartnerAuth::class, 'showRegistrationForm'])->name('partner.register');
Route::post('/partner/register', [PartnerAuth::class, 'register'])->name('partner.register.submit');

// Partner Pending Approval
Route::get('/partner/pending-approval', [PartnerAuth::class, 'pendingApproval'])
    ->name('partner.pending-approval')
    ->middleware('auth');

// Partner Portal (Authenticated + Approved Partners Only)
use App\Http\Controllers\Partner\DashboardController as PartnerDashboard;
use App\Http\Controllers\Partner\CommissionController as PartnerCommission;

Route::prefix('partner')->name('partner.')->middleware(['auth', 'partner'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [PartnerDashboard::class, 'index'])->name('dashboard');

    // Customer search and transaction creation
    Route::post('/search-customer', [PartnerDashboard::class, 'searchCustomer'])->name('search-customer');
    Route::post('/create-transaction', [PartnerDashboard::class, 'createTransaction'])->name('create-transaction');
    Route::get('/transaction-status/{id}', [PartnerDashboard::class, 'getTransactionStatus'])->name('transaction-status');

    // Transaction history
    Route::get('/transactions', [PartnerDashboard::class, 'transactionHistory'])->name('transactions');

    // Profit history - DISABLED
    // Route::get('/profits', [PartnerDashboard::class, 'profitHistory'])->name('profits');

    // Wallet
    Route::get('/wallet', [PartnerDashboard::class, 'wallet'])->name('wallet');
    Route::post('/wallet/withdraw', [PartnerDashboard::class, 'requestWithdrawal'])->name('wallet.withdraw');

    // Profile
    Route::get('/profile', [PartnerDashboard::class, 'profile'])->name('profile');
    Route::post('/profile', [PartnerDashboard::class, 'updateProfile'])->name('profile.update');

    // Bank Details (Rate Limited)
    Route::post('/profile/bank-details/request-otp', [PartnerDashboard::class, 'requestBankDetailsOtp'])
        ->name('bank-details.request-otp')
        ->middleware('throttle:3,60'); // 3 attempts per hour
    Route::post('/profile/bank-details/resend-otp', [PartnerDashboard::class, 'resendBankDetailsOtp'])
        ->name('bank-details.resend-otp')
        ->middleware('throttle:5,60'); // 5 resend attempts per hour
    Route::post('/profile/bank-details/verify-otp', [PartnerDashboard::class, 'verifyBankDetailsOtp'])
        ->name('bank-details.verify-otp')
        ->middleware('throttle:5,60'); // 5 verification attempts per hour
    Route::post('/profile/bank-details/verify-tpin', [PartnerDashboard::class, 'verifyBankDetailsTpin'])
        ->name('bank-details.verify-tpin')
        ->middleware('throttle:5,60'); // 5 TPIN verification attempts per hour
    Route::get('/profile/bank-details/cancel-otp', [PartnerDashboard::class, 'cancelBankDetailsOtp'])->name('bank-details.cancel-otp');

    Route::delete('/profile/logo', [PartnerDashboard::class, 'removeLogo'])->name('profile.remove-logo');

    // Commissions
    Route::get('/commissions', [PartnerCommission::class, 'index'])->name('commissions');
    Route::get('/commissions/{ledgerId}', [PartnerCommission::class, 'show'])->name('commissions.show');
    Route::get('/commissions/{ledgerId}/invoice', [PartnerCommission::class, 'downloadInvoice'])->name('commissions.invoice');

    // Logout
    Route::post('/logout', [PartnerDashboard::class, 'logout'])->name('logout');
});
