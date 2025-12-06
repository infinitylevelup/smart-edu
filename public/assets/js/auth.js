let currentStep = 1;
let countdownInterval = null;

const phoneForm  = document.getElementById('phoneForm');
const verifyForm = document.getElementById('verificationForm');
const resendBtn  = document.getElementById('resendCodeBtn');

const phoneInput = document.getElementById('phone');
const codeInput  = document.getElementById('verificationCode');
const phoneDisplay = document.getElementById('phoneNumberDisplay');
const modalEl = document.getElementById('authModal');

// URL ها از data-attribute (اگر نبود fallback)
const SEND_URL   = modalEl?.dataset.sendUrl   || '/auth/send-otp';
const VERIFY_URL = modalEl?.dataset.verifyUrl || '/auth/verify-otp';
const ROLE_URL   = modalEl?.dataset.roleUrl   || '/auth/set-role';

// state
let currentPhone = '';
let isSending = false;
let isVerifying = false;
let isResending = false;

const csrf = () => document.querySelector('meta[name="csrf-token"]')?.content || '';

// -------------------- Toast --------------------
function showToast(message, type = 'info') {
  if (!message) return;

  const toast = document.createElement('div');
  toast.style.cssText = `
    position: fixed; bottom: 20px; left: 20px;
    background: ${type === 'error' ? '#dc3545' : type === 'success' ? '#198754' : '#0d6efd'};
    color: white; padding: 14px 18px; border-radius: 12px;
    box-shadow: 0 5px 18px rgba(0,0,0,0.2);
    z-index: 9999; display: flex; gap: 10px; font-weight: 700;
    font-size: 0.95rem; max-width: 90vw; line-height: 1.6;
  `;
  toast.innerHTML = `<span>${message}</span>`;
  document.body.appendChild(toast);

  setTimeout(() => {
    toast.style.opacity = '0';
    toast.style.transform = 'translateY(10px)';
  }, 2500);

  setTimeout(() => toast.remove(), 3000);
}

// -------------------- fetch helper --------------------
async function postJSON(url, payload){
  const res = await fetch(url, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-TOKEN': csrf(),
      'X-Requested-With': 'XMLHttpRequest'
    },
    credentials: 'same-origin',
    body: JSON.stringify(payload)
  });

  const text = await res.text();
  let data = {};
  try {
    data = text ? JSON.parse(text) : {};
  } catch (e){
    throw new Error(`پاسخ سرور معتبر نبود. Status: ${res.status}`);
  }

  if(!res.ok){
    throw new Error(data.message || `خطای سرور. Status: ${res.status}`);
  }
  return data;
}

// -------------------- steps --------------------
function showStep(step){
  currentStep = step;
  const steps = document.querySelectorAll('.auth-step');
  steps.forEach((el, idx) => {
    el.style.display = (idx + 1 === step) ? 'block' : 'none';
  });
}

function startCountdown(){
  let count = 60;
  const el = document.getElementById('countdown');
  resendBtn.disabled = true;
  el.textContent = `(${count})`;

  clearInterval(countdownInterval);
  countdownInterval = setInterval(() => {
    count--;
    el.textContent = `(${count})`;
    if(count <= 0){
      clearInterval(countdownInterval);
      resendBtn.disabled = false;
      el.textContent = '';
    }
  }, 1000);
}

// -------------------- Step 1: send OTP --------------------
phoneForm?.addEventListener('submit', async (e) => {
  e.preventDefault();
  e.stopPropagation();

  if (isSending) return;
  isSending = true;

  const phone = phoneInput.value.trim();

  if (!phone || phone.length !== 10 || !/^9\d{9}$/.test(phone)) {
    showToast('لطفاً شماره همراه معتبر وارد کنید.', 'error');
    isSending = false;
    return;
  }

  const submitBtn = phoneForm.querySelector('button[type="submit"]');
  const originalText = submitBtn.innerHTML;
  submitBtn.disabled = true;
  submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin ms-2"></i> در حال ارسال...';

  try {
    const data = await postJSON(SEND_URL, { phone });

    showToast(data.message || 'کد تایید ارسال شد.', 'success');

    currentPhone = phone;
    phoneDisplay.textContent = `+98 ${phone}`;

    showStep(2);
    startCountdown();

  } catch (err){
    showToast(err.message, 'error');
    console.error(err);

  } finally {
    submitBtn.disabled = false;
    submitBtn.innerHTML = originalText;
    isSending = false;
  }
});


