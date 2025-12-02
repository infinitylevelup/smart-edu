<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Teacher Controllers (App\Http\Controllers\Teacher)
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Teacher\DashboardController;
use App\Http\Controllers\Teacher\TeacherClassController;
use App\Http\Controllers\Teacher\TeacherExamController;
use App\Http\Controllers\Teacher\TeacherStudentController;
use App\Http\Controllers\Teacher\SubjectController;
use App\Http\Controllers\Teacher\QuestionController;

Route::prefix('dashboard')
    ->middleware(['auth', 'role.selected'])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Teacher Dashboard
        |--------------------------------------------------------------------------
        */
        Route::prefix('teacher')
            ->name('teacher.')
            ->middleware('role:teacher')
            ->group(function () {

                Route::get('/', [DashboardController::class, 'index'])->name('index');

                // Classes
                Route::get('/classes', [TeacherClassController::class, 'index'])
                    ->name('classes.index');

                Route::resource('classes', TeacherClassController::class)
                    ->parameters(['classes' => 'class'])
                    ->except(['index']);

                Route::get('classes/{class}/students', [TeacherClassController::class, 'students'])
                    ->name('classes.students');

                Route::post('classes/{class}/students', [TeacherClassController::class, 'addStudent'])
                    ->name('classes.students.add');

                Route::delete('classes/{class}/students/{student}', [TeacherClassController::class, 'removeStudent'])
                    ->name('classes.students.remove');

                // Exams
                Route::resource('exams', TeacherExamController::class);

                // Questions
                Route::get('/exams/{exam}/questions', [QuestionController::class, 'index'])
                    ->name('exams.questions.index');
                Route::get('/exams/{exam}/questions/create', [QuestionController::class, 'create'])
                    ->name('exams.questions.create');
                Route::post('/exams/{exam}/questions', [QuestionController::class, 'store'])
                    ->name('exams.questions.store');
                Route::get('/exams/{exam}/questions/{question}/edit', [QuestionController::class, 'edit'])
                    ->name('exams.questions.edit');
                Route::put('/exams/{exam}/questions/{question}', [QuestionController::class, 'update'])
                    ->name('exams.questions.update');
                Route::delete('/exams/{exam}/questions/{question}', [QuestionController::class, 'destroy'])
                    ->name('exams.questions.destroy');

                // Students
                Route::get('/students', [TeacherStudentController::class, 'index'])
                    ->name('students.index');
                Route::get('/students/{student}', [TeacherStudentController::class, 'show'])
                    ->name('students.show');
                Route::get('/students/{student}/attempts', [TeacherStudentController::class, 'attempts'])
                    ->name('students.attempts');

                // Attempts
                Route::get('/attempts/{attempt}', [TeacherStudentController::class, 'attemptShow'])
                    ->name('attempts.show');
                Route::post('/attempts/{attempt}/answers/{answer}/grade', [TeacherStudentController::class, 'gradeEssayAnswer'])
                    ->name('attempts.answers.grade');

                // Subjects
                Route::get('/subjects', [SubjectController::class, 'index'])
                    ->name('subjects.index');
                Route::post('/subjects', [SubjectController::class, 'store'])
                    ->name('subjects.store');

                // Static
                Route::view('/reports', 'dashboard.teacher.reports.index')->name('reports.index');
                Route::view('/profile', 'dashboard.teacher.profile')->name('profile');
            });
    });
