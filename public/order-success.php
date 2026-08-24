<?php
$page_title = 'Order Success';
$current_page = 'order-success';
require_once '../config/database.php';
require_once '../includes/functions.php';

$order_number = isset($_GET['order']) ? $_GET['order'] : '';

require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>

<div style="padding: 60px 0; min-height: 60vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <div class="display-1 text-success mb-3">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h1 class="display-5 fw-bold">Order Placed!</h1>
                <p class="lead">Thank you for your purchase!</p>
                
                <?php if ($order_number): ?>
                    <div class="alert alert-info" style="border-radius: 16px;">
                        <strong>Order Number:</strong> <?php echo htmlspecialchars($order_number); ?>
                    </div>
                <?php endif; ?>
                
                <p class="text-muted">
                    You will receive a confirmation email with your order details.
                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                        <br><br>
                        <a href="/roshan-knowledge-platform/admin/orders/index.php" class="btn btn-primary">View Orders in Admin</a>
                    <?php endif; ?>
                </p>
                
                <div class="mt-4">
                    <a href="shop.php" class="btn btn-warning rounded-pill px-5">
                        <i class="fas fa-store"></i> Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>