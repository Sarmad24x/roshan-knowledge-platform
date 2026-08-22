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
    
    $stmt = $pdo->prepare("SELECT file_path FROM media WHERE id = ?");
    $stmt->execute([$id]);
    $media = $stmt->fetch();
    
    if ($media && $media['file_path']) {
        $file_path = '../../' . $media['file_path'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }
    
    $stmt = $pdo->prepare("DELETE FROM media WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: index.php?msg=deleted');
    exit();
}

$media_items = $pdo->query("
    SELECT m.*, l.title as lesson_title 
    FROM media m 
    LEFT JOIN lessons l ON m.lesson_id = l.id 
    ORDER BY m.created_at DESC
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Media - Admin</title>
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
                        <i class="fas fa-images text-warning"></i> Media Gallery
                    </h1>
                    <a href="upload.php" class="btn btn-primary rounded-pill px-4">
                        <i class="fas fa-upload"></i> Upload Media
                    </a>
                </div>

                <?php if (isset($_GET['msg'])): ?>
                    <div class="alert alert-success alert-dismissible fade show rounded-4">
                        <?php 
                            if ($_GET['msg'] == 'uploaded') echo '✅ Media uploaded successfully!';
                            if ($_GET['msg'] == 'deleted') echo '✅ Media deleted successfully!';
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body">
                        <?php if (count($media_items) > 0): ?>
                            <div class="row g-3">
                                <?php foreach($media_items as $media): ?>
                                    <div class="col-md-3 col-sm-4 col-6">
                                        <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 16px; transition: all 0.3s ease;">
                                            <?php if ($media['file_path']): ?>
                                                <?php 
                                                    $ext = strtolower(pathinfo($media['file_path'], PATHINFO_EXTENSION));
                                                    $is_video = in_array($ext, ['mp4', 'webm', 'ogg', 'mov']);
                                                ?>
                                                <?php if ($is_video): ?>
                                                    <video style="width:100%;height:180px;object-fit:cover;" controls>
                                                        <source src="<?php echo '../../' . $media['file_path']; ?>" type="video/mp4">
                                                    </video>
                                                <?php else: ?>
                                                    <img src="<?php echo '../../' . $media['file_path']; ?>" 
                                                         style="width:100%;height:180px;object-fit:cover;"
                                                         alt="<?php echo htmlspecialchars($media['caption'] ?? 'Media'); ?>">
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <div style="height:180px;background:#f0f0f0;display:flex;align-items:center;justify-content:center;">
                                                    <i class="fas fa-image fa-3x text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div class="card-body p-2">
                                                <small class="text-muted d-block text-truncate">
                                                    <?php echo htmlspecialchars($media['caption'] ?? 'No caption'); ?>
                                                </small>
                                                <?php if ($media['lesson_title']): ?>
                                                    <small class="text-primary">
                                                        <i class="fas fa-book"></i> <?php echo htmlspecialchars($media['lesson_title']); ?>
                                                    </small>
                                                <?php endif; ?>
                                                <div class="mt-1">
                                                    <a href="?delete=<?php echo $media['id']; ?>" class="btn btn-sm btn-danger rounded-pill" 
                                                       onclick="return confirm('Delete this media?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-images fa-4x d-block mb-3"></i>
                                <h5>No media uploaded yet</h5>
                                <p>Upload images and videos to use in your lessons.</p>
                                <a href="upload.php" class="btn btn-primary rounded-pill px-4">
                                    <i class="fas fa-upload"></i> Upload Media
                                </a>
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