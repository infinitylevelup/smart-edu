<?php

use App\Http\Controllers\AI\ExamAIController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DevController; // 👈 اضافه شد
use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;

// ============================================================
// 🌐 مسیرهای عمومی (بدون احراز هویت)
// ============================================================

// صفحه اصلی لندینگ
Route::get('/', [LandingController::class, 'index'])->name('landing');

// صفحه ورود
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// صفحه حریم خصوصی
Route::get('/privacy-policy', function () {
    return view('privacy-policy');
})->name('privacy');

// صفحه شرایط استفاده
Route::get('/terms-of-service', function () {
    return view('terms-of-service');
})->name('terms');

// ============================================================
// 📚 بارگذاری فایل‌های مسیر جداگانه
// ============================================================

// احراز هویت (auth.php)
require __DIR__.'/auth.php';

// پنل دانش‌آموز
require __DIR__.'/student.php';

// پنل معلم (مسیرهای اصلی معلم اینجا بارگذاری می‌شوند)
require __DIR__.'/teacher.php';

// پنل مدیر
require __DIR__.'/admin.php';

// پنل مشاور
require __DIR__.'/counselor.php';
//
require __DIR__.'/parent.php';

// ============================================================
// 🤖 مسیرهای هوش مصنوعی (AI)
// ============================================================

Route::prefix('ai')->middleware('auth')->group(function () {
    // پیشنهاد سوالات آزمون با AI
    Route::post('/exam/suggest', [ExamAIController::class, 'suggest'])
        ->name('ai.exam.suggest');
});

// ============================================================
// 🩺 مسیرهای تشخیص (Diagnosis)
// ============================================================

require __DIR__.'/diagnosis.php';

// ============================================================
// 🔧 کنسول توسعه (فقط در محیط local)
// ============================================================

if (app()->environment('local')) {
    Route::prefix('dev')->middleware(['auth'])->group(function () {
        // صفحه اصلی کنسول توسعه
        Route::get('/console', [DevController::class, 'index'])->name('dev.console');

        // اجرای دستورات
        Route::post('/run-command', [DevController::class, 'runCommand'])->name('dev.run.command');

        // لیست آزمون‌ها
        Route::get('/exams-list', [DevController::class, 'getExamsList'])->name('dev.exams.list');

        // دانلود فایل‌های لاگ
        Route::get('/download-logs', [DevController::class, 'downloadLogs'])->name('dev.download.logs');
    });
}

// ============================================================
// ⚡ مسیر فال‌بک (برای صفحات 404)
// ============================================================

Route::fallback(function () {
    return redirect()->route('landing');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');
