/* ===========================================================
 * exam-wizard.js (UUID + AJAX Real Data)
 * سازگار با routes/teacher.php و TeacherExamController
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
  classesAjax: "/dashboard/teacher/classes?ajax=1"
};

// UI Names (preview)
const examTypeNames = {
  public: "آزمون عمومی",
  class_single: "کلاسی تک درس",
  class_comprehensive: "کلاسی جامع"
};

const subjectTypeFallbackNames = {
  base_competency: "شایستگی پایه",
  non_technical_competency: "شایستگی غیرفنی",
  technical_competency: "شایستگی فنی",
  general: "دروس عمومی",
  all: "همه دروس",
  specialized_competency: "شایستگی‌های تخصصی"
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
  totalQuestions: 0
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
    timerProgressBar: true
  });
}

async function getJSON(url) {
  const res = await fetch(url, {
    headers: {
      Accept: "application/json",
      "X-Requested-With": "XMLHttpRequest"
    }
  });
  if (!res.ok) throw new Error("Network error");
  return res.json();
}

/* ===================== init ===================== */

document.addEventListener("DOMContentLoaded", () => {
  updateProgress();
  updateNavigationButtons();
  loadFromLocalStorage();

  // اگر از قبل examType انتخاب شده بود و کلاسی بود کلاس‌ها رو لود کن
  if (formData.examType && formData.examType !== "public") {
    showClassroomSection(true);
    loadExistingClassrooms();
  }
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
      card.onclick = (e) =>
        selectClassroom(e, classroom.id, classroom.title);
      container.appendChild(card);
    });

    // اگر قبلاً انتخاب داشته
    if (formData.classroomId) {
      container
        .querySelector(
          `[data-classroom-id="${formData.classroomId}"]`
        )
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

  // meta
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

    // اگر جامع کلاسی بود subjectType=all
    if (formData.examType === "class_comprehensive") {
      formData.subjectTypeSlug = "all";
      formData.subjectTypeName = subjectTypeFallbackNames.all;
      formData.subjectTypeId = "";
      setHidden("#subjectTypeId", "");
      calculateCoefficients("all");
    }
  } catch (err) {
    console.error("class meta error", err);
  }

  enableNext(true);
  updatePreview();
  saveToLocalStorage();
  nextStep();
};

/* ===================== step 2 sections/grades ===================== */

async function loadSectionsIfNeeded() {
  // اگر در بلید step0/step2 ندارید می‌تونید حذفش کنید
  // فعلا فقط اگر hidden وجود داشت ست می‌کنیم
  try {
    const data = await getJSON(endpoint.sections);
    // اینجا UI نداره، فقط نگه میداریم برای فیلتر grade/branch
    formData.sectionsList = data.sections || [];
  } catch (e) {}
}

/* -------- load grades -------- */
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
    card.innerHTML = `
      <div class="selection-icon">📘</div>
      <div class="selection-name">${g.name_fa}</div>
      <p class="selection-description">${g.slug || ""}</p>
    `;
    card.onclick = (e) => selectGrade(e, g.id, g.name_fa);
    container.appendChild(card);
  });

  // restore
  if (formData.gradeId) {
    container.querySelectorAll(".selection-card").forEach((c) => {
      if (c.textContent.includes(formData.gradeName)) c.classList.add("selected");
    });
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

  updatePreview();
  saveToLocalStorage();
};

/* ===================== step 3 branches ===================== */

async function loadBranches() {
  const container = qs("#branchesGrid");
  if (!container) return;

  container.innerHTML = `<div class="loading-spinner" style="grid-column:1/-1;text-align:center;">در حال بارگذاری...</div>`;

  const params = new URLSearchParams();
  if (formData.sectionId) params.append("section_id", formData.sectionId);

  const data = await getJSON(`${endpoint.branches}?${params.toString()}`);

  container.innerHTML = "";
  (data.branches || []).forEach((b) => {
    const card = document.createElement("div");
    card.className = "selection-card";
    card.innerHTML = `
      <div class="selection-icon">🎓</div>
      <div class="selection-name">${b.name_fa}</div>
      <p class="selection-description">${b.slug || ""}</p>
    `;
    card.onclick = (e) => selectBranch(e, b.id, b.name_fa);
    container.appendChild(card);
  });
}

