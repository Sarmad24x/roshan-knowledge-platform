<?php
$page_title = 'Product Details';
$current_page = 'shop';
require_once '../config/database.php';
require_once '../includes/functions.php';
require_once '../includes/cart-functions.php';

// Get product ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    header('Location: shop.php');
    exit();
}

// Get product details
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND is_active = 1");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: shop.php');
    exit();
}

// Increment view count
$stmt = $pdo->prepare("UPDATE products SET views_count = views_count + 1 WHERE id = ?");
$stmt->execute([$id]);

// Get related products (same category)
$stmt = $pdo->prepare("SELECT * FROM products WHERE category = ? AND id != ? AND is_active = 1 ORDER BY RAND() LIMIT 4");
$stmt->execute([$product['category'], $id]);
$related_products = $stmt->fetchAll();

// Handle add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    addToCart($product['id'], $quantity);
    header('Location: cart.php?added=1');
    exit();
}

require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>

<!-- ============================================================ -->
<!-- PRODUCT DETAIL -->
<!-- ============================================================ -->
<div style="padding: 30px 0;">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="shop.php" class="text-decoration-none text-warning">Shop</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($product['name']); ?></li>
            </ol>
        </nav>

        <div class="row g-4">
            <!-- Product Image -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 20px;">
                    <?php if($product['image_path']): ?>
                        <img src="<?php echo SITE_URL . $product['image_path']; ?>" 
                             class="img-fluid" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>"
                             style="width:100%; min-height: 400px; max-height: 500px; object-fit: cover;">
                    <?php else: ?>
                        <div style="min-height: 400px; background: linear-gradient(135deg, #1a1a3e, #2d2d5e); display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-box-open fa-6x text-warning" style="opacity: 0.3;"></i>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm" style="border-radius: 20px; overflow: hidden;">
                    <div class="card-body p-4">
                        <!-- Badges -->
                        <div class="d-flex gap-2 mb-3 flex-wrap">
                            <span class="badge bg-<?php echo $product['category'] == 'bundle' ? 'primary' : 'success'; ?> rounded-pill px-3 py-2">
                                <?php echo $product['category'] == 'bundle' ? '📦 Lesson Bundle' : '📖 Study Guide'; ?>
                            </span>
                            <?php if($product['is_featured']): ?>
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                                    <i class="fas fa-star"></i> Featured
                                </span>
                            <?php endif; ?>
                            <span class="badge bg-<?php echo $product['product_type'] == 'digital' ? 'info' : 'secondary'; ?> rounded-pill px-3 py-2">
                                <?php echo $product['product_type'] == 'digital' ? '💻 Digital Download' : '📦 Physical Product'; ?>
                            </span>
                        </div>

                        <!-- Title -->
                        <h1 class="display-6 fw-bold"><?php echo htmlspecialchars($product['name']); ?></h1>

                        <!-- Price -->
                        <div class="mt-3">
                            <?php if($product['sale_price'] && $product['sale_price'] < $product['price']): ?>
                                <span class="text-muted text-decoration-line-through h4">Rs. <?php echo number_format($product['price'], 0); ?></span>
                                <span class="h2 text-danger fw-bold ms-2">Rs. <?php echo number_format($product['sale_price'], 0); ?></span>
                                <span class="badge bg-danger rounded-pill ms-2 px-3 py-2">SAVE <?php echo round((($product['price'] - $product['sale_price']) / $product['price']) * 100); ?>%</span>
                            <?php else: ?>
                                <span class="h2 fw-bold text-success">Rs. <?php echo number_format($product['price'], 0); ?></span>
                            <?php endif; ?>
                        </div>

                        <!-- Stock -->
                        <div class="mt-2">
                            <?php if($product['stock_quantity'] > 0): ?>
                                <span class="text-success"><i class="fas fa-check-circle"></i> In Stock (<?php echo $product['stock_quantity']; ?> available)</span>
                            <?php else: ?>
                                <span class="text-danger"><i class="fas fa-times-circle"></i> Out of Stock</span>
                            <?php endif; ?>
                            <?php if($product['product_type'] == 'digital'): ?>
                                <span class="text-muted ms-2"><i class="fas fa-download"></i> Instant Download</span>
                            <?php endif; ?>
                        </div>

                        <!-- Description -->
                        <div class="mt-4">
                            <h6 class="fw-bold">Description</h6>
                            <p class="text-muted" style="line-height: 1.8;">
                                <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                            </p>
                        </div>

                        <!-- Add to Cart Form -->
                        <form method="POST" action="" class="mt-4">
                            <div class="row g-2 align-items-end">
                                <div class="col-4">
                                    <label class="form-label fw-bold small">Quantity</label>
                                    <input type="number" name="quantity" class="form-control text-center" value="1" min="1" max="<?php echo $product['stock_quantity'] > 0 ? $product['stock_quantity'] : 99; ?>">
                                </div>
                                <div class="col-8">
                                    <button type="submit" name="add_to_cart" class="btn btn-warning btn-lg w-100 rounded-pill py-3" <?php echo $product['stock_quantity'] == 0 && $product['product_type'] != 'digital' ? 'disabled' : ''; ?>>
                                        <i class="fas fa-cart-plus me-2"></i> Add to Cart
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- Secure Checkout Badge -->
                        <div class="mt-3">
                            <div class="d-flex gap-3 flex-wrap">
                                <span class="text-muted small"><i class="fas fa-lock text-success"></i> Secure Checkout</span>
                                <span class="text-muted small"><i class="fas fa-credit-card text-success"></i> Easy Payment</span>
                                <span class="text-muted small"><i class="fas fa-download text-success"></i> Instant Access</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        <?php if(count($related_products) > 0): ?>
            <div class="mt-5">
                <h3 class="fw-bold mb-4">Related Products</h3>
                <div class="row g-4">
                    <?php foreach($related_products as $related): ?>
                        <div class="col-md-3 col-6">
                            <div class="card border-0 shadow-sm h-100 text-center overflow-hidden" style="border-radius: 16px; transition: transform 0.3s ease;">
                                <?php if($related['image_path']): ?>
                                    <img src="<?php echo SITE_URL . $related['image_path']; ?>" 
                                         class="card-img-top" 
                                         alt="<?php echo htmlspecialchars($related['name']); ?>"
                                         style="height: 150px; object-fit: cover;">
                                <?php else: ?>
                                    <div style="height: 150px; background: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-box fa-2x text-muted"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="card-body p-2">
                                    <h6 class="card-title small fw-bold"><?php echo htmlspecialchars($related['name']); ?></h6>
                                    <p class="card-text fw-bold text-success small">Rs. <?php echo number_format($related['price'], 0); ?></p>
                                    <a href="product.php?id=<?php echo $related['id']; ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        View
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- ============================================================ -->
<!-- STYLES -->
<!-- ============================================================ -->
<style>
.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.08) !important;
}
.breadcrumb-item a:hover {
    text-decoration: underline !important;
}
</style>

<?php require_once '../includes/footer.php'; ?>