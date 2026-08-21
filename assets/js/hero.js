document.addEventListener('DOMContentLoaded', function () {
    const tagline = document.querySelector('[data-typing-text]');
    if (tagline) {
        const text = tagline.dataset.typingText || tagline.textContent.trim();
        tagline.textContent = '';
        let index = 0;
        const type = () => {
            if (index < text.length) {
                tagline.textContent += text[index++];
                window.setTimeout(type, 55);
            }
        };
        type();
    }

    const hero = document.querySelector('.hero-section');
    if (hero && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        hero.addEventListener('mousemove', function (event) {
            const x = (event.clientX / window.innerWidth - 0.5) * 10;
            const y = (event.clientY / window.innerHeight - 0.5) * 10;
            hero.style.setProperty('--parallax-x', x + 'px');
            hero.style.setProperty('--parallax-y', y + 'px');
        });
    }
});
