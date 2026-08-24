<?php
$page_title = 'Cart';
$current_page = 'cart';
require_once '../config/database.php';
require_once '../includes/functions.php';
require_once '../includes/cart-functions.php';

// Handle add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_to_cart'])) {
        $product_id = (int)$_POST['add_to_cart'];
        addToCart($product_id);
        header('Location: ' . SITE_URL . 'shop.php?added=1');
        exit();
    }
    
    if (isset($_POST['update_cart'])) {
        foreach ($_POST['quantity'] as $id => $qty) {
            updateCartQuantity((int)$id, (int)$qty);
        }
        header('Location: ' . SITE_URL . 'cart.php');
        exit();
    }
    
    if (isset($_POST['remove_item'])) {
        removeFromCart((int)$_POST['remove_item']);
        header('Location: ' . SITE_URL . 'cart.php');
        exit();
    }
}

$cart_data = getCartItems($pdo);
$items = $cart_data['items'];
$total = $cart_data['total'];

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
                    <i class="fas fa-shopping-cart text-warning"></i> Shopping Cart
                </h1>
                <p class="lead text-white-50">Review your items before checkout</p>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- CART CONTENT -->
<!-- ============================================================ -->
<div style="padding: 30px 0;">
    <div class="container">
        <?php if (count($items) > 0): ?>
            <div class="row g-4">
                <!-- Cart Items -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">
                        <div class="card-body">
                            <form method="POST" action="">
                                <?php foreach($items as $item): 
                                    $product = $item['product'];
                                ?>
                                    <div class="row align-items-center border-bottom pb-3 mb-3">
                                        <div class="col-2">
                                            <?php if($product['image_path']): ?>
                                                <img src="<?php echo SITE_URL . $product['image_path']; ?>" 
                                                     class="img-fluid rounded" 
                                                     alt="<?php echo htmlspecialchars($product['name']); ?>"
                                                     style="max-height: 80px; object-fit: cover;">
                                            <?php else: ?>
                                                <div style="width:80px;height:80px;background:#f0f0f0;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                                    <i class="fas fa-box text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-4">
                                            <h6 class="mb-0"><?php echo htmlspecialchars($product['name']); ?></h6>
                                            <small class="text-muted"><?php echo ucfirst($product['category']); ?></small>
                                        </div>
                                        <div class="col-2">
                                            <input type="number" name="quantity[<?php echo $product['id']; ?>]" 
                                                   value="<?php echo $item['quantity']; ?>" 
                                                   min="1" max="99" class="form-control form-control-sm text-center">
                                        </div>
                                        <div class="col-2 text-center">
                                            <span class="fw-bold">Rs. <?php echo number_format($item['subtotal'], 0); ?></span>
                                        </div>
                                        <div class="col-2 text-end">
                                            <button type="submit" name="remove_item" value="<?php echo $product['id']; ?>" 
                                                    class="btn btn-sm btn-danger rounded-pill">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                                
                                <div class="d-flex justify-content-between mt-3">
                                    <a href="shop.php" class="btn btn-outline-secondary rounded-pill px-4">
                                        <i class="fas fa-arrow-left"></i> Continue Shopping
                                    </a>
                                    <button type="submit" name="update_cart" class="btn btn-primary rounded-pill px-4">
                                        <i class="fas fa-sync"></i> Update Cart
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Cart Summary -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">
                        <div class="card-body">
                            <h5 class="fw-bold">Order Summary</h5>
                            <hr>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span class="fw-bold">Rs. <?php echo number_format($total, 0); ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Delivery:</span>
                                <span class="text-success">Free</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <h6 class="fw-bold">Total:</h6>
                                <h6 class="fw-bold text-warning">Rs. <?php echo number_format($total, 0); ?></h6>
                            </div>
                            <a href="checkout.php" class="btn btn-warning w-100 rounded-pill py-2">
                                <i class="fas fa-credit-card"></i> Proceed to Checkout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                <h3>Your Cart is Empty</h3>
                <p class="text-muted">Browse our shop and add some items!</p>
                <a href="shop.php" class="btn btn-warning rounded-pill px-5">
                    <i class="fas fa-store"></i> Shop Now
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>