/* -------------------------
   QUESTION WIZARD JS LOGIC
--------------------------*/

let currentStep = 1;
const totalSteps = 4;

function updateProgress() {
    const percent = (currentStep - 1) / (totalSteps - 1) * 100;
    document.getElementById("wizard_progress").style.width = percent + "%";
}

function showStep() {
    document.querySelectorAll('.step').forEach(step => step.classList.remove('active'));
    document.getElementById('step' + currentStep).classList.add('active');
    updateProgress();
}

window.nextStep = function () {
    if (currentStep < totalSteps) currentStep++;
    showStep();
};

window.prevStep = function () {
    if (currentStep > 1) currentStep--;
    showStep();
};

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('questionWizardForm');
    if (!form) return;

    let currentStep = 1;
    const maxStep = 4;

    const stepElems = Array.from(document.querySelectorAll('.qw-step'));
    const stepContents = Array.from(document.querySelectorAll('.qw-step-content'));
    const progressFill = document.getElementById('qwProgressFill');

    const btnPrev = document.getElementById('qwPrevBtn');
    const btnNext = document.getElementById('qwNextBtn');
    const btnSubmit = document.getElementById('qwSubmitBtn');

    const typeSelect = document.getElementById('qwQuestionType');
    const mcqBlock   = document.getElementById('qwMcqBlock');
    const tfBlock    = document.getElementById('qwTfBlock');
    const fillBlock  = document.getElementById('qwFillBlock');
    const essayInfo  = document.getElementById('qwEssayInfo');
    const addFillBtn = document.getElementById('qwAddFillAnswer');
    const fillContainer = document.getElementById('qwFillContainer');

    const addLinkBtn = document.getElementById('qwAddLink');
    const linksContainer = document.getElementById('qwLinksContainer');

    const subjectSelect = document.getElementById('qwSubjectSelect');
    const scoreInput = document.getElementById('qwScoreInput');
    const isActiveInput = document.getElementById('qwIsActive');
    const contentInput = document.getElementById('qwContent');
    const explanationInput = document.getElementById('qwExplanation');

    const previewRoot = document.getElementById('qwPreview');
    const pvTypeBadge = document.getElementById('qwPreviewTypeBadge');
    const pvScore     = document.getElementById('qwPreviewScore');
    const pvStatus    = document.getElementById('qwPreviewStatus');
    const pvContent   = document.getElementById('qwPreviewContent');
    const pvSubject   = document.getElementById('qwPreviewSubject');
    const pvAnswers   = document.getElementById('qwPreviewAnswersInner');
    const pvExplain   = document.getElementById('qwPreviewExplanation');
    const pvLinks     = document.getElementById('qwPreviewLinks');

    /* ---------------------------
     * Helpers: Steps
     * --------------------------- */
    function updateSteps() {
        stepElems.forEach(step => {
            const stepNo = parseInt(step.dataset.step, 10);
            step.classList.remove('active', 'completed');

            if (stepNo === currentStep) {
                step.classList.add('active');
            } else if (stepNo < currentStep) {
                step.classList.add('completed');
            }
        });

        stepContents.forEach(c => {
            const stepNo = parseInt(c.dataset.step, 10);
            c.classList.toggle('active', stepNo === currentStep);
        });

        if (progressFill) {
            const percent = (currentStep - 1) / (maxStep - 1) * 100;
            progressFill.style.width = `${percent}%`;
        }

        if (btnPrev) btnPrev.disabled = currentStep === 1;
        if (btnNext) btnNext.style.display = currentStep < maxStep ? 'inline-flex' : 'none';
        if (btnSubmit) btnSubmit.style.display = currentStep === maxStep ? 'inline-flex' : 'none';
    }

    function goToStep(step) {
        if (step < 1 || step > maxStep) return;
        currentStep = step;
        updateSteps();
        updatePreview();
    }

    /* ---------------------------
     * Simple per-step validation
     * --------------------------- */
    function validateCurrentStep() {
        // فقط حداقل‌های ضروری، بقیه را به بک‌اند می‌سپاریم
        if (currentStep === 1) {
            // اگر آزمون چنددرسی است، انتخاب درس را چک کن
            if (subjectSelect && subjectSelect.options.length > 0) {
                if (!subjectSelect.value) {
                    alert('برای سوال در آزمون جامع، انتخاب درس الزامی است.');
                    return false;
                }
            }
        }

        if (currentStep === 2) {
            if (!contentInput || !typeSelect) return true;
            const content = contentInput.value.trim();
            const typeVal = typeSelect.value;

            if (!content) {
                alert('متن سوال را وارد کن.');
                contentInput.focus();
                return false;
            }
            if (!typeVal) {
                alert('نوع سوال را انتخاب کن.');
                typeSelect.focus();
                return false;
            }
        }

        if (currentStep === 3 && typeSelect) {
            const typeVal = typeSelect.value;

            if (typeVal === 'mcq') {
                const optInputs = form.querySelectorAll('input[name^="options["]');
                let anyEmpty = false;
                optInputs.forEach(inp => {
                    if (!inp.value.trim()) anyEmpty = true;
                });
                const checked = form.querySelector('input[name="correct_answer[correct_option]"]:checked');
                if (anyEmpty) {
                    alert('همه گزینه‌های سوال تستی را پر کن.');
                    return false;
                }
                if (!checked) {
                    alert('گزینه صحیح سوال تستی را انتخاب کن.');
                    return false;
                }
            }

            if (typeVal === 'true_false') {
                const checked = form.querySelector('input[name="correct_answer[value]"]:checked');
                if (!checked) {
                    alert('برای سوال درست/نادرست، جواب صحیح را انتخاب کن.');
                    return false;
                }
            }

            if (typeVal === 'fill_blank') {
                const vals = Array.from(
                    form.querySelectorAll('input[name="correct_answer[values][]"]')
                ).map(i => i.value.trim()).filter(Boolean);

                if (!vals.length) {
                    alert('برای سوال جای خالی، حداقل یک جواب صحیح وارد کن.');
                    return false;
                }
            }
        }

        return true;
    }

    /* ---------------------------
     * Answer type blocks
     * --------------------------- */
    function toggleAnswerBlocks() {
        const typeVal = typeSelect ? typeSelect.value : '';

        if (mcqBlock) mcqBlock.style.display = typeVal === 'mcq' ? 'block' : 'none';
        if (tfBlock)  tfBlock.style.display  = typeVal === 'true_false' ? 'block' : 'none';
        if (fillBlock) fillBlock.style.display = typeVal === 'fill_blank' ? 'block' : 'none';
        if (essayInfo) essayInfo.style.display = typeVal === 'essay' ? 'block' : 'none';

        // Disable irrelevant inputs so required_if در بک‌اند به‌درستی عمل کند
        if (mcqBlock) {
            mcqBlock.querySelectorAll('input').forEach(el => {
                el.disabled = (typeVal !== 'mcq');
            });
        }
        if (tfBlock) {
            tfBlock.querySelectorAll('input').forEach(el => {
                el.disabled = (typeVal !== 'true_false');
            });
        }
        if (fillBlock) {
            fillBlock.querySelectorAll('input').forEach(el => {
                el.disabled = (typeVal !== 'fill_blank');
            });
        }

        updatePreview();
    }

    /* ---------------------------
     * Dynamic fill blanks
     * --------------------------- */
    if (addFillBtn && fillContainer) {
        addFillBtn.addEventListener('click', () => {
            const count = fillContainer.children.length + 1;
            const wrapper = document.createElement('div');
            wrapper.className = 'input-group mb-2';
            wrapper.innerHTML = `
                <span class="input-group-text">جواب ${count}</span>
                <input type="text"
                       name="correct_answer[values][]"
                       class="form-control qw-input">
            `;
            fillContainer.appendChild(wrapper);
            toggleAnswerBlocks();
        });
    }

    /* ---------------------------
     * Dynamic resource links
     * --------------------------- */
    let linkIndex = 0;

    function createLinkRow(title = '', url = '') {
        const row = document.createElement('div');
        row.className = 'qw-link-row';
        row.dataset.index = String(linkIndex);

        row.innerHTML = `
            <div class="qw-link-row-inner">
                <input type="text"
                       name="resource_links[${linkIndex}][title]"
                       class="form-control qw-input"
                       placeholder="عنوان لینک"
                       value="${title.replace(/"/g, '&quot;')}">

                <input type="url"
                       name="resource_links[${linkIndex}][url]"
                       class="form-control qw-input"
                       placeholder="https://example.com"
                       value="${url.replace(/"/g, '&quot;')}">

                <button type="button"
                        class="btn btn-outline-danger btn-sm qw-link-remove"
                        aria-label="حذف لینک">
                    <i class="bi bi-x"></i>
                </button>
            </div>
        `;

        const removeBtn = row.querySelector('.qw-link-remove');
        removeBtn.addEventListener('click', () => {
            row.remove();
            updatePreview();
        });

        linkIndex++;
        return row;
    }

    if (addLinkBtn && linksContainer) {
        addLinkBtn.addEventListener('click', () => {
            const row = createLinkRow();
            linksContainer.appendChild(row);
            updatePreview();
        });
    }

    /* ---------------------------
     * Live Preview
     * --------------------------- */
    function humanQuestionType(typeVal) {
        switch (typeVal) {
            case 'mcq': return 'تستی (چهارگزینه‌ای)';
            case 'true_false': return 'درست / نادرست';
            case 'fill_blank': return 'جای خالی';
            case 'essay': return 'تشریحی';
            default: return '—';
        }
    }

    function updatePreview() {
        if (!previewRoot) return;

        const typeVal = typeSelect ? typeSelect.value : '';
        const scoreVal = scoreInput ? (scoreInput.value || '0') : '0';
        const isActive = isActiveInput ? isActiveInput.checked : true;

        if (pvTypeBadge) {
            pvTypeBadge.textContent = `نوع سوال: ${humanQuestionType(typeVal)}`;
        }
        if (pvScore) {
            pvScore.textContent = `امتیاز: ${scoreVal}`;
        }
        if (pvStatus) {
            pvStatus.textContent = `وضعیت: ${isActive ? 'فعال' : 'غیرفعال'}`;
        }

        if (pvContent && contentInput) {
            const txt = contentInput.value.trim();
            pvContent.textContent = txt || 'هنوز متنی برای سوال وارد نشده است.';
        }

        if (subjectSelect && pvSubject) {
            if (subjectSelect.value) {
                const opt = subjectSelect.options[subjectSelect.selectedIndex];
                const title = opt.dataset.title || opt.textContent;
                pvSubject.textContent = title;
            } else {
                pvSubject.textContent = 'انتخاب نشده';
            }
        }

        if (pvAnswers) {
            if (!typeVal) {
                pvAnswers.textContent = 'نوع سوال را انتخاب کن تا پیش‌نمایش پاسخ‌ها نمایش داده شود.';
            } else if (typeVal === 'mcq') {
                const opts = ['a','b','c','d'].map(key => {
                    const input = form.querySelector(`input[name="options[${key}]"]`);
                    const text = input ? input.value.trim() : '';
                    const radio = form.querySelector(`input[name="correct_answer[correct_option]"][value="${key}"]`);
                    const isCorrect = radio && radio.checked;
                    if (!text) return null;
                    return { key, text, isCorrect };
                }).filter(Boolean);

                if (!opts.length) {
                    pvAnswers.textContent = 'گزینه‌ای ثبت نشده است.';
                } else {
                    const list = document.createElement('ul');
                    list.className = 'mb-0 ps-3';
                    opts.forEach(o => {
                        const li = document.createElement('li');
                        li.textContent = `${o.key.toUpperCase()}) ${o.text}`;
                        if (o.isCorrect) {
                            li.style.fontWeight = '800';
                            li.style.color = '#15803d';
                        }
                        list.appendChild(li);
                    });
                    pvAnswers.innerHTML = '';
                    pvAnswers.appendChild(list);
                }

            } else if (typeVal === 'true_false') {
                const checked = form.querySelector('input[name="correct_answer[value]"]:checked');
                if (!checked) {
                    pvAnswers.textContent = 'جواب صحیح (درست/نادرست) هنوز انتخاب نشده است.';
                } else {
                    const val = checked.value === '1' ? 'درست' : 'نادرست';
                    pvAnswers.textContent = `جواب صحیح: ${val}`;
                }

            } else if (typeVal === 'fill_blank') {
                const vals = Array.from(
                    form.querySelectorAll('input[name="correct_answer[values][]"]')
                )
                    .map(i => i.value.trim())
                    .filter(Boolean);

                if (!vals.length) {
                    pvAnswers.textContent = 'هنوز هیچ جواب صحیحی برای جای خالی ثبت نشده است.';
                } else {
                    pvAnswers.textContent = `جواب‌های قابل قبول: ${vals.join(' ، ')}`;
                }

            } else if (typeVal === 'essay') {
                pvAnswers.textContent = 'سوال تشریحی است؛ جواب توسط هنرجو نوشته می‌شود.';
            }
        }

        if (pvExplain && explanationInput) {
            const txt = explanationInput.value.trim();
            pvExplain.textContent = txt || 'هنوز توضیحی ثبت نشده است.';
        }

        if (pvLinks && linksContainer) {
            const rows = linksContainer.querySelectorAll('.qw-link-row');
            if (!rows.length) {
                pvLinks.textContent = 'لینکی ثبت نشده است.';
            } else {
                pvLinks.innerHTML = '';
                rows.forEach(row => {
                    const titleInput = row.querySelector('input[name*="[title]"]');
                    const urlInput = row.querySelector('input[name*="[url]"]');
                    const t = titleInput ? titleInput.value.trim() : '';
                    const u = urlInput ? urlInput.value.trim() : '';
                    if (!t && !u) return;

                    const pill = document.createElement('span');
                    pill.className = 'qw-link-pill';
                    pill.textContent = t || u || 'لینک بدون عنوان';
                    pvLinks.appendChild(pill);
                });
                if (!pvLinks.children.length) {
                    pvLinks.textContent = 'لینکی ثبت نشده است.';
                }
            }
        }
    }

    /* ---------------------------
     * Events
     * --------------------------- */
    if (btnPrev) {
        btnPrev.addEventListener('click', () => {
            if (currentStep > 1) {
                currentStep--;
                updateSteps();
            }
        });
    }

    if (btnNext) {
        btnNext.addEventListener('click', () => {
            if (!validateCurrentStep()) return;
            if (currentStep < maxStep) {
                currentStep++;
                updateSteps();
            }
        });
    }

    if (typeSelect) {
        typeSelect.addEventListener('change', () => {
            toggleAnswerBlocks();
            updatePreview();
        });
    }

    // Inputs that affect preview
    [subjectSelect, scoreInput, isActiveInput, contentInput, explanationInput].forEach(el => {
        if (!el) return;
        const evt = el.tagName === 'TEXTAREA' || el.tagName === 'INPUT' ? 'input' : 'change';
        el.addEventListener(evt, updatePreview);
    });

    // Delegate for dynamic link rows to update preview
    if (linksContainer) {
        linksContainer.addEventListener('input', e => {
            if (e.target.matches('input')) {
                updatePreview();
            }
        });
    }

    // Also preview answer changes
    if (form) {
        form.addEventListener('input', e => {
            if (e.target.name && e.target.name.startsWith('options[')) {
                updatePreview();
            }
            if (e.target.name && e.target.name.startsWith('correct_answer')) {
                updatePreview();
            }
        });
        form.addEventListener('change', e => {
            if (e.target.name && e.target.name.startsWith('correct_answer')) {
                updatePreview();
            }
        });
    }

    // Initial state
    toggleAnswerBlocks();
    updateSteps();
    updatePreview();
});


