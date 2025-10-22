<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ContactController;

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

Route::prefix('customer')->name('customer.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [CustomerDashboard::class, 'index'])->name('dashboard');
    Route::post('/complete-profile', [CustomerDashboard::class, 'completeProfile'])->name('complete-profile');

    // Profile
    Route::get('/profile', [CustomerDashboard::class, 'profile'])->name('profile');
    Route::post('/profile', [CustomerDashboard::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/bank-details/request-otp', [CustomerDashboard::class, 'requestBankDetailsOtp'])->name('bank-details.request-otp');
    Route::post('/profile/bank-details/verify-otp', [CustomerDashboard::class, 'verifyBankDetailsOtp'])->name('bank-details.verify-otp');
    Route::get('/profile/bank-details/cancel-otp', [CustomerDashboard::class, 'cancelBankDetailsOtp'])->name('bank-details.cancel-otp');

    // Wallet
    Route::get('/wallet', [CustomerDashboard::class, 'wallet'])->name('wallet');
    Route::post('/wallet/withdraw', [CustomerDashboard::class, 'requestWithdrawal'])->name('wallet.withdraw');

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

Route::prefix('partner')->name('partner.')->middleware(['auth', 'partner'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [PartnerDashboard::class, 'index'])->name('dashboard');

    // Customer search and transaction creation
    Route::post('/search-customer', [PartnerDashboard::class, 'searchCustomer'])->name('search-customer');
    Route::post('/create-transaction', [PartnerDashboard::class, 'createTransaction'])->name('create-transaction');
    Route::get('/transaction-status/{id}', [PartnerDashboard::class, 'getTransactionStatus'])->name('transaction-status');

    // Transaction history
    Route::get('/transactions', [PartnerDashboard::class, 'transactionHistory'])->name('transactions');

    // Profit history
    Route::get('/profits', [PartnerDashboard::class, 'profitHistory'])->name('profits');

    // Profile
    Route::get('/profile', [PartnerDashboard::class, 'profile'])->name('profile');
    Route::post('/profile', [PartnerDashboard::class, 'updateProfile'])->name('profile.update');
    Route::delete('/profile/logo', [PartnerDashboard::class, 'removeLogo'])->name('profile.remove-logo');

    // Logout
    Route::post('/logout', [PartnerDashboard::class, 'logout'])->name('logout');
});
