(function () {
  function init() {
    const form = document.querySelector('.join-form');
    if (!form) return;

    const btn = form.querySelector('[data-join-submit]');
    const label = form.querySelector('[data-join-label]');
    const live = form.querySelector('.join-live');

    if (!btn || !label) return;

    const defaultText = form.dataset.defaultText || label.textContent.trim();
    const loadingText = form.dataset.loadingText || 'در حال بررسی…';

    form.addEventListener('submit', function () {
      if (btn.disabled) return;

      btn.disabled = true;
      btn.setAttribute('aria-busy', 'true');

      label.textContent = loadingText;
      if (live) live.textContent = 'لطفاً چند ثانیه صبر کن…';
    });

    // اگر صفحه با validation error برگشت، دکمه دوباره فعال شود
    window.addEventListener('pageshow', function () {
      btn.disabled = false;
      btn.removeAttribute('aria-busy');
      label.textContent = defaultText;
      if (live) live.textContent = '';
    });
  }

  document.addEventListener('DOMContentLoaded', init);
})();
