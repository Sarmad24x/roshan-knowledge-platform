<?php
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

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: index.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $slug = createSlug($name);
    $description = sanitize($_POST['description'] ?? '');
    $price = (float)$_POST['price'] ?? 0;
    $sale_price = isset($_POST['sale_price']) && $_POST['sale_price'] != '' ? (float)$_POST['sale_price'] : null;
    $category = sanitize($_POST['category'] ?? 'guide');
    $product_type = sanitize($_POST['product_type'] ?? 'digital');
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $stock_quantity = (int)($_POST['stock_quantity'] ?? 0);
    $file_path = sanitize($_POST['file_path'] ?? '');
    
    $image_path = $product['image_path'];
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        $filename = $_FILES['image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $new_filename = time() . '_' . uniqid() . '.' . $ext;
            $upload_path = '../../assets/images/uploads/products/';
            
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path . $new_filename)) {
                if ($product['image_path'] && file_exists('../../' . $product['image_path'])) {
                    unlink('../../' . $product['image_path']);
                }
                $image_path = 'assets/images/uploads/products/' . $new_filename;
            } else {
                $error = 'Failed to upload image.';
            }
        } else {
            $error = 'Invalid file type.';
        }
    }
    
    if (empty($name) || empty($description) || $price <= 0) {
        $error = 'Name, description, and price are required.';
    } elseif (!$error) {
        $stmt = $pdo->prepare("UPDATE products SET 
            name = ?, slug = ?, description = ?, price = ?, sale_price = ?, 
            category = ?, product_type = ?, image_path = ?, file_path = ?, 
            is_featured = ?, is_active = ?, stock_quantity = ? 
            WHERE id = ?");
        
        if ($stmt->execute([$name, $slug, $description, $price, $sale_price, 
            $category, $product_type, $image_path, $file_path, 
            $is_featured, $is_active, $stock_quantity, $id])) {
            header('Location: index.php?msg=updated');
            exit();
        } else {
            $error = 'Failed to update product.';
        }
    }
}
$page_title = 'Edit Product';
include '../includes/admin-header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Admin</title>
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
                        <i class="fas fa-edit text-warning"></i> Edit Product
                    </h1>
                    <a href="index.php" class="btn btn-secondary rounded-pill px-4">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger rounded-4"><?php echo $error; ?></div>
                <?php endif; ?>

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body">
                        <form method="POST" action="" enctype="multipart/form-data">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Product Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Category <span class="text-danger">*</span></label>
                                    <select name="category" class="form-select" required>
                                        <option value="bundle" <?php echo $product['category'] == 'bundle' ? 'selected' : ''; ?>>📦 Lesson Bundle</option>
                                        <option value="guide" <?php echo $product['category'] == 'guide' ? 'selected' : ''; ?>>📖 Study Guide</option>
                                    </select>
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label">Description <span class="text-danger">*</span></label>
                                    <textarea name="description" class="form-control" rows="4" required><?php echo htmlspecialchars($product['description']); ?></textarea>
                                </div>
                                
                                <div class="col-md-4">
                                    <label class="form-label">Price (PKR) <span class="text-danger">*</span></label>
                                    <input type="number" name="price" class="form-control" value="<?php echo $product['price']; ?>" required min="0" step="100">
                                </div>
                                
                                <div class="col-md-4">
                                    <label class="form-label">Sale Price (PKR)</label>
                                    <input type="number" name="sale_price" class="form-control" value="<?php echo $product['sale_price']; ?>" min="0" step="100">
                                </div>
                                
                                <div class="col-md-4">
                                    <label class="form-label">Product Type</label>
                                    <select name="product_type" class="form-select">
                                        <option value="digital" <?php echo $product['product_type'] == 'digital' ? 'selected' : ''; ?>>💻 Digital</option>
                                        <option value="physical" <?php echo $product['product_type'] == 'physical' ? 'selected' : ''; ?>>📦 Physical</option>
                                    </select>
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label">Current Image</label>
                                    <?php if ($product['image_path']): ?>
                                        <div>
                                            <img src="<?php echo '../../' . $product['image_path']; ?>" style="max-width:200px;max-height:150px;border-radius:8px;border:1px solid #ddd;">
                                            <p class="small text-muted mt-1"><?php echo $product['image_path']; ?></p>
                                        </div>
                                    <?php else: ?>
                                        <p class="text-muted">No image uploaded.</p>
                                    <?php endif; ?>
                                    <input type="file" name="image" class="form-control mt-2" accept="image/*">
                                    <small class="text-muted">Upload new image to replace current one.</small>
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">File Path</label>
                                    <input type="text" name="file_path" class="form-control" value="<?php echo htmlspecialchars($product['file_path']); ?>" placeholder="assets/downloads/product.pdf">
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Stock Quantity</label>
                                    <input type="number" name="stock_quantity" class="form-control" value="<?php echo $product['stock_quantity']; ?>" min="0">
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-check mt-3">
                                        <input type="checkbox" name="is_featured" class="form-check-input" <?php echo $product['is_featured'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label">⭐ Featured Product</label>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-check mt-3">
                                        <input type="checkbox" name="is_active" class="form-check-input" <?php echo $product['is_active'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label">✅ Active</label>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5">
                                        <i class="fas fa-save"></i> Update Product
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