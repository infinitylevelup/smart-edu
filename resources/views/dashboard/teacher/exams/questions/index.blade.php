@extends('layouts.app')
@section('title', 'سوال‌های آزمون')

@push('styles')
<style>
    .q-card {
        border-radius: 1.25rem;
        background: #fff;
        box-shadow: 0 8px 24px rgba(18,38,63,.06);
        border: 0;
    }
    .q-header-pill {
        border-radius: 999px;
        padding: .35rem .8rem;
        font-weight: 700;
        font-size: .85rem;
        background: #f1f5f9;
        color: #334155;
        display: inline-flex;
        align-items: center;
        gap: .4rem;
    }
    .q-header-pill .dot {
        width: 9px; height: 9px; border-radius: 50%;
        background: #0d6efd;
        box-shadow: 0 0 0 4px rgba(13,110,253,.15);
    }
    .badge-type {
        border-radius: 999px;
        font-size: .75rem;
        font-weight: 800;
    }
    .badge-type-mcq    { background:#eff6ff; color:#1d4ed8; }
    .badge-type-tf     { background:#ecfdf3; color:#16a34a; }
    .badge-type-fill   { background:#fefce8; color:#ca8a04; }
    .badge-type-essay  { background:#f5f3ff; color:#6d28d9; }
    .badge-subject {
        background:#f1f5f9;
        color:#334155;
        font-weight:600;
    }
    .btn-q {
        border-radius: .9rem;
        padding: .45rem .9rem;
        font-size: .85rem;
        display:inline-flex;
        align-items:center;
        gap:.35rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <div>
            <div class="q-header-pill mb-2">
                <span class="dot"></span>
                مدیریت سوال‌ها
            </div>
            <h4 class="fw-bold mb-1">
                سوال‌های آزمون: {{ $exam->title ?? 'بدون عنوان' }}
            </h4>
            <div class="text-muted small">
                مسیر رسمی ایجاد و ویرایش سوال‌ها: Wizard
            </div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('teacher.exams.edit', $exam) }}"
               class="btn btn-outline-secondary btn-q">
                <i class="bi bi-arrow-right"></i>
                ویرایش آزمون
            </a>

            <a href="{{ route('teacher.exams.questions.wizard.create', $exam) }}"
               class="btn btn-primary btn-q">
                <i class="bi bi-plus-circle"></i>
                سوال جدید
            </a>
        </div>
    </div>

    <div class="q-card p-3 p-md-4">
        @if($questions->isEmpty())
            <div class="text-center py-4 text-muted">
                هنوز سوالی ثبت نشده است.
            </div>
        @else
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>متن سوال</th>
                            <th>نوع</th>
                            <th>پاسخ / توضیح</th>
                            <th>وضعیت</th>
                            <th class="text-end">عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($questions as $q)
                            @php
                                $opts = $q->options ?? [];
                                $ca   = $q->correct_answer ?? [];

                                // نرمال‌سازی type
                                $type = $q->question_type;
                                if ($type === 'essay') $type = 'descriptive';
                                if ($type === 'fill_blank') $type = 'short_answer';

                                $subjectTitle =
                                    $q->subject?->title_fa
                                    ?? $q->subject?->name_fa
                                    ?? null;
                            @endphp

                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td style="max-width:340px;">
                                    <div class="fw-semibold">
                                        {{ \Illuminate\Support\Str::limit($q->content, 80) }}
                                    </div>

                                    {{-- نمایش درس در آزمون جامع --}}
                                    @if($subjectTitle)
                                        <div class="mt-1">
                                            <span class="badge badge-subject">
                                                📘 {{ $subjectTitle }}
                                            </span>
                                        </div>
                                    @endif
                                </td>

                                {{-- TYPE --}}
                                <td>
                                    @switch($type)
                                        @case('mcq')
                                            <span class="badge badge-type badge-type-mcq">تستی</span>
                                            @break
                                        @case('true_false')
                                            <span class="badge badge-type badge-type-tf">درست / نادرست</span>
                                            @break
                                        @case('short_answer')
                                            <span class="badge badge-type badge-type-fill">جای خالی</span>
                                            @break
                                        @case('descriptive')
                                            <span class="badge badge-type badge-type-essay">تشریحی</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">نامشخص</span>
                                    @endswitch
                                </td>

                                {{-- ANSWER --}}
                                <td>
                                    {{-- MCQ --}}
                                    @if($type === 'mcq')
                                        @php $correct = $ca['correct_option'] ?? null; @endphp
                                        @foreach($opts as $k => $v)
                                            <div class="small">
                                                {{ strtoupper($k) }}.
                                                {{ $v }}
                                                @if($correct === $k)
                                                    <span class="badge bg-success-subtle text-success ms-1">صحیح</span>
                                                @endif
                                            </div>
                                        @endforeach

                                    {{-- TRUE / FALSE --}}
                                    @elseif($type === 'true_false')
                                        @php
                                            $val = $ca['value'] ?? null;
                                            $label = $val === null
                                                ? '—'
                                                : ((string)$val === '1' ? 'درست' : 'نادرست');
                                        @endphp
                                        <span class="badge bg-info-subtle text-info">
                                            جواب: {{ $label }}
                                        </span>

                                    {{-- SHORT ANSWER --}}
                                    @elseif($type === 'short_answer')
                                        @php
                                            $vals = $ca['values'] ?? [];
                                            if (is_string($vals)) $vals = [$vals];
                                        @endphp
                                        <div class="small">
                                            {{ implode(' ، ', array_filter($vals)) ?: '—' }}
                                        </div>

                                    {{-- DESCRIPTIVE --}}
                                    @elseif($type === 'descriptive')
                                        @if($q->explanation)
                                            <div class="small text-muted">
                                                📝 {{ \Illuminate\Support\Str::limit($q->explanation, 70) }}
                                            </div>
                                        @else
                                            <span class="text-muted small">بدون کلید تشریحی</span>
                                        @endif
                                    @endif
                                </td>

                                {{-- STATUS --}}
                                <td>
                                    @if($q->is_active)
                                        <span class="badge bg-success-subtle text-success">فعال</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary">غیرفعال</span>
                                    @endif
                                </td>

                                {{-- ACTIONS --}}
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('teacher.exams.questions.wizard.edit', [$exam, $q]) }}"
                                           class="btn btn-outline-primary btn-q btn-sm">
                                            ویرایش
                                        </a>

                                        <form action="{{ route('teacher.exams.questions.destroy', [$exam, $q]) }}"
                                              method="POST"
                                              onsubmit="return confirm('سوال حذف شود؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-outline-danger btn-q btn-sm">
                                                حذف
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
