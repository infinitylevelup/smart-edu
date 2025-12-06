<?php

namespace App\Http\Controllers\Teacher;
use Illuminate\Support\Facades\Auth; // برای گرفتن id کاربر لاگین‌شده
use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Question;

/**
 * Teacher Dashboard Controller
 *
 * این کنترلر فقط برای صفحه‌ی اصلی پنل معلم است:
 * /dashboard/teacher
 *
 * هدف:
 * - جمع‌آوری آمار کلی (تعداد آزمون‌ها، فعال/غیرفعال، تعداد سوال‌ها)
 * - گرفتن چند آزمون آخر برای نمایش در داشبورد
 *
 * نکته:
 * چون قبلاً داشبورد معلم با Route::view لود می‌شد،
 * هیچ دیتایی به ویو نمی‌رسید. این کنترلر همان داده‌ها را تامین می‌کند.
 */
class DashboardController extends Controller
{
    /**
     * نمایش صفحه داشبورد معلم
     *
     * خروجی:
     * view('dashboard.teacher.index')
     * همراه با:
     * - $stats : آمار خلاصه
     * - $recentExams : آخرین آزمون‌ها
     */

public function index()
{
    $user = Auth::user();

    // اگر پروفایل teacher جدا داری، از اون استفاده می‌کنیم
    $teacher = $user->teacher ?? null;

    // اگر exams.teacher_id به جدول teachers وصل است → id پروفایل معلم
    // اگر exams.teacher_id همان user_id است → id خود user
    $teacherKey = $teacher?->id ?? $user->id;

    /**
     * کوئری پایه برای آزمون‌های معلم
     */
    $examsQuery = Exam::where('teacher_id', $teacherKey);

    /**
     * stats با کلیدهای استاندارد مطابق Blade
     */
    $stats = [
        'total_exams' => (clone $examsQuery)->count(),

        'active_exams' => (clone $examsQuery)
            ->where('is_published', true)
            ->count(),

        // فعلاً تا وقتی مدل/رابطه‌ی کلاس و attempt/answer رو مچ نکردیم
        'students_count' => 0,
        'new_students' => 0,
        'pending_reviews' => 0,
    ];

    /**
     * آخرین ۵ آزمون + شمارش سوالات و شرکت‌کننده‌ها
     * نکته: باید روی Exam رابطه‌ی questions و attempts تعریف شده باشد.
     */
    $recentExams = (clone $examsQuery)
        ->latest()
        ->withCount(['questions', 'attempts'])
        ->take(5)
        ->get();

    return view('dashboard.teacher.index', compact('stats', 'recentExams'));
}



}
