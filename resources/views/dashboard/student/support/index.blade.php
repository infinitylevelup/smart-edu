@extends('layouts.app')
@section('title', 'پشتیبانی')

@section('content')
    <div class="container py-3 py-md-4">

        <div class="card-soft" style="max-width:900px;margin-inline:auto;">
            <div class="d-flex align-items-center gap-2 mb-2">
                <i class="bi bi-headset text-primary fs-4"></i>
                <h5 class="fw-bold m-0">پشتیبانی دانش‌آموز</h5>
            </div>

            <p class="text-muted small" style="line-height:1.9">
                اگر مشکلی در آزمون‌ها، کلاس‌ها یا حساب کاربری داشتی، از اینجا با ما در ارتباط باش.
            </p>

            <div class="row g-2 mt-2">
                <div class="col-12 col-md-6">
                    <div class="p-3 rounded-4 bg-light border">
                        <div class="fw-bold mb-1">📩 ارسال تیکت</div>
                        <div class="text-muted small">به‌زودی فرم تیکت اینجا فعال میشه.</div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="p-3 rounded-4 bg-light border">
                        <div class="fw-bold mb-1">☎️ تماس مستقیم</div>
                        <div class="text-muted small">شماره/واتساپ پشتیبانی را بعداً اینجا می‌گذاری.</div>
                    </div>
                </div>
            </div>

            <div class="alert alert-info mt-3 small">
                فعلاً این صفحه نمونه است؛ هر وقت گفتی، سیستم واقعی تیکت/چت رو هم می‌سازیم.
            </div>
        </div>

    </div>
@endsection
