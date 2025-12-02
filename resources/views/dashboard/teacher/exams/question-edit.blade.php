@extends('layouts.app')
@section('title', 'ویرایش سؤال')

@push('styles')
    <style>
        .form-card {
            border: 0;
            border-radius: 1.25rem;
            background: #fff;
            box-shadow: 0 8px 24px rgba(18, 38, 63, .06)
        }

        .input-soft {
            border: 0;
            box-shadow: 0 6px 18px rgba(18, 38, 63, .06);
            border-radius: .9rem;
            padding: .7rem .9rem
        }

        .input-soft:focus {
            box-shadow: 0 0 0 .25rem rgba(13, 110, 253, .15)
        }

        .option-card {
            border: 1px dashed #e2e8f0;
            border-radius: 1rem;
            background: #fff
        }

        .option-row {
            background: #f8fafc;
            border-radius: .8rem;
            padding: .55rem .7rem;
            display: flex;
            align-items: center;
            gap: .6rem
        }

        .option-tag {
            width: 30px;
            height: 30px;
            border-radius: .6rem;
            display: grid;
            place-items: center;
            background: #fff;
            border: 1px solid #e5e7eb;
            font-weight: 800
        }

        .correct-glow {
            background: rgba(16, 185, 129, .08);
            border-color: rgba(16, 185, 129, .6)
        }

        .hint {
            font-size: .85rem;
            color: #6c757d
        }

        .preview-card {
            border: 1px dashed #e2e8f0;
            border-radius: 1.25rem;
            background: linear-gradient(180deg, #fff, #f8fafc)
        }

        .btn-wizard {
            border-radius: 1rem;
            padding: .7rem 1rem;
            display: inline-flex;
            align-items: center;
            gap: .5rem
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">

        {{-- Header --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
            <div>
                <h4 class="fw-bold mb-1">
                    <i class="bi bi-pencil-square me-1 text-primary"></i> ویرایش سؤال
                </h4>
                <div class="text-muted small">
                    آزمون: <span class="fw-semibold">{{ $exam->title }}</span>
                    <span class="mx-1">•</span>

                    {{-- subject فقط اگر لود شده بود --}}
                    @if ($exam->relationLoaded('subject') && $exam->subject)
                        {{ $exam->subject->title ?? ($exam->subject->name ?? '—') }}
                    @else
                        —
                    @endif
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('teacher.exams.questions.index', $exam) }}"
                    class="btn btn-outline-secondary d-inline-flex align-items-center gap-2 shadow-sm">
                    <i class="bi bi-arrow-right"></i> بازگشت
                </a>
            </div>
        </div>

        {{-- Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <div class="fw-semibold mb-2">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                    لطفاً خطاهای زیر را بررسی کن:
                </div>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-3">
            <div class="col-lg-8">
                <div class="form-card p-3 p-md-4">

                    <form action="{{ route('teacher.exams.questions.update', [$exam, $question]) }}" method="POST"
                        class="row g-3" novalidate>
                        @csrf
                        @method('PUT')

                        {{-- Question text --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">متن سؤال <span class="text-danger">*</span></label>
                            <textarea name="question" rows="4" class="form-control input-soft" required placeholder="سؤال را اینجا بنویس...">{{ old('question', $question->question) }}</textarea>
                        </div>

                        {{-- Score + Explanation --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">امتیاز</label>
                            <input type="number" name="score" min="1" class="form-control input-soft"
                                value="{{ old('score', $question->score ?? 1) }}">
                        </div>

                        <div class="col-md-8">
                            <label class="form-label fw-semibold">توضیح/راهنما (اختیاری)</label>
                            <input type="text" name="explanation" class="form-control input-soft"
                                value="{{ old('explanation', $question->explanation) }}"
                                placeholder="مثلاً نکته یا توضیح کوتاه...">
                        </div>

                        {{-- Type --}}
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">نوع سؤال <span class="text-danger">*</span></label>
                            <select name="type" class="form-select input-soft" id="typeSelect" required>
                                @php $type = old('type', $question->type ?? 'mcq'); @endphp
                                <option value="mcq" {{ $type === 'mcq' ? 'selected' : '' }}>چندگزینه‌ای</option>
                                <option value="true_false" {{ $type === 'true_false' ? 'selected' : '' }}>صحیح/غلط</option>
                                <option value="fill_blank" {{ $type === 'fill_blank' ? 'selected' : '' }}>جای خالی</option>
                                <option value="essay" {{ $type === 'essay' ? 'selected' : '' }}>تشریحی</option>
                            </select>
                            <div class="hint mt-1">با تغییر نوع، ورودی‌ها تغییر می‌کنند.</div>
                        </div>

                        {{-- Options (MCQ + TrueFalse) --}}
                        <div class="col-12" id="optionsBlock" style="display:none">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="fw-bold"><i class="bi bi-list-check me-1"></i> گزینه‌ها</div>
                                <button type="button" id="addOptionBtn" class="btn btn-outline-primary btn-sm btn-wizard">
                                    <i class="bi bi-plus"></i> افزودن گزینه
                                </button>
                            </div>

                            @php
                                $legacyOptions = [
                                    old('options.0', $question->option_a),
                                    old('options.1', $question->option_b),
                                    old('options.2', $question->option_c),
                                    old('options.3', $question->option_d),
                                ];
                                $legacyCorrectMap = ['a' => 0, 'b' => 1, 'c' => 2, 'd' => 3];
                                $legacyCorrect = old(
                                    'correct_answer',
                                    $legacyCorrectMap[$question->correct_option] ?? null,
                                );
                            @endphp

                            <div id="optionsWrap" class="d-grid gap-2">
                                @foreach ($legacyOptions as $i => $opt)
                                    <div class="option-card p-2" data-idx="{{ $i }}">
                                        <div class="option-row">
                                            <div class="option-tag">{{ chr(65 + $i) }}</div>
                                            <input type="text" name="options[{{ $i }}]"
                                                class="form-control border-0 bg-transparent" value="{{ $opt ?? '' }}"
                                                placeholder="متن گزینه">
                                            <div class="form-check ms-auto">
                                                <input class="form-check-input correct-radio" type="radio"
                                                    name="correct_answer" value="{{ $i }}"
                                                    {{ (string) $legacyCorrect === (string) $i ? 'checked' : '' }}>
                                                <label class="form-check-label small">صحیح</label>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-danger remove-option">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Legacy hidden fields for controller --}}
                            <input type="hidden" name="option_a" id="legacyA">
                            <input type="hidden" name="option_b" id="legacyB">
                            <input type="hidden" name="option_c" id="legacyC">
                            <input type="hidden" name="option_d" id="legacyD">
                            <input type="hidden" name="correct_option" id="legacyCorrect">

                            <div class="hint mt-2">
                                برای چندگزینه‌ای حداقل ۴ گزینه لازم است. گزینه صحیح را مشخص کن.
                            </div>

                            {{-- True/False --}}
                            <div id="trueFalseBox" class="mt-3" style="display:none;">
                                <label class="form-label fw-semibold">گزینه صحیح: <span
                                        class="text-danger">*</span></label>
                                <div class="d-flex gap-3">
                                    <label class="form-check">
                                        <input class="form-check-input" type="radio" name="correct_tf" value="1"
                                            {{ old('correct_tf', (string) ($question->correct_tf ?? '')) === '1' ? 'checked' : '' }}>
                                        <span class="form-check-label">صحیح</span>
                                    </label>
                                    <label class="form-check">
                                        <input class="form-check-input" type="radio" name="correct_tf" value="0"
                                            {{ old('correct_tf', (string) ($question->correct_tf ?? '')) === '0' ? 'checked' : '' }}>
                                        <span class="form-check-label">غلط</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Fill blank --}}
                        <div class="col-12" id="blankBlock" style="display:none">
                            <label class="form-label fw-semibold">پاسخ(های) جای خالی <span
                                    class="text-danger">*</span></label>
                            <textarea name="correct_blanks" rows="3" class="form-control input-soft"
                                placeholder="پاسخ‌ها را با کاما یا خط جدید جدا کن...">{{ old('correct_blanks', is_array($question->correct_answer) ? implode(', ', $question->correct_answer) : $question->correct_answer ?? '') }}</textarea>
                        </div>

                        {{-- Essay --}}
                        <div class="col-12" id="descBlock" style="display:none">
                            <label class="form-label fw-semibold">راهنمای پاسخ تشریحی (اختیاری)</label>
                            <textarea name="descriptive_answer" rows="4" class="form-control input-soft"
                                placeholder="راهنمای پاسخ یا نکات مهم...">{{ old('descriptive_answer', $question->explanation) }}</textarea>
                        </div>

                        {{-- Submit --}}
                        <div class="col-12 d-flex gap-2 mt-2">
                            <button type="submit" class="btn btn-success btn-wizard shadow-sm">
                                <i class="bi bi-check2-circle"></i> ذخیره تغییرات
                            </button>
                        </div>

                    </form>
                </div>
            </div>

            {{-- Right side preview --}}
            <div class="col-lg-4">
                <div class="preview-card p-3">
                    <div class="fw-bold mb-2"><i class="bi bi-eye me-1"></i> پیش‌نمایش</div>
                    <div class="border rounded-3 p-3 bg-white">
                        <div class="fw-semibold" id="pvQuestion">{{ $question->question }}</div>
                        <div class="text-muted small mt-2" id="pvType">نوع: {{ $question->type }}</div>
                        <div class="text-muted small mt-1" id="pvScore">امتیاز: {{ $question->score ?? 1 }}</div>
                        <div class="mt-2" id="pvOptions"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const typeSel = document.getElementById('typeSelect');
            const optionsBlock = document.getElementById('optionsBlock');
            const descBlock = document.getElementById('descBlock');
            const blankBlock = document.getElementById('blankBlock');
            const optionsWrap = document.getElementById('optionsWrap');
            const addOptionBtn = document.getElementById('addOptionBtn');
            const trueFalseBox = document.getElementById('trueFalseBox');

            const legacyA = document.getElementById('legacyA');
            const legacyB = document.getElementById('legacyB');
            const legacyC = document.getElementById('legacyC');
            const legacyD = document.getElementById('legacyD');
            const legacyCorrect = document.getElementById('legacyCorrect');

            const pvQuestion = document.getElementById('pvQuestion');
            const pvType = document.getElementById('pvType');
            const pvScore = document.getElementById('pvScore');
            const pvOptions = document.getElementById('pvOptions');

            const qText = document.querySelector('textarea[name="question"]');
            const score = document.querySelector('input[name="score"]');

            const explanationInput = document.querySelector('input[name="explanation"]');
            const descriptiveInput = document.querySelector('textarea[name="descriptive_answer"]');

            function addOption(value = '') {
                const idx = optionsWrap.querySelectorAll('.option-card').length;
                const div = document.createElement('div');
                div.className = 'option-card p-2';
                div.innerHTML = `
        <div class="option-row">
            <div class="option-tag">${String.fromCharCode(65+idx)}</div>
            <input type="text" name="options[${idx}]" class="form-control border-0 bg-transparent" value="${value}" placeholder="متن گزینه">
            <div class="form-check ms-auto">
                <input class="form-check-input correct-radio" type="radio" name="correct_answer" value="${idx}">
                <label class="form-check-label small">صحیح</label>
            </div>
            <button type="button" class="btn btn-sm btn-outline-danger remove-option"><i class="bi bi-x"></i></button>
        </div>`;
                optionsWrap.appendChild(div);
            }

            function reindexOptions() {
                const cards = [...optionsWrap.querySelectorAll('.option-card')];
                cards.forEach((card, idx) => {
                    card.dataset.idx = idx;
                    const tag = card.querySelector('.option-tag');
                    if (tag) tag.textContent = String.fromCharCode(65 + idx);
                    const input = card.querySelector('input[name^="options"]');
                    if (input) input.name = `options[${idx}]`;
                    const radio = card.querySelector('.correct-radio');
                    if (radio) radio.value = idx;
                });
                refreshPreviewOptions();
                syncLegacyFields();
            }

            function ensureMinMcqOptions() {
                if (typeSel.value !== 'mcq') return;
                let count = optionsWrap.querySelectorAll('.option-card').length;
                while (count < 4) {
                    addOption('');
                    count++;
                }
                reindexOptions();
            }

            function refreshBlocks() {
                const t = typeSel.value;

                optionsBlock.style.display = (t === 'mcq' || t === 'true_false') ? 'block' : 'none';
                descBlock.style.display = (t === 'essay') ? 'block' : 'none';
                blankBlock.style.display = (t === 'fill_blank') ? 'block' : 'none';

                trueFalseBox.style.display = (t === 'true_false') ? 'block' : 'none';
                optionsWrap.style.display = (t === 'mcq') ? '' : 'none';
                addOptionBtn.style.display = (t === 'mcq') ? '' : 'none';

                pvType.textContent = 'نوع: ' + t;

                ensureMinMcqOptions();
                refreshPreviewOptions();
                syncLegacyFields();
            }

            function refreshPreviewOptions() {
                if (!pvOptions) return;
                const t = typeSel.value;
                pvOptions.innerHTML = '';
                if (t === 'mcq') {
                    const opts = [...optionsWrap.querySelectorAll('input[name^="options"]')]
                        .map(i => i.value).filter(Boolean);
                    pvOptions.innerHTML =
                        '<div class="text-muted small mt-2">گزینه‌ها:</div>' +
                        opts.map((o, idx) => `<div class="small mt-1">${String.fromCharCode(65+idx)}. ${o}</div>`)
                        .join('');
                }
            }

            function syncLegacyFields() {
                const opts = [...optionsWrap.querySelectorAll('input[name^="options"]')].map(i => i.value || '');

                legacyA.value = opts[0] ?? '';
                legacyB.value = opts[1] ?? '';
                legacyC.value = opts[2] ?? '';
                legacyD.value = opts[3] ?? '';

                const corr = document.querySelector('input[name="correct_answer"]:checked')?.value;
                legacyCorrect.value = (corr !== undefined) ?
                    (['a', 'b', 'c', 'd'][Number(corr)] ?? null) :
                    null;
            }

            addOptionBtn.addEventListener('click', () => {
                addOption('');
                reindexOptions();
            });

            optionsWrap.addEventListener('click', (e) => {
                const btn = e.target.closest('.remove-option');
                if (btn) {
                    const cards = optionsWrap.querySelectorAll('.option-card');
                    if (cards.length <= 4) {
                        alert('برای چندگزینه‌ای حداقل ۴ گزینه لازم است.');
                        return;
                    }
                    btn.closest('.option-card')?.remove();
                    reindexOptions();
                }
            });

            optionsWrap.addEventListener('input', (e) => {
                if (e.target.matches('input[name^="options"]')) {
                    refreshPreviewOptions();
                    syncLegacyFields();
                }
            });

            optionsWrap.addEventListener('change', (e) => {
                if (e.target.matches('.correct-radio')) {
                    optionsWrap.querySelectorAll('.option-row')
                        .forEach(r => r.classList.remove('correct-glow'));
                    e.target.closest('.option-row')?.classList.add('correct-glow');
                    syncLegacyFields();
                }
            });

            qText.addEventListener('input', () => pvQuestion.textContent = qText.value || 'متن سؤال');
            score.addEventListener('input', () => pvScore.textContent = 'امتیاز: ' + (score.value || 1));
            typeSel.addEventListener('change', refreshBlocks);

            document.querySelector('form').addEventListener('submit', () => {
                if (typeSel.value === 'essay') {
                    const guide = descriptiveInput?.value?.trim();
                    if (guide && (!explanationInput.value || explanationInput.value.trim().length === 0)) {
                        explanationInput.value = guide;
                    }
                }
                ensureMinMcqOptions();
                syncLegacyFields();
            });

            refreshBlocks();
            ensureMinMcqOptions();
            syncLegacyFields();
        });
    </script>
@endpush
