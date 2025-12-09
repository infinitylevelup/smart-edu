/* ===========================================================
 * teacher-classroom.js (UUID + AJAX Real Data)
 * برای صفحات کلاس‌های معلم:
 * 1) create/edit classroom taxonomy chain
 * 2) index classrooms ajax filters (?ajax=1)
 * سازگار با TeacherClassController + TeacherExamController data endpoints
 * =========================================================== */

(function () {
  /* -------------------- helpers -------------------- */
  const qs = (sel, root = document) => root.querySelector(sel);
  const qsa = (sel, root = document) => Array.from(root.querySelectorAll(sel));

  const csrf = () =>
    document.querySelector('meta[name="csrf-token"]')?.content || "";

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

  function debounce(fn, delay = 350) {
    let t;
    return (...args) => {
      clearTimeout(t);
      t = setTimeout(() => fn(...args), delay);
    };
  }

  function setOptions(selectEl, items, placeholder = "انتخاب کنید") {
    if (!selectEl) return;
    selectEl.innerHTML = "";
    const opt0 = document.createElement("option");
    opt0.value = "";
    opt0.textContent = placeholder;
    selectEl.appendChild(opt0);

    items.forEach((it) => {
      const opt = document.createElement("option");
      opt.value = it.id;
      opt.textContent = it.name_fa || it.title_fa || it.name || it.title || it.slug;
      selectEl.appendChild(opt);
    });
  }

  function setValueIfExists(selectEl, val) {
    if (!selectEl || !val) return;
    const opt = selectEl.querySelector(`option[value="${val}"]`);
    if (opt) selectEl.value = val;
  }

  /* -----------------------------------------------------------
   * 1) CREATE / EDIT CLASSROOM TAXONOMY CHAIN (select inputs)
   * ----------------------------------------------------------- */

  const form =
    qs("#teacherClassroomForm") ||
    qs("#classroomCreateForm") ||
    qs("form[data-classroom-taxonomy='1']");

  if (form) {
    // endpoints قابل تنظیم از Blade:
    // <form ... data-sections-endpoint="..."
    //             data-grades-endpoint="..."
    //             ... />
    const endpoint = {
      sections:
        form.dataset.sectionsEndpoint ||
        "/dashboard/teacher/exams/data/sections",
      grades:
        form.dataset.gradesEndpoint ||
        "/dashboard/teacher/exams/data/grades",
      branches:
        form.dataset.branchesEndpoint ||
        "/dashboard/teacher/exams/data/branches",
      fields:
        form.dataset.fieldsEndpoint ||
        "/dashboard/teacher/exams/data/fields",
      subfields:
        form.dataset.subfieldsEndpoint ||
        "/dashboard/teacher/exams/data/subfields",
      subjects:
        form.dataset.subjectsEndpoint ||
        "/dashboard/teacher/exams/data/subjects"
    };

    // select ها (نام‌ها رو با id های واقعی Blade هماهنگ کن)
    const sectionSel = qs('select[name="section_id"]', form) || qs("#sectionId", form);
    const gradeSel = qs('select[name="grade_id"]', form) || qs("#gradeId", form);
    const branchSel = qs('select[name="branch_id"]', form) || qs("#branchId", form);
    const fieldSel = qs('select[name="field_id"]', form) || qs("#fieldId", form);
    const subfieldSel = qs('select[name="subfield_id"]', form) || qs("#subfieldId", form);
    const subjectSel = qs('select[name="subject_id"]', form) || qs("#subjectId", form);

    // مقادیر preload برای edit
    const preload = {
      section_id: sectionSel?.dataset?.selected || sectionSel?.value || "",
      grade_id: gradeSel?.dataset?.selected || gradeSel?.value || "",
      branch_id: branchSel?.dataset?.selected || branchSel?.value || "",
      field_id: fieldSel?.dataset?.selected || fieldSel?.value || "",
      subfield_id: subfieldSel?.dataset?.selected || subfieldSel?.value || "",
      subject_id: subjectSel?.dataset?.selected || subjectSel?.value || ""
    };

    async function loadSections() {
      const data = await getJSON(endpoint.sections);
      setOptions(sectionSel, data.sections || [], "انتخاب مقطع");
      setValueIfExists(sectionSel, preload.section_id);
    }

    async function loadGrades(sectionId) {
      if (!sectionId) {
        setOptions(gradeSel, [], "انتخاب پایه");
        return;
      }
      const params = new URLSearchParams({ section_id: sectionId });
      const data = await getJSON(`${endpoint.grades}?${params}`);
      setOptions(gradeSel, data.grades || [], "انتخاب پایه");
      setValueIfExists(gradeSel, preload.grade_id);
    }

    async function loadBranches(sectionId, gradeId) {
      if (!sectionId || !gradeId) {
        setOptions(branchSel, [], "انتخاب شاخه");
        return;
      }
      const params = new URLSearchParams({
        section_id: sectionId,
        grade_id: gradeId
      });
      const data = await getJSON(`${endpoint.branches}?${params}`);
      setOptions(branchSel, data.branches || [], "انتخاب شاخه");
      setValueIfExists(branchSel, preload.branch_id);
    }

    async function loadFields(branchId) {
      if (!branchId) {
        setOptions(fieldSel, [], "انتخاب زمینه");
        return;
      }
      const params = new URLSearchParams({ branch_id: branchId });
      const data = await getJSON(`${endpoint.fields}?${params}`);
      setOptions(fieldSel, data.fields || [], "انتخاب زمینه");
      setValueIfExists(fieldSel, preload.field_id);
    }

    async function loadSubfields(fieldId) {
      if (!fieldId) {
        setOptions(subfieldSel, [], "انتخاب زیررشته");
        return;
      }
      const params = new URLSearchParams({ field_id: fieldId });
      const data = await getJSON(`${endpoint.subfields}?${params}`);
      setOptions(subfieldSel, data.subfields || [], "انتخاب زیررشته");
      setValueIfExists(subfieldSel, preload.subfield_id);
    }

    async function loadSubjects(filters) {
      const params = new URLSearchParams();
      Object.entries(filters).forEach(([k, v]) => v && params.append(k, v));

      const data = await getJSON(`${endpoint.subjects}?${params}`);
      setOptions(subjectSel, data.subjects || [], "انتخاب درس");
      setValueIfExists(subjectSel, preload.subject_id);
    }

    function resetDownstream(from) {
      if (from <= 1) setOptions(gradeSel, [], "انتخاب پایه");
      if (from <= 2) setOptions(branchSel, [], "انتخاب شاخه");
      if (from <= 3) setOptions(fieldSel, [], "انتخاب زمینه");
      if (from <= 4) setOptions(subfieldSel, [], "انتخاب زیررشته");
      if (from <= 5) setOptions(subjectSel, [], "انتخاب درس");
    }

    // listeners
    sectionSel?.addEventListener("change", async () => {
      resetDownstream(1);
      await loadGrades(sectionSel.value);
    });

    gradeSel?.addEventListener("change", async () => {
      resetDownstream(2);
      await loadBranches(sectionSel.value, gradeSel.value);
    });

    branchSel?.addEventListener("change", async () => {
      resetDownstream(3);
      await loadFields(branchSel.value);
    });

    fieldSel?.addEventListener("change", async () => {
      resetDownstream(4);
      await loadSubfields(fieldSel.value);
    });

    subfieldSel?.addEventListener("change", async () => {
      resetDownstream(5);
      await loadSubjects({
        grade_id: gradeSel.value,
        branch_id: branchSel.value,
        field_id: fieldSel.value,
        subfield_id: subfieldSel.value
      });
    });

    // init chain (preload edit-safe)
    (async function initTaxonomyChain() {
      try {
        await loadSections();

        if (preload.section_id) {
          await loadGrades(preload.section_id);
        }
        if (preload.section_id && preload.grade_id) {
          await loadBranches(preload.section_id, preload.grade_id);
        }
        if (preload.branch_id) {
          await loadFields(preload.branch_id);
        }
        if (preload.field_id) {
          await loadSubfields(preload.field_id);
        }
        if (preload.subfield_id) {
          await loadSubjects({
            grade_id: preload.grade_id,
            branch_id: preload.branch_id,
            field_id: preload.field_id,
            subfield_id: preload.subfield_id
          });
        }
      } catch (e) {
        console.error("taxonomy init error", e);
      }
    })();
  }

  /* -----------------------------------------------------------
   * 2) INDEX CLASSROOMS AJAX FILTERS (?ajax=1)
   * ----------------------------------------------------------- */

  const listRoot =
    qs("#classroomsList") ||
    qs("[data-classrooms-list='1']");

  if (listRoot) {
    const ajaxEndpoint =
      listRoot.dataset.ajaxEndpoint ||
      "/dashboard/teacher/classes?ajax=1";

    const searchInput =
      qs("#classroomsSearch") ||
      qs('input[name="q"]');

    const gradeFilter =
      qs("#filterGrade") ||
      qs('select[name="grade_id"]');

    const statusFilter =
      qs("#filterStatus") ||
      qs('select[name="status"]');

    const sortFilter =
      qs("#filterSort") ||
      qs('select[name="sort"]');

    async function fetchAndRender() {
      try {
        const params = new URLSearchParams();
        if (searchInput?.value) params.append("q", searchInput.value.trim());
        if (gradeFilter?.value) params.append("grade", gradeFilter.value);
        if (statusFilter?.value) params.append("status", statusFilter.value);
        if (sortFilter?.value) params.append("sort", sortFilter.value);

        const data = await getJSON(`${ajaxEndpoint}&${params}`);

        const items = data.classrooms || [];
        renderClassrooms(items);
      } catch (e) {
        console.error("classrooms ajax error", e);
        listRoot.innerHTML = `
          <div class="alert alert-danger text-center">
            خطا در دریافت کلاس‌ها
          </div>
        `;
      }
    }

    function renderClassrooms(items) {
      if (!items.length) {
        listRoot.innerHTML = `
          <div class="text-center p-4 text-muted">
            کلاسی یافت نشد.
          </div>
        `;
        return;
      }

      listRoot.innerHTML = "";
      items.forEach((c) => {
        const card = document.createElement("div");
        card.className = "classroom-card";
        card.innerHTML = `
          <div class="classroom-card__title">${c.title}</div>
          <div class="classroom-card__meta">
            ${c.grade || "-"} / ${c.subject || "-"}
          </div>
          <div class="classroom-card__stats">
            ${c.students_count || 0} هنرجو
          </div>
          <div class="classroom-card__actions">
            <a href="/dashboard/teacher/classes/${c.id}/edit" class="btn btn-sm btn-outline-primary">ویرایش</a>
            <a href="/dashboard/teacher/classes/${c.id}" class="btn btn-sm btn-outline-secondary">مشاهده</a>
          </div>
        `;
        listRoot.appendChild(card);
      });
    }

    const refresh = debounce(fetchAndRender, 300);

    searchInput?.addEventListener("input", refresh);
    gradeFilter?.addEventListener("change", fetchAndRender);
    statusFilter?.addEventListener("change", fetchAndRender);
    sortFilter?.addEventListener("change", fetchAndRender);

    // init
    fetchAndRender();
  }
})();
