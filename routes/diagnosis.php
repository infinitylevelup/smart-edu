<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Diagnosis\DiagnosisController;
use App\Http\Controllers\Diagnosis\MergeController;

Route::prefix('diagnosis')
    ->name('diagnosis.')
    ->middleware(['web'])
    ->group(function () {
        // روت‌های اصلی
        Route::get('/', [DiagnosisController::class, 'dashboard'])->name('dashboard');
        Route::get('/structure', [DiagnosisController::class, 'structure'])->name('structure');
        Route::post('/structure', [DiagnosisController::class, 'mergeManual'])->name('structure.mergeManual');
        Route::get('/structure/app', [DiagnosisController::class, 'appTree'])->name('structure.appTree');
        Route::get('/file', [DiagnosisController::class, 'viewFile'])->name('file');

        // روت‌های Merge
        Route::get('/merge', [MergeController::class, 'index'])->name('merge');
        Route::get('/merge/search', [MergeController::class, 'search'])->name('merge.search');
        Route::post('/merge/preview', [MergeController::class, 'preview'])->name('merge.preview');
        Route::post('/merge/download', [MergeController::class, 'download'])->name('merge.download');
        Route::get('/merge/preset/{preset}', [MergeController::class, 'preset'])->name('merge.preset');
        Route::post('/merge/clear-recent', [MergeController::class, 'clearRecent'])->name('merge.clearRecent');

        // 🔥 اضافه کردن روت پاک‌سازی session
        Route::post('/clear-session', function () {
            session()->forget([
                'mergedText',
                'mergedFiles',
                'error',
                'success',
                'rawPaths',
                'recent_merge_files'
            ]);
            return redirect()->route('diagnosis.structure')
                ->with('info', '✅ نتایج و کش حذف شدند.');
        })->name('clearSession');

        // روت‌های دیگر
        Route::get('/analysis', [DiagnosisController::class, 'analysis'])->name('analysis');
        Route::get('/security', [DiagnosisController::class, 'security'])->name('security');
        Route::get('/performance', [DiagnosisController::class, 'performance'])->name('performance');
    });
