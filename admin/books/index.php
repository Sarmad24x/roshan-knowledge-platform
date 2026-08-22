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
    
    $stmt = $pdo->prepare("SELECT cover_image FROM books WHERE id = ?");
    $stmt->execute([$id]);
    $book = $stmt->fetch();
    
    if ($book && $book['cover_image']) {
        $file_path = '../../' . $book['cover_image'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }
    
    $stmt = $pdo->prepare("DELETE FROM books WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: index.php?msg=deleted');
    exit();
}

$books = $pdo->query("
    SELECT b.*, c.name as category_name, c.color_hex 
    FROM books b 
    LEFT JOIN categories c ON b.category_id = c.id 
    ORDER BY b.created_at DESC
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Books - Admin</title>
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
                        <i class="fas fa-book-open text-warning"></i> Books
                    </h1>
                    <a href="add.php" class="btn btn-primary rounded-pill px-4">
                        <i class="fas fa-plus"></i> Add Book
                    </a>
                </div>

                <?php if (isset($_GET['msg'])): ?>
                    <div class="alert alert-success alert-dismissible fade show rounded-4">
                        <?php 
                            if ($_GET['msg'] == 'added') echo '✅ Book added successfully!';
                            if ($_GET['msg'] == 'updated') echo '✅ Book updated successfully!';
                            if ($_GET['msg'] == 'deleted') echo '✅ Book deleted successfully!';
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
                                        <th>Cover</th>
                                        <th>Title</th>
                                        <th>Author</th>
                                        <th>Category</th>
                                        <th>Difficulty</th>
                                        <th>Featured</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($books as $book): ?>
                                        <tr>
                                            <td>#<?php echo $book['id']; ?></td>
                                            <td>
                                                <?php if ($book['cover_image']): ?>
                                                    <img src="<?php echo '../../' . $book['cover_image']; ?>" 
                                                         style="width:50px;height:65px;object-fit:cover;border-radius:8px;">
                                                <?php else: ?>
                                                    <div style="width:50px;height:65px;background:#f0f0f0;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                                        <i class="fas fa-book text-muted"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td><strong><?php echo htmlspecialchars($book['title']); ?></strong></td>
                                            <td><i class="fas fa-user-edit text-muted"></i> <?php echo htmlspecialchars($book['author']); ?></td>
                                            <td>
                                                <span class="badge px-3 py-2" style="background: <?php echo $book['color_hex'] ?? '#6c757d'; ?>; color:white; border-radius:20px;">
                                                    <?php echo htmlspecialchars($book['category_name'] ?? 'Uncategorized'); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php echo $book['difficulty'] == 'beginner' ? 'success' : ($book['difficulty'] == 'intermediate' ? 'warning' : 'danger'); ?> rounded-pill px-3 py-2">
                                                    <?php echo ucfirst($book['difficulty']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($book['is_featured']): ?>
                                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                                                        <i class="fas fa-star"></i> Featured
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-light text-muted rounded-pill px-3 py-2">No</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="edit.php?id=<?php echo $book['id']; ?>" class="btn btn-sm btn-warning rounded-pill me-1">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="?delete=<?php echo $book['id']; ?>" class="btn btn-sm btn-danger rounded-pill" 
                                                       onclick="return confirm('Delete this book?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php if (count($books) == 0): ?>
                                        <tr>
                                            <td colspan="8" class="text-center py-4 text-muted">
                                                <i class="fas fa-book-open fa-3x d-block mb-3"></i>
                                                No books found. <a href="add.php">Add your first book</a>
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