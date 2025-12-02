<?php

namespace App\Http\Controllers;

use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * AuthController
 *
 * احراز هویت پروژه Smart-Edu بر پایه OTP انجام می‌شود.
 *
 * Flow (Updated):
 * 1) sendOtp(phone) -> کد ارسال می‌شود
 * 2) verifyOtp(phone, code) -> login/register
 *    - اگر role نداشت => need_role = true
 *    - اگر role داشت => redirect مناسب
 * 3) setRole(role) -> فقط برای اولین انتخاب نقش
 * 4) changeRole(role) -> فقط از پروفایل
 */
class AuthController extends Controller
{
    /**
     * ارسال OTP به شماره موبایل
     */
    public function sendOtp(Request $request)
    {
        $data = $request->validate([
            'phone' => ['required', 'regex:/^9\d{9}$/'], // 9xxxxxxxxx بدون صفر
        ]);

        $phone = $data['phone'];

        // حذف OTP‌های قبلی
        Otp::where('phone', $phone)->delete();

        // تولید کد 6 رقمی
        $code = rand(100000, 999999);

        // ذخیره OTP
        Otp::create([
            'phone'      => $phone,
            'code'       => $code,
            'expires_at' => now()->addMinutes(2),
        ]);

        // ✅ فعلاً برای تست، کد رو لاگ می‌کنیم
        Log::info("OTP for {$phone}: {$code}");

        return response()->json([
            'status'  => 'ok',
            'message' => 'کد تایید ارسال شد.',
        ]);
    }

    /**
     * تایید OTP و ورود/ثبت‌نام کاربر
     */
    public function verifyOtp(Request $request)
    {
        $data = $request->validate([
            'phone' => ['required', 'regex:/^9\d{9}$/'],
            'code'  => ['required', 'digits:6'],
        ]);

        $otp = Otp::where('phone', $data['phone'])
            ->where('code', $data['code'])
            ->first();

        if (!$otp) {
            return response()->json([
                'status'  => 'error',
                'message' => 'کد تایید نامعتبر است.',
            ], 422);
        }

        if ($otp->expires_at && now()->gt($otp->expires_at)) {
            $otp->delete();
            return response()->json([
                'status'  => 'error',
                'message' => 'کد منقضی شده است.',
            ], 422);
        }

        // پیدا/ساخت کاربر
        $user = User::firstOrCreate(
            ['phone' => $data['phone']],
            [
                'role' => null,
                'is_active' => 1
            ]
        );

        // لاگین
        Auth::login($user);

        // آخرین ورود
        $user->update(['last_login_at' => now()]);

        // پاک کردن OTP بعد از استفاده
        $otp->delete();

        /**
         * ✅ منطق جدید:
         * اگر role نداشت => فرانت مرحله انتخاب نقش را نشان می‌دهد
         * اگر role داشت => فرانت مستقیم redirect می‌کند
         */
        $needRole = is_null($user->role);

        $redirect = null;
        if (!$needRole) {
            $redirect = match ($user->role) {
                'student' => route('student.exams.index'),
                'teacher' => route('teacher.index'),
                'admin'   => route('landing'), // فعلاً ادمین نداریم
                default   => route('landing'),
            };
        }

        return response()->json([
            'status'    => 'ok',
            'message'   => 'ورود موفق.',
            'role'      => $user->role,
            'need_role' => $needRole,
            'redirect'  => $redirect,
        ]);
    }

    /**
     * انتخاب نقش فقط برای اولین ورود (role == null)
     */
    public function setRole(Request $request)
    {
        $request->validate([
            'role' => ['required', 'in:student,teacher'],
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'کاربر لاگین نیست'], 401);
        }

        // ✅ قفل: اگر قبلاً نقش دارد، دیگر از اینجا قابل تغییر نیست
        if (!is_null($user->role)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'نقش قبلاً انتخاب شده و فقط از پروفایل قابل تغییر است.',
            ], 403);
        }

        // ذخیره نقش + زمان انتخاب نقش + فعال‌سازی
        $user->update([
            'role'             => $request->role,
            'role_selected_at' => now(),
            'is_active'        => 1,
        ]);

        $redirect = match ($user->role) {
            'student' => route('student.exams.index'),
            'teacher' => route('teacher.index'),
            default   => route('landing'),
        };

        return response()->json([
            'status'   => 'ok',
            'message'  => 'نقش ذخیره شد.',
            'role'     => $user->role,
            'redirect' => $redirect,
        ]);
    }

    /**
     * تغییر نقش فقط از پروفایل
     * Route: POST /dashboard/profile/change-role
     */
    public function changeRole(Request $request)
    {
        $request->validate([
            'role' => ['required', 'in:student,teacher'],
        ]);

        $user = Auth::user();
        abort_unless($user, 401);

        // ✅ اینجا اجازه تغییر می‌دهیم چون مسیر پروفایل است
        $user->update([
            'role'             => $request->role,
            'role_selected_at' => $user->role_selected_at ?? now(),
        ]);

        $redirect = match ($user->role) {
            'student' => route('student.exams.index'),
            'teacher' => route('teacher.index'),
            default   => route('landing'),
        };

        return redirect($redirect)->with('success', 'نقش شما با موفقیت تغییر کرد.');
    }

    /**
     * خروج از حساب
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing');
    }
}
