<?php
$page_title = 'Home';
$current_page = 'home';
require_once '../config/database.php';
require_once '../includes/functions.php';

// Get featured lessons
$stmt = $pdo->query("SELECT * FROM lessons WHERE is_published = 1 ORDER BY view_count DESC LIMIT 6");
$featured_lessons = $stmt->fetchAll();

// Get all categories
$stmt = $pdo->query("SELECT * FROM categories WHERE is_active = 1");
$categories = $stmt->fetchAll();

// Get stats
$total_lessons = $pdo->query("SELECT COUNT(*) FROM lessons WHERE is_published = 1")->fetchColumn();
$total_teachers = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'teacher' AND is_approved = 1")->fetchColumn();
$total_books = $pdo->query("SELECT COUNT(*) FROM books")->fetchColumn();

require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>

<!-- ============================================================ -->
<!-- HERO SECTION WITH ANIMATED BACKGROUND -->
<!-- ============================================================ -->
<section class="hero-section position-relative" style="min-height: 100vh; background: var(--primary-gradient); overflow: hidden;">
    
    <!-- Animated Particles Background -->
    <div id="particles-js" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 0;"></div>
    
    <!-- Floating Shapes -->
    <div class="floating-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
        <div class="shape shape-4"></div>
    </div>
    
    <div class="container position-relative" style="z-index: 1; padding-top: 120px;">
        <div class="row align-items-center min-vh-100">
            <div class="col-lg-8 mx-auto text-center text-white">
                
                <!-- Animated Badge -->
                <div class="badge-coming-soon animate__animated animate__fadeInDown animate__delay-0.5s">
                    <span class="pulse-dot"></span>
                    <span style="color: #ffd700;">🎓 Transforming Education in Balochistan</span>
                </div>
                
                <!-- Main Heading with Typing Effect -->
                <!-- Main Heading with Typing Effect -->
                <h1 class="display-1 fw-bold mb-3 animate__animated animate__fadeInUp">
                    <span class="text-warning">Roshan</span>
                </h1>
                <h2 class="display-4 fw-light mb-3 animate__animated animate__fadeInUp animate__delay-0-5s">
                    <span class="typing-text" id="typingText" style="color: rgba(255,255,255,0.9);"></span>
                </h2>
                <p class="display-6 fw-light animate__animated animate__fadeInUp animate__delay-1s text-white-50">
                    Enlightenment Through Understanding
                </p>
                
                <!-- Description -->
                <p class="lead animate__animated animate__fadeInUp animate__delay-1-5s text-white-50" style="max-width: 700px; margin: 0 auto;">
                    Breaking the culture of cheating in Balochistan through genuine learning, 
                    critical thinking, and the pursuit of knowledge.
                </p>
                
                <!-- CTA Buttons -->
                <div class="mt-4 d-flex flex-wrap gap-3 justify-content-center animate__animated animate__fadeInUp animate__delay-2s">
                    <a href="<?php echo SITE_URL; ?>disciplines.php" class="btn btn-warning btn-lg px-5 py-3 rounded-pill pulse-glow">
                        <i class="fas fa-rocket me-2"></i> Start Learning
                        <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                    <a href="<?php echo SITE_URL; ?>about.php" class="btn btn-outline-light btn-lg px-5 py-3 rounded-pill">
                        <i class="fas fa-info-circle me-2"></i> Learn More
                    </a>
                </div>
                
                <!-- Scroll Indicator -->
                <div class="scroll-indicator animate__animated animate__fadeIn animate__delay-3s">
                    <span class="scroll-text">Scroll to explore</span>
                    <div class="scroll-arrow">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- STATS SECTION WITH COUNTING ANIMATION -->
