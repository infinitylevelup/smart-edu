<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Exam;
use App\Models\Attempt;
use App\Models\Question;

class PublicExamController extends Controller
{
    // 1) لیست آزمون‌های free
    public function index()
    {
        $exams = Exam::query()
            ->where('scope', 'free')
            ->where('is_published', true)
            ->where('is_active', true)
            ->withCount('questions')
            ->latest()
            ->paginate(12);

        return view('dashboard.student.exams.free.index', compact('exams'));
    }

    // 2) نمایش صفحه آزمون (سوال‌ها)
    public function show(Exam $exam)
    {
        abort_unless($exam->scope === 'free', 404);
        abort_unless($exam->is_published && $exam->is_active, 403);

        $questions = $exam->questions()->where('is_active', true)->get();

        // اگر attempt قبلاً submitted شده باشد، مستقیم نتیجه را نشان بده
        $attempt = Attempt::query()
            ->where('exam_id', $exam->id)
            ->where('student_id', auth()->id())
            ->latest()
            ->first();

        if ($attempt && $attempt->isFinal()) {
            return redirect()->route('student.exams.free.result', [$exam->id, $attempt->id]);
        }

        return view('dashboard.student.exams.free.show', compact('exam', 'questions'));
    }

    // 3) شروع attempt (اگر خواستی دکمه Start رسمی داشته باشی)
    public function start(Exam $exam)
    {
        abort_unless($exam->scope === 'free', 404);

        $attempt = Attempt::create([
            'exam_id' => $exam->id,
            'student_id' => auth()->id(),
            'status' => 'in_progress',
            'started_at' => now(),
        ]);

        return redirect()->route('student.exams.free.show', $exam->id);
    }

    // 4) submit پاسخ‌ها + محاسبه نمره
    public function submit(Request $request, Exam $exam)
    {
        abort_unless($exam->scope === 'free', 404);

        $questions = $exam->questions()->where('is_active', true)->get();

        // attempt جاری یا جدید
        $attempt = Attempt::query()->firstOrCreate(
            [
                'exam_id' => $exam->id,
                'student_id' => auth()->id(),
                'status' => 'in_progress',
            ],
            [
                'started_at' => now(),
            ]
        );

        $answers = [];
        $scoreObtained = 0;
        $scoreTotal = 0;

        foreach ($questions as $q) {
            $scoreTotal += (int)($q->score ?? 1);

            $key = "q_{$q->id}";

            if ($q->type === 'mcq') {
                $ans = $request->input($key); // a/b/c/d
                if ($ans) {
                    $answers[$q->id] = $ans;

                    // correct_option legacy یا correct_answer جدید
                    $correct = $q->correct_option ?? (is_array($q->correct_answer) ? $q->correct_answer[0] : null);
                    if ($correct && $ans === $correct) {
                        $scoreObtained += (int)($q->score ?? 1);
                    }
                }
            }

            elseif ($q->type === 'true_false') {
                // می‌آید به صورت "true"/"false"
                $ansRaw = $request->input($key);
                if ($ansRaw !== null) {
                    $ans = filter_var($ansRaw, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                    $answers[$q->id] = $ans;

                    if (!is_null($q->correct_tf) && $ans === (bool)$q->correct_tf) {
                        $scoreObtained += (int)($q->score ?? 1);
                    }
                }
            }
        }

        $percent = $scoreTotal > 0 ? round(($scoreObtained / $scoreTotal) * 100, 2) : 0;

        $attempt->update([
            'answers' => $answers,
            'status' => 'submitted',
            'submitted_at' => now(),
            'finished_at' => now(),
            'score_obtained' => $scoreObtained,
            'score_total' => $scoreTotal,
            'percent' => $percent,
            'score' => $scoreObtained, // ستون score decimal
        ]);

        return redirect()->route('student.exams.free.result', [$exam->id, $attempt->id]);
    }

    // 5) صفحه نتیجه
    public function result(Exam $exam, Attempt $attempt)
    {
        abort_unless($exam->id === $attempt->exam_id, 404);
        abort_unless($attempt->student_id === auth()->id(), 403);

        $questions = $exam->questions()->where('is_active', true)->get();

        return view('dashboard.student.exams.free.result', compact('exam','attempt','questions'));
    }
}
