<?php
$page_title = 'About Us';
$current_page = 'about';
require_once '../config/database.php';
require_once '../includes/functions.php';
require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>

<!-- Page Header -->
<div style="background: var(--primary-gradient); padding: 50px 0 30px 0; margin-top: -1px;">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-8 mx-auto">
                <h1 class="display-4 fw-bold text-white">
                    <i class="fas fa-info-circle text-warning"></i> About Roshan
                </h1>
                <p class="lead text-white-50">Enlightening Balochistan Through Understanding</p>
                <div class="mt-3">
                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">
                        <i class="fas fa-quote-left"></i> 
                        "Seeking knowledge is an obligation upon every Muslim" 
                        <i class="fas fa-quote-right"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div style="padding: 40px 0;">
    <div class="container">
        <div class="row g-4">
            <!-- Mission -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 16px; overflow: hidden;">
                    <div class="card-body p-4">
                        <h2 class="display-6 fw-bold mb-3" style="color: var(--gold-dark);">
                            <i class="fas fa-bullseye text-warning"></i> Our Mission
                        </h2>
                        <p class="lead" style="color: #555;">
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
                    </div>
                </div>
            </div>
            
            <!-- Why Balochistan -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 16px; overflow: hidden;">
                    <div class="card-body p-4">
                        <h2 class="display-6 fw-bold mb-3" style="color: var(--gold-dark);">
                            <i class="fas fa-map-marker-alt text-warning"></i> Why Balochistan?
                        </h2>
                        
                        <div class="d-flex mb-3 p-3" style="background: #f8f9fa; border-radius: 12px; border-left: 4px solid #e74c3c;">
                            <div class="me-3">
                                <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">The Problem</h6>
                                <p class="text-muted small mb-0">
                                    Students in Balochistan face unique challenges: limited resources, 
                                    cultural barriers, and an education system that often rewards 
                                    memorization over understanding.
                                </p>
                            </div>
                        </div>
                        
                        <div class="d-flex mb-3 p-3" style="background: #f8f9fa; border-radius: 12px; border-left: 4px solid #ffd700;">
                            <div class="me-3">
                                <i class="fas fa-lightbulb fa-2x text-warning"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Our Solution</h6>
                                <p class="text-muted small mb-0">
                                    Roshan provides free, high-quality educational content that is 
                                    engaging, interactive, and designed to promote critical thinking 
                                    and genuine understanding.
                                </p>
                            </div>
                        </div>
                        
                        <div class="d-flex p-3" style="background: #f8f9fa; border-radius: 12px; border-left: 4px solid #2ecc71;">
                            <div class="me-3">
                                <i class="fas fa-handshake fa-2x text-success"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Our Promise</h6>
                                <p class="text-muted small mb-0">
                                    Every lesson, video, and book recommendation is carefully curated 
                                    to help students move from <strong>memorization</strong> to 
                                    <strong>understanding</strong>.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- The Roshan Principles -->
        <div class="mt-5">
            <h2 class="display-6 fw-bold text-center mb-4">The Roshan Principles</h2>
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 text-center p-4" style="border-radius: 16px; transition: transform 0.3s ease;">
                        <div class="display-3 text-warning mb-3">📖</div>
                        <h5>Understanding Over Rote</h5>
                        <p class="text-muted small">
                            "Don't memorize, comprehend. Ask 'Why?' until you truly understand."
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 text-center p-4" style="border-radius: 16px; transition: transform 0.3s ease;">
                        <div class="display-3 text-warning mb-3">🔍</div>
                        <h5>Curiosity Over Passivity</h5>
                        <p class="text-muted small">
                            "The curious mind is an active mind. Question everything."
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 text-center p-4" style="border-radius: 16px; transition: transform 0.3s ease;">
                        <div class="display-3 text-warning mb-3">⚖️</div>
                        <h5>Honesty Over Cheating</h5>
                        <p class="text-muted small">
                            "The one who cheats only cheats themselves. True success comes from honest effort."
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Vision Box -->
        <div class="mt-4">
            <div class="card border-warning" style="border-radius: 16px; overflow: hidden;">
                <div class="card-body p-4" style="background: rgba(255, 215, 0, 0.05);">
                    <h5 class="text-warning">
                        <i class="fas fa-flag"></i> Our Vision
                    </h5>
                    <p class="mb-0">
                        A Balochistan where every student values understanding over grades, 
                        where curiosity is celebrated, and where knowledge is pursued for the 
                        sake of enlightenment and the betterment of society.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card.border-0.shadow-sm:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.08) !important;
}
</style>

<?php require_once '../includes/footer.php'; ?>