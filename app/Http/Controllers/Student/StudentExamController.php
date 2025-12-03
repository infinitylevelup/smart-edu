<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Attempt;
use App\Models\AttemptAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\GamificationService;

class StudentExamController extends Controller
{
    /**
     * Legacy index (backward compatible)
     * Shows free + classroom exams (only joined classrooms)
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
                                $pub->whereNull('is_published')->orWhere('is_published', true);
                            })
                            ->where(function ($act) {
                                $act->whereNull('is_active')->orWhere('is_active', true);
                            });
                    });
            })
            ->with('classroom')
            ->withCount('questions')
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

        $classrooms = $student->classrooms()->get();

        if ($request->filled('classroom_id')) {
            return view('dashboard.student.exams.classroom', compact('exams', 'classrooms'));
        }

        return view('dashboard.student.exams.public', compact('exams', 'classrooms'));
    }

    /**
     * Public exams list
     */
    public function publicIndex(Request $request)
    {
        $student = Auth::user();

        $examsQuery = Exam::query()
            ->where('scope', 'free')
            ->with('classroom')
            ->withCount('questions')
            ->with(['attempts' => function ($q) use ($student) {
                $q->where('student_id', $student->id)->latest();
            }])
            ->latest();

        $exams = $examsQuery->paginate(9)->withQueryString();

        return view('dashboard.student.exams.public', compact('exams'));
    }

    /**
     * Classroom exams list (joined classrooms only)
     */
    public function classroomIndex(Request $request)
    {
        $student = Auth::user();
        $classroomIds = $student->classrooms()->pluck('classrooms.id');

        $examsQuery = Exam::query()
            ->where('scope', 'classroom')
            ->whereIn('classroom_id', $classroomIds)
            ->where(function ($pub) {
                $pub->whereNull('is_published')->orWhere('is_published', true);
            })
            ->where(function ($act) {
                $act->whereNull('is_active')->orWhere('is_active', true);
            })
            ->with('classroom')
            ->withCount('questions')
            ->with(['attempts' => function ($q) use ($student) {
                $q->where('student_id', $student->id)->latest();
            }])
            ->latest();

        if ($request->filled('classroom_id')) {
            $examsQuery->where('classroom_id', $request->classroom_id);
        }

        $exams = $examsQuery->paginate(9)->withQueryString();
        $classrooms = $student->classrooms()->get();

        return view('dashboard.student.exams.classroom', compact('exams', 'classrooms'));
    }

    /**
     * Exam details
     */
    public function show(Exam $exam)
    {
        $this->authorizeExamForStudent($exam);

        $exam->load(['classroom', 'questions']);
        $exam->difficulty = $this->normalizeDifficulty($exam->difficulty);

        $student = Auth::user();

        $lastAttempt = Attempt::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->latest()
            ->first();

        $isFinalAttempt = $this->isFinalAttempt($lastAttempt);

        return view('dashboard.student.exams.show', compact('exam', 'lastAttempt', 'isFinalAttempt'));
    }

    /**
     * Take exam page
     */
    public function take(Exam $exam)
    {
        $this->authorizeExamForStudent($exam);

        $student = Auth::user();
        $exam->load('questions');

        if (empty($exam->duration_minutes) && !empty($exam->duration)) {
            $exam->duration_minutes = $exam->duration;
        }

        $attempt = Attempt::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->whereNull('finished_at')
            ->latest()
            ->first();

        return view('dashboard.student.exams.take', compact('exam', 'attempt'));
    }

    /**
     * Start exam
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
                'exam_id'        => $exam->id,
                'student_id'     => $student->id,
                'answers'        => [],
                'score'          => 0,
                'percent'        => 0,
                'score_total'    => 0,
                'score_obtained' => 0,
                'started_at'     => now(),
                'finished_at'    => null,
                'submitted_at'   => null,
                'status'         => 'in_progress',
            ]);
        }

        // ✅ CHANGED: بهتره route model binding استفاده بشه
        return redirect()->route('student.exams.take', $exam);
        // قبلی: return redirect()->route('student.exams.take', $exam->id);
    }

    /**
     * Submit exam answers + grading
     */
    public function submit(Request $request, Exam $exam, GamificationService $gamification)
    {
        $this->authorizeExamForStudent($exam);

        $request->validate([
            'answers' => ['required', 'array'],
        ]);

        $student = Auth::user();
        $exam->load('questions');

        $attempt = Attempt::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->whereNull('finished_at')
            ->latest()
            ->firstOrFail();

        DB::transaction(function () use ($request, $exam, $attempt) {
            AttemptAnswer::where('attempt_id', $attempt->id)->delete();

            $answersInput = $request->input('answers', []);

            $scoreTotal = 0;
            $scoreObtained = 0;

            foreach ($exam->questions as $q) {
                $qScore = (int) ($q->score ?? 1);
                $scoreTotal += $qScore;

                $studentAnswer = $answersInput[$q->id] ?? null;

                $grading = $this->gradeQuestion($q, $studentAnswer, $qScore);

                AttemptAnswer::create([
                    'attempt_id'    => $attempt->id,
                    'question_id'   => $q->id,
                    'answer'        => $studentAnswer,
                    'is_correct'    => $grading['is_correct'],
                    'score_awarded' => $grading['score_awarded'],
                ]);

                $scoreObtained += $grading['score_awarded'];
            }

            $percent = $scoreTotal > 0 ? round(($scoreObtained / $scoreTotal) * 100, 2) : 0;

            $attempt->update([
                'answers'        => $answersInput,

                'score_total'    => $scoreTotal,
                'score_obtained' => $scoreObtained,

                'score'          => $scoreObtained,
                'percent'        => $percent,

                'submitted_at'   => now(),
                'finished_at'    => now(),
                'status'         => 'submitted',
            ]);
        });

        $attempt->refresh();

        $baseXp = $exam->scope === 'free' ? 20 : 25;
        $scoreXp = (int) floor(((float) $attempt->percent) / 5);
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
     * Attempt Result page
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

        // ✅ CHANGED: به‌صورت صریح رابطه answers را بگیر
        // چون ستون JSON و رابطه همنام‌اند و ممکنه باگ رندوم بده
        $attemptAnswerModels = $attempt->getRelation('answers');

        return view('dashboard.student.attempts.result', [
            'attempt' => $attempt,
            'exam' => $exam,
            'attemptAnswers' => $attemptAnswerModels, // ✅ رابطه واقعی
            'legacyAnswers' => $attempt->getAttribute('answers'), // ✅ در صورت نیاز به JSON
        ]);
    }

