@extends('layouts.app')
@section('title', 'شرکت در آزمون')

@push('styles')
    <style>
        :root {
            --edu-blue: #2563eb;
            --edu-blue-2: #0ea5e9;
            --edu-blue-soft: #eff6ff;

            --edu-green: #16a34a;
            --edu-green-2: #22c55e;
            --edu-green-soft: #ecfdf3;

            --edu-amber: #f59e0b;
            --edu-amber-2: #fbbf24;
            --edu-amber-soft: #fffbeb;

            --edu-red: #dc2626;
            --edu-red-soft: #fff1f2;

            --edu-gray: #0f172a;
            --edu-muted: #64748b;
            --edu-bg: #f8fafc;

            --radius-xl: 1.25rem;
            --radius-lg: .9rem;
        }

        .take-page {
            animation: pageFade .6s ease both;
        }

        @keyframes pageFade {
            from {
                opacity: 0;
                transform: translateY(6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-soft {
            border: 1px solid #e2e8f0;
            background: #fff;
            border-radius: var(--radius-xl);
            box-shadow: 0 10px 28px rgba(15, 23, 42, .06);
        }

        .top-hero {
            background: linear-gradient(135deg, var(--edu-blue) 0%, var(--edu-blue-2) 55%, var(--edu-green-2) 100%);
            color: #fff;
            border-radius: var(--radius-xl);
            padding: 1rem 1.1rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 14px 35px rgba(37, 99, 235, .2);
            max-width: 1200px;
            margin-inline: auto;
            isolation: isolate;
        }

        .top-hero::after {
            content: "";
            position: absolute;
            inset: -35% -20% auto auto;
            width: 280px;
            height: 280px;
            background: radial-gradient(circle, rgba(255, 255, 255, .35), transparent 70%);
            transform: rotate(18deg);
            opacity: .85;
            pointer-events: none;
            z-index: -1;
        }

        .top-hero h4 {
            font-weight: 900;
            margin: 0;
            font-size: 1.15rem;
        }

        .top-hero .sub {
            font-size: .85rem;
            color: rgba(255, 255, 255, .92);
            margin-top: .25rem;
        }

        .timer-pill {
            background: #0f172a;
            color: #fff;
            border-radius: 999px;
            padding: .45rem .9rem;
            font-weight: 900;
            font-size: .95rem;
            letter-spacing: .5px;
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            box-shadow: 0 8px 20px rgba(0, 0, 0, .18);
            transition: .2s ease;
        }

        .timer-pill.warn {
            background: #b45309;
        }

        .timer-pill.danger {
            background: #dc2626;
            animation: pulse 1.2s infinite ease-in-out;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.04);
            }
        }

        .progress-wrap {
            background: rgba(255, 255, 255, .15);
            border-radius: 999px;
            height: 9px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, .25);
        }

        .progress-bar-edu {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, #fff, #dbeafe);
            transition: .35s ease;
        }

        .progress-text {
            font-size: .82rem;
            color: rgba(255, 255, 255, .98);
            font-weight: 900;
            white-space: nowrap;
        }

        .progress-meta {
            font-size: .8rem;
            font-weight: 900;
            color: rgba(255, 255, 255, .95);
            display: flex;
            align-items: center;
            gap: .35rem;
            background: rgba(0, 0, 0, .18);
            padding: .2rem .6rem;
            border-radius: 999px;
        }

        .q-section {
            display: none;
        }

        .q-section.active {
            display: block;
            animation: qIn .35s ease both;
        }

        @keyframes qIn {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .q-head {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: .5rem;
            margin-bottom: .6rem;
        }

        .q-number {
            font-weight: 900;
            color: var(--edu-gray);
            display: flex;
            align-items: center;
            gap: .45rem;
            font-size: 1rem;
        }

        .badge-type {
            background: var(--edu-blue-soft);
            color: var(--edu-blue);
            border: 1px solid #bfdbfe;
            font-weight: 900;
            border-radius: 999px;
            padding: .35rem .6rem;
            font-size: .78rem;
        }

        .q-card {
            border: 1px solid #bfdbfe;
            background: linear-gradient(180deg, #ffffff, #f8fbff);
            border-radius: var(--radius-lg);
            padding: 1rem 1.1rem;
            position: relative;
            box-shadow: inset 0 0 0 2px #eff6ff;
            line-height: 1.9;
            font-size: 1rem;
        }

        .q-card::before {
            content: "سؤال";
            position: absolute;
            top: .75rem;
            left: .75rem;
            font-size: .72rem;
            font-weight: 900;
            color: #1d4ed8;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            padding: .12rem .5rem;
            border-radius: 999px;
        }

        .answer-box {
            margin-top: .9rem;
            border: 1px dashed #86efac;
            background: #f6fffa;
            border-radius: var(--radius-lg);
            padding: .9rem;
            position: relative;
        }

        .answer-box::before {
            content: "پاسخ‌ها";
            position: absolute;
            top: -10px;
            right: 14px;
            font-size: .72rem;
            font-weight: 900;
            color: #166534;
            background: #ecfdf3;
            border: 1px solid #bbf7d0;
            padding: .1rem .5rem;
            border-radius: 999px;
        }

        .opt-item {
            border: 1px solid #e2e8f0;
            border-radius: .9rem;
            padding: .8rem .9rem;
            cursor: pointer;
            transition: .12s ease;
            display: flex;
            align-items: center;
            gap: .6rem;
            background: #ffffff;
            font-weight: 700;
        }

        .opt-item:hover {
            background: #f8fafc;
            border-color: #94a3b8;
            transform: translateY(-1px);
        }

        .opt-item.active {
            border-color: #86efac;
            background: #ecfdf3;
            box-shadow: 0 8px 18px rgba(34, 197, 94, .12);
        }

        .opt-item input {
            transform: translateY(1px) scale(1.08);
        }

        .essay-input {
            min-height: 140px;
            border-radius: .9rem;
        }

        .fill-input {
            border-radius: .9rem;
        }

        .nav-btn {
            border-radius: .9rem;
            font-weight: 900;
            padding: .6rem .95rem;
        }

        .sticky-box {
            position: sticky;
            top: 1rem;
        }

        .dots-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: .5rem;
        }

        .q-dot {
            width: 36px;
            height: 36px;
            border-radius: 999px;
            display: grid;
            place-items: center;
            font-weight: 900;
            cursor: pointer;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            transition: .15s ease;
            font-size: .85rem;
        }

        .q-dot:hover {
            transform: translateY(-1px);
            border-color: #cbd5e1;
        }

        .q-dot.active {
            background: var(--edu-blue);
            color: #fff;
            border-color: var(--edu-blue);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, .12);
        }

        .q-dot.answered {
            background: var(--edu-green-soft);
            border-color: var(--edu-green);
            color: var(--edu-green);
            box-shadow: 0 0 0 4px rgba(34, 197, 94, .12);
        }

        .q-dot.flagged {
            background: var(--edu-amber-soft);
            border-color: var(--edu-amber-2);
            color: #92400e;
        }

        .legend {
            display: flex;
            flex-wrap: wrap;
            gap: .5rem;
            font-size: .78rem;
            color: var(--edu-muted);
            font-weight: 800;
        }

        .legend span {
            display: flex;
            align-items: center;
            gap: .35rem;
        }

        .submit-section {
            border: 1px dashed #cbd5e1;
            background: #f8fafc;
            border-radius: var(--radius-lg);
        }

        .reward-flash {
            position: fixed;
            inset: 0;
            pointer-events: none;
            display: none;
            z-index: 9999;
            background: radial-gradient(circle at top, rgba(34, 197, 94, .12), transparent 55%);
            animation: flash .6s ease both;
        }

        @keyframes flash {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @media (max-width: 992px) {
            .dots-grid {
                grid-template-columns: repeat(10, 1fr);
            }

            .sticky-box {
                position: static;
            }
        }
    </style>
@endpush

@section('content')

    {{-- Guard --}}
    @if (empty($attempt))
        <div class="container py-4">
            <div class="alert alert-warning d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>برای شرکت در آزمون باید اول «شروع آزمون» را بزنید.</div>
                <a href="{{ route('student.exams.show', $exam->id) }}" class="btn btn-primary btn-sm">
                    بازگشت به صفحه آزمون
                </a>
            </div>
        </div>
        @php return; @endphp
    @endif

    <div class="reward-flash" id="rewardFlash"></div>

    <div class="take-page container py-3 py-md-4">

        {{-- ================= TOP HERO ================= --}}
        <div class="top-hero mb-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>
                    <h4>
                        <i class="bi bi-pencil-square me-1"></i>
                        شرکت در آزمون
                    </h4>
                    <div class="sub">
                        آزمون: <span class="fw-bold">{{ $exam->title }}</span>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    {{-- ✅ Timer based on exams.duration --}}
                    @if (!empty($exam->duration))
                        <div class="timer-pill" id="timerPill" data-min="{{ $exam->duration }}">
                            <i class="bi bi-stopwatch"></i>
                            <span id="timerText">{{ $exam->duration }}:00</span>
                        </div>
                    @endif

                    <a href="{{ route('student.exams.show', $exam->id) }}" class="btn btn-light btn-sm fw-bold">
                        <i class="bi bi-arrow-right"></i> بازگشت
                    </a>
                </div>
            </div>

            {{-- progress --}}
            <div class="mt-3 d-flex align-items-center gap-2">
                <div class="progress-wrap flex-grow-1">
                    <div class="progress-bar-edu" id="progressBar"></div>
                </div>
                <div class="progress-text" id="progressText">0%</div>
                <div class="progress-meta" id="progressMeta">
                    <i class="bi bi-layers"></i>
                    سؤال 1 از {{ $exam->questions->count() }}
                </div>
            </div>
        </div>


        <div class="row g-3">

            {{-- ================= QUESTIONS ================= --}}
            <div class="col-lg-9">

                <form method="POST" action="{{ route('student.exams.submit', $exam->id) }}" id="examForm">
                    @csrf

                    @if (isset($attempt) && $attempt)
                        <input type="hidden" name="attempt_id" value="{{ $attempt->id }}">
                    @endif

                    @foreach ($exam->questions as $q)
                        @php
                            $savedAnswers = $attempt?->answers ?? [];
                            $saved = $savedAnswers[$q->id] ?? null;

                            // ✅ normalize saved for mcq
                            if (is_array($saved)) {
                                $saved = $saved[0] ?? null;
                            }

                            // ✅ MCQ options support legacy + JSON
                            $opts = [];
                            if ($q->type === 'mcq') {
                                if (is_array($q->options) && count($q->options)) {
                                    $opts = $q->options; // expected {"a":"..","b":".."}
                                } else {
                                    $opts = [
                                        'a' => $q->option_a,
                                        'b' => $q->option_b,
                                        'c' => $q->option_c,
                                        'd' => $q->option_d,
                                    ];
                                }
                            }
                        @endphp

                        <div class="card-soft p-3 p-md-4 mb-3 q-section" id="q-{{ $q->id }}"
                            data-qid="{{ $q->id }}" data-index="{{ $loop->index }}">

                            {{-- Head --}}
                            <div class="q-head">
                                <div class="q-number">
                                    <span class="badge bg-primary rounded-pill">{{ $loop->iteration }}</span>
                                    سوال {{ $loop->iteration }}
                                </div>

                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge-type">{{ $q->type }}</span>

                                    @if (!empty($q->score))
                                        <span class="small text-muted fw-bold">
                                            امتیاز: {{ $q->score }}
                                        </span>
                                    @endif

                                    {{-- flag --}}
                                    <button type="button" class="btn btn-outline-warning btn-sm nav-btn flag-btn"
                                        data-flag="{{ $q->id }}">
                                        <i class="bi bi-flag"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- Question text --}}
                            <div class="q-card mb-3">
                                {!! nl2br(e($q->question ?? $q->question_text)) !!}
                            </div>

                            {{-- Answer box --}}
                            <div class="answer-box">
                                @if ($q->type === 'mcq')
                                    <div class="vstack gap-2">
                                        @foreach ($opts as $key => $text)
                                            @if ($text)
                                                <label class="opt-item {{ $saved == $key ? 'active' : '' }}">
                                                    <input class="form-check-input m-0" type="radio"
                                                        name="answers[{{ $q->id }}]" value="{{ $key }}"
                                                        @checked($saved == $key)>
                                                    <div class="flex-grow-1">
                                                        <span class="fw-bold me-1">{{ strtoupper($key) }}.</span>
                                                        {{ is_string($text) ? $text : json_encode($text) }}
                                                    </div>
                                                </label>
                                            @endif
                                        @endforeach
                                    </div>
                                @elseif($q->type === 'true_false')
                                    <div class="vstack gap-2">
                                        <label
                                            class="opt-item {{ $saved === 'true' || $saved === true || $saved === 1 ? 'active' : '' }}">
                                            <input class="form-check-input m-0" type="radio"
                                                name="answers[{{ $q->id }}]" value="true"
                                                @checked($saved === 'true' || $saved === true || $saved === 1)>
                                            <div class="flex-grow-1 fw-bold">صحیح</div>
                                        </label>

                                        <label
                                            class="opt-item {{ $saved === 'false' || $saved === false || $saved === 0 ? 'active' : '' }}">
                                            <input class="form-check-input m-0" type="radio"
                                                name="answers[{{ $q->id }}]" value="false"
                                                @checked($saved === 'false' || $saved === false || $saved === 0)>
                                            <div class="flex-grow-1 fw-bold">غلط</div>
                                        </label>
                                    </div>
                                @elseif($q->type === 'fill_blank')
                                    <div>
                                        <label class="form-label small text-muted fw-bold">
                                            پاسخ را وارد کن (اگر چند جواب دارد با «کاما» جدا کن)
                                        </label>
                                        <input type="text" name="answers[{{ $q->id }}]"
                                            value="{{ old('answers.' . $q->id, $saved) }}" class="form-control fill-input"
                                            placeholder="مثال: تهران, ایران">
                                    </div>
                                @elseif($q->type === 'essay')
                                    <div>
                                        <label class="form-label small text-muted fw-bold">پاسخ تشریحی</label>
                                        <textarea name="answers[{{ $q->id }}]" class="form-control essay-input" rows="4"
                                            placeholder="پاسخ خود را بنویس...">{{ old('answers.' . $q->id, $saved) }}</textarea>
                                        <div class="small text-muted mt-1">
                                            این سوال توسط معلم تصحیح می‌شود.
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-warning small">
                                        نوع سوال ناشناخته است. لطفاً به پشتیبانی اطلاع دهید.
                                    </div>
                                @endif
                            </div>

                            {{-- nav buttons --}}
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary btn-sm nav-btn prev-btn">
                                    <i class="bi bi-chevron-right"></i> سوال قبلی
                                </button>
                                <button type="button" class="btn btn-outline-primary btn-sm nav-btn next-btn">
                                    سوال بعدی <i class="bi bi-chevron-left"></i>
                                </button>
                            </div>
                        </div>

                        @if ($loop->last)
                            {{-- Submit --}}
                            <div class="card-soft p-3 p-md-4 submit-section">
                                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                    <div class="text-muted small fw-bold">
                                        بعد از ارسال، امکان تغییر پاسخ‌ها نیست.
                                    </div>
                                    <button id="finalSubmitBtnMain" type="submit" class="btn btn-success px-4 fw-bold">
                                        <i class="bi bi-check2-circle"></i> ارسال نهایی
                                    </button>
                                </div>
                            </div>
                        @endif
                    @endforeach

                </form>
            </div>


            {{-- ================= SIDE NAV ================= --}}
            <div class="col-lg-3">
                <div class="card-soft p-3 sticky-box">
                    <div class="fw-bold mb-2 d-flex align-items-center gap-2">
                        <i class="bi bi-map"></i>
                        مسیر سؤال‌ها
                    </div>

                    <div class="dots-grid" id="dotsBox">
                        @foreach ($exam->questions as $q)
                            <div class="q-dot" data-goto="q-{{ $q->id }}">
                                {{ $loop->iteration }}
                            </div>
                        @endforeach
                    </div>

                    <hr>

                    <div class="legend">
                        <span><span class="q-dot active"></span> سؤال فعلی</span>
                        <span><span class="q-dot answered"></span> پاسخ داده‌شده</span>
                        <span><span class="q-dot flagged"></span> علامت‌دار</span>
                    </div>

                    <div class="small text-muted mt-2">
                        روی شماره‌ها بزن تا سریع بری همون سؤال.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        (function() {
            const sections = Array.from(document.querySelectorAll('.q-section'));
            const dots = Array.from(document.querySelectorAll('.q-dot'));
            const submitBox = document.querySelector('.submit-section');

            let currentIndex = 0;
            const total = sections.length;

            function isQuestionAnswered(section) {
                const qid = section.dataset.qid;
                const checkedRadio = section.querySelector(`input[type="radio"][name="answers[${qid}]"]:checked`);
                if (checkedRadio) return true;

                const textInput = section.querySelector(`input[type="text"][name="answers[${qid}]"]`);
                if (textInput && textInput.value.trim()) return true;

                const textarea = section.querySelector(`textarea[name="answers[${qid}]"]`);
                if (textarea && textarea.value.trim()) return true;

                return false;
            }

            function updateAnsweredState(section) {
                const qid = section.dataset.qid;
                const idx = sections.findIndex(s => s.dataset.qid == qid);
                if (idx < 0) return;

                if (isQuestionAnswered(section)) dots[idx].classList.add('answered');
                else dots[idx].classList.remove('answered');
            }

            function updateProgress() {
                const answeredCount = sections.filter(s => isQuestionAnswered(s)).length;
                const percent = Math.round((answeredCount / total) * 100);

                const bar = document.getElementById('progressBar');
                const text = document.getElementById('progressText');
                const meta = document.getElementById('progressMeta');

                if (bar) bar.style.width = percent + '%';
                if (text) text.textContent = percent + '%';
                if (meta) meta.innerHTML = `<i class="bi bi-layers"></i> سؤال ${currentIndex+1} از ${total}`;
            }

            function showRewardFlash() {
                const fx = document.getElementById('rewardFlash');
                if (!fx) return;
                fx.style.display = 'block';
                setTimeout(() => fx.style.display = 'none', 500);
            }

            function fixNavButtonsForCurrent() {
                const currentSection = sections[currentIndex];
                if (!currentSection) return;

                const prevBtn = currentSection.querySelector('.prev-btn');
                const nextBtn = currentSection.querySelector('.next-btn');
                if (prevBtn) prevBtn.disabled = (currentIndex === 0);
                if (nextBtn) nextBtn.disabled = (currentIndex === total - 1);
            }

            function showQuestion(i) {
                currentIndex = Math.min(Math.max(i, 0), total - 1);

                sections.forEach((s, idx) => s.classList.toggle('active', idx === currentIndex));
                dots.forEach((d, idx) => d.classList.toggle('active', idx === currentIndex));

                if (submitBox) {
                    submitBox.style.display = (currentIndex === total - 1) ? 'block' : 'none';
                }

                fixNavButtonsForCurrent();
                updateProgress();

                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }

            // init
            sections.forEach((s, idx) => idx === 0 ? s.classList.add('active') : s.classList.remove('active'));
            dots[0]?.classList.add('active');
            if (submitBox) submitBox.style.display = 'none';
            updateProgress();
            fixNavButtonsForCurrent();

            // prev/next
            document.querySelectorAll('.prev-btn').forEach(btn =>
                btn.addEventListener('click', () => showQuestion(currentIndex - 1))
            );
            document.querySelectorAll('.next-btn').forEach(btn =>
                btn.addEventListener('click', () => showQuestion(currentIndex + 1))
            );

            // dots jump
            dots.forEach((dot, idx) => dot.addEventListener('click', () => showQuestion(idx)));

            // option highlight + answered state
            document.querySelectorAll('.opt-item input[type=radio]').forEach(inp => {
                inp.addEventListener('change', e => {
                    const section = e.target.closest('.q-section');
                    const options = section.querySelectorAll('.opt-item');
                    options.forEach(o => o.classList.remove('active'));
                    e.target.closest('.opt-item')?.classList.add('active');

                    updateAnsweredState(section);
                    updateProgress();
                    showRewardFlash();
                });
            });

            document.querySelectorAll('input[type=text], textarea').forEach(inp => {
                inp.addEventListener('input', e => {
                    const section = e.target.closest('.q-section');
                    updateAnsweredState(section);
                    updateProgress();
                });
            });

            // flagging
            document.querySelectorAll('.flag-btn').forEach(btn => {
                btn.addEventListener('click', e => {
                    const section = e.target.closest('.q-section');
                    const qid = section.dataset.qid;
                    const idx = sections.findIndex(s => s.dataset.qid == qid);
                    if (idx < 0) return;
                    dots[idx].classList.toggle('flagged');
                });
            });

            // Timer
            const timerPill = document.getElementById('timerPill');
            if (timerPill) {
                let minutes = parseInt(timerPill.dataset.min || '0');
                let seconds = minutes * 60;
                const timerText = document.getElementById('timerText');

                function setTimerStyle() {
                    timerPill.classList.remove('warn', 'danger');
                    if (seconds <= 60) timerPill.classList.add('danger');
                    else if (seconds <= 5 * 60) timerPill.classList.add('warn');
                }

                function tick() {
                    seconds--;
                    if (seconds < 0) seconds = 0;

                    const m = Math.floor(seconds / 60);
                    const s = seconds % 60;
                    timerText.textContent = `${m}:${String(s).padStart(2,'0')}`;

                    setTimerStyle();

                    if (seconds <= 0) document.getElementById('examForm').submit();
                }

                setTimerStyle();
                setInterval(tick, 1000);
            }

        })();
    </script>
@endpush
