<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::prefix('auth')->name('auth.')->group(function () {
    Route::post('/send-otp', [AuthController::class, 'sendOtp'])->name('sendOtp');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verifyOtp');

    Route::post('/set-role', [AuthController::class, 'setRole'])
        ->middleware('auth')
        ->name('setRole');

    Route::get('/csrf', function () {
        return response()->json(['token' => csrf_token()]);
    })->middleware('auth')->name('csrf');
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
