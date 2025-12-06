<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Classroom;
use App\Models\Section;
use App\Models\Grade;
use App\Models\Branch;
use App\Models\Field;
use App\Models\Subfield;
use App\Models\SubjectType;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TeacherExamController extends Controller
{
    // ==========================================================
    // CRUD
    // ==========================================================

    /**
     * لیست آزمون‌های معلم
     */
    public function index(Request $request)
    {
        $teacherId = Auth::id();

        $classrooms = Classroom::where('teacher_id', $teacherId)->latest()->get();

        $query = Exam::query()
            ->where('teacher_id', $teacherId)
            ->with('classroom')
            ->latest();

        if ($request->filled('classroom_id')) {
            $query->where('classroom_id', $request->classroom_id);
        }

        $exams = $query->get();

        return view('dashboard.teacher.exams.index', compact('exams', 'classrooms'));
    }

    /**
     * فرم ساخت آزمون (ویزارد تک‌صفحه‌ای)
     */
    public function create(Request $request)
    {
        $teacherId = Auth::id();

        // اگر از صفحه کلاس آمده باشد
        $selectedClassroomId = $request->get('classroom_id');

        if (
            $selectedClassroomId &&
            !Classroom::where('teacher_id', $teacherId)->where('id', $selectedClassroomId)->exists()
        ) {
            $selectedClassroomId = null;
        }

        return view('dashboard.teacher.exams.create', compact('selectedClassroomId'));
    }

    /**
     * ذخیره آزمون جدید از فرم create.blade.php
     *
     * ورودی‌های فرم تو:
     * exam_type, classroom_id, section_id, grade_id, branch_id, field_id, subfield_id, subject_type_id,
     * subjects, title, duration, description, is_active
     */
    public function store(Request $request)
    {
        $teacherId = Auth::id();

        $data = $request->validate([
            'exam_type'       => 'required|in:public,class_single,class_comprehensive',

            // کلاس فقط برای حالت‌های کلاسی اجباری می‌شود
            'classroom_id'    => 'nullable|required_if:exam_type,class_single,class_comprehensive|uuid|exists:classrooms,id',

            // اینها از فرانت باید UUID واقعی باشند
            'section_id'      => 'nullable|uuid|exists:sections,id',
            'grade_id'        => 'nullable|uuid|exists:grades,id',
            'branch_id'       => 'nullable|uuid|exists:branches,id',
            'field_id'        => 'nullable|uuid|exists:fields,id',
            'subfield_id'     => 'nullable|uuid|exists:subfields,id',
            'subject_type_id' => 'nullable|uuid|exists:subject_types,id',

            // subjects لیست UUIDهاست که فرانت می‌فرستد
            'subjects'        => 'required|string', // comma-separated uuids

            'title'           => 'required|string|max:250',
            'duration'        => 'required|integer|min:1',
            'description'     => 'nullable|string',
            'is_active'       => 'nullable|boolean',
        ]);

        // اگر آزمون کلاسی بود مالکیت کلاس چک شود
        if (!empty($data['classroom_id'])) {
            abort_unless(
                Classroom::where('teacher_id', $teacherId)
                    ->where('id', $data['classroom_id'])
                    ->exists(),
                403
            );
        }

        $exam = Exam::create([
            'id'                => (string) Str::uuid(),
            'teacher_id'        => $teacherId,
            'classroom_id'      => $data['classroom_id'] ?? null,

            'exam_type'         => $data['exam_type'],
            'title'             => $data['title'],
            'description'       => $data['description'] ?? null,
            'duration_minutes'  => (int) $data['duration'],

            // taxonomy (همه اختیاری)
            'section_id'        => $data['section_id'] ?? null,
            'grade_id'          => $data['grade_id'] ?? null,
            'branch_id'         => $data['branch_id'] ?? null,
            'field_id'          => $data['field_id'] ?? null,
            'subfield_id'       => $data['subfield_id'] ?? null,
            'subject_type_id'   => $data['subject_type_id'] ?? null,

            'shuffle_questions' => false,
            'shuffle_options'   => false,
            'ai_assisted'       => false,
            'is_active'         => (bool)($data['is_active'] ?? true),
            'is_published'      => false,
        ]);

        // attach subjects (pivot exam_subject)
        $subjectIds = collect(explode(',', $data['subjects']))
            ->map(fn ($id) => trim($id))
            ->filter()
            ->values();

        if ($subjectIds->isNotEmpty()) {
            $exam->subjects()->sync($subjectIds);
        }

        return redirect()
            ->route('teacher.exams.show', $exam->id)
            ->with('success', 'آزمون با موفقیت ساخته شد.');
    }

    /**
     * نمایش آزمون
     */
    public function show(Exam $exam)
    {
        $this->authorizeTeacherExam($exam);

        $exam->load(['classroom', 'questions', 'subjects']);

        return view('dashboard.teacher.exams.show', compact('exam'));
    }

    /**
     * فرم ویرایش آزمون
     */
    public function edit(Exam $exam)
    {
        $this->authorizeTeacherExam($exam);

        $classrooms = Classroom::where('teacher_id', Auth::id())->latest()->get();

        return view('dashboard.teacher.exams.edit', compact('exam', 'classrooms'));
    }

    /**
     * بروزرسانی آزمون
     */
    public function update(Request $request, Exam $exam)
    {
        $this->authorizeTeacherExam($exam);

        $data = $request->validate([
            'title'             => 'required|string|max:250',
            'description'       => 'nullable|string',
            'duration_minutes'  => 'nullable|integer|min:1',
            'start_at'          => 'nullable|date',
            'end_at'            => 'nullable|date|after_or_equal:start_at',
            'shuffle_questions' => 'nullable|boolean',
            'shuffle_options'   => 'nullable|boolean',
            'ai_assisted'       => 'nullable|boolean',
            'is_active'         => 'nullable|boolean',
            'is_published'      => 'nullable|boolean',
            'classroom_id'      => 'nullable|uuid|exists:classrooms,id',
        ]);

        if (!empty($data['classroom_id'])) {
            abort_unless(
                Classroom::where('teacher_id', Auth::id())
                    ->where('id', $data['classroom_id'])
                    ->exists(),
                403
            );
        }

        if (!array_key_exists('duration_minutes', $data) || is_null($data['duration_minutes'])) {
            unset($data['duration_minutes']);
        }

        $exam->update($data);

        return redirect()
            ->route('teacher.exams.edit', $exam->id)
            ->with('success', 'آزمون بروزرسانی شد.');
    }

    /**
     * حذف آزمون
     */
    public function destroy(Exam $exam)
    {
        $this->authorizeTeacherExam($exam);

        $exam->delete();

        return redirect()
            ->route('teacher.exams.index')
            ->with('success', 'آزمون حذف شد.');
    }

    // ==========================================================
    // AJAX endpoints (UUID real data for frontend)
    // مسیرها مطابق routes/teacher.php
    // ==========================================================

    public function sections()
    {
        return response()->json([
            'sections' => Section::where('is_active', true)
                ->orderBy('sort_order')
                ->get(['id', 'name_fa', 'slug'])
        ]);
    }

    public function grades(Request $request)
    {
        $q = Grade::query()->where('is_active', true);

        if ($request->filled('section_id')) {
            $q->where('section_id', $request->section_id);
        }

        return response()->json([
            'grades' => $q->orderBy('sort_order')
                ->get(['id', 'name_fa', 'slug', 'section_id'])
        ]);
    }

    public function branches(Request $request)
    {
        $q = Branch::query()->where('is_active', true);

        if ($request->filled('section_id')) {
            $q->where('section_id', $request->section_id);
        }

        return response()->json([
            'branches' => $q->orderBy('sort_order')
                ->get(['id', 'name_fa', 'slug', 'section_id'])
        ]);
    }

    public function fields(Request $request)
    {
        $q = Field::query()->where('is_active', true);

        if ($request->filled('branch_id')) {
            $q->where('branch_id', $request->branch_id);
        }

        return response()->json([
            'fields' => $q->orderBy('sort_order')
                ->get(['id', 'name_fa', 'slug', 'branch_id'])
        ]);
    }

    public function subfields(Request $request)
    {
        $q = Subfield::query()->where('is_active', true);

        if ($request->filled('field_id')) {
            $q->where('field_id', $request->field_id);
        }

        return response()->json([
            'subfields' => $q->orderBy('sort_order')
                ->get(['id', 'name_fa', 'slug', 'field_id'])
        ]);
    }

    public function subjectTypes()
    {
        return response()->json([
            'subject_types' => SubjectType::where('is_active', true)
                ->orderBy('sort_order')
                ->get(['id', 'name_fa', 'slug'])
        ]);
    }

    public function subjects(Request $request)
    {
        $q = Subject::query()->where('is_active', true);

        if ($request->filled('grade_id')) {
            $q->where('grade_id', $request->grade_id);
        }
        if ($request->filled('branch_id')) {
            $q->where('branch_id', $request->branch_id);
        }
        if ($request->filled('field_id')) {
            $q->where('field_id', $request->field_id);
        }
        if ($request->filled('subfield_id')) {
            $q->where('subfield_id', $request->subfield_id);
        }
        if ($request->filled('subject_type_id')) {
            $q->where('subject_type_id', $request->subject_type_id);
        }

        return response()->json([
            'subjects' => $q->orderBy('title_fa')
                ->get([
                    'id','title_fa','code','hours',
                    'grade_id','branch_id','field_id','subfield_id','subject_type_id'
                ])
        ]);
    }

    // ==========================================================
    // Helper: امنیت آزمون
    // ==========================================================
    protected function authorizeTeacherExam(Exam $exam)
    {
        abort_unless($exam->teacher_id === Auth::id(), 403);

        if ($exam->classroom_id) {
            abort_unless(
                $exam->classroom && $exam->classroom->teacher_id === Auth::id(),
                403
            );
        }
    }
}
