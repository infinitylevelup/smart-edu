{{-- resources/views/dashboard/teacher/exams/edit-script.blade.php --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ====== ELEMENTS ======
        const form = document.querySelector('form');
        const titleInput = document.querySelector('input[name="title"]');
        const descriptionTextarea = document.querySelector('textarea[name="description"]');
        const durationInput = document.querySelector('input[name="duration"]');
        const startAtInput = document.querySelector('input[name="start_at"]');
        const publishSwitch = document.getElementById('publishSwitch');
        
        // Classroom, Subject, Level selects (if they exist)
        const classroomSelect = document.querySelector('select[name="classroom_id"]');
        const subjectSelect = document.querySelector('select[name="subject"]');
        const levelSelect = document.querySelector('select[name="level"]');
        
        // ====== LIVE PREVIEW ELEMENTS ======
        const livePreviewTitle = document.getElementById('livePreviewTitle');
        const livePreviewType = document.getElementById('livePreviewType');
        const livePreviewDuration = document.getElementById('livePreviewDuration');
        const livePreviewStatus = document.querySelector('.preview-item:nth-child(4) .preview-value span');
        
        // ====== TYPE BADGE MAPPING ======
        const typeBadges = {
            'public': {
                text: 'آزاد',
                class: 'type-public-badge',
                icon: 'fas fa-globe'
            },
            'class': {
                text: 'کلاسی',
                class: 'type-class-badge',
                icon: 'fas fa-people-group'
            },
            'class_single': {
                text: 'کلاسی تک‌درس',
                class: 'type-class-single-badge',
                icon: 'fas fa-book'
            },
            'class_comprehensive': {
                text: 'کلاسی جامع',
                class: 'type-class-comprehensive-badge',
                icon: 'fas fa-books'
            }
        };
        
        // ====== GET CURRENT EXAM TYPE ======
        function getCurrentExamType() {
            // Get type from hidden input or data attribute
            const typeElement = document.querySelector('.exam-type-display');
            if (typeElement) {
                return typeElement.dataset.type;
            }
            return 'public'; // default
        }
        
        // ====== UPDATE LIVE PREVIEW ======
        function updateLivePreview() {
            // Title
            if (titleInput && titleInput.value.trim()) {
                livePreviewTitle.textContent = titleInput.value;
            }
            
            // Exam Type (readonly - display only)
            const currentType = getCurrentExamType();
            if (currentType && typeBadges[currentType]) {
                livePreviewType.innerHTML = `
                    <span class="type-badge ${typeBadges[currentType].class}">
                        <i class="${typeBadges[currentType].icon}"></i>
                        ${typeBadges[currentType].text}
                    </span>
                `;
            }
            
            // Duration
            if (durationInput && durationInput.value) {
                livePreviewDuration.textContent = durationInput.value + ' دقیقه';
            }
            
            // Publish Status
            if (publishSwitch) {
                if (publishSwitch.checked) {
                    livePreviewStatus.textContent = 'منتشر شده';
                    livePreviewStatus.style.color = '#00D4AA';
                } else {
                    livePreviewStatus.textContent = 'پیش‌نویس';
                    livePreviewStatus.style.color = '#FF9F43';
                }
            }
            
            // Update other preview info if needed
            updateAdditionalPreviewInfo();
        }
        
        // ====== UPDATE ADDITIONAL PREVIEW INFO ======
        function updateAdditionalPreviewInfo() {
            // Classroom
            if (classroomSelect && classroomSelect.value) {
                const classroomOption = classroomSelect.options[classroomSelect.selectedIndex];
                if (classroomOption) {
                    const previewClassroom = document.getElementById('livePreviewClassroom');
                    if (previewClassroom) {
                        previewClassroom.textContent = classroomOption.text;
                    }
                }
            }
            
            // Subject & Level
            if (subjectSelect && subjectSelect.value) {
                const previewSubject = document.getElementById('livePreviewSubject');
                if (previewSubject) {
                    previewSubject.textContent = subjectSelect.options[subjectSelect.selectedIndex].text;
                }
            }
            
            if (levelSelect && levelSelect.value) {
                const previewLevel = document.getElementById('livePreviewLevel');
                if (previewLevel) {
                    previewLevel.textContent = levelSelect.options[levelSelect.selectedIndex].text;
                }
            }
        }
        
        // ====== FORM VALIDATION ======
        function validateForm() {
            let isValid = true;
            const errors = [];
            
            // Validate Title
            if (!titleInput.value.trim()) {
                errors.push('عنوان آزمون الزامی است');
                titleInput.style.borderColor = 'var(--danger)';
                isValid = false;
            } else {
                titleInput.style.borderColor = 'var(--gray-border)';
            }
            
            // Validate Duration
            if (!durationInput.value || parseInt(durationInput.value) < 1) {
                errors.push('مدت آزمون باید یک عدد مثبت باشد');
                durationInput.style.borderColor = 'var(--danger)';
                isValid = false;
            } else {
                durationInput.style.borderColor = 'var(--gray-border)';
            }
            
            // Validate specific fields based on exam type
            const currentType = getCurrentExamType();
            
            if (['class', 'class_single', 'class_comprehensive'].includes(currentType)) {
                if (classroomSelect && !classroomSelect.value) {
                    errors.push('برای آزمون کلاسی، انتخاب کلاس الزامی است');
                    if (classroomSelect) classroomSelect.style.borderColor = 'var(--danger)';
                    isValid = false;
                } else if (classroomSelect) {
                    classroomSelect.style.borderColor = 'var(--gray-border)';
                }
            }
            
            if (currentType === 'public') {
                if (subjectSelect && !subjectSelect.value) {
                    errors.push('برای آزمون آزاد، انتخاب درس الزامی است');
                    if (subjectSelect) subjectSelect.style.borderColor = 'var(--danger)';
                    isValid = false;
                } else if (subjectSelect) {
                    subjectSelect.style.borderColor = 'var(--gray-border)';
                }
                
                if (levelSelect && !levelSelect.value) {
                    errors.push('برای آزمون آزاد، انتخاب سطح الزامی است');
                    if (levelSelect) levelSelect.style.borderColor = 'var(--danger)';
                    isValid = false;
                } else if (levelSelect) {
                    levelSelect.style.borderColor = 'var(--gray-border)';
                }
            }
            
            // Show errors if any
            if (errors.length > 0) {
                showValidationErrors(errors);
            } else {
                hideValidationErrors();
            }
            
            return isValid;
        }
        
        // ====== SHOW VALIDATION ERRORS ======
        function showValidationErrors(errors) {
            let errorsContainer = document.getElementById('validationErrorsContainer');
            
            if (!errorsContainer) {
                errorsContainer = document.createElement('div');
                errorsContainer.id = 'validationErrorsContainer';
                errorsContainer.className = 'validation-errors';
                
                const title = document.createElement('div');
                title.className = 'validation-title';
                title.innerHTML = '<i class="fas fa-exclamation-triangle"></i> خطاهای اعتبارسنجی';
                
                const list = document.createElement('ul');
                list.className = 'validation-list';
                
                errorsContainer.appendChild(title);
                errorsContainer.appendChild(list);
                
                const formContainer = document.querySelector('.main-form-card');
                if (formContainer) {
                    formContainer.insertBefore(errorsContainer, formContainer.firstChild);
                }
            }
            
            const list = errorsContainer.querySelector('.validation-list');
            list.innerHTML = '';
            
            errors.forEach(error => {
                const li = document.createElement('li');
                li.innerHTML = `<i class="fas fa-times-circle" style="color: var(--warning); margin-left: 8px;"></i> ${error}`;
                list.appendChild(li);
            });
            
            // Scroll to errors
            errorsContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
            
            // Add vibration on mobile
            if (navigator.vibrate) {
                navigator.vibrate(200);
            }
        }
        
        // ====== HIDE VALIDATION ERRORS ======
        function hideValidationErrors() {
            const errorsContainer = document.getElementById('validationErrorsContainer');
            if (errorsContainer) {
                errorsContainer.remove();
            }
        }
        
        // ====== INPUT HOVER EFFECTS ======
        function setupInputEffects() {
            const inputs = document.querySelectorAll('.form-input-edit, .form-select-edit, .form-textarea-edit');
            
            inputs.forEach(input => {
                // Hover effect
                input.addEventListener('mouseenter', function() {
                    this.style.boxShadow = 'var(--shadow-md)';
                    this.style.transform = 'translateY(-2px)';
                });
                
                input.addEventListener('mouseleave', function() {
                    if (this !== document.activeElement) {
                        this.style.boxShadow = 'var(--shadow-sm)';
                        this.style.transform = 'translateY(0)';
                    }
                });
                
                // Focus effect
                input.addEventListener('focus', function() {
                    this.style.boxShadow = '0 0 0 4px rgba(123, 104, 238, 0.15)';
                    this.style.transform = 'translateY(-2px)';
                });
                
                input.addEventListener('blur', function() {
                    this.style.boxShadow = 'var(--shadow-sm)';
                    this.style.transform = 'translateY(0)';
                });
            });
        }
        
        // ====== BUTTON EFFECTS ======
        function setupButtonEffects() {
            const buttons = document.querySelectorAll('.btn-action-edit, .btn-delete');
            
            buttons.forEach(button => {
                button.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-3px)';
                    this.style.boxShadow = 'var(--shadow-md)';
                });
                
                button.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = 'var(--shadow-sm)';
                });
                
                button.addEventListener('mousedown', function() {
                    this.style.transform = 'scale(0.98)';
                });
                
                button.addEventListener('mouseup', function() {
                    this.style.transform = 'translateY(-3px)';
                });
                
                // Touch feedback
                button.addEventListener('touchstart', function() {
                    if (navigator.vibrate) {
                        navigator.vibrate(20);
                    }
                    this.style.transform = 'scale(0.98)';
                });
                
                button.addEventListener('touchend', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        }
        
        // ====== FORM SUBMIT HANDLER ======
        function setupFormSubmit() {
            if (!form) return;
            
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validate form
                if (!validateForm()) {
                    return false;
                }
                
                // Confirm publish if switching to published
                if (publishSwitch && publishSwitch.checked && 
                    !publishSwitch.hasAttribute('data-was-checked')) {
                    if (!confirm('آیا از انتشار این آزمون اطمینان دارید؟ پس از انتشار، دانش‌آموزان می‌توانند در آزمون شرکت کنند.')) {
                        return false;
                    }
                }
                
                // Show loading state
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> در حال ذخیره...';
                    submitBtn.disabled = true;
                    
                    // Re-enable after 3 seconds in case of error
                    setTimeout(() => {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }, 3000);
                }
                
                // Submit form
                this.submit();
            });
        }
        
        // ====== DELETE CONFIRMATION ======
        function setupDeleteConfirmation() {
            const deleteForm = document.querySelector('.danger-zone-edit form');
            if (deleteForm) {
                deleteForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    // Custom confirmation dialog
                    const confirmDelete = confirm(
                        '⚠️ هشدار!\n\n' +
                        'آیا مطمئن هستید که می‌خواهید این آزمون را حذف کنید؟\n\n' +
                        'این عمل:\n' +
                        '• تمام سوالات آزمون را حذف می‌کند\n' +
                        '• نتایج دانش‌آموزان را پاک می‌کند\n' +
                        '• غیرقابل بازگشت است\n\n' +
                        'برای تایید، عبارت "حذف آزمون" را در کادر زیر وارد کنید:'
                    );
                    
                    if (confirmDelete) {
                        const userInput = prompt('لطفاً عبارت "حذف آزمون" را وارد کنید:');
                        if (userInput === 'حذف آزمون') {
                            this.submit();
                        } else {
                            alert('عبارت وارد شده صحیح نیست. عملیات حذف لغو شد.');
                        }
                    }
                });
            }
        }
        
        // ====== INITIALIZE ======
        function initialize() {
            // Set up input effects
            setupInputEffects();
            
            // Set up button effects
            setupButtonEffects();
            
            // Set up form submission
            setupFormSubmit();
            
            // Set up delete confirmation
            setupDeleteConfirmation();
            
            // Initial update of live preview
            updateLivePreview();
            
            // Add event listeners for live preview updates
            const previewInputs = [
                titleInput,
                descriptionTextarea,
                durationInput,
                startAtInput,
                publishSwitch,
                classroomSelect,
                subjectSelect,
                levelSelect
            ];
            
            previewInputs.forEach(input => {
                if (input) {
                    if (input.type === 'checkbox') {
                        input.addEventListener('change', updateLivePreview);
                    } else {
                        input.addEventListener('input', updateLivePreview);
                        input.addEventListener('change', updateLivePreview);
                    }
                }
            });
            
            // Initialize exam type display
            const currentType = getCurrentExamType();
            const examTypeDisplay = document.querySelector('.exam-type-display');
            if (examTypeDisplay && typeBadges[currentType]) {
                examTypeDisplay.innerHTML = `
                    <span>نوع آزمون (غیرقابل تغییر)</span>
                    <span class="type-badge ${typeBadges[currentType].class}">
                        <i class="${typeBadges[currentType].icon}"></i>
                        ${typeBadges[currentType].text}
                    </span>
                `;
            }
            
            // Add animation to form elements
            setTimeout(() => {
                const formGroups = document.querySelectorAll('.form-group-edit');
                formGroups.forEach((group, index) => {
                    group.style.animationDelay = `${index * 0.05}s`;
                    group.style.animation = 'fadeIn 0.5s ease-out forwards';
                    group.style.opacity = '0';
                });
            }, 300);
        }
        
        // ====== START EVERYTHING ======
        initialize();
        
        console.log('✅ Exam edit page initialized successfully');
    });
</script>