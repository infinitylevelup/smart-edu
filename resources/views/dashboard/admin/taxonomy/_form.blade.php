@php
    /**
     * فرم مشترک Taxonomy
     * انتظار:
     * $title, $routeName, $item (در edit), $mode=create|edit
     * $formFields: آرایه‌ی فیلدها
     *
     * هر فیلد:
     * [
     *   'name' => 'slug',
     *   'label'=> 'اسلاگ',
     *   'type' => 'text|number|textarea|select|checkbox',
     *   'options' => [ ['value'=>'x','label'=>'y'], ... ] برای select
     *   'required' => true|false
     *   'placeholder' => '...'
     * ]
     */

    $title = $title ?? 'آیتم';
    $routeName = $routeName ?? 'admin.sections';
    $mode = $mode ?? 'create';
    $item = $item ?? null;

    $formFields = $formFields ?? [
        [
            'name' => 'name_fa',
            'label' => 'نام فارسی',
            'type' => 'text',
            'required' => true,
            'placeholder' => 'مثال: فنی و حرفه‌ای',
        ],
        [
            'name' => 'slug',
            'label' => 'اسلاگ',
            'type' => 'text',
            'required' => true,
            'placeholder' => 'مثال: technical',
        ],
        ['name' => 'sort_order', 'label' => 'ترتیب', 'type' => 'number', 'required' => false, 'placeholder' => '0'],
        ['name' => 'is_active', 'label' => 'فعال است؟', 'type' => 'checkbox'],
    ];

    // آدرس action
    $action = $mode === 'edit' ? route($routeName . '.update', $item->id) : route($routeName . '.store');
@endphp

<form action="{{ $action }}" method="POST">
    @csrf
    @if ($mode === 'edit')
        @method('PUT')
    @endif

    <div class="row g-3">

        @foreach ($formFields as $field)
            @php
                $name = $field['name'];
                $type = $field['type'] ?? 'text';
                $label = $field['label'] ?? $name;
                $required = $field['required'] ?? false;
                $placeholder = $field['placeholder'] ?? '';
                $options = $field['options'] ?? [];
                $value = old($name, $item ? data_get($item, $name) : null);
            @endphp

            {{-- checkbox --}}
            @if ($type === 'checkbox')
                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="{{ $name }}" name="{{ $name }}"
                            value="1"
                            {{ old($name, $item ? (data_get($item, $name) ? 1 : 0) : 1) ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="{{ $name }}">
                            {{ $label }}
                        </label>
                    </div>
                </div>

                {{-- textarea --}}
            @elseif($type === 'textarea')
                <div class="col-12">
                    <label for="{{ $name }}" class="form-label fw-bold">
                        {{ $label }} @if ($required)
                            <span class="text-danger">*</span>
                        @endif
                    </label>
                    <textarea id="{{ $name }}" name="{{ $name }}" rows="4"
                        class="form-control @error($name) is-invalid @enderror" placeholder="{{ $placeholder }}"
                        @if ($required) required @endif>{{ $value }}</textarea>
                    @error($name)
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- select --}}
            @elseif($type === 'select')
                <div class="col-md-6">
                    <label for="{{ $name }}" class="form-label fw-bold">
                        {{ $label }} @if ($required)
                            <span class="text-danger">*</span>
                        @endif
                    </label>
                    <select id="{{ $name }}" name="{{ $name }}"
                        class="form-select @error($name) is-invalid @enderror"
                        @if ($required) required @endif>
                        <option value="">-- انتخاب کنید --</option>
                        @foreach ($options as $opt)
                            <option value="{{ $opt['value'] }}"
                                {{ (string) $value === (string) $opt['value'] ? 'selected' : '' }}>
                                {{ $opt['label'] }}
                            </option>
                        @endforeach
                    </select>
                    @error($name)
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- number/text --}}
            @else
                <div class="col-md-6">
                    <label for="{{ $name }}" class="form-label fw-bold">
                        {{ $label }} @if ($required)
                            <span class="text-danger">*</span>
                        @endif
                    </label>
                    <input type="{{ $type }}" id="{{ $name }}" name="{{ $name }}"
                        value="{{ $value }}" class="form-control @error($name) is-invalid @enderror"
                        placeholder="{{ $placeholder }}" @if ($required) required @endif>
                    @error($name)
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            @endif
        @endforeach
    </div>

    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary">
            {{ $mode === 'edit' ? 'ذخیره تغییرات' : 'ایجاد' }}
        </button>

        <a href="{{ route($routeName . '.index') }}" class="btn btn-secondary">
            بازگشت
        </a>
    </div>
</form>
