<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Services\OTPService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function __construct(private OTPService $otpService) {}

    public function sendOtp(Request $request)
    {
        $data = $request->validate([
            'phone' => ['required', "regex:/^9\d{9}$/"],
        ]);

        $phone = $data['phone'];

        $code = $this->otpService->send($phone);

        Log::info("OTP for {$phone}: {$code}");

        return response()->json([
            'status' => 'ok',
            'message' => 'کد تایید ارسال شد.',
        ]);
    }

    /**
     * ✅ NEW: verifyOtp now accepts role and saves it (users.selected_role + role_user pivot)
     */
    public function verifyOtp(Request $request)
    {
        $data = $request->validate([
            'phone' => ['required', "regex:/^9\d{9}$/"],
            'code' => ['required', 'digits:6'],
            // ✅ نقش از دکمه لندینگ میاد
            'role' => ['required', 'in:student,teacher'],
        ]);

        $phone = $data['phone'];
        $code = $data['code'];
        $roleSlug = $data['role']; // student | teacher

        if (! $this->otpService->verify($phone, $code)) {
            return response()->json([
                'status' => 'error',
                'message' => 'کد تایید نامعتبر است یا منقضی شده است.',
            ], 422);
        }

        // ✅ create/login user
        $user = User::firstOrCreate(
            ['phone' => $phone],
            [
                'name' => 'کاربر '.$phone,
                'password' => Hash::make(Str::random(12)),
                'status' => 'active',
            ]
        );

        Auth::login($user);

        // ✅ VERY IMPORTANT for CSRF after login
        $request->session()->regenerate();
        $request->session()->regenerateToken();

        /**
         * ✅ Save role in BOTH places:
         * 1) users.selected_role
         * 2) role_user pivot
         */
        $role = Role::firstOrCreate(
            ['slug' => $roleSlug],
            [
                'id' => (string) Str::uuid(),
                'name' => $roleSlug === 'teacher' ? 'معلم' : 'دانش‌آموز',
                'is_active' => true,
            ]
        );

        // selected_role column exists in migration :contentReference[oaicite:3]{index=3}
        $user->selected_role = $roleSlug;
        $user->save();

        // pivot sync (single-role)
        $user->roles()->sync([$role->id]);

        // refresh roles
        $user->load('roles');

        $redirect = match ($roleSlug) {
            'student' => route('student.index'),
            'teacher' => route('teacher.index'),
            default => route('landing'),
        };

        return response()->json([
            'status' => 'ok',
            'message' => 'ورود موفق.',
            'role' => $roleSlug,
            'redirect' => $redirect,
            'csrf' => csrf_token(), // ✅ fresh token to frontend
        ]);
    }

    // ⚠️ setRole / changeRole / logout can stay as-is (you can delete setRole later if you want)
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing');
    }
}
