<?php
$page_title = 'Checkout';
$current_page = 'checkout';
require_once '../config/database.php';
require_once '../includes/functions.php';
require_once '../includes/cart-functions.php';

// Check if cart has items
$cart_data = getCartItems($pdo);
$items = $cart_data['items'];
$total = $cart_data['total'];

if (count($items) == 0) {
    header('Location: shop.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_name = sanitize($_POST['customer_name'] ?? '');
    $customer_email = filter_var($_POST['customer_email'] ?? '', FILTER_SANITIZE_EMAIL);
    $customer_phone = sanitize($_POST['customer_phone'] ?? '');
    $shipping_address = sanitize($_POST['shipping_address'] ?? '');
    $payment_method = sanitize($_POST['payment_method'] ?? 'easypaisa');
    $order_notes = sanitize($_POST['order_notes'] ?? '');
    
    if (empty($customer_name) || empty($customer_email) || empty($customer_phone)) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        try {
            // Generate order number
            $order_number = 'ROSHAN-' . date('Ymd') . '-' . rand(1000, 9999);
            
            // Insert order
            $stmt = $pdo->prepare("INSERT INTO orders 
                (order_number, user_id, customer_name, customer_email, customer_phone, 
                 shipping_address, order_notes, subtotal, total_amount, payment_method) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            $user_id = isLoggedIn() ? $_SESSION['user_id'] : null;
            $stmt->execute([
                $order_number, $user_id, $customer_name, $customer_email, $customer_phone,
                $shipping_address, $order_notes, $total, $total, $payment_method
            ]);
            
            $order_id = $pdo->lastInsertId();
            
            // Insert order items
            foreach ($items as $item) {
                $product = $item['product'];
                $stmt = $pdo->prepare("INSERT INTO order_items 
                    (order_id, product_id, product_name, quantity, price) 
                    VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([
                    $order_id, $product['id'], $product['name'], 
                    $item['quantity'], $product['price']
                ]);
            }
            
            // Clear cart
            clearCart();
            
            // Redirect to success page
            header('Location: order-success.php?order=' . $order_number);
            exit();
            
        } catch (Exception $e) {
            $error = 'An error occurred. Please try again.';
        }
    }
}

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
                    <i class="fas fa-credit-card text-warning"></i> Checkout
                </h1>
                <p class="lead text-white-50">Complete your order</p>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- CHECKOUT CONTENT -->
<!-- ============================================================ -->
<div style="padding: 30px 0;">
    <div class="container">
        <div class="row g-4">
            <!-- Checkout Form -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">
                    <div class="card-body">
                        <h5 class="fw-bold">Shipping Details</h5>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="customer_name" class="form-control" required 
                                           value="<?php echo isLoggedIn() ? ($_SESSION['user_full_name'] ?? '') : ''; ?>">
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" name="customer_email" class="form-control" required 
                                           value="<?php echo isLoggedIn() ? ($_SESSION['user_email'] ?? '') : ''; ?>">
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                    <input type="text" name="customer_phone" class="form-control" required placeholder="03XX-XXXXXXX">
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label">Shipping Address <span class="text-muted small">(For physical items)</span></label>
                                    <textarea name="shipping_address" class="form-control" rows="3" placeholder="City, District, Province"></textarea>
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                                    <select name="payment_method" class="form-select" required>
                                        <option value="easypaisa">Easypaisa</option>
                                        <option value="jazzcash">JazzCash</option>
                                        <option value="bank_transfer">Bank Transfer</option>
                                        <option value="cash_on_delivery">Cash on Delivery</option>
                                    </select>
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label">Order Notes</label>
                                    <textarea name="order_notes" class="form-control" rows="2" placeholder="Any special instructions"></textarea>
                                </div>
                                
                                <div class="col-12">
                                    <button type="submit" class="btn btn-success btn-lg w-100 rounded-pill">
                                        <i class="fas fa-check-circle"></i> Place Order (Rs. <?php echo number_format($total, 0); ?>)
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Order Summary -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">
                    <div class="card-body">
                        <h5 class="fw-bold">Your Order</h5>
                        <hr>
                        <?php foreach($items as $item): ?>
                            <div class="d-flex justify-content-between mb-2">
                                <span><?php echo htmlspecialchars($item['product']['name']); ?> × <?php echo $item['quantity']; ?></span>
                                <span>Rs. <?php echo number_format($item['subtotal'], 0); ?></span>
                            </div>
                        <?php endforeach; ?>
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>Rs. <?php echo number_format($total, 0); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Delivery:</span>
                            <span class="text-success">Free</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <h6 class="fw-bold">Total:</h6>
                            <h6 class="fw-bold text-warning">Rs. <?php echo number_format($total, 0); ?></h6>
                        </div>
                        
                        <div class="alert alert-info mt-3 small" style="border-radius: 12px;">
                            <i class="fas fa-info-circle"></i> 
                            All digital products will be delivered via email after payment confirmation.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>