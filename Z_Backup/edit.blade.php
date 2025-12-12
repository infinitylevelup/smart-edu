@extends('layouts.app')
@section('title', 'ویرایش سوال')

@push('styles')
<style>
    .form-card {
        border-radius: 1.25rem;
        background:#fff;
        box-shadow:0 8px 24px rgba(18,38,63,.06);
        border:0;
    }
    .input-soft {
        border:0;
        box-shadow:0 6px 18px rgba(18,38,63,.06);
        border-radius:.9rem;
        padding:.7rem .9rem;
    }
    .input-soft:focus {
        box-shadow:0 0 0 .25rem rgba(13,110,253,.15);
    }
    .q-block-title { font-weight:700; margin-bottom:.35rem; }
</style>
@endpush

@section('content')
<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <div>
            <h4 class="fw-bold mb-1">
                <i class="bi bi-pencil-square text-warning me-1"></i>
                ویرایش سوال
            </h4>
            <div class="text-muted small">
                سوال مربوط به آزمون <strong>{{ $exam->title ?? 'بدون عنوان' }}</strong>.
            </div>
        </div>
        <a href="{{ route('teacher.exams.questions.index', $exam) }}"
           class="btn btn-outline-secondary d-inline-flex align-items-center gap-2 shadow-sm">
            <i class="bi bi-arrow-right"></i>
            بازگشت به سوال‌ها
        </a>
    </div>

    {{-- Errors --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <div class="fw-semibold mb-1">
                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                لطفاً خطاهای زیر را بررسی کن:
            </div>
            <ul class="mb-0">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $qt       = old('question_type', $question->question_type);
        $opts     = old('options', $question->options ?? []);
        $ca       = old('correct_answer', $question->correct_answer ?? []);
        $tfVal    = $ca['value']  ?? null;
        $fillVals = $ca['values'] ?? [''];
    @endphp

    <div class="form-card p-3 p-md-4">
        <form action="{{ route('teacher.exams.questions.update', [$exam, $question]) }}"
              method="POST"
              class="row g-3">
            @csrf
            @method('PUT')

            {{-- متن سوال --}}
            <div class="col-12">
                <label class="form-label fw-semibold">متن سوال <span class="text-danger">*</span></label>
                <textarea name="content"
                          rows="4"
                          class="form-control input-soft @error('content') is-invalid @enderror">{{ old('content', $question->content) }}</textarea>
                @error('content')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- نوع + امتیاز + فعال --}}
            <div class="col-md-6">
                <label class="form-label fw-semibold">نوع سوال <span class="text-danger">*</span></label>
                <select name="question_type"
                        id="question_type"
                        class="form-select input-soft @error('question_type') is-invalid @enderror">
                    <option value="mcq"        {{ $qt === 'mcq' ? 'selected' : '' }}>تستی</option>
                    <option value="true_false" {{ $qt === 'true_false' ? 'selected' : '' }}>درست / نادرست</option>
                    <option value="fill_blank" {{ $qt === 'fill_blank' ? 'selected' : '' }}>جای خالی</option>
                    <option value="essay"      {{ $qt === 'essay' ? 'selected' : '' }}>تشریحی</option>
                </select>
                @error('question_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold">امتیاز سوال</label>
                <input type="number"
                       name="score"
                       class="form-control input-soft @error('score') is-invalid @enderror"
                       value="{{ old('score', $question->score) }}"
                       min="0" step="0.25">
                @error('score')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-3 d-flex align-items-center">
                <div class="form-check mt-4 pt-1">
                    <input class="form-check-input"
                           type="checkbox"
                           name="is_active"
                           id="q_active"
                           value="1"
                        {{ old('is_active', $question->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold" for="q_active">
                        سوال فعال باشد
                    </label>
                </div>
            </div>

            {{-- تستی --}}
            <div class="col-12" id="mcq_block" style="display:none;">
                <div class="q-block-title">گزینه‌ها (تستی)</div>

                @foreach(['a','b','c','d'] as $opt)
                    <div class="input-group mb-2">
                        <span class="input-group-text">{{ strtoupper($opt) }}</span>
                        <input type="text"
                               name="options[{{ $opt }}]"
                               class="form-control input-soft"
                               value="{{ $opts[$opt] ?? '' }}"
                               placeholder="متن گزینه {{ strtoupper($opt) }}">
                        <span class="input-group-text bg-white">
                            <input class="form-check-input mt-0"
                                   type="radio"
                                   name="correct_answer[correct_option]"
                                   value="{{ $opt }}"
                                   {{ ($ca['correct_option'] ?? null) === $opt ? 'checked' : '' }}>
                            <span class="ms-1 small">صحیح</span>
                        </span>
                    </div>
                @endforeach
            </div>

            {{-- درست / نادرست --}}
            <div class="col-12" id="tf_block" style="display:none;">
                <div class="q-block-title">جواب صحیح (درست / نادرست)</div>
                <div class="d-flex align-items-center gap-3">
                    <div class="form-check">
                        <input class="form-check-input"
                               type="radio"
                               name="correct_answer[value]"
                               id="tf_true"
                               value="1"
                            {{ (string)$tfVal === '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="tf_true">درست</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input"
                               type="radio"
                               name="correct_answer[value]"
                               id="tf_false"
                               value="0"
                            {{ (string)$tfVal === '0' ? 'checked' : '' }}>
                        <label class="form-check-label" for="tf_false">نادرست</label>
                    </div>
                </div>
            </div>

            {{-- جای خالی --}}
            <div class="col-12" id="fill_block" style="display:none;">
                <div class="q-block-title">جواب‌های صحیح (جای خالی)</div>

                <div id="fill_container">
                    @foreach($fillVals as $idx => $val)
                        <div class="input-group mb-2">
                            <span class="input-group-text">جواب {{ $idx + 1 }}</span>
                            <input type="text"
                                   name="correct_answer[values][]"
                                   class="form-control input-soft"
                                   value="{{ $val }}">
                        </div>
                    @endforeach
                </div>

                <button type="button"
                        class="btn btn-outline-secondary btn-sm mt-1"
                        id="add_fill_answer">
                    <i class="bi bi-plus-circle"></i>
                    افزودن جواب دیگر
                </button>
            </div>

            {{-- توضیح --}}
            <div class="col-12">
                <label class="form-label fw-semibold">توضیح / راهنما (اختیاری)</label>
                <textarea name="explanation"
                          rows="3"
                          class="form-control input-soft">{{ old('explanation', $question->explanation) }}</textarea>
            </div>

            {{-- دکمه‌ها --}}
            <div class="col-12 d-flex flex-wrap gap-2 mt-2">
                <button type="submit" class="btn btn-primary btn-wizard shadow-sm">
                    <i class="bi bi-save2"></i>
                    ذخیره تغییرات
                </button>
                <a href="{{ route('teacher.exams.questions.index', $exam) }}"
                   class="btn btn-outline-secondary btn-wizard shadow-sm">
                    <i class="bi bi-arrow-right"></i>
                    بازگشت
                </a>
            </div>

        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleBlocks() {
    const type = document.getElementById('question_type')?.value;

    const mcq  = document.getElementById('mcq_block');
    const tf   = document.getElementById('tf_block');
    const fill = document.getElementById('fill_block');

    // --- غیرفعال کردن ورودی‌های تستی (MCQ) ---
    document.querySelectorAll('#mcq_block input').forEach(el => {
        el.disabled = (type !== 'mcq');
    });

    // --- غیرفعال کردن ورودی‌های درست/نادرست ---
    document.querySelectorAll('#tf_block input').forEach(el => {
        el.disabled = (type !== 'true_false');
    });

    // --- غیرفعال کردن ورودی‌های جای خالی ---
    document.querySelectorAll('#fill_block input').forEach(el => {
        el.disabled = (type !== 'fill_blank');
    });

    // --- نمایش / عدم نمایش بلوک‌ها ---
    mcq.style.display  = (type === 'mcq') ? 'block' : 'none';
    tf.style.display   = (type === 'true_false') ? 'block' : 'none';
    fill.style.display = (type === 'fill_blank') ? 'block' : 'none';
}

document.addEventListener('DOMContentLoaded', function () {
    const select = document.getElementById('question_type');
    if (select) {
        select.addEventListener('change', toggleBlocks);
        toggleBlocks(); // اجرای اولیه
    }

    // افزودن خط جدید برای پاسخ‌های جای خالی
    const addBtn = document.getElementById('add_fill_answer');
    if (addBtn) {
        addBtn.addEventListener('click', function () {
            const container = document.getElementById('fill_container');
            const count = container.children.length + 1;
            const div = document.createElement('div');
            div.className = 'input-group mb-2';
            div.innerHTML = `
                <span class="input-group-text">جواب ${count}</span>
                <input type="text" name="correct_answer[values][]" class="form-control input-soft">
            `;
            container.appendChild(div);

            toggleBlocks(); // دوباره disable/enable کن تا درست شود
        });
    }
});
</script>

@endpush
