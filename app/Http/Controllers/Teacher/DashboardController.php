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
        // شناسه معلم لاگین‌شده
        $teacherId = Auth::id();

        /**
         * کوئری پایه برای آزمون‌های این معلم
         * (با clone بعداً چند بار بدون تداخل استفاده می‌کنیم)
         */
        $examsQuery = Exam::where('teacher_id', $teacherId);

        /**
         * آمار اصلی داشبورد
         * اگر در Exam فیلد is_active داری، این شمارش‌ها درست کار می‌کنند.
         */
        $stats = [
            // تعداد کل آزمون‌های این معلم
            'exams_total' => (clone $examsQuery)->count(),

            // تعداد آزمون‌های فعال
            'exams_active' => (clone $examsQuery)
                                ->where('is_published', true)
                                ->count(),

            // تعداد آزمون‌های غیرفعال
            'exams_inactive' => (clone $examsQuery)
                                ->where('is_published', false)
                                ->count(),

            /**
             * تعداد کل سوال‌های متعلق به آزمون‌های این معلم
             * از whereHas استفاده می‌کنیم تا فقط سوال‌های Examهای همین معلم شمرده شود.
             */
            'questions_total' => Question::whereHas('exam', function ($q) use ($teacherId) {
                $q->where('teacher_id', $teacherId);
            })->count(),
        ];

        /**
         * آخرین 5 آزمون ساخته‌شده برای نمایش در جدول داشبورد
         */
        $recentExams = (clone $examsQuery)
                        ->latest()
                        ->take(5)
                        ->get();

        // ارسال داده‌ها به ویو داشبورد معلم
        return view('dashboard.teacher.index', compact('stats', 'recentExams'));
    }
}
