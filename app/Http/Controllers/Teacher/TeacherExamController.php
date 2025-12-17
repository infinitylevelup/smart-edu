<?php

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
use Morilog\Jalali\Jalalian;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TeacherExamController extends Controller
{
    // ===========================================================
    // Exams CRUD
    // ===========================================================

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

        // کلاس‌های معلم
        $classrooms = Classroom::where('teacher_id', auth::id())
            ->orderBy('title')
            ->get();

        // داده‌های مورد نیاز برای ویزارد
        $grades = Grade::where('is_active', 1)
            ->orderBy('sort_order')
            ->get(['id', 'name_fa', 'slug', 'section_id']);

        $subjectTypes = SubjectType::where('is_active', 1)
            ->orderBy('sort_order')
            ->get(['id', 'name_fa', 'slug']);

        // داده‌های طبقه‌بندی برای JavaScript
        $sections = Section::where('is_active', 1)
            ->orderBy('sort_order')
            ->get(['id', 'name_fa', 'slug']);

        $branches = Branch::where('is_active', 1)
            ->orderBy('sort_order')
            ->get(['id', 'name_fa', 'slug', 'section_id']);

        $fields = Field::where('is_active', 1)
            ->orderBy('sort_order')
            ->get(['id', 'name_fa', 'slug', 'branch_id']);

        $subfields = Subfield::where('is_active', 1)
            ->orderBy('sort_order')
            ->get(['id', 'name_fa', 'slug', 'field_id']);

        $subjects = Subject::where('is_active', 1)
            ->orderBy('title_fa')
            ->get(['id', 'uuid', 'title_fa', 'slug', 'code', 'grade_id', 'branch_id', 'field_id', 'subfield_id', 'subject_type_id']);

        return view('dashboard.teacher.exams.create', compact(
            'selectedClassroomId',
            'classrooms',
            'grades',
            'subjectTypes',
            'sections',
            'branches',
            'fields',
            'subfields',
            'subjects'
        ));
    }

    public function store(StoreExamRequest $request)
    {
        DB::beginTransaction();
        try {
            $v = $request->validated();
            $teacherId = Auth::id();

            Log::info('✅ Validation passed');
            Log::info('Teacher ID:', ['id' => $teacherId]);


            // ============================================
            // 📅 تبدیل تاریخ‌های شمسی به میلادی
            // ============================================
            $startAt = null;
            $endAt = null;

            if (!empty($v['start_at'])) {
                Log::info('Processing start_at:', ['date' => $v['start_at']]);
                try {
                    $dateString = $this->normalizePersianDate($v['start_at']);
                    $startAt = Jalalian::fromFormat('Y/m/d H:i', $dateString)
                        ->toCarbon()
                        ->format('Y-m-d H:i:s');
                    Log::info('✅ Start date converted:', ['gregorian' => $startAt]);
                } catch (\Exception $e) {
                    Log::error('❌ Start date conversion failed:', [
                        'error' => $e->getMessage(),
                        'date'  => $v['start_at']
                    ]);
                    $startAt = null;
                }
            }

            if (!empty($v['end_at'])) {
                try {
                    $dateString = $this->normalizePersianDate($v['end_at']);
                    $endAt = Jalalian::fromFormat('Y/m/d H:i', $dateString)
                        ->toCarbon()
                        ->format('Y-m-d H:i:s');
                } catch (\Exception $e) {
                    $endAt = null;
                }
            }

            // ============================================
            // 🎯 تشخیص نوع آزمون و ایجاد
            // ============================================
            $examType = (string) $v['exam_type'];
            Log::info('Exam type:', ['type' => $examType]);

            // 🔴 اصلاح شده: بخش آزمون کلاسی کامل شد
            if ($this->isClassExamType($examType)) {
                // آزمون کلاسی
                Log::info('Creating class exam...', ['exam_type' => $examType]);

                // 1. پیدا کردن کلاس
                $classroomId = $v['classroom_id'] ?? null;
                if (!$classroomId) {
                    Log::error('❌ classroom_id is missing for class exam');
                    DB::rollBack();
                    return back()->withErrors(['classroom_id' => 'برای آزمون کلاسی، کلاس باید انتخاب شود.'])->withInput();
                }

                $classroom = Classroom::find($classroomId);
                if (!$classroom) {
                    Log::error('❌ Classroom not found:', ['id' => $classroomId]);
                    DB::rollBack();
                    return back()->withErrors(['classroom_id' => 'کلاس انتخاب شده یافت نشد.'])->withInput();
                }

                // 2. بررسی مالکیت کلاس
                if ($classroom->teacher_id != $teacherId) {
                    Log::error('❌ Teacher does not own this classroom:', [
                        'teacher_id'           => $teacherId,
                        'classroom_teacher_id' => $classroom->teacher_id
                    ]);
                    DB::rollBack();
                    return back()->withErrors(['error' => 'شما به این کلاس دسترسی ندارید.'])->withInput();
                }

                // 3. تعیین exam_mode و primary_subject
                $examMode = ($examType === 'class_single') ? 'single_subject' : 'multi_subject';
                $primarySubjectId = ($examType === 'class_single') ? $classroom->subject_id : null;

                // 4. ایجاد آزمون کلاسی
                $exam = Exam::create([
                    'user_id'     => $teacherId,
                    'teacher_id'  => $teacherId,
                    'classroom_id'=> $classroomId,

                    'exam_type'   => $examType,
                    'exam_mode'   => $examMode,
                    'primary_subject_id' => $primarySubjectId,

                    'title'       => $v['title'],
                    'description' => $v['description'] ?? null,
                    'duration_minutes' => $v['duration_minutes'],

                    'start_at'    => $startAt,
                    'end_at'      => $endAt,

                    // taxonomy از کلاس
                    'section_id'      => $classroom->section_id,
                    'grade_id'        => $classroom->grade_id,
                    'branch_id'       => $classroom->branch_id,
                    'field_id'        => $classroom->field_id,
                    'subfield_id'     => $classroom->subfield_id,
                    'subject_type_id' => $classroom->subject_type_id,

                    'is_active'   => $request->boolean('is_active', true),
                    'is_published'=> $request->boolean('is_published', false),
                ]);

                Log::info('✅ Class exam created:', ['id' => $exam->id, 'title' => $exam->title]);

                // 5. sync دروس
                if ($examType === 'class_comprehensive') {
                    $subjectIds = $classroom->subjects()->pluck('subjects.id')->all();
                    if (!empty($subjectIds)) {
                        $exam->subjects()->sync($subjectIds);
                        Log::info('✅ Subjects synced for comprehensive class:', $subjectIds);
                    }
                } else if ($examType === 'class_single' && $classroom->subject_id) {
                    $exam->subjects()->sync([$classroom->subject_id]);
                    Log::info('✅ Subject synced for single class:', [$classroom->subject_id]);
                }

            } else {
                // آزمون عمومی
                Log::info('Creating public exam...');

                // subjects
                $subjectsRaw = $this->decodeSubjects($v['subjects']);
                Log::info('✅ Subjects raw from input:', $subjectsRaw);

                Log::info('📋 DEBUG - Raw subjects JSON:', ['json' => $v['subjects']]);

                $subjectIds = $this->resolveSubjectIds($subjectsRaw);
                Log::info('✅ Subject IDs resolved:', $subjectIds);

                // اعتبارسنجی subjects
                if (empty($subjectIds)) {
                    Log::warning('⚠️ No valid subjects after filtering');
                    DB::rollBack();
                    return back()
                        ->withErrors(['subjects' => 'لطفاً حداقل یک درس معتبر انتخاب کنید.'])
                        ->withInput();
                }

                // بررسی معتبر بودن subjectها
                $validSubjects = Subject::whereIn('id', $subjectIds)
                    ->where('is_active', 1)
                    ->count();

                if ($validSubjects !== count($subjectIds)) {
                    Log::warning('⚠️ Some subject IDs are invalid or inactive', [
                        'requested'   => $subjectIds,
                        'valid_count' => $validSubjects
                    ]);
                    DB::rollBack();
                    return back()
                        ->withErrors(['subjects' => 'برخی دروس انتخابی معتبر نیستند یا غیرفعال هستند.'])
                        ->withInput();
                }

                $examMode = (count($subjectIds) === 1) ? 'single_subject' : 'multi_subject';
                $primarySubjectId = ($examMode === 'single_subject') ? $subjectIds[0] : null;

                Log::info('Exam mode:', ['mode' => $examMode, 'primary_id' => $primarySubjectId]);

                $subjectTypeId = $this->resolveId(SubjectType::class, $v['subject_type_id']);
                Log::info('Subject type ID:', ['id' => $subjectTypeId]);

                // ایجاد آزمون عمومی
                Log::info('Creating exam record with data:', [
                    'teacher_id' => $teacherId,
                    'exam_type'  => 'public',
                    'exam_mode'  => $examMode,
                    'title'      => $v['title'],
                    'duration'   => $v['duration_minutes'],
                    'start_at'   => $startAt,
                    'end_at'     => $endAt,
                ]);

                $exam = Exam::create([
                    'user_id'    => $teacherId,
                    'teacher_id' => $teacherId,
                    'classroom_id'=> null,

                    'exam_type'  => 'public',
                    'exam_mode'  => $examMode,
                    'primary_subject_id' => $primarySubjectId,

                    'title'      => $v['title'],
                    'description'=> $v['description'] ?? null,
                    'duration_minutes' => $v['duration_minutes'],

                    'start_at'   => $startAt,
                    'end_at'     => $endAt,

                    'section_id' => $v['section_id'],
                    'grade_id'   => $v['grade_id'],
                    'branch_id'  => $v['branch_id'],
                    'field_id'   => $v['field_id'],
                    'subfield_id'=> $v['subfield_id'],
                    'subject_type_id' => $subjectTypeId,

                    'is_active'  => $request->boolean('is_active', true),
                    'is_published'=> $request->boolean('is_published', false),
                ]);

                Log::info('✅ Exam created:', ['id' => $exam->id]);

                // sync subjects
                Log::info('Syncing subjects:', ['subject_ids' => $subjectIds]);

                try {
                    $exam->subjects()->sync($subjectIds);
                    Log::info('✅ Subjects synced successfully');
                } catch (\Exception $e) {
                    Log::error('❌ Subject sync failed:', [
                        'error'       => $e->getMessage(),
                        'exam_id'     => $exam->id,
                        'subject_ids' => $subjectIds
                    ]);
                    throw $e;
                }
            }

            // 🔴 اصلاح مهم: اضافه کردن دیباگ قبل از redirect
            Log::info('🎯 FINAL DEBUG - About to redirect', [
                'exam_id'       => $exam->id ?? null,
                'exam_type'     => $examType,
                'route'         => 'teacher.exams.index',
                'redirect_url'  => route('teacher.exams.index')
            ]);

            DB::commit();
            Log::info('✅ Transaction committed');

            // 🔴 اصلاح: اضافه کردن headerهای دیباگ
            return redirect()
                ->route('teacher.exams.index')
                ->with('success', 'آزمون ' . ($examType === 'public' ? 'عمومی' : 'کلاسی') . ' با موفقیت ایجاد شد.')
                ->with('debug_exam_id', $exam->id ?? 0)
                ->with('debug_exam_type', $examType);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('❌ FINAL ERROR in store():', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'trace'   => $e->getTraceAsString()
            ]);

            return back()
                ->withErrors(['error' => 'خطا در ایجاد آزمون: ' . $e->getMessage()])
                ->withInput();
        }
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

        DB::beginTransaction();
        try {
            $v = $request->validated();

            $baseUpdate = [
                'title' => $v['title'],
                'description' => $v['description'] ?? null,
                'duration_minutes' => $v['duration_minutes'],

                'shuffle_questions' => $request->boolean('shuffle_questions', $exam->shuffle_questions),
                'shuffle_options' => $request->boolean('shuffle_options', $exam->shuffle_options),
                'ai_assisted' => $request->boolean('ai_assisted', $exam->ai_assisted),

                'is_active' => $request->boolean('is_active', $exam->is_active),
                'is_published' => $request->boolean('is_published', $exam->is_published),
            ];

            // تبدیل تاریخ‌های شمسی به میلادی
            if (!empty($v['start_at'])) {
                try {
                    $dateString = $this->normalizePersianDate($v['start_at']);
                    $baseUpdate['start_at'] = Jalalian::fromFormat('Y/m/d H:i', $dateString)
                        ->toCarbon()
                        ->format('Y-m-d H:i:s');
                } catch (\Exception $e) {
                    Log::error('خطا در تبدیل تاریخ شروع (آپدیت):', [
                        'تاریخ' => $v['start_at'],
                        'خطا' => $e->getMessage()
                    ]);
                    $baseUpdate['start_at'] = $exam->start_at;
                }
            } else {
                $baseUpdate['start_at'] = $exam->start_at;
            }

            if (!empty($v['end_at'])) {
                try {
                    $dateString = $this->normalizePersianDate($v['end_at']);
                    $baseUpdate['end_at'] = Jalalian::fromFormat('Y/m/d H:i', $dateString)
                        ->toCarbon()
                        ->format('Y-m-d H:i:s');
                } catch (\Exception $e) {
                    Log::error('خطا در تبدیل تاریخ پایان (آپدیت):', [
                        'تاریخ' => $v['end_at'],
                        'خطا' => $e->getMessage()
                    ]);
                    $baseUpdate['end_at'] = $exam->end_at;
                }
            } else {
                $baseUpdate['end_at'] = $exam->end_at;
            }

            // CLASS EXAM: فقط آپدیت فیلدهای عمومی
            if ($exam->exam_type !== 'public') {
                $exam->update($baseUpdate);

                DB::commit();

                Log::info('✅ Class exam updated:', ['id' => $exam->id]);

                return redirect()
                    ->route('teacher.exams.index')
                    ->with('success', 'آزمون کلاسی بروزرسانی شد.');
            }

            // PUBLIC EXAM: اگر ساختار فرستاده شد
            $structKeys = ['section_id','grade_id','branch_id','field_id','subfield_id','subject_type_id','subjects'];
            $hasStructUpdate = collect($structKeys)->some(fn($k) => $request->filled($k));

            if ($hasStructUpdate) {
                $subjectIds = $exam->subjects()->pluck('subjects.id')->all();

                if ($request->filled('subjects')) {
                    $subjectsRaw = $this->decodeSubjects((string) $request->input('subjects'));
                    $subjectIds = $this->resolveSubjectIds($subjectsRaw);
                }

                if (count($subjectIds) === 0) {
                    DB::rollBack();
                    return back()->withErrors(['subjects' => 'حداقل یک درس معتبر باید انتخاب شود.'])->withInput();
                }

                $examMode = (count($subjectIds) === 1) ? 'single_subject' : 'multi_subject';
                $primarySubjectId = ($examMode === 'single_subject') ? $subjectIds[0] : null;

                $subjectTypeId = $exam->subject_type_id;
                if ($request->filled('subject_type_id')) {
                    $subjectTypeId = $this->resolveId(SubjectType::class, $request->input('subject_type_id'));
                }

                $exam->update($baseUpdate + [
                    'exam_mode' => $examMode,
                    'primary_subject_id' => $primarySubjectId,

                    'section_id' => $v['section_id'] ?? $exam->section_id,
                    'grade_id' => $v['grade_id'] ?? $exam->grade_id,
                    'branch_id' => $v['branch_id'] ?? $exam->branch_id,
                    'field_id' => $v['field_id'] ?? $exam->field_id,
                    'subfield_id' => $v['subfield_id'] ?? $exam->subfield_id,
                    'subject_type_id' => $subjectTypeId,
                ]);

                $exam->subjects()->sync($subjectIds);

                DB::commit();

                return redirect()
                    ->route('teacher.exams.index')
                    ->with('success', 'آزمون عمومی (همراه با ساختار) بروزرسانی شد.');
            }

            // PUBLIC بدون تغییر ساختار
            $exam->update($baseUpdate);

            DB::commit();

            return redirect()
                ->route('teacher.exams.index')
                ->with('success', 'آزمون بروزرسانی شد.');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('❌ بروزرسانی آزمون ناموفق:', [
                'exam_id' => $exam->id,
                'خطا' => $e->getMessage(),
                'ردیابی' => $e->getTraceAsString()
            ]);

            return back()
                ->withErrors(['error' => 'خطا در بروزرسانی آزمون: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function destroy(Exam $exam)
    {
        abort_unless((int) $exam->teacher_id === (int) Auth::id(), 403);
        $exam->delete();
        return back()->with('success', 'آزمون حذف شد.');
    }

    // ===========================================================
    // Helpers
    // ===========================================================

    private function isClassExamType(string $examType): bool
    {
        return $examType !== 'public';
    }

/**
 * @return array<int, string|int>
 */
private function decodeSubjects(string $json): array
{
    if (empty($json) || $json === '""' || $json === '[]') {
        return [];
    }

    $arr = json_decode($json, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        Log::error('JSON decode error in subjects:', [
            'input' => $json,
            'error' => json_last_error_msg()
        ]);
        return [];
    }

    if (!is_array($arr)) {
        return [];
    }

    // ✅ اگر آیتم‌ها object باشند (مثل {"id":1,"uuid":"..."}), فقط uuid یا id را استخراج کن
    $out = [];
    foreach ($arr as $item) {
        if (is_array($item)) {
            if (!empty($item['uuid'])) {
                $out[] = (string) $item['uuid'];
                continue;
            }
            if (isset($item['id']) && $item['id'] !== '') {
                $out[] = is_numeric($item['id']) ? (int) $item['id'] : (string) $item['id'];
                continue;
            }
        }

        // اگر آیتم scalar بود (uuid یا id)
        if (is_string($item) || is_int($item)) {
            $out[] = $item;
        }
    }

    return $out;
}

/**
 * @param array<int, string|int> $idsOrUuids
 * @return array<int, int>
 */
private function resolveSubjectIds(array $idsOrUuids): array
{
    $idsOrUuids = array_values(array_filter($idsOrUuids, function ($v) {
        return $v !== null && $v !== '' && $v !== "0" && $v !== 0;
    }));

    Log::info('📊 Resolving subject IDs from:', $idsOrUuids);

    $uuids = [];
    $ids = [];

    foreach ($idsOrUuids as $v) {
        $v = (string) $v;

        if (Str::isUuid($v)) {
            $uuids[] = $v;
        } elseif (ctype_digit($v)) {
            $ids[] = (int) $v;
        }
    }

    $q = Subject::query()->where('is_active', 1);

    $found = collect();

    if (count($uuids)) {
        $found = $found->merge($q->clone()->whereIn('uuid', $uuids)->pluck('id'));
    }
    if (count($ids)) {
        $found = $found->merge($q->clone()->whereIn('id', $ids)->pluck('id'));
    }

    return $found->unique()->values()->all();
}


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

    private function normalizePersianDate($dateString): string
    {
        $dateString = str_replace(["\u{200C}", "‌"], '', $dateString);
        $dateString = preg_replace('/\s+/', ' ', trim($dateString));

        $persianNumbers = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
        $englishNumbers = ['0','1','2','3','4','5','6','7','8','9'];

        return str_replace($persianNumbers, $englishNumbers, $dateString);
    }

    // ===========================================================
    // AJAX Taxonomy Endpoints
    // ===========================================================

    public function sections()
    {
        $sections = Section::where('is_active', 1)
            ->orderBy('sort_order')
            ->get(['id', 'name_fa', 'slug']);

        return response()->json(['sections' => $sections]);
    }

    public function grades(Request $request)
    {
        $sectionId = $request->get('section_id');

        $grades = Grade::where('is_active', 1)
            ->when($sectionId, fn($q) => $q->where('section_id', $sectionId))
            ->orderBy('sort_order')
            ->get(['id', 'name_fa', 'slug', 'section_id']);

        return response()->json(['grades' => $grades]);
    }

    public function branches(Request $request)
    {
        $sectionId = $request->get('section_id');

        $branches = Branch::where('is_active', 1)
            ->when($sectionId, fn($q) => $q->where('section_id', $sectionId))
            ->orderBy('sort_order')
            ->get(['id', 'name_fa', 'slug', 'section_id']);

        return response()->json(['branches' => $branches]);
    }

    public function fields(Request $request)
    {
        $branchId = $request->get('branch_id');

        $fields = Field::where('is_active', 1)
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->orderBy('sort_order')
            ->get(['id', 'name_fa', 'slug', 'branch_id']);

        return response()->json(['fields' => $fields]);
    }

    public function subfields(Request $request)
    {
        $fieldId = $request->get('field_id');

        $subfields = Subfield::where('is_active', 1)
            ->when($fieldId, fn($q) => $q->where('field_id', $fieldId))
            ->orderBy('sort_order')
            ->get(['id', 'name_fa', 'slug', 'field_id']);

        return response()->json(['subfields' => $subfields]);
    }

    public function subjects(Request $request)
    {
        $q = Subject::query()->where('is_active', 1);

        $gradeId = $this->resolveId(Grade::class, $request->grade_id);
        $branchId = $this->resolveId(Branch::class, $request->branch_id);
        $fieldId = $this->resolveId(Field::class, $request->field_id);
        $subfieldId = $this->resolveId(Subfield::class, $request->subfield_id);

        if ($gradeId) $q->where('grade_id', $gradeId);
        if ($branchId) $q->where('branch_id', $branchId);
        if ($fieldId) $q->where('field_id', $fieldId);
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
            Log::error('subjectTypes error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'subject_types' => [],
                'message' => 'Server error in subjectTypes'
            ], 500);
        }
    }
}