// -------------------- Step 2: verify OTP --------------------
verifyForm?.addEventListener('submit', async (e) => {
  e.preventDefault();
  e.stopPropagation();

  if (isVerifying) return;
  isVerifying = true;

  const code = codeInput.value.trim();

  if (!currentPhone) {
    showToast('ابتدا شماره را وارد کنید.', 'error');
    isVerifying = false;
    return;
  }

  if (!code || code.length !== 6 || !/^\d{6}$/.test(code)) {
    showToast('کد ۶ رقمی معتبر نیست.', 'error');
    isVerifying = false;
    return;
  }

  // قفل فرم برای جلوگیری از submit دوم
  const controls = verifyForm.querySelectorAll('input, button');
  controls.forEach(el => el.disabled = true);

  const submitBtn = verifyForm.querySelector('button[type="submit"]');
  const originalText = submitBtn.innerHTML;
  submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin ms-2"></i> در حال تأیید...';

  try {
    const data = await postJSON(VERIFY_URL, {
      phone: currentPhone,
      code
    });

    if (data.status === 'ok') {
      showToast(data.message || 'ورود موفق.', 'success');
      clearInterval(countdownInterval);

      if (data.need_role) {
        showStep(3);

        // چون مرحله نقش داریم، دوباره آزاد
        isVerifying = false;
        controls.forEach(el => el.disabled = false);
        submitBtn.innerHTML = originalText;
        return;
      }

      if (data.redirect) {
        window.location.href = data.redirect;
        return;
      }

      window.location.reload();
      return;
    }

    showToast(data.message || 'مشکل در تایید کد.', 'error');
    isVerifying = false;
    controls.forEach(el => el.disabled = false);

  } catch (err){
    showToast(err.message, 'error');
    console.error(err);

    isVerifying = false;
    controls.forEach(el => el.disabled = false);

  } finally {
    if (!isVerifying) {
      submitBtn.innerHTML = originalText;
      submitBtn.disabled = false;
    }
  }
});


// -------------------- resend OTP --------------------
resendBtn?.addEventListener('click', async () => {
  if (resendBtn.disabled) return;
  if (isResending) return;
  isResending = true;

  try {
    if (!currentPhone) {
      showToast('شماره همراه مشخص نیست.', 'error');
      isResending = false;
      return;
    }

    const data = await postJSON(SEND_URL, { phone: currentPhone });

    showToast(data.message || 'کد تایید ارسال شد.', 'success');
    startCountdown();

  } catch (err){
    showToast(err.message, 'error');
    console.error(err);

  } finally {
    isResending = false;
  }
});


// -------------------- Step 3: select role --------------------
window.selectRole = async function(role){
  try {
    const data = await postJSON(ROLE_URL, { role });

    showToast(data.message || 'نقش ذخیره شد.', 'success');

    if (data.redirect) {
      window.location.href = data.redirect;
      return;
    }

  } catch (err){
    showToast(err.message, 'error');
    console.error(err);
  }
};


// -------------------- reset modal --------------------
modalEl?.addEventListener('hidden.bs.modal', function(){
  showStep(1);
  phoneForm?.reset();
  verifyForm?.reset();
  clearInterval(countdownInterval);

  currentPhone = '';
  isSending = false;
  isVerifying = false;
  isResending = false;

  resendBtn.disabled = false;
});
