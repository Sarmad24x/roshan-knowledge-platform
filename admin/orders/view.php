<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/functions.php';

if (!isLoggedIn() || !hasRole('admin')) {
    header('Location: ../login.php');
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) {
    header('Location: index.php');
    exit();
}

// Get order details
$stmt = $pdo->prepare("
    SELECT o.*, u.username as user_username, u.email as user_email
    FROM orders o
    LEFT JOIN users u ON o.user_id = u.id
    WHERE o.id = ?
");
$stmt->execute([$id]);
$order = $stmt->fetch();

if (!$order) {
    header('Location: index.php');
    exit();
}

// Get order items
$stmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
$stmt->execute([$id]);
$items = $stmt->fetchAll();

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $status = sanitize($_POST['status']);
    $allowed_statuses = ['pending', 'processing', 'completed', 'cancelled'];
    if (in_array($status, $allowed_statuses)) {
        $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
        header('Location: view.php?id=' . $id . '&msg=updated');
        exit();
    }
}

// Handle payment status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_payment'])) {
    $payment_status = sanitize($_POST['payment_status']);
    $allowed_payments = ['pending', 'paid', 'failed'];
    if (in_array($payment_status, $allowed_payments)) {
        $stmt = $pdo->prepare("UPDATE orders SET payment_status = ? WHERE id = ?");
        $stmt->execute([$payment_status, $id]);
        header('Location: view.php?id=' . $id . '&msg=updated');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar" style="min-height:100vh;">
                <?php include '../includes/sidebar.php'; ?>
            </nav>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">
                        <i class="fas fa-file-invoice text-warning"></i> Order Details
                    </h1>
                    <div>
                        <a href="index.php" class="btn btn-secondary rounded-pill px-4">
                            <i class="fas fa-arrow-left"></i> Back to Orders
                        </a>
                        <a href="?delete=<?php echo $id; ?>" class="btn btn-danger rounded-pill px-4 ms-2" 
                           onclick="return confirm('Delete this order?')">
                            <i class="fas fa-trash"></i> Delete Order
                        </a>
                    </div>
                </div>

                <?php if (isset($_GET['msg'])): ?>
                    <div class="alert alert-success alert-dismissible fade show rounded-4">
                        ✅ Order updated successfully!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="row g-4">
                    <!-- Order Info -->
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-header bg-transparent border-0 pt-3">
                                <h5 class="fw-bold"><i class="fas fa-info-circle text-warning"></i> Order Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <small class="text-muted">Order Number</small>
                                        <p class="fw-bold"><?php echo htmlspecialchars($order['order_number']); ?></p>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Date</small>
                                        <p><?php echo date('F j, Y h:i A', strtotime($order['created_at'])); ?></p>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Customer</small>
                                        <p><strong><?php echo htmlspecialchars($order['customer_name']); ?></strong></p>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Email</small>
                                        <p><?php echo htmlspecialchars($order['customer_email']); ?></p>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Phone</small>
                                        <p><?php echo htmlspecialchars($order['customer_phone']); ?></p>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Payment Method</small>
                                        <p><?php echo str_replace('_', ' ', ucfirst($order['payment_method'])); ?></p>
                                    </div>
                                    <?php if ($order['shipping_address']): ?>
                                        <div class="col-12">
                                            <small class="text-muted">Shipping Address</small>
                                            <p><?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></p>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($order['order_notes']): ?>
                                        <div class="col-12">
                                            <small class="text-muted">Order Notes</small>
                                            <p><?php echo nl2br(htmlspecialchars($order['order_notes'])); ?></p>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($order['user_username']): ?>
                                        <div class="col-12">
                                            <small class="text-muted">User Account</small>
                                            <p><a href="../users/index.php">@<?php echo htmlspecialchars($order['user_username']); ?></a></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Updates -->
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-header bg-transparent border-0 pt-3">
                                <h5 class="fw-bold"><i class="fas fa-cog text-warning"></i> Update Status</h5>
                            </div>
                            <div class="card-body">
                                <!-- Order Status -->
                                <form method="POST" action="" class="mb-3">
                                    <label class="form-label fw-bold">Order Status</label>
                                    <div class="d-flex gap-2">
                                        <select name="status" class="form-select">
                                            <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>⏳ Pending</option>
                                            <option value="processing" <?php echo $order['status'] == 'processing' ? 'selected' : ''; ?>>⚙️ Processing</option>
                                            <option value="completed" <?php echo $order['status'] == 'completed' ? 'selected' : ''; ?>>✅ Completed</option>
                                            <option value="cancelled" <?php echo $order['status'] == 'cancelled' ? 'selected' : ''; ?>>❌ Cancelled</option>
                                        </select>
                                        <button type="submit" name="update_status" class="btn btn-primary rounded-pill px-4">Update</button>
                                    </div>
                                    <small class="text-muted">Current status: <strong><?php echo ucfirst($order['status']); ?></strong></small>
                                </form>

                                <hr>

                                <!-- Payment Status -->
                                <form method="POST" action="">
                                    <label class="form-label fw-bold">Payment Status</label>
                                    <div class="d-flex gap-2">
                                        <select name="payment_status" class="form-select">
                                            <option value="pending" <?php echo $order['payment_status'] == 'pending' ? 'selected' : ''; ?>>⏳ Pending</option>
                                            <option value="paid" <?php echo $order['payment_status'] == 'paid' ? 'selected' : ''; ?>>✅ Paid</option>
                                            <option value="failed" <?php echo $order['payment_status'] == 'failed' ? 'selected' : ''; ?>>❌ Failed</option>
                                        </select>
                                        <button type="submit" name="update_payment" class="btn btn-success rounded-pill px-4">Update</button>
                                    </div>
                                    <small class="text-muted">Current status: <strong><?php echo ucfirst($order['payment_status']); ?></strong></small>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="card border-0 shadow-sm rounded-4 mt-4">
                    <div class="card-header bg-transparent border-0 pt-3">
                        <h5 class="fw-bold"><i class="fas fa-list text-warning"></i> Order Items</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Price (PKR)</th>
                                        <th>Subtotal (PKR)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $counter = 1;
                                    $subtotal = 0;
                                    foreach($items as $item): 
                                        $item_subtotal = $item['price'] * $item['quantity'];
                                        $subtotal += $item_subtotal;
                                    ?>
                                        <tr>
                                            <td><?php echo $counter++; ?></td>
                                            <td><strong><?php echo htmlspecialchars($item['product_name']); ?></strong></td>
                                            <td><?php echo $item['quantity']; ?></td>
                                            <td>Rs. <?php echo number_format($item['price'], 0); ?></td>
                                            <td><strong>Rs. <?php echo number_format($item_subtotal, 0); ?></strong></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr>
                                        <td colspan="4" class="text-end fw-bold">Subtotal:</td>
                                        <td><strong>Rs. <?php echo number_format($subtotal, 0); ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-end fw-bold">Delivery:</td>
                                        <td><span class="text-success">Free</span></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-end fw-bold">Total:</td>
                                        <td><strong class="text-warning h5">Rs. <?php echo number_format($order['total_amount'], 0); ?></strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Delete Order Button at Bottom -->
                <div class="text-end mt-4">
                    <a href="?delete=<?php echo $id; ?>" class="btn btn-danger rounded-pill px-5" 
                       onclick="return confirm('Delete this order? This action cannot be undone.')">
                        <i class="fas fa-trash"></i> Delete Order
                    </a>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>