<div class="modal fade" id="authModal"
data-send-url="/auth/send-otp"
data-verify-url="/auth/verify-otp"
data-role-url="/auth/set-role"
     tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg"
             style="
                background:#fff;
                border-radius:var(--radius-lg) !important;
                border:1px solid rgba(74,163,255,.20);
                overflow:hidden;
             ">

            {{-- Header --}}
            <div class="modal-header"
                 style="
                    background:linear-gradient(135deg, rgba(74,163,255,.10), rgba(111,215,178,.10));
                    border-bottom:1px solid rgba(74,163,255,.18);
                    padding:25px 30px;
                    position:relative;
                    overflow:hidden;
                 ">
                <div style="
                    position:absolute; top:-50%; right:-20%; width:200px; height:200px;
                    background:radial-gradient(circle, rgba(74,163,255,.10), transparent 70%);
                    border-radius:50%;
                "></div>

                <h5 class="modal-title fw-bold" id="authModalTitle"
                    style="
                        font-size:1.5rem;
                        color:var(--text);
                        display:flex;
                        align-items:center;
                        gap:12px;
                        position:relative;
                        z-index:2;
                    ">
                    <span style="
                        background:linear-gradient(120deg, var(--sky) 0%, var(--sky-dark) 100%);
                        -webkit-background-clip:text;
                        -webkit-text-fill-color:transparent;
                        background-clip:text;
                    ">ورود / ثبت‌نام</span>
                    🔐
                </h5>

                <button type="button" class="btn-close ms-0" data-bs-dismiss="modal"
                        style="position:relative; z-index:2; opacity:.7;"></button>
            </div>

            {{-- Body --}}
            <div class="modal-body" style="padding:40px 30px;">

                {{-- ✅ hidden fields (used by landing.js) --}}
                <input type="hidden" name="role" id="authRole" value="">
                <input type="hidden" name="redirect" id="authRedirect" value="">

                {{-- Status / Error (used by JS) --}}
                <div id="authStatus" class="alert alert-info d-none" style="font-weight:800;"></div>
                <div id="authError" class="alert alert-danger d-none" style="font-weight:800;"></div>

                {{-- Step 1: Phone --}}
                <div id="phoneStep" class="auth-step">
                    <div class="text-center mb-4">
                        <div style="
                            width:80px;height:80px;margin:0 auto 20px;
                            background:rgba(74,163,255,.10);
                            border-radius:50%;
                            display:flex;align-items:center;justify-content:center;
                            font-size:2rem;color:var(--sky);
                            border:2px solid rgba(74,163,255,.35);
                        ">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h4 style="font-weight:900;color:var(--text);margin-bottom:10px;font-size:1.3rem;">
                            ورود با شماره همراه
                        </h4>
                        <p style="color:var(--muted);font-size:1.05rem;line-height:1.6;max-width:400px;margin:0 auto;">
                            برای ورود یا ثبت‌نام، شماره همراه خود را وارد کنید.
                        </p>
                    </div>

                    <form id="phoneForm">
                        <div class="mb-4" style="position:relative;">
                            <label for="phone" class="form-label"
                                   style="color:var(--text);font-weight:900;font-size:1rem;margin-bottom:12px;display:flex;align-items:center;gap:10px;">
                                <i class="fas fa-phone"
                                   style="color:var(--sky);background:rgba(74,163,255,.10);width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;"></i>
                                شماره همراه
                            </label>

                            <div class="input-group" dir="ltr"
                                 style="border-radius:var(--radius-md);overflow:hidden;border:1px solid rgba(0,0,0,.08);">
                                <span class="input-group-text"
                                      style="background:var(--sky);color:#fff;border:none;font-weight:900;padding:16px 20px;font-size:1.05rem;">98</span>
                                <input type="tel" class="form-control text-start" id="phone"
                                       placeholder="9123456789" dir="ltr" required maxlength="10"
                                       style="padding:16px 20px;border:none;background:#fff;color:var(--text);font-weight:700;font-size:1.05rem;direction:ltr !important;text-align:left !important;unicode-bidi:plaintext;">
                            </div>

                            <div class="form-text" style="color:var(--muted);margin-top:8px;font-size:0.9rem;">
                                شماره را بدون صفر وارد کنید. کد تأیید به این شماره ارسال خواهد شد.
                            </div>
                        </div>

                        <button type="submit" class="btn w-100 py-3"
                                style="
                                    background:linear-gradient(135deg, var(--sky), var(--sky-dark));
                                    color:#fff;border:none;border-radius:var(--radius-lg);
                                    font-weight:900;font-size:1.1rem;
                                    transition:all .3s ease;
                                    box-shadow:0 8px 20px rgba(74,163,255,.25);
                                    display:flex;align-items:center;justify-content:center;gap:10px;
                                    min-height:55px;
                                ">
                            <i class="fas fa-sms"></i>
                            دریافت کد تایید
                        </button>
                    </form>
                </div>

                {{-- Step 2: Verify --}}
                <div id="verificationStep" class="auth-step" style="display:none;">
                    <div class="text-center mb-4">
                        <div style="
                            width:80px;height:80px;margin:0 auto 20px;
                            background:rgba(111,215,178,.12);
                            border-radius:50%;
                            display:flex;align-items:center;justify-content:center;
                            font-size:2rem;color:var(--green);
                            border:2px solid rgba(111,215,178,.35);
                        ">
                            <i class="fas fa-shield-alt"></i>
                        </div>

                        <h4 style="font-weight:900;color:var(--text);margin-bottom:10px;font-size:1.3rem;">
                            تأیید شماره همراه
                        </h4>
                        <p class="mb-1" style="color:var(--muted);font-size:1.05rem;">کد تایید به شماره</p>

                        <div id="phoneNumberDisplay" class="fw-bold" dir="ltr"
                             style="
                                direction:ltr;text-align:center;color:var(--sky);
                                font-size:1.2rem;background:rgba(74,163,255,.10);
                                padding:8px 20px;border-radius:var(--radius-md);
                                display:inline-block;margin:10px 0;
                             "></div>
                    </div>

                    <form id="verificationForm">
                        <div class="mb-4">
                            <label for="verificationCode" class="form-label"
                                   style="color:var(--text);font-weight:900;font-size:1rem;margin-bottom:12px;display:flex;align-items:center;gap:10px;">
                                <i class="fas fa-key"
                                   style="color:var(--sky);background:rgba(74,163,255,.10);width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;"></i>
                                کد تأیید ۶ رقمی
                            </label>

                            <div style="position:relative;">
                                <input type="text" class="form-control text-center verification-code"
                                       id="verificationCode" placeholder="______" maxlength="6" required
                                       style="
                                            padding:18px;
                                            font-size:1.8rem;
                                            letter-spacing:15px;
                                            font-weight:900;
                                            border:2px solid rgba(0,0,0,.08);
                                            border-radius:var(--radius-md);
                                            background:#fff;
                                            color:var(--text);
                                            text-align:center;
                                            transition:all .3s ease;
                                       ">
                                <div style="
                                    position:absolute; left:50%; transform:translateX(-50%);
                                    bottom:-25px; color:var(--muted);
                                    font-size:.9rem; font-weight:700;
                                ">کد ۶ رقمی را وارد کنید</div>
                            </div>
                        </div>

                        <button type="submit" class="btn w-100 py-3 mb-3"
                                style="
                                    background:var(--green);
                                    color:#fff;border:none;border-radius:var(--radius-lg);
                                    font-weight:900;font-size:1.1rem;
                                    transition:all .3s ease;
                                    box-shadow:0 8px 20px rgba(111,215,178,.25);
                                    display:flex;align-items:center;justify-content:center;gap:10px;
                                    min-height:55px;
                                ">
                            <i class="fas fa-check-circle"></i>
                            تأیید و ادامه
                        </button>

                        <button type="button" class="btn w-100 py-3" id="resendCodeBtn"
                                style="
                                    background:transparent;color:var(--muted);
                                    border:1px solid rgba(0,0,0,.10);
                                    border-radius:var(--radius-lg);
                                    font-weight:900;font-size:1rem;
                                    transition:all .3s ease;
                                    min-height:55px;
                                ">
                            <i class="fas fa-redo ms-2"></i>
                            ارسال مجدد کد
                            <span id="countdown" style="color:var(--sky);margin-right:5px;"></span>
                        </button>
                    </form>
                </div>

                {{-- Step 3: Role --}}
                <div id="roleStep" class="auth-step" style="display:none;">
                    <div class="text-center mb-4">
                        <div style="
                            width:80px;height:80px;margin:0 auto 20px;
                            background:rgba(214,167,58,.14);
                            border-radius:50%;
                            display:flex;align-items:center;justify-content:center;
                            font-size:2rem;color:var(--gold);
                            border:2px solid rgba(214,167,58,.35);
                        ">
                            <i class="fas fa-user-tie"></i>
                        </div>

                        <h4 style="font-weight:900;color:var(--text);margin-bottom:10px;font-size:1.3rem;">
                            انتخاب نقش
                        </h4>
                        <p style="color:var(--muted);font-size:1.05rem;line-height:1.6;max-width:500px;margin:0 auto;">
                            برای ورود به پنل اختصاصی، نقش خود را انتخاب کنید.
                        </p>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <button type="button"
                                    class="btn w-100 h-100 py-4 role-btn js-select-role"
                                    data-role="student"
                                    style="
                                        border:1px solid rgba(0,0,0,.10);
                                        background:#fff;
                                        border-radius:var(--radius-lg);
                                        transition:all .3s ease;
                                        height:100%;
                                        cursor:pointer;
                                    ">
                                <div style="
                                    width:80px;height:80px;margin:0 auto 20px;
                                    background:linear-gradient(135deg, var(--sky), var(--sky-dark));
                                    border-radius:50%;
                                    display:flex;align-items:center;justify-content:center;
                                    color:#fff;font-size:2.5rem;
                                ">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                                <div style="font-weight:900;color:var(--text);font-size:1.3rem;margin-bottom:8px;">
                                    دانش‌آموز
                                </div>
                                <small style="color:var(--muted);font-size:.95rem;line-height:1.5;">
                                    ورود سریع به پنل دانش‌آموزی
                                </small>
                            </button>
                        </div>

                        <div class="col-md-6">
                            <button type="button"
                                    class="btn w-100 h-100 py-4 role-btn js-select-role"
                                    data-role="teacher"
                                    style="
                                        border:1px solid rgba(0,0,0,.10);
                                        background:#fff;
                                        border-radius:var(--radius-lg);
                                        transition:all .3s ease;
                                        height:100%;
                                        cursor:pointer;
                                    ">
                                <div style="
                                    width:80px;height:80px;margin:0 auto 20px;
                                    background:linear-gradient(135deg, var(--green), #059669);
                                    border-radius:50%;
                                    display:flex;align-items:center;justify-content:center;
                                    color:#fff;font-size:2.5rem;
                                ">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                </div>
                                <div style="font-weight:900;color:var(--text);font-size:1.3rem;margin-bottom:8px;">
                                    معلم
                                </div>
                                <small style="color:var(--muted);font-size:.95rem;line-height:1.5;">
                                    ورود به پنل مدیریت آموزشی
                                </small>
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Footer --}}
            <div class="modal-footer"
                 style="border-top:1px solid rgba(0,0,0,.08); padding:20px 30px; background:rgba(0,0,0,.02);">
                <div style="display:flex;justify-content:space-between;align-items:center;width:100%;flex-wrap:wrap;gap:15px;">
                    <div style="color:var(--muted);font-size:.95rem;">
                        <i class="fas fa-lock ms-2"></i>
                        اطلاعات شما نزد ما امن است
                    </div>
                    <div style="display:flex;gap:15px;">
                        <a href="{{ route('privacy') }}" style="color:var(--sky);text-decoration:none;font-weight:700;font-size:.95rem;">
                            حریم خصوصی
                        </a>
                        <a href="{{ route('terms') }}" style="color:var(--sky);text-decoration:none;font-weight:700;font-size:.95rem;">
                            شرایط استفاده
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
