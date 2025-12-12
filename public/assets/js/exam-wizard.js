/* ===========================================================
 * exam-wizard.js (UUID + AJAX Real Data)
 * سازگار با routes/teacher.php و TeacherExamController
 * نسخه نهایی (منطق 3 حالته + پرش + FIX branches)
 * + FIX default examType from hidden
 * + FIX subjects title_fa/name_fa mismatch
 * =========================================================== */

let currentStep = 1;

const endpoint = {
  sections: "/dashboard/teacher/exams/data/sections",
  grades: "/dashboard/teacher/exams/data/grades",
  branches: "/dashboard/teacher/exams/data/branches",
  fields: "/dashboard/teacher/exams/data/fields",
  subfields: "/dashboard/teacher/exams/data/subfields",
  subjectTypes: "/dashboard/teacher/exams/data/subject-types",
  subjects: "/dashboard/teacher/exams/data/subjects",
  classMeta: (id) => `/dashboard/teacher/classes/${id}/meta`,
  classesAjax: "/dashboard/teacher/classes?ajax=1",
};

// UI Names (preview)
const examTypeNames = {
  public: "آزمون عمومی",
  class_single: "کلاسی تک درس",
  class_comprehensive: "کلاسی جامع",
};

const subjectTypeFallbackNames = {
  base_competency: "شایستگی پایه",
  non_technical_competency: "شایستگی غیرفنی",
  technical_competency: "شایستگی فنی",
  general: "دروس عمومی",
  all: "همه دروس",
  specialized_competency: "شایستگی‌های تخصصی",
};

// state
let formData = {
  examType: "",
  classroomId: null,
  classroomName: "",

  sectionId: "",
  sectionName: "",

  gradeId: "",
  gradeName: "",

  branchId: "",
  branchName: "",

  fieldId: "",
  fieldName: "",

  subfieldId: "",
  subfieldName: "",

  subjectTypeId: "",
  subjectTypeSlug: "",
  subjectTypeName: "",

  selectedSubjects: [], // UUID[]
  totalQuestions: 0,
};

/* ===================== helpers ===================== */

const qs = (sel) => document.querySelector(sel);
const qsa = (sel) => Array.from(document.querySelectorAll(sel));

const csrf = () =>
  document.querySelector('meta[name="csrf-token"]')?.content || "";

function setHidden(id, val) {
  const el = qs(id);
  if (el) el.value = val ?? "";
}

function showToast(message, icon = "error") {
  if (typeof Swal === "undefined") {
    alert(message);
    return;
  }
  Swal.fire({
    toast: true,
    position: "top-start",
    icon,
    title: message,
    showConfirmButton: false,
    timer: 2500,
    timerProgressBar: true,
  });
}

async function getJSON(url) {
  const res = await fetch(url, {
    headers: {
      Accept: "application/json",
      "X-Requested-With": "XMLHttpRequest",
    },
  });
  if (!res.ok) throw new Error("Network error");
  return res.json();
}

/* ===================== init ===================== */

document.addEventListener("DOMContentLoaded", () => {
  updateProgress();
  updateNavigationButtons();
  loadFromLocalStorage();

  // ✅ اگر classroom_id از Blade/URL آمده و قبلاً انتخاب نشده
  const preClassId = qs("#classroomId")?.value;
  if (!formData.classroomId && preClassId) {
    formData.classroomId = preClassId;
    setHidden("#classroomId", preClassId);
  }

  // ✅ FIX مهم: examType پیش‌فرض از hidden بگیر
  const preType = qs("#examType")?.value; // مثلا public
  if (!formData.examType && preType) {
    // بدون پاک‌سازی state، فقط همگام‌سازی و انتخاب کارت
    formData.examType = preType;
    qsa(".type-card").forEach((c) => c.classList.remove("selected"));
    qs(`.type-card[data-type="${preType}"]`)?.classList.add("selected");

    if (preType === "public") {
      showClassroomSection(false);
      enableNext(true);
    } else {
      showClassroomSection(true);
      loadExistingClassrooms();
      enableNext(!!formData.classroomId);
    }
    updatePreview();
    saveToLocalStorage();
  }

  // اگر از قبل examType انتخاب شده بود و کلاسی بود کلاس‌ها رو لود کن
  if (formData.examType && formData.examType !== "public") {
    showClassroomSection(true);
    loadExistingClassrooms();
  }

  updateExamTypeIndicator?.();
});

/* ===================== step 1 exam type ===================== */

