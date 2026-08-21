document.addEventListener('click', function (event) {
    const button = event.target.closest('.btn, button');
    if (!button || button.disabled) return;

    const ripple = document.createElement('span');
    const bounds = button.getBoundingClientRect();
    const size = Math.max(bounds.width, bounds.height);
    ripple.className = 'ripple';
    ripple.style.width = size + 'px';
    ripple.style.height = size + 'px';
    ripple.style.left = event.clientX - bounds.left - size / 2 + 'px';
    ripple.style.top = event.clientY - bounds.top - size / 2 + 'px';
    button.appendChild(ripple);
    window.setTimeout(() => ripple.remove(), 600);
});
