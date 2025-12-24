<?php

use App\Http\Controllers\Teacher\DashboardController;
use App\Http\Controllers\Teacher\QuestionController;
use App\Http\Controllers\Teacher\SubjectController;
use App\Http\Controllers\Teacher\TeacherClassController;
use App\Http\Controllers\Teacher\TeacherExamController;
use App\Http\Controllers\Teacher\TeacherStudentController;
use Illuminate\Support\Facades\Route;

Route::prefix('dashboard')
    ->middleware(['auth'])
    ->group(function () {

        Route::prefix('teacher')
            ->name('teacher.')
            ->middleware('role:teacher')
            ->group(function () {

                Route::get('/', [DashboardController::class, 'index'])->name('index');

                Route::get('/classes', [TeacherClassController::class, 'index'])->name('classes.index');

                Route::resource('classes', TeacherClassController::class)
                    ->parameters(['classes' => 'class'])
                    ->except(['index']);

                Route::get('classes-trash', [TeacherClassController::class, 'trash'])
                    ->name('teacher.classes.trash');

                Route::post('classes/{id}/restore', [TeacherClassController::class, 'restore'])
                    ->name('teacher.classes.restore');

                Route::get('classes/{class}/students', [TeacherClassController::class, 'students'])->name('classes.students');
                Route::post('classes/{class}/students', [TeacherClassController::class, 'addStudent'])->name('classes.students.add');
                Route::delete('classes/{class}/students/{student}', [TeacherClassController::class, 'removeStudent'])->name('classes.students.remove');

                Route::get('/classrooms/{classroom}/info', [TeacherClassController::class, 'info']);

                Route::prefix('classes/data')->name('classes.data.')->group(function () {
                    Route::get('/sections', [TeacherClassController::class, 'sections'])->name('sections');
                    Route::get('/grades/{section}', [TeacherClassController::class, 'grades'])->name('grades');

                    Route::get('/branches/{grade}', [TeacherClassController::class, 'branches'])->name('branches');
                    Route::get('/fields/{branch}', [TeacherClassController::class, 'fields'])->name('fields');
                    Route::get('/subfields/{field}', [TeacherClassController::class, 'subfields'])->name('subfields');

                    Route::get('/subject-types/{field}', [TeacherClassController::class, 'subjectTypes'])->name('subject-types');
                    Route::get('/subjects/{subjectType}', [TeacherClassController::class, 'subjects'])->name('subjects');
                });

                Route::prefix('exams/data')->name('exams.data.')->group(function () {
                    Route::get('/sections', [TeacherExamController::class, 'sections'])->name('sections');
                    Route::get('/grades', [TeacherExamController::class, 'grades'])->name('grades');
                    Route::get('/branches', [TeacherExamController::class, 'branches'])->name('branches');
                    Route::get('/fields', [TeacherExamController::class, 'fields'])->name('fields');
                    Route::get('/subfields', [TeacherExamController::class, 'subfields'])->name('subfields');
                    Route::get('/subject-types', [TeacherExamController::class, 'subjectTypes'])->name('subject-types');
                    Route::get('/subjects', [TeacherExamController::class, 'subjects'])->name('subjects');
                });

                Route::get('exams/data/classes', [TeacherClassController::class, 'index'])->name('exams.data.classes');

                Route::get('exams/data/classes-json', [TeacherExamController::class, 'classesJson'])
                    ->name('exams.data.classes-json');

                Route::resource('exams', TeacherExamController::class)->except(['edit']);
                Route::get('exams/{exam}/edit', [TeacherExamController::class, 'edit'])->name('exams.edit');

                Route::get('exams/{exam}/questions/attach', [TeacherExamController::class, 'attachQuestions'])
                    ->name('exams.questions.attach');

                Route::post('exams/{exam}/questions/attach', [TeacherExamController::class, 'storeAttachedQuestions'])
                    ->name('exams.questions.store-attached');

                Route::prefix('exams/{exam}')
                    ->name('exams.')
                    ->group(function () {

                        Route::get('questions', [QuestionController::class, 'index'])
                            ->name('questions.index');

                        Route::get('questions/wizard/create', [QuestionController::class, 'create'])
                            ->name('questions.wizard.create');

                        Route::get('questions/wizard/{question}/edit', [QuestionController::class, 'edit'])
                            ->name('questions.wizard.edit');

                        Route::post('questions', [QuestionController::class, 'store'])
                            ->name('questions.store');

                        Route::put('questions/{question}', [QuestionController::class, 'update'])
                            ->name('questions.update');

                        Route::delete('questions/{question}', [QuestionController::class, 'destroy'])
                            ->name('questions.destroy');

                        Route::get('questions/create', function ($exam) {
                            return redirect()->route('teacher.exams.questions.wizard.create', $exam);
                        })->name('questions.create');

                        Route::get('questions/{question}/edit', function ($exam, $question) {
                            return redirect()->route('teacher.exams.questions.wizard.edit', [$exam, $question]);
                        })->name('questions.edit');
                    });

                Route::get('/students', [TeacherStudentController::class, 'index'])->name('students.index');
                Route::get('/students/{student}', [TeacherStudentController::class, 'show'])->name('students.show');
                Route::get('/students/{student}/attempts', [TeacherStudentController::class, 'attempts'])->name('students.attempts');
                Route::get('/attempts/{attempt}', [TeacherStudentController::class, 'attemptShow'])->name('attempts.show');
                Route::post('/attempts/{attempt}/answers/{answer}/grade', [TeacherStudentController::class, 'gradeEssayAnswer'])
                    ->name('attempts.answers.grade');

                Route::get('/subjects', [SubjectController::class, 'index'])->name('subjects.index');
                Route::post('/subjects', [SubjectController::class, 'store'])->name('subjects.store');

                Route::view('/reports', 'dashboard.teacher.reports.index')->name('reports.index');
                Route::view('/profile', 'dashboard.teacher.profile')->name('profile');
            });
    });
