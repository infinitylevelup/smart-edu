@extends('layouts.app')
@section('title', 'افزودن سؤال جدید')

@push('styles')
    <style>
        .form-card {
            border: 0;
            border-radius: 1.25rem;
            background: #fff;
            box-shadow: 0 8px 24px rgba(18, 38, 63, .06)
        }

        .wizard {
            display: flex;
            gap: .5rem;
            flex-wrap: wrap
        }

        .wiz-step {
            flex: 1 1 120px;
            background: #f8fafc;
            border-radius: 1rem;
            padding: .7rem .9rem;
            display: flex;
            align-items: center;
            gap: .6rem;
            font-weight: 800;
            font-size: .9rem;
            color: #334155
        }

        .wiz-step.active {
            background: rgba(13, 110, 253, .1);
            color: #0d6efd
        }

        .wiz-step .num {
            width: 28px;
            height: 28px;
            border-radius: .7rem;
            display: grid;
            place-items: center;
            background: #fff;
            border: 1px solid #e2e8f0
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
            background: #fff;
            transition: .2s
        }

        .option-card:hover {
            border-color: #cbd5e1;
            box-shadow: 0 8px 20px rgba(18, 38, 63, .06)
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

        .floating-help {
            position: sticky;
            top: 90px
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
                    <i class="bi bi-plus-circle me-1 text-primary"></i> افزودن سؤال جدید
                </h4>
                <div class="text-muted small">
                    آزمون: <span class="fw-semibold">{{ $exam->title }}</span>

                    {{-- subject فقط اگر قبلاً لود شده بود نمایش بده --}}
                    @if ($exam->relationLoaded('subject') && $exam->subject)
                        • موضوع: <span class="fw-semibold">{{ $exam->subject->title ?? $exam->subject->name }}</span>
                    @endif

                    • کلاس: <span
                        class="fw-semibold">{{ $exam->classroom?->title ?? ($exam->classroom?->name ?? '—') }}</span>
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

            {{-- Form --}}
            <div class="col-lg-8">
                <div class="form-card p-3 p-md-4">

                    {{-- Wizard Steps --}}
                    <div class="wizard mb-3" id="wizard">
                        <div class="wiz-step active" data-step="1">
                            <div class="num">1</div> متن و امتیاز
                        </div>
                        <div class="wiz-step" data-step="2">
                            <div class="num">2</div> نوع و پاسخ
                        </div>
                        <div class="wiz-step" data-step="3">
                            <div class="num">3</div> مرور نهایی
                        </div>
                    </div>

                    <form action="{{ route('teacher.exams.questions.store', $exam) }}" method="POST" class="row g-3"
                        novalidate>
                        @csrf

                        {{-- Step 1 --}}
                        <div class="col-12 step" data-step="1">
                            <label class="form-label fw-semibold">
                                متن سؤال <span class="text-danger">*</span>
                            </label>
                            <textarea name="question" rows="4" class="form-control input-soft" required placeholder="سؤال را اینجا بنویس...">{{ old('question') }}</textarea>
                        </div>

                        <div class="col-md-4 step" data-step="1">
                            <label class="form-label fw-semibold">
                                امتیاز
                            </label>
                            <input type="number" name="score" min="1" class="form-control input-soft"
                                value="{{ old('score', 1) }}">
                        </div>

                        <div class="col-md-8 step" data-step="1">
                            <label class="form-label fw-semibold">توضیح/راهنما (اختیاری)</label>
                            <input type="text" name="explanation" class="form-control input-soft"
                                placeholder="مثلاً نکته یا توضیح کوتاه..." value="{{ old('explanation') }}">
                        </div>

                        {{-- Step 2: Type --}}
                        <div class="col-md-8 step" data-step="2" style="display:none">
                            <label class="form-label fw-semibold">نوع سؤال <span class="text-danger">*</span></label>
                            <select name="type" class="form-select input-soft" id="typeSelect" required>
                                @php $type = old('type','mcq'); @endphp
                                <option value="mcq" {{ $type === 'mcq' ? 'selected' : '' }}>چندگزینه‌ای</option>
                                <option value="true_false" {{ $type === 'true_false' ? 'selected' : '' }}>صحیح/غلط</option>
                                <option value="fill_blank" {{ $type === 'fill_blank' ? 'selected' : '' }}>جای خالی</option>
                                <option value="essay" {{ $type === 'essay' ? 'selected' : '' }}>تشریحی</option>
                            </select>
                            <div class="hint mt-1">با انتخاب نوع، ورودی‌های مرتبط نمایش داده می‌شود.</div>
                        </div>

                        {{-- Step 2: Options block (MCQ + TrueFalse) --}}
                        <div class="col-12 step" data-step="2" id="optionsBlock" style="display:none">

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="fw-bold"><i class="bi bi-list-check me-1"></i> گزینه‌ها</div>
                                <button type="button" id="addOptionBtn" class="btn btn-outline-primary btn-sm btn-wizard">
                                    <i class="bi bi-plus"></i> افزودن گزینه
                                </button>
                            </div>

                            <div id="optionsWrap" class="d-grid gap-2">
                                @php
                                    $initialOptions = old('options');
                                    if (!$initialOptions) {
                                        $initialOptions = array_filter(
                                            [old('option_a'), old('option_b'), old('option_c'), old('option_d')],
                                            fn($x) => $x !== null,
                                        );
                                    }
                                    if (!$initialOptions || count($initialOptions) === 0) {
                                        $initialOptions = ['', '', '', ''];
                                    }
                                @endphp

                                @foreach ($initialOptions as $i => $opt)
                                    <div class="option-card p-2" data-idx="{{ $i }}">
                                        <div class="option-row">
                                            <div class="option-tag">{{ chr(65 + $i) }}</div>
                                            <input type="text" name="options[{{ $i }}]"
                                                class="form-control border-0 bg-transparent" value="{{ $opt }}"
                                                placeholder="متن گزینه">
                                            <div class="form-check ms-auto">
                                                <input class="form-check-input correct-radio" type="radio"
                                                    name="correct_answer" value="{{ $i }}"
                                                    {{ old('correct_answer') === (string) $i ? 'checked' : '' }}>
                                                <label class="form-check-label small">صحیح</label>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-danger remove-option">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Legacy hidden fields for controller compatibility --}}
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
                                <label class="form-label fw-semibold">
                                    گزینه صحیح را انتخاب کن: <span class="text-danger">*</span>
                                </label>

                                <div class="d-flex gap-3">
                                    <label class="form-check">
                                        <input class="form-check-input" type="radio" name="correct_tf" value="1"
                                            {{ old('correct_tf') === '1' ? 'checked' : '' }}>
                                        <span class="form-check-label">صحیح</span>
                                    </label>
                                    <label class="form-check">
                                        <input class="form-check-input" type="radio" name="correct_tf" value="0"
                                            {{ old('correct_tf') === '0' ? 'checked' : '' }}>
                                        <span class="form-check-label">غلط</span>
                                    </label>
                                </div>

                                <div class="hint mt-1">برای صحیح/غلط فقط همین دو انتخاب کافی است.</div>
                            </div>
                        </div>

                        {{-- Step 2: Fill blank block --}}
                        <div class="col-12 step" data-step="2" id="blankBlock" style="display:none">
                            <label class="form-label fw-semibold">
                                پاسخ(های) جای خالی <span class="text-danger">*</span>
                            </label>
                            <textarea name="correct_blanks" rows="3" class="form-control input-soft"
                                placeholder="پاسخ‌ها را با کاما یا خط جدید جدا کن...">{{ old('correct_blanks') }}</textarea>
                            <div class="hint mt-1">مثال: تهران, پایتخت ایران</div>
                        </div>

                        {{-- Step 2: Essay block --}}
                        <div class="col-12 step" data-step="2" id="descBlock" style="display:none">
                            <label class="form-label fw-semibold">راهنمای پاسخ تشریحی (اختیاری)</label>
                            <textarea name="descriptive_answer" rows="4" class="form-control input-soft"
                                placeholder="راهنمای پاسخ یا نکات مهم...">{{ old('descriptive_answer') }}</textarea>
                            <div class="hint mt-1">اگر explanation خالی باشد، این متن در explanation ذخیره می‌شود.</div>
                        </div>

                        {{-- Step 3 Review --}}
                        <div class="col-12 step" data-step="3" style="display:none">
                            <div class="preview-card p-3">
                                <div class="fw-bold mb-2"><i class="bi bi-eye me-1"></i> مرور نهایی</div>
                                <div class="small text-muted">قبل از ذخیره یکبار چک کن.</div>
                                <div class="border rounded-3 p-3 bg-white mt-2" id="reviewBox"></div>
                            </div>
                        </div>

                        {{-- Nav buttons --}}
                        <div class="col-12 d-flex flex-wrap gap-2 mt-2">
                            <button type="button" class="btn btn-outline-secondary btn-wizard" id="prevBtn" disabled>
                                <i class="bi bi-arrow-right"></i> قبلی
                            </button>
                            <button type="button" class="btn btn-primary btn-wizard shadow-sm" id="nextBtn">
                                بعدی <i class="bi bi-arrow-left"></i>
                            </button>
                            <button type="submit" class="btn btn-success btn-wizard shadow-sm" id="submitBtn"
                                style="display:none">
                                <i class="bi bi-check2-circle"></i> ذخیره سؤال
                            </button>
                        </div>

                    </form>
                </div>
            </div>

            {{-- Right column live preview/help --}}
            <div class="col-lg-4">
                <div class="floating-help">

                    <div class="preview-card p-3 mb-3">
                        <div class="fw-bold mb-2"><i class="bi bi-eye me-1"></i> پیش‌نمایش زنده</div>
                        <div class="border rounded-3 p-3 bg-white" id="livePreview">
                            <div class="fw-semibold" id="pvQuestion">متن سؤال</div>
                            <div class="text-muted small mt-2" id="pvType">نوع: mcq</div>
                            <div class="text-muted small mt-1" id="pvScore">امتیاز: 1</div>
                            <div class="mt-2" id="pvOptions"></div>
                        </div>
                    </div>

                    <div class="form-card p-3">
                        <div class="fw-bold mb-2"><i class="bi bi-lightbulb me-1 text-warning"></i> نکته‌ها</div>
                        <ul class="small text-muted mb-0 ps-3">
                            <li>MCQ: حداقل ۴ گزینه و یک گزینه صحیح.</li>
                            <li>صحیح/غلط: یکی از دو حالت را انتخاب کن.</li>
                            <li>جای خالی: چند پاسخ را با کاما جدا کن.</li>
                            <li>تشریحی: فقط متن و امتیاز کافی است.</li>
                        </ul>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // Wizard
            const steps = [...document.querySelectorAll('.step')];
            const wizSteps = [...document.querySelectorAll('.wiz-step')];
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const submitBtn = document.getElementById('submitBtn');
            let current = 1;

            function setStep(n) {
                current = n;
                steps.forEach(s => s.style.display = (Number(s.dataset.step) === n) ? '' : 'none');
                wizSteps.forEach(w => w.classList.toggle('active', Number(w.dataset.step) === n));
                prevBtn.disabled = n === 1;
                nextBtn.style.display = n < 3 ? '' : 'none';
                submitBtn.style.display = n === 3 ? '' : 'none';
                if (n === 3) fillReview();
            }

            prevBtn.addEventListener('click', () => setStep(Math.max(1, current - 1)));
            nextBtn.addEventListener('click', () => setStep(Math.min(3, current + 1)));

            // Blocks
            const typeSel = document.getElementById('typeSelect');
            const optionsBlock = document.getElementById('optionsBlock');
            const descBlock = document.getElementById('descBlock');
            const blankBlock = document.getElementById('blankBlock');
            const optionsWrap = document.getElementById('optionsWrap');
            const addOptionBtn = document.getElementById('addOptionBtn');
            const trueFalseBox = document.getElementById('trueFalseBox');

            // Legacy hidden inputs
            const legacyA = document.getElementById('legacyA');
            const legacyB = document.getElementById('legacyB');
            const legacyC = document.getElementById('legacyC');
            const legacyD = document.getElementById('legacyD');
            const legacyCorrect = document.getElementById('legacyCorrect');

            // Preview
            const pvQuestion = document.getElementById('pvQuestion');
            const pvType = document.getElementById('pvType');
            const pvScore = document.getElementById('pvScore');
            const pvOptions = document.getElementById('pvOptions');

            const qText = document.querySelector('textarea[name="question"]');
            const score = document.querySelector('input[name="score"]');
            const reviewBox = document.getElementById('reviewBox');

            const explanationInput = document.querySelector('input[name="explanation"]');
            const descriptiveInput = document.querySelector('textarea[name="descriptive_answer"]');

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
                const t = typeSel?.value;

                optionsBlock.style.display = (t === 'mcq' || t === 'true_false') ? '' : 'none';
                descBlock.style.display = (t === 'essay') ? '' : 'none';
                blankBlock.style.display = (t === 'fill_blank') ? '' : 'none';

                if (trueFalseBox) trueFalseBox.style.display = (t === 'true_false') ? 'block' : 'none';
                if (optionsWrap) optionsWrap.style.display = (t === 'mcq') ? '' : 'none';
                if (addOptionBtn) addOptionBtn.style.display = (t === 'mcq') ? '' : 'none';

                if (pvType) pvType.textContent = 'نوع: ' + t;

                ensureMinMcqOptions();
                refreshPreviewOptions();
            }

            function refreshPreviewOptions() {
                if (!pvOptions) return;
                const t = typeSel?.value;
                pvOptions.innerHTML = '';

                if (t === 'mcq') {
                    const opts = [...optionsWrap.querySelectorAll('input[name^="options"]')]
                        .map(i => i.value).filter(Boolean);

                    if (opts.length) {
                        pvOptions.innerHTML =
                            '<div class="text-muted small mt-2">گزینه‌ها:</div>' +
                            opts.map((o, idx) =>
                                `<div class="small mt-1">${String.fromCharCode(65+idx)}. ${o}</div>`).join('');
                    }
                }
            }

            function fillReview() {
                const t = typeSel?.value;
                const text = qText?.value?.trim() || '—';
                const sc = score?.value || 1;
                const exp = explanationInput?.value || '—';

                let html = `<div class="fw-semibold mb-1">سؤال:</div><div>${text}</div>`;
                html += `<div class="mt-2 text-muted small">نوع: ${t} • امتیاز: ${sc}</div>`;
                html += `<div class="mt-2 small"><span class="fw-semibold">توضیح:</span> ${exp}</div>`;

                if (t === 'mcq') {
                    const opts = [...optionsWrap.querySelectorAll('input[name^="options"]')]
                        .map(i => i.value).filter(Boolean);
                    html += '<div class="fw-semibold mt-3 mb-1">گزینه‌ها:</div>' +
                        opts.map((o, idx) => `<div class="small">${String.fromCharCode(65+idx)}. ${o}</div>`).join(
                            '');
                }

                if (t === 'true_false') {
                    const checked = document.querySelector('input[name="correct_tf"]:checked');
                    html += `<div class="fw-semibold mt-3 mb-1">پاسخ صحیح:</div>
                <div class="small">${checked ? (checked.value=="1"?"صحیح":"غلط") : "—"}</div>`;
                }

                if (t === 'fill_blank') {
                    const blanks = document.querySelector('[name="correct_blanks"]')?.value || '—';
                    html += `<div class="fw-semibold mt-3 mb-1">پاسخ‌ها:</div><div class="small">${blanks}</div>`;
                }

                if (t === 'essay') {
                    const guide = descriptiveInput?.value || '—';
                    html +=
                        `<div class="fw-semibold mt-3 mb-1">راهنمای پاسخ:</div><div class="small">${guide}</div>`;
                }

                if (reviewBox) reviewBox.innerHTML = html;
            }

            // MCQ options management
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

            function syncLegacyFields() {
                const opts = [...optionsWrap.querySelectorAll('input[name^="options"]')].map(i => i.value || '');

                legacyA.value = opts[0] ?? '';
                legacyB.value = opts[1] ?? '';
                legacyC.value = opts[2] ?? '';
                legacyD.value = opts[3] ?? '';

                const corr = document.querySelector('input[name="correct_answer"]:checked')?.value;
                if (corr !== undefined) {
                    legacyCorrect.value = ['a', 'b', 'c', 'd'][Number(corr)] ?? null;
                } else {
                    legacyCorrect.value = null;
                }
            }

            addOptionBtn?.addEventListener('click', () => {
                addOption();
                reindexOptions();
            });

            optionsWrap?.addEventListener('click', (e) => {
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

            optionsWrap?.addEventListener('input', (e) => {
                if (e.target.matches('input[name^="options"]')) {
                    refreshPreviewOptions();
                    syncLegacyFields();
                }
            });

            optionsWrap?.addEventListener('change', (e) => {
                if (e.target.matches('.correct-radio')) {
                    optionsWrap.querySelectorAll('.option-row')
                        .forEach(r => r.classList.remove('correct-glow'));
                    e.target.closest('.option-row')?.classList.add('correct-glow');
                    syncLegacyFields();
                }
            });

            // Live preview binds
            qText?.addEventListener('input', () => pvQuestion.textContent = qText.value || 'متن سؤال');
            score?.addEventListener('input', () => pvScore.textContent = 'امتیاز: ' + (score.value || 1));
            typeSel?.addEventListener('change', refreshBlocks);

            document.querySelector('form')?.addEventListener('submit', () => {
                if (typeSel?.value === 'essay') {
                    const guide = descriptiveInput?.value?.trim();
                    if (guide && (!explanationInput.value || explanationInput.value.trim().length === 0)) {
                        explanationInput.value = guide;
                    }
                }
                ensureMinMcqOptions();
                syncLegacyFields();
            });

            setStep(1);
            refreshBlocks();
            ensureMinMcqOptions();
            syncLegacyFields();
        });
    </script>
@endpush
