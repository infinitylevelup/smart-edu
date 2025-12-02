<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;  //اصلاح به Classroom
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * StudentClassController
 * ----------------------------------------------------------------
 * کنترلر کلاس‌های دانش‌آموز
 *
 * این کنترلر مسئول این کارهاست:
 * 1) index        : نمایش لیست کلاس‌های عضو شده‌ی دانش‌آموز
 * 2) show         : نمایش صفحه جزئیات یک کلاس برای دانش‌آموز
 * 3) showJoinForm : نمایش فرم ورود به کلاس با join_code
 * 4) join         : عضویت دانش‌آموز در کلاس با join_code
 *
 * Bladeهای مربوط:
 * - dashboard.student.classrooms.index
 * - dashboard.student.classrooms.show
 * - dashboard.student.join-class
 */
class StudentClassController extends Controller
{
    /**
     * لیست کلاس‌های دانش‌آموز
     * ------------------------------------------------------------
     * فقط کلاس‌هایی که دانش‌آموز عضو آن‌هاست را می‌گیرد.
     *
     * Route:
     * GET /dashboard/student/classrooms
     * name: student.classrooms.index
     */
    public function index()
    {
        $student = Auth::user();

        // کلاس‌های عضو شده + اطلاعات معلم
        $classrooms = $student->classrooms()
            ->with('teacher')
            ->withCount(['students','exams'])
            ->latest()
            ->get();

        return view('dashboard.student.classrooms.index', compact('classrooms'));
    }

    /**
     * نمایش یک کلاس برای دانش‌آموز
     * ------------------------------------------------------------
     * امنیت مهم:
     * دانش‌آموز فقط اگر عضو این کلاس باشد اجازه دیدن صفحه را دارد.
     *
     * Route:
     * GET /dashboard/student/classrooms/{classroom}
     * name: student.classrooms.show
     */
    public function show(ClassRoom $classroom)  // ✅ اصلاح شد
    {
        $student = Auth::user();

        // چک عضویت دانش‌آموز در کلاس
        $isMember = $student->classrooms()
            ->where('classroom_id', $classroom->id)
            ->exists();

        abort_unless($isMember, 403, 'شما عضو این کلاس نیستید.');

        // لود اطلاعات تکمیلی برای نمایش در صفحه
        $classroom->load([
            'teacher',
            'exams' => fn($q) => $q->latest(),
        ]);

        return view('dashboard.student.classrooms.show', compact('classroom'));
    }

    /**
     * نمایش فرم ورود به کلاس
     * ------------------------------------------------------------
     * Route:
     * GET /dashboard/student/classrooms/join
     * name: student.classrooms.join.form
     */
    public function showJoinForm()
    {
        return view('dashboard.student.join-class');
    }

    /**
     * عضویت دانش‌آموز در کلاس با join_code
     * ------------------------------------------------------------
     * Flow:
     * 1) اعتبارسنجی join_code
     * 2) پیدا کردن کلاس
     * 3) جلوگیری از عضویت دوباره
     * 4) attach در pivot (classroom_student)
     * 5) redirect به لیست کلاس‌ها
     *
     * Route:
     * POST /dashboard/student/classrooms/join
     * name: student.classrooms.join
     */
    public function join(Request $request)
    {
        $request->validate([
            'join_code' => ['required', 'string', 'exists:classrooms,join_code'],
        ]);

        $student = Auth::user();

        $classroom = Classroom::where('join_code', $request->join_code)->firstOrFail();

        // جلوگیری از عضویت تکراری
        if ($student->classrooms()->where('classroom_id', $classroom->id)->exists()) {
            return back()->withErrors([
                'join_code' => 'شما قبلاً عضو این کلاس هستید.'
            ]);
        }

        // عضویت در pivot
        $student->classrooms()->attach($classroom->id);

        return redirect()->route('student.classrooms.index')
            ->with('success', 'با موفقیت به کلاس اضافه شدید.');
    }
}
