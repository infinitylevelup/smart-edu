<?php

namespace App\Services;

use Kavenegar\Laravel\Facade\Kavenegar;
use Illuminate\Support\Facades\Log;

class OtpSender
{
    /**
     * ارسال پیامک OTP
     */
    public function send(string $phone, string $code): void
    {
        if (config('otp.debug')) {
            Log::info("OTP DEBUG | phone={$phone} code={$code}");
            return;
        }

        $message = "کد ورود شما به سامانه Smart Edu: {$code}";

        Kavenegar::send(
            config('otp.sender'),
            $phone,
            $message
        );
    }
}