window.selectExamType = function (type) {
  qsa(".type-card").forEach((c) => c.classList.remove("selected"));
  qs(`.type-card[data-type="${type}"]`)?.classList.add("selected");

  formData.examType = type;
  setHidden("#examType", type);

  if (type === "public") {
    showClassroomSection(false);

    formData.classroomId = null;
    formData.classroomName = "";
    setHidden("#classroomId", "");

    enableNext(true);
  } else {
    showClassroomSection(true);
    loadExistingClassrooms();
    enableNext(false);
  }

  updateExamTypeIndicator?.();
  updatePreview();
  saveToLocalStorage();
};

function showClassroomSection(show) {
  const sec = qs("#classroomSelectionSection");
  if (!sec) return;
  sec.style.display = show ? "block" : "none";
}

function enableNext(enable) {
  const btn = qs(".btn-next");
  if (!btn) return;
  btn.disabled = !enable;
  btn.classList.toggle("disabled", !enable);
}

/* -------- load classrooms (UUID) -------- */

window.loadExistingClassrooms = async function () {
  const container = qs("#existingClassroomsContainer");
  if (!container) return;

  container.innerHTML = `
    <div class="loading-spinner" style="grid-column: 1 / -1; text-align:center; padding:20px;">
      <i class="fas fa-spinner fa-spin"></i>
      در حال بارگذاری کلاس‌ها...
    </div>
  `;

  try {
    const data = await getJSON(endpoint.classesAjax);

    container.innerHTML = "";

    const classes = data.classrooms || [];
    if (classes.length === 0) {
      container.innerHTML = `
        <div style="grid-column: 1 / -1; text-align:center; padding:20px; color:#777;">
          کلاسی یافت نشد. لطفاً کلاس جدید بسازید.
        </div>
      `;
      return;
    }

    classes.forEach((classroom) => {
      const card = document.createElement("div");
      card.className = "selection-card";
      card.dataset.classroomId = classroom.id;
      card.innerHTML = `
        <div class="selection-icon">🏫</div>
        <div class="selection-name">${classroom.title}</div>
        <p class="selection-description">
          <small>${classroom.grade || "بدون پایه"} - ${classroom.subject || "بدون موضوع"}</small>
          <br><strong>${classroom.students_count || 0} هنرجو</strong>
        </p>
      `;
      card.onclick = (e) => selectClassroom(e, classroom.id, classroom.title);
      container.appendChild(card);
    });

    if (formData.classroomId) {
      container
        .querySelector(`[data-classroom-id="${formData.classroomId}"]`)
        ?.classList.add("selected");
      enableNext(true);
    }
  } catch (err) {
    console.error(err);
    container.innerHTML = `
      <div style="grid-column: 1 / -1; text-align:center; padding:20px; color:#e67;">
        خطا در دریافت کلاس‌ها
        <br>
        <button type="button" onclick="loadExistingClassrooms()" class="btn-nav" style="padding:10px 20px; margin-top:15px;">تلاش مجدد</button>
      </div>
    `;
  }
};

/* -------- select classroom + auto-fill meta -------- */

window.selectClassroom = async function (e, classroomId, classroomName) {
  qsa("#existingClassroomsContainer .selection-card").forEach((c) =>
    c.classList.remove("selected")
  );
  e?.target?.closest(".selection-card")?.classList.add("selected");

  formData.classroomId = classroomId;
  formData.classroomName = classroomName;
  setHidden("#classroomId", classroomId);

  try {
    const metaData = await getJSON(endpoint.classMeta(classroomId));
    const meta = metaData.classroom || {};

    if (meta.section_id) {
      formData.sectionId = meta.section_id;
      setHidden("#sectionId", meta.section_id);
    }

    if (meta.grade_id) {
      formData.gradeId = meta.grade_id;
      setHidden("#gradeId", meta.grade_id);
    }

    if (meta.branch_id) {
      formData.branchId = meta.branch_id;
      setHidden("#branchId", meta.branch_id);
    }

    if (meta.field_id) {
      formData.fieldId = meta.field_id;
      setHidden("#fieldId", meta.field_id);
    }

    if (meta.subfield_id) {
      formData.subfieldId = meta.subfield_id;
      setHidden("#subfieldId", meta.subfield_id);
    }

    if (formData.examType === "class_single" && meta.subject_id) {
      formData.selectedSubjects = [meta.subject_id];
      setHidden("#subjectsInput", JSON.stringify(formData.selectedSubjects));
    }

    if (formData.examType === "class_comprehensive") {
      formData.subjectTypeSlug = "all";
      formData.subjectTypeName = subjectTypeFallbackNames.all;
      formData.subjectTypeId = "";
      setHidden("#subjectTypeId", "");
      calculateCoefficients("all");

      try {
        await loadSubjectsForAllAndSave();
      } catch (e) {
        console.error("auto all subjects error", e);
      }
    }
  } catch (err) {
    console.error("class meta error", err);
  }

  enableNext(true);
  updatePreview();
  saveToLocalStorage();

  if (
    formData.examType === "class_single" ||
    formData.examType === "class_comprehensive"
  ) {
    jumpToStep(8);
  } else {
    nextStep();
  }
};

