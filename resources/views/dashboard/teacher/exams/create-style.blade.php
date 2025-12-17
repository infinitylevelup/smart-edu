<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">
<style>
:root {
    --primary: #6c5ce7;
    --primary-light: #a29bfe;
    --primary-dark: #4834d4;
    --success: #00b894;
    --warning: #fdcb6e;
    --danger: #d63031;
    
    --gradient-1: linear-gradient(135deg, #6c5ce7, #a29bfe);
    --gradient-2: linear-gradient(135deg, #4834d4, #6c5ce7);
    --success-gradient: linear-gradient(135deg, #00b894, #55efc4);
    --warning-gradient: linear-gradient(135deg, #fdcb6e, #ffeaa7);
    
    --glass-bg: rgba(255, 255, 255, 0.25);
    --glass-border: rgba(255, 255, 255, 0.35);
    --glass-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    --text-dark: #2d3436;
}

.exam-container {
    max-width: 1100px;
    margin: 0 auto;
    padding: 25px;
}

/* راهنمای جامع */
.guide-card {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 18px;
    padding: 25px;
    box-shadow: var(--glass-shadow);
    backdrop-filter: blur(14px);
    margin-bottom: 25px;
}

.guide-item {
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    transition: 0.3s;
    height: 100%;
}

.guide-item:hover {
    border-color: var(--primary);
    transform: translateY(-2px);
}

.guide-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 15px;
    color: white;
    font-size: 1.2rem;
}

.guide-item ul {
    padding-right: 20px;
    margin-bottom: 0;
}

.guide-item li {
    margin-bottom: 5px;
    position: relative;
}

/* Step Bar بهبود یافته */
.step-bar {
    display: flex;
    justify-content: space-between;
    margin-bottom: 30px;
    padding: 20px;
    background: var(--glass-bg);
    border-radius: 18px;
    border: 1px solid var(--glass-border);
    backdrop-filter: blur(12px);
    box-shadow: var(--glass-shadow);
    position: relative;
}

.step-item {
    text-align: center;
    flex: 1;
    transition: 0.3s ease;
    color: #666;
    position: relative;
    z-index: 2;
}

.step-item.active {
    color: var(--primary);
}

.step-item.completed {
    color: var(--success);
}

.step-number {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: #fff;
    border: 2px solid #ddd;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 0 auto 8px;
    font-size: 1.1rem;
    font-weight: 700;
    transition: 0.3s ease;
}

.step-item.active .step-number {
    background: var(--gradient-1);
    color: white;
    border-color: var(--primary);
}

.step-item.completed .step-number {
    background: var(--success-gradient);
    color: white;
    border-color: var(--success);
}

.step-title {
    display: block;
    font-weight: 700;
    font-size: 0.9rem;
}

.step-desc {
    display: block;
    font-size: 0.8rem;
    color: #888;
    margin-top: 2px;
}

/* کارت‌های نوع آزمون */
.exam-type-card {
    border: 2px solid #e0e0e0;
    border-radius: 16px;
    overflow: hidden;
    cursor: pointer;
    transition: all 0.3s ease;
    height: 100%;
}

.exam-type-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.exam-type-card.selected {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(108, 92, 231, 0.1);
}

.exam-type-header {
    padding: 25px 20px;
    text-align: center;
    color: white;
}

.bg-primary-gradient {
    background: var(--gradient-1);
}

.bg-success-gradient {
    background: var(--success-gradient);
}

.exam-type-header i {
    font-size: 2.5rem;
    margin-bottom: 15px;
}

.exam-type-header h5 {
    margin: 0;
    font-weight: 700;
}

.exam-type-body {
    padding: 20px;
    background: white;
}

.exam-features {
    list-style: none;
    padding: 0;
    margin: 0;
}

.exam-features li {
    padding: 8px 0;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    align-items: center;
}

.exam-features li:last-child {
    border-bottom: none;
}

.exam-features li i {
    margin-left: 10px;
    font-size: 1.1rem;
}

/* کارت‌های نوع کلاس */
.class-type-card {
    border: 2px solid #e0e0e0;
    border-radius: 16px;
    padding: 25px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    height: 100%;
}

.class-type-card:hover {
    border-color: var(--primary);
    transform: translateY(-2px);
}

.class-type-card.selected {
    border-color: var(--primary);
    background: rgba(108, 92, 231, 0.05);
}

.class-type-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: var(--gradient-1);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    font-size: 1.8rem;
}

.class-type-card h5 {
    margin-bottom: 10px;
    font-weight: 700;
}

/* لیست کلاس‌ها */
.class-list {
    max-height: 300px;
    overflow-y: auto;
}

.class-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    margin-bottom: 10px;
    transition: 0.3s;
}

.class-item:hover {
    border-color: var(--primary);
    background: rgba(108, 92, 231, 0.05);
}

.class-item.selected {
    border-color: var(--primary);
    background: rgba(108, 92, 231, 0.1);
}

.class-info h6 {
    margin: 0 0 5px 0;
    font-weight: 600;
}

.class-item .badge {
    font-size: 0.7rem;
    padding: 4px 8px;
}

/* کارت‌های درس */
.subject-card {
    border: 2px solid #e0e0e0;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-bottom: 15px;
}

.subject-card:hover {
    border-color: var(--primary);
    transform: translateY(-2px);
}

.subject-card.selected {
    border-color: var(--primary);
    background: var(--gradient-1);
    color: white;
}

.subject-card.selected .subject-code {
    color: rgba(255, 255, 255, 0.9);
}

.subject-icon {
    font-size: 2rem;
    margin-bottom: 10px;
    color: var(--primary);
}

.subject-card.selected .subject-icon {
    color: white;
}

.subject-title {
    font-weight: 600;
    margin-bottom: 5px;
    font-size: 1rem;
}

.subject-code {
    font-size: 0.8rem;
    color: #666;
    font-family: monospace;
}

/* پیش‌نمایش */
.preview-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
}

