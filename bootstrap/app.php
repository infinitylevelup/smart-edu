<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use App\Http\Middleware\EnsureRoleSelected;
use App\Http\Middleware\EnsureUserRole;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // مقصد مهمان‌ها
        $middleware->redirectGuestsTo('/admin/login');

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
