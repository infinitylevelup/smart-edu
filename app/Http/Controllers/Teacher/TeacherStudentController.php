<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attempt;
use App\Models\Classroom;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\AttemptAnswer;

class TeacherStudentController extends Controller
{
    /**
     * نمایش لیست دانش‌آموزهای معلم
     */
    public function index(Request $request)
    {
        $teacher = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | NEW LOGIC (stable)
        |--------------------------------------------------------------------------
        | classrooms() مربوط به دانش‌آموز است (pivot classroom_student)
        | برای کلاس‌های معلم باید از teachingClassrooms() استفاده کنیم.
        */

        $classroomsQuery = $teacher->teachingClassrooms();

        // فیلتر کلاس (اختیاری)
        if ($request->filled('classroom_id')) {
            $classroomsQuery->where('id', $request->classroom_id);
        }

        $classrooms = $classroomsQuery->with('students')->get();

        // ✅ یک‌بار آی‌دی کلاس‌های معلم را بگیر (برای performance)
        $teacherClassroomIds = $teacher->teachingClassrooms()->pluck('id');

        // جمع کردن همه دانش‌آموزان متعلق به کلاس‌های معلم
        $studentsQuery = User::query()
            // ✅ DB جدید: نقش از pivot role_user
            ->whereHas('roles', fn($q) => $q->where('slug', 'student'))
            ->whereHas('classrooms', function ($q) use ($teacherClassroomIds) {
                $q->whereIn('classrooms.id', $teacherClassroomIds);
            })
            ->with(['studentProfile', 'classrooms']);

        // جستجو
        if ($request->filled('search')) {
            $search = $request->search;
            $studentsQuery->where(function ($q) use ($search) {
                $q->where('phone', 'like', "%{$search}%");
            });
        }

        $students = $studentsQuery->paginate(10);

        return view('dashboard.teacher.students.index', compact(
            'classrooms', 'students'
        ));

        /*
        |--------------------------------------------------------------------------
        | OLD CODE (kept for rollback)
        |--------------------------------------------------------------------------
        | $classrooms = $teacher->classrooms()
        |     ->with('students')
        |     ->get();
        |
        | $students = $teacher->students()
        |     ->with(['studentProfile', 'classrooms'])
        |     ->paginate(10);
        |
        | این منطق مربوط به pivot قبلی classroom_user بود.
        */
    }

    /**
     * پروفایل دانش‌آموز از دید معلم
     * ----------------------------------------
     * ✅ FIXES:
     * 1) کلاس‌های مشترک فقط کلاس‌های همین معلم هستند
     * 2) Attemptها فقط آزمون‌های همین معلم هستند
     * 3) stats با ستون‌های واقعی attempts محاسبه می‌شود
     */
    public function show(User $student)
    {
        $teacher = auth()->user();

        // امنیت: دانش‌آموز باید عضو یکی از کلاس‌های همین معلم باشد
        $allowed = $teacher->teachingClassrooms()
            ->whereHas('students', fn ($q) => $q->where('users.id', $student->id))
            ->exists();

        abort_unless($allowed, 403);

        // ✅ کلاس‌های مشترک معلم و دانش‌آموز
        $sharedClassrooms = $teacher->teachingClassrooms()
            ->whereHas('students', fn($q) => $q->where('users.id', $student->id))
            ->get();

        // ✅ Attempt های این دانش‌آموز فقط برای آزمون‌های همین معلم
        $attempts = Attempt::where('student_id', $student->id)
            ->whereHas('exam', fn($q) => $q->where('teacher_id', $teacher->id))
            ->with('exam')
            ->latest('submitted_at')
            ->get();

        $latestAttempts = $attempts->take(5);

        // ✅ آمار واقعی (با ستون‌های موجود)
        $stats = [
            'attempts_count' => $attempts->count(),
            'avg_percent'    => round($attempts->avg('percent') ?? 0, 2),
            'avg_score'      => round($attempts->avg('score_obtained') ?? 0, 2),
        ];

        return view('dashboard.teacher.students.show', compact(
            'student', 'sharedClassrooms', 'attempts', 'latestAttempts', 'stats'
        ));

        /*
        |--------------------------------------------------------------------------
        | OLD CODE (kept)
        |--------------------------------------------------------------------------
        | $classrooms = $student->classrooms()->get();
        |
        | $latestAttempts = Attempt::where('student_id', $student->id)
        |     ->with('exam')
        |     ->latest()
        |     ->take(5)
        |     ->get();
        |
        | $statsQuery = Attempt::where('student_id', $student->id)
        |     ->whereNotNull('finished_at');
        |
        | $stats = [
        |     'attempts_count' => (clone $statsQuery)->count(),
        |     'avg_score'      => round((clone $statsQuery)->avg('score') ?? 0, 2), // ❌ score نداریم
        |     'avg_percent'    => round((clone $statsQuery)->avg('percent') ?? 0, 2),
        | ];
        |
        | return view(... compact('student','classrooms','latestAttempts','stats'));
        */
    }