.preview-item {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 12px;
    border: 1px solid #e9ecef;
}

.preview-item strong {
    display: block;
    margin-bottom: 5px;
    color: #495057;
    font-size: 0.9rem;
}

.preview-item span, .preview-item p {
    color: #212529;
    font-weight: 500;
}

.preview-item.full-width {
    grid-column: 1 / -1;
}

/* دکمه‌ها */
.glass-card {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 18px;
    padding: 28px;
    box-shadow: var(--glass-shadow);
    backdrop-filter: blur(14px);
    margin-bottom: 25px;
    transition: 0.3s ease-in-out;
}

.glass-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 38px rgba(0,0,0,0.15);
}

.wizard-step { 
    display: none; 
    animation: fade 0.4s ease; 
}
.wizard-step.active { 
    display: block; 
}

@keyframes fade {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.wizard-buttons {
    display: flex;
    justify-content: space-between;
    margin-top: 25px;
    padding-top: 25px;
    border-top: 1px solid #e0e0e0;
}

.btn-next, .btn-prev, .btn-submit {
    padding: 12px 30px;
    border-radius: 12px;
    font-weight: 700;
    border: none;
    cursor: pointer;
    transition: 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 150px;
}

.btn-next { 
    background: var(--gradient-1); 
    color: #fff; 
}
.btn-prev { 
    background: #dfe6e9; 
    color: #2d3436; 
}
.btn-submit { 
    background: var(--success-gradient); 
    color: #fff; 
}

.btn-next:hover, .btn-prev:hover, .btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.btn-next:disabled, .btn-prev:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none !important;
}

/* فرم‌ها */
.form-control, .form-select {
    border-radius: 12px !important;
    padding: 12px 14px !important;
    border: 1px solid #ddd !important;
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary) !important;
    box-shadow: 0 0 0 3px rgba(108, 92, 231, 0.1) !important;
}

/* رسپانسیو */
@media (max-width: 768px) {
    .step-bar {
        flex-direction: column;
        gap: 15px;
    }
    
    .step-item {
        display: flex;
        align-items: center;
        text-align: right;
        gap: 15px;
    }
    
    .step-number {
        margin: 0;
        min-width: 36px;
        width: 36px;
        height: 36px;
    }
    
    .preview-grid {
        grid-template-columns: 1fr;
    }
    
    .wizard-buttons {
        flex-direction: column;
        gap: 10px;
    }
    
    .btn-next, .btn-prev, .btn-submit {
        width: 100%;
    }
}
</style>