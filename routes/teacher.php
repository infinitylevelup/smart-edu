<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Teacher\DashboardController;
use App\Http\Controllers\Teacher\TeacherClassController;
use App\Http\Controllers\Teacher\TeacherExamController;
use App\Http\Controllers\Teacher\TeacherStudentController;
use App\Http\Controllers\Teacher\SubjectController;
use App\Http\Controllers\Teacher\QuestionController;

// ============================================================
// 📁 بخش: Teacher Dashboard Routes
// ============================================================
Route::prefix('dashboard')
    ->middleware(['auth', 'role.selected'])
    ->group(function () {

        Route::prefix('teacher')
            ->name('teacher.')
            ->middleware('role:teacher')
            ->group(function () {

                // ============================================
                // 🏠 صفحه اصلی معلم
                // ============================================
                Route::get('/', [DashboardController::class, 'index'])->name('index');

                // ============================================
                // 📚 بخش کلاس‌ها (Classrooms)
                // ============================================
                // لیست کلاس‌ها
                Route::get('/classes', [TeacherClassController::class, 'index'])->name('classes.index');

                // عملیات CRUD کلاس‌ها (به جز index که بالاتر تعریف شد)
                Route::resource('classes', TeacherClassController::class)
                    ->parameters(['classes' => 'class'])
                    ->except(['index']);

                // مدیریت دانش‌آموزان کلاس
                Route::get('classes/{class}/students', [TeacherClassController::class, 'students'])->name('classes.students');
                Route::post('classes/{class}/students', [TeacherClassController::class, 'addStudent'])->name('classes.students.add');
                Route::delete('classes/{class}/students/{student}', [TeacherClassController::class, 'removeStudent'])->name('classes.students.remove');

                // اطلاعات کلاس برای AJAX
                Route::get('/classrooms/{classroom}/info', [TeacherClassController::class, 'info']);

                // ============================================
                // 📊 داده‌های AJAX برای کلاس‌ها (Taxonomy)
                // ============================================
                Route::prefix('classes/data')->name('classes.data.')->group(function () {
                    Route::get('/sections', [TeacherClassController::class, 'sections'])->name('sections');
                    Route::get('/grades/{section}', [TeacherClassController::class, 'grades'])->name('grades');
                    Route::get('/branches/{section}', [TeacherClassController::class, 'branches'])->name('branches');
                    Route::get('/fields/{branch}', [TeacherClassController::class, 'fields'])->name('fields');
                    Route::get('/subfields/{field}', [TeacherClassController::class, 'subfields'])->name('subfields');
                    Route::get('/subject-types', [TeacherClassController::class, 'subjectTypes'])->name('subject-types');
                    Route::get('/subjects', [TeacherClassController::class, 'subjects'])->name('subjects');
                });

                // ============================================
                // 📝 بخش آزمون‌ها (Exams) - قسمت اول: داده‌های AJAX
                // ============================================
                Route::prefix('exams/data')->name('exams.data.')->group(function () {
                    // Taxonomy برای ویزارد ایجاد/ویرایش آزمون
                    Route::get('/sections', [TeacherExamController::class, 'sections'])->name('sections');
                    Route::get('/grades', [TeacherExamController::class, 'grades'])->name('grades');
                    Route::get('/branches', [TeacherExamController::class, 'branches'])->name('branches');
                    Route::get('/fields', [TeacherExamController::class, 'fields'])->name('fields');
                    Route::get('/subfields', [TeacherExamController::class, 'subfields'])->name('subfields');
                    Route::get('/subject-types', [TeacherExamController::class, 'subjectTypes'])->name('subject-types');
                    Route::get('/subjects', [TeacherExamController::class, 'subjects'])->name('subjects');
                });

                // ============================================
                // 🏫 لیست کلاس‌ها برای ویزارد آزمون (AJAX)
                // ============================================
                Route::get('exams/data/classes', [TeacherClassController::class, 'index'])->name('exams.data.classes');
                // JSON کلاس‌ها برای ویزارد/ادیت آزمون
                Route::get('exams/data/classes-json', [TeacherExamController::class, 'classesJson'])
                    ->name('exams.data.classes-json');
                // ============================================
                // 📝 بخش آزمون‌ها (Exams) - قسمت دوم: عملیات CRUD
                // ⭐⭐ نکته مهم: edit را جدا تعریف می‌کنیم تا تداخل با create نداشته باشد ⭐⭐
                // ============================================
                Route::resource('exams', TeacherExamController::class)->except(['edit']);

                // ⭐⭐ تعریف جداگانه route edit برای جلوگیری از تداخل ⭐⭐
                Route::get('exams/{exam}/edit', [TeacherExamController::class, 'edit'])->name('exams.edit');

                // ============================================
                // ❓ بخش سوالات آزمون (Questions)
                // ============================================
                Route::prefix('exams/{exam}')
                    ->name('exams.')
                    ->group(function () {

                        // لیست سوالات
                        Route::get('questions', [QuestionController::class, 'index'])
                            ->name('questions.index');

                        // ویزارد ایجاد سوال
                        Route::get('questions/wizard/create', [QuestionController::class, 'create'])
                            ->name('questions.wizard.create');

                        // ویزارد ویرایش سوال
                        Route::get('questions/wizard/{question}/edit', [QuestionController::class, 'edit'])
                            ->name('questions.wizard.edit');

                        // ذخیره سوال جدید
                        Route::post('questions', [QuestionController::class, 'store'])
                            ->name('questions.store');

                        // به‌روزرسانی سوال
                        Route::put('questions/{question}', [QuestionController::class, 'update'])
                            ->name('questions.update');

                        // حذف سوال
                        Route::delete('questions/{question}', [QuestionController::class, 'destroy'])
                            ->name('questions.destroy');

                        // 🔁 ریدایرکت routes قدیمی به ویزارد
                        Route::get('questions/create', function ($exam) {
                            return redirect()->route('teacher.exams.questions.wizard.create', $exam);
                        })->name('questions.create');

                        Route::get('questions/{question}/edit', function ($exam, $question) {
                            return redirect()->route('teacher.exams.questions.wizard.edit', [$exam, $question]);
                        })->name('questions.edit');

                    });

                // ============================================
                // 👨‍🎓 بخش دانش‌آموزان (Students)
                // ============================================
                Route::get('/students', [TeacherStudentController::class, 'index'])->name('students.index');
                Route::get('/students/{student}', [TeacherStudentController::class, 'show'])->name('students.show');
                Route::get('/students/{student}/attempts', [TeacherStudentController::class, 'attempts'])->name('students.attempts');
                Route::get('/attempts/{attempt}', [TeacherStudentController::class, 'attemptShow'])->name('attempts.show');
                Route::post('/attempts/{attempt}/answers/{answer}/grade', [TeacherStudentController::class, 'gradeEssayAnswer'])
                    ->name('attempts.answers.grade');

                // ============================================
                // 📘 بخش دروس (Subjects) - فقط نمایش و ایجاد
                // ============================================
                Route::get('/subjects', [SubjectController::class, 'index'])->name('subjects.index');
                Route::post('/subjects', [SubjectController::class, 'store'])->name('subjects.store');

                // ============================================
                // 📊 صفحات استاتیک (Static Pages)
                // ============================================
                Route::view('/reports', 'dashboard.teacher.reports.index')->name('reports.index');
                Route::view('/profile', 'dashboard.teacher.profile')->name('profile');
            });
    });

// ============================================================
// ✅ پایان فایل routes/teacher.php
// ============================================================
