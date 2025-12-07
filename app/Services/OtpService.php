<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class OTPService
{
    public function __construct(private OtpSender $sender)
    {
    }

    public function send(string $phone): string
    {
        $code = (string) random_int(100000, 999999);

        Cache::put($this->cacheKey($phone), $code, now()->addMinutes(2));

        $this->sender->send($phone, $code);

        return $code;
    }

    public function verify(string $phone, string $code): bool
    {
        $key = $this->cacheKey($phone);
        $cached = Cache::get($key);

        if (!$cached) return false;
        if ($cached !== $code) return false;

        Cache::forget($key);
        return true;
    }

    private function cacheKey(string $phone): string
    {
        return "otp:{$phone}";
    }
}
