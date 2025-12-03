@extends('layouts.app')
@section('title', 'Attempt Result')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="h5 mb-1">نتیجه آزمون</h1>
                <div class="text-muted small">{{ $attempt->exam->title ?? '' }}</div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('counselor.attempts.analyze.edit', $attempt->id) }}" class="btn btn-primary">
                    تحلیل این Attempt
                </a>
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                    بازگشت
                </a>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="text-muted small">درصد</div>
                        <div class="fs-3 fw-bold">{{ $attempt->percent ?? 0 }}%</div>
                        <hr>
                        <div class="text-muted small">نمره</div>
                        <div class="fw-semibold">
                            {{ $attempt->score_obtained ?? ($attempt->score ?? 0) }}
                            /
                            {{ $attempt->score_total ?? 0 }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0">
                        <h2 class="h6 mb-0">پاسخ‌ها</h2>
                    </div>
                    <div class="card-body text-muted small">
                        فعلاً نمایش کامل پاسخ‌ها مثل Student Result در فاز بعدی اضافه می‌شود.
                    </div>
                </div>
            </div>
        </div>

        {{-- Existing counselor analysis preview --}}
        <div class="card shadow-sm border-0 mt-3">
            <div class="card-header bg-white border-0">
                <h2 class="h6 mb-0">تحلیل ثبت‌شده مشاور</h2>
            </div>
            <div class="card-body">
                @if ($attempt->counselorAnalysis)
                    <div class="mb-2">
                        <strong>نقاط قوت تحصیلی:</strong>
                        <div class="text-muted">{{ $attempt->counselorAnalysis->academic_strengths }}</div>
                    </div>
                    <div class="mb-2">
                        <strong>نقاط ضعف تحصیلی:</strong>
                        <div class="text-muted">{{ $attempt->counselorAnalysis->academic_weaknesses }}</div>
                    </div>
                    <div class="mb-2">
                        <strong>یادداشت‌های پرورشی/روحی:</strong>
                        <div class="text-muted">{{ $attempt->counselorAnalysis->developmental_notes }}</div>
                    </div>
                    <div>
                        <strong>پیشنهادها:</strong>
                        <div class="text-muted">{{ $attempt->counselorAnalysis->recommendations }}</div>
                    </div>
                @else
                    <div class="text-muted small">
                        هنوز تحلیلی ثبت نشده است.
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection
