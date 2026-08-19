// ============================================
// ROSHAN - Main JavaScript
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    'use strict';

    // ---------- Initialize Bootstrap Tooltips ----------
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // ---------- Smooth Scrolling ----------
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href === '#') return;
            
            const target = document.querySelector(href);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // ---------- Auto-hide Alerts ----------
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.classList.add('fade');
            setTimeout(() => {
                alert.style.display = 'none';
            }, 500);
        }, 5000);
    });

    // ---------- Form Validation ----------
    const forms = document.querySelectorAll('.needs-validation');
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });

    // ---------- Counter Animation ----------
    const counters = document.querySelectorAll('.counter');
    const speed = 200;

    counters.forEach(counter => {
        const updateCount = () => {
            const target = parseInt(counter.getAttribute('data-target'));
            const count = parseInt(counter.innerText);
            const increment = Math.ceil(target / speed);
            
            if (count < target) {
                counter.innerText = count + increment;
                setTimeout(updateCount, 1);
            } else {
                counter.innerText = target;
            }
        };
        
        // Check if counter is in viewport
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    updateCount();
                    observer.unobserve(entry.target);
                }
            });
        });
        
        observer.observe(counter);
    });

    // ---------- Search Functionality ----------
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');

    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            
            if (query.length > 2) {
                searchTimeout = setTimeout(() => {
                    performSearch(query);
                }, 300);
            } else {
                if (searchResults) {
                    searchResults.innerHTML = '';
                }
            }
        });
    }

    function performSearch(query) {
        fetch(`search.php?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                if (searchResults) {
                    if (data.length > 0) {
                        let html = '<div class="list-group">';
                        data.forEach(item => {
                            html += `
                                <a href="lesson-detail.php?id=${item.id}" class="list-group-item list-group-item-action">
                                    <h6 class="mb-1">${item.title}</h6>
                                    <small class="text-muted">${item.category}</small>
                                </a>
                            `;
                        });
                        html += '</div>';
                        searchResults.innerHTML = html;
                    } else {
                        searchResults.innerHTML = `
                            <div class="text-center py-3">
                                <i class="fas fa-search fa-2x text-muted"></i>
                                <p class="text-muted mt-2">No results found for "${query}"</p>
                            </div>
                        `;
                    }
                }
            })
            .catch(error => {
                console.error('Search error:', error);
            });
    }

    // ---------- Back to Top Button ----------
    const backToTopBtn = document.getElementById('backToTop');
    if (backToTopBtn) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                backToTopBtn.style.display = 'block';
                backToTopBtn.classList.add('fade-in');
            } else {
                backToTopBtn.style.display = 'none';
            }
        });

        backToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    // ---------- Video Lightbox ----------
    const videoTriggers = document.querySelectorAll('.video-trigger');
    videoTriggers.forEach(trigger => {
        trigger.addEventListener('click', function() {
            const videoUrl = this.dataset.video;
            const modal = document.getElementById('videoModal');
            if (modal) {
                const iframe = modal.querySelector('iframe');
                if (iframe) {
                    iframe.src = videoUrl;
                }
            }
        });
    });

    // Close video modal and stop playback
    const videoModal = document.getElementById('videoModal');
    if (videoModal) {
        videoModal.addEventListener('hidden.bs.modal', function() {
            const iframe = this.querySelector('iframe');
            if (iframe) {
                iframe.src = '';
            }
        });
    }

    // ---------- Reading Progress Bar ----------
    const progressBar = document.getElementById('readingProgress');
    if (progressBar) {
        window.addEventListener('scroll', function() {
            const scrollTop = window.scrollY;
            const docHeight = document.documentElement.scrollHeight - window.innerHeight;
            const progress = (scrollTop / docHeight) * 100;
            progressBar.style.width = progress + '%';
        });
    }

    // ---------- Quiz Interaction ----------
    const quizOptions = document.querySelectorAll('.quiz-option');
    quizOptions.forEach(option => {
        option.addEventListener('click', function() {
            const questionId = this.dataset.question;
            const selected = this.dataset.value;
            const parent = this.closest('.quiz-question');
            
            // Remove selected from all options in this question
            parent.querySelectorAll('.quiz-option').forEach(opt => {
                opt.classList.remove('selected');
            });
            
            // Add selected to this option
            this.classList.add('selected');
            
            // Store answer
            const answers = JSON.parse(sessionStorage.getItem('quizAnswers') || '{}');
            answers[questionId] = selected;
            sessionStorage.setItem('quizAnswers', JSON.stringify(answers));
        });
    });

    // ---------- Dark Mode Toggle (Bonus Feature) ----------
    const darkModeToggle = document.getElementById('darkModeToggle');
    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', function() {
            document.body.classList.toggle('dark-mode');
            const isDark = document.body.classList.contains('dark-mode');
            localStorage.setItem('darkMode', isDark);
            this.innerHTML = isDark ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
        });
        
        // Check saved preference
        if (localStorage.getItem('darkMode') === 'true') {
            document.body.classList.add('dark-mode');
            darkModeToggle.innerHTML = '<i class="fas fa-sun"></i>';
        }
    }

    // ---------- Console Easter Egg ----------
    console.log('%c🌟 ROSHAN - Knowledge Platform 🌟', 'font-size: 20px; font-weight: bold; color: #ffd700;');
    console.log('%cEnlightening Balochistan Through Understanding', 'font-size: 14px; color: #fff; background: #0a0a2e; padding: 10px;');
    console.log('%c"Seeking knowledge is an obligation upon every Muslim."', 'font-size: 12px; color: #aaa; font-style: italic;');
});

// ---------- Utility Functions (Global) ----------
function showToast(message, type = 'success') {
    const container = document.getElementById('toastContainer');
    if (!container) return;
    
    const colors = {
        success: 'bg-success',
        error: 'bg-danger',
        warning: 'bg-warning',
        info: 'bg-info'
    };
    
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white ${colors[type] || colors.info} border-0`;
    toast.role = 'alert';
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    container.appendChild(toast);
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    toast.addEventListener('hidden.bs.toast', function() {
        toast.remove();
    });
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-PK', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

function truncateText(text, length = 100) {
    if (text.length <= length) return text;
    return text.substr(0, length) + '...';
}