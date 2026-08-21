document.addEventListener('DOMContentLoaded', function () {
    const elements = document.querySelectorAll('.reveal-on-scroll, .section-padding, .hover-card, .lesson-card');
    if (!('IntersectionObserver' in window)) {
        elements.forEach(element => element.classList.add('is-visible'));
        return;
    }

    const observer = new IntersectionObserver(function (entries, currentObserver) {
        entries.forEach(function (entry) {
            if (!entry.isIntersecting) return;
            entry.target.classList.add('is-visible');
            currentObserver.unobserve(entry.target);
        });
    }, { threshold: 0.12 });

    elements.forEach(element => {
        element.classList.add('reveal-on-scroll');
        observer.observe(element);
    });

    const filter = document.querySelector('[data-book-filter]');
    if (filter) {
        filter.addEventListener('change', function () {
            document.querySelectorAll('.book-card').forEach(function (card) {
                card.classList.toggle('is-filtered', filter.value !== 'all' && card.dataset.category !== filter.value);
            });
        });
    }

    document.querySelectorAll('[data-filter-form]').forEach(function (form) {
        form.addEventListener('submit', function () { form.classList.add('filter-loading'); });
    });
});
