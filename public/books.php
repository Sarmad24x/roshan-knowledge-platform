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

<!-- ============================================================ -->
<!-- PAGE HEADER -->
<!-- ============================================================ -->
<div style="background: var(--primary-gradient); padding: 50px 0 30px 0; margin-top: -1px;">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-8 mx-auto">
                <h1 class="display-4 fw-bold text-white">
                    <i class="fas fa-book-open text-warning"></i> Recommended Books
                </h1>
                <p class="lead text-white-50">Curated reading for deeper understanding</p>
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">
                    <i class="fas fa-books"></i> <?php echo count($books); ?> Books Available
                </span>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- BOOKS GRID WITH FLIP CARDS -->
<!-- ============================================================ -->
<div style="padding: 30px 0;">
    <div class="container">
        <?php if (count($books) > 0): ?>
            <div class="row g-4">
                <?php foreach($books as $index => $book): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="book-card" style="perspective: 1000px; height: 400px;">
                            <div class="book-card-inner" style="position: relative; width: 100%; height: 100%; transition: transform 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275); transform-style: preserve-3d; cursor: pointer;">
                                
                                <!-- FRONT SIDE -->
                                <div class="book-card-front" style="position: absolute; width: 100%; height: 100%; backface-visibility: hidden; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.08); background: white;">
                                    
                                    <!-- Book Cover -->
                                    <?php if($book['cover_image']): ?>
                                        <img src="<?php echo SITE_URL . $book['cover_image']; ?>" 
                                             alt="<?php echo htmlspecialchars($book['title']); ?>" 
                                             style="width: 100%; height: 250px; object-fit: cover;">
                                    <?php else: ?>
                                        <div style="height: 250px; background: linear-gradient(135deg, <?php echo $book['color_hex'] ?? '#6c757d'; ?>, <?php echo $book['color_hex'] ?? '#6c757d'; ?>80); display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-book fa-4x text-white" style="opacity: 0.5;"></i>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <!-- Badges -->
                                    <div style="position: absolute; top: 10px; left: 10px; display: flex; gap: 5px; flex-wrap: wrap;">
                                        <span class="badge px-3 py-2" style="background: <?php echo $book['color_hex'] ?? '#6c757d'; ?>; color: white; border-radius: 20px;">
                                            <?php echo htmlspecialchars($book['category_name'] ?? 'General'); ?>
                                        </span>
                                        <?php if($book['is_featured']): ?>
                                            <span class="badge bg-warning text-dark px-3 py-2" style="border-radius: 20px;">
                                                <i class="fas fa-star"></i> Featured
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div style="position: absolute; top: 10px; right: 10px;">
                                        <span class="badge bg-<?php echo $book['difficulty'] == 'beginner' ? 'success' : ($book['difficulty'] == 'intermediate' ? 'warning' : 'danger'); ?> px-3 py-2" style="border-radius: 20px;">
                                            <?php echo ucfirst($book['difficulty']); ?>
                                        </span>
                                    </div>
                                    
                                    <!-- Book Info -->
                                    <div style="padding: 15px;">
                                        <h5 class="fw-bold mb-0"><?php echo htmlspecialchars($book['title']); ?></h5>
                                        <small class="text-muted">
                                            <i class="fas fa-user-edit"></i> <?php echo htmlspecialchars($book['author']); ?>
                                        </small>
                                        <div style="margin-top: 8px;">
                                            <span style="font-size: 0.8rem; color: #aaa;">
                                                <i class="fas fa-arrow-right"></i> Click to flip
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <!-- Hover Glow Effect -->
                                    <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 4px; background: <?php echo $book['color_hex'] ?? '#ffd700'; ?>;"></div>
                                </div>
                                
                                <!-- BACK SIDE -->
                                <div class="book-card-back" style="position: absolute; width: 100%; height: 100%; backface-visibility: hidden; transform: rotateY(180deg); border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.08); background: <?php echo $book['color_hex'] ?? '#0a0a2e'; ?>; padding: 25px; display: flex; flex-direction: column; justify-content: center; color: white;">
                                    
                                    <h5 class="fw-bold text-center mb-2"><?php echo htmlspecialchars($book['title']); ?></h5>
                                    <p style="text-align: center; font-size: 0.9rem; opacity: 0.8;">
                                        by <?php echo htmlspecialchars($book['author']); ?>
                                    </p>
                                    
                                    <?php if($book['description']): ?>
                                        <p style="font-size: 0.85rem; opacity: 0.9; text-align: center; margin-top: 10px; line-height: 1.6; overflow-y: auto; max-height: 100px;">
                                            <?php echo htmlspecialchars(substr($book['description'], 0, 150)) . '...'; ?>
                                        </p>
                                    <?php endif; ?>
                                    
                                    <div style="margin-top: auto; display: flex; flex-direction: column; gap: 8px;">
                                        <?php if($book['pdf_link']): ?>
                                            <a href="<?php echo $book['pdf_link']; ?>" target="_blank" class="btn btn-light btn-sm rounded-pill" style="color: <?php echo $book['color_hex'] ?? '#0a0a2e'; ?>; font-weight: 600;">
                                                <i class="fas fa-file-pdf"></i> Read Online
                                            </a>
                                        <?php endif; ?>
                                        <?php if($book['purchase_link']): ?>
                                            <a href="<?php echo $book['purchase_link']; ?>" target="_blank" class="btn btn-outline-light btn-sm rounded-pill">
                                                <i class="fas fa-shopping-cart"></i> Buy Now
                                            </a>
                                        <?php endif; ?>
                                        <button onclick="this.closest('.book-card-inner').style.transform = 'rotateY(0deg)';" class="btn btn-sm" style="background: rgba(255,255,255,0.1); color: white; border: none; border-radius: 20px;">
                                            <i class="fas fa-undo"></i> Flip Back
                                        </button>
                                    </div>
                                </div>
                                
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
</div>

