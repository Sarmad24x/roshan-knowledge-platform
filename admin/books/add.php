<?php
// admin/books/add.php
session_start();
require_once '../../config/database.php';
require_once '../../includes/functions.php';

if (!isLoggedIn() || !hasRole('admin')) {
    header('Location: ../login.php');
    exit();
}

$error = '';

// Get categories
$categories = $pdo->query("SELECT * FROM categories WHERE is_active = 1 ORDER BY name")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_id = (int)$_POST['category_id'] ?? 0;
    $title = sanitize($_POST['title'] ?? '');
    $author = sanitize($_POST['author'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $difficulty = sanitize($_POST['difficulty'] ?? 'beginner');
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $pdf_link = sanitize($_POST['pdf_link'] ?? '');
    $purchase_link = sanitize($_POST['purchase_link'] ?? '');
    
    // Handle image upload
    $cover_image = '';
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
                $cover_image = 'assets/images/uploads/books/' . $new_filename;
            } else {
                $error = 'Failed to upload image.';
            }
        } else {
            $error = 'Invalid file type.';
        }
    }
    
    if (empty($title) || empty($author) || $category_id == 0) {
        $error = 'Title, author, and category are required.';
    } elseif (!$error) {
        $stmt = $pdo->prepare("INSERT INTO books (category_id, title, author, description, cover_image, pdf_link, purchase_link, difficulty, is_featured) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        if ($stmt->execute([$category_id, $title, $author, $description, $cover_image, $pdf_link, $purchase_link, $difficulty, $is_featured])) {
            header('Location: index.php?msg=added');
            exit();
        } else {
            $error = 'Failed to add book.';
        }
    }
}
$page_title = 'Add Book';
include '../includes/admin-header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book - Admin</title>
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
                    <h1 class="h2">Add Book</h1>
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
                                    <label class="form-label">Author <span class="text-danger">*</span></label>
                                    <input type="text" name="author" class="form-control" required>
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
                                
                                <div class="col-md-6">
                                    <label class="form-label">Difficulty</label>
                                    <select name="difficulty" class="form-select">
                                        <option value="beginner">Beginner</option>
                                        <option value="intermediate">Intermediate</option>
                                        <option value="advanced">Advanced</option>
                                    </select>
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control" rows="3"></textarea>
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label">Cover Image</label>
                                    <input type="file" name="cover_image" class="form-control" accept="image/*">
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">PDF Link</label>
                                    <input type="url" name="pdf_link" class="form-control" placeholder="https://...">
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Purchase Link</label>
                                    <input type="url" name="purchase_link" class="form-control" placeholder="https://...">
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-check mt-2">
                                        <input type="checkbox" name="is_featured" class="form-check-input">
                                        <label class="form-check-label">Feature this book</label>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Save Book
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