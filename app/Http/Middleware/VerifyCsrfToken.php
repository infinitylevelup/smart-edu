<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    protected $except = [
        // ✅ OTP برای مهمان‌ها نباید CSRF بخواد
        'auth/send-otp',
        'auth/verify-otp',
    ];
}
