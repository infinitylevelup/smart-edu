<?php

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
    // Exams CRUD (اگر قبلاً داشتی، منطق قبلی‌ات رو نگه دار
    // اینجا فقط اسکلت امن گذاشتم تا route ها خطا ندهند)
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
        // اگر کلاس انتخابی از صفحه کلاس‌ها یا ... اومده
        $selectedClassroomId = $request->get('classroom_id');

        return view('dashboard.teacher.exams.create', compact('selectedClassroomId'));
    }

    public function store(Request $request)
    {
        $teacherId = Auth::id();

        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'duration'         => 'required|integer|min:15|max:300',
            'exam_type'        => 'required|string',

            'classroom_id'     => 'nullable|uuid|exists:classrooms,id',

            'section_id'       => 'required|uuid|exists:sections,id',
            'grade_id'         => 'required|uuid|exists:grades,id',
            'branch_id'        => 'required|uuid|exists:branches,id',
            'field_id'         => 'required|uuid|exists:fields,id',
            'subfield_id'      => 'nullable|uuid|exists:subfields,id',
            'subject_type_id'  => 'nullable|uuid|exists:subject_types,id',
            'subjects'         => 'required|string', // JSON of UUIDs
            'is_active'        => 'nullable|boolean',
        ]);

        // مالکیت کلاس (اگر آزمون کلاسی است)
        if (!empty($data['classroom_id'])) {
            $class = Classroom::findOrFail($data['classroom_id']);
            abort_unless($class->teacher_id === $teacherId, 403);
        }

        $exam = Exam::create([
            'id'              => (string) Str::uuid(),
            'teacher_id'      => $teacherId,
            'classroom_id'    => $data['classroom_id'] ?? null,
            'title'           => $data['title'],
            'description'     => $data['description'] ?? null,
            'duration'        => $data['duration'],
            'exam_type'       => $data['exam_type'],
            'section_id'      => $data['section_id'],
            'grade_id'        => $data['grade_id'],
            'branch_id'       => $data['branch_id'],
            'field_id'        => $data['field_id'],
            'subfield_id'     => $data['subfield_id'] ?? null,
            'subject_type_id' => $data['subject_type_id'] ?? null,
            'subjects'        => $data['subjects'],
            'is_active'       => (bool)($data['is_active'] ?? true),
        ]);

        return redirect()
            ->route('teacher.exams.show', $exam->id)
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
        return view('dashboard.teacher.exams.edit', compact('exam'));
    }

    public function update(Request $request, Exam $exam)
    {
        abort_unless($exam->teacher_id === Auth::id(), 403);

        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration'    => 'required|integer|min:15|max:300',
            'is_active'   => 'nullable|boolean',
        ]);

        $exam->update([
            'title'       => $data['title'],
            'description' => $data['description'] ?? null,
            'duration'    => $data['duration'],
            'is_active'   => (bool)($data['is_active'] ?? true),
        ]);

        return back()->with('success', 'آزمون بروزرسانی شد.');
    }

    public function destroy(Exam $exam)
    {
        abort_unless($exam->teacher_id === Auth::id(), 403);
        $exam->delete();
        return back()->with('success', 'آزمون حذف شد.');
    }

    // ==========================================================
    // ✅ AJAX Taxonomy Endpoints
    // ==========================================================

    // 1) Sections
    public function sections()
    {
        $sections = Section::where('is_active', 1)
            ->orderBy('sort_order')
            ->get(['id','name_fa','slug']);

        return response()->json(['sections' => $sections]);
    }

    // 2) Grades (filter by section_id)
    public function grades(Request $request)
    {
        $q = Grade::query()
            ->where('is_active', 1)
            ->orderBy('sort_order');

        if ($request->filled('section_id')) {
            $q->where('section_id', $request->section_id);
        }

        $grades = $q->get(['id','name_fa','slug','section_id']);

        return response()->json(['grades' => $grades]);
    }

    // 3) Branches (filter by section_id AND/OR grade_id)
    public function branches(Request $request)
    {
        $q = Branch::query()
            ->where('is_active', 1)
            ->orderBy('sort_order');

        if ($request->filled('section_id')) {
            $q->where('section_id', $request->section_id);
        }

        // اگر در DB شاخه‌ها به پایه هم وصل باشند، این فیلتر را فعال می‌کند
        if ($request->filled('grade_id') && Schema::hasColumn('branches', 'grade_id')) {
            $q->where('grade_id', $request->grade_id);
        }

        $branches = $q->get(['id','name_fa','slug','section_id']);

        return response()->json(['branches' => $branches]);
    }

    // 4) Fields (filter by branch_id)
    public function fields(Request $request)
    {
        $q = Field::query()
            ->where('is_active', 1)
            ->orderBy('sort_order');

        if ($request->filled('branch_id')) {
            $q->where('branch_id', $request->branch_id);
        }

        $fields = $q->get(['id','name_fa','slug','branch_id']);

        return response()->json(['fields' => $fields]);
    }

    // 5) Subfields (filter by field_id)
    public function subfields(Request $request)
    {
        $q = Subfield::query()
            ->where('is_active', 1)
            ->orderBy('sort_order');

        if ($request->filled('field_id')) {
            $q->where('field_id', $request->field_id);
        }

        $subfields = $q->get(['id','name_fa','slug','field_id']);

        return response()->json(['subfields' => $subfields]);
    }

    // 6) Subject Types (optionally filter by section/grade/branch/field/subfield if columns exist on DB)
    public function subjectTypes(Request $request)
    {
        $q = SubjectType::query()
            ->where('is_active', 1)
            ->orderBy('sort_order');

        // اگر subject_types جدولِ ربطی ندارد، اینها بی‌اثرند
        foreach (['section_id','grade_id','branch_id','field_id','subfield_id'] as $col) {
            if ($request->filled($col) && \Schema::hasColumn('subject_types', $col)) {
                $q->where($col, $request->$col);
            }
        }

        $subjectTypes = $q->get(['id','name_fa','slug','coefficient','weight_percent','default_question_count']);

        return response()->json(['subjectTypes' => $subjectTypes]);
    }

    // 7) Subjects (filter by grade/branch/field/subfield/subject_type)
    public function subjects(Request $request)
    {
        $q = Subject::query()
            ->where('is_active', 1)
            ->orderBy('sort_order');

        foreach (['section_id','grade_id','branch_id','field_id','subfield_id','subject_type_id'] as $col) {
            if ($request->filled($col)) {
                $q->where($col, $request->$col);
            }
        }

        $subjects = $q->get(['id','title_fa','slug','grade_id','branch_id','field_id','subfield_id','subject_type_id']);

        return response()->json(['subjects' => $subjects]);
    }
}
