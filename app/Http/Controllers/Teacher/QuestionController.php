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

        // استفاده از رابطه many-to-many (صحیح‌ترین روش)
        $questions = $exam->questions()
            ->orderByPivot('sort_order', 'asc')
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
    $this->examAccess->authorizeTeacherExam($exam);

    $validated = $request->validate([
        'content'        => 'required|string|max:2000',
        'question_type'  => ['required', Rule::in(['mcq','true_false','fill_blank','essay'])],
        'score'          => 'nullable|numeric|min:0',
        'explanation'    => 'nullable|string',
        'is_active'      => 'nullable|boolean',
        'subject_id'     => 'nullable|integer',

        'options'        => 'nullable|array',
        'correct_answer' => 'nullable|array',
    ]);

    $uiType = $validated['question_type'];

    // نوع استاندارد DB
    $dbType = \App\Enums\QuestionType::fromInput($uiType)->value;

    // همین متغیر را در match استفاده کن
    $correctAnswer = match ($uiType) {
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

    // نکته: اگر exam.section_id واقعاً null باشد باید قبلش fix شود.
    $question = Question::create([
        'exam_id'     => $exam->id,
        'subject_id'  => $validated['subject_id'] ?? $exam->primary_subject_id,

        'section_id'  => $exam->section_id,
        'grade_id'    => $exam->grade_id,
        'branch_id'   => $exam->branch_id,
        'field_id'    => $exam->field_id,
        'subfield_id' => $exam->subfield_id,
        'topic_id'    => $exam->topic_id ?? null, // اگر دارید

        'content'     => $validated['content'],
        'question_type' => $dbType,
        'score'       => $validated['score'] ?? 1,
        'explanation' => $validated['explanation'] ?? null,
        'is_active'   => $request->boolean('is_active', true),

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
            $this->examAccess->authorizeTeacherExam($exam);
            if ($question->exam_id !== $exam->id) abort(404);

            $examMode = $this->examAccess->detectExamMode($exam);

            // مثل create: اگر subjects را پاس می‌دادید همان رو بسازید
            $subjects = null;
            if ($examMode === 'multi_subject') {
                $subjects = $exam->subjects ?? collect();
            }

            return view('dashboard.teacher.exams.questions.wizard.edit', compact('exam', 'question', 'subjects'));
        }


    // ✅ update (final)
        public function update(Request $request, Exam $exam, Question $question)
        {
            $this->examAccess->authorizeTeacherExam($exam);
            if ($question->exam_id !== $exam->id) abort(404);

            $validated = $request->validate([
                'content'        => 'required|string|max:2000',
                'question_type'  => ['required', Rule::in(['mcq','true_false','fill_blank','essay'])],
                'score'          => 'nullable|numeric|min:0',
                'explanation'    => 'nullable|string',
                'is_active'      => 'nullable|boolean',
                'subject_id'     => 'nullable|integer',

                'options'        => 'nullable|array',
                'correct_answer' => 'nullable|array',
                'correct_answer.values'   => 'nullable|array',
                'correct_answer.values.*' => 'nullable|string|max:255',
                'correct_answer.value'    => 'nullable', // true_false
            ]);

            $uiType = $validated['question_type'];
            $dbType = \App\Enums\QuestionType::fromInput($uiType)->value;

            // --- correct_answer طبق نوع
            $correctAnswer = match ($uiType) {
                'mcq' => [
                    'correct_option' => data_get($validated, 'correct_answer.correct_option'),
                ],
                'true_false' => [
                    'value' => (bool) data_get($validated, 'correct_answer.value'),
                ],
                'fill_blank' => [
                    'values' => array_values(array_filter(
                        data_get($validated, 'correct_answer.values', []),
                        fn ($v) => trim((string)$v) !== ''
                    )),
                ],
                'essay' => [],
                default => [],
            };

            // حداقل یک جواب برای جای‌خالی
            if ($uiType === 'fill_blank' && count($correctAnswer['values'] ?? []) === 0) {
                return back()
                    ->withErrors(['correct_answer.values' => 'حداقل یک پاسخ برای جای‌خالی وارد کنید.'])
                    ->withInput();
            }

            $question->update([
                // همسان‌سازی با exam (تا دوباره مشکل NOT NULL یا mismatch نخورید)
                'section_id'  => $exam->section_id,
                'grade_id'    => $exam->grade_id,
                'branch_id'   => $exam->branch_id,
                'field_id'    => $exam->field_id,
                'subfield_id' => $exam->subfield_id,
                'topic_id'    => $exam->topic_id ?? null,

                'subject_id'    => $validated['subject_id'] ?? $question->subject_id ?? $exam->primary_subject_id,
                'content'       => $validated['content'],
                'question_type' => $dbType,
                'score'         => $validated['score'] ?? $question->score ?? 1,
                'explanation'   => $validated['explanation'] ?? null,
                'is_active'     => $request->boolean('is_active', true),

                'options'        => $validated['options'] ?? null,
                'correct_answer' => $correctAnswer,
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
