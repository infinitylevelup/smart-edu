<?php

namespace App\Http\Controllers;

use App\Models\Otp;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * AuthController
 *
 * احراز هویت پروژه Smart-Edu بر پایه OTP انجام می‌شود.
 *
 * Flow:
 * 1) sendOtp(phone) -> کد ارسال می‌شود (idempotent)
 * 2) verifyOtp(phone, code) -> login/register
 *    - اگر role نداشت => need_role = true
 *    - اگر role داشت => redirect مناسب
 * 3) setRole(role) -> فقط برای اولین انتخاب نقش
 * 4) changeRole(role) -> فقط از پروفایل
 */
class AuthController extends Controller
{
    /**
     * ارسال OTP به شماره موبایل (نسخه امن بدون debug_code)
     */
        public function sendOtp(Request $request)
        {
            $data = $request->validate([
                'phone' => ['required', 'regex:/^9\d{9}$/'],
            ]);

            $phone = $data['phone'];

            // ✅ اگر OTP معتبر هست، کد جدید نساز و لاگ هم نزن
            $existing = Otp::where('phone', $phone)
                ->where('expires_at', '>', now())
                ->latest()
                ->first();

            if ($existing) {
                return response()->json([
                    'status'  => 'ok',
                    'message' => 'کد تایید ارسال شد.',
                ]);
            }

            // ✅ تولید کد جدید فقط وقتی قبلی معتبر نیست
            $code = rand(100000, 999999);

            Otp::create([
                'phone'      => $phone,
                'code'       => $code,
                'expires_at' => now()->addMinutes(2),
            ]);

            // ✅ لاگ فقط یکبار، فقط برای کد جدید
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

        // ✅ آخرین OTP همین شماره را بگیر
        $otp = Otp::where('phone', $data['phone'])
            ->latest()
            ->first();

        if (!$otp) {
            return response()->json([
                'status'  => 'error',
                'message' => 'کد تایید پیدا نشد یا منقضی شده است.',
            ], 422);
        }

        if ($otp->expires_at && now()->gt($otp->expires_at)) {
            $otp->delete();
            return response()->json([
                'status'  => 'error',
                'message' => 'کد منقضی شده است.',
            ], 422);
        }

        if ((string)$otp->code !== (string)$data['code']) {
            return response()->json([
                'status'  => 'error',
                'message' => 'کد تایید نامعتبر است.',
            ], 422);
        }

        // ✅ مطابق DB جدید users: بدون role / is_active
        $user = User::firstOrCreate(
            ['phone' => $data['phone']],
            [
                'name'     => 'کاربر ' . $data['phone'],
                'password' => Hash::make(Str::random(12)), // فقط برای NOT NULL
                'status'   => 'active',
            ]
        );

        Auth::login($user);

        // اگر ستون last_login_at در DB وجود ندارد، خطا نده
        try {
            $user->update(['last_login_at' => now()]);
        } catch (\Throwable $e) {}

        // OTP بعد از موفقیت حذف شود
        $otp->delete();

        $needRole = is_null($user->role); // role مجازی از pivot خوانده می‌شود

        $redirect = null;
        if (!$needRole) {
            $redirect = match ($user->role) {
                'student'   => route('student.exams.index'),
                'teacher'   => route('teacher.index'),
                'counselor' => route('counselor.index'),
                'admin'     => route('landing'),
                default     => route('landing'),
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

        if (!is_null($user->role)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'نقش قبلاً انتخاب شده و فقط از پروفایل قابل تغییر است.',
            ], 403);
        }

        // ✅ نقش از roles.slug پیدا می‌شود و در pivot ثبت می‌شود
        $role = Role::where('slug', $request->role)->firstOrFail();
        $user->roles()->attach($role->id);

        $redirect = match ($request->role) {
            'student' => route('student.exams.index'),
            'teacher' => route('teacher.index'),
            default   => route('landing'),
        };

        return response()->json([
            'status'   => 'ok',
            'message'  => 'نقش ذخیره شد.',
            'role'     => $request->role,
            'redirect' => $redirect,
        ]);
    }

    /**
     * تغییر نقش فقط از پروفایل
     */
    public function changeRole(Request $request)
    {
        $request->validate([
            'role' => ['required', 'in:student,teacher'],
        ]);

        $user = Auth::user();
        abort_unless($user, 401);

        $role = Role::where('slug', $request->role)->firstOrFail();

        // ✅ تک‌نقشی: نقش قبلی پاک و جدید ثبت می‌شود
        $user->roles()->detach();
        $user->roles()->attach($role->id);

        $redirect = match ($request->role) {
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