<!-- ============================================================ -->
<section class="stats-section py-5" style="margin-top: -50px; position: relative; z-index: 2;">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3 col-6">
                <div class="stat-card text-center p-4 bg-white shadow-lg rounded-4 hover-grow">
                    <div class="stat-icon bg-warning bg-opacity-10 rounded-circle mx-auto mb-3" style="width: 70px; height: 70px; line-height: 70px;">
                        <i class="fas fa-book-open fa-2x text-warning"></i>
                    </div>
                    <h2 class="display-4 fw-bold text-dark counter" data-target="<?php echo $total_lessons; ?>">0</h2>
                    <p class="text-muted mb-0">Lessons Available</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card text-center p-4 bg-white shadow-lg rounded-4 hover-grow">
                    <div class="stat-icon bg-success bg-opacity-10 rounded-circle mx-auto mb-3" style="width: 70px; height: 70px; line-height: 70px;">
                        <i class="fas fa-chalkboard-teacher fa-2x text-success"></i>
                    </div>
                    <h2 class="display-4 fw-bold text-dark counter" data-target="<?php echo $total_teachers; ?>">0</h2>
                    <p class="text-muted mb-0">Teachers & Scholars</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card text-center p-4 bg-white shadow-lg rounded-4 hover-grow">
                    <div class="stat-icon bg-info bg-opacity-10 rounded-circle mx-auto mb-3" style="width: 70px; height: 70px; line-height: 70px;">
                        <i class="fas fa-book fa-2x text-info"></i>
                    </div>
                    <h2 class="display-4 fw-bold text-dark counter" data-target="<?php echo $total_books; ?>">0</h2>
                    <p class="text-muted mb-0">Recommended Books</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card text-center p-4 bg-white shadow-lg rounded-4 hover-grow">
                    <div class="stat-icon bg-danger bg-opacity-10 rounded-circle mx-auto mb-3" style="width: 70px; height: 70px; line-height: 70px;">
                        <i class="fas fa-users fa-2x text-danger"></i>
                    </div>
                    <h2 class="display-4 fw-bold text-dark counter" data-target="15000">0</h2>
                    <p class="text-muted mb-0">Students Reached</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- CATEGORIES SECTION WITH 3D CARDS -->
<!-- ============================================================ -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill mb-2 animate__animated animate__fadeIn">
                <i class="fas fa-layer-group"></i> Explore Disciplines
            </span>
            <h2 class="display-5 fw-bold animate__animated animate__fadeInUp">Five Paths to Understanding</h2>
            <p class="text-muted animate__animated animate__fadeInUp animate__delay-1s">Choose a discipline and begin your journey</p>
        </div>
        
        <div class="row g-4">
            <?php foreach($categories as $index => $category): ?>
                <div class="col-md-4 col-lg-<?php echo (count($categories) <= 3) ? '4' : '3'; ?>">
                    <div class="category-card card h-100 shadow-sm border-0 text-center p-4 animate__animated animate__fadeInUp" 
                         style="animation-delay: <?php echo $index * 0.1; ?>s; border-radius: 20px; overflow: hidden; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);">
                        
                        <!-- Gradient Overlay -->
                        <div class="category-card-overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(135deg, <?php echo $category['color_hex']; ?>20, transparent); opacity: 0; transition: opacity 0.4s ease;"></div>
                        
                        <div class="card-body position-relative" style="z-index: 1;">
                            <div class="category-icon display-1 mb-3 float-slow" style="color: <?php echo $category['color_hex']; ?>;">
                                <i class="fas <?php echo $category['icon_class']; ?>"></i>
                            </div>
                            <h5 class="card-title fw-bold"><?php echo htmlspecialchars($category['name']); ?></h5>
                            <p class="card-text small text-muted">
                                <?php echo htmlspecialchars(substr($category['description'], 0, 80)) . '...'; ?>
                            </p>
                            
                            <!-- Hover Stats -->
                            <div class="category-stats d-flex justify-content-center gap-3 mb-3">
                                <?php 
                                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM lessons WHERE category_id = ? AND is_published = 1");
                                    $stmt->execute([$category['id']]);
                                    $count = $stmt->fetchColumn();
                                ?>
                                <span class="badge bg-primary bg-opacity-10 text-primary">
                                    <i class="fas fa-book"></i> <?php echo $count; ?> Lessons
                                </span>
                            </div>
                            
                            <a href="<?php echo SITE_URL; ?>lessons.php?category=<?php echo $category['id']; ?>" 
                               class="btn btn-outline-primary rounded-pill px-4 mt-2">
                                Explore <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- FEATURED LESSONS SECTION -->
