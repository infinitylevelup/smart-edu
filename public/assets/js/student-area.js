// مدیریت نویگیشن موبایل (سازگار با route-based)
document.querySelectorAll('.nav-item').forEach(item => {
    item.addEventListener('click', function (e) {
        // اگر لینک واقعی باشد، بگذار مرورگر خودش route را باز کند
        const isLink = this.tagName.toLowerCase() === 'a' || this.querySelector('a[href]');
        if (isLink) return;

        // رفتار قدیمی prototype (اگر هنوز data-section جایی استفاده شد)
        e.preventDefault();

        document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
        this.classList.add('active');

        const sectionId = this.getAttribute('data-section');
        if (!sectionId) return;

        document.querySelectorAll('section').forEach(section => {
            section.classList.remove('active');
        });

        const target = document.getElementById(sectionId);
        if (target) target.classList.add('active');
    });
});
