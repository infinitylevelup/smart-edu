{{-- resources/views/dashboard/teacher/exams/edit-style.blade.php --}}
<style>
    /* ====== THEME VARIABLES ====== */
    :root {
        --primary: #7B68EE;
        --primary-light: rgba(123, 104, 238, 0.1);
        --primary-gradient: linear-gradient(135deg, #7B68EE, #FF6B9D);
        --secondary: #FF6B9D;
        --accent: #00D4AA;
        --accent-light: rgba(0, 212, 170, 0.1);
        --light: #ffffff;
        --dark: #2D3047;
        --dark-light: #3A3F6D;
        --gray: #8A8D9B;
        --gray-light: #B0B3C1;
        --gray-border: #D1D5E0;
        --light-gray: #F8F9FF;
        --danger: #FF4757;
        --warning: #FF9F43;
        --success: #00D4AA;
        
        --shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.08);
        --shadow-md: 0 8px 20px rgba(0, 0, 0, 0.12);
        --shadow-lg: 0 12px 30px rgba(0, 0, 0, 0.16);
        --shadow-xl: 0 20px 40px rgba(0, 0, 0, 0.2);
        --radius-xl: 24px;
        --radius-lg: 20px;
        --radius-md: 16px;
        --radius-sm: 12px;
    }

    /* ====== MAIN CONTAINER ====== */
    .edit-exam-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px 15px 80px;
        animation: fadeIn 0.6s ease both;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes slideInRight {
        from { transform: translateX(30px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    @keyframes slideInLeft {
        from { transform: translateX(-30px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    /* ====== HEADER ====== */
    .page-header-edit {
        background: var(--light);
        border-radius: var(--radius-xl);
        padding: 25px 30px;
        box-shadow: var(--shadow-lg);
        margin-bottom: 30px;
        border: 2px solid rgba(123, 104, 238, 0.08);
        position: relative;
        overflow: hidden;
        animation: slideInRight 0.5s ease-out;
    }

    .page-header-edit::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, rgba(123, 104, 238, 0.05), transparent);
        border-radius: 0 var(--radius-xl) 0 0;
    }

    .header-content-edit {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
        position: relative;
        z-index: 2;
    }

    .header-title-edit h1 {
        font-weight: 900;
        font-size: 1.8rem;
        color: var(--dark);
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .header-title-edit h1::before {
        content: '';
        width: 8px;
        height: 40px;
        background: var(--primary-gradient);
        border-radius: 10px;
    }

    .header-subtitle-edit {
        color: var(--gray);
        font-size: 1.05rem;
        line-height: 1.8;
    }

    /* ====== FORM CONTAINER ====== */
    .form-container-edit {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 25px;
    }

    @media (max-width: 992px) {
        .form-container-edit {
            grid-template-columns: 1fr;
        }
    }

    /* ====== MAIN FORM ====== */
    .main-form-card {
        background: var(--light);
        border-radius: var(--radius-xl);
        padding: 30px;
        box-shadow: var(--shadow-md);
        border: 2px solid rgba(123, 104, 238, 0.08);
        animation: slideInLeft 0.5s ease-out;
    }

    .form-section-title {
        font-weight: 900;
        font-size: 1.3rem;
        color: var(--dark);
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 12px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--light-gray);
    }

    .form-section-title i {
        color: var(--primary);
        background: var(--primary-light);
        width: 45px;
        height: 45px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* ====== FORM GROUPS ====== */
    .form-group-edit {
        margin-bottom: 25px;
    }

    .form-label-edit {
        color: var(--dark);
        font-weight: 800;
        font-size: 0.95rem;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-label-edit .required {
        color: var(--danger);
        font-size: 1.2rem;
    }

    /* ====== INPUT STYLES ====== */
    .form-input-edit, .form-select-edit, .form-textarea-edit {
        width: 100%;
        padding: 16px 20px;
        border: 2px solid var(--gray-border);
        border-radius: var(--radius-md);
        background: var(--light);
        color: var(--dark);
        font-weight: 700;
        transition: all 0.3s;
        font-size: 1rem;
        box-shadow: var(--shadow-sm);
    }

    .form-input-edit:hover, .form-select-edit:hover, .form-textarea-edit:hover {
        border-color: var(--gray-light);
        box-shadow: var(--shadow-md);
    }

    .form-input-edit:focus, .form-select-edit:focus, .form-textarea-edit:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(123, 104, 238, 0.15);
    }

    .form-textarea-edit {
        min-height: 120px;
        resize: vertical;
    }

    /* ====== READONLY EXAM TYPE DISPLAY ====== */
    .exam-type-display {
        background: var(--light-gray);
        border: 2px solid var(--gray-border);
        border-radius: var(--radius-md);
        padding: 16px 20px;
        font-weight: 800;
        font-size: 1rem;
        color: var(--dark);
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: var(--shadow-sm);
    }

    .exam-type-display .type-badge {
        padding: 8px 16px;
        border-radius: 50px;
        font-weight: 900;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .type-public-badge {
        background: linear-gradient(135deg, rgba(123, 104, 238, 0.15), rgba(123, 104, 238, 0.05));
        color: var(--primary);
        border: 1px solid rgba(123, 104, 238, 0.3);
    }

    .type-class-badge {
        background: linear-gradient(135deg, rgba(0, 212, 170, 0.15), rgba(0, 212, 170, 0.05));
        color: var(--accent);
        border: 1px solid rgba(0, 212, 170, 0.3);
    }

    .type-class-single-badge {
        background: linear-gradient(135deg, rgba(255, 107, 157, 0.15), rgba(255, 107, 157, 0.05));
        color: var(--secondary);
        border: 1px solid rgba(255, 107, 157, 0.3);
    }

    .type-class-comprehensive-badge {
        background: linear-gradient(135deg, rgba(255, 209, 102, 0.15), rgba(255, 209, 102, 0.05));
        color: var(--warning);
        border: 1px solid rgba(255, 209, 102, 0.3);
    }

    /* ====== FORM SWITCH ====== */
    .form-switch-custom {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px;
        background: var(--light-gray);
        border-radius: var(--radius-md);
        border: 2px solid var(--gray-border);
        transition: all 0.3s;
        box-shadow: var(--shadow-sm);
    }

    .form-switch-custom:hover {
        border-color: var(--primary);
        box-shadow: var(--shadow-md);
    }

    .form-switch-custom .form-check-input {
        width: 50px;
        height: 26px;
        cursor: pointer;
    }

    .form-switch-custom .form-check-label {
        font-weight: 800;
        color: var(--dark);
        cursor: pointer;
    }

    /* ====== SIDEBAR ====== */
    .sidebar-card {
        background: var(--light);
        border-radius: var(--radius-xl);
        padding: 25px;
        box-shadow: var(--shadow-md);
        border: 2px solid rgba(123, 104, 238, 0.08);
        animation: slideInRight 0.5s ease-out;
        position: sticky;
        top: 20px;
    }

    .preview-section {
        margin-bottom: 30px;
    }

    .preview-title {
        font-weight: 900;
        font-size: 1.1rem;
        color: var(--dark);
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .preview-title i {
        color: var(--primary);
        background: var(--primary-light);
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .preview-box {
        background: var(--light-gray);
        border-radius: var(--radius-md);
        padding: 20px;
        border: 2px solid var(--gray-border);
        box-shadow: var(--shadow-sm);
    }

    .preview-item {
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid rgba(123, 104, 238, 0.1);
    }

    .preview-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .preview-label {
        color: var(--gray);
        font-size: 0.9rem;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .preview-value {
        color: var(--dark);
        font-weight: 800;
        font-size: 1rem;
    }

    /* ====== ACTION BUTTONS ====== */
    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin-top: 25px;
    }

    .btn-action-edit {
        padding: 16px 20px;
        border-radius: var(--radius-md);
        font-weight: 800;
        font-size: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 2px solid transparent;
        cursor: pointer;
        text-align: center;
        box-shadow: var(--shadow-sm);
    }

    .btn-action-edit:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
    }

    .btn-action-edit:active {
        transform: scale(0.98);
    }

    .btn-save {
        background: var(--primary-gradient);
        color: white;
        box-shadow: 0 8px 20px rgba(123, 104, 238, 0.3);
    }

    .btn-save:hover {
        box-shadow: 0 12px 25px rgba(123, 104, 238, 0.4);
    }

    .btn-questions {
        background: transparent;
        color: var(--primary);
        border: 2px solid var(--primary);
    }

    .btn-questions:hover {
        background: var(--primary-light);
    }

    .btn-back {
        background: transparent;
        color: var(--gray);
        border: 2px solid var(--gray);
    }

    .btn-back:hover {
        background: var(--light-gray);
    }

    /* ====== DANGER ZONE ====== */
    .danger-zone-edit {
        margin-top: 30px;
        padding: 25px;
        background: linear-gradient(135deg, rgba(255, 71, 87, 0.05), transparent);
        border: 2px solid rgba(255, 71, 87, 0.2);
        border-radius: var(--radius-lg);
        animation: fadeIn 0.6s ease;
        box-shadow: var(--shadow-sm);
    }

    .danger-title {
        font-weight: 900;
        font-size: 1.2rem;
        color: var(--danger);
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .danger-description {
        color: var(--gray);
        font-size: 0.95rem;
        margin-bottom: 20px;
        line-height: 1.6;
    }

    .btn-delete {
        background: transparent;
        color: var(--danger);
        border: 2px solid var(--danger);
        padding: 12px 24px;
        border-radius: var(--radius-md);
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
        box-shadow: var(--shadow-sm);
    }

    .btn-delete:hover {
        background: rgba(255, 71, 87, 0.1);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    /* ====== ALERTS ====== */
    .alert-success-custom {
        background: linear-gradient(135deg, rgba(0, 212, 170, 0.1), rgba(0, 212, 170, 0.05));
        border: 2px solid rgba(0, 212, 170, 0.2);
        border-radius: var(--radius-lg);
        padding: 20px 25px;
        color: var(--dark);
        font-weight: 700;
        margin-bottom: 30px;
        animation: slideInRight 0.5s ease-out;
        display: flex;
        align-items: center;
        gap: 15px;
        box-shadow: var(--shadow-sm);
    }

    /* ====== VALIDATION ERRORS ====== */
    .validation-errors {
        background: linear-gradient(135deg, rgba(255, 159, 67, 0.1), rgba(255, 159, 67, 0.05));
        border: 2px solid rgba(255, 159, 67, 0.2);
        border-radius: var(--radius-lg);
        padding: 20px 25px;
        margin-bottom: 30px;
        animation: slideInRight 0.5s ease-out;
        box-shadow: var(--shadow-sm);
    }

    .validation-title {
        font-weight: 900;
        color: var(--warning);
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .validation-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .validation-list li {
        padding: 8px 0;
        border-bottom: 1px solid rgba(255, 159, 67, 0.1);
        color: var(--dark);
        font-weight: 700;
    }

    .validation-list li:last-child {
        border-bottom: none;
    }

    /* ====== TIPS SECTION ====== */
    .tips-section {
        background: var(--light-gray);
        border-radius: var(--radius-md);
        padding: 20px;
        border: 2px solid var(--gray-border);
        margin-top: 30px;
        box-shadow: var(--shadow-sm);
    }

    .tips-title {
        font-weight: 900;
        color: var(--dark);
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .tips-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .tips-list li {
        padding: 10px 0;
        border-bottom: 1px solid rgba(123, 104, 238, 0.1);
        color: var(--dark);
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .tips-list li:last-child {
        border-bottom: none;
    }

    .tips-list li i {
        color: var(--primary);
        font-size: 1.1rem;
    }

    /* ====== RESPONSIVE ====== */
    @media (max-width: 768px) {
        .edit-exam-container {
            padding: 15px 10px 40px;
        }
        
        .page-header-edit {
            padding: 20px;
        }
        
        .header-title-edit h1 {
            font-size: 1.5rem;
        }
        
        .main-form-card, .sidebar-card {
            padding: 20px;
        }
        
        .form-input-edit, .form-select-edit, .form-textarea-edit, .exam-type-display {
            padding: 14px 16px;
        }
        
        .btn-action-edit {
            padding: 14px 16px;
            font-size: 0.95rem;
        }
    }

    /* ====== TOUCH FRIENDLY ====== */
    .btn-action-edit, .form-switch-custom .form-check-input, .btn-delete {
        min-height: 44px;
    }

    /* ====== SELECTION ====== */
    ::selection {
        background: rgba(123, 104, 238, 0.2);
        color: var(--dark);
    }
</style>