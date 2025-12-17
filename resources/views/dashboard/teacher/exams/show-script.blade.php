<script>
    // نمایش تابعی جزئیات سوال با کلیک روی ردیف
    document.addEventListener('DOMContentLoaded', function() {
        const rows = document.querySelectorAll('.q-row');
        rows.forEach(row => {
            row.addEventListener('click', function(e) {
                if (e.target.tagName === 'A' || e.target.closest('a')) return;
                const questionId = this.dataset.id;
                if (questionId) {
                    window.location.href = `/teacher/exams/questions/${questionId}/edit`;
                }
            });
            row.style.cursor = 'pointer';
        });

        // انیمیشن هدر
        const hero = document.querySelector('.hero');
        if (hero) {
            hero.addEventListener('mouseenter', () => {
                hero.style.transform = 'scale(1.005)';
            });
            hero.addEventListener('mouseleave', () => {
                hero.style.transform = 'scale(1)';
            });
        }

        // نمایش پیام موفقیت با انیمیشن
        const alertSuccess = document.querySelector('.alert-success');
        if (alertSuccess) {
            setTimeout(() => {
                alertSuccess.style.transition = 'opacity 0.5s';
                alertSuccess.style.opacity = '0';
                setTimeout(() => alertSuccess.remove(), 500);
            }, 4000);
        }

        // نمایش جزئیات پاسخ صحیح در حالت‌های مختلف
        document.querySelectorAll('.correct-answer').forEach(el => {
            el.title = el.textContent;
        });
    });
</script>