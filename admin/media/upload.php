<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/functions.php';

if (!isLoggedIn() || !hasRole('admin')) {
    header('Location: ../login.php');
    exit();
}

$error = '';
$success = '';

// Get lessons for dropdown
$lessons = $pdo->query("SELECT id, title FROM lessons ORDER BY title")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lesson_id = isset($_POST['lesson_id']) && $_POST['lesson_id'] != '' ? (int)$_POST['lesson_id'] : null;
    $caption = sanitize($_POST['caption'] ?? '');
    $media_type = sanitize($_POST['media_type'] ?? 'image');
    
    if (isset($_FILES['file']) && $_FILES['file']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'mp4', 'webm', 'ogg'];
        $filename = $_FILES['file']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $new_filename = time() . '_' . uniqid() . '.' . $ext;
            $upload_path = '../../assets/images/uploads/media/';
            
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }
            
            if (move_uploaded_file($_FILES['file']['tmp_name'], $upload_path . $new_filename)) {
                $file_path = 'assets/images/uploads/media/' . $new_filename;
                
                $stmt = $pdo->prepare("INSERT INTO media (lesson_id, media_type, file_path, caption) VALUES (?, ?, ?, ?)");
                if ($stmt->execute([$lesson_id, $media_type, $file_path, $caption])) {
                    header('Location: index.php?msg=uploaded');
                    exit();
                } else {
                    $error = 'Failed to save to database.';
                }
            } else {
                $error = 'Failed to upload file.';
            }
        } else {
            $error = 'Invalid file type. Allowed: ' . implode(', ', $allowed);
        }
    } else {
        $error = 'Please select a file.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Media - Admin</title>
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
                        <i class="fas fa-upload text-warning"></i> Upload Media
                    </h1>
                    <a href="index.php" class="btn btn-secondary rounded-pill px-4">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger rounded-4"><?php echo $error; ?></div>
                <?php endif; ?>

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <form method="POST" action="" enctype="multipart/form-data">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-bold">Upload File <span class="text-danger">*</span></label>
                                    <input type="file" name="file" class="form-control" required accept="image/*,video/*">
                                    <small class="text-muted">Allowed: JPG, PNG, GIF, WebP, MP4, WebM</small>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Media Type</label>
                                    <select name="media_type" class="form-select">
                                        <option value="image">Image</option>
                                        <option value="infographic">Infographic</option>
                                        <option value="video_thumbnail">Video Thumbnail</option>
                                        <option value="student_work">Student Work</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Link to Lesson</label>
                                    <select name="lesson_id" class="form-select">
                                        <option value="">None</option>
                                        <?php foreach($lessons as $lesson): ?>
                                            <option value="<?php echo $lesson['id']; ?>">
                                                <?php echo htmlspecialchars($lesson['title']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label fw-bold">Caption</label>
                                    <input type="text" name="caption" class="form-control" placeholder="Brief description of the media">
                                </div>
                                
                                <div class="col-12">
                                    <button type="submit" class="btn btn-warning btn-lg w-100 rounded-pill">
                                        <i class="fas fa-upload"></i> Upload Media
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>