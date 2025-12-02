<?php

namespace App\Services;

use Kavenegar\Laravel\Facade\Kavenegar;
use Illuminate\Support\Facades\Log;

class OtpService
{
    public function send(string $phone, string $code): void
    {
        // اگر در حالت تست هستیم، فقط لاگ کن
        if (config('otp.debug')) {
            Log::info("OTP DEBUG | phone={$phone} code={$code}");
            return;
        }

        // ارسال واقعی با کاوه‌نگار
        // متن پیامک:
        $message = "کد ورود شما به سامانه Smart Edu: {$code}";

        Kavenegar::send(
            config('otp.sender'),
            $phone,
            $message
        );
    }
}
