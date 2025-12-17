<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\AI\ExamAIController; // 👈 اضافه شد
use App\Http\Controllers\DevController;





Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/privacy-policy', function () {
    return view('privacy-policy');
})->name('privacy');

Route::get('/terms-of-service', function () {
    return view('terms-of-service');
})->name('terms');

require __DIR__.'/auth.php';

require __DIR__.'/student.php';
require __DIR__.'/teacher.php';
require __DIR__.'/admin.php';
require __DIR__.'/counselor.php';

// ⭐⭐⭐ مسیر AI — همین‌جا باید باشد ⭐⭐⭐
Route::prefix('ai')->middleware('auth')->group(function () {
    Route::post('/exam/suggest', [ExamAIController::class, 'suggest'])
        ->name('ai.exam.suggest');
});
// ⭐⭐⭐ پایان مسیر AI ⭐⭐⭐

require __DIR__.'/diagnosis.php';



// 🔧🔧🔧 کنسول توسعه (اضافه کنید اینجا) 🔧🔧🔧
// 🔧 کنسول توسعه حرفه‌ای
if (app()->environment('local')) {
    Route::prefix('dev')->middleware(['auth'])->group(function () {
        Route::get('/console', [DevController::class, 'index'])->name('dev.console');
        Route::post('/run-command', [DevController::class, 'runCommand'])->name('dev.run.command');
        Route::get('/exams-list', [DevController::class, 'getExamsList'])->name('dev.exams.list');
        Route::get('/download-logs', [DevController::class, 'downloadLogs'])->name('dev.download.logs');
    });
}
// 🔧🔧🔧 پایان کنسول توسعه 🔧🔧🔧


Route::fallback(function () {
    return redirect()->route('landing');
    
});
