<?php
// admin/dashboard.php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Check if logged in and is admin
if (!isLoggedIn() || !hasRole('admin')) {
    header('Location: login.php');
    exit();
}

// Get statistics
$total_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_lessons = $pdo->query("SELECT COUNT(*) FROM lessons")->fetchColumn();
$total_books = $pdo->query("SELECT COUNT(*) FROM books")->fetchColumn();
$total_categories = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$total_contacts = $pdo->query("SELECT COUNT(*) FROM contacts WHERE is_read = 0")->fetchColumn();

// Get recent activity
$recent_lessons = $pdo->query("SELECT * FROM lessons ORDER BY created_at DESC LIMIT 5")->fetchAll();
$recent_users = $pdo->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 5")->fetchAll();

// Get category stats
$category_stats = $pdo->query("
    SELECT c.name, COUNT(l.id) as lesson_count 
    FROM categories c 
    LEFT JOIN lessons l ON c.id = l.category_id 
    GROUP BY c.id
")->fetchAll();
// Get pending users count
$stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE is_approved = 0 AND role != 'admin'");
$pending_users = $stmt->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Roshan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/admin-enhanced.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar" style="min-height: 100vh;">
                <div class="position-sticky pt-3">
                    <h5 class="text-warning px-3 py-2">
                        <i class="fas fa-lightbulb"></i> Roshan
                    </h5>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active text-white" href="dashboard.php">
                                <i class="fas fa-home"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="categories/index.php">
                                <i class="fas fa-layer-group"></i> Categories
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="lessons/index.php">
                                <i class="fas fa-book"></i> Lessons
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="books/index.php">
                                <i class="fas fa-book-open"></i> Books
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="users/index.php">
                                <i class="fas fa-users"></i> Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="media/index.php">
                                <i class="fas fa-images"></i> Media
                            </a>
                        </li>
                        <li class="nav-item mt-3">
                            <a class="nav-link text-danger" href="logout.php">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Dashboard</h1>
                    <div>
                        <span class="text-muted">Welcome, <?php echo $_SESSION['user_full_name']; ?></span>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h5 class="card-title">Total Users</h5>
                                <h2 class="display-6"><?php echo $total_users; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h5 class="card-title">Total Lessons</h5>
                                <h2 class="display-6"><?php echo $total_lessons; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-dark">
                            <div class="card-body">
                                <h5 class="card-title">Total Books</h5>
                                <h2 class="display-6"><?php echo $total_books; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-danger text-white">
                            <div class="card-body">
                                <h5 class="card-title">Unread Messages</h5>
                                <h2 class="display-6"><?php echo $total_contacts; ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h5 class="card-title">Pending Users</h5>
                                <h2 class="display-6"><?php echo $pending_users; ?></h2>
                                <a href="users/index.php" class="text-white text-decoration-none">
                                <small>View all <i class="fas fa-arrow-right"></i></small>
                                </a>
                            </div>
                        </div>
                    </div>

                <div class="row">
                    <!-- Category Stats -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fas fa-layer-group"></i> Lessons by Category</h5>
                            </div>
                            <div class="card-body">
                                <?php foreach($category_stats as $stat): ?>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span><?php echo htmlspecialchars($stat['name']); ?></span>
                                        <span class="badge bg-primary"><?php echo $stat['lesson_count']; ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fas fa-clock"></i> Recent Lessons</h5>
                            </div>
                            <div class="card-body">
                                <?php foreach($recent_lessons as $lesson): ?>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span><?php echo htmlspecialchars($lesson['title']); ?></span>
                                        <small class="text-muted">
                                            <?php echo timeAgo($lesson['created_at']); ?>
                                        </small>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fas fa-bolt"></i> Quick Actions</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-md-3">
                                        <a href="lessons/add.php" class="btn btn-primary w-100">
                                            <i class="fas fa-plus"></i> Add Lesson
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="books/add.php" class="btn btn-success w-100">
                                            <i class="fas fa-plus"></i> Add Book
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="categories/add.php" class="btn btn-warning w-100">
                                            <i class="fas fa-plus"></i> Add Category
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="users/index.php" class="btn btn-info text-white w-100">
                                            <i class="fas fa-users"></i> Manage Users
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/admin.js"></script>
    <script src="../assets/js/ripple.js"></script>
    <script src="../assets/js/admin-charts.js"></script>
</body>
</html>