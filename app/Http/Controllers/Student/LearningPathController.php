<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Attempt;
use App\Models\Exam;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class LearningPathController extends Controller
{
    public function index()
    {
        $student = Auth::user();

        // ==============================
        // 1) آخرین Attempt های دانش‌آموز
        // ==============================
        $attempts = Attempt::query()
            ->with([
                'exam.subject',
                'answers.question',
            ])
            ->where('student_id', $student->id)
            ->whereIn('status', ['submitted', 'graded', 'finished'])
            ->orderByDesc('finished_at')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        // اگر هنوز آزمونی نداده
        if ($attempts->isEmpty()) {
            $overallPercent = 0;
            $currentLevel = 1;
            $levelProgress = 0;
            $focusTopics = ['شروع آزمون اول', 'آشنایی با روند سوالات', 'مدیریت زمان'];
            $recommendedLevel = 'taghviyati';

            $suggestedExams = $this->suggestDefaultExams($student->id);

            return view('dashboard.student.learning-path', compact(
                'overallPercent',
                'currentLevel',
                'levelProgress',
                'focusTopics',
                'recommendedLevel',
                'suggestedExams'
            ));
        }

        // ==============================
        // 2) محاسبات پیشرفت و Level
        // ==============================
        $overallPercent = round($attempts->avg(fn ($a) => (float) ($a->percent ?? 0)));

        $currentLevel = max(1, (int) floor($overallPercent / 25) + 1);
        $currentLevel = min($currentLevel, 5);

        $levelProgress = $overallPercent % 25;
        $levelProgress = round(($levelProgress / 25) * 100);

        // ==============================
        // 3) استخراج نقاط ضعف
        // ==============================
        $wrongAnswers = $attempts
            ->flatMap(fn ($a) => $a->answers()->get())   // ✅ FIX HERE
            ->filter(fn ($ans) => (int) $ans->is_correct === 0);
        // ->values();
        $weakSubjects = $wrongAnswers
            ->groupBy(fn ($ans) => optional($ans->question->exam->subject)->title
                ?? optional($ans->question->exam->subject)->name
                ?? 'بدون درس'
            )
            ->map->count()
            ->sortDesc()
            ->keys()
            ->take(2)
            ->values()
            ->all();

        $weakTypes = $wrongAnswers
            ->groupBy(fn ($ans) => $ans->question->type ?? 'unknown')
            ->map->count()
            ->sortDesc()
            ->keys()
            ->take(2)
            ->values()
            ->map(function ($t) {
                return match ($t) {
                    'mcq' => 'تست‌های ۴ گزینه‌ای',
                    'true_false' => 'صحیح/غلط',
                    'fill_blank' => 'جای خالی',
                    'essay' => 'تشریحی',
                    default => 'سوالات ترکیبی',
                };
            })
            ->all();

        $weakDifficulties = $wrongAnswers
            ->groupBy(fn ($ans) => $ans->question->difficulty ?? 'medium')
            ->map->count()
            ->sortDesc()
            ->keys()
            ->take(1)
            ->values()
            ->map(function ($d) {
                return match ($d) {
                    'easy' => 'سوالات ساده (تمرکز روی دقت)',
                    'hard' => 'سوالات سخت (نیاز به تمرین بیشتر)',
                    default => 'سوالات متوسط (تقویت پایه)',
                };
            })
            ->all();

        $focusTopics = array_values(array_unique(array_filter([
            ...array_map(fn ($s) => 'ضعف در درس: '.$s, $weakSubjects),
            ...array_map(fn ($t) => 'تقویت مهارت: '.$t, $weakTypes),
            ...$weakDifficulties,
        ])));

        $focusTopics = array_slice($focusTopics, 0, 3);

        if (count($focusTopics) < 3) {
            $focusTopics = array_merge($focusTopics, [
                'مدیریت زمان در آزمون',
                'مرور توضیحات سوالات غلط',
                'تکرار آزمون‌های کوتاه',
            ]);
            $focusTopics = array_slice($focusTopics, 0, 3);
        }

        // ==============================
        // 4) پیشنهاد سطح کلی
        // ==============================
        $recommendedLevel = match (true) {
            $overallPercent >= 85 => 'olympiad',
            $overallPercent >= 60 => 'konkur',
            default => 'taghviyati',
        };

        // ==============================
        // 5) 3 آزمون پیشنهادی واقعی از DB
        // ==============================
        $suggestedExams = $this->suggestNextExams(
            studentId: $student->id,
            attempts: $attempts,
            overallPercent: $overallPercent,
            weakSubjects: $weakSubjects
        );

        return view('dashboard.student.learning-path', compact(
            'overallPercent',
            'currentLevel',
            'levelProgress',
            'focusTopics',
            'recommendedLevel',
            'suggestedExams'
        ));
    }

    /**
     * پیشنهاد 3 آزمون:
     * 1) سخت‌تر / سطح بالاتر
     * 2) مشابه برای رکورد
     * 3) کوتاه تقویتی
     */
    private function suggestNextExams(int $studentId, $attempts, int $overallPercent, array $weakSubjects)
    {
        $user = Auth::user();

        $classroomIds = $user->classrooms?->pluck('id') ?? collect();

        $doneExamIds = Attempt::where('student_id', $studentId)
            ->whereIn('status', ['submitted', 'graded', 'finished'])
            ->pluck('exam_id')
            ->unique();

        // ✅ base مقاوم
        $base = Exam::query()
            ->withCount('questions')
            ->where('is_published', 1)
            ->where('is_active', 1)
            ->whereNotIn('id', $doneExamIds)
            ->when(Schema::hasColumn('exams', 'start_at'), function ($q) {
                $q->where(function ($x) {
                    $x->whereNull('start_at')
                        ->orWhere('start_at', '<=', now());
                });
            })
            ->where(function ($q) use ($classroomIds) {

                if (Schema::hasColumn('exams', 'scope')) {
                    $q->whereIn('scope', ['free', 'public', 'general', 'all'])
                        ->orWhereNull('scope');
                } else {
                    $q->whereRaw('1=1');
                }

                if ($classroomIds->isNotEmpty() && Schema::hasColumn('exams', 'classroom_id')) {
                    $q->orWhereIn('classroom_id', $classroomIds);
                }
            });

        $lastExam = $attempts->first()?->exam;
        $lastLevel = $lastExam->level ?? null;
        $lastSubjectId = $lastExam->subject_id ?? null;

        // 1) سخت‌تر
        $harder = (clone $base)
            ->when(true, function ($q) use ($overallPercent) {
                if ($overallPercent >= 85) {
                    $q->where('level', 'olympiad');
                } elseif ($overallPercent >= 60) {
                    $q->where('level', 'konkur');
                } else {
                    $q->where('level', 'taghviyati');
                }
            })
            ->when($lastSubjectId, fn ($q) => $q->where('subject_id', $lastSubjectId))
            ->latest()
            ->first();

        // 2) مشابه
        $similar = (clone $base)
            ->when($lastLevel, fn ($q) => $q->where('level', $lastLevel))
            ->when($lastSubjectId, fn ($q) => $q->where('subject_id', $lastSubjectId))
            ->latest()
            ->first();

        // 3) کوتاه
        $short = (clone $base)
            ->where('level', 'taghviyati')
            ->orderBy('duration', 'asc')
            ->first();

        // اگر ضعف درس داریم
        if (! empty($weakSubjects)) {
            $weakExam = (clone $base)
                ->whereHas('subject', function ($q) use ($weakSubjects) {
                    $q->where(function ($qq) use ($weakSubjects) {
                        $qq->whereIn('title', $weakSubjects);
                    });
                })

                ->latest()
                ->first();

            $harder ??= $weakExam;
            $similar ??= $weakExam;
            $short ??= $weakExam;
        }

        $out = collect([$harder, $similar, $short])
            ->filter()
            ->unique('id')
            ->values();

        // ✅ اگر هنوز کمتر از 3 بود، از هرچی هست پر کن
        if ($out->count() < 3) {
            $fill = (clone $base)
                ->latest()
                ->take(3 - $out->count())
                ->get();

            $out = $out->concat($fill)->unique('id')->values();
        }

        return $out;
    }

    /**
     * پیشنهاد پیش‌فرض برای دانش‌آموز تازه‌وارد
     */
    private function suggestDefaultExams(int $studentId)
    {
        return Exam::query()
            ->withCount('questions')
            ->where('scope', 'free')
            ->where('is_published', 1)
            ->where('is_active', 1)
            ->orderBy('questions_count', 'asc')
            ->orderByDesc('created_at')
            ->take(3)
            ->get();
    }
}
