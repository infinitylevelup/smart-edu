@extends('layouts.app')
@section('title', 'افزودن سوال به آزمون')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>افزودن سوال به آزمون: {{ $exam->title }}</h5>
            <a href="{{ route('teacher.exams.show', $exam) }}" class="btn btn-outline-secondary">
                بازگشت
            </a>
        </div>
        
        <div class="card-body">
            <form action="{{ route('teacher.exams.questions.store-attached', $exam) }}" method="POST" id="attachForm">
                @csrf
                
                {{-- فیلتر و جستجو --}}
                <div class="row mb-4">
                    <div class="col-md-8">
                        <input type="text" class="form-control" placeholder="جستجو در سوالات..." id="searchInput">
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" id="typeFilter">
                            <option value="">همه انواع</option>
                            <option value="mcq">چندگزینه‌ای</option>
                            <option value="true_false">صحیح/غلط</option>
                            <option value="descriptive">تشریحی</option>
                        </select>
                    </div>
                </div>
                
                {{-- لیست سوالات --}}
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="50">
                                    <input type="checkbox" id="selectAll">
                                </th>
                                <th>متن سوال</th>
                                <th width="100">نوع</th>
                                <th width="80">امتیاز</th>
                                <th width="100">سختی</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($questions as $question)
                            <tr class="question-row" data-type="{{ $question->question_type }}">
                                <td>
                                    <input type="checkbox" name="question_ids[]" 
                                           value="{{ $question->id }}" 
                                           class="question-checkbox">
                                </td>
                                <td>
                                    <div class="question-content">
                                        {{ \Illuminate\Support\Str::limit($question->content, 150) }}
                                    </div>
                                    <small class="text-muted">
                                        موضوع: {{ $question->subject->title_fa ?? '—' }}
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ $question->question_type }}
                                    </span>
                                </td>
                                <td>{{ $question->score }}</td>
                                <td>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-{{ $question->difficulty <= 2 ? 'success' : ($question->difficulty <= 3 ? 'warning' : 'danger') }}" 
                                             style="width: {{ ($question->difficulty / 5) * 100 }}%">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="empty-state">
                                        <i class="bi bi-question-circle display-4 text-muted mb-3"></i>
                                        <p class="h5 mb-2">سوالی برای نمایش وجود ندارد.</p>
                                        <p class="small mb-4">می‌توانید یک سوال جدید ایجاد کنید.</p>
                                        <a href="{{ route('teacher.exams.questions.create', $exam) }}" 
                                           class="btn btn-primary">
                                            ایجاد سوال جدید
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{-- پیجینیشن --}}
                @if($questions->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $questions->links() }}
                </div>
                @endif
                
                {{-- دکمه‌های اقدام --}}
                <div class="mt-4 d-flex justify-content-between">
                    <a href="{{ route('teacher.exams.show', $exam) }}" class="btn btn-outline-secondary">
                        انصراف
                    </a>
                    <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                        <i class="bi bi-plus-circle"></i>
                        افزودن سوالات انتخاب شده
                        (<span id="selectedCount">0</span>)
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.question-checkbox');
    const selectedCount = document.getElementById('selectedCount');
    const submitBtn = document.getElementById('submitBtn');
    const searchInput = document.getElementById('searchInput');
    const typeFilter = document.getElementById('typeFilter');
    const questionRows = document.querySelectorAll('.question-row');
    
    // انتخاب همه
    selectAll.addEventListener('change', function() {
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateSelection();
    });
    
    // به‌روزرسانی تعداد انتخاب‌ها
    function updateSelection() {
        const checked = document.querySelectorAll('.question-checkbox:checked');
        selectedCount.textContent = checked.length;
        submitBtn.disabled = checked.length === 0;
    }
    
    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateSelection);
    });
    
    // جستجو
    searchInput.addEventListener('input', function() {
        const term = this.value.toLowerCase();
        questionRows.forEach(row => {
            const content = row.querySelector('.question-content').textContent.toLowerCase();
            row.style.display = content.includes(term) ? '' : 'none';
        });
    });
    
    // فیلتر نوع
    typeFilter.addEventListener('change', function() {
        const type = this.value;
        questionRows.forEach(row => {
            if (!type || row.dataset.type === type) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});
</script>
@endpush