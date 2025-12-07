<?php

namespace App\Http\Controllers\Admin;

use App\Models\SubjectType;

class SubjectTypeController extends BaseTaxonomyController
{
    protected string $modelClass = SubjectType::class;
    protected string $viewPath   = 'dashboard.admin.taxonomy.subject-types';
    protected string $routeName  = 'admin.subject-types';
    protected ?string $title     = 'نوع درس‌ها';

    protected array $validation = [
        'slug'       => 'required|string|max:100|unique:subject_types,slug',
        'name_fa'    => 'required|string|max:200',
        'sort_order' => 'nullable|integer|min:0',
        'is_active'  => 'nullable|boolean',
    ];

    protected array $sortable = ['name_fa','slug','sort_order','is_active','created_at'];
    protected string $defaultSort = 'sort_order';
    protected string $defaultDir  = 'asc';
}
