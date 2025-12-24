<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // مقصد مهمان‌ها
        $middleware->redirectGuestsTo(function ($request) {
            // فقط مسیرهای ادمین → admin login
            if ($request->is('admin*') || $request->is('dashboard/admin*')) {
                return route('admin.login'); // یا '/admin/login'
            }

            // student / teacher / parent / counselor → login عمومی
            return route('login'); // /login
        });

        // OTP بدون CSRF
        $middleware->validateCsrfTokens(except: [
            'auth/send-otp',
            'auth/verify-otp',
            'admin/send-otp',
            'admin/verify-otp',
        ]);

        // ✅ alias های نقش (هم‌راستا با فایل‌های موجودت)
        $middleware->alias([
            'role.selected' => \App\Http\Middleware\EnsureRoleSelected::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
