<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Services\OTPService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminAuthController extends Controller
{
    public function __construct(private OTPService $otpService)
    {
    }

    public function showLogin()
    {
        return view('dashboard.admin.login');
    }

    private function normalizePhone(string $phone): string
    {
        $phone = trim($phone);
        $phone = preg_replace('/\s+/', '', $phone);
        $phone = preg_replace('/^(\+98|98)/', '', $phone);

        if (str_starts_with($phone, '0')) {
            $phone = substr($phone, 1);
        }

        return $phone;
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|regex:/^9\d{9}$/',
        ]);

        $phone = $this->normalizePhone($request->phone);

        $user = User::where('phone', $phone)->first();
        if (!$user || ($user->status ?? null) !== 'admin') {
            return response()->json(['message' => 'این شماره ادمین نیست.'], 403);
        }

        $this->otpService->send($phone);

        return response()->json([
            'status'  => 'ok',
            'message' => 'کد ورود ادمین ارسال شد.'
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|regex:/^9\d{9}$/',
            'code'  => 'required|digits:6',
        ]);

        $phone = $this->normalizePhone($request->phone);

        $user = User::where('phone', $phone)->first();
        if (!$user || ($user->status ?? null) !== 'admin') {
            return response()->json(['message' => 'این شماره ادمین نیست.'], 403);
        }

        if (!$this->otpService->verify($phone, $request->code)) {
            return response()->json(['message' => 'کد معتبر نیست.'], 422);
        }

        Auth::login($user);
        $request->session()->regenerate();
        $request->session()->regenerateToken();

        // ✅ 1) نقش admin را بساز یا بگیر
        $adminRole = Role::firstOrCreate(
            ['slug' => 'admin'],
            [
                'id'        => (string) Str::uuid(),
                'name'      => 'ادمین',
                'is_active' => true,
            ]
        );

        // ✅ 2) نقش admin را در pivot وصل کن (تک‌نقشی)
        $user->roles()->sync([$adminRole->id]);

        // ✅ 3) نقش انتخابی برای سایدبار/ورود را هم ست کن
        if (($user->selected_role ?? null) !== 'admin') {
            $user->selected_role = 'admin';
            $user->save();
        }

        return response()->json([
            'status'   => 'ok',
            'message'  => 'ورود ادمین موفق بود.',
            'redirect' => route('admin.dashboard'),
            'csrf'     => csrf_token(),
        ]);
    }
}