<!-- ============================================================ -->
<!-- WHY BOOKS MATTER SECTION -->
<!-- ============================================================ -->
<div style="background: #f8f9fa; padding: 50px 0; margin-top: 30px;">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-8 mx-auto">
                <h2 class="display-5 fw-bold mb-3">Why Read Books?</h2>
                <p class="lead text-muted">
                    In a world of short videos and quick summaries, books offer deep, 
                    comprehensive understanding that transforms how we think.
                </p>
            </div>
        </div>
        <div class="row g-3 mt-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 text-center p-4" style="border-radius: 16px; transition: transform 0.3s ease;">
                    <div class="display-4 text-warning mb-2"><i class="fas fa-brain"></i></div>
                    <h5>Deep Understanding</h5>
                    <small class="text-muted">Books provide comprehensive knowledge that builds true understanding.</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 text-center p-4" style="border-radius: 16px; transition: transform 0.3s ease;">
                    <div class="display-4 text-warning mb-2"><i class="fas fa-clock"></i></div>
                    <h5>Focus & Patience</h5>
                    <small class="text-muted">Reading teaches patience and focus - essential skills for lifelong learning.</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 text-center p-4" style="border-radius: 16px; transition: transform 0.3s ease;">
                    <div class="display-4 text-warning mb-2"><i class="fas fa-lightbulb"></i></div>
                    <h5>New Perspectives</h5>
                    <small class="text-muted">Books expose you to different ideas, cultures, and ways of thinking.</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- STYLES -->
<!-- ============================================================ -->
<style>
.book-card {
    perspective: 1000px;
    height: 400px;
}

.book-card-inner {
    position: relative;
    width: 100%;
    height: 100%;
    transition: transform 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    transform-style: preserve-3d;
    cursor: pointer;
}

.book-card-inner.flipped {
    transform: rotateY(180deg);
}

.book-card-front,
.book-card-back {
    position: absolute;
    width: 100%;
    height: 100%;
    backface-visibility: hidden;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
}

.book-card-back {
    transform: rotateY(180deg);
    padding: 25px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    color: white;
}

.card.border-0.shadow-sm:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
}

/* Responsive */
@media (max-width: 768px) {
    .book-card {
        height: 350px;
    }
    .book-card-front img {
        height: 180px;
    }
}
</style>

<!-- ============================================================ -->
<!-- JAVASCRIPT FOR FLIP CARDS -->
<!-- ============================================================ -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Flip card on click
    document.querySelectorAll('.book-card').forEach(card => {
        card.addEventListener('click', function(e) {
            // Don't flip if clicking on a button or link
            if (e.target.closest('a') || e.target.closest('button')) {
                return;
            }
            const inner = this.querySelector('.book-card-inner');
            inner.classList.toggle('flipped');
        });
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>