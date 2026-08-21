<?php
// admin/lessons/add.php
session_start();
require_once '../../config/database.php';
require_once '../../includes/functions.php';

if (!isLoggedIn() || !hasRole('admin')) {
    header('Location: ../login.php');
    exit();
}

$error = '';
$success = '';

// Get categories for dropdown
$categories = $pdo->query("SELECT * FROM categories WHERE is_active = 1 ORDER BY name")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_id = (int)$_POST['category_id'] ?? 0;
    $title = sanitize($_POST['title'] ?? '');
    $slug = createSlug($title);
    $subtitle = sanitize($_POST['subtitle'] ?? '');
    $summary = sanitize($_POST['summary'] ?? '');
    $content = $_POST['content'] ?? '';
    $difficulty = sanitize($_POST['difficulty'] ?? 'beginner');
    $reading_time = (int)($_POST['reading_time'] ?? 5);
    $video_url = sanitize($_POST['video_url'] ?? '');
    $curiosity_question = sanitize($_POST['curiosity_question'] ?? '');
    $is_published = isset($_POST['is_published']) ? 1 : 0;
    
    // Handle image upload
    $image_path = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $filename = $_FILES['image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $new_filename = time() . '_' . uniqid() . '.' . $ext;
            $upload_path = '../../assets/images/uploads/lessons/';
            
            // Create directory if it doesn't exist
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path . $new_filename)) {
                $image_path = 'assets/images/uploads/lessons/' . $new_filename;
            } else {
                $error = 'Failed to upload image.';
            }
        } else {
            $error = 'Invalid file type. Allowed: ' . implode(', ', $allowed);
        }
    }
    
    if (empty($title) || empty($content) || $category_id == 0) {
        $error = 'Title, content, and category are required.';
    } elseif (!$error) {
        $stmt = $pdo->prepare("INSERT INTO lessons (category_id, user_id, title, slug, subtitle, summary, content, image_path, video_url, curiosity_question, difficulty, reading_time, is_published) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        if ($stmt->execute([$category_id, $_SESSION['user_id'], $title, $slug, $subtitle, $summary, $content, $image_path, $video_url, $curiosity_question, $difficulty, $reading_time, $is_published])) {
            header('Location: index.php?msg=added');
            exit();
        } else {
            $error = 'Failed to add lesson.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Lesson - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../../assets/css/admin-enhanced.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar">
                <?php include '../includes/sidebar.php'; ?>
            </nav>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Add Lesson</h1>
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="" enctype="multipart/form-data" class="auth-form">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control" required>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Category <span class="text-danger">*</span></label>
                                    <select name="category_id" class="form-select" required>
                                        <option value="">Select Category</option>
                                        <?php foreach($categories as $cat): ?>
                                            <option value="<?php echo $cat['id']; ?>">
                                                <?php echo htmlspecialchars($cat['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label">Subtitle</label>
                                    <input type="text" name="subtitle" class="form-control">
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label">Summary</label>
                                    <textarea name="summary" class="form-control" rows="2"></textarea>
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label">Content <span class="text-danger">*</span></label>
                                    <textarea name="content" class="form-control" rows="10" required></textarea>
                                    <small class="text-muted">You can use HTML tags for formatting.</small>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Difficulty</label>
                                    <select name="difficulty" class="form-select">
                                        <option value="beginner">Beginner</option>
                                        <option value="intermediate">Intermediate</option>
                                        <option value="advanced">Advanced</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Reading Time (minutes)</label>
                                    <input type="number" name="reading_time" class="form-control" value="5" min="1">
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label">Feature Image</label>
                                    <input type="file" name="image" class="form-control" accept="image/*">
                                    <small class="text-muted">Allowed: JPG, PNG, GIF, WebP (Max 5MB)</small>
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label">Video URL (YouTube)</label>
                                    <input type="url" name="video_url" class="form-control" placeholder="https://youtu.be/... or https://www.youtube.com/watch?v=...">
                                    <small class="text-muted">Paste the YouTube video URL here.</small>
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label">Curiosity Question</label>
                                    <input type="text" name="curiosity_question" class="form-control" placeholder="e.g., Did you know that...?">
                                    <small class="text-muted">A question that sparks curiosity about the video.</small>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-check mt-2">
                                        <input type="checkbox" name="is_published" class="form-check-input" checked>
                                        <label class="form-check-label">Publish immediately</label>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Save Lesson
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <div class="toast-container" aria-live="polite" aria-atomic="true"></div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/auth.js"></script>
    <script src="../../assets/js/ripple.js"></script>
</body>
</html>