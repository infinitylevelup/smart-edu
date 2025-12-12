<?php
// app/Http/Controllers/Teacher/TeacherExamController.php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'exam_type'        => 'required|string',
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'duration'         => 'required|integer|min:5|max:300',
            'classroom_id'     => 'nullable|exists:classrooms,id',

            // Taxonomy
            'section_id'       => 'required|integer',
            'grade_id'         => 'required|integer',
            'branch_id'        => 'required|integer',
            'field_id'         => 'required|integer',
            'subfield_id'      => 'required|integer',
            'subject_type_id'  => 'required|integer',

            // Step7
            'subjects'         => 'required|string',  // JSON string
        ]);

        // ---------------------------
        // 1) استخراج subjects
        // ---------------------------
        $subjects = json_decode($validated['subjects'], true) ?? [];

        if (!is_array($subjects) || count($subjects) === 0) {
            return back()->withErrors(['subjects' => 'حداقل یک درس باید انتخاب شود.'])->withInput();
        }

        // ---------------------------
        // 2) تعیین exam_mode
        // ---------------------------
        $examMode = $this->detectExamMode($validated['exam_type']);

        // ---------------------------
        // 3) تعیین درس اصلی در حالت تک‌درس
        // ---------------------------
        $primarySubjectId = ($examMode === 'single_subject')
            ? $subjects[0]
            : null;

        // ---------------------------
        // 4) ساخت رکورد exam
        // ---------------------------
        $exam = Exam::create([
            'user_id'          => auth()->id(),
            'teacher_id'       => auth()->id(),
            'classroom_id'     => $validated['classroom_id'] ?? null,

            'exam_type'        => $validated['exam_type'],
            'exam_mode'        => $examMode,
            'primary_subject_id' => $primarySubjectId,

            'title'            => $validated['title'],
            'description'      => $validated['description'] ?? null,
            'duration_minutes' => $validated['duration'],

            'section_id'       => $validated['section_id'],
            'grade_id'         => $validated['grade_id'],
            'branch_id'        => $validated['branch_id'],
            'field_id'         => $validated['field_id'],
            'subfield_id'      => $validated['subfield_id'],
            'subject_type_id'  => $validated['subject_type_id'],

            'is_active'        => $request->boolean('is_active', true),
        ]);

        // ---------------------------
        // 5) اتصال subjects به pivot
        // ---------------------------
        if ($examMode === 'multi_subject') {
            $exam->subjects()->sync($subjects);
        } else {
            // single_subject → فقط 1 درس
            $exam->subjects()->sync([$primarySubjectId]);
        }

        return redirect()
            ->route('teacher.exams.index')
            ->with('success', 'آزمون با موفقیت ایجاد شد.');
    }


    public function show(Exam $exam)
    {
        abort_unless($exam->teacher_id === Auth::id(), 403);
        return view('dashboard.teacher.exams.show', compact('exam'));
    }

    public function edit(Exam $exam)
    {
        abort_unless($exam->teacher_id === Auth::id(), 403);

        // کلاس‌های همین معلم برای دراپ‌دان
        $classrooms = Classroom::query()
            ->where('teacher_id', Auth::id())
            ->orderBy('title')
            ->get();

        // لیست دروس برای دراپ‌دان ساده
        $subjects = Subject::where('is_active', 1)
            ->orderBy('title_fa')
            ->pluck('title_fa')
            ->toArray();

        return view('dashboard.teacher.exams.edit', compact('exam', 'classrooms', 'subjects'));
    }

    public function update(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'duration'         => 'required|integer|min:5|max:300',

            // Wizard در Edit نداریم → اما برای سازگاری ذخیره می‌کنیم
            'scope'            => 'required|string|in:free,classroom',
            'classroom_id'     => 'nullable|exists:classrooms,id',
            'subject'          => 'nullable|string',
            'level'            => 'nullable|string',
        ]);

        // ---------------------------
        // 1) update پایه
        // ---------------------------
        $exam->update([
            'title'            => $validated['title'],
            'description'      => $validated['description'] ?? null,
            'duration_minutes' => $validated['duration'],

            'scope'            => $validated['scope'],
            'classroom_id'     => $validated['scope'] === 'classroom'
                                    ? $validated['classroom_id']
                                    : null,

            'subject'          => $validated['subject'],
            'level'            => $validated['level'],

            'is_published'     => $request->boolean('is_published', false),
        ]);

        return redirect()
            ->route('teacher.exams.index')
            ->with('success', 'آزمون با موفقیت بروزرسانی شد.');
    }


    public function destroy(Exam $exam)
    {
        abort_unless($exam->teacher_id === Auth::id(), 403);
        $exam->delete();
        return back()->with('success', 'آزمون حذف شد.');
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
            ->when($sectionId, fn($q)=>$q->where('section_id',$sectionId))
            ->orderBy('sort_order')
            ->get(['id','name_fa','slug','section_id']);

        return response()->json(['grades' => $grades]);
    }

    public function branches(Request $request)
    {
        $sectionId = $request->get('section_id');

        $branches = Branch::where('is_active', 1)
            ->when($sectionId, fn($q)=>$q->where('section_id',$sectionId))
            ->orderBy('sort_order')
            ->get(['id','name_fa','slug','section_id']);

        return response()->json(['branches' => $branches]);
    }

    public function fields(Request $request)
    {
        $branchId = $request->get('branch_id');

        $fields = Field::where('is_active', 1)
            ->when($branchId, fn($q)=>$q->where('branch_id',$branchId))
            ->orderBy('sort_order')
            ->get(['id','name_fa','slug','branch_id']);

        return response()->json(['fields' => $fields]);
    }

    public function subfields(Request $request)
    {
        $fieldId = $request->get('field_id');

        $subfields = Subfield::where('is_active', 1)
            ->when($fieldId, fn($q)=>$q->where('field_id',$fieldId))
            ->orderBy('sort_order')
            ->get(['id','name_fa','slug','field_id']);

        return response()->json(['subfields' => $subfields]);
    }

    public function subjects(Request $request)
    {
        $q = Subject::query()->where('is_active', 1);

        $resolveId = function(string $modelClass, $val) {
            if (!$val) return null;

            if (\Illuminate\Support\Str::isUuid($val)) {
                $row = $modelClass::where('uuid', $val)->first();
                return $row?->id;
            }

            return $val;
        };

        $gradeId    = $resolveId(Grade::class, $request->grade_id);
        $branchId   = $resolveId(Branch::class, $request->branch_id);
        $fieldId    = $resolveId(Field::class, $request->field_id);
        $subfieldId = $resolveId(Subfield::class, $request->subfield_id);

        if ($gradeId)    $q->where('grade_id', $gradeId);
        if ($branchId)   $q->where('branch_id', $branchId);
        if ($fieldId)    $q->where('field_id', $fieldId);
        if ($subfieldId) $q->where('subfield_id', $subfieldId);

        $typeId = $resolveId(SubjectType::class, $request->subject_type_id);
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

            if ($request->filled('grade_id') && \Schema::hasColumn('subject_types', 'grade_id')) {
                $q->where('grade_id', $request->grade_id);
            }
            if ($request->filled('branch_id') && \Schema::hasColumn('subject_types', 'branch_id')) {
                $q->where('branch_id', $request->branch_id);
            }
            if ($request->filled('field_id') && \Schema::hasColumn('subject_types', 'field_id')) {
                $q->where('field_id', $request->field_id);
            }
            if ($request->filled('subfield_id') && \Schema::hasColumn('subject_types', 'subfield_id')) {
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
