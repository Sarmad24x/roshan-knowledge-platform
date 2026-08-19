<?php
$page_title = 'Recommended Books';
$current_page = 'books';
require_once '../config/database.php';
require_once '../includes/functions.php';

// Get all books with category info
$sql = "SELECT b.*, c.name as category_name, c.color_hex 
        FROM books b 
        LEFT JOIN categories c ON b.category_id = c.id 
        ORDER BY b.is_featured DESC, b.created_at DESC";

$stmt = $pdo->query($sql);
$books = $stmt->fetchAll();

// Get categories for filter
$stmt = $pdo->query("SELECT * FROM categories WHERE is_active = 1");
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
                    <i class="fas fa-book-open text-warning"></i> Recommended Books
                </h1>
                <p class="lead">Curated reading for deeper understanding</p>
            </div>
        </div>
    </div>
</section>

<!-- Books Grid -->
<section class="py-5">
    <div class="container">
        <?php if (count($books) > 0): ?>
            <div class="row g-4">
                <?php foreach($books as $book): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm hover-card border-0">
                            <?php if($book['cover_image']): ?>
                                <img src="<?php echo SITE_URL . $book['cover_image']; ?>" 
                                     class="card-img-top" alt="<?php echo htmlspecialchars($book['title']); ?>" 
                                     style="height: 250px; object-fit: cover;">
                            <?php else: ?>
                                <div class="bg-secondary text-white text-center py-5" style="height: 250px;">
                                    <i class="fas fa-book fa-4x"></i>
                                    <p class="mt-2">No Cover Available</p>
                                </div>
                            <?php endif; ?>
                            
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="badge" style="background: <?php echo $book['color_hex'] ?? '#6c757d'; ?>;">
                                        <?php echo htmlspecialchars($book['category_name'] ?? 'General'); ?>
                                    </span>
                                    <?php if($book['is_featured']): ?>
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-star"></i> Featured
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <h5 class="card-title"><?php echo htmlspecialchars($book['title']); ?></h5>
                                <h6 class="card-subtitle mb-2 text-muted">
                                    <i class="fas fa-user-edit"></i> <?php echo htmlspecialchars($book['author']); ?>
                                </h6>
                                <p class="card-text small text-muted">
                                    <?php echo htmlspecialchars(substr($book['description'], 0, 150)) . '...'; ?>
                                </p>
                                <span class="badge bg-secondary">
                                    <?php echo ucfirst($book['difficulty']); ?>
                                </span>
                            </div>
                            
                            <div class="card-footer bg-transparent border-0">
                                <?php if($book['pdf_link']): ?>
                                    <a href="<?php echo $book['pdf_link']; ?>" target="_blank" class="btn btn-success btn-sm w-100 mb-1">
                                        <i class="fas fa-file-pdf"></i> Read Online
                                    </a>
                                <?php endif; ?>
                                <?php if($book['purchase_link']): ?>
                                    <a href="<?php echo $book['purchase_link']; ?>" target="_blank" class="btn btn-outline-primary btn-sm w-100">
                                        <i class="fas fa-shopping-cart"></i> Buy Now
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-book-open fa-4x text-muted mb-3"></i>
                <h3>No Books Yet</h3>
                <p class="text-muted">We're adding new book recommendations regularly. Check back soon!</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Why Books Matter -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 class="display-6 fw-bold">Why Read Books?</h2>
                <p class="lead text-muted">
                    In a world of short videos and quick summaries, books offer deep, 
                    comprehensive understanding that transforms how we think.
                </p>
                <div class="row g-3 mt-4">
                    <div class="col-md-4">
                        <div class="card border-0">
                            <div class="card-body">
                                <i class="fas fa-brain fa-2x text-warning"></i>
                                <h6>Deep Understanding</h6>
                                <small class="text-muted">Books provide comprehensive knowledge that builds true understanding.</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0">
                            <div class="card-body">
                                <i class="fas fa-clock fa-2x text-warning"></i>
                                <h6>Focus & Patience</h6>
                                <small class="text-muted">Reading teaches patience and focus - essential skills for lifelong learning.</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0">
                            <div class="card-body">
                                <i class="fas fa-lightbulb fa-2x text-warning"></i>
                                <h6>New Perspectives</h6>
                                <small class="text-muted">Books expose you to different ideas, cultures, and ways of thinking.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>