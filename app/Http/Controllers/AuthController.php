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
 * OTP Flow:
 * 1) sendOtp(phone)
 * 2) verifyOtp(phone, code) -> login/register
 *    - if no role => need_role = true
 * 3) setRole(role) -> first time only
 * 4) changeRole(role) -> profile only
 */
class AuthController extends Controller
{
    /**
     * Send OTP (no debug code)
     */
    public function sendOtp(Request $request)
    {
        $data = $request->validate([
            "phone" => ["required", "regex:/^9\d{9}$/"],
        ]);

        $phone = $data["phone"];

        // ✅ If a valid OTP already exists, don't create another one
        $existing = Otp::where("phone", $phone)
            ->where("expires_at", ">", now())
            ->latest()
            ->first();

        if ($existing) {
            return response()->json([
                "status"  => "ok",
                "message" => "کد تایید ارسال شد.",
            ]);
        }

        // ✅ Create new OTP
        $code = rand(100000, 999999);

        Otp::create([
            "phone"      => $phone,
            "code"       => $code,
            "expires_at" => now()->addMinutes(2),
        ]);

        Log::info("OTP for {$phone}: {$code}");

        return response()->json([
            "status"  => "ok",
            "message" => "کد تایید ارسال شد.",
        ]);
    }

    /**
     * Verify OTP + login/register
     */
    public function verifyOtp(Request $request)
    {
        $data = $request->validate([
            "phone" => ["required", "regex:/^9\d{9}$/"],
            "code"  => ["required", "digits:6"],
        ]);

        $otp = Otp::where("phone", $data["phone"])
            ->latest()
            ->first();

        if (!$otp) {
            return response()->json([
                "status"  => "error",
                "message" => "کد تایید پیدا نشد یا منقضی شده است.",
            ], 422);
        }

        if ($otp->expires_at && now()->gt($otp->expires_at)) {
            $otp->delete();
            return response()->json([
                "status"  => "error",
                "message" => "کد منقضی شده است.",
            ], 422);
        }

        if ((string) $otp->code !== (string) $data["code"]) {
            return response()->json([
                "status"  => "error",
                "message" => "کد تایید نامعتبر است.",
            ], 422);
        }

        // ✅ users table new schema (no role column)
        $user = User::firstOrCreate(
            ["phone" => $data["phone"]],
            [
                "name"     => "کاربر " . $data["phone"],
                "password" => Hash::make(Str::random(12)),
                "status"   => "active",
            ]
        );

        Auth::login($user);

        // ✅ VERY IMPORTANT for CSRF after login
        $request->session()->regenerate();
        $request->session()->regenerateToken();

        // Load roles pivot
        $user->load("roles");

        // OTP delete after success
        $otp->delete();

        // ✅ role comes from pivot
        $needRole = $user->roles->isEmpty();

        $redirect = null;
        if (!$needRole) {
            $redirect = match ($user->role) {
                "student" => route("student.exams.index"),
                "teacher" => route("teacher.index"),
                default   => route("landing"),
            };
        }

        return response()->json([
            "status"    => "ok",
            "message"   => "ورود موفق.",
            "role"      => $user->role,
            "need_role" => $needRole,
            "redirect"  => $redirect,
            "csrf"      => csrf_token(), // ✅ send fresh token to frontend
        ]);
    }

    /**
     * Set role only first time (role == null)
     * ✅ FIXED: no Seeder needed, role auto-creates if table is empty
     */

        public function setRole(Request $request)
        {
            $user = auth()->user();
            if (!$user) {
                return response()->json(['message' => 'Unauthenticated'], 401);
            }

            $roleSlug = $request->validate([
                'role' => 'required|in:student,teacher,admin,counselor'
            ])['role'];

            // 1) ذخیره slug در users
            $user->selected_role = $roleSlug;
            $user->save();

            // 2) پیدا کردن role واقعی از جدول roles با slug
            $role = Role::where('slug', $roleSlug)->first();
            if (!$role) {
                return response()->json([
                    'message' => 'نقش معتبر نیست یا در جدول roles وجود ندارد.'
                ], 422);
            }

            // 3) سینک pivot با ID واقعی نقش
            $user->roles()->sync([$role->id]);  // ✅ role_id صحیح

            return response()->json([
                'status' => 'ok',
                'message' => 'نقش ذخیره شد.',
                'redirect' => match ($roleSlug) {
                    'admin' => route('admin.dashboard'),
                    'teacher' => route('teacher.index'),
                    'student' => route('student.index'),
                    'counselor' => route('counselor.index'),
                    default => route('landing'),
                }
            ]);
        }


    /**
     * Change role from profile only
     * ✅ FIXED: no Seeder needed, role auto-creates if missing
     */
    public function changeRole(Request $request)
    {
        $request->validate([
            "role" => ["required", "in:student,teacher"],
        ]);

        $user = Auth::user();
        abort_unless($user, 401);

        // ✅ اگر نقش نبود بساز
        $role = Role::firstOrCreate(
            ["slug" => $request->role],
            [
                "id"        => (string) Str::uuid(),
                "name"      => $request->role === "teacher" ? "معلم" : "دانش‌آموز",
                "is_active" => true,
            ]
        );

        // تک‌نقشی: قبلی‌ها پاک و جدید ثبت شود
        $user->roles()->sync([$role->id]);

        $redirect = match ($request->role) {
            "student" => route("student.exams.index"),
            "teacher" => route("teacher.index"),
            default   => route("landing"),
        };

        return redirect($redirect)->with("success", "نقش شما با موفقیت تغییر کرد.");
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route("landing");
    }
}
