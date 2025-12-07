<?php

namespace App\Http\Controllers\Admin;

use App\Models\Field;
use App\Models\Branch;

class FieldController extends BaseTaxonomyController
{
    protected string $modelClass = Field::class;
    protected string $viewPath   = 'dashboard.admin.taxonomy.fields';
    protected string $routeName  = 'admin.fields';
    protected ?string $title     = 'زمینه‌ها';

    protected array $validation = [
        'branch_id'  => 'required|uuid|exists:branches,id',
        'slug'       => 'required|string|max:100|unique:fields,slug',
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
            'branches' => Branch::where('is_active', 1)
                ->orderBy('sort_order')
                ->get(['id','name_fa']),
        ];
    }
}
