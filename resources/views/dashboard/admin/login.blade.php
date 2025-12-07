@extends('layouts.guest')

@section('title', 'ورود ادمین')

@section('content')
<div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="col-12 col-md-6 col-lg-4">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4">

                <h5 class="fw-bold mb-1 text-center">ورود ادمین</h5>
                <p class="text-muted small text-center mb-4">
                    شماره موبایل ادمین را وارد کنید تا کد ورود ارسال شود
                </p>

                {{-- Alerts --}}
                <div id="alertBox" class="alert d-none" role="alert"></div>

                {{-- Step 1: Phone --}}
                <div id="stepPhone">
                    <div class="mb-3">
                        <label class="form-label">شماره موبایل (بدون 0)</label>
                        <div class="input-group">
                            <span class="input-group-text">+98</span>
                            <input type="text" class="form-control text-start" id="phone"
                                   placeholder="9xxxxxxxxx" maxlength="10" autocomplete="tel">
                        </div>
                        <div class="form-text text-danger d-none" id="phoneError"></div>
                    </div>

                    <button id="btnSendOtp" class="btn btn-primary w-100">
                        ارسال کد تایید
                    </button>
                </div>

                {{-- Step 2: Code --}}
                <div id="stepCode" class="d-none">
                    <div class="mb-3">
                        <label class="form-label">کد تایید ۶ رقمی</label>
                        <input type="text" class="form-control text-center fs-5 tracking-wide"
                               id="code" placeholder="------" maxlength="6" inputmode="numeric">
                        <div class="form-text text-danger d-none" id="codeError"></div>
                    </div>

                    <button id="btnVerifyOtp" class="btn btn-success w-100 mb-3">
                        تایید و ورود
                    </button>

                    <div class="d-flex justify-content-between align-items-center small">
                        <button id="btnResendOtp" class="btn btn-link p-0 text-decoration-none" disabled>
                            ارسال مجدد
                        </button>
                        <span class="text-muted">
                            <span id="timer">01:00</span> تا ارسال مجدد
                        </span>
                    </div>

                    <hr class="my-3">

                    <button id="btnBack" class="btn btn-outline-secondary w-100">
                        تغییر شماره موبایل
                    </button>
                </div>

            </div>
        </div>

        <p class="text-center text-muted small mt-3">
            Smart-Edu Admin Panel
        </p>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const sendUrl   = "{{ route('admin.send-otp') }}";
    const verifyUrl = "{{ route('admin.verify-otp') }}";

    const csrfToken = "{{ csrf_token() }}";

    const stepPhone = document.getElementById('stepPhone');
    const stepCode  = document.getElementById('stepCode');

    const phoneInput = document.getElementById('phone');
    const codeInput  = document.getElementById('code');

    const btnSendOtp   = document.getElementById('btnSendOtp');
    const btnVerifyOtp = document.getElementById('btnVerifyOtp');
    const btnResendOtp = document.getElementById('btnResendOtp');
    const btnBack      = document.getElementById('btnBack');

    const alertBox  = document.getElementById('alertBox');
    const phoneError = document.getElementById('phoneError');
    const codeError  = document.getElementById('codeError');
    const timerEl    = document.getElementById('timer');

    let timer = 60;
    let timerInterval = null;
    let lastPhone = null;

    function showAlert(type, message) {
        alertBox.className = `alert alert-${type}`;
        alertBox.textContent = message;
        alertBox.classList.remove('d-none');
    }
    function hideAlert() {
        alertBox.classList.add('d-none');
    }

    function showPhoneError(msg) {
        phoneError.textContent = msg;
        phoneError.classList.remove('d-none');
    }
    function hidePhoneError() {
        phoneError.classList.add('d-none');
    }

    function showCodeError(msg) {
        codeError.textContent = msg;
        codeError.classList.remove('d-none');
    }
    function hideCodeError() {
        codeError.classList.add('d-none');
    }

    function validatePhone(phone) {
        return /^9\d{9}$/.test(phone);
    }

    function startTimer() {
        timer = 60;
        btnResendOtp.disabled = true;

        clearInterval(timerInterval);
        timerInterval = setInterval(() => {
            timer--;
            const m = String(Math.floor(timer / 60)).padStart(2, '0');
            const s = String(timer % 60).padStart(2, '0');
            timerEl.textContent = `${m}:${s}`;

            if (timer <= 0) {
                clearInterval(timerInterval);
                btnResendOtp.disabled = false;
                timerEl.textContent = "00:00";
            }
        }, 1000);
    }

    async function postJson(url, data) {
        const res = await fetch(url, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
                "Accept": "application/json"
            },
            body: JSON.stringify(data)
        });
        const json = await res.json().catch(() => ({}));
        return { ok: res.ok, status: res.status, json };
    }

    btnSendOtp.addEventListener('click', async () => {
        hideAlert(); hidePhoneError();

        const phone = phoneInput.value.trim();
        if (!validatePhone(phone)) {
            showPhoneError("شماره باید مثل 9xxxxxxxxx باشد.");
            return;
        }

        btnSendOtp.disabled = true;
        btnSendOtp.innerHTML = 'در حال ارسال...';

        const { ok, status, json } = await postJson(sendUrl, { phone });

        btnSendOtp.disabled = false;
        btnSendOtp.innerHTML = 'ارسال کد تایید';

        if (!ok) {
            showAlert("danger", json.message || "خطا در ارسال کد.");
            return;
        }

        lastPhone = phone;
        showAlert("success", json.message || "کد ارسال شد.");

        stepPhone.classList.add('d-none');
        stepCode.classList.remove('d-none');

        codeInput.value = "";
        codeInput.focus();

        startTimer();
    });

    btnVerifyOtp.addEventListener('click', async () => {
        hideAlert(); hideCodeError();

        const code = codeInput.value.trim();
        if (!/^\d{6}$/.test(code)) {
            showCodeError("کد باید ۶ رقم باشد.");
            return;
        }

        btnVerifyOtp.disabled = true;
        btnVerifyOtp.innerHTML = 'در حال بررسی...';

        const { ok, json } = await postJson(verifyUrl, {
            phone: lastPhone,
            code: code
        });

        btnVerifyOtp.disabled = false;
        btnVerifyOtp.innerHTML = 'تایید و ورود';

        if (!ok) {
            showAlert("danger", json.message || "کد معتبر نیست.");
            return;
        }

        showAlert("success", json.message || "ورود موفق.");

        // ریدایرکت به داشبورد ادمین
        if (json.redirect) {
            window.location.href = json.redirect;
        }
    });

    btnResendOtp.addEventListener('click', async () => {
        hideAlert(); hideCodeError();

        if (!lastPhone) return;

        btnResendOtp.disabled = true;
        const { ok, json } = await postJson(sendUrl, { phone: lastPhone });

        if (!ok) {
            btnResendOtp.disabled = false;
            showAlert("danger", json.message || "خطا در ارسال مجدد.");
            return;
        }

        showAlert("success", json.message || "کد دوباره ارسال شد.");
        startTimer();
    });

    btnBack.addEventListener('click', () => {
        hideAlert();
        stepCode.classList.add('d-none');
        stepPhone.classList.remove('d-none');

        phoneInput.focus();
        clearInterval(timerInterval);
    });

    // Enter key support
    phoneInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') btnSendOtp.click();
    });
    codeInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') btnVerifyOtp.click();
    });

})();
</script>
@endpush