window.selectBranch = function (e, branchId, branchName) {
  qsa("#branchesGrid .selection-card").forEach((c) =>
    c.classList.remove("selected")
  );
  e?.target?.closest(".selection-card")?.classList.add("selected");

  formData.branchId = branchId;
  formData.branchName = branchName;
  setHidden("#branchId", branchId);

  // با تغییر branch فیلدها و زیررشته‌ها ریست
  formData.fieldId = "";
  formData.fieldName = "";
  setHidden("#fieldId", "");
  formData.subfieldId = "";
  formData.subfieldName = "";
  setHidden("#subfieldId", "");

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
    card.innerHTML = `
      <div class="selection-icon">🏭</div>
      <div class="selection-name">${f.name_fa}</div>
      <p class="selection-description">${f.slug || ""}</p>
    `;
    card.onclick = (e) => selectField(e, f.id, f.name_fa);
    container.appendChild(card);
  });
}

window.selectField = function (e, fieldId, fieldName) {
  qsa("#fieldsGrid .selection-card").forEach((c) =>
    c.classList.remove("selected")
  );
  e?.target?.closest(".selection-card")?.classList.add("selected");

  formData.fieldId = fieldId;
  formData.fieldName = fieldName;
  setHidden("#fieldId", fieldId);

  // ریست زیررشته
  formData.subfieldId = "";
  formData.subfieldName = "";
  setHidden("#subfieldId", "");

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
    card.innerHTML = `
      <div class="selection-icon">🔬</div>
      <div class="selection-name">${s.name_fa}</div>
      <p class="selection-description">${s.slug || ""}</p>
    `;
    card.onclick = (e) => selectSubfield(e, s.id, s.name_fa);
    container.appendChild(card);
  });
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

/* ===================== step 6 subject types ===================== */

async function loadSubjectTypes() {
  const container = qs("#subjectTypesGrid");
  if (!container) return;

  container.innerHTML = `<div class="loading-spinner" style="grid-column:1/-1;text-align:center;">در حال بارگذاری...</div>`;

  const data = await getJSON(endpoint.subjectTypes);

  container.innerHTML = "";
  (data.subject_types || []).forEach((st) => {
    const card = document.createElement("div");
    card.className = "selection-card";
    card.dataset.slug = st.slug;
    card.innerHTML = `
      <div class="selection-icon">📚</div>
      <div class="selection-name">${st.name_fa}</div>
      <p class="selection-description">${st.slug || ""}</p>
    `;
    card.onclick = (e) =>
      selectSubjectType(e, st.id, st.slug, st.name_fa);
    container.appendChild(card);
  });

  // restore
  if (formData.subjectTypeId) {
    container.querySelectorAll(".selection-card").forEach((c) => {
      if (c.textContent.includes(formData.subjectTypeName)) c.classList.add("selected");
    });
  }
}

window.selectSubjectType = function (e, id, slug, name) {
  qsa("#subjectTypesGrid .selection-card").forEach((c) =>
    c.classList.remove("selected")
  );
  e?.target?.closest(".selection-card")?.classList.add("selected");

  formData.subjectTypeId = id;
  formData.subjectTypeSlug = slug;
  formData.subjectTypeName = name;

  setHidden("#subjectTypeId", id);

  calculateCoefficients(slug);
  updatePreview();
  saveToLocalStorage();
};

/* ---------- coefficient section (simple, your previous UI can stay) ---------- */
function calculateCoefficients(slug) {
  // اینجا فقط totalQuestions رو نگه میداریم؛
  // اگر UI ضرایب داری همون قبلی می‌تونه بماند.
  if (slug === "all") formData.totalQuestions = 115;
  else if (slug === "technical_competency") formData.totalQuestions = 60;
  else if (slug === "base_competency") formData.totalQuestions = 35;
  else if (slug === "non_technical_competency") formData.totalQuestions = 20;
  else formData.totalQuestions = 0;

  qs("#previewTotalQuestions") &&
    (qs("#previewTotalQuestions").textContent =
      formData.totalQuestions + " سوال");
}

/* ===================== step 7 subjects ===================== */

async function loadSubjects() {
  const container = qs("#subjectsContainer");
  if (!container) return;

  container.innerHTML =
    '<div class="loading-spinner"><i class="fas fa-spinner fa-spin"></i> در حال بارگذاری دروس...</div>';

  // فیلترها
  const params = new URLSearchParams();
  if (formData.gradeId) params.append("grade_id", formData.gradeId);
  if (formData.branchId) params.append("branch_id", formData.branchId);
  if (formData.fieldId) params.append("field_id", formData.fieldId);
  if (formData.subfieldId) params.append("subfield_id", formData.subfieldId);
  if (formData.subjectTypeId && formData.subjectTypeSlug !== "all")
    params.append("subject_type_id", formData.subjectTypeId);

  try {
    const data = await getJSON(
      `${endpoint.subjects}?${params.toString()}`
    );
    const subjects = data.subjects || [];

    if (subjects.length === 0) {
      container.innerHTML =
        '<div class="alert alert-info">هیچ درسی یافت نشد.</div>';
      return;
    }

    displaySubjects(subjects);
  } catch (e) {
    console.error(e);
    container.innerHTML =
      '<div class="alert alert-danger">خطا در دریافت دروس.</div>';
  }
}

