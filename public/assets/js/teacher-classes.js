/* ===========================================================
 * teacher-classroom.js (ID + AJAX Real Data)
 * برای صفحات کلاس‌های معلم:
 * 1) create/edit classroom taxonomy chain
 * 2) index classrooms ajax filters (?ajax=1)
 * سازگار با TeacherClassController data endpoints
 * =========================================================== */

(function () {
  /* -------------------- helpers -------------------- */
  const qs = (sel, root = document) => root.querySelector(sel);
  const qsa = (sel, root = document) => Array.from(root.querySelectorAll(sel));

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
      opt.textContent =
        it.name_fa || it.title_fa || it.name || it.title || it.slug;
      selectEl.appendChild(opt);
    });

    selectEl.disabled = false;
  }

  function resetSelect(selectEl, placeholder = "انتخاب کنید") {
    if (!selectEl) return;
    selectEl.innerHTML = `<option value="">${placeholder}</option>`;
    selectEl.disabled = true;
  }

  function setValueIfExists(selectEl, val) {
    if (!selectEl || !val) return;
    const opt = selectEl.querySelector(`option[value="${val}"]`);
    if (opt) selectEl.value = val;
  }

  /* -----------------------------------------------------------
   * 1) CREATE / EDIT CLASSROOM TAXONOMY CHAIN
   * ----------------------------------------------------------- */

  const form =
    qs("#teacherClassroomForm") ||
    qs("#classroomCreateForm") ||
    qs("form[data-classroom-taxonomy='1']") ||
    qs("form[action*='teacher/classes']");

  if (form) {
    const endpoint = {
      sections:
        form.dataset.sectionsEndpoint ||
        "/dashboard/teacher/classes/data/sections",
      grades:
        form.dataset.gradesEndpoint ||
        "/dashboard/teacher/classes/data/grades",
      branches:
        form.dataset.branchesEndpoint ||
        "/dashboard/teacher/classes/data/branches",
      fields:
        form.dataset.fieldsEndpoint ||
        "/dashboard/teacher/classes/data/fields",
      subfields:
        form.dataset.subfieldsEndpoint ||
        "/dashboard/teacher/classes/data/subfields",
      subjectTypes:
        form.dataset.subjectTypesEndpoint ||
        "/dashboard/teacher/classes/data/subject-types",
      subjects:
        form.dataset.subjectsEndpoint ||
        "/dashboard/teacher/classes/data/subjects"
    };

    const sectionSel  = qs('select[name="section_id"]', form) || qs("#section_id", form);
    const gradeSel    = qs('select[name="grade_id"]', form) || qs("#grade_id", form);
    const branchSel   = qs('select[name="branch_id"]', form) || qs("#branch_id", form);
    const fieldSel    = qs('select[name="field_id"]', form) || qs("#field_id", form);
    const subfieldSel = qs('select[name="subfield_id"]', form) || qs("#subfield_id", form);
    const stSel       = qs('select[name="subject_type_id"]', form) || qs("#subject_type_id", form);
    const subjectSel  = qs('select[name="subject_id"]', form) || qs("#subject_id", form);

    const preload = {
      section_id: sectionSel?.dataset?.selected || sectionSel?.value || "",
      grade_id: gradeSel?.dataset?.selected || gradeSel?.value || "",
      branch_id: branchSel?.dataset?.selected || branchSel?.value || "",
      field_id: fieldSel?.dataset?.selected || fieldSel?.value || "",
      subfield_id: subfieldSel?.dataset?.selected || subfieldSel?.value || "",
      subject_type_id: stSel?.dataset?.selected || stSel?.value || "",
      subject_id: subjectSel?.dataset?.selected || subjectSel?.value || ""
    };

    async function loadSections() {
      const data = await getJSON(endpoint.sections);
      setOptions(sectionSel, data || [], "انتخاب مقطع");
      setValueIfExists(sectionSel, preload.section_id);
    }

    async function loadGrades(sectionId) {
      if (!sectionId) {
        resetSelect(gradeSel, "انتخاب پایه");
        return;
      }
      const data = await getJSON(`${endpoint.grades}/${sectionId}`);
      setOptions(gradeSel, data || [], "انتخاب پایه");
      setValueIfExists(gradeSel, preload.grade_id);
    }

    async function loadBranches(gradeId) {
      if (!gradeId) {
        resetSelect(branchSel, "انتخاب شاخه");
        return;
      }
      const data = await getJSON(`${endpoint.branches}/${gradeId}`);
      setOptions(branchSel, data || [], "انتخاب شاخه");
      setValueIfExists(branchSel, preload.branch_id);
    }

    async function loadFields(branchId) {
      if (!branchId) {
        resetSelect(fieldSel, "انتخاب رشته");
        return;
      }
      const data = await getJSON(`${endpoint.fields}/${branchId}`);
      setOptions(fieldSel, data || [], "انتخاب رشته");
      setValueIfExists(fieldSel, preload.field_id);
    }

    async function loadSubfields(fieldId) {
      if (!fieldId) {
        resetSelect(subfieldSel, "انتخاب زیررشته");
        return;
      }
      const data = await getJSON(`${endpoint.subfields}/${fieldId}`);
      setOptions(subfieldSel, data || [], "انتخاب زیررشته");
      setValueIfExists(subfieldSel, preload.subfield_id);
    }

    async function loadSubjectTypes(fieldId) {
      if (!fieldId) {
        resetSelect(stSel, "انتخاب نوع درس");
        return;
      }
      const data = await getJSON(`${endpoint.subjectTypes}/${fieldId}`);
      setOptions(stSel, data || [], "انتخاب نوع درس");
      setValueIfExists(stSel, preload.subject_type_id);
    }

    async function loadSubjects(subjectTypeId) {
      if (!subjectTypeId) {
        resetSelect(subjectSel, "انتخاب درس");
        return;
      }
      const data = await getJSON(`${endpoint.subjects}/${subjectTypeId}`);
      setOptions(subjectSel, data || [], "انتخاب درس");
      setValueIfExists(subjectSel, preload.subject_id);
    }

    function resetDownstream(from) {
      if (from <= 1) resetSelect(gradeSel, "انتخاب پایه");
      if (from <= 2) resetSelect(branchSel, "انتخاب شاخه");
      if (from <= 3) resetSelect(fieldSel, "انتخاب رشته");
      if (from <= 4) resetSelect(subfieldSel, "انتخاب زیررشته");
      if (from <= 5) resetSelect(stSel, "انتخاب نوع درس");
      if (from <= 6) resetSelect(subjectSel, "انتخاب درس");
    }

    sectionSel?.addEventListener("change", async () => {
      resetDownstream(1);
      await loadGrades(sectionSel.value);
    });

    gradeSel?.addEventListener("change", async () => {
      resetDownstream(2);
      await loadBranches(gradeSel.value);
    });

    branchSel?.addEventListener("change", async () => {
      resetDownstream(3);
      await loadFields(branchSel.value);
    });

    fieldSel?.addEventListener("change", async () => {
      resetDownstream(4);
      await loadSubfields(fieldSel.value);
      await loadSubjectTypes(fieldSel.value); // ✅ جدا
    });

    stSel?.addEventListener("change", async () => {
      resetDownstream(6);
      await loadSubjects(stSel.value);
    });

    (async function initTaxonomyChain() {
      try {
        await loadSections();

        if (preload.section_id) await loadGrades(preload.section_id);
        if (preload.grade_id) await loadBranches(preload.grade_id);
        if (preload.branch_id) await loadFields(preload.branch_id);
        if (preload.field_id) {
          await loadSubfields(preload.field_id);
          await loadSubjectTypes(preload.field_id);
        }
        if (preload.subject_type_id) {
          await loadSubjects(preload.subject_type_id);
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

    fetchAndRender();
  }
})();
