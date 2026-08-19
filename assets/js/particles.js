// ============================================
// ROSHAN - Particle Background
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    // Check if particles container exists
    const particlesContainer = document.getElementById('particles-js');
    if (!particlesContainer) return;

    // Create particles
    const particleCount = window.innerWidth < 768 ? 50 : 120;
    const particles = [];

    for (let i = 0; i < particleCount; i++) {
        const particle = document.createElement('div');
        const size = Math.random() * 3 + 1;
        
        particle.className = 'star';
        particle.style.width = size + 'px';
        particle.style.height = size + 'px';
        particle.style.left = Math.random() * 100 + '%';
        particle.style.top = Math.random() * 100 + '%';
        particle.style.setProperty('--duration', (Math.random() * 3 + 2) + 's');
        particle.style.setProperty('--delay', (Math.random() * 5) + 's');
        particle.style.opacity = Math.random() * 0.5 + 0.1;
        
        particlesContainer.appendChild(particle);
        particles.push(particle);
    }

    // Mouse interaction - particles move slightly on mouse move
    let mouseX = 0;
    let mouseY = 0;

    document.addEventListener('mousemove', function(e) {
        const rect = particlesContainer.getBoundingClientRect();
        mouseX = (e.clientX - rect.left) / rect.width;
        mouseY = (e.clientY - rect.top) / rect.height;
        
        particles.forEach((particle, index) => {
            const speed = 0.5 + (index / particles.length) * 0.5;
            const xOffset = (mouseX - 0.5) * 20 * speed;
            const yOffset = (mouseY - 0.5) * 20 * speed;
            particle.style.transform = `translate(${xOffset}px, ${yOffset}px)`;
        });
    });

    // Responsive: Recreate particles on resize
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            location.reload();
        }, 1000);
    });
});