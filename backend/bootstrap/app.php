<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin.auth' => \App\Http\Middleware\AdminAuth::class,
            'role.permission' => \App\Http\Middleware\RolePermission::class,
            'customer.role' => \App\Http\Middleware\EnsureCustomerRole::class,
            'partner' => \App\Http\Middleware\EnsurePartnerRole::class,
            'check.blocked' => \App\Http\Middleware\CheckBlockedIp::class,
        ]);

        // Exclude customer auth API routes from CSRF verification
        // These routes use Firebase phone auth and need to set web sessions
        $middleware->validateCsrfTokens(except: [
            'api/customer/auth/*'
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle CSRF token mismatch (419 Page Expired)
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, Request $request) {
            // If it's an admin login request, redirect to admin login
            if ($request->is('admin/login') || $request->is('admin/*')) {
                return redirect()->route('admin.login')
                    ->withErrors(['session' => 'Your session has expired. Please login again.']);
            }

            // For other routes, redirect to home or respective login
            return redirect('/login')
                ->withErrors(['session' => 'Your session has expired. Please login again.']);
        });
    })->create();
