<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;   // ✅ اضافه شد

abstract class BaseTaxonomyController extends Controller
{
    // ✅ با کنترلرهای جدیدت هماهنگ شد
    protected string $modelClass;   // Model::class
    protected string $viewPath;     // e.g. "dashboard.admin.taxonomy.sections"
    protected string $routeName;    // e.g. "admin.sections"
    protected array  $validation;   // rules

    protected array  $listColumns = ['name_fa','slug','sort_order','is_active'];
    protected array  $formColumns = ['name_fa','slug','sort_order','is_active'];

    // ستون‌های قابل مرتب‌سازی (در فرزند override می‌کنی)
    protected array $sortable = ['name_fa','slug','sort_order','is_active','value'];
    protected string $defaultSort = 'sort_order';
    protected string $defaultDir  = 'asc';


    // اگر خواستی عنوان سفارشی بدی، تو کنترلر فرزند ست کن
    protected ?string $title = null;

    // اگر FK لازم داری، این متد را override کن
    protected function parentData(): array { return []; }

    protected function model()
    {
        return app($this->modelClass);
    }

    public function index()
    {
        $model = $this->modelClass;

        $sort = request('sort', $this->defaultSort);
        $dir  = request('dir',  $this->defaultDir);

        // امنیت: فقط روی ستون‌های whitelist اجازه بده
        if (!in_array($sort, $this->sortable)) {
            $sort = $this->defaultSort;
        }

        $dir = strtolower($dir) === 'desc' ? 'desc' : 'asc';

        $items = $model::orderBy($sort, $dir)->paginate(20)->withQueryString();

        return view($this->viewPath . '.index', [
            'items'       => $items,
            'listColumns' => $this->listColumns,
            'routeName'   => $this->routeName,
            'title'       => $this->title(),
            'sort'        => $sort,   // ✅ بفرست به ویو
            'dir'         => $dir,
        ]);
    }



    public function create()
    {
        return view($this->viewPath . '.create', array_merge([
            'routeName'   => $this->routeName,
            'formColumns' => $this->formColumns,
            'title'       => $this->title(),
        ], $this->parentData()));
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->validation);

        // UUID
        if (empty($data['id'])) {
            $data['id'] = (string) Str::uuid();
        }

        $model = $this->modelClass;
        $model::create($data);

        return redirect()
            ->route($this->routeName . '.index')
            ->with('success', 'آیتم با موفقیت ایجاد شد.');
    }

    public function edit(string $id)
    {
        $model = $this->modelClass;
        $item  = $model::findOrFail($id);

        return view($this->viewPath . '.edit', array_merge([
            'item'        => $item,
            'routeName'   => $this->routeName,
            'formColumns' => $this->formColumns,
            'title'       => $this->title(),
        ], $this->parentData()));
    }

    public function update(Request $request, string $id)
    {
        $model = $this->modelClass;
        $item  = $model::findOrFail($id);

        $rules = [];

        foreach ($this->validation as $field => $rule) {

            // اگر rule به صورت string بود و unique داشت
            if (is_string($rule) && str_contains($rule, 'unique:')) {

                // example: unique:grades,slug
                // table=grades , column=slug
                preg_match('/unique:([^,]+),([^,|]+)/', $rule, $m);
                $table  = $m[1] ?? null;
                $column = $m[2] ?? $field;

                // بقیه rule ها (غیر از unique)
                $otherRules = collect(explode('|', $rule))
                    ->reject(fn($r) => str_starts_with($r, 'unique:'))
                    ->values()
                    ->all();

                // unique با ignore
                $rules[$field] = array_merge(
                    $otherRules,
                    [ \Illuminate\Validation\Rule::unique($table, $column)->ignore($id) ]
                );

            } else {
                // بقیه rule ها مثل قبل
                $rules[$field] = $rule;
            }
        }

        $data = $request->validate($rules);

        $item->update($data);

        return redirect()
            ->route($this->routeName . '.index')
            ->with('success', 'آیتم بروزرسانی شد.');
    }


    public function destroy(string $id)
    {
        $model = $this->modelClass;
        $item  = $model::findOrFail($id);

        $item->delete();

        return back()->with('success', 'آیتم حذف شد.');
    }

    protected function title(): string
    {
        if ($this->title) return $this->title;

        return class_basename($this->modelClass);
    }
}