/* ===================== step 2 grades ===================== */

async function loadGrades() {
  const container = qs("#gradesGrid");
  if (!container) return;

  container.innerHTML = `<div class="loading-spinner" style="grid-column:1/-1;text-align:center;">در حال بارگذاری...</div>`;

  const params = new URLSearchParams();
  if (formData.sectionId) params.append("section_id", formData.sectionId);

  const data = await getJSON(`${endpoint.grades}?${params.toString()}`);

  container.innerHTML = "";
  (data.grades || []).forEach((g) => {
    const card = document.createElement("div");
    card.className = "selection-card";
    card.dataset.id = g.id;
    card.innerHTML = `
      <div class="selection-icon">📘</div>
      <div class="selection-name">${g.name_fa}</div>
      <p class="selection-description">${g.slug || ""}</p>
    `;
    card.onclick = (e) => selectGrade(e, g.id, g.name_fa);
    container.appendChild(card);
  });

  if (formData.gradeId) {
    container
      .querySelector(`[data-id="${formData.gradeId}"]`)
      ?.classList.add("selected");
  }
}

window.selectGrade = function (e, gradeId, gradeName) {
  qsa("#gradesGrid .selection-card").forEach((c) =>
    c.classList.remove("selected")
  );
  e?.target?.closest(".selection-card")?.classList.add("selected");

  formData.gradeId = gradeId;
  formData.gradeName = gradeName;
  setHidden("#gradeId", gradeId);

  formData.branchId = "";
  formData.branchName = "";
  setHidden("#branchId", "");

  formData.fieldId = "";
  formData.fieldName = "";
  setHidden("#fieldId", "");

  formData.subfieldId = "";
  formData.subfieldName = "";
  setHidden("#subfieldId", "");

  formData.subjectTypeId = "";
  formData.subjectTypeSlug = "";
  formData.subjectTypeName = "";
  setHidden("#subjectTypeId", "");

  formData.selectedSubjects = [];
  setHidden("#subjectsInput", JSON.stringify([]));

  updatePreview();
  saveToLocalStorage();
};

/* ===================== step 3 branches (FIXED) ===================== */

async function loadBranches() {
  const container = qs("#branchesGrid");
  if (!container) return;

  container.innerHTML =
    `<div class="loading-spinner" style="grid-column:1/-1;text-align:center;">
        در حال بارگذاری...
     </div>`;

  const params = new URLSearchParams();
  if (formData.sectionId) params.append("section_id", formData.sectionId);
  if (formData.gradeId) params.append("grade_id", formData.gradeId);

  try {
    const data = await getJSON(`${endpoint.branches}?${params.toString()}`);

    container.innerHTML = "";
    (data.branches || []).forEach((b) => {
      const card = document.createElement("div");
      card.className = "selection-card";
      card.dataset.id = b.id;
      card.innerHTML = `
        <div class="selection-icon">🎓</div>
        <div class="selection-name">${b.name_fa}</div>
        <p class="selection-description">${b.slug || ""}</p>
      `;
      card.onclick = (e) => selectBranch(e, b.id, b.name_fa);
      container.appendChild(card);
    });

    if (formData.branchId) {
      container
        .querySelector(`[data-id="${formData.branchId}"]`)
        ?.classList.add("selected");
    }
  } catch (err) {
    console.error(err);
    container.innerHTML = `
      <div class="alert alert-danger text-center" style="grid-column:1/-1;">
        خطا در دریافت شاخه‌ها. لطفاً دوباره تلاش کنید.
        <br>
        <button type="button"
                class="btn-nav btn-prev"
                style="margin-top:12px;border-color:var(--primary);color:var(--primary);"
                onclick="loadBranches()">
          تلاش مجدد
        </button>
      </div>
    `;
  }
}

