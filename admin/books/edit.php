<?php
// admin/books/edit.php
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

// Get book
$stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
$stmt->execute([$id]);
$book = $stmt->fetch();

if (!$book) {
    header('Location: index.php');
    exit();
}

// Get categories
$categories = $pdo->query("SELECT * FROM categories WHERE is_active = 1 ORDER BY name")->fetchAll();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_id = (int)$_POST['category_id'] ?? 0;
    $title = sanitize($_POST['title'] ?? '');
    $author = sanitize($_POST['author'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $difficulty = sanitize($_POST['difficulty'] ?? 'beginner');
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $pdf_link = sanitize($_POST['pdf_link'] ?? '');
    $purchase_link = sanitize($_POST['purchase_link'] ?? '');
    
    $cover_image = $book['cover_image'];
    
    // Handle image upload
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $filename = $_FILES['cover_image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $new_filename = time() . '_' . uniqid() . '.' . $ext;
            $upload_path = '../../assets/images/uploads/books/';
            
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }
            
            if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $upload_path . $new_filename)) {
                // Delete old image
                if ($book['cover_image'] && file_exists('../../' . $book['cover_image'])) {
                    unlink('../../' . $book['cover_image']);
                }
                $cover_image = 'assets/images/uploads/books/' . $new_filename;
            } else {
                $error = 'Failed to upload image.';
            }
        } else {
            $error = 'Invalid file type. Allowed: ' . implode(', ', $allowed);
        }
    }
    
    if (empty($title) || empty($author) || $category_id == 0) {
        $error = 'Title, author, and category are required.';
    } elseif (!$error) {
        $stmt = $pdo->prepare("UPDATE books SET category_id = ?, title = ?, author = ?, description = ?, cover_image = ?, pdf_link = ?, purchase_link = ?, difficulty = ?, is_featured = ? WHERE id = ?");
        
        if ($stmt->execute([$category_id, $title, $author, $description, $cover_image, $pdf_link, $purchase_link, $difficulty, $is_featured, $id])) {
            header('Location: index.php?msg=updated');
            exit();
        } else {
            $error = 'Failed to update book.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book - Admin</title>
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
                    <h1 class="h2">Edit Book</h1>
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
                                    <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($book['title']); ?>" required>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Author <span class="text-danger">*</span></label>
                                    <input type="text" name="author" class="form-control" value="<?php echo htmlspecialchars($book['author']); ?>" required>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Category <span class="text-danger">*</span></label>
                                    <select name="category_id" class="form-select" required>
                                        <option value="">Select Category</option>
                                        <?php foreach($categories as $cat): ?>
                                            <option value="<?php echo $cat['id']; ?>" <?php echo $cat['id'] == $book['category_id'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($cat['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Difficulty</label>
                                    <select name="difficulty" class="form-select">
                                        <option value="beginner" <?php echo $book['difficulty'] == 'beginner' ? 'selected' : ''; ?>>Beginner</option>
                                        <option value="intermediate" <?php echo $book['difficulty'] == 'intermediate' ? 'selected' : ''; ?>>Intermediate</option>
                                        <option value="advanced" <?php echo $book['difficulty'] == 'advanced' ? 'selected' : ''; ?>>Advanced</option>
                                    </select>
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($book['description']); ?></textarea>
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label">Current Cover Image</label>
                                    <?php if ($book['cover_image']): ?>
                                        <div>
                                            <img src="<?php echo '../../' . $book['cover_image']; ?>" 
                                                 style="max-width:200px;max-height:200px;border-radius:8px;border:1px solid #ddd;">
                                            <p class="small text-muted mt-1"><?php echo $book['cover_image']; ?></p>
                                        </div>
                                    <?php else: ?>
                                        <p class="text-muted">No cover image uploaded.</p>
                                    <?php endif; ?>
                                    <input type="file" name="cover_image" class="form-control mt-2" accept="image/*">
                                    <small class="text-muted">Upload new image to replace current one. Allowed: JPG, PNG, GIF, WebP</small>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">PDF Link</label>
                                    <input type="url" name="pdf_link" class="form-control" value="<?php echo htmlspecialchars($book['pdf_link']); ?>" placeholder="https://...">
                                    <small class="text-muted">Link to read online</small>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Purchase Link</label>
                                    <input type="url" name="purchase_link" class="form-control" value="<?php echo htmlspecialchars($book['purchase_link']); ?>" placeholder="https://...">
                                    <small class="text-muted">Link to buy the book</small>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-check mt-2">
                                        <input type="checkbox" name="is_featured" class="form-check-input" <?php echo $book['is_featured'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label">Feature this book</label>
                                        <small class="d-block text-muted">Featured books appear prominently on the books page</small>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Book
                                    </button>
                                    <a href="index.php" class="btn btn-secondary">Cancel</a>
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