    /**
     * لیست Attemptهای دانش‌آموز
     * ----------------------------------------
     * ✅ FIX: فقط Attemptهای مربوط به آزمون‌های همین معلم
     */
    public function attempts(User $student)
    {
        $teacher = auth()->user();

        $allowed = $teacher->teachingClassrooms()
            ->whereHas('students', fn ($q) => $q->where('users.id', $student->id))
            ->exists();

        abort_unless($allowed, 403);

        $attempts = Attempt::where('student_id', $student->id)
            ->whereHas('exam', fn($q) => $q->where('teacher_id', $teacher->id))
            ->with('exam')
            ->latest('submitted_at')
            ->paginate(10);

        return view('dashboard.teacher.students.attempts', compact('student', 'attempts'));

        /*
        |--------------------------------------------------------------------------
        | OLD CODE (kept)
        |--------------------------------------------------------------------------
        | $attempts = Attempt::where('student_id', $student->id)
        |     ->with('exam')
        |     ->latest()
        |     ->paginate(10);
        */
    }

    /**
     * جزئیات Attempt برای تصحیح
     * ----------------------------------------
     * ✅ FIX: امنیت + لود روابط لازم برای teacher/attempts/show
     */
    public function attemptShow(Attempt $attempt)
    {
        $teacher = auth()->user();

        // امنیت دقیق‌تر: Attempt باید مربوط به آزمون‌های همین معلم باشد
        $allowed = $attempt->exam
            && $attempt->exam->teacher_id == $teacher->id;

        abort_unless($allowed, 403);

        $attempt->load(['student', 'exam.questions', 'answers.question']);

        return view('dashboard.teacher.attempts.show', compact('attempt'));

        /*
        |--------------------------------------------------------------------------
        | OLD SECURITY CHECK (kept)
        |--------------------------------------------------------------------------
        | $allowed = $teacher->teachingClassrooms()
        |   ->whereHas('students', fn ($q) => $q->where('users.id', $attempt->student_id))
        |   ->exists();
        */
    }

