<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;     // ✅ اضافه شود
use App\Http\Controllers\Admin\ClassroomController;     // ✅ اضافه شود

use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\GradeController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\FieldController;
use App\Http\Controllers\Admin\SubfieldController;
use App\Http\Controllers\Admin\SubjectTypeController;
use App\Http\Controllers\Admin\ExamController; // ✅ اضافه شود      
use App\Http\Controllers\Admin\SubjectTaxonomyController; // ✅ اضافه شود


/*
|--------------------------------------------------------------------------
| Admin Auth Routes (OTP Login) - PUBLIC (no auth middleware)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/send-otp', [AdminAuthController::class, 'sendOtp'])->name('send-otp');
    Route::post('/verify-otp', [AdminAuthController::class, 'verifyOtp'])->name('verify-otp');
});




/*
|--------------------------------------------------------------------------
| Admin Dashboard + Routes - PROTECTED
|--------------------------------------------------------------------------
*/
Route::prefix('dashboard/admin')
    ->middleware(['auth', 'role.selected', 'role:admin'])
    ->name('admin.')
    ->group(function () {

        Route::get('/', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('users', AdminUserController::class)
            ->only(['index','show','update','destroy']);

        Route::resource('classrooms', ClassroomController::class)
            ->only(['index','show','update','destroy']);

        // ✅ همین رو اضافه کن
        Route::resource('exams', ExamController::class)
            ->only(['index','show','update','destroy']);

        Route::resource('sections', SectionController::class);
        Route::resource('grades', GradeController::class);
        Route::resource('branches', BranchController::class);
        Route::resource('fields', FieldController::class);
        Route::resource('subfields', SubfieldController::class);
        Route::resource('subject-types', SubjectTypeController::class);
        Route::resource('subjects', SubjectController::class);

        // ✅ API قبلی خودت (بدون تغییر)
        Route::get('api/subjects/by-grade/{grade}', [SubjectController::class, 'byGrade'])
            ->name('api.subjects.by-grade');

        // ✅ NEW APIs برای dropdown هوشمند
        Route::get('api/grades/by-section/{section}', [SubjectTaxonomyController::class, 'gradesBySection'])
            ->name('api.grades.by-section');

        Route::get('api/branches/by-section/{section}', [SubjectTaxonomyController::class, 'branchesBySection'])
            ->name('api.branches.by-section');

        Route::get('api/fields/by-branch/{branch}', [SubjectTaxonomyController::class, 'fieldsByBranch'])
            ->name('api.fields.by-branch');

        Route::get('api/subfields/by-field/{field}', [SubjectTaxonomyController::class, 'subfieldsByField'])
            ->name('api.subfields.by-field');
    });
