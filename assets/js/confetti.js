window.launchConfetti = function () {
    const colors = ['#ffd700', '#2ecc71', '#3498db', '#e74c3c'];
    for (let index = 0; index < 36; index += 1) {
        const piece = document.createElement('span');
        piece.className = 'confetti-piece';
        piece.style.left = Math.random() * 100 + 'vw';
        piece.style.backgroundColor = colors[index % colors.length];
        piece.style.animationDelay = Math.random() * 0.35 + 's';
        document.body.appendChild(piece);
        window.setTimeout(() => piece.remove(), 1800);
    }
};
