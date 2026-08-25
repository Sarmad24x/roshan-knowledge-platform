<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/functions.php';

if (!isLoggedIn() || !hasRole('admin')) {
    header('Location: ../login.php');
    exit();
}

// Handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    // Get image path to delete
    $stmt = $pdo->prepare("SELECT image_path FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch();
    
    if ($product && $product['image_path']) {
        $file_path = '../../' . $product['image_path'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }
    
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: index.php?msg=deleted');
    exit();
}

// Get all products
$products = $pdo->query("SELECT * FROM products ORDER BY created_at DESC")->fetchAll();
$page_title = 'Products';
include '../includes/admin-header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - Admin</title>
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
                        <i class="fas fa-store text-warning"></i> Products
                    </h1>
                    <a href="add.php" class="btn btn-primary rounded-pill px-4">
                        <i class="fas fa-plus"></i> Add Product
                    </a>
                </div>

                <?php if (isset($_GET['msg'])): ?>
                    <div class="alert alert-success alert-dismissible fade show rounded-4">
                        <?php 
                            if ($_GET['msg'] == 'added') echo '✅ Product added successfully!';
                            if ($_GET['msg'] == 'updated') echo '✅ Product updated successfully!';
                            if ($_GET['msg'] == 'deleted') echo '✅ Product deleted successfully!';
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Price (PKR)</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($products) > 0): ?>
                                        <?php foreach($products as $product): ?>
                                            <tr>
                                                <td>#<?php echo $product['id']; ?></td>
                                                <td>
                                                    <?php if ($product['image_path']): ?>
                                                        <img src="<?php echo '../../' . $product['image_path']; ?>" 
                                                             style="width:50px;height:50px;object-fit:cover;border-radius:12px;">
                                                    <?php else: ?>
                                                        <div style="width:50px;height:50px;background:#f0f0f0;border-radius:12px;display:flex;align-items:center;justify-content:center;">
                                                            <i class="fas fa-box text-muted"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                                <td><strong><?php echo htmlspecialchars($product['name']); ?></strong></td>
                                                <td>
                                                    <span class="badge bg-<?php echo $product['category'] == 'bundle' ? 'primary' : 'success'; ?> rounded-pill px-3 py-2">
                                                        <?php echo ucfirst($product['category']); ?>
                                                    </span>
                                                </td>
                                                <td><strong>Rs. <?php echo number_format($product['price'], 0); ?></strong></td>
                                                <td>
                                                    <span class="badge bg-<?php echo $product['product_type'] == 'digital' ? 'info' : 'secondary'; ?> rounded-pill px-3 py-2">
                                                        <?php echo ucfirst($product['product_type']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if ($product['is_active']): ?>
                                                        <span class="badge bg-success rounded-pill px-3 py-2">Active</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary rounded-pill px-3 py-2">Inactive</span>
                                                    <?php endif; ?>
                                                    <?php if ($product['is_featured']): ?>
                                                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                                                            <i class="fas fa-star"></i> Featured
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group" role="group">
                                                        <a href="edit.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-warning rounded-pill me-1">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="?delete=<?php echo $product['id']; ?>" class="btn btn-sm btn-danger rounded-pill" 
                                                           onclick="return confirm('Delete this product?')">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center py-4 text-muted">
                                                <i class="fas fa-store fa-3x d-block mb-3"></i>
                                                No products found. <a href="add.php">Add your first product</a>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>