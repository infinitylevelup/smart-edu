<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Counselor\CounselorDashboardController;
use App\Http\Controllers\Counselor\CounselorStudentController;
use App\Http\Controllers\Counselor\CounselorAttemptController;
use App\Http\Controllers\Counselor\CounselorLearningPathController;
use App\Http\Controllers\Counselor\CounselorProfileController;

Route::prefix('dashboard')
    ->middleware(['auth', 'role.selected'])
    ->group(function () {

        Route::prefix('counselor')
            ->name('counselor.')
            ->middleware('role:counselor')
            ->group(function () {

                // ✅ Dashboard
                Route::get('/', [CounselorDashboardController::class, 'index'])
                    ->name('index');

                // ✅ Students
                Route::get('/students', [CounselorStudentController::class, 'index'])
                    ->name('students.index');
                Route::get('/students/{student}', [CounselorStudentController::class, 'show'])
                    ->name('students.show');

                // ✅ Attempts by student
                Route::get('/students/{student}/attempts', [CounselorAttemptController::class, 'index'])
                    ->name('attempts.index');
                Route::get('/attempts/{attempt}', [CounselorAttemptController::class, 'show'])
                    ->name('attempts.show');

                // ✅ Analyze attempt
                Route::get('/attempts/{attempt}/analyze', [CounselorAttemptController::class, 'editAnalysis'])
                    ->name('attempts.analyze.edit');
                Route::post('/attempts/{attempt}/analyze', [CounselorAttemptController::class, 'storeAnalysis'])
                    ->name('attempts.analyze.store');

                // ✅ Learning paths
                Route::get('/learning-paths', [CounselorLearningPathController::class, 'index'])
                    ->name('learning-paths.index');
                Route::get('/learning-paths/create/{student}', [CounselorLearningPathController::class, 'create'])
                    ->name('learning-paths.create');
                Route::post('/learning-paths/{student}', [CounselorLearningPathController::class, 'store'])
                    ->name('learning-paths.store');

                // ✅ Reports (Phase 1 just views)
                Route::view('/reports', 'dashboard.counselor.reports.index')
                    ->name('reports.index');
                Route::view('/reports/{report}', 'dashboard.counselor.reports.show')
                    ->name('reports.show');

                // ✅ Profile
                Route::get('/profile', [CounselorProfileController::class, 'edit'])
                    ->name('profile');
                Route::put('/profile', [CounselorProfileController::class, 'update'])
                    ->name('profile.update');
            });
    });
