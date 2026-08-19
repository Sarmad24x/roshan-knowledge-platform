<?php
$page_title = 'About Us';
$current_page = 'about';
require_once '../config/database.php';
require_once '../includes/functions.php';
require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>

<!-- Page Header -->
<section class="py-5" style="background: var(--primary-gradient);">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center text-white">
                <h1 class="display-4 fw-bold">
                    <i class="fas fa-info-circle text-warning"></i> About Roshan
                </h1>
                <p class="lead">Enlightening Balochistan Through Understanding</p>
                <div class="mt-3">
                    <span class="badge bg-warning text-dark p-2">
                        <i class="fas fa-quote-left"></i> 
                        "Seeking knowledge is an obligation upon every Muslim" 
                        <i class="fas fa-quote-right"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="py-5">
    <div class="container">
        <div class="row g-5">
            <!-- Left Column -->
            <div class="col-lg-6">
                <h2 class="display-6 fw-bold">Our Mission</h2>
                <p class="lead" style="color: var(--gold-dark);">
                    Transforming education in Balochistan from memorization to genuine understanding.
                </p>
                <p>
                    In Balochistan, many students grow up in an environment where cheating is normalized 
                    and understanding is sacrificed for grades. <strong>Roshan</strong> was created to 
                    change this culture by providing an engaging, accessible platform that makes learning 
                    exciting and meaningful.
                </p>
                <p>
                    We believe that every student has the potential to excel through hard work, curiosity, 
                    and critical thinking. Our platform is designed to spark that curiosity and provide 
                    the resources needed for genuine learning.
                </p>
                
                <div class="row mt-4 g-3">
                    <div class="col-sm-6">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-graduation-cap fa-3x text-warning"></i>
                                <h5 class="mt-2">5+ Disciplines</h5>
                                <p class="small text-muted">Islamic, Astronomy, Psychology, Philosophy, CS</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-chalkboard-teacher fa-3x text-warning"></i>
                                <h5 class="mt-2">Expert Teachers</h5>
                                <p class="small text-muted">Scholars and professors sharing knowledge</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-book-open fa-3x text-warning"></i>
                                <h5 class="mt-2">Curated Books</h5>
                                <p class="small text-muted">Recommended reading for deeper understanding</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-video fa-3x text-warning"></i>
                                <h5 class="mt-2">Video Integration</h5>
                                <p class="small text-muted">Curiosity-driven video content</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Column -->
            <div class="col-lg-6">
                <h2 class="display-6 fw-bold">Why Balochistan?</h2>
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex mb-3">
                            <div class="me-3">
                                <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                            </div>
                            <div>
                                <h5>The Problem</h5>
                                <p class="text-muted small">
                                    Students in Balochistan face unique challenges: limited resources, 
                                    cultural barriers, and an education system that often rewards 
                                    memorization over understanding.
                                </p>
                            </div>
                        </div>
                        <div class="d-flex mb-3">
                            <div class="me-3">
                                <i class="fas fa-lightbulb fa-2x text-warning"></i>
                            </div>
                            <div>
                                <h5>Our Solution</h5>
                                <p class="text-muted small">
                                    Roshan provides free, high-quality educational content that is 
                                    engaging, interactive, and designed to promote critical thinking 
                                    and genuine understanding.
                                </p>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fas fa-handshake fa-2x text-warning"></i>
                            </div>
                            <div>
                                <h5>Our Promise</h5>
                                <p class="text-muted small">
                                    Every lesson, video, and book recommendation is carefully curated 
                                    to help students move from <strong>memorization</strong> to 
                                    <strong>understanding</strong>.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Vision Box -->
                <div class="card border-warning mt-3">
                    <div class="card-body bg-warning bg-opacity-10">
                        <h5 class="text-warning">
                            <i class="fas fa-flag"></i> Our Vision
                        </h5>
                        <p class="small mb-0">
                            A Balochistan where every student values understanding over grades, 
                            where curiosity is celebrated, and where knowledge is pursued for the 
                            sake of enlightenment and the betterment of society.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- The Roshan Principles -->
        <div class="row mt-5">
            <div class="col-12">
                <h2 class="display-6 fw-bold text-center mb-4">The Roshan Principles</h2>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <div class="display-4 text-warning mb-2">📖</div>
                                <h5>Understanding Over Rote</h5>
                                <p class="small text-muted">
                                    "Don't memorize, comprehend. Ask 'Why?' until you truly understand."
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <div class="display-4 text-warning mb-2">🔍</div>
                                <h5>Curiosity Over Passivity</h5>
                                <p class="small text-muted">
                                    "The curious mind is an active mind. Question everything."
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <div class="display-4 text-warning mb-2">⚖️</div>
                                <h5>Honesty Over Cheating</h5>
                                <p class="small text-muted">
                                    "The one who cheats only cheats themselves. True success comes from honest effort."
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-5" style="background: var(--primary-gradient);">
    <div class="container">
        <div class="row text-center text-white">
            <div class="col-md-3">
                <h2 class="display-4 fw-bold text-warning" id="statStudents">0</h2>
                <p>Students Reached</p>
            </div>
            <div class="col-md-3">
                <h2 class="display-4 fw-bold text-warning" id="statLessons">0</h2>
                <p>Lessons Available</p>
            </div>
            <div class="col-md-3">
                <h2 class="display-4 fw-bold text-warning" id="statTeachers">0</h2>
                <p>Teachers & Scholars</p>
            </div>
            <div class="col-md-3">
                <h2 class="display-4 fw-bold text-warning" id="statBooks">0</h2>
                <p>Books Recommended</p>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-5">
    <div class="container">
        <div class="card bg-dark text-white border-0">
            <div class="card-body p-5 text-center">
                <h2 class="display-5 fw-bold">Ready to Start Learning?</h2>
                <p class="lead">Join thousands of students in Balochistan who are choosing understanding over memorization.</p>
                <a href="<?php echo SITE_URL; ?>disciplines.php" class="btn btn-warning btn-lg">
                    <i class="fas fa-rocket"></i> Start Your Journey Today
                </a>
            </div>
        </div>
    </div>
</section>

<script>
// Animate stats on scroll
document.addEventListener('DOMContentLoaded', function() {
    const statIds = ['statStudents', 'statLessons', 'statTeachers', 'statBooks'];
    const targets = [15000, <?php echo $total_lessons ?? 10; ?>, <?php echo $total_teachers ?? 5; ?>, <?php echo $total_books ?? 8; ?>];
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounter(entry.target.id, targets[statIds.indexOf(entry.target.id)]);
                observer.unobserve(entry.target);
            }
        });
    });
    
    statIds.forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            observer.observe(el);
        }
    });
});

function animateCounter(id, target) {
    const el = document.getElementById(id);
    if (!el) return;
    
    let current = 0;
    const increment = Math.ceil(target / 50);
    
    const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
            el.textContent = target;
            clearInterval(timer);
        } else {
            el.textContent = current;
        }
    }, 30);
}
</script>

<?php require_once '../includes/footer.php'; ?>