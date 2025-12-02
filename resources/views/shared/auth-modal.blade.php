<div class="modal fade" id="authModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="authModalTitle">ورود / ثبت‌نام</h5>
                <button type="button" class="btn-close ms-0" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                {{-- Step 1: Phone --}}
                <div id="phoneStep" class="auth-step">
                    <form id="phoneForm">
                        <div class="mb-4">
                            <label for="phone" class="form-label">شماره همراه</label>
                            <div class="input-group">
                                <span class="input-group-text">+98</span>
                                <input type="tel" class="form-control" id="phone" placeholder="9123456789"
                                    required maxlength="10">
                            </div>
                            <div class="form-text text-muted">شماره را بدون صفر وارد کنید.</div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="fas fa-sms ms-2"></i>
                            دریافت کد تایید
                        </button>
                    </form>
                </div>

                {{-- Step 2: Verify --}}
                <div id="verificationStep" class="auth-step" style="display:none;">
                    <div class="text-center mb-4">
                        <p class="mb-1">کد تایید به شماره</p>
                        <div id="phoneNumberDisplay" class="fw-bold"></div>
                    </div>

                    <form id="verificationForm">
                        <div class="mb-4">
                            <label for="verificationCode" class="form-label">کد تایید</label>
                            <input type="text" class="form-control text-center verification-code"
                                id="verificationCode" placeholder="______" maxlength="6" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">تایید و ادامه</button>

                        <button type="button" class="btn btn-outline-secondary w-100" id="resendCodeBtn">
                            ارسال مجدد کد <span id="countdown">(60)</span>
                        </button>
                    </form>
                </div>

                {{-- Step 3: Role --}}
                <div id="roleStep" class="auth-step" style="display:none;">
                    <div class="text-center mb-4">
                        <h6 class="fw-bold">نقش خود را انتخاب کنید</h6>
                        <p class="text-muted small">برای ورود به پنل اختصاصی</p>
                    </div>

                    <div class="row g-3">
                        <div class="col-6">
                            {{-- ✅ role باید دقیقاً student باشد --}}
                            <button type="button" class="btn btn-outline-primary w-100 h-100 py-4 role-btn"
                                onclick="selectRole('student')">
                                <i class="fas fa-user-graduate fa-3x mb-3"></i>
                                <div class="fw-bold">دانش‌آموز</div>
                                <small class="text-muted">ورود سریع</small>
                            </button>
                        </div>

                        <div class="col-6">
                            {{-- ✅ role باید دقیقاً teacher باشد --}}
                            <button type="button" class="btn btn-outline-success w-100 h-100 py-4 role-btn"
                                onclick="selectRole('teacher')">
                                <i class="fas fa-chalkboard-teacher fa-3x mb-3"></i>
                                <div class="fw-bold">معلم</div>
                                <small class="text-muted">نیاز به تایید</small>
                            </button>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
