<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Enums\QuestionType;
use Illuminate\Validation\Rules\Enum;
use App\Services\Teacher\ExamAccessService;

class QuestionController extends Controller
{
    public function __construct(
        protected ExamAccessService $examAccess
    ) {}


    public function index(Exam $exam)
    {
        //------------------------------
        $this->examAccess->authorizeTeacherExam($exam);
        $examMode = $this->examAccess->detectExamMode($exam);
        //-----------------------------
        $exam->load('classroom');

        $questions = Question::where('exam_id', $exam->id)
            ->latest()
            ->get();

        return view('dashboard.teacher.exams.questions.index', compact('exam', 'questions'));
    }

    public function create(Exam $exam)
    {
        //------------------------------
        $this->examAccess->authorizeTeacherExam($exam);
        $examMode = $this->examAccess->detectExamMode($exam);
        //-----------------------------
        $subjects = null;
        if ($examMode === 'multi_subject') {
            $subjects = $exam->subjects()->get(['id', 'title_fa']);
        }

        return view('dashboard.teacher.exams.questions.wizard.create', [
            'exam'     => $exam,
            'subjects' => $subjects,
            'examMode' => $examMode,
        ]);
    }

    // ✅ store (final)
public function store(Request $request, Exam $exam)
{
    //------------------------------
    $this->examAccess->authorizeTeacherExam($exam);
    //-----------------------------
    $validated = $request->validate([
        'content'       => 'required|string|max:2000',
        'question_type' => ['required', new Enum(QuestionType::class)],
        'score'         => 'nullable|numeric|min:0',
        'explanation'   => 'nullable|string',
        'is_active'     => 'nullable|boolean',
        'subject_id'    => 'nullable|integer',

        'options'        => 'nullable|array',
        'correct_answer' => 'nullable|array',
    ]);

    $type = $validated['question_type'];

    // ❗ correct_answer نباید null باشد
    $correctAnswer = match ($type) {
        'mcq' => [
            'correct_option' => data_get($validated, 'correct_answer.correct_option'),
        ],
        'true_false' => [
            'value' => (bool) data_get($validated, 'correct_answer.value'),
        ],
        'fill_blank' => [
            'values' => array_values(array_filter(
                data_get($validated, 'correct_answer.values', [])
            )),
        ],
        'essay' => [], // 👈 بسیار مهم
        default => [],
    };

    Question::create([
        'exam_id'        => $exam->id,
        'subject_id'     => $validated['subject_id'] ?? $exam->primary_subject_id,
        'section_id'     => $exam->section_id,
        'grade_id'       => $exam->grade_id,
        'branch_id'      => $exam->branch_id,
        'field_id'       => $exam->field_id,
        'subfield_id'    => $exam->subfield_id,

        'content'        => $validated['content'],
        'question_type'  => $type,
        'score'          => $validated['score'] ?? 1,
        'explanation'    => $validated['explanation'] ?? null,
        'is_active'      => $request->boolean('is_active', true),

        'options'        => $validated['options'] ?? null,
        'correct_answer' => $correctAnswer,
        'difficulty'     => 2,
    ]);

    return redirect()
        ->route('teacher.exams.questions.index', $exam)
        ->with('success', 'سوال با موفقیت اضافه شد.');
}


    public function edit(Exam $exam, Question $question)
    {
        //------------------------------
        $this->examAccess->authorizeTeacherExam($exam);
        $examMode = $this->examAccess->detectExamMode($exam);
        //-----------------------------
        if ($question->exam_id !== $exam->id) abort(404);

        $subjects = null;

        if ($examMode === 'multi_subject') {
            $subjects = $exam->subjects()->get(['id', 'title_fa']);
        }

        return view('dashboard.teacher.exams.questions.wizard.edit', [
            'exam'     => $exam,
            'question' => $question,
            'subjects' => $subjects,
            'examMode' => $examMode,
        ]);
    }

    // ✅ update (final)
public function update(Request $request, Exam $exam, Question $question)
{
    //------------------------------
    $this->examAccess->authorizeTeacherExam($exam);
    //-----------------------------
    if ($question->exam_id !== $exam->id) {
        abort(404);
    }

    $validated = $request->validate([
        'content'       => 'required|string|max:2000',
        'question_type' => ['required', new Enum(QuestionType::class)],
        'score'         => 'nullable|numeric|min:0',
        'explanation'   => 'nullable|string',
        'is_active'     => 'nullable|boolean',
        'subject_id'    => 'nullable|integer',

        'options'        => 'nullable|array',
        'correct_answer' => 'nullable|array',
    ]);

    $type = $validated['question_type'];

    $correctAnswer = match ($type) {
        'mcq' => [
            'correct_option' => data_get($validated, 'correct_answer.correct_option'),
        ],
        'true_false' => [
            'value' => (bool) data_get($validated, 'correct_answer.value'),
        ],
        'fill_blank' => [
            'values' => array_values(array_filter(
                data_get($validated, 'correct_answer.values', [])
            )),
        ],
        'essay' => [],
        default => [],
    };

    $question->update([
        'subject_id'     => $validated['subject_id'] ?? $question->subject_id,
        'content'        => $validated['content'],
        'question_type'  => $type,
        'score'          => $validated['score'] ?? 1,
        'explanation'    => $validated['explanation'] ?? null,
        'is_active'      => $request->boolean('is_active', true),
        'options'        => $validated['options'] ?? null,
        'correct_answer' => $correctAnswer,
        'difficulty'     => 2,
    ]);

    return redirect()
        ->route('teacher.exams.questions.index', $exam)
        ->with('success', 'سوال با موفقیت ویرایش شد.');
}


    public function destroy(Exam $exam, Question $question)
    {
    //------------------------------
    $this->examAccess->authorizeTeacherExam($exam);
    //-----------------------------
        if ($question->exam_id !== $exam->id) abort(404);

        $question->delete();

        return redirect()
            ->route('teacher.exams.questions.index', $exam)
            ->with('success', 'سؤال حذف شد.');
    }

   
}