window.selectBranch = function (e, branchId, branchName) {
  qsa("#branchesGrid .selection-card").forEach((c) =>
    c.classList.remove("selected")
  );
  e?.target?.closest(".selection-card")?.classList.add("selected");

  formData.branchId = branchId;
  formData.branchName = branchName;
  setHidden("#branchId", branchId);

  formData.fieldId = "";
  formData.fieldName = "";
  setHidden("#fieldId", "");

  formData.subfieldId = "";
  formData.subfieldName = "";
  setHidden("#subfieldId", "");

  formData.subjectTypeId = "";
  formData.subjectTypeSlug = "";
  formData.subjectTypeName = "";
  setHidden("#subjectTypeId", "");

  formData.selectedSubjects = [];
  setHidden("#subjectsInput", JSON.stringify([]));

  updatePreview();
  saveToLocalStorage();
};

/* ===================== step 4 fields ===================== */

async function loadFields() {
  const container = qs("#fieldsGrid");
  if (!container) return;

  container.innerHTML = `<div class="loading-spinner" style="grid-column:1/-1;text-align:center;">در حال بارگذاری...</div>`;

  const params = new URLSearchParams();
  if (formData.branchId) params.append("branch_id", formData.branchId);

  const data = await getJSON(`${endpoint.fields}?${params.toString()}`);

  container.innerHTML = "";
  (data.fields || []).forEach((f) => {
    const card = document.createElement("div");
    card.className = "selection-card";
    card.dataset.id = f.id;
    card.innerHTML = `
      <div class="selection-icon">🏭</div>
      <div class="selection-name">${f.name_fa}</div>
      <p class="selection-description">${f.slug || ""}</p>
    `;
    card.onclick = (e) => selectField(e, f.id, f.name_fa);
    container.appendChild(card);
  });

  if (formData.fieldId) {
    container
      .querySelector(`[data-id="${formData.fieldId}"]`)
      ?.classList.add("selected");
  }
}

window.selectField = function (e, fieldId, fieldName) {
  qsa("#fieldsGrid .selection-card").forEach((c) =>
    c.classList.remove("selected")
  );
  e?.target?.closest(".selection-card")?.classList.add("selected");

  formData.fieldId = fieldId;
  formData.fieldName = fieldName;
  setHidden("#fieldId", fieldId);

  formData.subfieldId = "";
  formData.subfieldName = "";
  setHidden("#subfieldId", "");

  formData.subjectTypeId = "";
  formData.subjectTypeSlug = "";
  formData.subjectTypeName = "";
  setHidden("#subjectTypeId", "");

  formData.selectedSubjects = [];
  setHidden("#subjectsInput", JSON.stringify([]));

  updatePreview();
  saveToLocalStorage();
};

/* ===================== step 5 subfields ===================== */

async function loadSubfields() {
  const container = qs("#subfieldGrid");
  if (!container) return;

  container.innerHTML = `<div class="loading-spinner" style="grid-column:1/-1;text-align:center;">در حال بارگذاری...</div>`;

  const params = new URLSearchParams();
  if (formData.fieldId) params.append("field_id", formData.fieldId);

  const data = await getJSON(`${endpoint.subfields}?${params.toString()}`);

  container.innerHTML = "";
  (data.subfields || []).forEach((s) => {
    const card = document.createElement("div");
    card.className = "selection-card";
    card.dataset.id = s.id;
    card.innerHTML = `
      <div class="selection-icon">🔬</div>
      <div class="selection-name">${s.name_fa}</div>
      <p class="selection-description">${s.slug || ""}</p>
    `;
    card.onclick = (e) => selectSubfield(e, s.id, s.name_fa);
    container.appendChild(card);
  });

  if (formData.subfieldId) {
    container
      .querySelector(`[data-id="${formData.subfieldId}"]`)
      ?.classList.add("selected");
  }
}

window.selectSubfield = function (e, subfieldId, subfieldName) {
  qsa("#subfieldGrid .selection-card").forEach((c) =>
    c.classList.remove("selected")
  );
  e?.target?.closest(".selection-card")?.classList.add("selected");

  formData.subfieldId = subfieldId;
  formData.subfieldName = subfieldName;
  setHidden("#subfieldId", subfieldId);

  updatePreview();
  saveToLocalStorage();
};
/* ===================== step 6 subject types (FIXED like branches) ===================== */
/* ===================== step 6 subject types (FIX like branches) ===================== */

const subjectTypeMetaMap = {
  // slugs واقعی DB شما
  DSHP: { name: "شایستگی پایه", questions: 35 },
  DSHGHF: { name: "شایستگی غیرفنی", questions: 20 },
  DSHF: { name: "شایستگی فنی", questions: 60 },

  // slugs استاندارد قبلی (برای سازگاری)
  base_competency: { name: "شایستگی پایه", questions: 35 },
  non_technical_competency: { name: "شایستگی غیرفنی", questions: 20 },
  technical_competency: { name: "شایستگی فنی", questions: 60 },

  general: { name: "دروس عمومی", questions: 0 },
  specialized_competency: { name: "شایستگی‌های تخصصی", questions: 0 },
  all: { name: "همه دروس", questions: 115 },
};

