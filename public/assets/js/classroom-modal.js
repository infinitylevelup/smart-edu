/* ===========================================================
 * classroom-modal.js
 * مودال ساخت کلاس جدید (ساده + قابل نگهداری)
 * سازگار با exam-wizard.js و routes/teacher.php
 * =========================================================== */

window.createNewClassroom = async function () {
  if (typeof Swal === "undefined") {
    alert("SweetAlert2 لود نشده است.");
    return;
  }

  const endpoints = {
    sections: "/dashboard/teacher/exams/data/sections",
    grades: "/dashboard/teacher/exams/data/grades",
    branches: "/dashboard/teacher/exams/data/branches",
    fields: "/dashboard/teacher/exams/data/fields",
    subfields: "/dashboard/teacher/exams/data/subfields",
    subjectTypes: "/dashboard/teacher/exams/data/subject-types",
    subjects: "/dashboard/teacher/exams/data/subjects",
    storeClass: document
      .querySelector('meta[name="classroom-store-url"]')
      ?.content || "/dashboard/teacher/classes"
  };

  const getJSON = async (url) => {
    const res = await fetch(url, {
      headers: {
        Accept: "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },
    });
    if (!res.ok) throw new Error("network");
    return res.json();
  };

  const makeOptions = (items, placeholder, labelKey = "name_fa") => {
    let html = `<option value="">${placeholder}</option>`;
    items.forEach((i) => {
      const label = i[labelKey] || i.title_fa || "";
      html += `<option value="${i.id}" data-name="${label}">${label}</option>`;
    });
    return html;
  };

  // ---------- Load sections first ----------
  let sections = [];
  try {
    const s = await getJSON(endpoints.sections);
    sections = s.sections || [];
  } catch {
    Swal.fire("خطا", "دریافت مقاطع ممکن نیست", "error");
    return;
  }

  // ---------- Build modal HTML ----------
  const config = [
    {
      key: "section",
      label: "🎓 مقطع",
      id: "cc_section",
      placeholder: "مقطع را انتخاب کنید",
      load: async (state) => sections,
      dependsOn: [],
      required: true,
      labelKey: "name_fa",
    },
    {
      key: "grade",
      label: "📊 پایه",
      id: "cc_grade",
      placeholder: "پایه را انتخاب کنید",
      load: async (state) => {
        const qs = new URLSearchParams({ section_id: state.section });
        const g = await getJSON(`${endpoints.grades}?${qs}`);
        return g.grades || [];
      },
      dependsOn: ["section"],
      required: true,
      labelKey: "name_fa",
    },
    {
      key: "branch",
      label: "🧩 شاخه / رشته",
      id: "cc_branch",
      placeholder: "شاخه/رشته را انتخاب کنید",
      load: async (state) => {
        const qs = new URLSearchParams({
          section_id: state.section,
          grade_id: state.grade,
        });
        const b = await getJSON(`${endpoints.branches}?${qs}`);
        return b.branches || [];
      },
      dependsOn: ["section", "grade"],
      required: true,
      labelKey: "name_fa",
    },
    {
      key: "field",
      label: "🏭 زمینه آموزشی",
      id: "cc_field",
      placeholder: "زمینه را انتخاب کنید",
      load: async (state) => {
        const qs = new URLSearchParams({ branch_id: state.branch });
        const f = await getJSON(`${endpoints.fields}?${qs}`);
        return f.fields || [];
      },
      dependsOn: ["branch"],
      required: true,
      labelKey: "name_fa",
    },
    {
      key: "subfield",
      label: "🔬 زیررشته",
      id: "cc_subfield",
      placeholder: "زیررشته را انتخاب کنید",
      load: async (state) => {
        const qs = new URLSearchParams({ field_id: state.field });
        const sf = await getJSON(`${endpoints.subfields}?${qs}`);
        return sf.subfields || [];
      },
      dependsOn: ["field"],
      required: true,
      labelKey: "name_fa",
    },
    {
      key: "subject_type",
      label: "📚 دسته درسی",
      id: "cc_subject_type",
      placeholder: "دسته درسی را انتخاب کنید",
      load: async (state) => {
        const qs = new URLSearchParams({
          section_id: state.section,
          grade_id: state.grade,
          branch_id: state.branch,
          field_id: state.field,
          subfield_id: state.subfield,
        });
        const st = await getJSON(`${endpoints.subjectTypes}?${qs}`);
        return st.subjectTypes || st.subject_types || [];
      },
      dependsOn: ["subfield"],
      required: false,
      labelKey: "name_fa",
    },
    {
      key: "subject",
      label: "📖 درس",
      id: "cc_subject",
      placeholder: "درس را انتخاب کنید",
      load: async (state) => {
        const qs = new URLSearchParams({
          section_id: state.section,
          grade_id: state.grade,
          branch_id: state.branch,
          field_id: state.field,
          subfield_id: state.subfield,
          subject_type_id: state.subject_type || "",
        });
        const sub = await getJSON(`${endpoints.subjects}?${qs}`);
        return sub.subjects || [];
      },
      dependsOn: ["subject_type", "subfield"],
      required: true,
      labelKey: "title_fa",
    },
  ];

  const buildModalHTML = () => {
    return `
      <div style="text-align:right">
        ${config
          .map(
            (c) => `
          <label class="mb-2 fw-bold d-block mt-3">${c.label}</label>
          <select id="${c.id}" class="swal2-input" ${
              c.dependsOn.length ? "disabled" : ""
            }>
            ${
              c.dependsOn.length
                ? `<option value="">ابتدا مرحله قبل را انتخاب کنید</option>`
                : makeOptions(sections, c.placeholder, c.labelKey)
            }
          </select>
        `
          )
          .join("")}

        <label class="mb-2 fw-bold d-block mt-3">🏷️ نام کلاس</label>
        <input type="text" id="cc_title" class="swal2-input"
               placeholder="مثال: کلاس یازدهم شبکه - پایگاه داده">
      </div>
    `;
  };

  // ---------- Modal ----------
  Swal.fire({
    title: "ایجاد کلاس جدید",
    html: buildModalHTML(),
    showCancelButton: true,
    confirmButtonText: "ایجاد کلاس",
    cancelButtonText: "انصراف",
    reverseButtons: true,
    width: 650,

    didOpen: () => {
      const state = {};

      const setDisabledAndPlaceholder = (selectEl, text) => {
        selectEl.innerHTML = `<option value="">${text}</option>`;
        selectEl.disabled = true;
      };

      const loadSelect = async (c) => {
        const el = document.getElementById(c.id);

        // check deps
        for (const dep of c.dependsOn) {
          if (!state[dep]) {
            setDisabledAndPlaceholder(el, "ابتدا مرحله قبل را انتخاب کنید");
            return;
          }
        }

        el.disabled = true;
        el.innerHTML = `<option>در حال بارگذاری...</option>`;

        try {
          const items = await c.load(state);
          el.innerHTML = makeOptions(items, c.placeholder, c.labelKey);
          el.disabled = false;
        } catch {
          el.innerHTML = `<option value="">خطا در بارگذاری</option>`;
        }
      };

      const resetBelow = (key) => {
        const idx = config.findIndex((x) => x.key === key);
        config.slice(idx + 1).forEach((c) => {
          const el = document.getElementById(c.id);
          if (!el) return;
          el.innerHTML = `<option value="">ابتدا مرحله قبل را انتخاب کنید</option>`;
          el.disabled = true;
          state[c.key] = "";
        });
      };

      config.forEach((c) => {
        const el = document.getElementById(c.id);
        if (!el) return;

        el.addEventListener("change", async () => {
          state[c.key] = el.value;
          resetBelow(c.key);

          // load next select (اگر هست)
          const next = config.find(
            (n) => n.dependsOn.includes(c.key)
          );
          if (next) await loadSelect(next);
        });
      });
    },

    preConfirm: () => {
      const getVal = (id) => document.getElementById(id)?.value || "";
      const getName = (id) => {
        const el = document.getElementById(id);
        return (
          el?.options[el.selectedIndex]?.dataset?.name ||
          el?.options[el.selectedIndex]?.text ||
          ""
        );
      };

      const payload = {
        section_id: getVal("cc_section"),
        grade_id: getVal("cc_grade"),
        branch_id: getVal("cc_branch"),
        field_id: getVal("cc_field"),
        subfield_id: getVal("cc_subfield"),
        subject_type_id: getVal("cc_subject_type") || null,
        subject_id: getVal("cc_subject"),
        title: document.getElementById("cc_title")?.value.trim() || "",
        metadata: {
          section_name: getName("cc_section"),
          grade_name: getName("cc_grade"),
          branch_name: getName("cc_branch"),
          field_name: getName("cc_field"),
          subfield_name: getName("cc_subfield"),
          subject_type_name: getName("cc_subject_type"),
          subject_name: getName("cc_subject"),
        },
      };

      // validate required selects
      const missing = config.filter(c => c.required && !payload[`${c.key}_id`]);
      if (missing.length) {
        Swal.showValidationMessage("لطفاً همه موارد آموزشی ضروری را انتخاب کنید.");
        return false;
      }
      if (!payload.title) {
        Swal.showValidationMessage("نام کلاس الزامی است.");
        return false;
      }

      return payload;
    },
  }).then(async (result) => {
    if (!result.isConfirmed) return;

    Swal.fire({
      title: "در حال ایجاد کلاس...",
      allowOutsideClick: false,
      didOpen: () => Swal.showLoading(),
    });

    try {
      const fd = new FormData();
      Object.entries(result.value).forEach(([k, v]) => {
        if (k === "metadata") fd.append(k, JSON.stringify(v));
        else if (v !== null && v !== "") fd.append(k, v);
      });
      fd.append("classroom_type", "single"); // اگر خواستی هوشمندش می‌کنیم
      fd.append("is_active", 1);

      const res = await fetch(endpoints.storeClass, {
        method: "POST",
        headers: {
          "X-CSRF-TOKEN":
            document.querySelector('meta[name="csrf-token"]')?.content || "",
          "X-Requested-With": "XMLHttpRequest",
          Accept: "application/json",
        },
        body: fd,
      });

      const data = await res.json();
      Swal.close();

      if (data.success) {
        Swal.fire("✅ موفقیت", "کلاس ایجاد شد", "success").then(() => {
          if (typeof loadExistingClassrooms === "function") {
            loadExistingClassrooms();
          }
          if (data.classroom && typeof selectClassroom === "function") {
            setTimeout(() => {
              selectClassroom(
                {
                  target: document.querySelector(
                    `[data-classroom-id="${data.classroom.id}"]`
                  ),
                },
                data.classroom.id,
                data.classroom.title
              );
            }, 250);
          }
        });
      } else {
        Swal.fire("❌ خطا", data.message || "خطا در ایجاد کلاس", "error");
      }
    } catch (e) {
      console.error(e);
      Swal.close();
      Swal.fire("❌ خطای شبکه", "ارتباط با سرور مشکل دارد.", "error");
    }
  });
};
