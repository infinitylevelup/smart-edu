<?php

namespace App\Http\Controllers\Admin;

use App\Models\Branch;
use App\Models\Section;

class BranchController extends BaseTaxonomyController
{
    protected string $modelClass = Branch::class;
    protected string $viewPath   = 'dashboard.admin.taxonomies.branches';
    protected string $routeName  = 'admin.branches';
    protected ?string $title     = 'شاخه‌ها';

    protected array $validation = [
        // ✅ section_id الان عددی است نه UUID
        'section_id' => 'required|integer|exists:sections,id',
        'slug'       => 'required|string|max:100|unique:branches,slug',
        'name_fa'    => 'required|string|max:200',
        'sort_order' => 'nullable|integer|min:0',
        'is_active'  => 'nullable|boolean',
    ];

    protected array $sortable = ['name_fa','slug','sort_order','is_active','created_at'];
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
