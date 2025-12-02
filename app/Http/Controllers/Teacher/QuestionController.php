<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    // لیست سوالات یک آزمون
    public function index(Exam $exam)
    {
        $exam->load('classroom');

        $questions = $exam->questions()->latest()->get();
        return view('dashboard.teacher.exams.questions', compact('exam','questions'));
    }

    // فرم ایجاد سوال جدید
    public function create(Exam $exam)
    {
        $this->authorizeTeacherExam($exam);

        return view('dashboard.teacher.exams.questions-create', compact('exam'));
    }

    public function store(Request $request, Exam $exam)
    {
        $this->authorizeTeacherExam($exam);

        // ✅ هم question (فرم جدید) هم question_text (legacy)
        $baseRules = [
            'type'        => 'required|in:mcq,true_false,fill_blank,essay',

            'question'      => 'required_without:question_text|string|max:2000',
            'question_text' => 'required_without:question|string|max:2000',

            'score'       => 'nullable|integer|min:1|max:100',
            'explanation' => 'nullable|string|max:2000',
        ];

        $typeRules = match ($request->type) {
            'mcq' => [
                'option_a'       => 'required|string|max:1000',
                'option_b'       => 'required|string|max:1000',
                'option_c'       => 'required|string|max:1000',
                'option_d'       => 'required|string|max:1000',
                'correct_option' => 'required|in:a,b,c,d',
            ],
            'true_false' => [
                'correct_tf' => 'required|boolean',
            ],
            'fill_blank' => [
                'correct_blanks' => 'required|string|max:2000',
            ],
            'essay' => [],
            default => []
        };

        $validated = $request->validate(array_merge($baseRules, $typeRules));

        // ✅ متن سوال را از هر کدام که آمده بگیر
        $questionText = $validated['question'] ?? $validated['question_text'];

        $payload = [
            'type'          => $validated['type'],
            'question_text' => $questionText,   // ✅ ذخیره در ستون واقعی DB
            'score'         => $validated['score'] ?? 1,
            'explanation'   => $validated['explanation'] ?? null,
        ];

        // reset multi-type fields
        $payload['options']        = null;
        $payload['correct_answer'] = null;
        $payload['correct_tf']     = null;

        // Legacy MCQ
        if ($validated['type'] === 'mcq') {
            $payload['option_a']       = $validated['option_a'];
            $payload['option_b']       = $validated['option_b'];
            $payload['option_c']       = $validated['option_c'];
            $payload['option_d']       = $validated['option_d'];
            $payload['correct_option'] = $validated['correct_option'];
        }

        if ($validated['type'] === 'true_false') {
            $payload['correct_tf'] = (bool) $validated['correct_tf'];
        }

        if ($validated['type'] === 'fill_blank') {
            $answers = preg_split("/[,\\n]+/", $validated['correct_blanks']);
            $answers = array_values(array_filter(array_map('trim', $answers)));
            $payload['correct_answer'] = $answers;
        }

        $exam->questions()->create($payload);

        return redirect()
            ->route('teacher.exams.questions.index', $exam)
            ->with('success', 'سؤال جدید اضافه شد.');
    }

    public function edit(Exam $exam, Question $question)
    {
        $this->authorizeTeacherExam($exam);

        if ($question->exam_id !== $exam->id) {
            abort(404);
        }

        return view('dashboard.teacher.exams.question-edit', compact('exam', 'question'));
    }

    public function update(Request $request, Exam $exam, Question $question)
    {
        $this->authorizeTeacherExam($exam);

        if ($question->exam_id !== $exam->id) {
            abort(404);
        }

        $baseRules = [
            'type'        => 'required|in:mcq,true_false,fill_blank,essay',

            'question'      => 'required_without:question_text|string|max:2000',
            'question_text' => 'required_without:question|string|max:2000',

            'score'       => 'nullable|integer|min:1|max:100',
            'explanation' => 'nullable|string|max:2000',
        ];

        $typeRules = match ($request->type) {
            'mcq' => [
                'option_a'       => 'required|string|max:1000',
                'option_b'       => 'required|string|max:1000',
                'option_c'       => 'required|string|max:1000',
                'option_d'       => 'required|string|max:1000',
                'correct_option' => 'required|in:a,b,c,d',
            ],
            'true_false' => [
                'correct_tf' => 'required|boolean',
            ],
            'fill_blank' => [
                'correct_blanks' => 'required|string|max:2000',
            ],
            'essay' => [],
            default => []
        };

        $validated = $request->validate(array_merge($baseRules, $typeRules));

        $questionText = $validated['question'] ?? $validated['question_text'];

        $payload = [
            'type'          => $validated['type'],
            'question_text' => $questionText,
            'score'         => $validated['score'] ?? 1,
            'explanation'   => $validated['explanation'] ?? null,
        ];

        // reset fields
        $payload['options']        = null;
        $payload['correct_answer'] = null;
        $payload['correct_tf']     = null;

        // legacy MCQ reset
        $payload['option_a'] = $payload['option_b'] = $payload['option_c'] = $payload['option_d'] = null;
        $payload['correct_option'] = null;

        if ($validated['type'] === 'mcq') {
            $payload['option_a']       = $validated['option_a'];
            $payload['option_b']       = $validated['option_b'];
            $payload['option_c']       = $validated['option_c'];
            $payload['option_d']       = $validated['option_d'];
            $payload['correct_option'] = $validated['correct_option'];
        }

        if ($validated['type'] === 'true_false') {
            $payload['correct_tf'] = (bool) $validated['correct_tf'];
        }

        if ($validated['type'] === 'fill_blank') {
            $answers = preg_split("/[,\\n]+/", $validated['correct_blanks']);
            $answers = array_values(array_filter(array_map('trim', $answers)));
            $payload['correct_answer'] = $answers;
        }

        $question->update($payload);

        return redirect()
            ->route('teacher.exams.questions.index', $exam)
            ->with('success', 'سؤال با موفقیت ویرایش شد.');
    }

    public function destroy(Exam $exam, Question $question)
    {
        $this->authorizeTeacherExam($exam);

        if ($question->exam_id !== $exam->id) {
            abort(404);
        }

        $question->delete();

        return redirect()
            ->route('teacher.exams.questions.index', $exam)
            ->with('success', 'سؤال حذف شد.');
    }

    private function authorizeTeacherExam(Exam $exam): void
    {
        if ($exam->scope === 'free') {
            abort_unless(
                $exam->teacher_id === Auth::id(),
                403,
                'شما اجازه مدیریت سوالات این آزمون را ندارید.'
            );
            return;
        }

        $exam->loadMissing('classroom');

        abort_unless(
            $exam->classroom && $exam->classroom->teacher_id === Auth::id(),
            403,
            'شما اجازه مدیریت سوالات این آزمون را ندارید.'
        );
    }
}
