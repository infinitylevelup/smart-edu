<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use App\Http\Middleware\RoleSelectedMiddleware;
use App\Http\Middleware\RoleMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // ✅ مهم: مقصد مهمان‌ها (guest) وقتی auth رد می‌شود
        $middleware->redirectGuestsTo('/admin/login');

        // ✅ OTP بدون CSRF
        $middleware->validateCsrfTokens(except: [
            'auth/send-otp',
            'auth/verify-otp',
            // اگر ادمین OTP هم آدرس جدا دارد:
            'admin/send-otp',
            'admin/verify-otp',
        ]);

        // middleware alias های نقش
        $middleware->alias([
            'role.selected' => RoleSelectedMiddleware::class,
            'role'          => RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
