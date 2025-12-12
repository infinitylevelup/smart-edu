@extends('layouts.app')
@section('title', 'پنل معلم - کلاس‌های من')

@push('styles')
<style>
    /* ========== THEME ENHANCEMENTS ========== */
    :root {
        --primary: #7B68EE;
        --primary-light: rgba(123, 104, 238, 0.1);
        --primary-gradient: linear-gradient(135deg, #7B68EE, #FF6B9D);
        --secondary: #FF6B9D;
        --secondary-light: rgba(255, 107, 157, 0.1);
        --accent: #00D4AA;
        --accent-light: rgba(0, 212, 170, 0.1);
        --warning: #FFD166;
        --warning-light: rgba(255, 209, 102, 0.1);
        --danger: #EF476F;
        --danger-light: rgba(239, 71, 111, 0.1);
        --light: #ffffff;
        --dark: #2D3047;
        --dark-light: #3A3F6D;
        --gray: #8A8D9B;
        --light-gray: #F8F9FF;
        --shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.08);
        --shadow-md: 0 8px 20px rgba(0, 0, 0, 0.12);
        --shadow-lg: 0 12px 30px rgba(0, 0, 0, 0.16);
        --shadow-xl: 0 20px 40px rgba(0, 0, 0, 0.2);
        --gradient-1: linear-gradient(135deg, #7B68EE, #FF6B9D);
        --gradient-2: linear-gradient(135deg, #00D4AA, #4361EE);
        --gradient-3: linear-gradient(135deg, #FFD166, #FF9A3D);
        --gradient-4: linear-gradient(135deg, #7209B7, #3A0CA3);
        --radius-xl: 24px;
        --radius-lg: 20px;
        --radius-md: 16px;
        --radius-sm: 12px;
        --radius-full: 999px;
    }

    * { font-family: 'Vazirmatn', 'Segoe UI', sans-serif; }

    body {
        background: linear-gradient(135deg, #f5f7ff 0%, #f0f2ff 100%);
        min-height: 100vh;
        color: var(--dark);
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    /* ========== MAIN CONTAINER ========== */
    .classes-container {
        max-width: 1600px;
        margin: 0 auto;
        padding: 25px 20px 100px;
        animation: fadeIn 0.8s ease both;
    }
    @keyframes fadeIn {
        from {opacity: 0; transform: translateY(30px);}
        to {opacity: 1; transform: translateY(0);}
    }
    @keyframes slideInLeft {
        from {transform: translateX(-40px); opacity: 0;}
        to {transform: translateX(0); opacity: 1;}
    }
    @keyframes slideInRight {
        from {transform: translateX(40px); opacity: 0;}
        to {transform: translateX(0); opacity: 1;}
    }
    @keyframes float { 0%,100% {transform: translateY(0);} 50% {transform: translateY(-15px);} }
    @keyframes pulse {
        0% {transform: scale(1); box-shadow: 0 0 0 0 rgba(123, 104, 238, 0.4);}
        70% {transform: scale(1.05); box-shadow: 0 0 0 15px rgba(123, 104, 238, 0);}
        100% {transform: scale(1); box-shadow: 0 0 0 0 rgba(123, 104, 238, 0);}
    }
    @keyframes gradientFlow {
        0% {background-position: 0% 50%;}
        50% {background-position: 100% 50%;}
        100% {background-position: 0% 50%;}
    }

    /* ========== STATS HEADER ========== */
    .stats-header {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 35px;
        animation: slideInRight 0.6s ease-out;
    }
    @media (max-width: 1200px) { .stats-header {grid-template-columns: repeat(2, 1fr);} }
    @media (max-width: 768px) { .stats-header {grid-template-columns: 1fr;} }

    .stat-card {
        background: var(--light);
        border-radius: var(--radius-xl);
        padding: 25px;
        box-shadow: var(--shadow-lg);
        border: 2px solid transparent;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: hidden;
    }
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0; right: 0;
        width: 120px; height: 120px;
        border-radius: 0 0 0 100%;
        opacity: 0.1; transition: all 0.5s;
    }
    .stat-card:hover { transform: translateY(-8px); box-shadow: var(--shadow-xl); }
    .stat-card:nth-child(1)::before {background: var(--primary);}
    .stat-card:nth-child(2)::before {background: var(--secondary);}
    .stat-card:nth-child(3)::before {background: var(--accent);}
    .stat-card:nth-child(4)::before {background: var(--warning);}
    .stat-card:hover::before { width: 150px; height: 150px; opacity: 0.15; }

    .stat-icon {
        width: 60px; height: 60px;
        border-radius: var(--radius-lg);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.8rem; margin-bottom: 20px;
        position: relative; z-index: 2;
    }
    .stat-card:nth-child(1) .stat-icon {background: var(--primary-light); color: var(--primary);}
    .stat-card:nth-child(2) .stat-icon {background: var(--secondary-light); color: var(--secondary);}
    .stat-card:nth-child(3) .stat-icon {background: var(--accent-light); color: var(--accent);}
    .stat-card:nth-child(4) .stat-icon {background: var(--warning-light); color: #FF9A3D;}

    .stat-value {
        font-size: 2.2rem; font-weight: 900;
        line-height: 1; margin-bottom: 8px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        position: relative; z-index: 2;
    }
    .stat-label { color: var(--gray); font-weight: 700; font-size: 0.95rem; position: relative; z-index: 2; }
    .stat-change { display: flex; align-items: center; gap: 6px; font-size: 0.85rem; font-weight: 700; margin-top: 12px; position: relative; z-index: 2; }
    .stat-change.positive {color: var(--accent);}
    .stat-change.negative {color: var(--danger);}

    /* ========== HERO SECTION ========== */
    .hero-section {
        background: linear-gradient(135deg,
            rgba(123, 104, 238, 0.08) 0%,
            rgba(255, 107, 157, 0.08) 50%,
            rgba(0, 212, 170, 0.08) 100%);
        border-radius: var(--radius-xl);
        padding: 40px 45px;
        margin-bottom: 40px;
        border: 2px solid rgba(123, 104, 238, 0.2);
        position: relative; overflow: hidden;
        animation: slideInLeft 0.5s ease-out;
        backdrop-filter: blur(10px);
    }
    .hero-section::before,
    .hero-section::after {
        content: ''; position: absolute; border-radius: 50%;
        animation: float 8s ease-in-out infinite;
    }
    .hero-section::before {
        top: -80px; right: -80px; width: 300px; height: 300px;
        background: radial-gradient(circle, rgba(123, 104, 238, 0.15), transparent 70%);
        animation-delay: -2s;
    }
    .hero-section::after {
        bottom: -60px; left: -60px; width: 250px; height: 250px;
        background: radial-gradient(circle, rgba(0, 212, 170, 0.15), transparent 70%);
        animation-delay: -4s;
    }
    .hero-content h1 {
        font-weight: 900; font-size: 2.5rem; margin-bottom: 15px;
        display: flex; align-items: center; gap: 20px; position: relative; z-index: 2;
    }
    .hero-title-gradient {
        background: linear-gradient(120deg,
            var(--primary) 0%,
            var(--secondary) 30%,
            var(--accent) 70%,
            var(--primary) 100%);
        background-size: 300% 300%;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: gradientFlow 4s ease infinite;
    }
    .hero-subtitle { color: var(--gray); font-size: 1.15rem; line-height: 1.8; max-width: 800px; margin: 0; position: relative; z-index: 2; font-weight: 500; }

    .hero-actions { display: flex; gap: 20px; margin-top: 35px; flex-wrap: wrap; position: relative; z-index: 2; }
    .btn-hero {
        padding: 18px 36px; border-radius: var(--radius-lg);
        font-weight: 800; font-size: 1.1rem;
        display: inline-flex; align-items: center; gap: 14px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        text-decoration: none; border: 3px solid transparent; position: relative; overflow: hidden;
        box-shadow: var(--shadow-lg); min-width: 200px; justify-content: center;
    }
    .btn-primary-grad { background: var(--gradient-1); color: white; box-shadow: 0 12px 30px rgba(123, 104, 238, 0.35); }
    .btn-success-grad { background: var(--gradient-2); color: white; box-shadow: 0 12px 30px rgba(0, 212, 170, 0.35); }
    .btn-outline-light { background: transparent; color: var(--dark); border: 3px solid var(--gray); backdrop-filter: blur(10px); }

    /* ========== QUICK ACTIONS ========== */
    .quick-actions { display: flex; gap: 15px; flex-wrap: wrap; margin-bottom: 35px; animation: fadeIn 0.6s ease-out 0.3s both; }
    .quick-action-btn {
        padding: 16px 28px; border-radius: var(--radius-lg);
        background: var(--light); border: 2px solid var(--light-gray);
        color: var(--dark); font-weight: 700; font-size: 0.95rem;
        display: flex; align-items: center; gap: 12px; transition: all 0.3s; text-decoration: none;
        box-shadow: var(--shadow-sm); flex: 1; min-width: 180px; justify-content: center;
    }

    /* ========== FILTER SECTION ========== */
    .filter-section {
        background: var(--light); border-radius: var(--radius-xl);
        padding: 35px; box-shadow: var(--shadow-xl);
        margin-bottom: 45px; border: 2px solid rgba(123, 104, 238, 0.1);
        animation: slideInRight 0.6s ease-out 0.2s both; position: relative; overflow: hidden;
    }
    .filter-section::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: var(--gradient-1); }
    .filter-header { display: flex; align-items: center; gap: 15px; margin-bottom: 30px; }
    .filter-header i {
        color: var(--primary); background: var(--primary-light);
        width: 60px; height: 60px; border-radius: 16px;
        display: flex; align-items: center; justify-content: center; font-size: 1.6rem; box-shadow: var(--shadow-sm);
    }
    .filter-grid {
        display: grid; grid-template-columns: repeat(5, 1fr);
        gap: 20px; align-items: end;
    }
    @media (max-width: 1200px) {.filter-grid {grid-template-columns: repeat(3, 1fr);} }
    @media (max-width: 992px) {.filter-grid {grid-template-columns: repeat(2, 1fr);} }
    @media (max-width: 576px) { .meta-value{ font-size: 1.5rem; }
                                .filter-grid {grid-template-columns: 1fr;}
                                .class-ribbon{ font-size: 0.75rem; padding: 6px 10px; }
                                }

    .filter-label { color: var(--dark); font-weight: 800; font-size: 0.9rem; margin-bottom: 10px; display: block; padding-right: 8px; }
    .filter-input, .filter-select {
        width: 100%; padding: 16px 20px; border: 2px solid var(--light-gray);
        border-radius: var(--radius-md); background: var(--light); color: var(--dark);
        font-weight: 700; transition: all 0.3s; box-shadow: var(--shadow-sm);
    }
    .filter-input { padding-left: 50px; background-repeat: no-repeat; background-position: left 20px center; background-size: 18px; }
    .filter-select {
        appearance: none; background-repeat: no-repeat; background-position: left 20px center; background-size: 16px;
        padding-left: 50px; cursor: pointer;
    }
    .filter-buttons { display: flex; gap: 15px; height: 100%; align-items: end; }
    .btn-filter {
        flex: 1; padding: 16px; border-radius: var(--radius-md);
        font-weight: 800; font-size: 1rem; background: transparent; color: var(--dark);
        border: 2px solid var(--gray); transition: all 0.3s; cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: 10px; min-height: 56px; box-shadow: var(--shadow-sm);
    }
    .btn-filter-primary { background: var(--gradient-1); color: white; border: none; box-shadow: 0 8px 20px rgba(123, 104, 238, 0.3); }
    .btn-filter-reset { background: transparent; color: var(--gray); border: 2px solid var(--gray); }

    /* ========== VIEW TOGGLE ========== */
    .view-toggle {
        display: flex; background: var(--light);
        border-radius: var(--radius-lg); padding: 6px; margin-bottom: 25px;
        box-shadow: var(--shadow-md); width: fit-content; margin-left: auto;
        animation: fadeIn 0.6s ease-out 0.4s both;
    }
    .view-btn {
        padding: 12px 24px; border-radius: var(--radius-md); background: transparent; border: none;
        color: var(--gray); font-weight: 700; font-size: 0.95rem;
        display: flex; align-items: center; gap: 8px; cursor: pointer; transition: all 0.3s;
    }
    .view-btn.active { background: var(--primary-gradient); color: white; box-shadow: var(--shadow-sm); }

    /* ========== CLASSES GRID ========== */
    .classes-grid {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
        gap: 30px; margin-bottom: 45px; animation: fadeIn 0.8s ease-out 0.5s both;
    }
    @media (max-width: 1200px) {.classes-grid {grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));}}
    @media (max-width: 768px) {.classes-grid {grid-template-columns: 1fr;}}

    .class-card {
        background: var(--light); border-radius: var(--radius-xl);
        padding: 0; box-shadow: var(--shadow-xl); border: 3px solid transparent;
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative; overflow: hidden; height: 100%;
        display: flex; flex-direction: column;
        animation: fadeInUp 0.6s ease-out forwards; opacity: 0;
    }
    @keyframes fadeInUp {
        from {opacity: 0; transform: translateY(30px);}
        to {opacity: 1; transform: translateY(0);}
    }

.class-ribbon{
    position: absolute;
    top: 20px;
    left: 20px;
    padding: 8px 14px;
    font-size: 0.85rem;
    font-weight: 900;
    border-radius: var(--radius-full);
    z-index: 2;

    display: inline-flex;
    align-items: center;
    gap: 6px;

    white-space: nowrap;   /* مهم: نذار بشکنه */
    flex-wrap: nowrap;     /* مهم */
    line-height: 1.2;      /* ارتفاع خط کنترل‌شده */
}

    .ribbon-active { background: rgba(0, 212, 170, 0.2); color: #00D4AA; border: 2px solid rgba(0, 212, 170, 0.3); }
    .ribbon-archived { background: rgba(138, 141, 155, 0.2); color: var(--gray); border: 2px solid rgba(138, 141, 155, 0.3); }

    .class-header { padding: 30px 30px 25px; border-bottom: 2px solid var(--light-gray); position: relative; z-index: 2; }
    .class-title {
        font-weight: 900; font-size: 1.5rem; color: var(--dark);
        margin-bottom: 10px; display: flex; align-items: center; gap: 15px; line-height: 1.4;
        word-break: break-word;
    }
    .class-title i {
        color: var(--primary); background: var(--primary-light);
        width: 50px; height: 50px; border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; font-size: 1.4rem; box-shadow: var(--shadow-sm);
    }
/* === FIX: class header text overlap === */
.class-title-row{
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;          /* اجازه بده بشکنه */
}

.class-title-text{
    font-weight: 900;
    font-size: 1.4rem;
    line-height: 1.7;         /* مهم: روی هم نیفته */
    max-width: 100%;
    word-break: break-word;   /* عنوان‌های بلند */
}

.class-title-badge{
    background: var(--warning-light);
    color: #FF9A3D;
    font-weight: 800;
    font-size: .85rem;
    padding: .25rem .6rem;
    border-radius: var(--radius-full);
    white-space: nowrap;
    flex: 0 0 auto;
}

.class-subject{
    padding-right: 65px;
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;          /* اگر متن بلند شد، خط دوم بره */
    line-height: 1.8;
}

.class-subject .dot-sep{
    margin: 0 6px;
    white-space: nowrap;
    opacity: .7;
}

.class-subject .subject-text,
.class-subject .grade-text{
    word-break: break-word;
}



    .class-subject {
        color: var(--gray); font-size: 1rem; font-weight: 700;
        padding-right: 65px; display: flex; align-items: center; gap: 8px;
        flex-wrap: wrap;
    }

    .class-body { padding: 25px 30px; flex: 1; position: relative; z-index: 2; }
    .class-description {
        color: var(--gray); font-size: 1rem; line-height: 1.8;
        margin-bottom: 30px; min-height: 72px; padding: 15px;
        background: var(--light-gray); border-radius: var(--radius-md);
        border-right: 4px solid var(--primary);
    }

    .class-meta { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 30px; }
    .meta-item {
        background: var(--light-gray); border-radius: var(--radius-lg); padding: 20px; text-align: center;
        transition: all 0.4s; border: 2px solid transparent;
    }
.meta-value {
    font-size: 1.8rem;
    font-weight: 900;
    color: var(--dark);
    margin-bottom: 8px;
    line-height: 1.4;          /* مهم */
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    flex-wrap: wrap;           /* برای عدد/متن بلند */
    word-break: break-word;
}

    .meta-label { font-size: 0.9rem; color: var(--gray); font-weight: 700; }

    .class-code {
        background: linear-gradient(135deg, rgba(123, 104, 238, 0.1), rgba(255, 107, 157, 0.1));
        border-radius: var(--radius-lg); padding: 20px; margin-bottom: 25px; text-align: center;
        border: 3px dashed rgba(123, 104, 238, 0.3); transition: all 0.3s; cursor: pointer;
    }
    .code-label { font-size: 0.95rem; color: var(--gray); font-weight: 700; margin-bottom: 10px; display:flex; align-items:center; justify-content:center; gap:8px;}
    .code-value {
        font-size: 1.8rem; font-weight: 900; color: var(--primary);
        font-family: 'Courier New', monospace; letter-spacing: 3px;
        padding: 10px; background: rgba(255, 255, 255, 0.5);
        border-radius: var(--radius-md);
    }

    .class-footer { padding: 25px 30px 30px; border-top: 2px solid var(--light-gray); position: relative; z-index: 2; }
    .class-actions { display: flex; gap: 15px; }
    .btn-class-action {
        flex: 1; padding: 16px; border-radius: var(--radius-md); font-weight: 800; font-size: 1rem;
        display:flex; align-items:center; justify-content:center; gap:10px; transition: all 0.4s;
        text-decoration:none; border:3px solid transparent; min-height:52px;
    }
    .btn-exam { background: var(--gradient-1); color: white; box-shadow: 0 8px 20px rgba(123, 104, 238, 0.3); }
    .btn-details { background: transparent; color: var(--dark); border: 3px solid var(--gray); }
    .btn-manage { background: var(--gradient-2); color: white; box-shadow: 0 8px 20px rgba(0, 212, 170, 0.3); }

    /* ========== EMPTY STATE ========== */
    .empty-state {
        background: var(--light); border-radius: var(--radius-xl); padding: 80px 50px; text-align:center;
        box-shadow: var(--shadow-xl); border: 3px dashed rgba(123, 104, 238, 0.3);
        animation: fadeIn 0.8s ease-out;
    }
    .empty-icon {
        width: 120px; height: 120px; background: linear-gradient(135deg, rgba(123, 104, 238, 0.1), rgba(0, 212, 170, 0.1));
        border-radius: 50%; display:flex; align-items:center; justify-content:center;
        margin: 0 auto 30px; font-size: 3.2rem; color: var(--primary); animation: pulse 2s infinite;
    }
    .empty-title { font-weight: 900; font-size: 2rem; margin-bottom: 20px; }
    .empty-description { color: var(--gray); font-size: 1.2rem; line-height: 1.8; max-width: 600px; margin: 0 auto 40px; }

    /* ========== PAGINATION ========== */
    .pagination-container { display:flex; justify-content:center; margin-top:40px; animation: fadeIn 0.8s ease-out; }
    .pagination-custom { display:flex; gap:12px; list-style:none; margin:0; padding:0; align-items:center; }
    .page-link {
        padding: 14px 22px; border-radius: var(--radius-md); background: var(--light); color: var(--dark);
        font-weight: 800; text-decoration:none; border:2px solid var(--light-gray);
        transition: all 0.4s; min-width: 50px; display:flex; align-items:center; justify-content:center;
        box-shadow: var(--shadow-sm);
    }
    .page-item.active .page-link { background: var(--gradient-1); color:white; }

    /* ========== FLOATING ACTIONS / TOAST ========== */
    .floating-actions { position: fixed; bottom: 30px; left: 30px; display:flex; flex-direction:column; gap:15px; z-index:1000; }
    .floating-btn {
        width: 60px; height: 60px; border-radius: 50%; background: var(--gradient-1); color:white;
        display:flex; align-items:center; justify-content:center; font-size:1.4rem; box-shadow: var(--shadow-xl);
        border:none; cursor:pointer; transition: all 0.4s;
    }
    .floating-btn-tooltip {
        position: absolute; right: 70px; background: var(--dark); color:white; padding:8px 16px; border-radius: var(--radius-md);
        font-size:0.9rem; font-weight:700; opacity:0; transform: translateX(10px); transition: all 0.3s;
        white-space: nowrap; pointer-events:none;
    }
    .floating-btn:hover .floating-btn-tooltip { opacity:1; transform: translateX(0); }

    .toast {
        position: fixed; bottom: 100px; left: 30px; background: var(--dark); color:white;
        padding: 20px 25px; border-radius: var(--radius-lg); box-shadow: var(--shadow-xl);
        z-index:1001; display:flex; align-items:center; gap:15px; transform: translateX(-100px); opacity:0; transition: all 0.4s;
    }
    .toast.show { transform: translateX(0); opacity:1; }

    @media (max-width: 768px) {
        .classes-container { padding: 20px 15px 80px; }
        .hero-section { padding: 25px; }
        .hero-content h1 { font-size: 2rem; }
        .hero-actions { flex-direction: column; width: 100%; }
        .btn-hero { width: 100%; min-width: unset; }
        .filter-section { padding: 25px; }
        .filter-buttons { flex-direction: column; }
        .classes-grid { grid-template-columns: 1fr; }
        .class-meta { grid-template-columns: 1fr; }
        .class-actions { flex-direction: column; }
        .floating-actions { bottom: 20px; left: 20px; }
        .floating-btn { width: 55px; height: 55px; }
    }
    /* =========================
   COMPACT VIEW (جمع‌وجور)
   فقط وقتی classesGrid کلاس is-compact دارد
   ========================= */

.classes-grid.is-compact .class-header{
    padding: 18px 18px 14px;
}

.classes-grid.is-compact .class-title-text{
    font-size: 1.05rem;
    line-height: 1.6;
}

.classes-grid.is-compact .class-title-row i{
    width: 38px;
    height: 38px;
    font-size: 1.05rem;
    border-radius: 12px;
}

.classes-grid.is-compact .class-title-badge{
    font-size: .75rem;
    padding: .15rem .45rem;
}

.classes-grid.is-compact .class-subject{
    font-size: .85rem;
    padding-right: 48px;
    gap: 6px;
}

.classes-grid.is-compact .class-body{
    padding: 14px 18px;
}

.classes-grid.is-compact .class-description{
    font-size: .9rem;
    line-height: 1.7;
    padding: 10px 12px;
    min-height: 54px;
    margin-bottom: 16px;
}

.classes-grid.is-compact .class-meta{
    gap: 10px;
    margin-bottom: 14px;
}

.classes-grid.is-compact .meta-item{
    padding: 12px;
    border-radius: 14px;
}

.classes-grid.is-compact .meta-value{
    font-size: 1.25rem;
    line-height: 1.3;
    gap: 5px;
}

.classes-grid.is-compact .meta-label{
    font-size: .75rem;
}

.classes-grid.is-compact .class-code{
    padding: 12px;
    margin-bottom: 12px;
}

.classes-grid.is-compact .code-label{
    font-size: .8rem;
    margin-bottom: 6px;
}

.classes-grid.is-compact .code-value{
    font-size: 1.2rem;
    letter-spacing: 2px;
    padding: 6px 8px;
}

.classes-grid.is-compact .class-footer{
    padding: 12px 18px 16px;
}

.classes-grid.is-compact .btn-class-action{
    padding: 10px;
    font-size: .85rem;
    min-height: 44px;
}

</style>
@endpush

@section('content')
<div class="classes-container">

    {{-- ========== STATS HEADER ========== --}}
    <div class="stats-header">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-chalkboard-teacher"></i></div>
            <div class="stat-value" id="totalClasses">{{ $classes->total() ?? 0 }}</div>
            <div class="stat-label">کل کلاس‌ها</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                <span>{{ $classes->where('is_active', true)->count() ?? 0 }} فعال</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-value" id="totalStudents">{{ $totalStudents ?? 0 }}</div>
            <div class="stat-label">دانش‌آموزان کل</div>
            <div class="stat-change positive">
                <i class="fas fa-user-plus"></i>
                <span>میانگین {{ $avgStudentsPerClass ?? 0 }} نفر</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-file-alt"></i></div>
            <div class="stat-value" id="totalExams">{{ $totalExams ?? 0 }}</div>
            <div class="stat-label">آزمون‌های برگزار شده</div>
            <div class="stat-change positive">
                <i class="fas fa-chart-line"></i>
                <span>{{ $activeExams ?? 0 }} فعال</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
            <div class="stat-value" id="attendanceRate">{{ $avgAttendance ?? '0' }}%</div>
            <div class="stat-label">میانگین حضور</div>
            <div class="stat-change positive">
                <i class="fas fa-check-circle"></i>
                <span>+۵٪ نسبت به هفته قبل</span>
            </div>
        </div>
    </div>

    {{-- ========== HERO SECTION ========== --}}
    <div class="hero-section">
        <div class="hero-content">
            <h1><span class="hero-title-gradient">مدیریت کلاس‌های هوشمند</span> 🎓</h1>
            <p class="hero-subtitle">
                اینجا می‌تونی کلاس‌هات رو بسازی، دانش‌آموز اضافه کنی، آزمون بگیری و گزارش‌ها رو ببینی.
            </p>
        </div>

        <div class="hero-actions">
            @if (Route::has('teacher.classes.create'))
                <a href="{{ route('teacher.classes.create') }}" class="btn-hero btn-primary-grad">
                    <i class="fas fa-plus-circle"></i> ایجاد کلاس جدید
                </a>
            @endif

            @if (Route::has('teacher.exams.index'))
                <a href="{{ route('teacher.exams.index') }}" class="btn-hero btn-success-grad">
                    <i class="fas fa-file-alt"></i> مشاهده آزمون‌ها
                </a>
            @endif

            <a href="{{ route('teacher.index') }}" class="btn-hero btn-outline-light">
                <i class="fas fa-home"></i> بازگشت به داشبورد
            </a>
        </div>
    </div>

    {{-- ========== QUICK ACTIONS ========== --}}
    <div class="quick-actions">
        <a href="{{ route('teacher.classes.create') }}" class="quick-action-btn">
            <i class="fas fa-plus-circle"></i> کلاس جدید
        </a>
        <a href="#" class="quick-action-btn" onclick="showBulkActions()">
            <i class="fas fa-bolt"></i> اقدامات گروهی
        </a>
        <a href="#" class="quick-action-btn" onclick="exportClasses()">
            <i class="fas fa-download"></i> خروجی Excel
        </a>
        <a href="#" class="quick-action-btn" onclick="showArchivePanel()">
            <i class="fas fa-archive"></i> کلاس‌های آرشیو
        </a>
        <a href="#" class="quick-action-btn" onclick="showStatistics()">
            <i class="fas fa-chart-pie"></i> آمار و گزارش
        </a>
    </div>

    {{-- ========== FILTER SECTION ========== --}}
    <div class="filter-section">
        <div class="filter-header">
            <i class="fas fa-sliders-h"></i>
            <h3>فیلتر و جستجوی پیشرفته</h3>
        </div>

        <form method="GET" action="{{ route('teacher.classes.index') }}" class="filter-grid" id="filterForm">
            <div class="filter-group">
                <label class="filter-label"><i class="fas fa-search"></i> جستجوی کلاس</label>
                <input type="text" name="q" class="filter-input"
                       placeholder="نام کلاس یا کد ورود..."
                       value="{{ request('q') }}" autocomplete="off">
            </div>

            <div class="filter-group">
                <label class="filter-label"><i class="fas fa-graduation-cap"></i> پایه تحصیلی</label>
                @php $grade = request('grade', 'all'); @endphp
                <select name="grade" class="filter-select">
                    <option value="all" {{ $grade === 'all' ? 'selected' : '' }}>همه پایه‌ها</option>
                    <option value="7" {{ $grade === '7' ? 'selected' : '' }}>هفتم</option>
                    <option value="8" {{ $grade === '8' ? 'selected' : '' }}>هشتم</option>
                    <option value="9" {{ $grade === '9' ? 'selected' : '' }}>نهم</option>
                    <option value="10" {{ $grade === '10' ? 'selected' : '' }}>دهم</option>
                    <option value="11" {{ $grade === '11' ? 'selected' : '' }}>یازدهم</option>
                    <option value="12" {{ $grade === '12' ? 'selected' : '' }}>دوازدهم</option>
                    <option value="other" {{ $grade === 'other' ? 'selected' : '' }}>سایر</option>
                </select>
            </div>

            <div class="filter-group">
                <label class="filter-label"><i class="fas fa-flag"></i> وضعیت</label>
                @php $status = request('status', 'all'); @endphp
                <select name="status" class="filter-select">
                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>همه کلاس‌ها</option>
                    <option value="active" {{ $status === 'active' ? 'selected' : '' }}>فعال</option>
                    <option value="archived" {{ $status === 'archived' ? 'selected' : '' }}>آرشیو شده</option>
                </select>
            </div>

            <div class="filter-group">
                <label class="filter-label"><i class="fas fa-sort"></i> مرتب‌سازی</label>
                @php $sort = request('sort', 'latest'); @endphp
                <select name="sort" class="filter-select">
                    <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>جدیدترین</option>
                    <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>قدیمی‌ترین</option>
                    <option value="students" {{ $sort === 'students' ? 'selected' : '' }}>پر دانش‌آموزترین</option>
                    <option value="title_asc" {{ $sort === 'title_asc' ? 'selected' : '' }}>نام (صعودی)</option>
                    <option value="title_desc" {{ $sort === 'title_desc' ? 'selected' : '' }}>نام (نزولی)</option>
                </select>
            </div>

            <div class="filter-group">
                <label class="filter-label"><i class="fas fa-filter"></i> نمایش در صفحه</label>
                <select name="per_page" class="filter-select">
                    <option value="12" {{ request('per_page') == 12 ? 'selected' : '' }}>۱۲ مورد</option>
                    <option value="24" {{ request('per_page') == 24 ? 'selected' : '' }}>۲۴ مورد</option>
                    <option value="36" {{ request('per_page') == 36 ? 'selected' : '' }}>۳۶ مورد</option>
                    <option value="48" {{ request('per_page') == 48 ? 'selected' : '' }}>۴۸ مورد</option>
                </select>
            </div>

            <div class="filter-buttons">
                <button type="submit" class="btn-filter btn-filter-primary" id="applyFilter">
                    <i class="fas fa-sliders-h"></i> اعمال فیلتر
                </button>
                <a href="{{ route('teacher.classes.index') }}" class="btn-filter btn-filter-reset">
                    <i class="fas fa-times"></i> حذف فیلتر
                </a>
            </div>
        </form>
    </div>

    {{-- ========== VIEW TOGGLE ========== --}}
    <div class="view-toggle">
        <button class="view-btn active" data-view="grid"><i class="fas fa-th-large"></i> نمایش شبکه‌ای</button>
        <button class="view-btn" data-view="list"><i class="fas fa-list"></i> نمایش لیستی</button>
        <button class="view-btn" data-view="compact"><i class="fas fa-grip-vertical"></i> نمایش فشرده</button>
    </div>

    {{-- ========== CLASSES LIST ========== --}}
    @php
        $classes = $classes ?? collect();
        $hasPaginator = method_exists($classes, 'total');
        $totalClasses = $hasPaginator ? $classes->total() : $classes->count();
    @endphp

    @if ($totalClasses == 0)
        <div class="empty-state">
            <div class="empty-icon"><i class="fas fa-people-group"></i></div>
            <h3 class="empty-title">هنوز کلاسی ایجاد نکرده‌اید!</h3>
            <p class="empty-description">
                با ایجاد کلاس می‌توانید دانش‌آموزان را اضافه کرده، آزمون برگزار کنید و پیشرفت‌ها را رصد کنید.
            </p>
            @if (Route::has('teacher.classes.create'))
                <a href="{{ route('teacher.classes.create') }}" class="btn-hero btn-primary-grad" style="display:inline-flex;">
                    <i class="fas fa-plus-circle"></i> ایجاد اولین کلاس
                </a>
            @endif
        </div>
    @else
        <div class="classes-grid" id="classesGrid">
            @foreach ($classes as $index => $class)
                @php
                    // counts
                    $studentsCount = $class->students_count ?? ($class->relationLoaded('students') ? $class->students->count() : 0);
                    $examsCount    = $class->exams_count ?? 0;
                    $isActive      = (bool)($class->is_active ?? true);

                    // safe titles from relations
                    $gradeLabel = optional($class->grade)->name_fa
                                  ?? optional($class->grade)->name
                                  ?? optional($class->grade)->title_fa
                                  ?? '—';

                    $subjectTitle = optional($class->subject)->title_fa
                                  ?? optional($class->subject)->name_fa
                                  ?? optional($class->subject)->name
                                  ?? 'عمومی';

                    // join code
                    $code = $class->join_code ?? null;

                    // created_at safe carbon
                    $createdAt = $class->created_at
                        ? \Illuminate\Support\Carbon::parse($class->created_at)
                        : now();

$diffMinutes = $createdAt->diffInMinutes(now());

if ($diffMinutes < 60) {
    $timeValue = $diffMinutes;
    $timeLabel = 'دقیقه قبل';
} elseif ($diffMinutes < 1440) { // 24*60
    $timeValue = floor($diffMinutes / 60);
    $timeLabel = 'ساعت قبل';
} else {
    $timeValue = floor($diffMinutes / 1440);
    $timeLabel = 'روز قبل';
}

$isNew = $diffMinutes < 10080; // 7 روز = 7*24*60


                    $description = $class->description ?? null;
                @endphp

                <div class="class-card" style="animation-delay: {{ $index * 0.1 }}s">
                    <div class="class-ribbon {{ $isActive ? 'ribbon-active' : 'ribbon-archived' }}">
                        <i class="fas {{ $isActive ? 'fa-bolt' : 'fa-archive' }}"></i>
                        {{ $isActive ? 'فعال' : 'آرشیو' }}
                    </div>

<div class="class-header">
    <div class="class-title-row">
        <i class="fas fa-chalkboard-teacher"></i>

        <h5 class="class-title-text m-0">
            {{ $class->title ?? 'کلاس بدون نام' }}
        </h5>
        @if ($isNew)
            <span class="class-title-badge">✨ جدید</span>
        @endif
    </div>

    <div class="class-subject">
        <i class="fas fa-book"></i>
        <span class="subject-text">{{ $subjectTitle }}</span>
        <span class="dot-sep">•</span>
        <span class="grade-text">پایه {{ $gradeLabel }}</span>
    </div>
</div>


                    <div class="class-body">
                        @if ($description)
                            <div class="class-description">
                                {{ \Illuminate\Support\Str::limit($description, 150) }}
                            </div>
                        @else
                            <div class="class-description" style="font-style: italic;">
                                <i class="fas fa-info-circle"></i>
                                توضیحاتی برای این کلاس ثبت نشده است.
                            </div>
                        @endif

                        <div class="class-meta">
                            <div class="meta-item" data-tooltip="پایه تحصیلی کلاس">
                                <div class="meta-value"><i class="fas fa-graduation-cap"></i> {{ $gradeLabel }}</div>
                                <div class="meta-label">پایه</div>
                            </div>
                            <div class="meta-item" data-tooltip="تعداد دانش‌آموزان">
                                <div class="meta-value"><i class="fas fa-users"></i> {{ $studentsCount }}</div>
                                <div class="meta-label">دانش‌آموز</div>
                            </div>
                            <div class="meta-item" data-tooltip="تعداد آزمون‌ها">
                                <div class="meta-value"><i class="fas fa-file-alt"></i> {{ $examsCount }}</div>
                                <div class="meta-label">آزمون</div>
                            </div><div class="meta-item" data-tooltip="تاریخ ایجاد">
    <div class="meta-value">
        <i class="fas fa-calendar"></i>
        {{ $timeValue }}
    </div>
    <div class="meta-label">{{ $timeLabel }}</div>
</div>

                        </div>

                        @if ($code)
                            <div class="class-code" onclick="copyClassCode('{{ $code }}')" data-tooltip="کلیک برای کپی">
                                <div class="code-label"><i class="fas fa-key"></i> کد ورود به کلاس</div>
                                <div class="code-value">{{ $code }}</div>
                            </div>
                        @endif
                    </div>

                    <div class="class-footer">
                        <div class="class-actions">
                            @if (Route::has('teacher.exams.create'))
                                <a href="{{ route('teacher.exams.create', ['classroom_id' => $class->id]) }}"
                                   class="btn-class-action btn-exam">
                                    <i class="fas fa-plus-circle"></i> آزمون جدید
                                </a>
                            @endif

                            @if (Route::has('teacher.classes.show'))
                                <a href="{{ route('teacher.classes.show', $class->id) }}"
                                   class="btn-class-action btn-manage">
                                    <i class="fas fa-cogs"></i> مدیریت
                                </a>
                            @endif

                            <button class="btn-class-action btn-details"
                                    onclick="showClassDetails({{ $class->id }})">
                                <i class="fas fa-eye"></i> جزئیات
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ========== PAGINATION ========== --}}
        @if (method_exists($classes, 'hasPages') && $classes->hasPages())
            <div class="pagination-container">
                <ul class="pagination-custom">
                    @if (!$classes->onFirstPage())
                        <li class="page-item">
                            <a class="page-link" href="{{ $classes->url(1) }}"><i class="fas fa-fast-backward"></i></a>
                        </li>
                    @endif

                    @if ($classes->onFirstPage())
                        <li class="page-item disabled"><span class="page-link"><i class="fas fa-chevron-right"></i></span></li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $classes->previousPageUrl() }}"><i class="fas fa-chevron-right"></i></a>
                        </li>
                    @endif

                    @php
                        $current = $classes->currentPage();
                        $last = $classes->lastPage();
                        $start = max($current - 2, 1);
                        $end = min($current + 2, $last);
                    @endphp

                    @if ($start > 1)
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    @endif

                    @for ($page = $start; $page <= $end; $page++)
                        @if ($page == $current)
                            <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $classes->url($page) }}">{{ $page }}</a></li>
                        @endif
                    @endfor

                    @if ($end < $last)
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    @endif

                    @if ($classes->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $classes->nextPageUrl() }}"><i class="fas fa-chevron-left"></i></a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="{{ $classes->url($last) }}"><i class="fas fa-fast-forward"></i></a>
                        </li>
                    @else
                        <li class="page-item disabled"><span class="page-link"><i class="fas fa-chevron-left"></i></span></li>
                    @endif
                </ul>
            </div>
        @endif
    @endif

    {{-- ========== FLOATING ACTIONS ========== --}}
    <div class="floating-actions">
        <button class="floating-btn" onclick="scrollToTop()">
            <i class="fas fa-arrow-up"></i><span class="floating-btn-tooltip">برو بالا</span>
        </button>
        <button class="floating-btn" onclick="quickCreateExam()">
            <i class="fas fa-plus"></i><span class="floating-btn-tooltip">آزمون سریع</span>
        </button>
        <button class="floating-btn btn-primary-grad" onclick="refreshPage()">
            <i class="fas fa-sync-alt"></i><span class="floating-btn-tooltip">بروزرسانی</span>
        </button>
    </div>

    {{-- ========== TOAST ========== --}}
    <div class="toast" id="toast">
        <div class="toast-icon"><i class="fas fa-check"></i></div>
        <div class="toast-content">
            <div class="toast-title" id="toastTitle">موفق!</div>
            <div class="toast-message" id="toastMessage">عملیات با موفقیت انجام شد</div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // cards animation delay
    document.querySelectorAll('.class-card').forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });

    // view toggle (guard if no grid)
    const viewButtons = document.querySelectorAll('.view-btn');
    const classesGrid = document.getElementById('classesGrid');

    if (classesGrid) {
        viewButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                const view = this.dataset.view;
                viewButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                classesGrid.className = 'classes-grid';

                if (view === 'list') {
                    classesGrid.style.gridTemplateColumns = '1fr';
                    document.querySelectorAll('.class-card').forEach(card => card.style.height = 'auto');
                } else if (view === 'compact') {
                    classesGrid.style.gridTemplateColumns = 'repeat(auto-fill, minmax(300px, 1fr))';
                    document.querySelectorAll('.class-card').forEach(card => card.style.height = '400px');
                } else {
                    classesGrid.style.gridTemplateColumns = '';
                    document.querySelectorAll('.class-card').forEach(card => card.style.height = '');
                }
            });
        });
    }

    // copy class code
    window.copyClassCode = function (code) {
        if (!navigator.clipboard) return;
        navigator.clipboard.writeText(code).then(() => {
            showToast('کپی شد!', `کد ${code} در کلیپ‌بورد کپی شد.`);
        });
    };

    // filter button loading
    const filterForm = document.getElementById('filterForm');
    const applyFilterBtn = document.getElementById('applyFilter');
    if (filterForm) {
        filterForm.addEventListener('submit', function () {
            const originalText = applyFilterBtn.innerHTML;
            applyFilterBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> در حال جستجو...';
            applyFilterBtn.disabled = true;

            setTimeout(() => {
                applyFilterBtn.innerHTML = originalText;
                applyFilterBtn.disabled = false;
            }, 900);
        });
    }

    // optional stats api
    fetch('/api/teacher/class-stats')
        .then(res => res.json())
        .then(data => {
            if (!data || !data.success) return;
            document.getElementById('totalClasses').textContent  = data.total_classes  || 0;
            document.getElementById('totalStudents').textContent = data.total_students || 0;
            document.getElementById('totalExams').textContent     = data.total_exams    || 0;
            document.getElementById('attendanceRate').textContent = (data.attendance_rate || 0) + '%';
        })
        .catch(() => {});
});

// ========== helper functions ==========
function showToast(title, message) {
    const toast = document.getElementById('toast');
    document.getElementById('toastTitle').textContent = title;
    document.getElementById('toastMessage').textContent = message;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 3000);
}

function showClassDetails(classId) {
    showToast('جزئیات کلاس', 'در حال بارگذاری اطلاعات کلاس...');
    setTimeout(() => window.location.href = `/dashboard/teacher/classes/${classId}`, 400);
}

function scrollToTop() { window.scrollTo({top: 0, behavior: 'smooth'}); }
function quickCreateExam() { showToast('آزمون سریع', 'این قابلیت به زودی اضافه می‌شود'); }
function refreshPage() { window.location.reload(); }
function showBulkActions(){ showToast('اقدامات گروهی', 'به زودی اضافه می‌شود'); }
function exportClasses(){ showToast('خروجی Excel', 'در حال آماده‌سازی...'); }
function showArchivePanel(){ showToast('کلاس‌های آرشیو', 'در حال بارگذاری...'); }
function showStatistics(){ showToast('آمار و گزارش', 'در حال تولید گزارش...'); }
</script>
@endpush
