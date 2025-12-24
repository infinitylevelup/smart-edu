<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;  // اصلاح به ClassRoom
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
    // بالا: use ها (اگر ندارید اضافه کنید)

    public function index(Request $request)
    {
        $student = Auth::user();

        $classrooms = $student->classrooms()
            ->with('teacher')
            ->withCount(['students', 'exams'])
            ->latest()
            ->get();

        // ✅ JSON mode for SPA sync
        if ($request->expectsJson()) {

            $payload = $classrooms->map(function ($c) {
                return [
                    'id' => $c->id,
                    'title' => $c->title ?? $c->name ?? 'کلاس',
                    'teacher' => $c->teacher?->name ?? '—',
                    'progress' => (int) ($c->progress ?? 0),

                    // optional useful fields
                    'students_count' => (int) ($c->students_count ?? 0),
                    'exams_count' => (int) ($c->exams_count ?? 0),
                    'updated_at' => optional($c->updated_at)->toISOString(),
                ];
            })->values();

            $etag = '"'.sha1($payload->toJson()).'"';

            if ($request->header('If-None-Match') === $etag) {
                return response('', 304, [
                    'ETag' => $etag,
                    'Cache-Control' => 'private, must-revalidate',
                ]);
            }

            return response()->json(['classes' => $payload], 200, [
                'ETag' => $etag,
                'Cache-Control' => 'private, must-revalidate',
            ]);
        }

        // ✅ HTML mode (همان رفتار قبلی)
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
            'exams' => fn ($q) => $q->latest(),
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
        return view('dashboard.student.classrooms.join-class');
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

        $classroom = ClassRoom::where('join_code', $request->join_code)->firstOrFail();

        // جلوگیری از عضویت تکراری
        if ($student->classrooms()->where('classroom_id', $classroom->id)->exists()) {
            return back()->withErrors([
                'join_code' => 'شما قبلاً عضو این کلاس هستید.',
            ]);
        }

        // عضویت در pivot
        $student->classrooms()->attach($classroom->id);

        return redirect()
            ->route('student.index')
            ->with('success', 'عضویت انجام شد ✅')
            ->with('highlight_class_id', $classroom->id);
    }
}
