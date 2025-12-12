<?php
// app/Http/Controllers/Teacher/TeacherExamController.php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExamRequest;
use App\Http\Requests\UpdateExamRequest;
use App\Models\Section;
use App\Models\Grade;
use App\Models\Branch;
use App\Models\Field;
use App\Models\Subfield;
use App\Models\SubjectType;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class TeacherExamController extends Controller
{
    // ==========================================================
    // Exams CRUD
    // ==========================================================

    public function index()
    {
        $teacherId = Auth::id();

        $exams = Exam::query()
            ->where('teacher_id', $teacherId)
            ->latest()
            ->paginate(10);

        return view('dashboard.teacher.exams.index', compact('exams'));
    }

    public function create(Request $request)
    {
        $selectedClassroomId = $request->get('classroom_id');
        return view('dashboard.teacher.exams.create', compact('selectedClassroomId'));
    }

    public function store(StoreExamRequest $request)
    {
        $v = $request->validated();
        $teacherId = Auth::id();

        $examType = (string) $v['exam_type'];

        // ============================
        // CLASS EXAM (Source of Truth = Classroom)
        // ============================
        if ($this->isClassExamType($examType)) {
            $classroom = Classroom::query()
                ->with('subjects') // اگر ندارید حذفش کن
                ->findOrFail($v['classroom_id']);

            abort_unless((int) $classroom->teacher_id === (int) $teacherId, 403);

            $derived = $this->deriveClassExamPayload($classroom);

            $exam = Exam::create([
                'user_id'      => $teacherId,
                'teacher_id'   => $teacherId,
                'classroom_id' => $classroom->id,

                // ENFORCE (Wizard authority ندارد)
                'exam_type'          => $derived['exam_type'],
                'exam_mode'          => $derived['exam_mode'],
                'primary_subject_id' => $derived['primary_subject_id'],

                'title'        => $v['title'],
                'description'  => $v['description'] ?? null,
                'duration_minutes' => $v['duration'],

                // taxonomy از کلاس
                'section_id'      => $derived['section_id'],
                'grade_id'        => $derived['grade_id'],
                'branch_id'       => $derived['branch_id'],
                'field_id'        => $derived['field_id'],
                'subfield_id'     => $derived['subfield_id'],
                'subject_type_id' => $derived['subject_type_id'],

                'is_active' => $request->boolean('is_active', true),
            ]);

            $exam->subjects()->sync($derived['subject_ids']);

            return redirect()
                ->route('teacher.exams.index')
                ->with('success', 'آزمون کلاسی با موفقیت ایجاد شد.');
        }

        // ============================
        // PUBLIC EXAM (Wizard owner)
        // ============================

        // subjects از UI: JSON(uuid[])
        $subjectsRaw = $this->decodeSubjects($v['subjects']);
        $subjectIds  = $this->resolveSubjectIds($subjectsRaw);

        if (count($subjectIds) === 0) {
            return back()->withErrors(['subjects' => 'حداقل یک درس معتبر باید انتخاب شود.'])->withInput();
        }

        // exam_mode از تعداد درس‌ها (طبق Handoff: جدا از exam_type)
        $examMode = (count($subjectIds) === 1) ? 'single_subject' : 'multi_subject';
        $primarySubjectId = ($examMode === 'single_subject') ? $subjectIds[0] : null;

        // subject_type_id ممکنه UUID باشه؛ به id تبدیلش می‌کنیم
        $subjectTypeId = $this->resolveId(SubjectType::class, $v['subject_type_id']);

        $exam = Exam::create([
            'user_id'      => $teacherId,
            'teacher_id'   => $teacherId,
            'classroom_id' => null,

            'exam_type'          => 'public',
            'exam_mode'          => $examMode,
            'primary_subject_id' => $primarySubjectId,

            'title'        => $v['title'],
            'description'  => $v['description'] ?? null,
            'duration_minutes' => $v['duration'],

            'section_id'   => $v['section_id'],
            'grade_id'     => $v['grade_id'],
            'branch_id'    => $v['branch_id'],
            'field_id'     => $v['field_id'],
            'subfield_id'  => $v['subfield_id'],
            'subject_type_id' => $subjectTypeId,

            'is_active' => $request->boolean('is_active', true),
        ]);

        $exam->subjects()->sync($subjectIds);

        return redirect()
            ->route('teacher.exams.index')
            ->with('success', 'آزمون عمومی با موفقیت ایجاد شد.');
    }

    public function show(Exam $exam)
    {
        abort_unless((int) $exam->teacher_id === (int) Auth::id(), 403);
        return view('dashboard.teacher.exams.show', compact('exam'));
    }

    public function edit(Exam $exam)
    {
        abort_unless((int) $exam->teacher_id === (int) Auth::id(), 403);

        $classrooms = Classroom::query()
            ->where('teacher_id', Auth::id())
            ->orderBy('title')
            ->get();

        $subjects = Subject::where('is_active', 1)
            ->orderBy('title_fa')
            ->pluck('title_fa')
            ->toArray();

        return view('dashboard.teacher.exams.edit', compact('exam', 'classrooms', 'subjects'));
    }

    public function update(UpdateExamRequest $request, Exam $exam)
    {
        abort_unless((int) $exam->teacher_id === (int) Auth::id(), 403);

        $v = $request->validated();

        $baseUpdate = [
            'title'            => $v['title'],
            'description'      => $v['description'] ?? null,
            'duration_minutes' => $v['duration'],

            'start_at'         => $v['start_at'] ?? $exam->start_at,
            'end_at'           => $v['end_at'] ?? $exam->end_at,

            'shuffle_questions'=> $request->boolean('shuffle_questions', $exam->shuffle_questions),
            'shuffle_options'  => $request->boolean('shuffle_options', $exam->shuffle_options),
            'ai_assisted'      => $request->boolean('ai_assisted', $exam->ai_assisted),

            'is_active'        => $request->boolean('is_active', $exam->is_active),
            'is_published'     => $request->boolean('is_published', $exam->is_published),
        ];

        // CLASS EXAM: ساختار ممنوع/بی‌اثر
        if ($exam->exam_type !== 'public') {
            $exam->update($baseUpdate);

            return redirect()
                ->route('teacher.exams.index')
                ->with('success', 'آزمون کلاسی بروزرسانی شد.');
        }

        // PUBLIC EXAM: اگر ساختار فرستاده شد mirror store(public)
        $structKeys = ['section_id','grade_id','branch_id','field_id','subfield_id','subject_type_id','subjects'];
        $hasStructUpdate = collect($structKeys)->some(fn($k) => $request->filled($k));

        if ($hasStructUpdate) {
            // subjects
            $subjectIds = $exam->subjects()->pluck('subjects.id')->all();

            if ($request->filled('subjects')) {
                $subjectsRaw = $this->decodeSubjects((string) $request->input('subjects'));
                $subjectIds  = $this->resolveSubjectIds($subjectsRaw);
            }

            if (count($subjectIds) === 0) {
                return back()->withErrors(['subjects' => 'حداقل یک درس معتبر باید انتخاب شود.'])->withInput();
            }

            $examMode = (count($subjectIds) === 1) ? 'single_subject' : 'multi_subject';
            $primarySubjectId = ($examMode === 'single_subject') ? $subjectIds[0] : null;

            // subject_type
            $subjectTypeId = $exam->subject_type_id;
            if ($request->filled('subject_type_id')) {
                $subjectTypeId = $this->resolveId(SubjectType::class, $request->input('subject_type_id'));
            }

            $exam->update($baseUpdate + [
                'exam_mode'          => $examMode,
                'primary_subject_id' => $primarySubjectId,

                'section_id'      => $v['section_id'] ?? $exam->section_id,
                'grade_id'        => $v['grade_id'] ?? $exam->grade_id,
                'branch_id'       => $v['branch_id'] ?? $exam->branch_id,
                'field_id'        => $v['field_id'] ?? $exam->field_id,
                'subfield_id'     => $v['subfield_id'] ?? $exam->subfield_id,
                'subject_type_id' => $subjectTypeId,
            ]);

            $exam->subjects()->sync($subjectIds);

            return redirect()
                ->route('teacher.exams.index')
                ->with('success', 'آزمون عمومی (همراه با ساختار) بروزرسانی شد.');
        }

        // PUBLIC بدون تغییر ساختار
        $exam->update($baseUpdate);

        return redirect()
            ->route('teacher.exams.index')
            ->with('success', 'آزمون بروزرسانی شد.');
    }

    public function destroy(Exam $exam)
    {
        abort_unless((int) $exam->teacher_id === (int) Auth::id(), 403);
        $exam->delete();
        return back()->with('success', 'آزمون حذف شد.');
    }

    // ==========================================================
    // Helpers (داخل همین Controller، فایل جدید نمی‌خواهد)
    // ==========================================================

    private function isClassExamType(string $examType): bool
    {
        return $examType !== 'public';
    }

    private function decodeSubjects(string $json): array
    {
        $arr = json_decode($json, true);
        return is_array($arr) ? $arr : [];
    }

    /**
     * subjects در UI به صورت uuid as id می‌آید.
     * این متد هم uuid و هم id عددی را قبول می‌کند و به id های واقعی برمی‌گرداند.
     */
    private function resolveSubjectIds(array $idsOrUuids): array
    {
        $idsOrUuids = array_values(array_filter($idsOrUuids, fn($v) => $v !== null && $v !== ''));

        $uuids = [];
        $ids = [];

        foreach ($idsOrUuids as $v) {
            $v = (string) $v;
            if (Str::isUuid($v)) $uuids[] = $v;
            elseif (ctype_digit($v)) $ids[] = (int) $v;
        }

        $q = Subject::query()->where('is_active', 1);

        $found = collect();

        if (count($uuids)) $found = $found->merge($q->clone()->whereIn('uuid', $uuids)->pluck('id'));
        if (count($ids))   $found = $found->merge($q->clone()->whereIn('id', $ids)->pluck('id'));

        return $found->unique()->values()->all();
    }

    /**
     * برای مدل‌هایی که در API uuid as id می‌دهند (مثل subject_types)
     */
    private function resolveId(string $modelClass, $val): ?int
    {
        if (!$val) return null;

        $val = (string) $val;

        if (Str::isUuid($val)) {
            $row = $modelClass::where('uuid', $val)->first();
            return $row?->id;
        }

        return ctype_digit($val) ? (int) $val : null;
    }

    /**
     * طبق DB:
     * classroom_type enum('single','comprehensive')
     * برای single: subject_id ستون اصلی است
     */
    private function deriveClassExamPayload(Classroom $classroom): array
    {
        $type = $classroom->classroom_type; // 'single' | 'comprehensive'

        $mode = ($type === 'single') ? 'single_subject' : 'multi_subject';
        $examType = ($type === 'single') ? 'class_single' : 'class_comprehensive';

        if ($type === 'single') {
            if (!$classroom->subject_id) {
                abort(422, 'کلاس تک‌درس است اما subject_id ندارد.');
            }

            $subjectIds = [(int) $classroom->subject_id];
            $primarySubjectId = (int) $classroom->subject_id;
        } else {
            if (!method_exists($classroom, 'subjects')) {
                abort(500, 'برای کلاس جامع، رابطه subjects() در Classroom تعریف نشده است.');
            }

            $subjectIds = $classroom->subjects()->pluck('subjects.id')->all();
            if (count($subjectIds) === 0) {
                abort(422, 'کلاس جامع است اما هیچ درسی به آن وصل نشده است.');
            }

            $primarySubjectId = null;
        }

        return [
            'exam_type' => $examType,
            'exam_mode' => $mode,
            'primary_subject_id' => $primarySubjectId,

            // taxonomy از Classroom (Source of Truth)
            'section_id'      => $classroom->section_id,
            'grade_id'        => $classroom->grade_id,
            'branch_id'       => $classroom->branch_id,
            'field_id'        => $classroom->field_id,
            'subfield_id'     => $classroom->subfield_id,
            'subject_type_id' => $classroom->subject_type_id,

            'subject_ids'     => $subjectIds,
        ];
    }

    // ==========================================================
    // AJAX Taxonomy Endpoints
    // ==========================================================

    public function sections()
    {
        $sections = Section::where('is_active', 1)
            ->orderBy('sort_order')
            ->get(['id','name_fa','slug']);

        return response()->json(['sections' => $sections]);
    }

    public function grades(Request $request)
    {
        $sectionId = $request->get('section_id');

        $grades = Grade::where('is_active', 1)
            ->when($sectionId, fn($q) => $q->where('section_id', $sectionId))
            ->orderBy('sort_order')
            ->get(['id','name_fa','slug','section_id']);

        return response()->json(['grades' => $grades]);
    }

    public function branches(Request $request)
    {
        $sectionId = $request->get('section_id');

        $branches = Branch::where('is_active', 1)
            ->when($sectionId, fn($q) => $q->where('section_id', $sectionId))
            ->orderBy('sort_order')
            ->get(['id','name_fa','slug','section_id']);

        return response()->json(['branches' => $branches]);
    }

    public function fields(Request $request)
    {
        $branchId = $request->get('branch_id');

        $fields = Field::where('is_active', 1)
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->orderBy('sort_order')
            ->get(['id','name_fa','slug','branch_id']);

        return response()->json(['fields' => $fields]);
    }

    public function subfields(Request $request)
    {
        $fieldId = $request->get('field_id');

        $subfields = Subfield::where('is_active', 1)
            ->when($fieldId, fn($q) => $q->where('field_id', $fieldId))
            ->orderBy('sort_order')
            ->get(['id','name_fa','slug','field_id']);

        return response()->json(['subfields' => $subfields]);
    }

    public function subjects(Request $request)
    {
        $q = Subject::query()->where('is_active', 1);

        $gradeId    = $this->resolveId(Grade::class, $request->grade_id);
        $branchId   = $this->resolveId(Branch::class, $request->branch_id);
        $fieldId    = $this->resolveId(Field::class, $request->field_id);
        $subfieldId = $this->resolveId(Subfield::class, $request->subfield_id);

        if ($gradeId)    $q->where('grade_id', $gradeId);
        if ($branchId)   $q->where('branch_id', $branchId);
        if ($fieldId)    $q->where('field_id', $fieldId);
        if ($subfieldId) $q->where('subfield_id', $subfieldId);

        $typeId = $this->resolveId(SubjectType::class, $request->subject_type_id);
        if ($typeId) $q->where('subject_type_id', $typeId);

        return response()->json([
            'subjects' => $q->get([
                'uuid as id',
                'title_fa',
                'slug',
                'code',
                'hours',
            ]),
        ]);
    }

    public function subjectTypes(Request $request)
    {
        try {
            $q = SubjectType::query()->where('is_active', 1);

            if ($request->filled('grade_id') && Schema::hasColumn('subject_types', 'grade_id')) {
                $q->where('grade_id', $request->grade_id);
            }
            if ($request->filled('branch_id') && Schema::hasColumn('subject_types', 'branch_id')) {
                $q->where('branch_id', $request->branch_id);
            }
            if ($request->filled('field_id') && Schema::hasColumn('subject_types', 'field_id')) {
                $q->where('field_id', $request->field_id);
            }
            if ($request->filled('subfield_id') && Schema::hasColumn('subject_types', 'subfield_id')) {
                $q->where('subfield_id', $request->subfield_id);
            }

            $types = $q->orderBy('sort_order')->get([
                'uuid as id',
                'slug',
                'name_fa',
            ]);

            return response()->json([
                'subject_types' => $types,
            ]);
        } catch (\Throwable $e) {
            \Log::error('subjectTypes error: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'subject_types' => [],
                'message' => 'Server error in subjectTypes'
            ], 500);
        }
    }
}
