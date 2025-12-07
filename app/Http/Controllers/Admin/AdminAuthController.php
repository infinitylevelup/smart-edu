<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OTPService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function __construct(private OTPService $otpService)
    {
    }

    public function showLogin()
    {
        return view('admin.login');
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

        Auth::login($user, false);
        // یا کوتاه‌تر:
        Auth::login($user);

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
