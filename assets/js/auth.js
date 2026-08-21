document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-password-toggle]').forEach(function (button) {
        button.addEventListener('click', function () {
            const input = document.getElementById(button.dataset.passwordToggle);
            if (!input) return;
            input.type = input.type === 'password' ? 'text' : 'password';
            button.setAttribute('aria-pressed', input.type === 'text');
            button.innerHTML = input.type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
        });
    });

    document.querySelectorAll('.auth-form').forEach(function (form) {
        form.addEventListener('submit', function () {
            if (!form.checkValidity()) form.classList.add('is-invalid');
        });
    });

    const password = document.getElementById('password');
    const strength = document.querySelector('.password-strength');
    if (password && strength) {
        password.addEventListener('input', function () {
            const value = password.value;
            let level = 0;
            if (value.length >= 6) level += 1;
            if (/[A-Z]/.test(value) && /[0-9]/.test(value)) level += 1;
            if (/[^A-Za-z0-9]/.test(value) && value.length >= 10) level += 1;
            strength.dataset.level = level;
            strength.querySelector('span').style.width = (level / 3 * 100) + '%';
        });
    }

    document.querySelectorAll('input[type="file"][accept*="image"]').forEach(function (input) {
        input.addEventListener('change', function () {
            const file = input.files && input.files[0];
            if (!file || !file.type.startsWith('image/')) return;
            let preview = input.parentElement.querySelector('.image-preview-live');
            if (!preview) {
                preview = document.createElement('img');
                preview.className = 'image-preview-live';
                input.parentElement.appendChild(preview);
            }
            preview.src = URL.createObjectURL(file);
        });
    });
});
