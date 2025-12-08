<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;

// Landing Page
Route::get('/', [LandingController::class, 'index'])->name('landing');

// صفحه لاگین عمومی (اگر می‌خواهی جدا باشد)
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

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
require __DIR__.'/teacher.php';
require __DIR__.'/admin.php';
require __DIR__.'/counselor.php';

// Fallback Route
Route::fallback(function () {
    return redirect()->route('landing');
});
