<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Attempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentExamController extends Controller
{
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
        // ✅ attempt های همین دانش آموز برای هر آزمون
        ->with(['attempts' => function ($q) use ($student) {
            $q->where('student_id', $student->id)
              ->latest(); // آخرین attempt اول میاد
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

    return view('dashboard.student.exams.index', compact('exams'));
}

    /**
     * ✅ جزئیات آزمون
     * - اگر قبلاً attempt نهایی داشته باشد: فقط هشدار + دکمه مشاهده نتیجه
     * - اگر نداشته باشد: دکمه شروع آزمون دیده می‌شود
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
     * ✅ شروع آزمون
     * - اگر قبلاً attempt نهایی داشته باشد اجازه شروع مجدد نمی‌دهد
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

    public function submit(Request $request, Exam $exam)
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

            $exam->load('questions');
            $answersArray = $request->answers;

            $scoreObtained = 0;
            $scoreTotal    = 0;

            foreach ($exam->questions as $question) {

                $qid = $question->id;
                $studentAnswer = $answersArray[$qid] ?? null;

                $qScore = (int)($question->score ?? 1);
                $scoreTotal += $qScore;

                $isCorrect = null;
                $awarded   = 0;

                switch ($question->type) {
                    case 'mcq':
                        if ($studentAnswer !== null && $question->correct_option) {
                            $isCorrect = ((string)$studentAnswer === (string)$question->correct_option);
                            if ($isCorrect) $awarded = $qScore;
                        }
                        break;

                    case 'true_false':
                        if ($studentAnswer !== null && !is_null($question->correct_tf)) {
                            $normalized = filter_var($studentAnswer, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                            $isCorrect = ($normalized === (bool)$question->correct_tf);
                            if ($isCorrect) $awarded = $qScore;
                        }
                        break;

                    case 'fill_blank':
                        if ($studentAnswer !== null && $question->correct_answer) {
                            $correct = $question->correct_answer;
                            if (is_string($correct)) {
                                $decoded = json_decode($correct, true);
                                $correct = $decoded ?? $correct;
                            }

                            $stu = $studentAnswer;
                            if (is_string($stu)) {
                                $stu = array_values(array_filter(array_map('trim', explode(',', $stu))));
                            }

                            $isCorrect = ($stu == $correct);
                            if ($isCorrect) $awarded = $qScore;
                        }
                        break;

                    case 'essay':
                        $isCorrect = null;
                        $awarded = 0;
                        break;
                }

                $scoreObtained += $awarded;

                $attempt->answers()->updateOrCreate(
                    ['question_id' => $qid],
                    [
                        'answer'        => $studentAnswer,
                        'is_correct'    => $isCorrect,
                        'score_awarded' => $awarded,
                    ]
                );
            }

            $percent = $scoreTotal > 0
                ? round(($scoreObtained / $scoreTotal) * 100, 2)
                : 0;

            $attempt->update([
                'answers'        => $answersArray,
                'score'          => $scoreObtained,
                'percent'        => $percent,
                'finished_at'    => now(),
                'status'         => 'submitted',
                'score_total'    => $scoreTotal,
                'score_obtained' => $scoreObtained,
            ]);
        });

        return redirect()
            ->route('student.attempts.show', $attempt->id)
            ->with('success', 'پاسخ‌ها با موفقیت ثبت شد.');
    }


public function attemptShow(Attempt $attempt)
{
    $student = Auth::user();
    abort_unless($attempt->student_id == $student->id, 403);

    // اگر توی result صفحه سوال‌ها و جواب‌ها و توضیحات رو می‌خوای
    $attempt->load([
        'exam.questions',      // برای $exam و سوال‌ها
        'answers.question'     // برای attemptAnswers
    ]);

    $exam = $attempt->exam;

    return view('dashboard.student.exams.result', [
        'attempt' => $attempt,
        'exam' => $exam,  // ✅ این خط مشکل Undefined variable $exam رو حل می‌کنه
        'attemptAnswers' => $attempt->answers
    ]);
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
     * ✅ Attempt نهایی یعنی کاربر یکبار آزمون را ارسال کرده
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