function getSubjectTypeMeta(slug) {
  return subjectTypeMetaMap[slug] || { name: slug || "--", questions: 0 };
}

async function loadSubjectTypes() {
  const container = qs("#subjectTypesGrid");
  if (!container) return;

  container.innerHTML = `
    <div class="loading-spinner" style="grid-column:1/-1;text-align:center;">
      در حال بارگذاری...
    </div>
  `;

  // مثل branches: پارامترها رو دقیق و مرحله‌ای می‌سازیم
  const params = new URLSearchParams();
  if (formData.sectionId) params.append("section_id", formData.sectionId);
  if (formData.gradeId) params.append("grade_id", formData.gradeId);
  if (formData.branchId) params.append("branch_id", formData.branchId);
  if (formData.fieldId) params.append("field_id", formData.fieldId);
  if (formData.subfieldId) params.append("subfield_id", formData.subfieldId);

  const urlWithParams = `${endpoint.subjectTypes}?${params.toString()}`;
  const urlNoParams = `${endpoint.subjectTypes}`;

  try {
    // 1) اول با فیلترها امتحان کن
    let data;
    try {
      data = await getJSON(urlWithParams);
    } catch (e) {
      // 2) اگر مثل شاخه‌ها backend با فیلترها خطا داد، بدون فیلتر دوباره بگیر
      console.warn("subject-types with params failed, retrying without params", e);
      data = await getJSON(urlNoParams);
    }

    container.innerHTML = "";
    const list = data.subject_types || data.subjectTypes || [];

    if (list.length === 0) {
      container.innerHTML = `
        <div class="alert alert-info text-center" style="grid-column:1/-1;">
          دسته درسی برای انتخاب‌های شما یافت نشد.
        </div>
      `;
      return;
    }

    list.forEach((st) => {
      const card = document.createElement("div");
      card.className = "selection-card";
      card.dataset.slug = st.slug;

      const title = st.name_fa || getSubjectTypeMeta(st.slug).name;

      card.innerHTML = `
        <div class="selection-icon">📚</div>
        <div class="selection-name">${title}</div>
        <p class="selection-description">${st.slug || ""}</p>
      `;
      card.onclick = (e) => selectSubjectType(e, st.id, st.slug, title);
      container.appendChild(card);
    });

    // اگر قبلاً انتخاب شده بود (localStorage)، هایلایت برگرده
    if (formData.subjectTypeSlug) {
      container
        .querySelector(`[data-slug="${formData.subjectTypeSlug}"]`)
        ?.classList.add("selected");
    }

  } catch (err) {
    console.error("subject types error", err);
    container.innerHTML = `
      <div class="alert alert-danger text-center" style="grid-column:1/-1;">
        خطا در دریافت دسته‌های درسی. لطفاً دوباره تلاش کنید.
        <br>
        <button type="button"
          class="btn-nav btn-prev"
          style="margin-top:12px;border-color:var(--primary);color:var(--primary);"
          onclick="loadSubjectTypes()">
          تلاش مجدد
        </button>
      </div>
    `;
  }
}

window.selectSubjectType = function (e, id, slug, name) {
  qsa("#subjectTypesGrid .selection-card").forEach((c) =>
    c.classList.remove("selected")
  );
  e?.target?.closest(".selection-card")?.classList.add("selected");

  formData.subjectTypeId = id;
  formData.subjectTypeSlug = slug;

  // name یا از بک‌اند یا از map
  formData.subjectTypeName = name || getSubjectTypeMeta(slug).name;

  setHidden("#subjectTypeId", id);

  calculateCoefficients(slug);
  updatePreview();
  saveToLocalStorage();
};

/* ---------- coefficient section (FIX slugs) ---------- */

function calculateCoefficients(slug) {
  const meta = getSubjectTypeMeta(slug);
  formData.totalQuestions = meta.questions || 0;

  const el = qs("#previewTotalQuestions");
  if (el) el.textContent = (formData.totalQuestions || 0) + " سوال";
}

function isUuid(val) {
  return typeof val === "string" &&
    /^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i.test(val);
}

