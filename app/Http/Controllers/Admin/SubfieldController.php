<?php

namespace App\Http\Controllers\Admin;

use App\Models\Subfield;
use App\Models\Field;

class SubfieldController extends BaseTaxonomyController
{
    protected string $modelClass = Subfield::class;
    protected string $viewPath   = 'dashboard.admin.taxonomies.subfields';
    protected string $routeName  = 'admin.subfields';
    protected ?string $title     = 'زیررشته‌ها';

    protected array $validation = [
        'field_id'   => 'required|uuid|exists:fields,id',
        'slug'       => 'required|string|max:100|unique:subfields,slug',
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
            'fields' => Field::where('is_active', 1)
                ->orderBy('sort_order')
                ->get(['id','name_fa']),
        ];
    }
}
