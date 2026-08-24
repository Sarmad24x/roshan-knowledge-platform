<?php
$page_title = 'Shop';
$current_page = 'shop';
require_once '../config/database.php';
require_once '../includes/functions.php';
require_once '../includes/cart-functions.php';

// Get products
$category = isset($_GET['category']) ? $_GET['category'] : '';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$sql = "SELECT * FROM products WHERE is_active = 1";
$params = [];

if (!empty($category)) {
    $sql .= " AND category = ?";
    $params[] = $category;
}

if (!empty($search)) {
    $sql .= " AND (name LIKE ? OR description LIKE ?)";
    $searchTerm = '%' . $search . '%';
    $params[] = $searchTerm;
    $params[] = $searchTerm;
}

$sql .= " ORDER BY is_featured DESC, created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

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
                    <i class="fas fa-store text-warning"></i> Roshan Shop
                </h1>
                <p class="lead text-white-50">Premium study guides & lesson bundles</p>
                <div class="mt-3">
                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">
                        <i class="fas fa-shopping-bag"></i> <?php echo count($products); ?> Products Available
                    </span>
                    <a href="cart.php" class="badge bg-info text-white px-3 py-2 rounded-pill ms-2">
                        <i class="fas fa-shopping-cart"></i> Cart (<?php echo getCartCount(); ?>)
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- FILTERS -->
<!-- ============================================================ -->
<div style="background: #f8f9fa; border-bottom: 1px solid #eee; padding: 15px 0;">
    <div class="container">
        <form method="GET" action="<?php echo SITE_URL; ?>shop.php" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-bold small text-muted"><i class="fas fa-search text-warning"></i> Search</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold small text-muted"><i class="fas fa-tag text-warning"></i> Category</label>
                <select name="category" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Products</option>
                    <option value="bundle" <?php echo $category == 'bundle' ? 'selected' : ''; ?>>Lesson Bundles</option>
                    <option value="guide" <?php echo $category == 'guide' ? 'selected' : ''; ?>>Study Guides</option>
                </select>
            </div>
            <div class="col-md-2">
                <a href="<?php echo SITE_URL; ?>shop.php" class="btn btn-outline-secondary btn-sm w-100">
                    <i class="fas fa-undo"></i> Reset
                </a>
            </div>
            <div class="col-md-3 text-end">
                <a href="cart.php" class="btn btn-warning btn-sm w-100">
                    <i class="fas fa-shopping-cart"></i> Cart (<?php echo getCartCount(); ?>)
                </a>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================ -->
<!-- PRODUCTS GRID -->
<!-- ============================================================ -->
<div style="padding: 30px 0;">
    <div class="container">
        <?php if (count($products) > 0): ?>
            <div class="row g-4">
                <?php foreach($products as $product): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm border-0 overflow-hidden" 
                             style="border-radius: 16px; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                            
                            <?php if($product['image_path']): ?>
                                <img src="<?php echo SITE_URL . $product['image_path']; ?>" 
                                     class="card-img-top" 
                                     alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                     style="height: 200px; object-fit: cover;">
                            <?php else: ?>
                                <div style="height: 200px; background: linear-gradient(135deg, #1a1a3e, #2d2d5e); display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-box-open fa-4x text-warning" style="opacity: 0.3;"></i>
                                </div>
                            <?php endif; ?>
                            
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="badge bg-<?php echo $product['category'] == 'bundle' ? 'primary' : 'success'; ?> rounded-pill px-3 py-2">
                                        <?php echo $product['category'] == 'bundle' ? '📦 Bundle' : '📖 Guide'; ?>
                                    </span>
                                    <?php if($product['is_featured']): ?>
                                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                                            <i class="fas fa-star"></i> Featured
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <h5 class="card-title fw-bold"><?php echo htmlspecialchars($product['name']); ?></h5>
                                <p class="card-text small text-muted">
                                    <?php echo htmlspecialchars(substr($product['description'], 0, 100)) . '...'; ?>
                                </p>
                                
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <div>
                                        <?php if($product['sale_price'] && $product['sale_price'] < $product['price']): ?>
                                            <span class="text-muted text-decoration-line-through small">Rs. <?php echo number_format($product['price'], 0); ?></span>
                                            <span class="h5 text-danger fw-bold">Rs. <?php echo number_format($product['sale_price'], 0); ?></span>
                                        <?php else: ?>
                                            <span class="h5 fw-bold text-success">Rs. <?php echo number_format($product['price'], 0); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <span class="badge bg-<?php echo $product['product_type'] == 'digital' ? 'info' : 'secondary'; ?> rounded-pill">
                                        <?php echo $product['product_type'] == 'digital' ? '💻 Digital' : '📦 Physical'; ?>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="card-footer bg-transparent border-0 d-flex gap-2">
                                <a href="product.php?id=<?php echo $product['id']; ?>" 
                                   class="btn btn-outline-primary btn-sm flex-grow-1 rounded-pill">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <form method="POST" action="cart.php" style="flex:1;">
                                    <input type="hidden" name="add_to_cart" value="<?php echo $product['id']; ?>">
                                    <button type="submit" class="btn btn-warning btn-sm w-100 rounded-pill">
                                        <i class="fas fa-cart-plus"></i> Add
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-store fa-4x text-muted mb-3"></i>
                <h3>No Products Found</h3>
                <p class="text-muted">Check back later for new study materials!</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- ============================================================ -->
<!-- STYLES -->
<!-- ============================================================ -->
<style>
.card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
}
</style>

<?php require_once '../includes/footer.php'; ?>