<!-- ============================================================ -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-primary text-white px-3 py-2 rounded-pill mb-2 animate__animated animate__fadeIn">
                <i class="fas fa-star"></i> Featured Lessons
            </span>
            <h2 class="display-5 fw-bold animate__animated animate__fadeInUp">Start Your Journey Today</h2>
            <p class="text-muted animate__animated animate__fadeInUp animate__delay-1s">Handpicked lessons to get you started</p>
        </div>
        
        <div class="row g-4">
            <?php foreach($featured_lessons as $index => $lesson): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="lesson-card card h-100 shadow-sm border-0 overflow-hidden animate__animated animate__fadeInUp" 
                         style="animation-delay: <?php echo $index * 0.1; ?>s; border-radius: 16px; transition: all 0.4s ease;">
                        
                        <?php if($lesson['image_path']): ?>
                            <div class="lesson-image-wrapper position-relative" style="overflow: hidden; height: 220px;">
                                <img src="<?php echo SITE_URL . $lesson['image_path']; ?>" 
                                     class="card-img-top lesson-image" 
                                     alt="<?php echo htmlspecialchars($lesson['title']); ?>" 
                                     style="height: 220px; width: 100%; object-fit: cover; transition: transform 0.6s ease;">
                                <div class="lesson-overlay position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(to top, rgba(0,0,0,0.6), transparent);"></div>
                                <?php if($lesson['video_url']): ?>
                                    <span class="badge bg-danger position-absolute top-0 end-0 m-3 rounded-pill px-3 py-2">
                                        <i class="fas fa-play"></i> Video
                                    </span>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="bg-secondary text-white text-center py-5" style="height: 220px;">
                                <i class="fas fa-book-open fa-3x"></i>
                            </div>
                        <?php endif; ?>
                        
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <?php 
                                    $cat_stmt = $pdo->prepare("SELECT name, color_hex FROM categories WHERE id = ?");
                                    $cat_stmt->execute([$lesson['category_id']]);
                                    $cat = $cat_stmt->fetch();
                                ?>
                                <span class="badge px-3 py-2 rounded-pill" style="background: <?php echo $cat ? $cat['color_hex'] : '#6c757d'; ?>;">
                                    <?php echo $cat ? $cat['name'] : 'General'; ?>
                                </span>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill">
                                    <?php echo ucfirst($lesson['difficulty']); ?>
                                </span>
                            </div>
                            
                            <h5 class="card-title fw-bold"><?php echo htmlspecialchars($lesson['title']); ?></h5>
                            <p class="card-text small text-muted">
                                <?php echo htmlspecialchars(substr($lesson['summary'] ?? $lesson['content'], 0, 100)) . '...'; ?>
                            </p>
                            
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="d-flex gap-3">
                                    <span class="small text-muted">
                                        <i class="far fa-clock"></i> <?php echo $lesson['reading_time']; ?> min
                                    </span>
                                    <span class="small text-muted">
                                        <i class="fas fa-eye"></i> <?php echo $lesson['view_count']; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-footer bg-transparent border-0">
                            <a href="<?php echo SITE_URL; ?>lessons-details.php?id=<?php echo $lesson['id']; ?>" 
                               class="btn btn-primary w-100 rounded-pill py-2">
                                Read Lesson <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-5">
            <a href="<?php echo SITE_URL; ?>lessons.php" class="btn btn-outline-primary btn-lg rounded-pill px-5">
                View All Lessons <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- THE ROSHAN PLEDGE MODAL -->