/* ===================== step 7 subjects ===================== */
async function loadSubjects() {
// ✅ آخرین خط دفاع: اگر subjectTypeId عددی/قدیمی بود، پاکش کن
if (formData.subjectTypeId && !isUuid(formData.subjectTypeId)) {
  console.warn("subjectTypeId was numeric, clearing it:", formData.subjectTypeId);
  formData.subjectTypeId = "";
  setHidden("#subjectTypeId", "");
  saveToLocalStorage();
}

  const container = qs("#subjectsContainer");
  if (!container) return;

  container.innerHTML =
    '<div class="loading-spinner"><i class="fas fa-spinner fa-spin"></i> در حال بارگذاری دروس...</div>';

  const params = new URLSearchParams();

  // ✅ فیلترهای اصلی
  if (formData.gradeId) params.append("grade_id", formData.gradeId);
  if (formData.branchId) params.append("branch_id", formData.branchId);
  if (formData.fieldId) params.append("field_id", formData.fieldId);
  if (formData.subfieldId) params.append("subfield_id", formData.subfieldId);

  // ✅ حالت‌های subject type
  if (formData.subjectTypeSlug === "all") {
    // هیچ subject_type_id نفرستیم، بک‌اند همه رو برگردونه
  } else if (formData.subjectTypeId) {
    params.append("subject_type_id", formData.subjectTypeId);
  } else if (formData.subjectTypeSlug) {
    // اگر بک‌اند slug می‌پذیره
    params.append("subject_type_slug", formData.subjectTypeSlug);
  }

  const url = `${endpoint.subjects}?${params.toString()}`;
  console.log("subjects url =>", url);

  try {
    const data = await getJSON(url);

    // ✅ انعطاف در نام کلید جواب
    const subjects =
      data.subjects ||
      data.data ||
      data.items ||
      [];

    if (!subjects.length) {
      container.innerHTML =
        '<div class="alert alert-info">هیچ درسی یافت نشد.</div>';
      return;
    }

    displaySubjects(subjects);

  } catch (e) {
    console.error("subjects fetch error", e);
    container.innerHTML =
      '<div class="alert alert-danger">خطا در دریافت دروس.</div>';
  }
}


function displaySubjects(subjects) {
  const container = qs("#subjectsContainer");
  container.innerHTML = "";

  const singleSelect =
    formData.examType === "public" || formData.examType === "class_single";

  subjects.forEach((subject) => {
    // ✅ FIX: بک‌اند name_fa می‌دهد، فرانت title_fa می‌خواست
    const title = subject.title_fa || subject.name_fa || subject.title || "--";

    const item = document.createElement("div");
    item.className = "subject-item";
    item.innerHTML = `
      <div class="subject-checkbox">
        <input type="checkbox"
               id="subject_${subject.id}"
               value="${subject.id}"
               data-single="${singleSelect ? 1 : 0}"
               onchange="updateSelectedSubjects(this)">
      </div>
      <div class="subject-info">
        <div class="subject-name">${title}</div>
        <div class="subject-meta">
          <span class="subject-code">${subject.code || "-"}</span>
          <span>${subject.hours || 0} ساعت</span>
        </div>
      </div>
    `;
    container.appendChild(item);
  });

  if (
    formData.subjectTypeSlug === "all" ||
    formData.examType === "class_comprehensive"
  ) {
    setTimeout(() => {
      qsa(".subject-checkbox input").forEach((cb) => (cb.checked = true));
      updateSelectedSubjects();
    }, 50);
    return;
  }

  if (formData.selectedSubjects.length) {
    setTimeout(() => {
      formData.selectedSubjects.forEach((id) => {
        const el = qs(`#subject_${id}`);
        if (el) el.checked = true;
      });
      updateSelectedSubjects();
    }, 50);
  }
}

window.updateSelectedSubjects = function (changedEl = null) {
  const all = qsa(".subject-checkbox input");
  const singleSelect =
    formData.examType === "public" || formData.examType === "class_single";

  if (singleSelect && changedEl && changedEl.checked) {
    all.forEach((cb) => {
      if (cb !== changedEl) cb.checked = false;
    });
  }

  const checked = all.filter((cb) => cb.checked);
  formData.selectedSubjects = checked.map((cb) => cb.value);

  setHidden("#subjectsInput", JSON.stringify(formData.selectedSubjects));

  const countEl = qs("#previewSubjectsCount");
  if (countEl) {
    countEl.textContent = formData.selectedSubjects.length
      ? formData.selectedSubjects.length + " درس"
      : "--";
  }

  saveToLocalStorage();
};

/* ===================== step 8 preview ===================== */

