@extends('layouts.app')
@section('title', 'ساخت آزمون')

@section('content')
    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold">ساخت آزمون جدید</h4>
            <a href="{{ route('teacher.exams.index') }}" class="btn btn-outline-secondary btn-sm">
                بازگشت
            </a>
        </div>

        @if ($errors->any())
            <div class="alert alert-warning small">
                لطفاً خطاهای زیر را بررسی کن:
                <ul class="mb-0 mt-1">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('teacher.exams.store') }}">
            @csrf

            {{-- عنوان آزمون --}}
            <div class="mb-3">
                <label class="form-label">عنوان آزمون</label>
                <input type="text" name="title" class="form-control" value="{{ old('title') }}"
                    placeholder="مثلاً: آزمون فصل ۱">
            </div>

            {{-- ✅ NEW: انتخاب نوع آزمون (scope) --}}
            <div class="mb-3">
                <label class="form-label d-block">نوع آزمون</label>

                @php $oldScope = old('scope', 'classroom'); @endphp

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="scope" id="scopeClassroom" value="classroom"
                        {{ $oldScope === 'classroom' ? 'checked' : '' }}>
                    <label class="form-check-label" for="scopeClassroom">آزمون کلاسی</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="scope" id="scopeFree" value="free"
                        {{ $oldScope === 'free' ? 'checked' : '' }}>
                    <label class="form-check-label" for="scopeFree">آزمون آزاد</label>
                </div>

                <div class="form-text">
                    آزمون آزاد بدون کلاس ساخته می‌شود و همه‌ی دانش‌آموزان آن را می‌بینند.
                </div>
            </div>

            {{-- انتخاب کلاس --}}
            <div class="mb-3" id="classroomBox">
                <label class="form-label">کلاس مربوطه</label>

                <select name="classroom_id" class="form-select" @disabled($selectedClassroomId)>
                    <option value="">انتخاب کنید...</option>
                    @foreach ($classrooms as $c)
                        <option value="{{ $c->id }}" @selected(old('classroom_id', $selectedClassroomId) == $c->id)>
                            {{ $c->title ?? $c->name }}
                        </option>
                    @endforeach
                </select>

                {{-- اگر کلاس از URL آمده و select قفل شده، مقدار را hidden ارسال می‌کنیم --}}
                @if ($selectedClassroomId)
                    <input type="hidden" name="classroom_id" value="{{ $selectedClassroomId }}">
                    <div class="form-text">
                        این آزمون برای همین کلاس ساخته می‌شود.
                    </div>
                @endif
            </div>

            {{-- مدت --}}
            <div class="mb-3">
                <label class="form-label">مدت آزمون (دقیقه)</label>
                <input type="number" name="duration" class="form-control" value="{{ old('duration') }}" min="1"
                    placeholder="مثلاً 30">
            </div>

            {{-- توضیحات --}}
            <div class="mb-3">
                <label class="form-label">توضیحات (اختیاری)</label>
                <textarea name="description" class="form-control" rows="3" placeholder="توضیح کوتاه درباره آزمون...">{{ old('description') }}</textarea>
            </div>

            {{-- وضعیت --}}
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="is_active" id="activeCheck" value="1" checked>
                <label class="form-check-label" for="activeCheck">
                    آزمون فعال باشد
                </label>
            </div>

            <button class="btn btn-primary">
                <i class="bi bi-check2-circle"></i> ساخت آزمون
            </button>
        </form>

    </div>

    {{-- ✅ NEW: اسکریپت ساده برای مخفی/غیرفعال کردن کلاس در حالت free --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const scopeFree = document.getElementById('scopeFree');
                const scopeClassroom = document.getElementById('scopeClassroom');
                const classroomBox = document.getElementById('classroomBox');
                const classroomSelect = classroomBox.querySelector('select[name="classroom_id"]');

                function toggleClassroom() {
                    const isFree = scopeFree.checked;
                    classroomBox.style.display = isFree ? 'none' : 'block';
                    if (classroomSelect && !classroomSelect.disabled) {
                        classroomSelect.required = !isFree;
                    }
                }

                scopeFree.addEventListener('change', toggleClassroom);
                scopeClassroom.addEventListener('change', toggleClassroom);

                toggleClassroom(); // initial
            });
        </script>
    @endpush
@endsection
