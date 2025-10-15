<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
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
});