<!-- ============================================================ -->
<div class="modal fade" id="pledgeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border-0 rounded-4" style="background: linear-gradient(135deg, #0a0a2e, #1a0a3e) !important;">
            <div class="modal-header border-secondary">
                <h5 class="modal-title">
                    <i class="fas fa-handshake text-warning"></i> The Roshan Pledge
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <div class="display-1 text-warning mb-3">🤝</div>
                <h4 class="fw-bold">Take the Roshan Pledge</h4>
                <p class="text-muted">Join the movement to transform education in Balochistan</p>
                
                <div class="text-start mt-4">
                    <div class="d-flex align-items-start mb-3">
                        <i class="fas fa-check-circle text-success mt-1 me-3"></i>
                        <div>
                            <h6 class="mb-0">Seek Understanding</h6>
                            <small class="text-muted">Not just grades, but true comprehension</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-start mb-3">
                        <i class="fas fa-check-circle text-success mt-1 me-3"></i>
                        <div>
                            <h6 class="mb-0">Stay Curious</h6>
                            <small class="text-muted">Question, think, and explore</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-start mb-3">
                        <i class="fas fa-check-circle text-success mt-1 me-3"></i>
                        <div>
                            <h6 class="mb-0">Never Cheat</h6>
                            <small class="text-muted">Cheating only cheats yourself</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-start mb-3">
                        <i class="fas fa-check-circle text-success mt-1 me-3"></i>
                        <div>
                            <h6 class="mb-0">Share Knowledge</h6>
                            <small class="text-muted">Lift others as you rise</small>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-quote-left"></i> 
                    "The best of you are those who learn the Quran and teach it."
                    <i class="fas fa-quote-right"></i>
                </div>
            </div>
            <div class="modal-footer border-secondary justify-content-center">
                <button type="button" class="btn btn-warning btn-lg px-5 rounded-pill" data-bs-dismiss="modal">
                    <i class="fas fa-hand-peace me-2"></i> I Take This Pledge
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- EXTRA CSS FOR HOMEPAGE -->
<!-- ============================================================ -->
<style>
/* Floating Shapes */
.floating-shapes {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 0;
    pointer-events: none;
    overflow: hidden;
}

.shape {
    position: absolute;
    border-radius: 50%;
    opacity: 0.05;
    animation: float-shape 20s infinite ease-in-out;
}

.shape-1 {
    width: 300px;
    height: 300px;
    background: #ffd700;
    top: 10%;
    left: -5%;
    animation-delay: 0s;
}

.shape-2 {
    width: 200px;
    height: 200px;
    background: #3498db;
    bottom: 20%;
    right: -5%;
    animation-delay: -7s;
}

.shape-3 {
    width: 150px;
    height: 150px;
    background: #2ecc71;
    top: 40%;
    right: 15%;
    animation-delay: -14s;
}

.shape-4 {
    width: 100px;
    height: 100px;
    background: #e74c3c;
    bottom: 40%;
    left: 10%;
    animation-delay: -10s;
}

@keyframes float-shape {
    0%, 100% { transform: translate(0, 0) rotate(0deg) scale(1); }
    25% { transform: translate(30px, -50px) rotate(90deg) scale(1.1); }
    50% { transform: translate(-20px, 30px) rotate(180deg) scale(0.9); }
    75% { transform: translate(50px, -20px) rotate(270deg) scale(1.05); }
}

/* Badge */
.badge-coming-soon {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: rgba(255, 215, 0, 0.1);
    border: 1px solid rgba(255, 215, 0, 0.2);
    padding: 8px 20px;
    border-radius: 50px;
    font-size: 0.9rem;
    color: #fff;
    margin-bottom: 20px;
}

.pulse-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #2ecc71;
    animation: pulse-dot 2s ease-in-out infinite;
    display: inline-block;
}

@keyframes pulse-dot {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(0.8); }
}

/* Typing Text */
.typing-text::after {
    content: '|';
    animation: blink 0.8s step-end infinite;
}

@keyframes blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0; }
}

/* Scroll Indicator */
.scroll-indicator {
    margin-top: 50px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
}

.scroll-text {
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: rgba(255, 255, 255, 0.5);
}

.scroll-arrow {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
}

.scroll-arrow span {
    display: block;
    width: 20px;
    height: 20px;
    border-right: 2px solid rgba(255, 215, 0, 0.5);
    border-bottom: 2px solid rgba(255, 215, 0, 0.5);
    transform: rotate(45deg);
    animation: scroll-bounce 2s infinite;
}

.scroll-arrow span:nth-child(2) {
    animation-delay: -0.4s;
    opacity: 0.7;
}

.scroll-arrow span:nth-child(3) {
    animation-delay: -0.8s;
    opacity: 0.4;
}

@keyframes scroll-bounce {
    0%, 100% { transform: rotate(45deg) translate(0, 0); opacity: 0; }
    50% { opacity: 1; }
    70% { transform: rotate(45deg) translate(10px, 10px); }
}

/* Stat Cards */
.stat-card {
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    border-radius: 20px;
}

