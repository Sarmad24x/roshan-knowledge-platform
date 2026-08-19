<?php
$page_title = 'Disciplines';
$current_page = 'disciplines';
require_once '../config/database.php';
require_once '../includes/functions.php';

// Get all categories
$stmt = $pdo->query("SELECT * FROM categories WHERE is_active = 1 ORDER BY name");
$categories = $stmt->fetchAll();

require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>

<!-- Page Header -->
<section class="py-5" style="background: var(--primary-gradient);">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center text-white">
                <h1 class="display-4 fw-bold">
                    <i class="fas fa-layer-group text-warning"></i> Disciplines
                </h1>
                <p class="lead">Five paths to understanding and enlightenment</p>
                <p class="small text-white-50">Choose a discipline and begin your journey</p>
            </div>
        </div>
    </div>
</section>

<!-- Disciplines Grid -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <?php foreach($categories as $category): 
                // Get lesson count for this category
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM lessons WHERE category_id = ? AND is_published = 1");
                $stmt->execute([$category['id']]);
                $lesson_count = $stmt->fetchColumn();
                
                // Get book count for this category
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM books WHERE category_id = ?");
                $stmt->execute([$category['id']]);
                $book_count = $stmt->fetchColumn();
            ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm hover-card border-0">
                        <div class="card-body text-center p-4">
                            <div class="display-1 mb-3" style="color: <?php echo $category['color_hex']; ?>">
                                <i class="fas <?php echo $category['icon_class']; ?>"></i>
                            </div>
                            <h3 class="card-title"><?php echo htmlspecialchars($category['name']); ?></h3>
                            <p class="card-text text-muted">
                                <?php echo htmlspecialchars($category['description']); ?>
                            </p>
                            <div class="d-flex justify-content-center gap-3 mb-3">
                                <span class="badge bg-primary">
                                    <i class="fas fa-book"></i> <?php echo $lesson_count; ?> Lessons
                                </span>
                                <span class="badge bg-success">
                                    <i class="fas fa-book-open"></i> <?php echo $book_count; ?> Books
                                </span>
                            </div>
                            <a href="<?php echo SITE_URL; ?>lessons.php?category=<?php echo $category['id']; ?>" 
                               class="btn btn-outline-primary w-100">
                                Explore <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Coming Soon -->
        <div class="text-center mt-5">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> 
                More disciplines coming soon! We're constantly expanding our content.
            </div>
        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>