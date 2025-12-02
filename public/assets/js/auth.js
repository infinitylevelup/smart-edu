let currentStep = 1;
let countdownInterval;

const phoneForm = document.getElementById('phoneForm');
const verifyForm = document.getElementById('verificationForm');
const resendBtn  = document.getElementById('resendCodeBtn');

const csrf = () => document.querySelector('meta[name="csrf-token"]')?.content;
const phoneValue = () => document.getElementById('phone').value.trim();

async function postJSON(url, payload){
  const token = csrf();

  const res = await fetch(url, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      ...(token ? { 'X-CSRF-TOKEN': token } : {}),
    },
    credentials: 'same-origin',
    body: JSON.stringify(payload)
  });

  const text = await res.text();
  let data = {};
  try {
    data = text ? JSON.parse(text) : {};
  } catch (e){
    throw new Error(`پاسخ JSON نبود. Status: ${res.status}`);
  }

  if(!res.ok){
    throw new Error(data.message || `خطای سرور. Status: ${res.status}`);
  }
  return data;
}

function showStep(step){
  currentStep = step;
  document.querySelectorAll('.auth-step').forEach((el, idx) => {
    el.style.display = (idx + 1 === step) ? 'block' : 'none';
  });
}

function startCountdown(){
  let count = 60;
  const el = document.getElementById('countdown');
  resendBtn.disabled = true;

  clearInterval(countdownInterval);
  countdownInterval = setInterval(() => {
    count--;
    el.textContent = `(${count})`;
    if(count <= 0){
      clearInterval(countdownInterval);
      resendBtn.disabled = false;
      el.textContent = '(60)';
    }
  }, 1000);
}

// Step 1: send OTP
phoneForm?.addEventListener('submit', async (e) => {
  e.preventDefault();
  try {
    const phone = phoneValue();
    await postJSON('/auth/send-otp', { phone });

    document.getElementById('phoneNumberDisplay').textContent = '+98' + phone;
    showStep(2);
    startCountdown();
  } catch (err){
    alert(err.message);
    console.error(err);
  }
});

// Step 2: verify OTP
verifyForm?.addEventListener('submit', async (e) => {
  e.preventDefault();
  try {
    const phone = phoneValue();
    const code  = document.getElementById('verificationCode').value.trim();

    const data = await postJSON('/auth/verify-otp', { phone, code });

    if (data.status === 'ok') {

      // ✅ 1) گرفتن CSRF جدید از سشن لاگین شده
      const res2 = await fetch('/auth/csrf', {
        method: 'GET',
        headers: { 'Accept': 'application/json' },
        credentials: 'same-origin'
      });
      const fresh = await res2.json();

      // ✅ 2) آپدیت meta csrf-token
      if (fresh.token){
        const meta = document.querySelector('meta[name="csrf-token"]');
        if(meta) meta.setAttribute('content', fresh.token);
      }

      /**
       * ✅ منطق جدید:
       * اگر need_role = true => مرحله انتخاب نقش
       * اگر redirect دارد => مستقیم برو داشبورد
       */
      if (data.need_role) {
        showStep(3);
      } else if (data.redirect) {
        window.location.href = data.redirect;
      } else {
        // حالت fallback
        window.location.reload();
      }

      return;
    }

    alert(data.message || 'مشکل در تایید کد');
  } catch (err){
    alert(err.message);
    console.error(err);
  }
});

// resend OTP
resendBtn?.addEventListener('click', async () => {
  try {
    const phone = phoneValue();
    await postJSON('/auth/send-otp', { phone });
    startCountdown();
  } catch (err){
    alert(err.message);
    console.error(err);
  }
});

// Step 3: select role (only first time)
window.selectRole = async function(role){
  try {
    const data = await postJSON('/auth/set-role', { role });
    if(data.redirect){
      window.location.href = data.redirect;
      return;
    }
    alert(data.message || 'نقش ذخیره شد');
  } catch (err){
    alert(err.message);
    console.error(err);
  }
};

// reset modal
document.getElementById('authModal')
  ?.addEventListener('hidden.bs.modal', function(){
    showStep(1);
    phoneForm.reset();
    verifyForm.reset();
    clearInterval(countdownInterval);
  });
