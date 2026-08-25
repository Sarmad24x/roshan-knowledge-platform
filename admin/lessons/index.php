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
    
    $stmt = $pdo->prepare("SELECT image_path FROM lessons WHERE id = ?");
    $stmt->execute([$id]);
    $lesson = $stmt->fetch();
    
    if ($lesson && $lesson['image_path']) {
        $file_path = '../../' . $lesson['image_path'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }
    
    $stmt = $pdo->prepare("DELETE FROM lessons WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: index.php?msg=deleted');
    exit();
}

$lessons = $pdo->query("
    SELECT l.*, c.name as category_name, c.color_hex 
    FROM lessons l 
    LEFT JOIN categories c ON l.category_id = c.id 
    ORDER BY l.created_at DESC
")->fetchAll();
$page_title = 'Lessons';
include '../includes/admin-header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Lessons - Admin</title>
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
                        <i class="fas fa-book text-warning"></i> Lessons
                    </h1>
                    <a href="add.php" class="btn btn-primary rounded-pill px-4">
                        <i class="fas fa-plus"></i> Add Lesson
                    </a>
                </div>

                <?php if (isset($_GET['msg'])): ?>
                    <div class="alert alert-success alert-dismissible fade show rounded-4">
                        <?php 
                            if ($_GET['msg'] == 'added') echo '✅ Lesson added successfully!';
                            if ($_GET['msg'] == 'updated') echo '✅ Lesson updated successfully!';
                            if ($_GET['msg'] == 'deleted') echo '✅ Lesson deleted successfully!';
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
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Difficulty</th>
                                        <th>Views</th>
                                        <th>Status</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($lessons) > 0): ?>
                                        <?php foreach($lessons as $lesson): ?>
                                            <tr>
                                                <td>#<?php echo $lesson['id']; ?></td>
                                                <td>
                                                    <?php if ($lesson['image_path']): ?>
                                                        <img src="<?php echo '../../' . $lesson['image_path']; ?>" 
                                                             style="width:50px;height:50px;object-fit:cover;border-radius:12px;">
                                                    <?php else: ?>
                                                        <div style="width:50px;height:50px;background:#f0f0f0;border-radius:12px;display:flex;align-items:center;justify-content:center;">
                                                            <i class="fas fa-book text-muted"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                                <td><strong><?php echo htmlspecialchars($lesson['title']); ?></strong></td>
                                                <td>
                                                    <span class="badge px-3 py-2" style="background: <?php echo $lesson['color_hex'] ?? '#6c757d'; ?>; color:white; border-radius:20px;">
                                                        <?php echo htmlspecialchars($lesson['category_name'] ?? 'Uncategorized'); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-<?php echo $lesson['difficulty'] == 'beginner' ? 'success' : ($lesson['difficulty'] == 'intermediate' ? 'warning' : 'danger'); ?> rounded-pill px-3 py-2">
                                                        <?php echo ucfirst($lesson['difficulty']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <i class="fas fa-eye text-muted"></i> <?php echo $lesson['view_count']; ?>
                                                </td>
                                                <td>
                                                    <?php if ($lesson['is_published']): ?>
                                                        <span class="badge bg-success rounded-pill px-3 py-2">Published</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary rounded-pill px-3 py-2">Draft</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group" role="group">
                                                        <a href="<?php echo SITE_URL; ?>lessons-details.php?id=<?php echo $lesson['id']; ?>" 
                                                           class="btn btn-sm btn-info rounded-pill me-1" target="_blank">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="edit.php?id=<?php echo $lesson['id']; ?>" class="btn btn-sm btn-warning rounded-pill me-1">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="?delete=<?php echo $lesson['id']; ?>" class="btn btn-sm btn-danger rounded-pill" 
                                                           onclick="return confirm('Delete this lesson?')">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center py-4 text-muted">
                                                <i class="fas fa-book fa-3x d-block mb-3"></i>
                                                No lessons found. <a href="add.php">Create your first lesson</a>
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