/* QUESTION TYPE BLOCK HANDLER */
function initTypeBlocks() {
    const typeSelect = document.getElementById('question_type');
    if (!typeSelect) return;

    function refresh() {
        const type = typeSelect.value;

        document.getElementById('block_mcq').style.display  = (type === 'mcq') ? 'block' : 'none';
        document.getElementById('block_tf').style.display   = (type === 'true_false') ? 'block' : 'none';
        document.getElementById('block_fill').style.display = (type === 'fill_blank') ? 'block' : 'none';
        document.getElementById('block_essay').style.display = (type === 'essay') ? 'block' : 'none';
    }

    typeSelect.addEventListener('change', refresh);
    refresh();
}

/* ADD FILL-ANSWER FIELD */
window.addFillAnswer = function () {
    const container = document.getElementById('fill_container');
    const idx = container.children.length + 1;

    const field = document.createElement('div');
    field.className = "input-group mb-2";
    field.innerHTML = `
        <span class="input-group-text">جواب ${idx}</span>
        <input type="text" name="correct_answer[values][]" class="form-control input-soft">
    `;

    container.appendChild(field);
};

/* ADD EDUCATIONAL LINK */
window.addLink = function () {
    const container = document.getElementById('links_container');
    const index = container.children.length;

    const item = document.createElement('div');
    item.className = "link-item";

    item.innerHTML = `
        <div class="d-flex justify-content-between mb-2">
            <strong>لینک ${index + 1}</strong>
            <button type="button" class="btn btn-sm btn-danger remove-link">حذف</button>
        </div>
        <input type="text" name="resource_links[${index}][title]" class="form-control input-soft mb-2" placeholder="عنوان لینک">
        <input type="url" name="resource_links[${index}][url]" class="form-control input-soft" placeholder="آدرس لینک">
    `;

    container.appendChild(item);

    item.querySelector('.remove-link').addEventListener('click', () => item.remove());
};
