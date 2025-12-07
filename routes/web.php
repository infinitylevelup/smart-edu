<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\OTPController;
// ✅ اضافه شود (بالای require ها)
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

// Landing Page
Route::get('/', [LandingController::class, 'index'])->name('landing');
// Fallback Route
Route::fallback(function () {
    return redirect()->route('landing');
});
// Route برای صفحه حریم خصوصی
Route::get('/privacy-policy', function () {
    return view('privacy-policy');
})->name('privacy');

// Route برای صفحه شرایط استفاده
Route::get('/terms-of-service', function () {
    return view('terms-of-service');
})->name('terms');



// Authentication Routes
require __DIR__.'/auth.php';
// Dashboard Routes
require __DIR__.'/student.php';
// Teacher Routes
require __DIR__.'/teacher.php';
// Admin Routes
require __DIR__.'/admin.php';
// Counselor Routes
require __DIR__.'/counselor.php';
