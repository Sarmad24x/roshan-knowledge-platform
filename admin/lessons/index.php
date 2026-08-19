<?php
// admin/lessons/index.php
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
    
    // Get image path to delete file
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

// Get all lessons with category names
$lessons = $pdo->query("
    SELECT l.*, c.name as category_name 
    FROM lessons l 
    LEFT JOIN categories c ON l.category_id = c.id 
    ORDER BY l.created_at DESC
")->fetchAll();
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
            <nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar">
                <?php include '../includes/sidebar.php'; ?>
            </nav>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Lessons</h1>
                    <a href="add.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Lesson
                    </a>
                </div>

                <?php if (isset($_GET['msg'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?php 
                            if ($_GET['msg'] == 'added') echo 'Lesson added successfully!';
                            if ($_GET['msg'] == 'updated') echo 'Lesson updated successfully!';
                            if ($_GET['msg'] == 'deleted') echo 'Lesson deleted successfully!';
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Image</th>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Difficulty</th>
                                        <th>Views</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($lessons) > 0): ?>
                                        <?php foreach($lessons as $lesson): ?>
                                            <tr>
                                                <td><?php echo $lesson['id']; ?></td>
                                                <td>
                                                    <?php if ($lesson['image_path']): ?>
                                                        <img src="<?php echo '../../' . $lesson['image_path']; ?>" 
                                                             style="width:50px;height:50px;object-fit:cover;border-radius:8px;">
                                                    <?php else: ?>
                                                        <div style="width:50px;height:50px;background:#eee;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                                            <i class="fas fa-book text-muted"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($lesson['title']); ?></td>
                                                <td><?php echo htmlspecialchars($lesson['category_name'] ?? 'Uncategorized'); ?></td>
                                                <td>
                                                    <span class="badge bg-<?php echo $lesson['difficulty'] == 'beginner' ? 'success' : ($lesson['difficulty'] == 'intermediate' ? 'warning' : 'danger'); ?>">
                                                        <?php echo ucfirst($lesson['difficulty']); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo $lesson['view_count']; ?></td>
                                                <td>
                                                    <?php if ($lesson['is_published']): ?>
                                                        <span class="status-badge published">Published</span>
                                                    <?php else: ?>
                                                        <span class="status-badge draft">Draft</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a href="<?php echo SITE_URL; ?>lesson-detail.php?id=<?php echo $lesson['id']; ?>" 
                                                       class="btn btn-sm btn-info" target="_blank">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="edit.php?id=<?php echo $lesson['id']; ?>" class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="?delete=<?php echo $lesson['id']; ?>" class="btn btn-sm btn-danger" 
                                                       onclick="return confirm('Delete this lesson?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center py-4">
                                                <i class="fas fa-book fa-3x text-muted mb-3 d-block"></i>
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