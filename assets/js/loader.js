(function () {
    'use strict';

    const loader = document.querySelector('.roshan-loader');
    if (!loader) return;

    const hideLoader = () => {
        window.setTimeout(() => loader.classList.add('is-hidden'), 350);
    };

    if (document.readyState === 'complete') {
        hideLoader();
    } else {
        window.addEventListener('load', hideLoader, { once: true });
    }

    document.addEventListener('click', function (event) {
        const link = event.target.closest('a[href]');
        if (!link || link.target === '_blank' || link.hasAttribute('download') || link.getAttribute('href').startsWith('#')) return;
        if (link.origin !== window.location.origin || link.pathname === window.location.pathname) return;

        loader.classList.remove('is-hidden');
    });
})();
