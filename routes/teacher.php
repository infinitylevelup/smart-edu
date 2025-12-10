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

/*
|--------------------------------------------------------------------------
| Teacher Panel Routes
|--------------------------------------------------------------------------
| ساختار اصلی قبلی حفظ شده:
| /dashboard/teacher/...
| middleware: auth + role.selected + role:teacher
|--------------------------------------------------------------------------
*/
Route::prefix('dashboard')
    ->middleware(['auth', 'role.selected'])
    ->group(function () {

        Route::prefix('teacher')
            ->name('teacher.')
            ->middleware('role:teacher')
            ->group(function () {

                // ==========================================================
                // Teacher Dashboard
                // ==========================================================
                Route::get('/', [DashboardController::class, 'index'])->name('index');

                // ==========================================================
                // Classes (CRUD اصلی)
                // ==========================================================
                // لیست کلاس‌ها (index) جداست چون resource بجز index تعریف شده
                Route::get('/classes', [TeacherClassController::class, 'index'])
                    ->name('classes.index');

                // resource کامل برای create/store/show/edit/update/destroy
                Route::resource('classes', TeacherClassController::class)
                    ->parameters(['classes' => 'class'])
                    ->except(['index']);

                // Students of a class
                Route::get('classes/{class}/students', [TeacherClassController::class, 'students'])
                    ->name('classes.students');

                Route::post('classes/{class}/students', [TeacherClassController::class, 'addStudent'])
                    ->name('classes.students.add');

                Route::delete('classes/{class}/students/{student}', [TeacherClassController::class, 'removeStudent'])
                    ->name('classes.students.remove');


                // ==========================================================
                // ✅ Classes AJAX Data (برای پر کردن دراپ‌داون‌های تاکسونومی)
                // ----------------------------------------------------------
                // این بخش جدید اضافه شد و هیچ تداخلی با resource ندارد،
                // چون مسیرها زیر /classes/data/... هستند.
                //
                // وابستگی‌ها:
                // sections -> grades -> branches -> fields -> subfields
                // fields -> subject-types -> subjects
                //
                // خروجی JSON است و فقط برای فرم ساخت/ادیت کلاس استفاده می‌شود.
                // ==========================================================
                Route::prefix('classes/data')
                    ->name('classes.data.')
                    ->group(function () {
                        Route::get('/sections', [TeacherClassController::class, 'sections'])->name('sections');
                        Route::get('/grades/{section}', [TeacherClassController::class, 'grades'])->name('grades');
                        Route::get('/branches/{grade}', [TeacherClassController::class, 'branches'])->name('branches');
                        Route::get('/fields/{branch}', [TeacherClassController::class, 'fields'])->name('fields');
                        Route::get('/subfields/{field}', [TeacherClassController::class, 'subfields'])->name('subfields');
                        Route::get('/subject-types/{field}', [TeacherClassController::class, 'subjectTypes'])->name('subject-types');
                        Route::get('/subjects/{subjectType}', [TeacherClassController::class, 'subjects'])->name('subjects');
                    });



                // ==========================================================
                // Exams (resource کامل)
                // ==========================================================
                Route::resource('exams', TeacherExamController::class);

                // ==========================================================
                // Exams AJAX Data (قبلی شما بدون تغییر)
                // ==========================================================
                Route::prefix('exams/data')
                    ->name('exams.data.')
                    ->group(function () {

                        Route::get('/sections', [TeacherExamController::class, 'sections'])
                            ->name('sections');

                        Route::get('/grades', [TeacherExamController::class, 'grades'])
                            ->name('grades');

                        Route::get('/branches', [TeacherExamController::class, 'branches'])
                            ->name('branches');

                        Route::get('/fields', [TeacherExamController::class, 'fields'])
                            ->name('fields');

                        Route::get('/subfields', [TeacherExamController::class, 'subfields'])
                            ->name('subfields');

                        Route::get('/subject-types', [TeacherExamController::class, 'subjectTypes'])
                            ->name('subject-types');

                        Route::get('/subjects', [TeacherExamController::class, 'subjects'])
                            ->name('subjects');
                    });

                // ==========================================================
                // Questions (قبلی شما بدون تغییر)
                // ==========================================================
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

                // ==========================================================
                // Students (قبلی شما بدون تغییر)
                // ==========================================================
                Route::get('/students', [TeacherStudentController::class, 'index'])
                    ->name('students.index');
                Route::get('/students/{student}', [TeacherStudentController::class, 'show'])
                    ->name('students.show');
                Route::get('/students/{student}/attempts', [TeacherStudentController::class, 'attempts'])
                    ->name('students.attempts');

                Route::get('/attempts/{attempt}', [TeacherStudentController::class, 'attemptShow'])
                    ->name('attempts.show');
                Route::post('/attempts/{attempt}/answers/{answer}/grade', [TeacherStudentController::class, 'gradeEssayAnswer'])
                    ->name('attempts.answers.grade');

                // ==========================================================
                // Subjects (قبلی شما بدون تغییر)
                // ==========================================================
                Route::get('/subjects', [SubjectController::class, 'index'])
                    ->name('subjects.index');
                Route::post('/subjects', [SubjectController::class, 'store'])
                    ->name('subjects.store');

                // ==========================================================
                // Static
                // ==========================================================
                Route::view('/reports', 'dashboard.teacher.reports.index')->name('reports.index');
                Route::view('/profile', 'dashboard.teacher.profile')->name('profile');
            });
    });
