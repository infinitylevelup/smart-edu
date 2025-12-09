<?php

namespace App\Http\Controllers\Admin;

use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SectionController extends BaseTaxonomyController
{
    protected string $modelClass = Section::class;
    protected string $viewPath   = 'dashboard.admin.taxonomies.sections';
    protected string $routeName  = 'admin.sections';
    protected ?string $title     = 'مقطع‌ها';

    protected array $validation = [
        'slug'       => 'required|string|max:100|unique:sections,slug',
        'name_fa'    => 'required|string|max:200',
        'sort_order' => 'nullable|integer|min:0',
        'is_active'  => 'nullable|boolean',
    ];

    // -----------------------------
    // ✅ سورت قطعی مخصوص Sections
    // -----------------------------
    protected array $sortable = ['name_fa','slug','sort_order','is_active','created_at'];
    protected string $defaultSort = 'sort_order';
    protected string $defaultDir  = 'asc';

    public function index()
    {
        $sort = request('sort', $this->defaultSort);
        $dir  = request('dir',  $this->defaultDir);

        if (!in_array($sort, $this->sortable)) {
            $sort = $this->defaultSort;
        }

        $dir = strtolower($dir) === 'desc' ? 'desc' : 'asc';

        $items = Section::orderBy($sort, $dir)
            ->paginate(20)
            ->withQueryString();

        return view($this->viewPath.'.index', [
            'items'     => $items,
            'routeName' => $this->routeName,
            'title'     => $this->title(),
            'sort'      => $sort,
            'dir'       => $dir,
        ]);
    }

    // بقیه متدها از Base ارث می‌بره و نیاز نیست چیزی اضافه کنی
}
