<?php

namespace App\Http\Controllers\Admin;

use App\Models\Grade;
use App\Models\Section;

class GradeController extends BaseTaxonomyController
{
    protected string $modelClass = Grade::class;
    protected string $viewPath   = 'dashboard.admin.taxonomy.grades';
    protected string $routeName  = 'admin.grades';
    protected ?string $title     = 'پایه‌ها';

    protected array $validation = [
        'section_id' => 'required|uuid|exists:sections,id',
        'value'      => 'required|integer|min:1',
        'slug'       => 'required|string|max:100|unique:grades,slug',
        'name_fa'    => 'required|string|max:200',
        'sort_order' => 'nullable|integer|min:0',
        'is_active'  => 'nullable|boolean',
    ];

    // ✅ مرتب‌سازی مثل مقطع‌ها
    protected array $sortable = ['name_fa','slug','value','sort_order','is_active','created_at'];
    protected string $defaultSort = 'sort_order';
    protected string $defaultDir  = 'asc';

    protected function parentData(): array
    {
        return [
            'sections' => Section::where('is_active', 1)
                ->orderBy('sort_order')
                ->get(['id','name_fa']),
        ];
    }
}
