<div class="modal fade" id="authModal" tabindex="-1" aria-hidden="true" data-send-url="{{ route('auth.sendOtp') }}"
    data-verify-url="{{ route('auth.verifyOtp') }}" data-role-url="{{ route('auth.setRole') }}">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg"
            style="
            background: var(--light);
            border-radius: var(--radius-xl) !important;
            border: 3px solid rgba(0, 206, 209, 0.15);
            overflow: hidden;
        ">
            {{-- هدر مودال با گرادیانت --}}
            <div class="modal-header"
                style="
                background: linear-gradient(135deg, rgba(0, 206, 209, 0.1), rgba(70, 130, 180, 0.1));
                border-bottom: 2px solid rgba(0, 206, 209, 0.15);
                padding: 25px 30px;
                position: relative;
                overflow: hidden;
            ">
                <div
                    style="position: absolute; top: -50%; right: -20%; width: 200px; height: 200px;
                    background: radial-gradient(circle, rgba(0, 206, 209, 0.08), transparent 70%); border-radius: 50%;">
                </div>

                <h5 class="modal-title fw-bold" id="authModalTitle"
                    style="
                    font-size: 1.5rem;
                    color: var(--dark);
                    display: flex;
                    align-items: center;
                    gap: 12px;
                    position: relative;
                    z-index: 2;
                ">
                    <span
                        style="
                        background: linear-gradient(120deg, var(--primary) 0%, var(--secondary) 100%);
                        -webkit-background-clip: text;
                        -webkit-text-fill-color: transparent;
                        background-clip: text;
                    ">
                        ورود / ثبت‌نام
                    </span>
                    🔐
                </h5>
                <button type="button" class="btn-close ms-0" data-bs-dismiss="modal"
                    style="
                    position: relative;
                    z-index: 2;
                    font-size: 1.2rem;
                    opacity: 0.7;
                    transition: all 0.3s;
                "></button>
            </div>

            {{-- بدنه مودال --}}
            <div class="modal-body" style="padding: 40px 30px;">
                {{-- Step 1: Phone --}}
                <div id="phoneStep" class="auth-step">
                    <div class="text-center mb-4">
                        <div
                            style="
                            width: 80px;
                            height: 80px;
                            margin: 0 auto 20px;
                            background: var(--primary-light);
                            border-radius: 50%;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            font-size: 2rem;
                            color: var(--primary);
                            border: 3px solid var(--primary);
                        ">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h4
                            style="
                            font-weight: 900;
                            color: var(--dark);
                            margin-bottom: 10px;
                            font-size: 1.3rem;
                        ">
                            ورود با شماره همراه
                        </h4>
                        <p
                            style="color: var(--gray); font-size: 1.05rem; line-height: 1.6; max-width: 400px; margin: 0 auto;">
                            برای ورود یا ثبت‌نام، شماره همراه خود را وارد کنید.
                        </p>
                    </div>

                    <form id="phoneForm">
                        <div class="mb-4" style="position: relative;">
                            <label for="phone" class="form-label"
                                style="
                                color: var(--dark);
                                font-weight: 900;
                                font-size: 1rem;
                                margin-bottom: 12px;
                                display: flex;
                                align-items: center;
                                gap: 10px;
                            ">
                                <i class="fas fa-phone"
                                    style="
                                    color: var(--primary);
                                    background: var(--primary-light);
                                    width: 36px;
                                    height: 36px;
                                    border-radius: 10px;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    font-size: 1.1rem;
                                "></i>
                                شماره همراه
                            </label>
                            <div class="input-group" dir="ltr"
                                style="
                                border-radius: var(--radius-md);
                                overflow: hidden;
                                border: 2px solid var(--light-gray);
                                transition: all 0.3s;
                            ">
                                <span class="input-group-text"
                                    style="
                                    background: var(--primary);
                                    color: white;
                                    border: none;
                                    font-weight: 900;
                                    padding: 16px 20px;
                                    font-size: 1.05rem;
                                ">
                                    +98
                                </span>
                                <input type="tel" class="form-control text-start" id="phone"
                                    placeholder="9123456789" dir="ltr" required maxlength="10"
                                    style="
                                    padding: 16px 20px;
                                    border: none;
                                    background: var(--light);
                                    color: var(--dark);
                                    font-weight: 700;
                                    font-size: 1.05rem;
                                    transition: all 0.3s;
                                    direction: ltr !important;
                                    text-align: left !important;
                                    unicode-bidi: plaintext;
                                    ">
                            </div>
                            <div class="form-text" style="color: var(--gray); margin-top: 8px; font-size: 0.9rem;">
                                شماره را بدون صفر وارد کنید. کد تأیید به این شماره ارسال خواهد شد.
                            </div>
                        </div>

                        <button type="submit" class="btn w-100 py-3"
                            style="
                            background: var(--primary-gradient);
                            color: white;
                            border: none;
                            border-radius: var(--radius-lg);
                            font-weight: 900;
                            font-size: 1.1rem;
                            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                            box-shadow: 0 8px 20px rgba(0, 206, 209, 0.3);
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            gap: 10px;
                            min-height: 55px;
                        ">
                            <i class="fas fa-sms"></i>
                            دریافت کد تایید
                        </button>
                    </form>
                </div>

                {{-- Step 2: Verify --}}
                <div id="verificationStep" class="auth-step" style="display:none;">
                    <div class="text-center mb-4">
                        <div
                            style="
                            width: 80px;
                            height: 80px;
                            margin: 0 auto 20px;
                            background: var(--success-light);
                            border-radius: 50%;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            font-size: 2rem;
                            color: var(--success);
                            border: 3px solid var(--success);
                        ">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h4
                            style="
                            font-weight: 900;
                            color: var(--dark);
                            margin-bottom: 10px;
                            font-size: 1.3rem;
                        ">
                            تأیید شماره همراه
                        </h4>
                        <p class="mb-1" style="color: var(--gray); font-size: 1.05rem;">کد تایید به شماره</p>

                        {{-- ✅ FIXED --}}
                        <div id="phoneNumberDisplay" class="fw-bold" dir="ltr"
                            style="direction:ltr; text-align:center; color: var(--primary);
                            font-size: 1.2rem; background: var(--primary-light); padding: 8px 20px;
                            border-radius: var(--radius-md); display: inline-block; margin: 10px 0;">
                        </div>
                    </div>

                    <form id="verificationForm">
                        <div class="mb-4">
                            <label for="verificationCode" class="form-label"
                                style="
                                color: var(--dark);
                                font-weight: 900;
                                font-size: 1rem;
                                margin-bottom: 12px;
                                display: flex;
                                align-items: center;
                                gap: 10px;
                            ">
                                <i class="fas fa-key"
                                    style="
                                    color: var(--primary);
                                    background: var(--primary-light);
                                    width: 36px;
                                    height: 36px;
                                    border-radius: 10px;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    font-size: 1.1rem;
                                "></i>
                                کد تأیید ۶ رقمی
                            </label>
                            <div style="position: relative;">
                                <input type="text" class="form-control text-center verification-code"
                                    id="verificationCode" placeholder="______" maxlength="6" required
                                    style="
                                    padding: 18px;
                                    font-size: 1.8rem;
                                    letter-spacing: 15px;
                                    font-weight: 900;
                                    border: 3px solid var(--light-gray);
                                    border-radius: var(--radius-md);
                                    background: var(--light);
                                    color: var(--dark);
                                    text-align: center;
                                    transition: all 0.3s;
                                ">
                                <div
                                    style="
                                    position: absolute;
                                    left: 50%;
                                    transform: translateX(-50%);
                                    bottom: -25px;
                                    color: var(--gray);
                                    font-size: 0.9rem;
                                    font-weight: 700;
                                ">
                                    کد ۶ رقمی را وارد کنید
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn w-100 py-3 mb-3"
                            style="
                            background: var(--success);
                            color: white;
                            border: none;
                            border-radius: var(--radius-lg);
                            font-weight: 900;
                            font-size: 1.1rem;
                            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                            box-shadow: 0 8px 20px rgba(50, 205, 50, 0.3);
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            gap: 10px;
                            min-height: 55px;
                        ">
                            <i class="fas fa-check-circle"></i>
                            تأیید و ادامه
                        </button>

                        <button type="button" class="btn w-100 py-3" id="resendCodeBtn"
                            style="
                            background: transparent;
                            color: var(--gray);
                            border: 2px solid var(--light-gray);
                            border-radius: var(--radius-lg);
                            font-weight: 900;
                            font-size: 1rem;
                            transition: all 0.3s;
                            min-height: 55px;
                        ">
                            <i class="fas fa-redo ms-2"></i>
                            ارسال مجدد کد
                            <span id="countdown" style="color: var(--primary); margin-right: 5px;">(60)</span>
                        </button>
                    </form>
                </div>

                {{-- Step 3: Role --}}
                <div id="roleStep" class="auth-step" style="display:none;">
                    <div class="text-center mb-4">
                        <div
                            style="
                            width: 80px;
                            height: 80px;
                            margin: 0 auto 20px;
                            background: var(--accent-light);
                            border-radius: 50%;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            font-size: 2rem;
                            color: var(--accent);
                            border: 3px solid var(--accent);
                        ">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <h4
                            style="
                            font-weight: 900;
                            color: var(--dark);
                            margin-bottom: 10px;
                            font-size: 1.3rem;
                        ">
                            انتخاب نقش
                        </h4>
                        <p
                            style="color: var(--gray); font-size: 1.05rem; line-height: 1.6; max-width: 500px; margin: 0 auto;">
                            برای ورود به پنل اختصاصی، نقش خود را انتخاب کنید.
                        </p>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <button type="button" class="btn w-100 h-100 py-4 role-btn"
                                onclick="selectRole('student')"
                                style="
                                border: 3px solid var(--light-gray);
                                background: var(--light);
                                border-radius: var(--radius-lg);
                                transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                                height: 100%;
                                cursor: pointer;
                            ">
                                <div
                                    style="
                                    width: 80px;
                                    height: 80px;
                                    margin: 0 auto 20px;
                                    background: linear-gradient(135deg, var(--elementary), #357ABD);
                                    border-radius: 50%;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    color: white;
                                    font-size: 2.5rem;
                                ">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                                <div
                                    style="font-weight: 900; color: var(--dark); font-size: 1.3rem; margin-bottom: 8px;">
                                    دانش‌آموز
                                </div>
                                <small style="color: var(--gray); font-size: 0.95rem; line-height: 1.5;">
                                    ورود سریع به پنل دانش‌آموزی
                                </small>
                            </button>
                        </div>

                        <div class="col-md-6">
                            <button type="button" class="btn w-100 h-100 py-4 role-btn"
                                onclick="selectRole('teacher')"
                                style="
                                border: 3px solid var(--light-gray);
                                background: var(--light);
                                border-radius: var(--radius-lg);
                                transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                                height: 100%;
                                cursor: pointer;
                            ">
                                <div
                                    style="
                                    width: 80px;
                                    height: 80px;
                                    margin: 0 auto 20px;
                                    background: linear-gradient(135deg, var(--middle-school), #28a745);
                                    border-radius: 50%;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    color: white;
                                    font-size: 2.5rem;
                                ">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                </div>
                                <div
                                    style="font-weight: 900; color: var(--dark); font-size: 1.3rem; margin-bottom: 8px;">
                                    معلم
                                </div>
                                <small style="color: var(--gray); font-size: 0.95rem; line-height: 1.5;">
                                    ورود به پنل مدیریت آموزشی
                                </small>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- فوتر مودال --}}
            <div class="modal-footer"
                style="
                border-top: 2px solid var(--light-gray);
                padding: 20px 30px;
                background: var(--light-gray);
            ">
                <div
                    style="
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    width: 100%;
                    flex-wrap: wrap;
                    gap: 15px;
                ">
                    <div style="color: var(--gray); font-size: 0.95rem;">
                        <i class="fas fa-lock ms-2"></i>
                        اطلاعات شما نزد ما امن است
                    </div>
                    <div style="display: flex; gap: 15px;">
                        <a href="{{ route('privacy') }}"
                            style="color: var(--primary); text-decoration: none; font-weight: 700; font-size: 0.95rem;">
                            حریم خصوصی
                        </a>
                        <a href="{{ route('terms') }}"
                            style="color: var(--primary); text-decoration: none; font-weight: 700; font-size: 0.95rem;">
                            شرایط استفاده
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    :root {
        --primary: #00CED1;
        --primary-light: rgba(0, 206, 209, 0.1);
        --primary-gradient: linear-gradient(135deg, #00CED1, #20B2AA);
        --secondary: #4682B4;
        --light: #ffffff;
        --dark: #2F4F4F;
        --gray: #708090;
        --light-gray: #F0F8FF;
        --success: #32CD32;
        --success-light: rgba(50, 205, 50, 0.1);
        --elementary: #4A90E2;
        --middle-school: #32CD32;
        --radius-xl: 24px;
        --radius-lg: 20px;
        --radius-md: 16px;
        --shadow-lg: 0 12px 30px rgba(0, 0, 0, 0.16);
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modal.fade .modal-content {
        animation: fadeIn 0.4s ease-out;
    }

    .modal-content {
        border: none !important;
        box-shadow: var(--shadow-lg) !important;
    }

    .verification-code {
        font-family: 'Courier New', monospace !important;
        letter-spacing: 10px !important;
    }
</style>
