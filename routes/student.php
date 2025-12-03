<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Student\StudentExamController;
use App\Http\Controllers\Student\StudentClassController;
use App\Http\Controllers\Student\LearningPathController;
use App\Http\Controllers\Student\MyTeachersController;
use App\Http\Controllers\Student\StudentProfileController;

Route::prefix('dashboard')
    ->middleware(['auth', 'role.selected'])
    ->group(function () {

        Route::prefix('student')
            ->name('student.')
            ->middleware('role:student')
            ->group(function () {

                // Dashboard
                Route::view('/', 'dashboard.student.index')->name('index');


                // Classrooms
                Route::get('/classrooms/join', [StudentClassController::class, 'showJoinForm'])
                    ->name('classrooms.join.form');

                Route::post('/classrooms/join', [StudentClassController::class, 'join'])
                    ->name('classrooms.join');

                Route::get('/classrooms', [StudentClassController::class, 'index'])
                    ->name('classrooms.index');

                Route::get('/classrooms/{classroom}', [StudentClassController::class, 'show'])
                    ->name('classrooms.show');


                // Exams (legacy general list - keep for now)
                Route::get('/exams', [StudentExamController::class, 'index'])
                    ->name('exams.index');

                // ✅ NEW: split exam lists by type
                Route::get('/exams/public', [StudentExamController::class, 'publicIndex'])
                    ->name('exams.public');

                Route::get('/exams/classroom', [StudentExamController::class, 'classroomIndex'])
                    ->name('exams.classroom');

                // Shared exam routes
                Route::get('/exams/{exam}', [StudentExamController::class, 'show'])
                    ->name('exams.show');

                Route::get('/exams/{exam}/take', [StudentExamController::class, 'take'])
                    ->name('exams.take');

                Route::post('/exams/{exam}/start', [StudentExamController::class, 'start'])
                    ->name('exams.start');

                Route::post('/exams/{exam}/submit', [StudentExamController::class, 'submit'])
                    ->name('exams.submit');


                // ✅ NEW: Attempt result & analysis
                Route::get('/attempts/{attempt}/result', [StudentExamController::class, 'result'])
                    ->name('attempts.result');

                Route::get('/attempts/{attempt}/analysis', [StudentExamController::class, 'analysis'])
                    ->name('attempts.analysis');

                // legacy attempt show (keep so old links won't break)
                Route::get('/attempts/{attempt}', [StudentExamController::class, 'attemptShow'])
                    ->name('attempts.show');


                // Reports / Support
                Route::view('/reports', 'dashboard.student.reports.index')
                    ->name('reports.index');

                Route::view('/support', 'dashboard.student.support.index')
                    ->name('support.index');


                // Learning Path / My Teachers
                Route::get('/learning-path', [LearningPathController::class, 'index'])
                    ->name('learning-path');

                Route::get('/my-teachers', [MyTeachersController::class, 'index'])
                    ->name('my-teachers.index');


                // Profile
                Route::get('/profile', [StudentProfileController::class, 'edit'])
                    ->name('profile');

                Route::put('/profile', [StudentProfileController::class, 'update'])
                    ->name('profile.update');

                Route::post('/profile/avatar', [StudentProfileController::class, 'updateAvatar'])
                    ->name('profile.avatar');
            });
    });
