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
        $teacherId = Auth::id();

        // اول exam_type را بگیر تا ولیدیشن شرطی کنیم
        $examType = $request->input('exam_type');

        $rules = [
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'duration'         => 'required|integer|min:15|max:300',  // دقیقه
            'exam_type'        => 'required|in:public,class_single,class_comprehensive',

            'classroom_id'     => 'nullable|uuid|exists:classrooms,id',

            // taxonomy ها در آزمون عمومی اجباری‌اند
            'section_id'       => 'nullable|uuid|exists:sections,id',
            'grade_id'         => 'nullable|uuid|exists:grades,id',
            'branch_id'        => 'nullable|uuid|exists:branches,id',
            'field_id'         => 'nullable|uuid|exists:fields,id',
            'subfield_id'      => 'nullable|uuid|exists:subfields,id',
            'subject_type_id'  => 'nullable|uuid|exists:subject_types,id',

            // از فرانت میاد: JSON array یا comma list
            'subjects'         => 'required|string',
            'is_active'        => 'nullable|boolean',
        ];

        // اگر عمومی است taxonomy باید حتماً پر باشد
        if ($examType === 'public') {
            $rules['section_id'] = 'required|uuid|exists:sections,id';
            $rules['grade_id']   = 'required|uuid|exists:grades,id';
            $rules['branch_id']  = 'required|uuid|exists:branches,id';
            $rules['field_id']   = 'required|uuid|exists:fields,id';
            // بقیه می‌تواند نال باشد
        }

        // اگر آزمون کلاسی است classroom_id اجباری
        if (in_array($examType, ['class_single', 'class_comprehensive'])) {
            $rules['classroom_id'] = 'required|uuid|exists:classrooms,id';
        }

        $data = $request->validate($rules);

        // ---------- مالکیت کلاس در آزمون کلاسی ----------
        $class = null;
        if (!empty($data['classroom_id'])) {
            $class = Classroom::findOrFail($data['classroom_id']);
            abort_unless($class->teacher_id === $teacherId, 403);
        }

        // ---------- نرمال‌سازی subjects ----------
        // فرانت جدید JSON می‌فرسته، ولی اگر قدیمی بود و comma بود تبدیلش کن
        $subjectsRaw = trim($data['subjects']);

        if (Str::startsWith($subjectsRaw, '[')) {
            // JSON
            $subjectsArr = json_decode($subjectsRaw, true) ?: [];
        } else {
            // comma list
            $subjectsArr = array_values(array_filter(array_map('trim', explode(',', $subjectsRaw))));
        }

        // قوانین تعداد درس:
        if ($examType === 'public' || $examType === 'class_single') {
            if (count($subjectsArr) !== 1) {
                return back()->withErrors([
                    'subjects' => 'در این نوع آزمون فقط یک درس باید انتخاب شود.'
                ])->withInput();
            }
        }

        // ---------- اگر آزمون کلاسی است و taxonomy نیامده از کلاس بگیر ----------
        if ($class) {
            $data['section_id']      = $data['section_id']      ?: $class->section_id;
            $data['grade_id']        = $data['grade_id']        ?: $class->grade_id;
            $data['branch_id']       = $data['branch_id']       ?: $class->branch_id;
            $data['field_id']        = $data['field_id']        ?: $class->field_id;
            $data['subfield_id']     = $data['subfield_id']     ?: $class->subfield_id;
            // subject_type برای کلاس جامع/تک درس میتونه نال بمونه
            // subjects هم اگر خالی بود از کلاس بگیر (تک درس)
            if (empty($subjectsArr) && $class->subject_id) {
                $subjectsArr = [$class->subject_id];
            }
        }

        // ذخیره subjects به صورت JSON
        $subjectsJson = json_encode($subjectsArr, JSON_UNESCAPED_UNICODE);

        $exam = Exam::create([
            'id'              => (string) Str::uuid(),
            'teacher_id'      => $teacherId,
            'classroom_id'    => $data['classroom_id'] ?? null,
            'title'           => $data['title'],
            'description'     => $data['description'] ?? null,
            'duration'        => $data['duration'],
            'exam_type'       => $data['exam_type'],

            'section_id'      => $data['section_id'] ?? null,
            'grade_id'        => $data['grade_id'] ?? null,
            'branch_id'       => $data['branch_id'] ?? null,
            'field_id'        => $data['field_id'] ?? null,
            'subfield_id'     => $data['subfield_id'] ?? null,
            'subject_type_id' => $data['subject_type_id'] ?? null,

            'subjects'        => $subjectsJson,
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
        $grades = Grade::where('is_active', 1)
            ->orderBy('sort_order')
            ->get(['id','name_fa','slug','section_id']);

        return response()->json(['grades' => $grades]);
    }

    public function branches(Request $request)
    {
        $gradeId = $request->get('grade_id');
        $branches = Branch::where('is_active', 1)
            ->when($gradeId, fn($q)=>$q->where('grade_id',$gradeId))
            ->orderBy('sort_order')
            ->get(['id','name_fa','slug','grade_id']);

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

    public function subjectTypes(Request $request)
    {
        $subfieldId = $request->get('subfield_id');
        $subjectTypes = SubjectType::where('is_active', 1)
            ->when($subfieldId, fn($q)=>$q->where('subfield_id',$subfieldId))
            ->orderBy('sort_order')
            ->get(['id','name_fa','slug','subfield_id']);

        return response()->json(['subjectTypes' => $subjectTypes]);
    }

    public function subjects(Request $request)
    {
        $subjectTypeId = $request->get('subject_type_id');
        $fieldId = $request->get('field_id');
        $subfieldId = $request->get('subfield_id');

        $subjects = Subject::where('is_active', 1)
            ->when($subjectTypeId, fn($q)=>$q->where('subject_type_id',$subjectTypeId))
            ->when($subfieldId, fn($q)=>$q->where('subfield_id',$subfieldId))
            ->when($fieldId, fn($q)=>$q->where('field_id',$fieldId))
            ->orderBy('sort_order')
            ->get(['id','name_fa','slug','subject_type_id','field_id','subfield_id']);

        return response()->json(['subjects' => $subjects]);
    }
}
