/**
 * Smart-Edu Landing Auth (OTP) - FINAL (No setRole endpoint)
 * - Opens Bootstrap modal
 * - Send OTP
 * - Verify OTP (includes intended role)
 * - Updates CSRF token from backend response
 * - Redirects to role dashboard
 */

(function () {
  const log = (...args) => console.log("[auth]", ...args);

  function $(sel, root = document) {
    return root.querySelector(sel);
  }
  function $all(sel, root = document) {
    return Array.from(root.querySelectorAll(sel));
  }

  function getCsrf() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.getAttribute("content") : "";
  }

  function setCsrf(token) {
    const meta = document.querySelector('meta[name="csrf-token"]');
    if (meta && token) meta.setAttribute("content", token);
  }

  async function postJSON(url, data) {
    const res = await fetch(url, {
      method: "POST",
      headers: {
        "Accept": "application/json",
        "Content-Type": "application/json",
        "X-Requested-With": "XMLHttpRequest",
        "X-CSRF-TOKEN": getCsrf(),
      },
      credentials: "same-origin",
      body: JSON.stringify(data),
    });

    let json = null;
    try {
      json = await res.json();
    } catch (e) {
      // maybe HTML error page
    }

    if (!res.ok) {
      const msg =
        (json && (json.message || (json.errors && Object.values(json.errors)[0]?.[0]))) ||
        `HTTP ${res.status}`;
      const err = new Error(msg);
      err.status = res.status;
      err.payload = json;
      throw err;
    }

    return json;
  }

  function show(el) {
    if (!el) return;
    el.classList.remove("d-none");
  }
  function hide(el) {
    if (!el) return;
    el.classList.add("d-none");
  }
  function setText(el, text) {
    if (!el) return;
    el.textContent = text || "";
  }

  function showError(msg) {
    const box = $("#authError");
    const ok = $("#authStatus");
    hide(ok);
    setText(box, msg);
    show(box);
  }

  function showStatus(msg) {
    const box = $("#authStatus");
    const err = $("#authError");
    hide(err);
    setText(box, msg);
    show(box);
  }

  function gotoStep(stepId) {
    const steps = ["phoneStep", "verificationStep", "roleStep"];
    steps.forEach((id) => {
      const el = document.getElementById(id);
      if (!el) return;
      el.style.display = id === stepId ? "" : "none";
    });
  }

  function normalizePhone(input) {
    // expects 10 digits starting with 9 (without 0)
    return String(input || "").trim().replace(/\D/g, "");
  }

  function init() {
    const modalEl = document.getElementById("authModal");
    if (!modalEl) {
      console.warn("[auth] authModal not found in DOM");
      return;
    }

    if (typeof bootstrap === "undefined" || !bootstrap.Modal) {
      console.error("[auth] Bootstrap JS not loaded. Make sure bootstrap.bundle.min.js is included.");
      return;
    }

    const sendUrl = modalEl.dataset.sendUrl;
    const verifyUrl = modalEl.dataset.verifyUrl;

    const authRole = $("#authRole");
    const authRedirect = $("#authRedirect");

    const phoneInput = $("#phone");
    const codeInput = $("#verificationCode");
    const phoneDisplay = $("#phoneNumberDisplay");

    const phoneForm = $("#phoneForm");
    const verificationForm = $("#verificationForm");

    const resendBtn = $("#resendCodeBtn");
    const countdownEl = $("#countdown");

    const modal = new bootstrap.Modal(modalEl, { backdrop: true });

    // Open modal from landing buttons
    $all(".js-open-auth").forEach((btn) => {
      btn.addEventListener("click", (e) => {
        e.preventDefault();

        const role = btn.dataset.role || "";
        const redirect = btn.dataset.redirect || "";

        log("clicked", role, redirect);

        if (authRole) authRole.value = role;
        if (authRedirect) authRedirect.value = redirect;

        // reset ui
        hide($("#authError"));
        hide($("#authStatus"));
        gotoStep("phoneStep");

        // open
        modal.show();
        setTimeout(() => phoneInput && phoneInput.focus(), 250);
      });
    });

    // Send OTP
    phoneForm?.addEventListener("submit", async (e) => {
      e.preventDefault();
      hide($("#authError"));
      hide($("#authStatus"));

      const phone = normalizePhone(phoneInput?.value);
      if (!/^9\d{9}$/.test(phone)) {
        showError("شماره همراه معتبر نیست. مثال: 9123456789");
        return;
      }

      try {
        showStatus("در حال ارسال کد تایید...");
        await postJSON(sendUrl, { phone });

        // show verification step
        setText(phoneDisplay, `+98 ${phone}`);
        gotoStep("verificationStep");
        showStatus("کد تایید ارسال شد.");
        codeInput && (codeInput.value = "");
        setTimeout(() => codeInput && codeInput.focus(), 200);

        // simple resend cooldown
        startResendCooldown(60, resendBtn, countdownEl);
      } catch (err) {
        showError(err.message || "خطا در ارسال کد تایید");
      }
    });

    // Verify OTP (role is REQUIRED now)
    verificationForm?.addEventListener("submit", async (e) => {
      e.preventDefault();
      hide($("#authError"));
      hide($("#authStatus"));

      const phone = normalizePhone(phoneInput?.value);
      const code = String(codeInput?.value || "").trim().replace(/\D/g, "");
      const role = String(authRole?.value || "").trim();

      if (!/^9\d{9}$/.test(phone)) {
        showError("شماره همراه معتبر نیست.");
        return;
      }
      if (!/^\d{6}$/.test(code)) {
        showError("کد تایید باید ۶ رقم باشد.");
        return;
      }
      if (!["student", "teacher"].includes(role)) {
        showError("نقش نامعتبر است. لطفاً از دکمه ورود دانش‌آموز/معلم وارد شوید.");
        return;
      }

      try {
        showStatus("در حال تایید کد...");

        const resp = await postJSON(verifyUrl, { phone, code, role });

        // update csrf token after session regenerate (backend returns it) :contentReference[oaicite:7]{index=7}
        if (resp && resp.csrf) setCsrf(resp.csrf);

        showStatus("ورود موفق. در حال انتقال...");

        const finalRedirect = (resp && resp.redirect) || (authRedirect?.value || "/");
        window.location.href = finalRedirect;
      } catch (err) {
        showError(err.message || "خطا در تایید کد");
      }
    });

    // Resend code
    resendBtn?.addEventListener("click", async () => {
      if (resendBtn.disabled) return;

      hide($("#authError"));
      hide($("#authStatus"));

      const phone = normalizePhone(phoneInput?.value);
      if (!/^9\d{9}$/.test(phone)) {
        showError("شماره همراه معتبر نیست.");
        return;
      }

      try {
        showStatus("در حال ارسال مجدد کد...");
        await postJSON(sendUrl, { phone });
        showStatus("کد مجدداً ارسال شد.");
        startResendCooldown(60, resendBtn, countdownEl);
      } catch (err) {
        showError(err.message || "ارسال مجدد ناموفق بود");
      }
    });
  }

  function startResendCooldown(seconds, btn, countdownEl) {
    if (!btn) return;
    let remaining = seconds;

    btn.disabled = true;
    if (countdownEl) countdownEl.textContent = `(${remaining})`;

    const timer = setInterval(() => {
      remaining -= 1;
      if (countdownEl) countdownEl.textContent = remaining > 0 ? `(${remaining})` : "";
      if (remaining <= 0) {
        clearInterval(timer);
        btn.disabled = false;
      }
    }, 1000);
  }

  document.addEventListener("DOMContentLoaded", init);
})();