.stat-card:hover {
    transform: translateY(-10px) scale(1.02);
    box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
}

.stat-icon {
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Category Cards */
.category-card {
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    cursor: pointer;
    border-radius: 20px;
}

.category-card:hover {
    transform: translateY(-10px) scale(1.02);
    box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
}

.category-card:hover .category-card-overlay {
    opacity: 1;
}

.category-card:hover .category-icon {
    animation: icon-bounce 0.6s ease;
}

@keyframes icon-bounce {
    0%, 100% { transform: scale(1) rotate(0deg); }
    50% { transform: scale(1.2) rotate(10deg); }
}

/* Lesson Cards */
.lesson-card {
    transition: all 0.4s ease;
}

.lesson-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
}

.lesson-card:hover .lesson-image {
    transform: scale(1.05);
}

/* Float Animation */
.float-slow {
    animation: float-slow 4s ease-in-out infinite;
}

@keyframes float-slow {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

/* Pulse Glow */
.pulse-glow {
    animation: pulse-glow 2s ease-in-out infinite;
}

@keyframes pulse-glow {
    0%, 100% { box-shadow: 0 0 20px rgba(255, 215, 0, 0.2); }
    50% { box-shadow: 0 0 40px rgba(255, 215, 0, 0.4); }
}

/* Responsive */
@media (max-width: 768px) {
    .hero-section h1 {
        font-size: 2.5rem !important;
    }
    
    .stat-card {
        padding: 20px !important;
    }
    
    .stat-card .display-4 {
        font-size: 2rem !important;
    }
}
</style>

<!-- ============================================================ -->
<!-- JAVASCRIPT FOR HOMEPAGE -->
<!-- ============================================================ -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // ---- TYPING EFFECT ----
    const typingText = document.getElementById('typingText');
    if (typingText) {
        const phrases = [
            '📚 Knowledge Platform',
            '🌟 Enlightenment Hub',
            '🎯 Understanding Over Grades',
            '💡 Critical Thinking',
            '🚀 Future of Education'
        ];
        let phraseIndex = 0;
        let charIndex = 0;
        let isDeleting = false;
        
        function typeEffect() {
            const currentPhrase = phrases[phraseIndex];
            
            if (isDeleting) {
                typingText.textContent = currentPhrase.substring(0, charIndex - 1);
                charIndex--;
            } else {
                typingText.textContent = currentPhrase.substring(0, charIndex + 1);
                charIndex++;
            }
            
            let speed = isDeleting ? 50 : 100;
            
            if (!isDeleting && charIndex === currentPhrase.length) {
                speed = 2000;
                isDeleting = true;
            } else if (isDeleting && charIndex === 0) {
                isDeleting = false;
                phraseIndex = (phraseIndex + 1) % phrases.length;
                speed = 500;
            }
            
            setTimeout(typeEffect, speed);
        }
        
        typeEffect();
    }
    
    // ---- COUNTER ANIMATION ----
    const counters = document.querySelectorAll('.counter');
    const speed = 100;
    
    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const target = parseInt(entry.target.dataset.target);
                const counter = entry.target;
                const increment = Math.ceil(target / speed);
                let current = 0;
                
                const updateCounter = () => {
                    current += increment;
                    if (current >= target) {
                        counter.textContent = target;
                        return;
                    }
                    counter.textContent = current;
                    setTimeout(updateCounter, 20);
                };
                
                updateCounter();
                counterObserver.unobserve(counter);
            }
        });
    });
    
    counters.forEach(counter => {
        counterObserver.observe(counter);
    });
    
    // ---- PLEDGE MODAL ----
    if (!localStorage.getItem('roshan_pledge_taken')) {
        setTimeout(() => {
            const modal = new bootstrap.Modal(document.getElementById('pledgeModal'));
            modal.show();
            localStorage.setItem('roshan_pledge_taken', 'true');
        }, 2000);
    }
    
    // ---- HOVER 3D TILT ON CATEGORY CARDS ----
    document.querySelectorAll('.category-card').forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            const rotateX = (y - centerY) / 15;
            const rotateY = (centerX - x) / 15;
            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.02)`;
        });
        
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) scale(1)';
        });
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>