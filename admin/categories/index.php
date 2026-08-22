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
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: index.php?msg=deleted');
    exit();
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories - Admin</title>
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
                        <i class="fas fa-layer-group text-warning"></i> Categories
                    </h1>
                    <a href="add.php" class="btn btn-primary rounded-pill px-4">
                        <i class="fas fa-plus"></i> Add Category
                    </a>
                </div>

                <?php if (isset($_GET['msg'])): ?>
                    <div class="alert alert-success alert-dismissible fade show rounded-4">
                        <?php 
                            if ($_GET['msg'] == 'added') echo '✅ Category added successfully!';
                            if ($_GET['msg'] == 'updated') echo '✅ Category updated successfully!';
                            if ($_GET['msg'] == 'deleted') echo '✅ Category deleted successfully!';
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
                                        <th>Icon</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Color</th>
                                        <th>Status</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($categories as $cat): ?>
                                        <tr>
                                            <td>#<?php echo $cat['id']; ?></td>
                                            <td><i class="fas <?php echo $cat['icon_class']; ?> fa-lg" style="color: <?php echo $cat['color_hex']; ?>;"></i></td>
                                            <td><strong><?php echo htmlspecialchars($cat['name']); ?></strong></td>
                                            <td><?php echo htmlspecialchars(substr($cat['description'], 0, 40)) . (strlen($cat['description']) > 40 ? '...' : ''); ?></td>
                                            <td>
                                                <span style="display:inline-block;width:30px;height:30px;border-radius:8px;background:<?php echo $cat['color_hex']; ?>;border:2px solid #ddd;"></span>
                                                <code><?php echo $cat['color_hex']; ?></code>
                                            </td>
                                            <td>
                                                <?php if ($cat['is_active']): ?>
                                                    <span class="badge bg-success rounded-pill px-3 py-2">Active</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary rounded-pill px-3 py-2">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="edit.php?id=<?php echo $cat['id']; ?>" class="btn btn-sm btn-warning rounded-pill me-1">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="?delete=<?php echo $cat['id']; ?>" class="btn btn-sm btn-danger rounded-pill" 
                                                       onclick="return confirm('Delete this category? This will also delete all lessons in it!')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php if (count($categories) == 0): ?>
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted">
                                                <i class="fas fa-layer-group fa-3x d-block mb-3"></i>
                                                No categories found. <a href="add.php">Create your first category</a>
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