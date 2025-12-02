<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Controllers
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\LandingController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Student Controllers (App\Http\Controllers\Student)
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Student\StudentExamController;
use App\Http\Controllers\Student\StudentClassController;
use App\Http\Controllers\Student\LearningPathController;
use App\Http\Controllers\Student\MyTeachersController;
use App\Http\Controllers\Student\StudentProfileController;
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
| Landing (Guest)
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingController::class, 'index'])->name('landing');


/*
|--------------------------------------------------------------------------
| OTP Authentication (Guest)
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->name('auth.')->group(function () {

    Route::post('/send-otp',   [AuthController::class, 'sendOtp'])->name('sendOtp');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verifyOtp');

    // ✅ انتخاب نقش فقط اولین بار
    Route::post('/set-role',   [AuthController::class, 'setRole'])
        ->middleware('auth')
        ->name('setRole');

    /*
    | CSRF Refresh (Auth)
    */
    Route::get('/csrf', function () {
        return response()->json(['token' => csrf_token()]);
    })->middleware('auth')->name('csrf');
});


/*
|--------------------------------------------------------------------------
| Logout (Auth)
|--------------------------------------------------------------------------
*/
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


/*
|--------------------------------------------------------------------------
| Role Select (First login)
|--------------------------------------------------------------------------
| بیرون از role.selected تا لوپ ایجاد نشود
*/
Route::get('/dashboard/role-select', function () {
    return view('dashboard.role-select');
})->middleware('auth')->name('role.select');


/*
|--------------------------------------------------------------------------
| Change Role (Profile only)
|--------------------------------------------------------------------------
*/
Route::post('/dashboard/profile/change-role', [AuthController::class, 'changeRole'])
    ->middleware('auth')
    ->name('profile.change-role');


/*
|--------------------------------------------------------------------------
| Dashboards (Auth + Role Selected)
|--------------------------------------------------------------------------
*/
Route::prefix('dashboard')
    ->middleware(['auth', 'role.selected'])
    ->group(function () {

    /*
    |----------------------------------------------------------------------
    | Student Dashboard
    |----------------------------------------------------------------------
    */
Route::prefix('student')
    ->name('student.')
    ->middleware('role:student')
    ->group(function () {

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

    // Exams
    Route::get('/exams', [StudentExamController::class, 'index'])
        ->name('exams.index');
    Route::get('/exams/{exam}', [StudentExamController::class, 'show'])
        ->name('exams.show');
    Route::get('/exams/{exam}/take', [StudentExamController::class, 'take'])
        ->name('exams.take');
    Route::post('/exams/{exam}/start', [StudentExamController::class, 'start'])
        ->name('exams.start');
    Route::post('/exams/{exam}/submit', [StudentExamController::class, 'submit'])
        ->name('exams.submit');

    Route::get('/attempts/{attempt}', [StudentExamController::class, 'attemptShow'])
        ->name('attempts.show');

    // Reports (static ok)
    Route::view('/reports', 'dashboard.student.reports.index')
        ->name('reports.index');

    // ✅ Learning Path (real controller)
    Route::get('/learning-path', [LearningPathController::class, 'index'])
        ->name('learning-path');

    // ✅ My Teachers
    Route::get('/my-teachers', [MyTeachersController::class, 'index'])
        ->name('my-teachers.index');
    // Classrooms (کلاس‌های من)
    Route::get('/classrooms', [StudentClassController::class, 'index'])
        ->name('classrooms.index');

    Route::get('/classrooms/{classroom}', [StudentClassController::class, 'show'])
        ->name('classrooms.show');

    // Support (پشتیبانی) - فعلاً یک صفحه ساده
    Route::view('/support', 'dashboard.student.support.index')
        ->name('support.index');

    // Profile (پروفایل)
    // این از قبل داری و اوکیه:
    Route::view('/profile', 'dashboard.student.profile')->name('profile');
    Route::get('/profile', [StudentProfileController::class, 'edit'])->name('profile');
    Route::put('/profile', [StudentProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [StudentProfileController::class, 'updateAvatar'])->name('profile.avatar');

});


    /*
    |----------------------------------------------------------------------
    | Teacher Dashboard
    |----------------------------------------------------------------------
    */
    Route::prefix('teacher')
        ->name('teacher.')
        ->middleware('role:teacher')
        ->group(function () {

        Route::get('/', [DashboardController::class, 'index'])->name('index');

        // Classes
        Route::get('/classes', [TeacherClassController::class,'index'])
            ->name('classes.index');

        Route::resource('classes', TeacherClassController::class)
            ->parameters(['classes' => 'class'])
            ->except(['index']);

        Route::get('classes/{class}/students', [TeacherClassController::class,'students'])
            ->name('classes.students');

        Route::post('classes/{class}/students', [TeacherClassController::class,'addStudent'])
            ->name('classes.students.add');

        Route::delete('classes/{class}/students/{student}', [TeacherClassController::class,'removeStudent'])
            ->name('classes.students.remove');

        // Exams
        Route::resource('exams', TeacherExamController::class);

        // Questions
        Route::get('/exams/{exam}/questions', [QuestionController::class,'index'])
            ->name('exams.questions.index');

        Route::get('/exams/{exam}/questions/create', [QuestionController::class,'create'])
            ->name('exams.questions.create');

        Route::post('/exams/{exam}/questions', [QuestionController::class,'store'])
            ->name('exams.questions.store');

        Route::get('/exams/{exam}/questions/{question}/edit', [QuestionController::class,'edit'])
            ->name('exams.questions.edit');

        Route::put('/exams/{exam}/questions/{question}', [QuestionController::class,'update'])
            ->name('exams.questions.update');

        Route::delete('/exams/{exam}/questions/{question}', [QuestionController::class,'destroy'])
            ->name('exams.questions.destroy');

        // Students
        Route::get('/students', [TeacherStudentController::class,'index'])
            ->name('students.index');

        Route::get('/students/{student}', [TeacherStudentController::class,'show'])
            ->name('students.show');

        Route::get('/students/{student}/attempts', [TeacherStudentController::class,'attempts'])
            ->name('students.attempts');

        // Attempts
        Route::get('/attempts/{attempt}', [TeacherStudentController::class,'attemptShow'])
            ->name('attempts.show');

        Route::post('/attempts/{attempt}/answers/{answer}/grade',
            [TeacherStudentController::class,'gradeEssayAnswer']
        )->name('attempts.answers.grade');

        // Subjects
        Route::get('/subjects', [SubjectController::class,'index'])
            ->name('subjects.index');

        Route::post('/subjects', [SubjectController::class,'store'])
            ->name('subjects.store');

        // Static
        Route::view('/reports', 'dashboard.teacher.reports.index')->name('reports.index');
        Route::view('/profile', 'dashboard.teacher.profile')->name('profile');
    });

    // Admin Dashboard: فعلاً نداریم
});
