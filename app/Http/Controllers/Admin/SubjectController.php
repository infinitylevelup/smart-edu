<?php

namespace App\Http\Controllers\Admin;

use App\Models\Subject;
use App\Models\Grade;
use App\Models\Section;
use App\Models\Branch;
use App\Models\Field;
use App\Models\Subfield;
use App\Models\SubjectType;

class SubjectController extends BaseTaxonomyController
{
    protected string $modelClass = Subject::class;
    protected string $viewPath   = 'dashboard.admin.taxonomies.subjects';
    protected string $routeName  = 'admin.subjects';
    protected ?string $title     = 'درس‌ها';

    protected array $validation = [
        // ✅ همه FK ها عددی هستند
        'section_id'      => 'required|integer|exists:sections,id',
        'grade_id'        => 'required|integer|exists:grades,id',
        'branch_id'       => 'required|integer|exists:branches,id',
        'field_id'        => 'required|integer|exists:fields,id',
        'subfield_id'     => 'nullable|integer|exists:subfields,id',
        'subject_type_id' => 'nullable|integer|exists:subject_types,id',

        'title_fa'   => 'required|string|max:200',
        'slug'       => 'required|string|max:100|unique:subjects,slug',
        'code'       => 'nullable|string|max:50',
        'hours'      => 'nullable|integer|min:0',
        'sort_order' => 'nullable|integer|min:0',
        'is_active'  => 'nullable|boolean',
    ];

    protected array $sortable = [
        'title_fa','slug','code',
        'section_id','grade_id','branch_id','field_id','subfield_id','subject_type_id',
        'sort_order','is_active','created_at'
    ];
    protected string $defaultSort = 'sort_order';
    protected string $defaultDir  = 'asc';

    /**
     * داده‌های والد برای dropdown ها در create/edit
     * اگر create شما ترکیبی باشد (فرم + لیست)، همین کافی است.
     */
    protected function parentData(): array
    {
        return [
            'sections' => Section::where('is_active', 1)
                ->orderBy('sort_order')
                ->get(['id','name_fa']),

            'grades' => Grade::where('is_active', 1)
                ->orderBy('sort_order')
                ->get(['id','name_fa','section_id']),

            'branches' => Branch::where('is_active', 1)
                ->orderBy('sort_order')
                ->get(['id','name_fa','section_id']),

            'fields' => Field::where('is_active', 1)
                ->orderBy('sort_order')
                ->get(['id','name_fa','branch_id']),

            'subfields' => Subfield::where('is_active', 1)
                ->orderBy('sort_order')
                ->get(['id','name_fa','field_id']),

            'subjectTypes' => SubjectType::where('is_active', 1)
                ->orderBy('sort_order')
                ->get(['id','name_fa']),
        ];
    }

    /**
     * ✅ API قبلی که در routes/admin.php داری:
     * /dashboard/admin/api/subjects/by-grade/{grade}
     * اینجا grade عددی است.
     */
    public function byGrade(int $gradeId)
    {
        $subjects = Subject::where('grade_id', $gradeId)
            ->where('is_active', 1)
            ->orderBy('sort_order')
            ->get(['id','title_fa']);

        return response()->json($subjects);
    }
}
