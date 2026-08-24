<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/functions.php';

if (!isLoggedIn() || !hasRole('admin')) {
    header('Location: ../login.php');
    exit();
}

// Handle status update
if (isset($_GET['update_status']) && isset($_GET['status'])) {
    $order_id = (int)$_GET['update_status'];
    $status = sanitize($_GET['status']);
    
    $allowed_statuses = ['pending', 'processing', 'completed', 'cancelled'];
    if (in_array($status, $allowed_statuses)) {
        $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->execute([$status, $order_id]);
        header('Location: index.php?msg=updated');
        exit();
    }
}

// Handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM orders WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: index.php?msg=deleted');
    exit();
}

// Get all orders with item counts
$orders = $pdo->query("
    SELECT o.*, 
           COUNT(oi.id) as item_count,
           u.username as user_username
    FROM orders o
    LEFT JOIN order_items oi ON o.id = oi.order_id
    LEFT JOIN users u ON o.user_id = u.id
    GROUP BY o.id
    ORDER BY o.created_at DESC
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders - Admin</title>
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
                        <i class="fas fa-shopping-bag text-warning"></i> Orders
                    </h1>
                    <span class="badge bg-primary rounded-pill px-3 py-2">
                        <i class="fas fa-file-invoice"></i> <?php echo count($orders); ?> Total Orders
                    </span>
                </div>

                <?php if (isset($_GET['msg'])): ?>
                    <div class="alert alert-success alert-dismissible fade show rounded-4">
                        <?php 
                            if ($_GET['msg'] == 'updated') echo '✅ Order status updated successfully!';
                            if ($_GET['msg'] == 'deleted') echo '✅ Order deleted successfully!';
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body">
                        <?php if (count($orders) > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Order #</th>
                                            <th>Customer</th>
                                            <th>Email</th>
                                            <th>Items</th>
                                            <th>Total (PKR)</th>
                                            <th>Payment</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($orders as $order): ?>
                                            <tr>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($order['order_number']); ?></strong>
                                                </td>
                                                <td>
                                                    <div>
                                                        <strong><?php echo htmlspecialchars($order['customer_name']); ?></strong>
                                                        <?php if ($order['user_username']): ?>
                                                            <br>
                                                            <small class="text-muted">
                                                                <i class="fas fa-user"></i> <?php echo htmlspecialchars($order['user_username']); ?>
                                                            </small>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <small><?php echo htmlspecialchars($order['customer_email']); ?></small>
                                                    <br>
                                                    <small class="text-muted"><?php echo htmlspecialchars($order['customer_phone']); ?></small>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-info rounded-pill px-3 py-2">
                                                        <?php echo $order['item_count']; ?> items
                                                    </span>
                                                </td>
                                                <td>
                                                    <strong>Rs. <?php echo number_format($order['total_amount'], 0); ?></strong>
                                                </td>
                                                <td>
                                                    <span class="badge bg-<?php echo $order['payment_status'] == 'paid' ? 'success' : ($order['payment_status'] == 'pending' ? 'warning' : 'danger'); ?> rounded-pill px-3 py-2">
                                                        <?php echo ucfirst($order['payment_status']); ?>
                                                    </span>
                                                    <br>
                                                    <small class="text-muted">
                                                        <?php echo str_replace('_', ' ', ucfirst($order['payment_method'])); ?>
                                                    </small>
                                                </td>
                                                <td>
                                                    <?php
                                                    $status_colors = [
                                                        'pending' => 'warning',
                                                        'processing' => 'info',
                                                        'completed' => 'success',
                                                        'cancelled' => 'danger'
                                                    ];
                                                    ?>
                                                    <form method="GET" action="" class="d-flex flex-column gap-1">
                                                        <input type="hidden" name="update_status" value="<?php echo $order['id']; ?>">
                                                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()" style="min-width:100px;">
                                                            <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>⏳ Pending</option>
                                                            <option value="processing" <?php echo $order['status'] == 'processing' ? 'selected' : ''; ?>>⚙️ Processing</option>
                                                            <option value="completed" <?php echo $order['status'] == 'completed' ? 'selected' : ''; ?>>✅ Completed</option>
                                                            <option value="cancelled" <?php echo $order['status'] == 'cancelled' ? 'selected' : ''; ?>>❌ Cancelled</option>
                                                        </select>
                                                    </form>
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        <?php echo date('M d, Y h:i A', strtotime($order['created_at'])); ?>
                                                    </small>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group" role="group">
                                                        <a href="view.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-info rounded-pill me-1">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="?delete=<?php echo $order['id']; ?>" class="btn btn-sm btn-danger rounded-pill" 
                                                           onclick="return confirm('Delete this order? This action cannot be undone.')">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-shopping-bag fa-4x d-block mb-3"></i>
                                <h5>No orders yet</h5>
                                <p>When customers place orders, they will appear here.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>