function updatePreview() {
  qs("#previewExamType") &&
    (qs("#previewExamType").textContent =
      examTypeNames[formData.examType] || "--");

  qs("#previewGrade") &&
    (qs("#previewGrade").textContent = formData.gradeName || "--");

  qs("#previewBranch") &&
    (qs("#previewBranch").textContent = formData.branchName || "--");

  qs("#previewField") &&
    (qs("#previewField").textContent = formData.fieldName || "--");

  qs("#previewSubfield") &&
    (qs("#previewSubfield").textContent = formData.subfieldName || "--");

  qs("#previewSubjectType") &&
    (qs("#previewSubjectType").textContent =
      formData.subjectTypeName ||
      subjectTypeFallbackNames[formData.subjectTypeSlug] ||
      "--");

  qs("#previewSubjectsCount") &&
    (qs("#previewSubjectsCount").textContent =
      formData.selectedSubjects.length
        ? formData.selectedSubjects.length + " درس"
        : "--");
}

/* ===================== navigation ===================== */

window.nextStep = function () {
  if (!validateCurrentStep()) return;

  if (currentStep < 8) {
    qs(`#step${currentStep}`)?.classList.remove("active");
    currentStep++;
    qs(`#step${currentStep}`)?.classList.add("active");

    handleStepChange();
    updateProgress();
    updateNavigationButtons();
    updatePreview();
    saveToLocalStorage();

    window.scrollTo({ top: 0, behavior: "smooth" });
  }
};

window.prevStep = function () {
  if (currentStep > 1) {
    qs(`#step${currentStep}`)?.classList.remove("active");
    currentStep--;
    qs(`#step${currentStep}`)?.classList.add("active");

    handleStepChange();
    updateProgress();
    updateNavigationButtons();
    updatePreview();
    saveToLocalStorage();

    window.scrollTo({ top: 0, behavior: "smooth" });
  }
};

function handleStepChange() {
  if (currentStep === 2) loadGrades();
  if (currentStep === 3) loadBranches();
  if (currentStep === 4) loadFields();
  if (currentStep === 5) loadSubfields();
  if (currentStep === 6) loadSubjectTypes();
  if (currentStep === 7) loadSubjects();
}

/* ===================== validation ===================== */

function validateCurrentStep() {
  let ok = true;
  let msg = "";

  switch (currentStep) {
    case 1:
      if (!formData.examType) {
        ok = false;
        msg = "لطفاً نوع آزمون را انتخاب کنید.";
      }
      if (formData.examType !== "public" && !formData.classroomId) {
        ok = false;
        msg = "لطفاً یک کلاس انتخاب کنید.";
      }
      break;

    case 2:
      if (!formData.gradeId) {
        ok = false;
        msg = "لطفاً پایه تحصیلی را انتخاب کنید.";
      }
      break;

    case 3:
      if (!formData.branchId) {
        ok = false;
        msg = "لطفاً شاخه تحصیلی را انتخاب کنید.";
      }
      break;

    case 4:
      if (!formData.fieldId) {
        ok = false;
        msg = "لطفاً زمینه فنی را انتخاب کنید.";
      }
      break;

    case 5:
      if (!formData.subfieldId) {
        ok = false;
        msg = "لطفاً زیررشته را انتخاب کنید.";
      }
      break;

    case 6:
      if (!formData.subjectTypeSlug && !formData.subjectTypeId) {
        ok = false;
        msg = "لطفاً دسته درسی را انتخاب کنید.";
      }
      break;

    case 7:
      if (
        formData.selectedSubjects.length === 0 &&
        formData.subjectTypeSlug !== "all"
      ) {
        ok = false;
        msg = "لطفاً حداقل یک درس انتخاب کنید.";
      }
      break;
  }

  if (!ok && msg) showToast(msg, "error");
  return ok;
}

window.validateFinalStep = function () {
  if (
    !formData.examType ||
    !formData.gradeId ||
    !formData.branchId ||
    !formData.fieldId ||
    !formData.subfieldId ||
    (!formData.subjectTypeSlug && !formData.subjectTypeId)
  ) {
    showToast("لطفاً تمام مراحل را تکمیل کنید.", "error");
    return false;
  }

  if (
    formData.selectedSubjects.length === 0 &&
    formData.subjectTypeSlug !== "all"
  ) {
    showToast("لطفاً حداقل یک درس انتخاب کنید.", "error");
    return false;
  }

  localStorage.removeItem("examFormData");
  localStorage.removeItem("examCurrentStep");
  return true;
};

/* ===================== progress ===================== */

