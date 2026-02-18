<?php

use App\Http\Controllers\Api\SlideController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\PromotionController;
use App\Http\Controllers\Api\Auth\CustomerAuthController;
use App\Http\Controllers\Api\CustomerProfileController;
use App\Http\Controllers\Api\ExternalApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/slides', [SlideController::class, 'index']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/brands', [BrandController::class, 'index']);
Route::get('/promotions', [PromotionController::class, 'index']);

// Customer Authentication Routes (Public) with Rate Limiting
// IMPORTANT: 'web' middleware is added to enable session handling for auth()->login()
Route::prefix('customer/auth')->middleware(['web', 'check.blocked', 'throttle:customer-auth'])->group(function () {
    Route::post('/check-phone', [CustomerAuthController::class, 'checkPhone'])
        ->middleware('throttle:20,1'); // 20 requests per minute - higher for UX
    Route::post('/send-otp', [CustomerAuthController::class, 'sendOtp'])
        ->middleware('throttle:10,1'); // 10 requests per minute
    Route::post('/verify-otp', [CustomerAuthController::class, 'verifyOtp'])
        ->middleware('throttle:5,1'); // 5 requests per minute
    Route::post('/verify-firebase-token', [CustomerAuthController::class, 'verifyFirebaseToken'])
        ->middleware('throttle:10,1'); // 10 requests per minute - Firebase phone auth
    Route::post('/login-pin', [CustomerAuthController::class, 'loginWithPin'])
        ->middleware('throttle:5,1'); // 5 requests per minute
    Route::post('/reset-pin/request', [CustomerAuthController::class, 'resetPinRequest'])
        ->middleware('throttle:3,1'); // 3 requests per minute
    Route::post('/reset-pin/verify', [CustomerAuthController::class, 'resetPin'])
        ->middleware('throttle:5,1'); // 5 requests per minute
});

// Customer Protected Routes (Require Authentication)
Route::middleware('auth:sanctum')->prefix('customer')->group(function () {
    // Authentication
    Route::post('/auth/setup-pin', [CustomerAuthController::class, 'setupPin']);
    Route::post('/auth/logout', [CustomerAuthController::class, 'logout']);

    // Profile Management
    Route::get('/profile', [CustomerProfileController::class, 'show']);
    Route::put('/profile', [CustomerProfileController::class, 'update']);
    Route::post('/profile/avatar', [CustomerProfileController::class, 'uploadAvatar']);
    Route::delete('/profile/avatar', [CustomerProfileController::class, 'deleteAvatar']);

    // Get authenticated user
    Route::get('/me', function (Request $request) {
        return response()->json([
            'success' => true,
            'data' => [
                'user' => $request->user()
            ]
        ]);
    });
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// External Partner API
Route::prefix('external')->middleware(['check.blocked', 'external.api', 'throttle:60,1'])->group(function () {
    Route::post('/verify-member', [ExternalApiController::class, 'verifyMember']);
    Route::post('/report-transaction', [ExternalApiController::class, 'reportTransaction']);
});