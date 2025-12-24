@extends('layouts.app')
@section('title','کلاس‌های حذف‌شده')

@push('styles')
<style>
  .trash-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(360px,1fr));gap:20px}
  .trash-card{background:#fff;border-radius:18px;box-shadow:0 10px 30px rgba(0,0,0,.08);overflow:hidden;border:2px solid rgba(0,0,0,.06)}
  .trash-card .head{padding:16px 18px;border-bottom:1px solid rgba(0,0,0,.06);display:flex;justify-content:space-between;align-items:center}
  .trash-card .body{padding:16px 18px}
  .trash-card .actions{display:flex;gap:12px}
  .trash-card.fade-out{opacity:0;transform:translateY(10px);transition:all .25s ease}
</style>
@endpush

@section('content')
<div class="container-fluid"
     data-page="teacher-classes-trash"
     data-restore-url-template="{{ route('teacher.classes.restore', '__ID__') }}">

  <div class="d-flex align-items-center justify-content-between mb-3">
    <div>
      <h4 class="fw-bold mb-1">کلاس‌های حذف‌شده</h4>
      <div class="text-muted small">اینجا می‌تونی کلاس‌ها را بازیابی کنی.</div>
    </div>

    <a class="btn btn-outline-secondary" href="{{ route('teacher.classes.index') }}">
      برگشت به کلاس‌ها
    </a>
  </div>

  @if($classes->count() === 0)
    <div class="alert alert-info">کلاسی در سطل زباله نیست.</div>
  @else
    <div class="trash-grid">
      @foreach($classes as $class)
        <div class="trash-card" data-id="{{ $class->id }}">
          <div class="head">
            <div class="fw-bold">{{ $class->title ?? 'بدون نام' }}</div>
            <span class="badge bg-secondary">حذف‌شده</span>
          </div>

          <div class="body">
            <div class="text-muted small mb-3">
              زمان حذف: {{ optional($class->deleted_at)->format('Y-m-d H:i') }}
            </div>

            <div class="actions">
              <button type="button"
                      class="btn btn-success flex-fill"
                      data-action="restore"
                      data-id="{{ $class->id }}"
                      data-confirm="کلاس «{{ $class->title ?? 'بدون نام' }}» بازیابی شود؟">
                بازیابی
              </button>

              <a class="btn btn-outline-primary flex-fill"
                 href="{{ route('teacher.classes.show', $class->id) }}">
                 مشاهده
              </a>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <div class="mt-3">
      {{ $classes->links() }}
    </div>
  @endif
</div>
@endsection
