<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\VerifyCsrfToken;  // ✅ حتماً همین

Route::prefix('auth')->name('auth.')->group(function () {

    // ✅ گرفتن CSRF برای fetch (بدون auth)
    Route::get('/csrf', function () {
        return response()->json(['csrf' => csrf_token()]);
    })->name('csrf');

    Route::post('/send-otp', [AuthController::class, 'sendOtp'])
        ->middleware(['throttle:5,1'])
        ->name('sendOtp');

    Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])
        ->middleware(['throttle:10,1'])
        ->name('verifyOtp');

    // ✅ انتخاب نقش فقط بعد از لاگین
    Route::post('/set-role', [AuthController::class, 'setRole'])
        ->middleware('auth')
        ->name('setRole');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::get('/dashboard/role-select', function () {
    return view('dashboard.role-select');
})->middleware('auth')->name('role.select');

Route::post('/dashboard/profile/change-role', [AuthController::class, 'changeRole'])
    ->middleware('auth')
    ->name('profile.change-role');
