// public/assets/js/exam.js
// SmartEdu — Exam Edit (API-based, no mock)

(function () {
  const ED = window.examEditData || {};
  const R = (ED.routes || {});
  const PREF = (ED.prefilled || {});
  const EXAM = (ED.exam || {});

  // ---------- helpers ----------
  const $ = (id) => document.getElementById(id);

  async function getJson(url, params = {}) {
    const u = new URL(url, window.location.origin);
    Object.entries(params).forEach(([k, v]) => {
      if (v !== null && v !== undefined && v !== "") u.searchParams.set(k, v);
    });
    const res = await fetch(u.toString(), { headers: { "Accept": "application/json" } });
    if (!res.ok) throw new Error(`HTTP ${res.status} for ${u}`);
    return await res.json();
  }

  function setOptions(selectEl, items, { valueKey = "id", textKey = "name_fa", placeholder = "انتخاب کنید..." } = {}) {
    selectEl.innerHTML = "";
    const ph = document.createElement("option");
    ph.value = "";
    ph.textContent = placeholder;
    selectEl.appendChild(ph);

    items.forEach((it) => {
      const opt = document.createElement("option");
      opt.value = it[valueKey];
      opt.textContent = it[textKey] ?? it.slug ?? it.title_fa ?? it.title ?? String(it[valueKey]);
      selectEl.appendChild(opt);
    });

    selectEl.disabled = false;
  }

  function setValueIfExists(selectEl, value) {
    if (!value) return;
    selectEl.value = String(value);
  }

  // ---------- Elements ----------
  const scopePublic = $("scope-public");
  const scopeClass = $("scope-class");

  const classSelect = $("class-select");
  const classTypeSelect = $("class-type-select");

  const sectionSelect = $("section-select");
  const gradeSelect = $("grade-select");
  const branchSelect = $("branch-select");
  const fieldSelect = $("field-select");
  const subfieldSelect = $("subfield-select");
  const subjectTypeSelect = $("subject-type-select");

  const subjectsContainer = $("subjects-container");
  const subjectsJson = $("subjects-json");

  // ---------- Subjects UI ----------
  function renderSubjects(subjects, selectedIds) {
    if (!subjectsContainer) return;
    subjectsContainer.innerHTML = "";

    if (!subjects.length) {
      subjectsContainer.innerHTML = `<div class="text-muted text-center py-2">هیچ درسی یافت نشد.</div>`;
      if (subjectsJson) subjectsJson.value = "";
      return;
    }

    const selectedSet = new Set((selectedIds || []).map(String));

    subjects.forEach((s) => {
      const id = (s.id ?? s.uuid);
      const card = document.createElement("div");
      card.className = "subject-card";
      card.dataset.id = id;
      card.textContent = s.title_fa ?? s.title ?? "درس";

      if (selectedSet.has(String(id))) card.classList.add("selected");

      card.addEventListener("click", () => {
        card.classList.toggle("selected");
        syncSelectedSubjects();
      });

      subjectsContainer.appendChild(card);
    });

    syncSelectedSubjects();
  }

  function syncSelectedSubjects() {
    if (!subjectsContainer || !subjectsJson) return;
    const selected = [...subjectsContainer.querySelectorAll(".subject-card.selected")]
      .map((el) => el.dataset.id);
    subjectsJson.value = selected.length ? JSON.stringify(selected) : "";
  }

  // ---------- Loaders ----------
  async function loadSections() {
    const { sections } = await getJson(R.sections);
    setOptions(sectionSelect, sections, { valueKey: "id", textKey: "name_fa", placeholder: "انتخاب بخش..." });
  }

  async function loadGrades(section_id) {
    const { grades } = await getJson(R.grades, { section_id });
    setOptions(gradeSelect, grades, { valueKey: "id", textKey: "name_fa", placeholder: "انتخاب پایه..." });
  }

  async function loadBranches(section_id) {
    const { branches } = await getJson(R.branches, { section_id });
    setOptions(branchSelect, branches, { valueKey: "id", textKey: "name_fa", placeholder: "انتخاب شاخه..." });
  }

  async function loadFields(branch_id) {
    const { fields } = await getJson(R.fields, { branch_id });
    setOptions(fieldSelect, fields, { valueKey: "id", textKey: "name_fa", placeholder: "انتخاب زمینه..." });
  }

  async function loadSubfields(field_id) {
    const { subfields } = await getJson(R.subfields, { field_id });
    setOptions(subfieldSelect, subfields, { valueKey: "id", textKey: "name_fa", placeholder: "انتخاب زیررشته..." });
  }

  async function loadSubjectTypes(params) {
    const { subject_types } = await getJson(R.subjectTypes, params);
    // این endpoint شما uuid as id می‌دهد
    setOptions(subjectTypeSelect, subject_types, { valueKey: "id", textKey: "name_fa", placeholder: "انتخاب نوع درس..." });
  }

  async function loadSubjects(params) {
    const { subjects } = await getJson(R.subjects, params);
    // این endpoint شما uuid as id می‌دهد (id = uuid)
    const selected = PREF.subject_ids || [];
    renderSubjects(subjects, selected);
  }

  async function loadClasses() {
    if (!classSelect) return;
    const { classes } = await getJson(R.classes);
    setOptions(classSelect, classes, { valueKey: "id", textKey: "title", placeholder: "انتخاب کلاس..." });
  }

  // ---------- Scope handling ----------
  function isPublicScope() {
    // منبع اصلی: exam_type
    return (EXAM.exam_type === "public") || (scopePublic && scopePublic.checked);
  }

  function toggleScopeUI() {
    const publicBox = document.getElementById("public-settings");
    const classBox = document.getElementById("class-settings");

    if (!publicBox || !classBox) return;

    if (isPublicScope()) {
      publicBox.style.display = "block";
      classBox.style.display = "none";
    } else {
      publicBox.style.display = "none";
      classBox.style.display = "block";
    }
  }

  // ---------- Init (prefill) ----------
  async function initPrefillPublic() {
    // 1) sections
    await loadSections();
    setValueIfExists(sectionSelect, PREF.section_id);

    // 2) grades + branches depend on section
    if (PREF.section_id) {
      await Promise.all([
        loadGrades(PREF.section_id),
        loadBranches(PREF.section_id),
      ]);
    }

    setValueIfExists(gradeSelect, PREF.grade_id);
    setValueIfExists(branchSelect, PREF.branch_id);

    // 3) fields depend on branch
    if (PREF.branch_id) await loadFields(PREF.branch_id);
    setValueIfExists(fieldSelect, PREF.field_id);

    // 4) subfields depend on field
    if (PREF.field_id) await loadSubfields(PREF.field_id);
    setValueIfExists(subfieldSelect, PREF.subfield_id);

    // 5) subject types depend on taxonomy (uuid as id)
    await loadSubjectTypes({
      grade_id: PREF.grade_id || "",
      branch_id: PREF.branch_id || "",
      field_id: PREF.field_id || "",
      subfield_id: PREF.subfield_id || "",
    });
    setValueIfExists(subjectTypeSelect, PREF.subject_type_id);

    // 6) subjects filtered
    await loadSubjects({
      grade_id: PREF.grade_id || "",
      branch_id: PREF.branch_id || "",
      field_id: PREF.field_id || "",
      subfield_id: PREF.subfield_id || "",
      subject_type_id: PREF.subject_type_id || "",
    });
  }

  async function initPrefillClass() {
    await loadClasses();
    setValueIfExists(classSelect, PREF.classroom_id);

    if (classTypeSelect && PREF.classroom_type) {
      classTypeSelect.value = (PREF.classroom_type === "single") ? "single" : "comprehensive";
    }
  }

  // ---------- Events ----------
  function bindEvents() {
    if (scopePublic) scopePublic.addEventListener("change", () => { toggleScopeUI(); });
    if (scopeClass) scopeClass.addEventListener("change", () => { toggleScopeUI(); });

    // taxonomy chain
    if (sectionSelect) sectionSelect.addEventListener("change", async () => {
      const section_id = sectionSelect.value;

      // reset downstream
      gradeSelect.innerHTML = ""; gradeSelect.disabled = true;
      branchSelect.innerHTML = ""; branchSelect.disabled = true;
      fieldSelect.innerHTML = ""; fieldSelect.disabled = true;
      subfieldSelect.innerHTML = ""; subfieldSelect.disabled = true;
      subjectTypeSelect.innerHTML = ""; subjectTypeSelect.disabled = true;
      if (subjectsContainer) subjectsContainer.innerHTML = "";
      if (subjectsJson) subjectsJson.value = "";

      if (!section_id) return;

      await Promise.all([loadGrades(section_id), loadBranches(section_id)]);
    });

    if (branchSelect) branchSelect.addEventListener("change", async () => {
      const branch_id = branchSelect.value;
      fieldSelect.innerHTML = ""; fieldSelect.disabled = true;
      subfieldSelect.innerHTML = ""; subfieldSelect.disabled = true;
      subjectTypeSelect.innerHTML = ""; subjectTypeSelect.disabled = true;
      if (subjectsContainer) subjectsContainer.innerHTML = "";
      if (subjectsJson) subjectsJson.value = "";

      if (!branch_id) return;
      await loadFields(branch_id);
    });

    if (fieldSelect) fieldSelect.addEventListener("change", async () => {
      const field_id = fieldSelect.value;
      subfieldSelect.innerHTML = ""; subfieldSelect.disabled = true;
      subjectTypeSelect.innerHTML = ""; subjectTypeSelect.disabled = true;
      if (subjectsContainer) subjectsContainer.innerHTML = "";
      if (subjectsJson) subjectsJson.value = "";

      if (!field_id) return;
      await loadSubfields(field_id);
    });

    if (subfieldSelect) subfieldSelect.addEventListener("change", async () => {
      subjectTypeSelect.innerHTML = ""; subjectTypeSelect.disabled = true;
      if (subjectsContainer) subjectsContainer.innerHTML = "";
      if (subjectsJson) subjectsJson.value = "";

      await loadSubjectTypes({
        grade_id: gradeSelect?.value || "",
        branch_id: branchSelect?.value || "",
        field_id: fieldSelect?.value || "",
        subfield_id: subfieldSelect?.value || "",
      });
    });

    if (subjectTypeSelect) subjectTypeSelect.addEventListener("change", async () => {
      if (subjectsContainer) subjectsContainer.innerHTML = "";
      if (subjectsJson) subjectsJson.value = "";

      await loadSubjects({
        grade_id: gradeSelect?.value || "",
        branch_id: branchSelect?.value || "",
        field_id: fieldSelect?.value || "",
        subfield_id: subfieldSelect?.value || "",
        subject_type_id: subjectTypeSelect.value || "",
      });
    });
  }

  // ---------- boot ----------
  document.addEventListener("DOMContentLoaded", async () => {
    // نمایش scope اولیه بر اساس exam_type
    if (scopePublic && scopeClass) {
      if (EXAM.exam_type === "public") scopePublic.checked = true;
      else scopeClass.checked = true;
    }
    toggleScopeUI();
    bindEvents();

    // prefill
    try {
      if (EXAM.exam_type === "public") await initPrefillPublic();
      else await initPrefillClass();
    } catch (e) {
      console.error("Edit init error:", e);
    }
  });
})();