    /**
     * Attempt Analysis (Phase 1 stub)
     */
    public function analysis(Attempt $attempt)
    {
        $student = Auth::user();
        abort_unless($attempt->student_id == $student->id, 403);

        $attempt->load([
            'exam',
            'answers.question'
        ]);

        $analysis = null;

        return view('dashboard.student.attempts.analysis', [
            'attempt' => $attempt,
            'exam' => $attempt->exam,
            'analysis' => $analysis,
        ]);
    }

    /**
     * Legacy attempt show → redirect to result
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
     * Final attempt means student already submitted once
     */
    private function isFinalAttempt(?Attempt $attempt): bool
    {
        if (!$attempt) return false;

        return
            !is_null($attempt->finished_at) ||
            !is_null($attempt->submitted_at) ||
            in_array($attempt->status ?? null, ['submitted', 'graded']);
    }

    /**
     * Normalize difficulty to easy|medium|hard
     */
    private function normalizeDifficulty(?string $difficulty): string
    {
        $difficulty = strtolower(trim((string) $difficulty));

        return match ($difficulty) {
            'easy', 'low', 'simple' => 'easy',
            'hard', 'high', 'difficult' => 'hard',
            default => 'medium',
        };
    }

    /**
     * Grade a single question
     */
    private function gradeQuestion($q, $studentAnswer, int $qScore): array
    {
        $type = $q->type ?? 'mcq';

        $correctOption = $q->correct_option ?? null;
        $correctAnswer = $q->correct_answer ?? null;

        $isCorrect = null;
        $awarded = 0;

        if ($type === 'essay') {
            $isCorrect = null;
            $awarded = 0;

        } elseif ($type === 'mcq') {
            $studentKey = is_array($studentAnswer) ? ($studentAnswer[0] ?? null) : $studentAnswer;

            if (!empty($correctOption)) {
                $isCorrect = strtolower((string) $studentKey) === strtolower((string) $correctOption);
            } else {
                $correctArr = is_string($correctAnswer) ? json_decode($correctAnswer, true) : $correctAnswer;
                $correctArr = is_array($correctArr) ? $correctArr : [$correctArr];
                $isCorrect = in_array(
                    strtolower((string) $studentKey),
                    array_map(fn($v)=>strtolower((string)$v), $correctArr)
                );
            }
            $awarded = $isCorrect ? $qScore : 0;

        } elseif ($type === 'true_false') {

            // ✅ CHANGED: سازگاری کامل با boolean و correct_tf
            $studentVal = $studentAnswer;
            if (is_string($studentVal)) $studentVal = strtolower($studentVal);
            if ($studentVal === 'true') $studentVal = true;
            if ($studentVal === 'false') $studentVal = false;

            // اولویت با correct_tf اگر موجود بود
            if (!is_null($q->correct_tf)) {
                $correctVal = (bool) $q->correct_tf;
            } else {
                $correctVal = $correctAnswer;
                if (is_string($correctVal)) {
                    $correctVal = strtolower($correctVal);
                    $correctVal = $correctVal === 'true';
                }
            }

            $isCorrect = (bool)$studentVal === (bool)$correctVal;
            $awarded = $isCorrect ? $qScore : 0;

        } elseif ($type === 'fill_blank') {
            $studentText = is_array($studentAnswer) ? implode(',', $studentAnswer) : (string)$studentAnswer;
            $studentParts = array_filter(array_map(fn($v)=>trim(mb_strtolower($v)), explode(',', $studentText)));

            $correctArr = is_string($correctAnswer) ? json_decode($correctAnswer, true) : $correctAnswer;
            $correctArr = is_array($correctArr) ? $correctArr : [$correctArr];
            $correctArr = array_filter(array_map(fn($v)=>trim(mb_strtolower((string)$v)), $correctArr));

            $isCorrect = false;
            foreach ($studentParts as $sp) {
                if (in_array($sp, $correctArr, true)) {
                    $isCorrect = true;
                    break;
                }
            }

            $awarded = $isCorrect ? $qScore : 0;

        } else {
            $isCorrect = false;
            $awarded = 0;
        }

        return [
            'is_correct' => $isCorrect,
            'score_awarded' => $awarded,
        ];
    }
}