    /**
     * ثبت نمره تشریحی برای Attempt
     * ----------------------------------------
     * ✅ FIXES:
     * - استفاده از score_awarded و graded_at
     * - جمع نمره با score_awarded
     * - status: submitted → graded
     */
    public function gradeEssay(Request $request, Attempt $attempt)
    {
        $teacher = auth()->user();

        // Attempt باید متعلق به آزمون همین معلم باشد
        $allowed = $attempt->exam
            && $attempt->exam->teacher_id == $teacher->id;

        abort_unless($allowed, 403);

        $data = $request->validate([
            'scores' => ['required', 'array'],
            'scores.*' => ['nullable', 'numeric', 'min:0'],
            'feedbacks' => ['nullable', 'array'],
            'feedbacks.*' => ['nullable', 'string'],
        ]);

        foreach ($data['scores'] as $answerId => $score) {

            $answer = $attempt->answers()
                ->where('id', $answerId)
                ->with('question')
                ->first();

            if ($answer && $answer->question->type === 'essay') {

                $max = (int)($answer->question->score ?? 0);
                $finalScore = min(max((int)($score ?? 0), 0), $max);

                $answer->update([
                    'score_awarded'    => $finalScore,
                    'teacher_feedback' => $data['feedbacks'][$answerId] ?? $answer->teacher_feedback,
                    'graded_by'        => $teacher->id,
                    'graded_at'        => now(),
                ]);
            }
        }

        // محاسبه مجدد نمره نهایی
        $attempt->refresh();

        $attempt->score_obtained = $attempt->answers()->sum('score_awarded');

        // اگر score_total خالی بود، از سوالات حساب کن
        if (!$attempt->score_total) {
            $attempt->score_total = $attempt->exam->questions()->sum('score');
        }

        $attempt->percent = $attempt->score_total > 0
            ? round(($attempt->score_obtained / $attempt->score_total) * 100, 2)
            : 0;

        // آیا هنوز essay بدون تصحیح داریم؟
        $hasUngradedEssay = $attempt->answers()
            ->whereHas('question', fn($q) => $q->where('type', 'essay'))
            ->whereNull('graded_at')
            ->exists();

        $attempt->status = $hasUngradedEssay ? 'submitted' : 'graded';

        if (!$hasUngradedEssay && !$attempt->finished_at) {
            $attempt->finished_at = now();
        }

        $attempt->save();

        return redirect()
            ->back()
            ->with('success', 'نمره‌های تشریحی ثبت شد و Attempt بروزرسانی شد.');
    }

    /**
     * Grade single essay answer (route: teacher.attempts.answers.grade)
     */
    public function gradeEssayAnswer(Request $request, Attempt $attempt, AttemptAnswer $answer)
    {
        $teacher = auth()->user();

        // امنیت: این Attempt متعلق به معلم باشد
        abort_unless($attempt->exam && $attempt->exam->teacher_id == $teacher->id, 403);

        // امنیت: answer باید متعلق به همین attempt باشد
        abort_unless($answer->attempt_id == $attempt->id, 403);

        $data = $request->validate([
            'score_awarded'    => ['required','numeric','min:0'],
            'teacher_feedback' => ['nullable','string'],
        ]);

        // فقط برای essay
        abort_unless($answer->question && $answer->question->type === 'essay', 403);

        $max = (int)($answer->question->score ?? 0);
        $finalScore = min(max((int)$data['score_awarded'], 0), $max);

        $answer->update([
            'score_awarded'    => $finalScore,
            'teacher_feedback' => $data['teacher_feedback'] ?? $answer->teacher_feedback,
            'graded_by'        => $teacher->id,
            'graded_at'        => now(),
            'is_correct'       => null,
        ]);

        // آپدیت Attempt بعد از هر تصحیح
        $attempt->refresh();

        $attempt->score_obtained = $attempt->answers()->sum('score_awarded');

        if (!$attempt->score_total) {
            $attempt->score_total = $attempt->exam->questions()->sum('score');
        }

        $attempt->percent = $attempt->score_total > 0
            ? round(($attempt->score_obtained / $attempt->score_total) * 100, 2)
            : 0;

        $hasUngradedEssay = $attempt->answers()
            ->whereHas('question', fn($q) => $q->where('type','essay'))
            ->whereNull('graded_at')
            ->exists();

        $attempt->status = $hasUngradedEssay ? 'submitted' : 'graded';

        if (!$hasUngradedEssay && !$attempt->finished_at) {
            $attempt->finished_at = now();
        }

        $attempt->save();

        return back()->with('success', 'نمره تشریحی ثبت شد و نتیجه بروزرسانی شد.');
    }
}
