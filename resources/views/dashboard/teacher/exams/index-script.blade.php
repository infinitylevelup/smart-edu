<script>
document.addEventListener('DOMContentLoaded', function() {
    // ویبره برای موبایل
    if (navigator.vibrate) {
        const clickableItems = document.querySelectorAll(
            '.btn-create-exam, .btn-filter, .btn-action, .exams-table tbody tr');
        clickableItems.forEach(item => {
            item.addEventListener('click', function() {
                navigator.vibrate(20);
            });
        });
    }

    // افکت hover برای سطرهای جدول
    const tableRows = document.querySelectorAll('.exams-table tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            if (navigator.vibrate) {
                navigator.vibrate(10);
            }
        });

        // کلیک روی سطر برای مشاهده جزئیات
        const detailsBtn = row.querySelector('.btn-details');
        if (detailsBtn) {
            row.addEventListener('click', function(e) {
                if (!e.target.closest('.btn-action')) {
                    window.location.href = detailsBtn.href;
                }
            });
        }
    });

    // انیمیشن ورود المان‌ها
    const animateElements = () => {
        const elements = document.querySelectorAll('.exams-table tbody tr');
        elements.forEach((el, i) => {
            el.style.animationDelay = `${i * 0.05}s`;
            el.style.animation = 'fadeIn 0.5s ease-out forwards';
            el.style.opacity = '0';
        });
    };

    // اجرای انیمیشن بعد از لود صفحه
    setTimeout(animateElements, 300);

    // اعتبارسنجی فیلتر
    const filterForm = document.querySelector('.filter-form');
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            const select = this.querySelector('select[name="classroom_id"]');
            if (select.value) {
                // نمایش بارگیری
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML =
                    '<i class="fas fa-spinner fa-spin"></i> در حال اعمال فیلتر...';
                submitBtn.disabled = true;

                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 1000);
            }
        });
    }

    // نمایش تعداد کلاس‌های فیلتر شده
    const updateFilterCount = () => {
        const selectedClass = document.querySelector('select[name="classroom_id"]').value;
        const examCount = document.querySelectorAll('.exams-table tbody tr').length;
        const countElement = document.querySelector('.exams-count');

        if (countElement && selectedClass) {
            const className = document.querySelector(`option[value="${selectedClass}"]`).textContent;
            countElement.innerHTML = `<i class="fas fa-filter"></i> ${examCount} آزمون (${className})`;
        }
    };

    // رویداد تغییر فیلتر
    const classSelect = document.querySelector('select[name="classroom_id"]');
    if (classSelect) {
        classSelect.addEventListener('change', updateFilterCount);
    }
    
    // اضافه کردن استایل‌های انیمیشن
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    `;
    document.head.appendChild(style);
});
</script>