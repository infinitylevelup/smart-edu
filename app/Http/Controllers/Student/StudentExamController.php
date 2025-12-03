<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Attempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\GamificationService;

class StudentExamController extends Controller
{
    /**
     * Legacy index (kept for backward compatibility)
     * Shows both free + classroom exams (joined classrooms only)
     */
    public function index(Request $request)
    {
        $student = Auth::user();
        $classroomIds = $student->classrooms()->pluck('classrooms.id');

        $examsQuery = Exam::query()
            ->where(function ($q) use ($classroomIds) {
                $q->where('scope', 'free')
                  ->orWhere(function ($q2) use ($classroomIds) {
                      $q2->where('scope', 'classroom')
                         ->whereIn('classroom_id', $classroomIds)
                         ->where(function ($pub) {
                             $pub->whereNull('is_published')
                                 ->orWhere('is_published', true);
                         })
                         ->where(function ($act) {
                             $act->whereNull('is_active')
                                 ->orWhere('is_active', true);
                         });
                  });
            })
            ->with('classroom')
            ->with(['attempts' => function ($q) use ($student) {
                $q->where('student_id', $student->id)->latest();
            }])
            ->latest();

        if ($request->filled('classroom_id')) {
            $examsQuery->where(function ($q) use ($request) {
                $q->where('scope', 'free')
                  ->orWhere(function ($q2) use ($request) {
                      $q2->where('scope', 'classroom')
                         ->where('classroom_id', $request->classroom_id);
                  });
            });
        }

        $exams = $examsQuery->paginate(9)->withQueryString();

        // ✅ چون توی public.blade.php ازش استفاده کردی
        $classrooms = $student->classrooms()->get();

        // ✅ اگر فیلتر کلاس انتخاب شده → صفحه classroom
        if ($request->filled('classroom_id')) {
            return view('dashboard.student.exams.classroom', compact('exams', 'classrooms'));
        }

        // ✅ در غیر این صورت → صفحه public
        return view('dashboard.student.exams.public', compact('exams', 'classrooms'));
    }

    /**
     * ✅ NEW: Public exams list (no class membership required)
     */
    public function publicIndex(Request $request)
    {
        $student = Auth::user();

        $examsQuery = Exam::query()
            ->where('scope', 'free')
            ->with('classroom')
            ->with(['attempts' => function ($q) use ($student) {
                $q->where('student_id', $student->id)->latest();
            }])
            ->latest();

        $exams = $examsQuery->paginate(9)->withQueryString();

        return view('dashboard.student.exams.public', compact('exams'));
    }

    /**
     * ✅ NEW: Classroom exams list (only exams for joined classrooms)
     */
public function classroomIndex(Request $request)
{
    $student = Auth::user();
    $classroomIds = $student->classrooms()->pluck('classrooms.id');

    $examsQuery = Exam::query()
        ->where('scope', 'classroom')
        ->whereIn('classroom_id', $classroomIds)
        ->where(function ($pub) {
            $pub->whereNull('is_published')
                ->orWhere('is_published', true);
        })
        ->where(function ($act) {
            $act->whereNull('is_active')
                ->orWhere('is_active', true);
        })
        ->with('classroom')
        ->with(['attempts' => function ($q) use ($student) {
            $q->where('student_id', $student->id)->latest();
        }])
        ->latest();

    if ($request->filled('classroom_id')) {
        $examsQuery->where('classroom_id', $request->classroom_id);
    }

    $exams = $examsQuery->paginate(9)->withQueryString();

    // ✅ اینجا اضافه میشه
    $classrooms = $student->classrooms()->get();

    return view('dashboard.student.exams.classroom', compact('exams', 'classrooms'));
}


    /**
     * ✅ Exam details
     */
    public function show(Exam $exam)
    {
        $this->authorizeExamForStudent($exam);

        $exam->load(['classroom', 'questions']);

        $student = Auth::user();

        $lastAttempt = Attempt::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->latest()
            ->first();

        $isFinalAttempt = $this->isFinalAttempt($lastAttempt);

        return view('dashboard.student.exams.show', compact('exam', 'lastAttempt', 'isFinalAttempt'));
    }

