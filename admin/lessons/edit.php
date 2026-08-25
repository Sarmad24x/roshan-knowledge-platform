<?php
// admin/lessons/edit.php
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

// Get lesson
$stmt = $pdo->prepare("SELECT * FROM lessons WHERE id = ?");
$stmt->execute([$id]);
$lesson = $stmt->fetch();

if (!$lesson) {
    header('Location: index.php');
    exit();
}

// Get categories
$categories = $pdo->query("SELECT * FROM categories WHERE is_active = 1 ORDER BY name")->fetchAll();

$error = '';

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
    
    $image_path = $lesson['image_path'];
    
    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $filename = $_FILES['image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $new_filename = time() . '_' . uniqid() . '.' . $ext;
            $upload_path = '../../assets/images/uploads/lessons/';
            
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path . $new_filename)) {
                // Delete old image
                if ($lesson['image_path'] && file_exists('../../' . $lesson['image_path'])) {
                    unlink('../../' . $lesson['image_path']);
                }
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
        $stmt = $pdo->prepare("UPDATE lessons SET category_id = ?, title = ?, slug = ?, subtitle = ?, summary = ?, content = ?, image_path = ?, video_url = ?, curiosity_question = ?, difficulty = ?, reading_time = ?, is_published = ? WHERE id = ?");
        
        if ($stmt->execute([$category_id, $title, $slug, $subtitle, $summary, $content, $image_path, $video_url, $curiosity_question, $difficulty, $reading_time, $is_published, $id])) {
            header('Location: index.php?msg=updated');
            exit();
        } else {
            $error = 'Failed to update lesson.';
        }
    }
}
$page_title = 'Edit Lesson';
include '../includes/admin-header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Lesson - Admin</title>
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
                    <h1 class="h2">Edit Lesson</h1>
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="" enctype="multipart/form-data">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($lesson['title']); ?>" required>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Category <span class="text-danger">*</span></label>
                                    <select name="category_id" class="form-select" required>
                                        <option value="">Select Category</option>
                                        <?php foreach($categories as $cat): ?>
                                            <option value="<?php echo $cat['id']; ?>" <?php echo $cat['id'] == $lesson['category_id'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($cat['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label">Subtitle</label>
                                    <input type="text" name="subtitle" class="form-control" value="<?php echo htmlspecialchars($lesson['subtitle']); ?>">
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label">Summary</label>
                                    <textarea name="summary" class="form-control" rows="2"><?php echo htmlspecialchars($lesson['summary']); ?></textarea>
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label">Content <span class="text-danger">*</span></label>
                                    <textarea name="content" class="form-control" rows="10" required><?php echo htmlspecialchars($lesson['content']); ?></textarea>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Difficulty</label>
                                    <select name="difficulty" class="form-select">
                                        <option value="beginner" <?php echo $lesson['difficulty'] == 'beginner' ? 'selected' : ''; ?>>Beginner</option>
                                        <option value="intermediate" <?php echo $lesson['difficulty'] == 'intermediate' ? 'selected' : ''; ?>>Intermediate</option>
                                        <option value="advanced" <?php echo $lesson['difficulty'] == 'advanced' ? 'selected' : ''; ?>>Advanced</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Reading Time (minutes)</label>
                                    <input type="number" name="reading_time" class="form-control" value="<?php echo $lesson['reading_time']; ?>" min="1">
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label">Current Image</label>
                                    <?php if ($lesson['image_path']): ?>
                                        <div>
                                            <img src="<?php echo '../../' . $lesson['image_path']; ?>" style="max-width:200px;border-radius:8px;">
                                            <p class="small text-muted mt-1"><?php echo $lesson['image_path']; ?></p>
                                        </div>
                                    <?php else: ?>
                                        <p class="text-muted">No image uploaded.</p>
                                    <?php endif; ?>
                                    <input type="file" name="image" class="form-control mt-2" accept="image/*">
                                    <small class="text-muted">Upload new image to replace current one.</small>
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label">Video URL (YouTube)</label>
                                    <input type="url" name="video_url" class="form-control" value="<?php echo htmlspecialchars($lesson['video_url']); ?>">
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label">Curiosity Question</label>
                                    <input type="text" name="curiosity_question" class="form-control" value="<?php echo htmlspecialchars($lesson['curiosity_question']); ?>">
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-check mt-2">
                                        <input type="checkbox" name="is_published" class="form-check-input" <?php echo $lesson['is_published'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label">Published</label>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Lesson
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