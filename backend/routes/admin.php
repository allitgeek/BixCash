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
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\ContextController;
use App\Http\Controllers\Admin\WithdrawalController;
use App\Http\Controllers\Admin\WithdrawalSettingsController;
use App\Http\Controllers\Admin\CommissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\WhatsAppSettingsController;

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
    // Login routes (accessible without admin authentication)
    // Note: No 'guest' middleware to allow customer/partner users to access admin login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

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
            Route::get('profit-sharing', [DashboardController::class, 'profitSharing'])->name('profit-sharing');
            Route::post('profit-sharing/run-assignment', [DashboardController::class, 'runProfitSharingAssignment'])->name('profit-sharing.run-assignment');
            Route::post('profit-sharing/disperse', [DashboardController::class, 'disperseProfitSharing'])->name('profit-sharing.disperse');
            Route::get('profit-sharing/history', [DashboardController::class, 'profitSharingHistory'])->name('profit-sharing.history');
            Route::get('profit-sharing/history/{distribution}', [DashboardController::class, 'profitSharingHistoryDetail'])->name('profit-sharing.history.detail');
        });

        // Customer Management
        Route::middleware(['role.permission:manage_users'])->group(function () {
            Route::resource('customers', CustomerController::class)->except(['create', 'store']);
            Route::patch('customers/{customer}/toggle-status', [CustomerController::class, 'toggleStatus'])->name('customers.toggle-status');
            Route::post('customers/{customer}/reset-pin', [CustomerController::class, 'resetPin'])->name('customers.reset-pin');
            Route::post('customers/{customer}/unlock-pin', [CustomerController::class, 'unlockPin'])->name('customers.unlock-pin');
            Route::get('customers/{customer}/transactions', [CustomerController::class, 'transactions'])->name('customers.transactions');
            Route::post('customers/{customer}/verify-phone', [CustomerController::class, 'verifyPhone'])->name('customers.verify-phone');
        });

        // Partner Management
        Route::middleware(['role.permission:manage_users'])->prefix('partners')->name('partners.')->group(function () {
            Route::get('/', [PartnerController::class, 'index'])->name('index');
            Route::get('/create', [PartnerController::class, 'create'])->name('create');
            Route::post('/', [PartnerController::class, 'store'])->name('store');
            Route::post('/send-registration-otp', [PartnerController::class, 'sendRegistrationOtp'])->name('send-registration-otp');
            Route::post('/verify-registration-otp', [PartnerController::class, 'verifyRegistrationOtp'])->name('verify-registration-otp');
            Route::get('/pending', [PartnerController::class, 'pendingApplications'])->name('pending');
            Route::get('/{partner}', [PartnerController::class, 'show'])->name('show');
            Route::get('/{partner}/edit', [PartnerController::class, 'edit'])->name('edit');
            Route::put('/{partner}', [PartnerController::class, 'update'])->name('update');
            Route::post('/{partner}/approve', [PartnerController::class, 'approve'])->name('approve');
            Route::post('/{partner}/reject', [PartnerController::class, 'reject'])->name('reject');
            Route::patch('/{partner}/status', [PartnerController::class, 'updateStatus'])->name('update-status');
            Route::post('/{partner}/set-pin', [PartnerController::class, 'setPin'])->name('set-pin');
            Route::post('/{partner}/reset-pin', [PartnerController::class, 'resetPin'])->name('reset-pin');
            Route::patch('/{partner}/logo', [PartnerController::class, 'updateLogo'])->name('update-logo');
            Route::delete('/{partner}/logo', [PartnerController::class, 'removeLogo'])->name('remove-logo');
            Route::get('/{partner}/transactions', [PartnerController::class, 'transactions'])->name('transactions');
            Route::post('/{partner}/verify-phone', [PartnerController::class, 'verifyPhone'])->name('verify-phone');
        });

        // Customer Queries Management
        Route::prefix('queries')->name('queries.')->group(function () {
            Route::get('/', [CustomerQueryController::class, 'index'])->name('index');
            Route::get('/{query}', [CustomerQueryController::class, 'show'])->name('show');
            Route::put('/{query}', [CustomerQueryController::class, 'update'])->name('update');
            Route::post('/{query}/reply', [CustomerQueryController::class, 'reply'])->name('reply');
            Route::delete('/{query}', [CustomerQueryController::class, 'destroy'])->name('destroy');
        });

        // Project Context Documentation - Redirect to standalone HTML
        Route::get('/context', function () {
            return redirect('/context.html');
        })->name('context');

        // Email Settings (Super Admin & Admin)
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/email', [EmailSettingController::class, 'index'])->name('email');
            Route::put('/email', [EmailSettingController::class, 'update'])->name('email.update');
            Route::post('/email/test', [EmailSettingController::class, 'test'])->name('email.test');

            // Withdrawal Settings
            Route::get('/withdrawals', [WithdrawalSettingsController::class, 'index'])->name('withdrawals');
            Route::post('/withdrawals', [WithdrawalSettingsController::class, 'update'])->name('withdrawals.update');

            // WhatsApp OTP Settings
            Route::get('/whatsapp', [WhatsAppSettingsController::class, 'index'])->name('whatsapp');
            Route::post('/whatsapp', [WhatsAppSettingsController::class, 'save'])->name('whatsapp.save');
            Route::post('/whatsapp/test', [WhatsAppSettingsController::class, 'testConnection'])->name('whatsapp.test');
        });

        // Withdrawal Management (Super Admin & Admin)
        Route::prefix('withdrawals')->name('withdrawals.')->group(function () {
            Route::get('/', [WithdrawalController::class, 'index'])->name('index');
            Route::get('/export', [WithdrawalController::class, 'export'])->name('export');
            Route::post('/bulk-approve', [WithdrawalController::class, 'bulkApprove'])->name('bulk-approve');
            Route::post('/bulk-reject', [WithdrawalController::class, 'bulkReject'])->name('bulk-reject');
            Route::get('/{id}', [WithdrawalController::class, 'show'])->name('show');
            Route::post('/{id}/approve', [WithdrawalController::class, 'approve'])->name('approve');
            Route::post('/{id}/reject', [WithdrawalController::class, 'reject'])->name('reject');
            Route::post('/{id}/processing', [WithdrawalController::class, 'markProcessing'])->name('processing');
        });

        // Commission Management (requires commissions.view permission)
        Route::middleware(['role.permission:commissions.view'])->prefix('commissions')->name('commissions.')->group(function () {
            // Dashboard (read-only)
            Route::get('/', [CommissionController::class, 'index'])->name('index');

            // Batches (read-only + calculation)
            Route::get('/batches', [CommissionController::class, 'batchIndex'])->middleware('role.permission:commissions.view_batches')->name('batches.index');
            Route::get('/batches/{id}', [CommissionController::class, 'batchShow'])->middleware('role.permission:commissions.view_batches')->name('batches.show');
            Route::post('/batches/{id}/notify-all', [CommissionController::class, 'notifyAllPartners'])->middleware('role.permission:commissions.send_notifications')->name('batches.notify-all');
            Route::post('/calculate', [CommissionController::class, 'triggerCalculation'])->middleware('role.permission:commissions.calculate')->name('calculate');

            // Partners (read-only)
            Route::get('/partners', [CommissionController::class, 'partnerIndex'])->middleware('role.permission:commissions.view_partners')->name('partners.index');
            Route::get('/partners/{partnerId}', [CommissionController::class, 'partnerShow'])->middleware('role.permission:commissions.view_partners')->name('partners.show');

            // Settlements (view + process)
            Route::get('/settlements/history', [CommissionController::class, 'settlementHistory'])->middleware('role.permission:commissions.view_settlements')->name('settlements.history');
            Route::get('/settlements/proof-gallery', [CommissionController::class, 'proofGallery'])->middleware('role.permission:commissions.view_proof')->name('settlements.proof-gallery');
            Route::get('/settlements/create/{ledgerId}', [CommissionController::class, 'settlementCreate'])->middleware('role.permission:commissions.process_settlement')->name('settlements.create');
            Route::post('/settlements/{ledgerId}', [CommissionController::class, 'settlementStore'])->middleware('role.permission:commissions.process_settlement')->name('settlements.store');
            Route::post('/settlements/bulk-settle', [CommissionController::class, 'bulkSettle'])->middleware('role.permission:commissions.bulk_settle')->name('settlements.bulk-settle');
            Route::post('/settlements/{id}/void', [CommissionController::class, 'voidSettlement'])->middleware('role.permission:commissions.void_settlement')->name('settlements.void');

            // Adjustments (view + create)
            Route::get('/adjustments', [CommissionController::class, 'adjustmentIndex'])->middleware('role.permission:commissions.view_adjustments')->name('adjustments.index');
            Route::get('/adjustments/create/{ledgerId}', [CommissionController::class, 'adjustmentCreate'])->middleware('role.permission:commissions.create_adjustment')->name('adjustments.create');
            Route::post('/adjustments/{ledgerId}', [CommissionController::class, 'adjustmentStore'])->middleware('role.permission:commissions.create_adjustment')->name('adjustments.store');

            // Invoice (read-only, uses view permission)
            Route::get('/invoice/{ledgerId}', [CommissionController::class, 'downloadInvoice'])->name('invoice.download');

            // Exports (all require export permission)
            Route::middleware(['role.permission:commissions.export'])->group(function () {
                Route::get('/export/batches', [CommissionController::class, 'exportBatches'])->name('export.batches');
                Route::get('/export/ledgers', [CommissionController::class, 'exportLedgers'])->name('export.ledgers');
                Route::get('/export/settlements', [CommissionController::class, 'exportSettlements'])->name('export.settlements');
                Route::get('/export/partner/{partnerId}', [CommissionController::class, 'exportPartnerReport'])->name('export.partner');
            });
        });

        // Roles & Permissions Management (Super Admin & Manager only)
        Route::middleware(['role.permission:roles.view'])->group(function () {
            Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
            Route::get('roles-search', [RoleController::class, 'search'])->name('roles.search');
            Route::get('roles/{role}', [RoleController::class, 'show'])->name('roles.show');

            // Creating and editing roles (requires create/edit permissions)
            Route::middleware(['role.permission:roles.create'])->group(function () {
                Route::get('roles/create', [RoleController::class, 'create'])->name('roles.create');
                Route::post('roles', [RoleController::class, 'store'])->name('roles.store');
                Route::post('roles/{role}/duplicate', [RoleController::class, 'duplicate'])->name('roles.duplicate');
            });

            Route::middleware(['role.permission:roles.edit'])->group(function () {
                Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
                Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update');
                Route::patch('roles/{role}/toggle-status', [RoleController::class, 'toggleStatus'])->name('roles.toggle-status');
            });

            Route::middleware(['role.permission:roles.delete'])->group(function () {
                Route::delete('roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
            });
        });

        // Permissions Management (Super Admin only - view permissions and assign to users)
        Route::middleware(['role.permission:roles.view'])->group(function () {
            Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index');
            Route::get('permissions/{permission}', [PermissionController::class, 'show'])->name('permissions.show');

            // User permission management (assign custom permissions to individual users)
            Route::middleware(['role.permission:users.manage_permissions'])->group(function () {
                Route::get('users/{user}/permissions', [PermissionController::class, 'getUserPermissions'])->name('users.permissions.get');
                Route::post('users/{user}/permissions/sync', [PermissionController::class, 'syncUserPermissions'])->name('users.permissions.sync');
                Route::post('permissions/grant-to-user', [PermissionController::class, 'grantToUser'])->name('permissions.grant-to-user');
                Route::post('permissions/revoke-from-user', [PermissionController::class, 'revokeFromUser'])->name('permissions.revoke-from-user');
            });
        });

        // Settings (Super Admin only)
        Route::middleware(['role.permission:manage_settings'])->group(function () {
            Route::get('settings', [DashboardController::class, 'settings'])->name('settings');
            Route::get('settings/admin', [DashboardController::class, 'adminSettings'])->name('settings.admin');

            // Active Criteria Management
            Route::post('settings/admin/criteria', [DashboardController::class, 'updateActiveCriteria'])->name('settings.admin.criteria.update');

            // Social Media Links Management
            Route::post('social-media', [DashboardController::class, 'storeSocialMedia'])->name('social-media.store');
            Route::put('social-media/{socialMedia}', [DashboardController::class, 'updateSocialMedia'])->name('social-media.update');
            Route::delete('social-media/{socialMedia}', [DashboardController::class, 'destroySocialMedia'])->name('social-media.destroy');

            // Firebase Configuration Management
            Route::post('firebase-config', [DashboardController::class, 'updateFirebaseConfig'])->name('firebase-config.update');
            Route::get('firebase-config/test', [DashboardController::class, 'testFirebaseConnection'])->name('firebase-config.test');
        });
    });
});