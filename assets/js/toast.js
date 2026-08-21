(function () {
    'use strict';

    window.showToast = function (message, type) {
        const container = document.querySelector('.toast-container');
        if (!container) return;

        const toast = document.createElement('div');
        toast.className = 'roshan-toast roshan-toast--' + (type || 'info');
        toast.setAttribute('role', 'status');
        toast.innerHTML = '<span>' + message + '</span><button type="button" aria-label="Dismiss notification">&times;</button>';
        container.appendChild(toast);

        requestAnimationFrame(() => toast.classList.add('is-visible'));
        const remove = () => {
            toast.classList.remove('is-visible');
            window.setTimeout(() => toast.remove(), 250);
        };
        toast.querySelector('button').addEventListener('click', remove);
        window.setTimeout(remove, 4500);
    };

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.alert').forEach(function (alert) {
            const type = alert.classList.contains('alert-danger') ? 'error' : 'success';
            if (alert.textContent.trim()) window.showToast(alert.textContent.trim(), type);
        });
    });
})();
