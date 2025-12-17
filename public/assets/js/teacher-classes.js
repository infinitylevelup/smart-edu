/*!
 * Smart-Edu — Teacher Classes (index/create/show/edit) module
 * Location: public/assets/js/teacher-classes.js
 *
 * صفحات باید HTML-only باشند و فقط یک marker داشته باشند:
 *   data-page="teacher-classes-index|create|show|edit"
 *
 * (اختیاری) config برای routeها را روی همان wrapper بگذارید:
 *   data-sections-url
 *   data-grades-url          (template, contains :section)
 *   data-branches-url        (template, contains :grade)
 *   data-fields-url          (template, contains :branch)
 *   data-subfields-url       (template, contains :field)
 *   data-subject-types-url   (template, contains :field)
 *   data-subjects-url        (template, contains :subjectType)
 *
 * (اختیاری برای edit) initial values:
 *   data-initial-section, data-initial-grade, data-initial-branch, data-initial-field,
 *   data-initial-subfield, data-initial-subject-type, data-initial-subject
 */

(function () {
  'use strict';

  // --------- Core helpers ---------
  function $(sel, root = document) { return root.querySelector(sel); }
  function $all(sel, root = document) { return Array.from(root.querySelectorAll(sel)); }

  function getPageRoot() {
    // Prefer the first element that declares data-page; fallback to body.
    return document.querySelector('[data-page]') || document.body;
  }

  function getPageName(root) {
    return (root && root.dataset && root.dataset.page) || (document.body && document.body.dataset && document.body.dataset.page) || '';
  }

  function safeText(v) {
    return (v ?? '').toString().trim();
  }

  function templateUrl(tpl, key, value) {
    if (!tpl) return '';
    return tpl.replace(`:${key}`, encodeURIComponent(String(value)));
  }

  async function fetchJson(url, { signal } = {}) {
    const res = await fetch(url, {
      headers: { 'Accept': 'application/json' },
      signal
    });
    if (!res.ok) {
      const text = await res.text().catch(() => '');
      throw new Error(`HTTP ${res.status} for ${url}${text ? `\n${text}` : ''}`);
    }
    return res.json();
  }

  function setSelectOptions(selectEl, items, { placeholder = 'انتخاب کنید', valueKey = 'id', labelKey = 'title' } = {}) {
    if (!selectEl) return;
    selectEl.innerHTML = '';
    const ph = document.createElement('option');
    ph.value = '';
    ph.textContent = placeholder;
    selectEl.appendChild(ph);

    (items || []).forEach((it) => {
      const opt = document.createElement('option');
      opt.value = it[valueKey] ?? it.id ?? '';
      opt.textContent =
        it[labelKey] ??
        it.title_fa ??
        it.name_fa ??
        it.title ??
        it.name ??
        '';
      selectEl.appendChild(opt);
    });
  }

  function setSelectEnabled(selectEl, enabled) {
    if (!selectEl) return;
    selectEl.disabled = !enabled;
  }

  function resetSelect(selectEl, placeholder) {
    if (!selectEl) return;
    setSelectOptions(selectEl, [], { placeholder: placeholder || 'انتخاب کنید' });
    setSelectEnabled(selectEl, false);
  }

  // --------- Taxonomy loader (shared between create/edit/modal later) ---------
  function createTaxonomyLoader(root) {
    const cfg = {
      sectionsUrl: root.dataset.sectionsUrl || '',
      gradesTpl: root.dataset.gradesUrl || '',
      branchesTpl: root.dataset.branchesUrl || '',
      fieldsTpl: root.dataset.fieldsUrl || '',
      subfieldsTpl: root.dataset.subfieldsUrl || '',
      subjectTypesTpl: root.dataset.subjectTypesUrl || '',
      subjectsTpl: root.dataset.subjectsUrl || '',
    };

    const ac = new AbortController();

    async function loadSections(select) {
      if (!cfg.sectionsUrl) return [];
      const data = await fetchJson(cfg.sectionsUrl, { signal: ac.signal });
      setSelectOptions(select, data, { placeholder: 'انتخاب مقطع' });
      setSelectEnabled(select, true);
      return data;
    }

    async function loadGrades(sectionId, select) {
      if (!cfg.gradesTpl) return [];
      const url = templateUrl(cfg.gradesTpl, 'section', sectionId);
      const data = await fetchJson(url, { signal: ac.signal });
      setSelectOptions(select, data, { placeholder: 'انتخاب پایه' });
      setSelectEnabled(select, true);
      return data;
    }

    async function loadBranches(gradeId, select) {
      if (!cfg.branchesTpl) return [];
      const url = templateUrl(cfg.branchesTpl, 'grade', gradeId);
      const data = await fetchJson(url, { signal: ac.signal });
      setSelectOptions(select, data, { placeholder: 'انتخاب شاخه' });
      setSelectEnabled(select, true);
      return data;
    }

    async function loadFields(branchId, select) {
      if (!cfg.fieldsTpl) return [];
      const url = templateUrl(cfg.fieldsTpl, 'branch', branchId);
      const data = await fetchJson(url, { signal: ac.signal });
      setSelectOptions(select, data, { placeholder: 'انتخاب رشته' });
      setSelectEnabled(select, true);
      return data;
    }

    async function loadSubfields(fieldId, select) {
      if (!cfg.subfieldsTpl) return [];
      const url = templateUrl(cfg.subfieldsTpl, 'field', fieldId);
      const data = await fetchJson(url, { signal: ac.signal });
      setSelectOptions(select, data, { placeholder: 'انتخاب زیررشته' });
      setSelectEnabled(select, true);
      return data;
    }

    async function loadSubjectTypes(fieldId, select) {
      if (!cfg.subjectTypesTpl) return [];
      const url = templateUrl(cfg.subjectTypesTpl, 'field', fieldId);
      const data = await fetchJson(url, { signal: ac.signal });
      setSelectOptions(select, data, { placeholder: 'انتخاب نوع درس' });
      setSelectEnabled(select, true);
      return data;
    }

    async function loadSubjects(subjectTypeId, select) {
      if (!cfg.subjectsTpl) return [];
      const url = templateUrl(cfg.subjectsTpl, 'subjectType', subjectTypeId);
      const data = await fetchJson(url, { signal: ac.signal });
      setSelectOptions(select, data, { placeholder: 'انتخاب درس' });
      setSelectEnabled(select, true);
      return data;
    }

    function destroy() { ac.abort(); }

    return {
      cfg,
      loadSections,
      loadGrades,
      loadBranches,
      loadFields,
      loadSubfields,
      loadSubjectTypes,
      loadSubjects,
      destroy
    };
  }

  // --------- Common UI utils ---------
  function copyToClipboard(text) {
    const t = safeText(text);
    if (!t) return Promise.resolve(false);
    if (navigator.clipboard && navigator.clipboard.writeText) {
      return navigator.clipboard.writeText(t).then(() => true).catch(() => false);
    }
    // Fallback
    const ta = document.createElement('textarea');
    ta.value = t;
    ta.style.position = 'fixed';
    ta.style.left = '-9999px';
    document.body.appendChild(ta);
    ta.select();
    try {
      const ok = document.execCommand('copy');
      document.body.removeChild(ta);
      return Promise.resolve(ok);
    } catch {
      document.body.removeChild(ta);
      return Promise.resolve(false);
    }
  }

  function randomJoinCode(len = 6) {
    // Friendly uppercase code: A-Z + 2-9 (avoid 0/1/O/I)
    const alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    let out = '';
    for (let i = 0; i < len; i++) out += alphabet[Math.floor(Math.random() * alphabet.length)];
    return out;
  }

  // --------- Page inits ---------
  function initIndex(root) {
    // Optional: confirm delete buttons (data-confirm)
    $all('[data-confirm]', root).forEach((btn) => {
      btn.addEventListener('click', (e) => {
        const msg = btn.getAttribute('data-confirm') || 'آیا مطمئن هستید؟';
        if (!confirm(msg)) e.preventDefault();
      });
    });
  }

  function initShow(root) {
    const codeEl = $('#joinCode', root);
    const copyBtn = $('#copyCodeBtn', root);
    if (copyBtn && codeEl) {
      copyBtn.addEventListener('click', async () => {
        const ok = await copyToClipboard(codeEl.value || codeEl.textContent);
        if (!ok) alert('کپی انجام نشد. لطفاً دستی کپی کنید.');
      });
    }
  }

  function initCreate(root) {
    const form = $('form', root);
    if (!form) return;

    const steps = $all('.step[data-step]', root);
    const stepTabs = $all('.wiz-step[data-step]', root);
    const prevBtn = $('#prevBtn', root);
    const nextBtn = $('#nextBtn', root);
    const submitBtn = $('#submitBtn', root);
    const reviewBox = $('#reviewBox', root);

    const pvTitle = $('#pvTitle', root);
    const pvTax = $('#pvTax', root);
    const pvType = $('#pvType', root);
    const pvActive = $('#pvActive', root);
    const pvCode = $('#pvCode', root);
    const pvDesc = $('#pvDesc', root);

    const titleEl = $('input[name="title"]', root);
    const descEl = $('textarea[name="description"]', root);
    const activeSwitch = $('#activeSwitch', root);
    const classroomType = $('#classroom_type', root);
    const joinCodeInput = $('#joinCode', root);
    const genCodeBtn = $('#genCodeBtn', root);

    const sectionSel = $('#section_id', root);
    const gradeSel = $('#grade_id', root);
    const branchSel = $('#branch_id', root);
    const fieldSel = $('#field_id', root);
    const subfieldSel = $('#subfield_id', root);
    const subjectTypeSel = $('#subject_type_id', root);
    const subjectSel = $('#subject_id', root);

    const tax = createTaxonomyLoader(root);

    let currentStep = 1;

    function setStep(n) {
      currentStep = n;

      steps.forEach((el) => {
        const s = Number(el.getAttribute('data-step'));
        el.style.display = (s === currentStep) ? '' : 'none';
      });

      stepTabs.forEach((el) => {
        const s = Number(el.getAttribute('data-step'));
        el.classList.toggle('active', s === currentStep);
        el.classList.toggle('done', s < currentStep);
      });

      if (prevBtn) prevBtn.disabled = currentStep <= 1;

      const isLast = currentStep >= 3;
      if (nextBtn) nextBtn.style.display = isLast ? 'none' : '';
      if (submitBtn) submitBtn.style.display = isLast ? '' : 'none';

      if (currentStep === 3) fillReview();
    }

    function validateStep1() {
      const container = $('.step[data-step="1"]', root);
      if (!container) return true;

      const requiredEls = $all('[required]', container).filter((el) => !el.disabled);
      let ok = true;

      requiredEls.forEach((el) => {
        if (!safeText(el.value)) {
          ok = false;
          el.classList.add('is-invalid');
        } else {
          el.classList.remove('is-invalid');
        }
      });

      return ok;
    }

    function taxonomyLabel(selectEl) {
      if (!selectEl) return '';
      const opt = selectEl.options && selectEl.options[selectEl.selectedIndex];
      const v = safeText(opt && opt.textContent);
      return v && v !== 'انتخاب کنید' ? v : '';
    }

    function updateLivePreview() {
      if (pvTitle && titleEl) pvTitle.textContent = safeText(titleEl.value) || 'نام کلاس';
      if (pvDesc && descEl) pvDesc.textContent = safeText(descEl.value);

      const taxParts = [
        taxonomyLabel(sectionSel),
        taxonomyLabel(gradeSel),
        taxonomyLabel(branchSel),
        taxonomyLabel(fieldSel),
        taxonomyLabel(subfieldSel),
        taxonomyLabel(subjectTypeSel),
        taxonomyLabel(subjectSel),
      ].filter(Boolean);

      if (pvTax) pvTax.textContent = taxParts.length ? `تاکسونومی: ${taxParts.join(' / ')}` : 'تاکسونومی: —';

      if (pvType && classroomType) {
        const type = safeText(classroomType.value);
        pvType.textContent = type ? `نوع کلاس: ${type === 'single' ? 'تکی' : (type === 'comprehensive' ? 'جامع' : type)}` : 'نوع کلاس: —';
      }

      if (pvActive && activeSwitch) {
        const on = !!activeSwitch.checked;
        pvActive.innerHTML = on ? '<i class="bi bi-broadcast"></i> فعال' : '<i class="bi bi-pause-circle"></i> غیرفعال';
      }

      if (pvCode && joinCodeInput) {
        const code = safeText(joinCodeInput.value);
        pvCode.innerHTML = code ? `<i class="bi bi-key"></i> کد: ${code}` : '<i class="bi bi-key"></i> کد: —';
      }
    }

    function fillReview() {
      if (!reviewBox) return;
      reviewBox.innerHTML = '';

      const rows = [
        ['نام کلاس', safeText(titleEl && titleEl.value)],
        ['مقطع', taxonomyLabel(sectionSel)],
        ['پایه', taxonomyLabel(gradeSel)],
        ['شاخه', taxonomyLabel(branchSel)],
        ['رشته', taxonomyLabel(fieldSel)],
        ['زیررشته', taxonomyLabel(subfieldSel)],
        ['نوع درس', taxonomyLabel(subjectTypeSel)],
        ['درس', taxonomyLabel(subjectSel)],
        ['نوع کلاس', (safeText(classroomType && classroomType.value) === 'single' ? 'تکی' : (safeText(classroomType && classroomType.value) === 'comprehensive' ? 'جامع' : safeText(classroomType && classroomType.value)))],
        ['وضعیت', (activeSwitch && activeSwitch.checked) ? 'فعال' : 'غیرفعال'],
        ['توضیحات', safeText(descEl && descEl.value)],
      ].filter((pair) => pair[1]);

      const ul = document.createElement('ul');
      ul.className = 'review-list';
      rows.forEach(([k, v]) => {
        const li = document.createElement('li');
        li.innerHTML = `<span class="k">${k}:</span> <span class="v">${v}</span>`;
        ul.appendChild(li);
      });
      reviewBox.appendChild(ul);
    }

    if (prevBtn) prevBtn.addEventListener('click', () => setStep(Math.max(1, currentStep - 1)));
    if (nextBtn) nextBtn.addEventListener('click', () => {
      if (currentStep === 1 && !validateStep1()) return;
      setStep(Math.min(3, currentStep + 1));
    });

    ['input', 'change'].forEach((ev) => {
      if (titleEl) titleEl.addEventListener(ev, updateLivePreview);
      if (descEl) descEl.addEventListener(ev, updateLivePreview);
      if (activeSwitch) activeSwitch.addEventListener(ev, updateLivePreview);
      if (classroomType) classroomType.addEventListener(ev, updateLivePreview);
    });

    if (genCodeBtn && joinCodeInput) {
      genCodeBtn.addEventListener('click', () => {
        joinCodeInput.value = randomJoinCode(6);
        updateLivePreview();
      });
    }

    function resetDownstream(from) {
      if (from === 'section') {
        resetSelect(gradeSel, 'انتخاب پایه');
        resetSelect(branchSel, 'انتخاب شاخه');
        resetSelect(fieldSel, 'انتخاب رشته');
        resetSelect(subfieldSel, 'انتخاب زیررشته');
        resetSelect(subjectTypeSel, 'انتخاب نوع درس');
        resetSelect(subjectSel, 'انتخاب درس');
      } else if (from === 'grade') {
        resetSelect(branchSel, 'انتخاب شاخه');
        resetSelect(fieldSel, 'انتخاب رشته');
        resetSelect(subfieldSel, 'انتخاب زیررشته');
        resetSelect(subjectTypeSel, 'انتخاب نوع درس');
        resetSelect(subjectSel, 'انتخاب درس');
      } else if (from === 'branch') {
        resetSelect(fieldSel, 'انتخاب رشته');
        resetSelect(subfieldSel, 'انتخاب زیررشته');
        resetSelect(subjectTypeSel, 'انتخاب نوع درس');
        resetSelect(subjectSel, 'انتخاب درس');
      } else if (from === 'field') {
        resetSelect(subfieldSel, 'انتخاب زیررشته');
        resetSelect(subjectTypeSel, 'انتخاب نوع درس');
        resetSelect(subjectSel, 'انتخاب درس');
      } else if (from === 'subjectType') {
        resetSelect(subjectSel, 'انتخاب درس');
      }
      updateLivePreview();
    }

    async function bootTaxonomy() {
      if (sectionSel) setSelectEnabled(sectionSel, false);
      resetDownstream('section');

      if (sectionSel && tax.cfg.sectionsUrl) {
        try {
          await tax.loadSections(sectionSel);
        } catch (e) {
          console.error(e);
        }
      }
      updateLivePreview();
    }

    if (sectionSel) sectionSel.addEventListener('change', async () => {
      resetDownstream('section');
      const sectionId = safeText(sectionSel.value);
      if (!sectionId) return;

      try { await tax.loadGrades(sectionId, gradeSel); } catch (e) { console.error(e); }
      updateLivePreview();
    });

    if (gradeSel) gradeSel.addEventListener('change', async () => {
      resetDownstream('grade');
      const gradeId = safeText(gradeSel.value);
      if (!gradeId) return;

      try { await tax.loadBranches(gradeId, branchSel); } catch (e) { console.error(e); }
      updateLivePreview();
    });

    if (branchSel) branchSel.addEventListener('change', async () => {
      resetDownstream('branch');
      const branchId = safeText(branchSel.value);
      if (!branchId) return;

      try { await tax.loadFields(branchId, fieldSel); } catch (e) { console.error(e); }
      updateLivePreview();
    });

    if (fieldSel) fieldSel.addEventListener('change', async () => {
      resetDownstream('field');
      const fieldId = safeText(fieldSel.value);
      if (!fieldId) return;

      const tasks = [];
      if (subfieldSel && tax.cfg.subfieldsTpl) tasks.push(tax.loadSubfields(fieldId, subfieldSel));
      if (subjectTypeSel && tax.cfg.subjectTypesTpl) tasks.push(tax.loadSubjectTypes(fieldId, subjectTypeSel));
      try { await Promise.all(tasks); } catch (e) { console.error(e); }
      updateLivePreview();
    });

    if (subjectTypeSel) subjectTypeSel.addEventListener('change', async () => {
      resetDownstream('subjectType');
      const stId = safeText(subjectTypeSel.value);
      if (!stId) return;

      try { await tax.loadSubjects(stId, subjectSel); } catch (e) { console.error(e); }
      updateLivePreview();
    });

    if (subjectSel) subjectSel.addEventListener('change', updateLivePreview);
    if (subfieldSel) subfieldSel.addEventListener('change', updateLivePreview);

    setStep(1);
    updateLivePreview();
    bootTaxonomy();

    window.addEventListener('beforeunload', () => tax.destroy(), { once: true });
  }

  function initEdit(root) {
    // Safe progressive enhancement: copy button + (optional) taxonomy prefill
    initShow(root);

    const tax = createTaxonomyLoader(root);

    const sectionSel = $('#section_id', root);
    const gradeSel = $('#grade_id', root);
    const branchSel = $('#branch_id', root);
    const fieldSel = $('#field_id', root);
    const subfieldSel = $('#subfield_id', root);
    const subjectTypeSel = $('#subject_type_id', root);
    const subjectSel = $('#subject_id', root);

    const initial = {
      section: root.dataset.initialSection || '',
      grade: root.dataset.initialGrade || '',
      branch: root.dataset.initialBranch || '',
      field: root.dataset.initialField || '',
      subfield: root.dataset.initialSubfield || '',
      subjectType: root.dataset.initialSubjectType || '',
      subject: root.dataset.initialSubject || '',
    };

    const hasTaxUI = !!(sectionSel && gradeSel && branchSel && fieldSel);
    if (!hasTaxUI) return;

    (async () => {
      try {
        await tax.loadSections(sectionSel);
        if (initial.section) sectionSel.value = initial.section;

        if (initial.section) {
          await tax.loadGrades(initial.section, gradeSel);
          if (initial.grade) gradeSel.value = initial.grade;
        }

        if (initial.grade) {
          await tax.loadBranches(initial.grade, branchSel);
          if (initial.branch) branchSel.value = initial.branch;
        }

        if (initial.branch) {
          await tax.loadFields(initial.branch, fieldSel);
          if (initial.field) fieldSel.value = initial.field;
        }

        if (initial.field) {
          const tasks = [];
          if (subfieldSel && tax.cfg.subfieldsTpl) tasks.push(tax.loadSubfields(initial.field, subfieldSel));
          if (subjectTypeSel && tax.cfg.subjectTypesTpl) tasks.push(tax.loadSubjectTypes(initial.field, subjectTypeSel));
          await Promise.all(tasks).catch(() => {});

          if (initial.subfield && subfieldSel) subfieldSel.value = initial.subfield;
          if (initial.subjectType && subjectTypeSel) subjectTypeSel.value = initial.subjectType;
        }

        if (initial.subjectType && subjectSel && tax.cfg.subjectsTpl) {
          await tax.loadSubjects(initial.subjectType, subjectSel);
          if (initial.subject) subjectSel.value = initial.subject;
        }
      } catch (e) {
        console.error(e);
      }
    })();

    window.addEventListener('beforeunload', () => tax.destroy(), { once: true });
  }

  // --------- Boot dispatcher ---------
  function boot() {
    const root = getPageRoot();
    const page = getPageName(root);

    switch (page) {
      case 'teacher-classes-index':
        initIndex(root);
        break;
      case 'teacher-classes-create':
        initCreate(root);
        break;
      case 'teacher-classes-show':
        initShow(root);
        break;
      case 'teacher-classes-edit':
        initEdit(root);
        break;
      default:
        break;
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
  } else {
    boot();
  }
})();