function displaySubjects(subjects) {
  const container = qs("#subjectsContainer");
  container.innerHTML = "";

  subjects.forEach((subject) => {
    const item = document.createElement("div");
    item.className = "subject-item";
    item.innerHTML = `
      <div class="subject-checkbox">
        <input type="checkbox" id="subject_${subject.id}"
               value="${subject.id}" onchange="updateSelectedSubjects()">
      </div>
      <div class="subject-info">
        <div class="subject-name">${subject.title_fa}</div>
        <div class="subject-meta">
          <span class="subject-code">${subject.code || "-"}</span>
          <span>${subject.hours || 0} ساعت</span>
        </div>
      </div>
    `;

    container.appendChild(item);
  });

  // در حالت all همه انتخاب شوند
  if (formData.subjectTypeSlug === "all") {
    setTimeout(() => {
      qsa(".subject-checkbox input").forEach((cb) => (cb.checked = true));
      updateSelectedSubjects();
    }, 50);
  }

  // restore subject selections
  if (formData.selectedSubjects.length) {
    setTimeout(() => {
      formData.selectedSubjects.forEach((id) => {
        qs(`#subject_${id}`) && (qs(`#subject_${id}`).checked = true);
      });
      updateSelectedSubjects();
    }, 50);
  }
}

window.updateSelectedSubjects = function () {
  const checked = qsa(".subject-checkbox input:checked");
  formData.selectedSubjects = checked.map((cb) => cb.value);

  setHidden("#subjectsInput", formData.selectedSubjects.join(","));

  const countEl = qs("#previewSubjectsCount");
  if (countEl) countEl.textContent = formData.selectedSubjects.length + " درس";

  saveToLocalStorage();
};

/* ===================== step 8 preview ===================== */

function updatePreview() {
  qs("#previewExamType") &&
    (qs("#previewExamType").textContent =
      examTypeNames[formData.examType] || "--");

  qs("#previewGrade") &&
    (qs("#previewGrade").textContent =
      formData.gradeName || "--");

  qs("#previewBranch") &&
    (qs("#previewBranch").textContent =
      formData.branchName || "--");

  qs("#previewField") &&
    (qs("#previewField").textContent =
      formData.fieldName || "--");

  qs("#previewSubfield") &&
    (qs("#previewSubfield").textContent =
      formData.subfieldName || "--");

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

  if (currentStep === 1) {
    prevBtn.style.display = "none";
    nextBtn.style.display = "flex";
    submitBtn.style.display = "none";
  } else if (currentStep === 8) {
    prevBtn.style.display = "flex";
    nextBtn.style.display = "none";
    submitBtn.style.display = "flex";
  } else {
    prevBtn.style.display = "flex";
    nextBtn.style.display = "flex";
    submitBtn.style.display = "none";
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

    // restore hidden values
    setHidden("#examType", formData.examType);
    setHidden("#classroomId", formData.classroomId);
    setHidden("#sectionId", formData.sectionId);
    setHidden("#gradeId", formData.gradeId);
    setHidden("#branchId", formData.branchId);
    setHidden("#fieldId", formData.fieldId);
    setHidden("#subfieldId", formData.subfieldId);
    setHidden("#subjectTypeId", formData.subjectTypeId);
    setHidden("#subjectsInput", formData.selectedSubjects.join(","));

    // restore UI selections for step1 quickly
    if (formData.examType) {
      qs(`.type-card[data-type="${formData.examType}"]`)?.classList.add("selected");
    }
    if (formData.examType && formData.examType !== "public") {
      showClassroomSection(true);
      loadExistingClassrooms();
    }

    updatePreview();
    updateProgress();
    updateNavigationButtons();

    // jump to saved step
    qsa(".form-section").forEach(s => s.classList.remove("active"));
    qs(`#step${currentStep}`)?.classList.add("active");
    handleStepChange();
  }
}

// پاکسازی بعد از submit
qs("#examForm")?.addEventListener("submit", () => {
  localStorage.removeItem("examFormData");
  localStorage.removeItem("examCurrentStep");
});