function updateProgress() {
  const progress = (currentStep / 8) * 100;
  qs("#progressFill") &&
    (qs("#progressFill").style.width = `${progress}%`);

  qsa(".step-item").forEach((item, index) => {
    item.classList.remove("active", "completed");
    if (index + 1 < currentStep) item.classList.add("completed");
    else if (index + 1 === currentStep) item.classList.add("active");
  });
}

function updateNavigationButtons() {
  const prevBtn = qs(".btn-prev");
  const nextBtn = qs(".btn-next");
  const submitBtn = qs(".btn-submit");

  if (!prevBtn || !nextBtn || !submitBtn) return;

  prevBtn.style.display = "flex";
  nextBtn.style.display = "flex";
  submitBtn.style.display = "none";

  if (currentStep === 1) {
    prevBtn.style.display = "none";
    nextBtn.style.display = "flex";
    submitBtn.style.display = "none";
  } else if (currentStep === 8) {
    prevBtn.style.display = "flex";
    nextBtn.style.display = "none";
    submitBtn.style.display = "flex";
  }
}

/* ===================== local storage ===================== */

function saveToLocalStorage() {
  localStorage.setItem("examFormData", JSON.stringify(formData));
  localStorage.setItem("examCurrentStep", currentStep);
}

function loadFromLocalStorage() {
  const savedData = localStorage.getItem("examFormData");
  const savedStep = localStorage.getItem("examCurrentStep");

  if (savedData) {
    formData = { ...formData, ...JSON.parse(savedData) };
    if (savedStep) currentStep = parseInt(savedStep);

    setHidden("#examType", formData.examType);
    setHidden("#classroomId", formData.classroomId);
    setHidden("#sectionId", formData.sectionId);
    setHidden("#gradeId", formData.gradeId);
    setHidden("#branchId", formData.branchId);
    setHidden("#fieldId", formData.fieldId);
    setHidden("#subfieldId", formData.subfieldId);
    setHidden("#subjectTypeId", formData.subjectTypeId);
    setHidden("#subjectsInput", JSON.stringify(formData.selectedSubjects));

    if (formData.examType) {
      qs(`.type-card[data-type="${formData.examType}"]`)?.classList.add(
        "selected"
      );
    }
    if (formData.examType && formData.examType !== "public") {
      showClassroomSection(true);
      loadExistingClassrooms();
    }

    updateExamTypeIndicator?.();
    updatePreview();
    updateProgress();
    updateNavigationButtons();

    qsa(".form-section").forEach((s) => s.classList.remove("active"));
    qs(`#step${currentStep}`)?.classList.add("active");
    handleStepChange();
  }
}

qs("#examForm")?.addEventListener("submit", () => {
  localStorage.removeItem("examFormData");
  localStorage.removeItem("examCurrentStep");
});

/* ===================== extras: jump + auto all subjects ===================== */

function jumpToStep(stepNumber) {
  qs(`#step${currentStep}`)?.classList.remove("active");

  currentStep = stepNumber;

  qsa(".form-section").forEach((s) => s.classList.remove("active"));
  qs(`#step${currentStep}`)?.classList.add("active");

  handleStepChange();
  updateProgress();
  updateNavigationButtons();
  updatePreview();
  saveToLocalStorage();

  window.scrollTo({ top: 0, behavior: "smooth" });
}

async function loadSubjectsForAllAndSave() {
  const params = new URLSearchParams();
  if (formData.gradeId) params.append("grade_id", formData.gradeId);
  if (formData.branchId) params.append("branch_id", formData.branchId);
  if (formData.fieldId) params.append("field_id", formData.fieldId);
  if (formData.subfieldId) params.append("subfield_id", formData.subfieldId);

  const data = await getJSON(`${endpoint.subjects}?${params.toString()}`);
  const subjects = data.subjects || [];

  formData.selectedSubjects = subjects.map((s) => s.id);
  setHidden("#subjectsInput", JSON.stringify(formData.selectedSubjects));

  const countEl = qs("#previewSubjectsCount");
  if (countEl) countEl.textContent = formData.selectedSubjects.length + " درس";

  saveToLocalStorage();
}

function updateExamTypeIndicator() {
  const wrap = qs("#examTypeIndicator");
  const text = qs("#examTypeIndicatorText");
  if (!wrap || !text) return;

  if (!formData.examType) {
    wrap.style.display = "none";
    return;
  }

  wrap.style.display = "block";
  text.textContent = examTypeNames[formData.examType] || formData.examType;

  text.className = "badge";
  if (formData.examType === "public") text.classList.add("bg-primary");
  if (formData.examType === "class_single") text.classList.add("bg-success");
  if (formData.examType === "class_comprehensive")
    text.classList.add("bg-warning");
}
