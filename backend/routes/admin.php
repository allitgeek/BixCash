<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SlideController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\CustomerQueryController;
use App\Http\Controllers\Admin\EmailSettingController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here are all the admin panel routes. They are separate from the main
| web routes to keep admin functionality isolated and secure.
|
*/

// Admin Authentication Routes (public)
Route::prefix('admin')->name('admin.')->group(function () {
    // Login routes (accessible without authentication)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    });

    // Logout route (needs authentication)
    Route::post('/logout', [AuthController::class, 'logout'])
        ->middleware(['web', 'admin.auth'])
        ->name('logout');

    // Protected admin routes
    Route::middleware(['web', 'admin.auth'])->group(function () {

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.home');

        // User Management (Super Admin & Admin only)
        Route::middleware(['role.permission:manage_users'])->group(function () {
            Route::resource('users', UserController::class);
            Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        });

        // Content Management
        Route::middleware(['role.permission:manage_content'])->group(function () {
            Route::resource('slides', SlideController::class);
            Route::patch('slides/{slide}/toggle-status', [SlideController::class, 'toggleStatus'])->name('slides.toggle-status');
            Route::post('slides/reorder', [SlideController::class, 'reorder'])->name('slides.reorder');

            Route::resource('categories', CategoryController::class);
            Route::patch('categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
            Route::post('categories/reorder', [CategoryController::class, 'reorder'])->name('categories.reorder');

            Route::resource('promotions', PromotionController::class);
            Route::patch('promotions/{promotion}/toggle-status', [PromotionController::class, 'toggleStatus'])->name('promotions.toggle-status');
            Route::post('promotions/reorder', [PromotionController::class, 'reorder'])->name('promotions.reorder');
        });

        // Brand Management
        Route::middleware(['role.permission:manage_brands'])->group(function () {
            Route::resource('brands', BrandController::class);
            Route::patch('brands/{brand}/toggle-status', [BrandController::class, 'toggleStatus'])->name('brands.toggle-status');
            Route::patch('brands/{brand}/toggle-featured', [BrandController::class, 'toggleFeatured'])->name('brands.toggle-featured');
            Route::post('brands/reorder', [BrandController::class, 'reorder'])->name('brands.reorder');
        });

        // Analytics (view only for most admins)
        Route::middleware(['role.permission:view_analytics'])->group(function () {
            Route::get('analytics', [DashboardController::class, 'analytics'])->name('analytics');
            Route::get('reports', [DashboardController::class, 'reports'])->name('reports');
        });

        // Customer Queries Management
        Route::prefix('queries')->name('queries.')->group(function () {
            Route::get('/', [CustomerQueryController::class, 'index'])->name('index');
            Route::get('/{query}', [CustomerQueryController::class, 'show'])->name('show');
            Route::put('/{query}', [CustomerQueryController::class, 'update'])->name('update');
            Route::delete('/{query}', [CustomerQueryController::class, 'destroy'])->name('destroy');
        });

        // Email Settings (Super Admin & Admin)
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/email', [EmailSettingController::class, 'index'])->name('email');
            Route::put('/email', [EmailSettingController::class, 'update'])->name('email.update');
            Route::post('/email/test', [EmailSettingController::class, 'test'])->name('email.test');
        });

        // Settings (Super Admin only)
        Route::middleware(['role.permission:manage_settings'])->group(function () {
            Route::get('settings', [DashboardController::class, 'settings'])->name('settings');
        });
    });
});