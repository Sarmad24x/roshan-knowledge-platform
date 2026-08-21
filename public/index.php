<?php
$page_title = 'Home';
$current_page = 'home';
require_once '../config/database.php';
require_once '../includes/functions.php';

// Get featured lessons
$stmt = $pdo->query("SELECT * FROM lessons WHERE is_published = 1 ORDER BY view_count DESC LIMIT 4");
$featured_lessons = $stmt->fetchAll();

// Get all categories
$stmt = $pdo->query("SELECT * FROM categories WHERE is_active = 1");
$categories = $stmt->fetchAll();

// Get total stats
$total_lessons = $pdo->query("SELECT COUNT(*) FROM lessons WHERE is_published = 1")->fetchColumn();
$total_teachers = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'teacher' AND is_approved = 1")->fetchColumn();
$total_books = $pdo->query("SELECT COUNT(*) FROM books")->fetchColumn();

require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>

<!-- Hero Section with Particles -->
<main class="page-content">
<section class="hero-section position-relative" style="min-height: 80vh; background: linear-gradient(135deg, #0a0a2e 0%, #1a0a3e 50%, #0a0a2e 100%);">
    <div id="particles-js" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 0;"></div>
    <div class="container position-relative" style="z-index: 1; padding-top: 100px;">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center text-white">
                <h1 class="display-1 fw-bold animate__animated animate__fadeInUp">
                    <i class="fas fa-lightbulb text-warning"></i> Roshan
                </h1>
                <p class="display-6 fw-light animate__animated animate__fadeInUp animate__delay-1s" data-typing-text="Enlightenment Through Understanding">
                    Enlightenment Through Understanding
                </p>
                <p class="lead animate__animated animate__fadeInUp animate__delay-2s">
                    Breaking the culture of cheating in Balochistan through genuine learning, 
                    critical thinking, and the pursuit of knowledge.
                </p>
                <div class="mt-4 animate__animated animate__fadeInUp animate__delay-3s">
                    <a href="<?php echo SITE_URL; ?>disciplines.php" class="btn btn-warning btn-lg me-3">
                        <i class="fas fa-rocket"></i> Start Learning
                    </a>
                    <a href="<?php echo SITE_URL; ?>about.php" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-info-circle"></i> Learn More
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Stats -->
        <div class="row mt-5 animate__animated animate__fadeInUp animate__delay-4s">
            <div class="col-md-4 text-center text-white">
                <h2 class="display-4 fw-bold text-warning counter" data-target="<?php echo $total_lessons; ?>">0</h2>
                <p>Lessons Available</p>
            </div>
            <div class="col-md-4 text-center text-white">
                <h2 class="display-4 fw-bold text-warning counter" data-target="<?php echo $total_teachers; ?>">0</h2>
                <p>Teachers & Scholars</p>
            </div>
            <div class="col-md-4 text-center text-white">
                <h2 class="display-4 fw-bold text-warning counter" data-target="<?php echo $total_books; ?>">0</h2>
                <p>Recommended Books</p>
            </div>
        </div>
    </div>
</section>

<!-- The Roshan Pledge Modal -->
<div class="modal fade" id="pledgeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header border-secondary">
                <h5 class="modal-title"><i class="fas fa-handshake text-warning"></i> The Roshan Pledge</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="lead">I pledge to:</p>
                <ul class="list-unstyled">
                    <li><i class="fas fa-check-circle text-success me-2"></i> Seek understanding, not just grades</li>
                    <li><i class="fas fa-check-circle text-success me-2"></i> Question, think, and apply knowledge</li>
                    <li><i class="fas fa-check-circle text-success me-2"></i> Never cheat - it only cheats myself</li>
                    <li><i class="fas fa-check-circle text-success me-2"></i> Share knowledge to uplift Balochistan</li>
                </ul>
                <p class="text-muted mt-3">"The best of you are those who learn the Quran and teach it."</p>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">
                    <i class="fas fa-hand-peace"></i> I Take This Pledge
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Categories Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold">Explore Disciplines</h2>
            <p class="text-muted">Five paths to understanding and enlightenment</p>
        </div>
        <div class="row g-4">
            <?php foreach($categories as $category): ?>
                <div class="col-md-4 col-lg-<?php echo (count($categories) <= 3) ? '4' : '3'; ?>">
                    <div class="card h-100 shadow-sm hover-card border-0">
                        <div class="card-body text-center p-4">
                            <div class="display-1 mb-3" style="color: <?php echo $category['color_hex']; ?>">
                                <i class="fas <?php echo $category['icon_class']; ?>"></i>
                            </div>
                            <h5 class="card-title"><?php echo htmlspecialchars($category['name']); ?></h5>
                            <p class="card-text small text-muted">
                                <?php echo htmlspecialchars(substr($category['description'], 0, 80)) . '...'; ?>
                            </p>
                            <a href="<?php echo SITE_URL; ?>lessons.php?category=<?php echo $category['id']; ?>" 
                               class="btn btn-sm btn-outline-primary">
                                Explore <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Featured Lessons -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold">Featured Lessons</h2>
            <p class="text-muted">Start your journey to understanding today</p>
        </div>
        <div class="row g-4">
            <?php foreach($featured_lessons as $lesson): ?>
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 shadow-sm hover-card border-0">
                        <?php if($lesson['image_path']): ?>
                            <img src="<?php echo SITE_URL . $lesson['image_path']; ?>" 
                                 class="card-img-top" alt="<?php echo $lesson['title']; ?>" 
                                 style="height: 180px; object-fit: cover;">
                        <?php else: ?>
                            <div class="bg-secondary text-white text-center py-5" style="height: 180px;">
                                <i class="fas fa-book-open fa-3x"></i>
                            </div>
                        <?php endif; ?>
                        <div class="card-body">
                            <span class="badge bg-primary mb-2">
                                <?php 
                                    $cat_stmt = $pdo->prepare("SELECT name FROM categories WHERE id = ?");
                                    $cat_stmt->execute([$lesson['category_id']]);
                                    $cat = $cat_stmt->fetch();
                                    echo $cat ? $cat['name'] : 'General';
                                ?>
                            </span>
                            <h5 class="card-title"><?php echo htmlspecialchars($lesson['title']); ?></h5>
                            <p class="card-text small text-muted">
                                <?php echo htmlspecialchars(substr($lesson['summary'], 0, 100)) . '...'; ?>
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-secondary">
                                    <i class="far fa-clock"></i> <?php echo $lesson['reading_time']; ?> min
                                </span>
                                <span class="badge bg-info">
                                    <i class="fas fa-eye"></i> <?php echo $lesson['view_count']; ?>
                                </span>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0">
                            <a href="<?php echo SITE_URL; ?>lessons-details.php?id=<?php echo $lesson['id']; ?>" 
                               class="btn btn-primary w-100">
                                Read Lesson <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<script>
// Show pledge modal on first visit
document.addEventListener('DOMContentLoaded', function() {
    if (!localStorage.getItem('roshan_pledge_taken')) {
        var pledgeModal = new bootstrap.Modal(document.getElementById('pledgeModal'));
        pledgeModal.show();
        localStorage.setItem('roshan_pledge_taken', 'true');
    }
});
</script>

<script src="<?php echo SITE_URL; ?>assets/js/hero.js"></script>
</main>

<?php require_once '../includes/footer.php'; ?>