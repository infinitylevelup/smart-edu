@extends('layouts.app')
@section('title', 'Analyze Attempt')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="h5 mb-1">تحلیل Attempt</h1>
                <div class="text-muted small">{{ $attempt->exam->title ?? '' }}</div>
            </div>
            <a href="{{ route('counselor.attempts.show', $attempt->id) }}" class="btn btn-outline-secondary">
                بازگشت
            </a>
        </div>

        <form method="POST" action="{{ route('counselor.attempts.analyze.store', $attempt->id) }}">
            @csrf

            <div class="row g-3">

                <div class="col-lg-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-0">
                            <h2 class="h6 mb-0">تحلیل تحصیلی (Academic)</h2>
                        </div>
                        <div class="card-body">

                            <label class="form-label small">نقاط قوت تحصیلی</label>
                            <textarea name="academic_strengths" class="form-control" rows="4" placeholder="مثلاً: دقت بالا در تست‌های فصل 1">
{{ old('academic_strengths', optional($attempt->counselorAnalysis)->academic_strengths) }}</textarea>

                            <label class="form-label small mt-3">نقاط ضعف تحصیلی</label>
                            <textarea name="academic_weaknesses" class="form-control" rows="4"
                                placeholder="مثلاً: ضعف در سوالات سخت و زمان‌دار">
{{ old('academic_weaknesses', optional($attempt->counselorAnalysis)->academic_weaknesses) }}</textarea>

                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-0">
                            <h2 class="h6 mb-0">تحلیل پرورشی/روحی (Developmental)</h2>
                        </div>
                        <div class="card-body">

                            <label class="form-label small">یادداشت‌های روحی و پرورشی</label>
                            <textarea name="developmental_notes" class="form-control" rows="5"
                                placeholder="مثلاً: اضطراب در مواجهه با سوالات بلند">
{{ old('developmental_notes', optional($attempt->counselorAnalysis)->developmental_notes) }}</textarea>

                            <label class="form-label small mt-3">پیشنهاد مسیر و معرفی متخصص</label>
                            <textarea name="recommendations" class="form-control" rows="3"
                                placeholder="مثلاً: پیشنهاد معلم X و تمرین آزمون‌های کوتاه">
{{ old('recommendations', optional($attempt->counselorAnalysis)->recommendations) }}</textarea>

                        </div>
                    </div>
                </div>

            </div>

            <div class="d-flex justify-content-end mt-3">
                <button class="btn btn-primary px-4">
                    ذخیره تحلیل
                </button>
            </div>

        </form>

    </div>
@endsection
