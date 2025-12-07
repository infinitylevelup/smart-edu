<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureSingleAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        // اگر لاگین نیست
        if (!$user) {
            abort(403, 'شما دسترسی ندارید.');
        }

        /**
         * ✅ فقط یک اکانت خاص اجازه ورود دارد
         * یکی از این روش‌ها را انتخاب کن (بقیه را پاک کن)
         */

        // روش 1: محدود بر اساس UUID/ID ادمین
        $ADMIN_ID = 'PUT_ADMIN_UUID_HERE';
        if ((string)$user->id !== $ADMIN_ID) {
            abort(403, 'شما ادمین نیستید.');
        }

        // روش 2: محدود بر اساس ایمیل
        // if ($user->email !== 'admin@example.com') {
        //     abort(403, 'شما ادمین نیستید.');
        // }

        // روش 3: محدود بر اساس موبایل
        // if ($user->phone !== '0911xxxxxxx') {
        //     abort(403, 'شما ادمین نیستید.');
        // }

        return $next($request);
    }
}
