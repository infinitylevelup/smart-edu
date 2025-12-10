<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;   // ✅ برای unique ignore

abstract class BaseTaxonomyController extends Controller
{
    // ✅ با کنترلرهای جدیدت هماهنگ شد
    protected string $modelClass;   // Model::class
    protected string $viewPath;     // e.g. "dashboard.admin.taxonomies.sections"
    protected string $routeName;    // e.g. "admin.sections"
    protected array  $validation;   // rules

    // ستون‌های قابل نمایش/فرم (در فرزند override می‌کنی اگر لازم شد)
    protected array  $listColumns = ['name_fa','slug','sort_order','is_active'];
    protected array  $formColumns = ['name_fa','slug','sort_order','is_active'];

    // ستون‌های قابل مرتب‌سازی
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

        if (!in_array($sort, $this->sortable)) {
            $sort = $this->defaultSort;
        }

        $dir = strtolower($dir) === 'desc' ? 'desc' : 'asc';

        $items = $model::orderBy($sort, $dir)
            ->paginate(20)
            ->withQueryString();

        return view($this->viewPath . '.index', [
            'items'       => $items,
            'listColumns' => $this->listColumns,
            'routeName'   => $this->routeName,
            'title'       => $this->title(),
            'sort'        => $sort,
            'dir'         => $dir,
        ]);
    }

public function create()
{
    $model = $this->modelClass;

    // همان منطق سورت index
    $sort = request('sort', $this->defaultSort);
    $dir  = request('dir',  $this->defaultDir);

    if (!in_array($sort, $this->sortable)) {
        $sort = $this->defaultSort;
    }

    $dir = strtolower($dir) === 'desc' ? 'desc' : 'asc';

    $items = $model::orderBy($sort, $dir)
        ->paginate(20)
        ->withQueryString();

    return view($this->viewPath . '.create', array_merge([
        'items'       => $items,              // ✅ اضافه شد
        'listColumns' => $this->listColumns,  // ✅
        'routeName'   => $this->routeName,
        'formColumns' => $this->formColumns,
        'title'       => $this->title(),
        'sort'        => $sort,               // ✅ اگر ویو لینک سورت دارد
        'dir'         => $dir,
    ], $this->parentData()));
}

    public function store(Request $request)
    {


        $data = $request->validate($this->validation);

        /**
         * ✅ دیتابیس جدید:
         * id اتواینکریمنت است ⇒ نباید دستی ست شود
         * uuid جداست ⇒ اگر نبود بساز
         */
        if (!isset($data['uuid'])) {
            $data['uuid'] = (string) Str::uuid();
        }

        $model = $this->modelClass;
        $model::create($data);

        return redirect()
            ->route($this->routeName . '.index')
            ->with('success', 'آیتم با موفقیت ایجاد شد.');
    }

    public function edit(int $id)
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

    public function update(Request $request, int $id)
    {
        $model = $this->modelClass;
        $item  = $model::findOrFail($id);

        $rules = [];

        foreach ($this->validation as $field => $rule) {

            if (is_string($rule) && str_contains($rule, 'unique:')) {

                preg_match('/unique:([^,]+),([^,|]+)/', $rule, $m);
                $table  = $m[1] ?? null;
                $column = $m[2] ?? $field;

                $otherRules = collect(explode('|', $rule))
                    ->reject(fn($r) => str_starts_with($r, 'unique:'))
                    ->values()
                    ->all();

                $rules[$field] = array_merge(
                    $otherRules,
                    [ Rule::unique($table, $column)->ignore($id) ]
                );

            } else {
                $rules[$field] = $rule;
            }
        }

        $data = $request->validate($rules);

        $item->update($data);

        return redirect()
            ->route($this->routeName . '.index')
            ->with('success', 'آیتم بروزرسانی شد.');
    }

    public function destroy(int $id)
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
