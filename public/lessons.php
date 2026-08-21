<?php
$page_title = 'Lessons';
$current_page = 'lessons';
require_once '../config/database.php';
require_once '../includes/functions.php';

// Get filter parameters
$category_id = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$difficulty = isset($_GET['difficulty']) ? $_GET['difficulty'] : '';

// Build query
$sql = "SELECT l.*, c.name as category_name, c.color_hex 
        FROM lessons l 
        LEFT JOIN categories c ON l.category_id = c.id 
        WHERE l.is_published = 1";

$params = [];

if ($category_id > 0) {
    $sql .= " AND l.category_id = ?";
    $params[] = $category_id;
}

if (!empty($search)) {
    $sql .= " AND (l.title LIKE ? OR l.content LIKE ? OR l.summary LIKE ?)";
    $searchTerm = '%' . $search . '%';
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
}

if (!empty($difficulty)) {
    $sql .= " AND l.difficulty = ?";
    $params[] = $difficulty;
}

$sql .= " ORDER BY l.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$lessons = $stmt->fetchAll();

// Get all categories for filter
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
                    <i class="fas fa-book text-warning"></i> Lessons
                </h1>
                <p class="lead">Explore our growing library of lessons</p>
            </div>
        </div>
    </div>
</section>

<!-- Filters -->
<section class="py-4 bg-light border-bottom">
    <div class="container">
        <form method="GET" action="<?php echo SITE_URL; ?>lessons.php" class="row g-3" data-filter-form>
            <div class="col-md-4">
                <label class="form-label">Search</label>
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search lessons..." 
                           value="<?php echo htmlspecialchars($search); ?>">
                    <button class="btn btn-warning" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label">Category</label>
                <select name="category" class="form-select" onchange="this.form.submit()">
                    <option value="0">All Categories</option>
                    <?php foreach($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>" 
                            <?php echo ($category_id == $cat['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Difficulty</label>
                <select name="difficulty" class="form-select" onchange="this.form.submit()">
                    <option value="">All Levels</option>
                    <option value="beginner" <?php echo ($difficulty == 'beginner') ? 'selected' : ''; ?>>Beginner</option>
                    <option value="intermediate" <?php echo ($difficulty == 'intermediate') ? 'selected' : ''; ?>>Intermediate</option>
                    <option value="advanced" <?php echo ($difficulty == 'advanced') ? 'selected' : ''; ?>>Advanced</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <a href="<?php echo SITE_URL; ?>lessons.php" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-undo"></i> Reset
                </a>
            </div>
        </form>
    </div>
</section>

<!-- Lessons Grid -->
<section class="py-5">
    <div class="container">
        <?php if (count($lessons) > 0): ?>
            <div class="row g-4">
                <?php foreach($lessons as $lesson): ?>
                    <div class="col-md-6 col-lg-4 reveal-on-scroll">
                        <div class="card h-100 shadow-sm hover-card border-0 lesson-card">
                            <?php if($lesson['image_path']): ?>
                                <img src="<?php echo SITE_URL . $lesson['image_path']; ?>" 
                                     class="card-img-top" alt="<?php echo htmlspecialchars($lesson['title']); ?>" 
                                     style="height: 200px; object-fit: cover;">
                            <?php else: ?>
                                <div class="bg-secondary text-white text-center py-5" style="height: 200px;">
                                    <i class="fas fa-book-open fa-3x"></i>
                                </div>
                            <?php endif; ?>
                            
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="badge" style="background: <?php echo $lesson['color_hex'] ?? '#6c757d'; ?>;">
                                        <?php echo htmlspecialchars($lesson['category_name'] ?? 'General'); ?>
                                    </span>
                                    <span class="badge bg-secondary">
                                        <?php echo ucfirst($lesson['difficulty']); ?>
                                    </span>
                                </div>
                                
                                <h5 class="card-title"><?php echo htmlspecialchars($lesson['title']); ?></h5>
                                <p class="card-text small text-muted">
                                    <?php echo htmlspecialchars(substr($lesson['summary'] ?? $lesson['content'], 0, 120)) . '...'; ?>
                                </p>
                                
                                <?php if($lesson['video_url']): ?>
                                    <span class="badge bg-danger">
                                        <i class="fas fa-play"></i> Has Video
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="card-footer bg-transparent border-0 d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="far fa-clock"></i> <?php echo $lesson['reading_time']; ?> min
                                    <i class="fas fa-eye ms-2"></i> <?php echo $lesson['view_count']; ?>
                                </small>
                                <a href="<?php echo SITE_URL; ?>lessons-details.php?id=<?php echo $lesson['id']; ?>" 
                                   class="btn btn-sm btn-primary">
                                    Read <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-book-open fa-4x text-muted mb-3"></i>
                <h3>No Lessons Found</h3>
                <p class="text-muted">Try adjusting your filters or check back later for new content.</p>
                <a href="<?php echo SITE_URL; ?>lessons.php" class="btn btn-warning">
                    <i class="fas fa-undo"></i> Clear Filters
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>