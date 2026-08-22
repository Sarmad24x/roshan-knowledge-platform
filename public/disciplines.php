<?php
$page_title = 'Disciplines';
$current_page = 'disciplines';
require_once '../config/database.php';
require_once '../includes/functions.php';

$stmt = $pdo->query("SELECT * FROM categories WHERE is_active = 1 ORDER BY name");
$categories = $stmt->fetchAll();

require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>

<!-- Page Header -->
<div style="background: var(--primary-gradient); padding: 50px 0 30px 0; margin-top: -1px;">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-8 mx-auto">
                <h1 class="display-4 fw-bold text-white">
                    <i class="fas fa-layer-group text-warning"></i> Disciplines
                </h1>
                <p class="lead text-white-50">Five paths to understanding and enlightenment</p>
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">
                    <i class="fas fa-graduation-cap"></i> <?php echo count($categories); ?> Disciplines Available
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Disciplines Grid -->
<div style="padding: 40px 0;">
    <div class="container">
        <div class="row g-4">
            <?php foreach($categories as $index => $category): 
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM lessons WHERE category_id = ? AND is_published = 1");
                $stmt->execute([$category['id']]);
                $lesson_count = $stmt->fetchColumn();
                
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM books WHERE category_id = ?");
                $stmt->execute([$category['id']]);
                $book_count = $stmt->fetchColumn();
            ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100 text-center p-4" 
                         style="border-radius: 20px; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); border-top: 5px solid <?php echo $category['color_hex']; ?>;">
                        
                        <div class="display-1 mb-3 float-slow" style="color: <?php echo $category['color_hex']; ?>;">
                            <i class="fas <?php echo $category['icon_class']; ?>"></i>
                        </div>
                        
                        <h3 class="card-title fw-bold"><?php echo htmlspecialchars($category['name']); ?></h3>
                        <p class="card-text text-muted">
                            <?php echo htmlspecialchars($category['description']); ?>
                        </p>
                        
                        <div class="d-flex justify-content-center gap-3 mb-3">
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                <i class="fas fa-book"></i> <?php echo $lesson_count; ?> Lessons
                            </span>
                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                <i class="fas fa-book-open"></i> <?php echo $book_count; ?> Books
                            </span>
                        </div>
                        
                        <a href="<?php echo SITE_URL; ?>lessons.php?category=<?php echo $category['id']; ?>" 
                           class="btn btn-outline-primary rounded-pill px-4">
                            Explore <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Coming Soon -->
        <div class="text-center mt-5">
            <div class="alert alert-info" style="border-radius: 16px;">
                <i class="fas fa-info-circle"></i> 
                More disciplines coming soon! We're constantly expanding our content.
            </div>
        </div>
    </div>
</div>

<style>
.card {
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
.card:hover {
    transform: translateY(-10px) scale(1.02);
    box-shadow: 0 20px 40px rgba(0,0,0,0.08) !important;
}
.float-slow {
    animation: float-slow 4s ease-in-out infinite;
}
@keyframes float-slow {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}
</style>

<?php require_once '../includes/footer.php'; ?>