/* =========================================================
   Student SPA — Final (Phases 1 → 10)
   - Blade = Shell
   - JS = Router + State + UX + Sync
   - Backend = Source of Truth (GET classes)
   ========================================================= */

(() => {
  // -------------------- BOOT --------------------
  const boot = window.__STUDENT_APP__ || {};

  const cfg = {
    joinUrl: boot.joinUrl || null,
    classesUrl: boot.classesUrl || null,

    // Storage keys
    kLastClassId: "student:lastClassId",
    kLastRoute: "student:lastRoute",
    kJoinQueue: "student:joinQueue",
    kEtag: "student:classesETag",
    kSyncMeta: "student:syncMeta",

    // Phase 10 tuning
    syncTtlMs: 120_000,       // 2 min
    syncCooldownMs: 25_000,   // prevent sync storms
    retryBaseMs: 1_000,
    retryMaxMs: 30_000,

    // Scroll persist
    scrollThrottleMs: 150,
  };

  const state = {
    user: boot.user || { name: "دانش‌آموز" },
    classes: Array.isArray(boot.classes) ? boot.classes : [],
    currentClassId: boot.currentClassId || null,
    flash: boot.flash || null,

    // UX internal
    _navResetScroll: true,
  };

  // -------------------- DOM --------------------
  const $ = (sel) => document.querySelector(sel);

  const dom = {
    app: () => $("#app"),
    title: () => $("#title"),
    subtitle: () => $("#subtitle"),
    crumbs: () => $("#crumbs"),
    backBtn: () => $("#backBtn"),
    toast: () => $("#toast"),
    view: () => $("#view"),
  };

  // -------------------- UTIL --------------------
  function escapeHtml(str) {
    return String(str)
      .replaceAll("&", "&amp;")
      .replaceAll("<", "&lt;")
      .replaceAll(">", "&gt;")
      .replaceAll('"', "&quot;")
      .replaceAll("'", "&#039;");
  }

  function safeText(v, fallback = "—") {
    const s = (v ?? "").toString().trim();
    return s ? s : fallback;
  }

  function showToast(message, ms = 1800) {
    const el = dom.toast();
    if (!el) return;

    el.textContent = message;
    el.classList.remove("hidden", "is-hide");

    if (el._t) clearTimeout(el._t);
    if (el._t2) clearTimeout(el._t2);

    el._t = setTimeout(() => {
      el.classList.add("is-hide");
      el._t2 = setTimeout(() => el.classList.add("hidden"), 180);
    }, ms);
  }

  // Header: Title ثابت برای کاهش حس پرش
  function setHeader({ title, subtitle, showBack }) {
    const t = dom.title();
    const s = dom.subtitle();
    const b = dom.backBtn();

    if (t) t.textContent = "یادگیری";
    if (s) s.textContent = title || subtitle || "";
    if (b) b.classList.toggle("hidden", !showBack);
  }

  function currentClass() {
    return state.classes.find((c) => c.id === state.currentClassId) || null;
  }

  function normalizeClassShape(raw) {
    // backend canonical: {id,title,teacher,progress,...}
    // but tolerate older shapes
    return {
      id: raw?.id,
      title: raw?.title ?? raw?.name ?? "کلاس",
      teacher: raw?.teacher ?? raw?.teacher_name ?? raw?.teacher?.name ?? "—",
      progress: Number.isFinite(Number(raw?.progress)) ? Number(raw.progress) : 0,
      students_count: Number.isFinite(Number(raw?.students_count)) ? Number(raw.students_count) : undefined,
      exams_count: Number.isFinite(Number(raw?.exams_count)) ? Number(raw.exams_count) : undefined,
      updated_at: raw?.updated_at ?? undefined,
    };
  }

  function validateCurrentClassId() {
    if (!state.currentClassId) return;
    const exists = state.classes.some((c) => c.id === state.currentClassId);
    if (!exists) {
      state.currentClassId = null;
      try { localStorage.removeItem(cfg.kLastClassId); } catch {}
    }
  }

  // -------------------- STORAGE --------------------
  function lsGet(key, fallback = null) {
    try {
      const v = localStorage.getItem(key);
      return v === null ? fallback : v;
    } catch {
      return fallback;
    }
  }

  function lsSet(key, value) {
    try { localStorage.setItem(key, value); } catch {}
  }

  function lsGetJson(key, fallback) {
    try {
      const raw = localStorage.getItem(key);
      if (!raw) return fallback;
      return JSON.parse(raw);
    } catch {
      return fallback;
    }
  }

  function lsSetJson(key, value) {
    try { localStorage.setItem(key, JSON.stringify(value)); } catch {}
  }

  // -------------------- HASH ROUTER --------------------
  const routes = {
    "/": Dashboard,
    "/join": Join,
    "/class": ClassHub,
    "/learn": Learn,
    "/exams": Exams,
    "/report": Report,
    "/more": More,
  };

  function getPath() {
    const h = location.hash || "#/";
    const p = h.replace(/^#/, "");
    return p.startsWith("/") ? p : "/" + p;
  }

  function navigate(path, opts = {}) {
    state._navResetScroll = opts.resetScroll ?? true;
    location.hash = "#" + path;
  }

  function guardPath(path) {
    // Unknown route => /
    if (!routes[path]) return "/";

    // Do not restore or enter /join automatically (UX guard)
    if (path === "/join" && !state._allowJoinRouteRestore) return "/";

    const needsClass = ["/class", "/learn", "/exams", "/report"].includes(path);
    if (needsClass && !currentClass()) return "/";

    return path;
  }

  window.addEventListener("hashchange", () => {
    softRender({ resetScroll: state._navResetScroll });
    state._navResetScroll = true;
  });

  function bindBackButton() {
    const b = dom.backBtn();
    if (!b) return;
    b.onclick = () => history.back();
  }

  // -------------------- BREADCRUMB --------------------
  function getCrumbs(path) {
    const c = currentClass();

    if (path === "/") return ["خانه"];
    if (path === "/join") return ["خانه", "افزودن کلاس"];
    if (path === "/more") return ["خانه", "بیشتر…"];

    const className = c?.title || "کلاس";
    if (path === "/class") return ["خانه", className];
    if (path === "/learn") return ["خانه", className, "یادگیری"];
    if (path === "/exams") return ["خانه", className, "آزمون‌ها"];
    if (path === "/report") return ["خانه", className, "گزارش"];

    return ["خانه"];
  }

  function renderCrumbsSoft() {
    const crumbsEl = dom.crumbs();
    if (!crumbsEl) return;

    const path = getPath();
    const parts = getCrumbs(path);

    crumbsEl.classList.add("is-changing");

    setTimeout(() => {
      crumbsEl.innerHTML = parts
        .map((p, idx) => {
          const isLast = idx === parts.length - 1;
          const sep = idx < parts.length - 1 ? `<span class="sep">/</span>` : "";

          if (idx === 0 && p === "خانه" && path !== "/") {
            return `<a href="#/" data-nav="home">${escapeHtml(p)}</a>${sep}`;
          }

          const label = isLast
            ? `<span class="here">${escapeHtml(p)}</span>`
            : `<span>${escapeHtml(p)}</span>`;

          return label + sep;
        })
        .join("");

      const homeLink = crumbsEl.querySelector('a[data-nav="home"]');
      if (homeLink) {
        homeLink.addEventListener("click", (e) => {
          e.preventDefault();
          navigate("/", { resetScroll: true });
        });
      }

      crumbsEl.classList.remove("is-changing");
    }, 120);
  }

  // -------------------- PERSIST: CLASS + ROUTE + SCROLL --------------------
  function persistLastClassId() {
    if (!state.currentClassId) return;
    lsSet(cfg.kLastClassId, String(state.currentClassId));
  }

  function restoreLastClassId() {
    const id = lsGet(cfg.kLastClassId, null);
    if (!id) return;
    if (state.classes.some((c) => String(c.id) === String(id))) {
      state.currentClassId = id;
    }
  }

  function persistLastRoute(path) {
    // UX guard: do not persist /join
    if (path === "/join") return;
    lsSet(cfg.kLastRoute, path);
  }

  function restoreLastRoute() {
    const saved = lsGet(cfg.kLastRoute, "/");
    if (!saved || saved === "/join") return "/";
    return saved;
  }

  function scrollKey(path) {
    return `student:scroll:${path}`;
  }

  function restoreScroll(path) {
    const raw = lsGet(scrollKey(path), null);
    const y = raw ? Number(raw) : 0;
    if (!Number.isFinite(y)) return;

    requestAnimationFrame(() => {
      window.scrollTo({ top: y, behavior: "auto" });
    });
  }

  function throttle(fn, ms) {
    let last = 0;
    let timer = null;
    return (...args) => {
      const now = Date.now();
      const remain = ms - (now - last);

      if (remain <= 0) {
        last = now;
        fn(...args);
        return;
      }

      if (timer) return;
      timer = setTimeout(() => {
        timer = null;
        last = Date.now();
        fn(...args);
      }, remain);
    };
  }

  const saveScrollThrottled = throttle(() => {
    const p = getPath();
    lsSet(scrollKey(p), String(window.scrollY || 0));
  }, cfg.scrollThrottleMs);

  window.addEventListener("scroll", saveScrollThrottled, { passive: true });

  // -------------------- RENDER --------------------
  function softRender({ resetScroll }) {
    const viewEl = dom.view();
    if (!viewEl) return;

    viewEl.classList.add("is-leaving");

    setTimeout(() => {
      renderCore();

      viewEl.classList.remove("is-leaving");
      viewEl.classList.add("is-entering");

      const path = getPath();
      persistLastRoute(path);

      if (resetScroll) {
        window.scrollTo({ top: 0, behavior: "smooth" });
      } else {
        restoreScroll(path);
      }

      if (state.flash) {
        showToast(state.flash);
        state.flash = null;
      }

      requestAnimationFrame(() => {
        viewEl.classList.remove("is-entering");
      });
    }, 140);
  }

  function renderCore() {
    const raw = getPath();
    const path = guardPath(raw);

    // If guard changed path, normalize hash (without extra scroll reset)
    if (path !== raw) {
      navigate(path, { resetScroll: false });
      return;
    }

    const viewFn = routes[path] || Dashboard;
    const viewEl = dom.view();
    if (!viewEl) return;

    viewEl.innerHTML = "";
    viewFn();

    renderCrumbsSoft();
  }

  // -------------------- NETWORK HELPERS --------------------
  function csrfToken() {
    const el = document.querySelector('meta[name="csrf-token"]');
    return el ? el.getAttribute("content") : "";
  }

  function normalizeCode(raw) {
    return String(raw || "").replace(/\s+/g, "").toUpperCase();
  }

  // -------------------- PHASE 10: SMART SYNC (ETag/304) --------------------
  let inFlightSync = null;

  function readSyncMeta() {
    return lsGetJson(cfg.kSyncMeta, { lastSyncAt: 0, lastReason: "" });
  }

  function writeSyncMeta(meta) {
    lsSetJson(cfg.kSyncMeta, meta);
  }

  async function smartSyncClasses(reason, { force = false } = {}) {
    if (!cfg.classesUrl) return { ok: false, skipped: true, why: "classesUrl-not-set" };

    if (inFlightSync) return inFlightSync;

    const now = Date.now();
    const meta = readSyncMeta();
    const elapsed = now - (meta.lastSyncAt || 0);

    const ttlExpired = elapsed > cfg.syncTtlMs;
    if (!force && !ttlExpired) return { ok: true, skipped: true, why: "ttl" };

    // prevent storms (even when TTL expired but user triggers repeatedly)
    if (!force && elapsed < cfg.syncCooldownMs) return { ok: true, skipped: true, why: "cooldown" };

    const etag = lsGet(cfg.kEtag, null);

    inFlightSync = (async () => {
      try {
        const headers = {
          "Accept": "application/json",
          "X-Requested-With": "XMLHttpRequest",
        };
        if (etag) headers["If-None-Match"] = etag;

        const r = await fetch(cfg.classesUrl, {
          method: "GET",
          credentials: "same-origin",
          headers,
        });

        if (r.status === 304) {
          writeSyncMeta({ lastSyncAt: Date.now(), lastReason: reason });
          return { ok: true, notModified: true };
        }

        const newEtag = r.headers.get("ETag");
        if (newEtag) lsSet(cfg.kEtag, newEtag);

        if (!r.ok) {
          return { ok: false, status: r.status };
        }

        const data = await r.json();
        const list = Array.isArray(data) ? data : (data?.classes || []);
        if (!Array.isArray(list)) {
          return { ok: false, badShape: true };
        }

        state.classes = list.map(normalizeClassShape);

        // validate selection after sync
        validateCurrentClassId();

        writeSyncMeta({ lastSyncAt: Date.now(), lastReason: reason });
        return { ok: true, updated: true };
      } catch {
        return { ok: false, status: 0 };
      } finally {
        inFlightSync = null;
      }
    })();

    return inFlightSync;
  }

  // -------------------- PHASE 8: OFFLINE JOIN QUEUE --------------------
  function cryptoRandomId() {
    try {
      return crypto.getRandomValues(new Uint32Array(2)).join("-");
    } catch {
      return String(Date.now()) + "-" + Math.random().toString(16).slice(2);
    }
  }

  function getJoinQueue() {
    return lsGetJson(cfg.kJoinQueue, []);
  }

  function setJoinQueue(items) {
    lsSetJson(cfg.kJoinQueue, items);
  }

  function enqueueJoin(code) {
    const q = getJoinQueue();
    q.push({
      id: cryptoRandomId(),
      code,
      tries: 0,
      nextAt: Date.now(),
      createdAt: Date.now(),
    });
    setJoinQueue(q);
  }

  function dequeueJoin(id) {
    const q = getJoinQueue().filter((x) => x.id !== id);
    setJoinQueue(q);
  }

  function updateJoin(item) {
    const q = getJoinQueue().map((x) => (x.id === item.id ? item : x));
    setJoinQueue(q);
  }

  function backoffMs(tries) {
    const base = cfg.retryBaseMs * Math.pow(2, Math.min(tries, 6));
    const jitter = Math.floor(Math.random() * 250);
    return Math.min(base + jitter, cfg.retryMaxMs);
  }

  async function joinRequest(code, { fromQueue } = { fromQueue: false }) {
    if (!cfg.joinUrl) {
      return { ok: false, status: 0, message: "joinUrl تنظیم نشده" };
    }

    try {
      const r = await fetch(cfg.joinUrl, {
        method: "POST",
        credentials: "same-origin",
        headers: {
          "Accept": "application/json",
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": csrfToken(),
          "X-Requested-With": "XMLHttpRequest",
        },
        body: JSON.stringify({ join_code: code }), // ✅ backend expects join_code
      });

      // Might return JSON in future; tolerate empty
      let data = null;
      try { data = await r.json(); } catch {}

      if (r.ok) return { ok: true, status: r.status, data };
      return {
        ok: false,
        status: r.status,
        data,
        message: data?.message || (fromQueue ? null : "مشکلی پیش آمد"),
      };
    } catch {
      return { ok: false, status: 0, message: null };
    }
  }

  async function processJoinQueue(reason = "queue") {
    if (!navigator.onLine) return;

    const q = getJoinQueue();
    if (!q.length) return;

    for (const item of q) {
      if (Date.now() < item.nextAt) continue;

      const res = await joinRequest(item.code, { fromQueue: true });

      if (res.ok) {
        dequeueJoin(item.id);

        // After join success => canonical sync
        await smartSyncClasses("join-queue-success", { force: true });
        continue;
      }

      // Your current backend join is redirect-based; JSON status codes might not be implemented yet.
      // Still: if backend later supports 422/409, handle them:
      if (res.status === 422) {
        dequeueJoin(item.id);
        showToast("کد کلاس معتبر نیست 🙂");
        continue;
      }
      if (res.status === 409) {
        dequeueJoin(item.id);
        showToast("قبلاً عضو این کلاس شدی ✅");
        await smartSyncClasses("join-queue-409", { force: true });
        continue;
      }

      // Network/5xx: backoff
      item.tries += 1;
      item.nextAt = Date.now() + backoffMs(item.tries);
      updateJoin(item);
    }
  }

  window.addEventListener("online", () => processJoinQueue("online"));
  document.addEventListener("visibilitychange", () => {
    if (document.visibilityState === "visible") processJoinQueue("visible");
  });

  // -------------------- VIEWS --------------------
  function Dashboard() {
    setHeader({ title: "داشبورد", subtitle: "خانه‌ی یادگیری", showBack: false });

    const v = dom.view();
    if (!v) return;

    v.innerHTML = `
      <div class="card">
        <div class="h3">سلام ${escapeHtml(safeText(state.user?.name, "دوست من"))} 👋</div>
        <div class="p">برای شروع، یک کلاس انتخاب کن یا کد کلاس رو وارد کن.</div>
        <div style="height:10px"></div>
        <button class="btn btn-primary" id="goJoin">افزودن کلاس</button>
        <div class="small">کد را از معلمت بگیر</div>
      </div>
    `;

    const goJoin = $("#goJoin");
    if (goJoin) goJoin.onclick = () => navigate("/join", { resetScroll: true });

    if (!state.classes.length) {
      const empty = document.createElement("div");
      empty.className = "card";
      empty.innerHTML = `
        <div class="h3">هنوز کلاسی نداری</div>
        <div class="p">کد کلاس رو وارد کن تا به کلاس اضافه بشی.</div>
      `;
      v.appendChild(empty);
    } else {
      const list = document.createElement("div");
      list.className = "card";
      list.innerHTML = `<div class="h3">کلاس‌های من</div>`;

      state.classes.map(normalizeClassShape).forEach((c) => {
        const row = document.createElement("div");
        row.className = "card list-card";
        row.style.margin = "10px 0 0 0";
        row.innerHTML = `
          <div>
            <div style="font-weight:900">${escapeHtml(safeText(c.title, "کلاس"))}</div>
            <div class="p" style="margin-top:2px">${escapeHtml(safeText(c.teacher, "—"))}</div>
          </div>
          <div class="badge">${escapeHtml(String(c.progress ?? 0))}%</div>
        `;
        row.onclick = () => {
          state.currentClassId = c.id;
          persistLastClassId();
          navigate("/class", { resetScroll: true });
        };
        list.appendChild(row);
      });

      v.appendChild(list);
    }

    const more = document.createElement("div");
    more.className = "card";
    more.innerHTML = `
      <div class="h3">بیشتر…</div>
      <div class="grid2" style="margin-top:10px">
        <button class="btn btn-ghost" id="goMore">حساب کاربری</button>
        <button class="btn btn-ghost" id="help">راهنما</button>
      </div>
    `;
    v.appendChild(more);

    const goMore = $("#goMore");
    const help = $("#help");
    if (goMore) goMore.onclick = () => navigate("/more", { resetScroll: true });
    if (help) help.onclick = () => showToast("راهنما: از داشبورد یک کلاس را انتخاب کن.");

    // Phase 10: Light sync by TTL (silent)
    smartSyncClasses("dashboard-ttl", { force: false }).then((res) => {
      if (res?.updated) softRender({ resetScroll: false });
    });
  }

  function Join() {
    setHeader({ title: "افزودن کلاس", subtitle: "کد کلاس را وارد کن", showBack: true });

    const v = dom.view();
    if (!v) return;

    v.innerHTML = `
      <div class="card">
        <div class="h3">کد کلاس</div>
        <div class="p">کدی که معلمت داده رو وارد کن.</div>

        <div style="height:10px"></div>

        <input id="code" class="input"
          inputmode="latin"
          autocomplete="off"
          autocapitalize="characters"
          placeholder="مثلاً: AB12" />

        <div style="height:12px"></div>

        <button class="btn btn-primary" id="joinBtn" disabled style="opacity:.6">
          ورود به کلاس
        </button>

        <div class="small">بعد از ورود، به داشبورد برمی‌گردی</div>
      </div>
    `;

    const input = $("#code");
    const btn = $("#joinBtn");

    function updateBtn() {
      const code = normalizeCode(input?.value || "");
      const ok = code.length >= 3;
      btn.disabled = !ok;
      btn.style.opacity = ok ? "1" : ".6";
    }

    input.addEventListener("input", () => {
      const normalized = normalizeCode(input.value);
      if (input.value !== normalized) input.value = normalized;
      updateBtn();
    });

    input.addEventListener("blur", () => {
      input.value = normalizeCode(input.value);
      updateBtn();
    });

    btn.onclick = async () => {
      const code = normalizeCode(input.value);
      if (code.length < 3) {
        showToast("کد رو درست وارد کن 🙂");
        return;
      }

      // Offline-safe
      if (!navigator.onLine) {
        enqueueJoin(code);
        state.flash = "آفلاین هستی؛ ذخیره شد و بعداً خودکار انجام میشه ✅";
        navigate("/", { resetScroll: true });
        return;
      }

      btn.disabled = true;
      btn.style.opacity = ".6";

      const res = await joinRequest(code, { fromQueue: false });

      if (res.ok) {
        // Since backend join might still be redirect-only, do canonical sync anyway
        await smartSyncClasses("join-success", { force: true });

        state.flash = res.data?.message || "کلاس اضافه شد ✅";
        navigate("/", { resetScroll: true });
        return;
      }

      // If backend later supports status codes, show meaningful messages:
      if (res.status === 422) {
        showToast(res.message || "کد کلاس معتبر نیست 🙂");
      } else if (res.status === 409) {
        showToast(res.message || "قبلاً عضو این کلاس شدی ✅");
        await smartSyncClasses("join-409", { force: true });
        navigate("/", { resetScroll: true });
      } else if (res.status === 0) {
        // Network fail => queue it
        enqueueJoin(code);
        state.flash = "ارتباط مشکل داشت؛ ذخیره شد و بعداً خودکار انجام میشه ✅";
        navigate("/", { resetScroll: true });
      } else {
        showToast(res.message || "مشکلی پیش آمد. دوباره تلاش کن.");
      }

      btn.disabled = false;
      btn.style.opacity = "1";
    };

    updateBtn();
  }

  function ClassHub() {
    const c = currentClass();
    if (!c) {
      navigate("/", { resetScroll: true });
      return;
    }

    setHeader({ title: safeText(c.title, "کلاس"), subtitle: safeText(c.teacher, ""), showBack: true });

    dom.view().innerHTML = `
      <div class="card">
        <div class="h3">الان چی کار کنیم؟</div>
        <div class="p">یکی را انتخاب کن:</div>
        <div style="height:10px"></div>
        <button class="btn btn-primary" id="goLearn">شروع یادگیری</button>
        <div style="height:10px"></div>
        <button class="btn btn-soft" id="goExams">آزمون‌ها</button>
      </div>

      <div class="card">
        <div class="h3">پیشرفت</div>
        <div class="p">پیشرفت کلی: ${escapeHtml(String(c.progress ?? 0))}%</div>
        <div style="height:10px"></div>
        <button class="btn btn-ghost" id="goReport">گزارش کامل</button>
      </div>
    `;

    $("#goLearn").onclick = () => navigate("/learn", { resetScroll: true });
    $("#goExams").onclick = () => navigate("/exams", { resetScroll: true });
    $("#goReport").onclick = () => navigate("/report", { resetScroll: true });
  }

  function Learn() {
    const c = currentClass();
    if (!c) {
      navigate("/", { resetScroll: true });
      return;
    }

    setHeader({ title: "مسیر آموزشی", subtitle: safeText(c.title, ""), showBack: true });

    dom.view().innerHTML = `
      <div class="card">
        <div class="h3">مسیر آموزشی</div>
        <div class="p">اینجا بعداً لیست مرحله‌ها/درس‌ها میاد.</div>
        <div style="height:10px"></div>
        <button class="btn btn-primary" id="demo1">شروع مرحله ۱</button>
      </div>
    `;
    $("#demo1").onclick = () => showToast("دمو: شروع مرحله ۱");
  }

  function Exams() {
    const c = currentClass();
    if (!c) {
      navigate("/", { resetScroll: true });
      return;
    }

    setHeader({ title: "آزمون‌ها", subtitle: safeText(c.title, ""), showBack: true });

    dom.view().innerHTML = `
      <div class="card">
        <div class="h3">آزمون‌ها</div>
        <div class="p">اینجا بعداً لیست آزمون‌ها میاد.</div>
        <div style="height:10px"></div>
        <button class="btn btn-soft" id="demo2">آزمون کوتاه</button>
      </div>
    `;
    $("#demo2").onclick = () => showToast("دمو: آزمون کوتاه");
  }

  function Report() {
    const c = currentClass();
    if (!c) {
      navigate("/", { resetScroll: true });
      return;
    }

    setHeader({ title: "گزارش", subtitle: safeText(c.title, ""), showBack: true });

    dom.view().innerHTML = `
      <div class="card">
        <div class="h3">گزارش پیشرفت</div>
        <div class="p">پیشرفت کلی: ${escapeHtml(String(c.progress ?? 0))}%</div>
        <div class="p" style="margin-top:6px">آخرین فعالیت: (دمو)</div>
      </div>
    `;
  }

  function More() {
    setHeader({ title: "بیشتر…", subtitle: "دسترسی‌های حاشیه‌ای", showBack: true });

    dom.view().innerHTML = `
      <div class="card">
        <div class="h3">حساب کاربری</div>
        <div class="p">نام: ${escapeHtml(safeText(state.user?.name, "—"))}</div>
      </div>

      <div class="card">
        <button class="btn btn-ghost" id="settings">تنظیمات</button>
        <div style="height:10px"></div>
        <button class="btn btn-ghost" id="help2">راهنما</button>
        <div style="height:10px"></div>
        <button class="btn btn-ghost" id="logout">خروج</button>
      </div>
    `;

    $("#settings").onclick = () => showToast("تنظیمات (دمو)");
    $("#help2").onclick = () => showToast("راهنما (دمو)");
    $("#logout").onclick = () => showToast("خروج (دمو)");
  }

  // -------------------- INIT --------------------
  function init() {
    bindBackButton();

    // normalize initial list shape
    state.classes = state.classes.map(normalizeClassShape);

    // restore currentClassId
    restoreLastClassId();
    validateCurrentClassId();

    // restore route (Phase 6): do not restore /join
    const savedRoute = restoreLastRoute();
    if (!location.hash) {
      location.hash = "#" + savedRoute;
    } else {
      const p = getPath();
      if (p === "/join") {
        // never land on join via reload
        location.hash = "#/";
      }
    }

    // initial render
    softRender({ resetScroll: false });

    // queue processing
    processJoinQueue("init");

    // smart sync on init (silent)
    smartSyncClasses("init-ttl", { force: false }).then((res) => {
      if (res?.updated) softRender({ resetScroll: false });
    });

    // visibility trigger
    document.addEventListener("visibilitychange", () => {
      if (document.visibilityState === "visible") {
        smartSyncClasses("visible-ttl", { force: false }).then((res) => {
          if (res?.updated) softRender({ resetScroll: false });
        });
      }
    });
  }

  init();
})();
