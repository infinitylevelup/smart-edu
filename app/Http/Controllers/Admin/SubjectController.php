<?php

namespace App\Http\Controllers\Admin;

use App\Models\Section;
use App\Models\Grade;
use App\Models\Branch;
use App\Models\Field;
use App\Models\Subfield;
use App\Models\SubjectType;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends BaseTaxonomyController
{
    protected string $modelClass = Subject::class;
    protected string $viewPath   = 'dashboard.admin.taxonomies.subjects';
    protected string $routeName  = 'admin.subjects';
    protected ?string $title     = 'دروس';

    protected array $validation = [
        'title_fa'        => 'required|string|max:255',
        'slug'            => 'required|string|max:255|unique:subjects,slug',
        'code'            => 'nullable|string|max:50',
        'hours'           => 'nullable|integer|min:0',
        'sort_order'      => 'nullable|integer|min:0',
        'is_active'       => 'nullable|boolean',

        'section_id'      => 'required|uuid|exists:sections,id',
        'grade_id'        => 'required|uuid|exists:grades,id',
        'branch_id'       => 'required|uuid|exists:branches,id',
        'field_id'        => 'required|uuid|exists:fields,id',
        'subfield_id'     => 'nullable|uuid|exists:subfields,id',
        'subject_type_id' => 'nullable|uuid|exists:subject_types,id',
    ];

    protected array $sortable = [
        'title_fa','slug','code','hours','sort_order','is_active','created_at'
    ];
    protected string $defaultSort = 'sort_order';
    protected string $defaultDir  = 'asc';

    protected function parentData(): array
    {
        return [
            'sections'     => Section::where('is_active', 1)->orderBy('sort_order')->get(),
            'grades'       => Grade::where('is_active', 1)->orderBy('sort_order')->get(),
            'branches'     => Branch::where('is_active', 1)->orderBy('sort_order')->get(),
            'fields'       => Field::where('is_active', 1)->orderBy('sort_order')->get(),
            'subfields'    => Subfield::where('is_active', 1)->orderBy('sort_order')->get(),
            'subjectTypes' => SubjectType::where('is_active', 1)->orderBy('sort_order')->get(),
        ];
    }

    // --------------------------
    // ✅ برای dropdown وابسته
    // --------------------------
    public function byGrade(string $gradeId)
    {
        $subjects = Subject::where('grade_id', $gradeId)
            ->orderBy('sort_order')
            ->get(['id','title_fa']);

        return response()->json($subjects);
    }
}
