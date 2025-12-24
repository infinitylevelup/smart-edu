document.addEventListener('DOMContentLoaded', function() {
    // انیمیشن مهارت‌ها
    document.querySelectorAll('.skill-fill').forEach(fill => {
        const percent = fill.getAttribute('data-percent') || '0';
        setTimeout(() => {
            fill.style.width = percent + '%';
        }, 300);
    });

    // انیمیشن کارت‌های آمار
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach((card, i) => {
        card.style.animationDelay = (i * 0.1) + 's';
    });

    // ویبره برای موبایل
    if (navigator.vibrate) {
        const clickableItems = document.querySelectorAll(
            '.btn-action, .quick-action, .exam-item, .btn-advisor, .btn-start-exam, .btn-reports');
        clickableItems.forEach(item => {
            item.addEventListener('click', function() {
                navigator.vibrate(30);
            });
        });
    }

    // افکت hover برای کارت‌ها
    const dashCards = document.querySelectorAll('.dash-card');
    dashCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            if (navigator.vibrate) {
                navigator.vibrate(20);
            }
        });
    });

    // فعال‌سازی کلیک روی کارت آزمون‌ها
    const examItems = document.querySelectorAll('.exam-item');
    examItems.forEach(item => {
        item.addEventListener('click', function() {
            const examName = this.querySelector('.exam-name').textContent;
            showExamModal(examName);
        });
    });
});

function showComingSoon(feature) {
        const modal = document.createElement('div');
        modal.style.cssText = `
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) scale(0.9);
    background: white;
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    z-index: 1000;
    text-align: center;
    max-width: 350px;
    width: 85%;
    animation: scaleIn 0.3s ease forwards;
    border: 3px solid var(--primary);
`;

        modal.innerHTML = `
    <div style="font-size: 3rem; margin-bottom: 20px; color: var(--primary);">
        <i class="fas fa-tools"></i>
    </div>
    <h3 style="margin-bottom: 15px; color: var(--dark); font-size: 1.3rem; font-weight: 700;">${feature}</h3>
    <p style="color: var(--gray); margin-bottom: 25px; font-size: 1rem; line-height: 1.6;">
        این قابلیت در حال توسعه است و به زودی در دسترس قرار خواهد گرفت.
    </p>
    <button onclick="this.parentElement.remove(); if (this.parentElement.nextElementSibling) this.parentElement.nextElementSibling.remove();"
            style="padding: 14px 40px; border: none; background: var(--gradient-1); color: white; border-radius: 12px; font-weight: 800; font-size: 1rem; width: 100%;">
        باشه، متوجه شدم
    </button>
`;

        document.body.appendChild(modal);

        const overlay = document.createElement('div');
        overlay.style.cssText = `
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    z-index: 999;
    animation: fadeIn 0.3s ease;
`;
        document.body.appendChild(overlay);

        if (navigator.vibrate) {
            navigator.vibrate([100, 50, 100]);
        }

        setTimeout(() => {
            if (document.body.contains(modal)) {
                modal.remove();
                overlay.remove();
            }
        }, 5000);
    }

    function showExamModal(examName) {
        const modal = document.createElement('div');
        modal.style.cssText = `
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) scale(0.9);
    background: white;
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    z-index: 1000;
    text-align: center;
    max-width: 400px;
    width: 90%;
    animation: scaleIn 0.3s ease forwards;
    border: 3px solid var(--primary);
`;

        modal.innerHTML = `
    <div style="font-size: 3rem; margin-bottom: 20px; color: var(--primary);">
        <i class="fas fa-clipboard-check"></i>
    </div>
    <h3 style="margin-bottom: 15px; color: var(--dark); font-size: 1.3rem; font-weight: 700;">${examName}</h3>
    <p style="color: var(--gray); margin-bottom: 25px; font-size: 1rem; line-height: 1.6;">
        برای شروع این آزمون روی دکمه زیر کلیک کن. زمان آزمون: ۴۵ دقیقه
    </p>
    <div style="display: flex; gap: 10px;">
        <button onclick="this.parentElement.parentElement.remove(); if (this.parentElement.parentElement.nextElementSibling) this.parentElement.parentElement.nextElementSibling.remove();"
                style="flex:1; padding: 14px; border: none; background: var(--light-gray); color: var(--dark); border-radius: 12px; font-weight: 700; font-size: 1rem;">
            بعداً
        </button>
        <button onclick="window.location.href='{{ route('student.exams.public') }}';"
                style="flex:1; padding: 14px; border: none; background: var(--gradient-1); color: white; border-radius: 12px; font-weight: 700; font-size: 1rem;">
            شروع آزمون
        </button>
    </div>
`;

        document.body.appendChild(modal);

        const overlay = document.createElement('div');
        overlay.style.cssText = `
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    z-index: 999;
    animation: fadeIn 0.3s ease;
`;
        document.body.appendChild(overlay);

        if (navigator.vibrate) {
            navigator.vibrate([100, 50, 100]);
        }
    }

    // اضافه کردن استایل‌های انیمیشن
    const style = document.createElement('style');
    style.textContent = `
@keyframes scaleIn {
    from { transform: translate(-50%, -50%) scale(0.9); opacity: 0; }
    to { transform: translate(-50%, -50%) scale(1); opacity: 1; }
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
`;
document.head.appendChild(style);
