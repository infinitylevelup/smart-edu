<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Teacher\DashboardController;
use App\Http\Controllers\Teacher\TeacherClassController;
use App\Http\Controllers\Teacher\TeacherExamController;
use App\Http\Controllers\Teacher\TeacherStudentController;
use App\Http\Controllers\Teacher\SubjectController;
use App\Http\Controllers\Teacher\QuestionController;

Route::prefix('dashboard')
    ->middleware(['auth', 'role.selected'])
    ->group(function () {

        Route::prefix('teacher')
            ->name('teacher.')
            ->middleware('role:teacher')
            ->group(function () {

                Route::get('/', [DashboardController::class, 'index'])->name('index');

                // Classes
                Route::get('/classes', [TeacherClassController::class, 'index'])->name('classes.index');
                Route::resource('classes', TeacherClassController::class)
                    ->parameters(['classes' => 'class'])
                    ->except(['index']);

                Route::get('classes/{class}/students', [TeacherClassController::class, 'students'])->name('classes.students');
                Route::post('classes/{class}/students', [TeacherClassController::class, 'addStudent'])->name('classes.students.add');
                Route::delete('classes/{class}/students/{student}', [TeacherClassController::class, 'removeStudent'])->name('classes.students.remove');

                // Classes AJAX Data
                Route::prefix('classes/data')->name('classes.data.')->group(function () {
                    Route::get('/sections', [TeacherClassController::class, 'sections'])->name('sections');
                    Route::get('/grades/{section}', [TeacherClassController::class, 'grades'])->name('grades');
                    Route::get('/branches/{section}', [TeacherClassController::class, 'branches'])->name('branches');
                    Route::get('/fields/{branch}', [TeacherClassController::class, 'fields'])->name('fields');
                    Route::get('/subfields/{field}', [TeacherClassController::class, 'subfields'])->name('subfields');
                    Route::get('/subject-types', [TeacherClassController::class, 'subjectTypes'])->name('subject-types');
                    Route::get('/subjects', [TeacherClassController::class, 'subjects'])->name('subjects');
                });

                // Exams
                Route::resource('exams', TeacherExamController::class);

                Route::prefix('exams/data')->name('exams.data.')->group(function () {
                    Route::get('/sections', [TeacherExamController::class, 'sections'])->name('sections');
                    Route::get('/grades', [TeacherExamController::class, 'grades'])->name('grades');
                    Route::get('/branches', [TeacherExamController::class, 'branches'])->name('branches');
                    Route::get('/fields', [TeacherExamController::class, 'fields'])->name('fields');
                    Route::get('/subfields', [TeacherExamController::class, 'subfields'])->name('subfields');
                    Route::get('/subject-types', [TeacherExamController::class, 'subjectTypes'])->name('subject-types');
                    Route::get('/subjects', [TeacherExamController::class, 'subjects'])->name('subjects');
                });

                /*
                |----------------------------------------------------------------------
                | ✅ Exam Questions (Wizard Only)
                |----------------------------------------------------------------------
                */
                Route::prefix('exams/{exam}')
                    ->name('exams.')
                    ->group(function () {

                        // index
                        Route::get('questions', [QuestionController::class, 'index'])
                            ->name('questions.index');

                        // Wizard UI
                        Route::get('questions/wizard/create', [QuestionController::class, 'create'])
                            ->name('questions.wizard.create');

                        Route::get('questions/wizard/{question}/edit', [QuestionController::class, 'edit'])
                            ->name('questions.wizard.edit');

                        // Store / Update
                        Route::post('questions', [QuestionController::class, 'store'])
                            ->name('questions.store');

                        Route::put('questions/{question}', [QuestionController::class, 'update'])
                            ->name('questions.update');

                        // Delete
                        Route::delete('questions/{question}', [QuestionController::class, 'destroy'])
                            ->name('questions.destroy');

                        // 🔁 Legacy routes → Hard Redirect to Wizard (با name رسمی)
                        Route::get('questions/create', function ($exam) {
                            return redirect()->route('teacher.exams.questions.wizard.create', $exam);
                        })->name('questions.create');

                        Route::get('questions/{question}/edit', function ($exam, $question) {
                            return redirect()->route('teacher.exams.questions.wizard.edit', [$exam, $question]);
                        })->name('questions.edit');
                    });

                // Students
                Route::get('/students', [TeacherStudentController::class, 'index'])->name('students.index');
                Route::get('/students/{student}', [TeacherStudentController::class, 'show'])->name('students.show');
                Route::get('/students/{student}/attempts', [TeacherStudentController::class, 'attempts'])->name('students.attempts');
                Route::get('/attempts/{attempt}', [TeacherStudentController::class, 'attemptShow'])->name('attempts.show');
                Route::post('/attempts/{attempt}/answers/{answer}/grade', [TeacherStudentController::class, 'gradeEssayAnswer'])
                    ->name('attempts.answers.grade');

                // Subjects
                Route::get('/subjects', [SubjectController::class, 'index'])->name('subjects.index');
                Route::post('/subjects', [SubjectController::class, 'store'])->name('subjects.store');

                // Static
                Route::view('/reports', 'dashboard.teacher.reports.index')->name('reports.index');
                Route::view('/profile', 'dashboard.teacher.profile')->name('profile');
            });
    });