    public function take(Exam $exam)
    {
        $this->authorizeExamForStudent($exam);

        $student = Auth::user();
        $exam->load('questions');

        $attempt = Attempt::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->whereNull('finished_at')
            ->latest()
            ->first();

        return view('dashboard.student.exams.take', compact('exam', 'attempt'));
    }

    /**
     * ✅ Start exam
     */
    public function start(Exam $exam)
    {
        $this->authorizeExamForStudent($exam);

        $student = Auth::user();

        $lastAttempt = Attempt::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->latest()
            ->first();

        if ($this->isFinalAttempt($lastAttempt)) {
            return redirect()
                ->route('student.exams.show', $exam)
                ->with('warning', 'شما قبلاً در این آزمون شرکت کرده‌اید و امکان برگزاری مجدد وجود ندارد.');
        }

        $attempt = Attempt::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->whereNull('finished_at')
            ->latest()
            ->first();

        if (!$attempt) {
            $attempt = Attempt::create([
                'exam_id'     => $exam->id,
                'student_id'  => $student->id,
                'answers'     => [],
                'score'       => 0,
                'percent'     => 0,
                'started_at'  => now(),
                'finished_at' => null,
                'status'      => 'in_progress',
            ]);
        }

        return redirect()->route('student.exams.take', $exam->id);
    }

    public function submit(Request $request, Exam $exam, GamificationService $gamification)
    {
        $this->authorizeExamForStudent($exam);

        $request->validate([
            'answers' => ['required', 'array'],
        ]);

        $student = Auth::user();

        $attempt = Attempt::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->whereNull('finished_at')
            ->latest()
            ->firstOrFail();

        DB::transaction(function () use ($request, $exam, $attempt) {
            // ... کل منطق فعلی تو بدون تغییر
        });

        // =========================
        // ✅ Gamification Hook (Phase A)
        // =========================
        $attempt->refresh(); // چون داخل transaction آپدیت شده

        $baseXp = $exam->scope === 'free' ? 20 : 25;     // آزمون عمومی/کلاسی
        $scoreXp = (int) floor($attempt->percent / 5);  // هر 5% = 1 XP
        $totalAward = $baseXp + $scoreXp;

        $gamification->awardXp(
            $student,
            'exam',
            $exam->id,
            $totalAward
        );

        return redirect()
            ->route('student.attempts.result', $attempt->id)
            ->with('success', 'پاسخ‌ها با موفقیت ثبت شد.');
    }

    /**
     * ✅ NEW: Attempt Result page
     */
    public function result(Attempt $attempt)
    {
        $student = Auth::user();
        abort_unless($attempt->student_id == $student->id, 403);

        $attempt->load([
            'exam.questions',
            'answers.question'
        ]);

        $exam = $attempt->exam;

        return view('dashboard.student.attempts.result', [
            'attempt' => $attempt,
            'exam' => $exam,
            'attemptAnswers' => $attempt->answers
        ]);
    }

    /**
     * ✅ NEW: Attempt Analysis page (Phase 1 stub)
     * Later you will load academic + developmental analysis here.
     */
    public function analysis(Attempt $attempt)
    {
        $student = Auth::user();
        abort_unless($attempt->student_id == $student->id, 403);

        $attempt->load([
            'exam',
            'answers.question'
        ]);

        // TODO Phase 2/3: load real analysis models
        $analysis = null;

        return view('dashboard.student.attempts.analysis', [
            'attempt' => $attempt,
            'exam' => $attempt->exam,
            'analysis' => $analysis,
        ]);
    }

    /**
     * Legacy attempt page (keep old links working)
     * Redirect to new result page.
     */
    public function attemptShow(Attempt $attempt)
    {
        return redirect()->route('student.attempts.result', $attempt->id);
    }

    protected function authorizeExamForStudent(Exam $exam): void
    {
        $student = Auth::user();

        if ($exam->scope === 'free') {
            return;
        }

        $isMember = $student->classrooms()
            ->where('classroom_id', $exam->classroom_id)
            ->exists();

        abort_unless($isMember, 403, 'شما عضو این کلاس نیستید.');
    }

    /**
     * ✅ Final attempt means student already submitted once
     */
    private function isFinalAttempt(?Attempt $attempt): bool
    {
        if (!$attempt) return false;

        return
            !is_null($attempt->finished_at) ||
            !is_null($attempt->submitted_at) ||
            in_array($attempt->status ?? null, ['submitted', 'graded']);
    }
}
