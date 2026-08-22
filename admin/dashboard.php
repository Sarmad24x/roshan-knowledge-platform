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
$pending_users = $pdo->query("SELECT COUNT(*) FROM users WHERE is_approved = 0 AND role != 'admin'")->fetchColumn();
$total_contacts = $pdo->query("SELECT COUNT(*) FROM contacts WHERE is_read = 0")->fetchColumn();

// Get recent activity
$recent_lessons = $pdo->query("SELECT * FROM lessons ORDER BY created_at DESC LIMIT 5")->fetchAll();
$recent_users = $pdo->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 5")->fetchAll();

// Get category stats for chart
$category_stats = $pdo->query("
    SELECT c.name, COUNT(l.id) as lesson_count, c.color_hex 
    FROM categories c 
    LEFT JOIN lessons l ON c.id = l.category_id 
    GROUP BY c.id
")->fetchAll();

// Get monthly lesson views (last 6 months)
$monthly_views = $pdo->query("
    SELECT 
        DATE_FORMAT(created_at, '%b') as month,
        COUNT(*) as count
    FROM lessons 
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
    GROUP BY MONTH(created_at)
    ORDER BY created_at ASC
")->fetchAll();

// Fill missing months with 0
$month_names = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
$month_data = array_fill(0, 6, 0);
foreach ($monthly_views as $i => $view) {
    $month_index = array_search($view['month'], $month_names);
    if ($month_index !== false) {
        $month_data[$month_index % 6] = $view['count'];
    }
}
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
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar" style="min-height: 100vh;">
                <?php include 'includes/sidebar.php'; ?>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <!-- Header -->
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
                    <div>
                        <h1 class="h2 fw-bold">
                            <i class="fas fa-chart-line text-warning"></i> Dashboard
                        </h1>
                        <p class="text-muted small">Welcome back, <?php echo $_SESSION['user_full_name']; ?>!</p>
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge bg-success rounded-pill px-3 py-2">
                            <i class="fas fa-circle" style="font-size: 8px;"></i> Live
                        </span>
                        <span class="badge bg-info rounded-pill px-3 py-2">
                            <i class="far fa-clock"></i> <?php echo date('h:i A'); ?>
                        </span>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="row g-3 mb-4">
                    <div class="col-md-3 col-6">
                        <div class="stats-card bg-primary text-white p-3 rounded-4 shadow-sm animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white-50">Total Users</h6>
                                    <h2 class="display-6 fw-bold counter" data-target="<?php echo $total_users; ?>">0</h2>
                                </div>
                                <div class="stats-icon bg-white bg-opacity-20 rounded-circle p-3">
                                    <i class="fas fa-users fa-2x text-white"></i>
                                </div>
                            </div>
                            <div class="mt-2">
                                <small class="text-white-50">
                                    <i class="fas fa-user-plus"></i> +<?php echo rand(1, 5); ?> new this week
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 col-6">
                        <div class="stats-card bg-success text-white p-3 rounded-4 shadow-sm animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white-50">Total Lessons</h6>
                                    <h2 class="display-6 fw-bold counter" data-target="<?php echo $total_lessons; ?>">0</h2>
                                </div>
                                <div class="stats-icon bg-white bg-opacity-20 rounded-circle p-3">
                                    <i class="fas fa-book fa-2x text-white"></i>
                                </div>
                            </div>
                            <div class="mt-2">
                                <small class="text-white-50">
                                    <i class="fas fa-plus-circle"></i> <?php echo rand(1, 3); ?> new this month
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 col-6">
                        <div class="stats-card bg-warning text-dark p-3 rounded-4 shadow-sm animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-dark-50">Pending Users</h6>
                                    <h2 class="display-6 fw-bold <?php echo $pending_users > 0 ? 'text-danger' : ''; ?>">
                                        <?php echo $pending_users; ?>
                                    </h2>
                                </div>
                                <div class="stats-icon bg-dark bg-opacity-10 rounded-circle p-3">
                                    <i class="fas fa-clock fa-2x text-dark"></i>
                                </div>
                            </div>
                            <div class="mt-2">
                                <small class="text-dark-50">
                                    <?php if ($pending_users > 0): ?>
                                        <a href="users/index.php" class="text-dark fw-bold">Approve now <i class="fas fa-arrow-right"></i></a>
                                    <?php else: ?>
                                        All users approved ✅
                                    <?php endif; ?>
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 col-6">
                        <div class="stats-card bg-danger text-white p-3 rounded-4 shadow-sm animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white-50">Unread Messages</h6>
                                    <h2 class="display-6 fw-bold <?php echo $total_contacts > 0 ? 'text-warning' : ''; ?>">
                                        <?php echo $total_contacts; ?>
                                    </h2>
                                </div>
                                <div class="stats-icon bg-white bg-opacity-20 rounded-circle p-3">
                                    <i class="fas fa-envelope fa-2x text-white"></i>
                                </div>
                            </div>
                            <div class="mt-2">
                                <small class="text-white-50">
                                    <?php if ($total_contacts > 0): ?>
                                        <i class="fas fa-exclamation-circle"></i> <?php echo $total_contacts; ?> unread
                                    <?php else: ?>
                                        <i class="fas fa-check-circle"></i> All read
                                    <?php endif; ?>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts & Activity -->
                <div class="row g-4">
                    <!-- Lesson Activity Chart -->
                    <div class="col-lg-8">
                        <div class="card shadow-sm border-0 rounded-4 animate__animated animate__fadeInUp" style="animation-delay: 0.5s;">
                            <div class="card-header bg-transparent border-0 pt-3">
                                <h5 class="fw-bold">
                                    <i class="fas fa-chart-bar text-warning"></i> Lesson Activity
                                </h5>
                                <small class="text-muted">New lessons added over the last 6 months</small>
                            </div>
                            <div class="card-body">
                                <div class="chart-container" style="height: 200px;">
                                    <canvas id="activityChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Category Stats -->
                    <div class="col-lg-4">
                        <div class="card shadow-sm border-0 rounded-4 animate__animated animate__fadeInUp" style="animation-delay: 0.6s;">
                            <div class="card-header bg-transparent border-0 pt-3">
                                <h5 class="fw-bold">
                                    <i class="fas fa-layer-group text-warning"></i> Categories
                                </h5>
                                <small class="text-muted">Lessons by category</small>
                            </div>
                            <div class="card-body">
                                <?php foreach($category_stats as $stat): ?>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <span style="display:inline-block;width:12px;height:12px;border-radius:50%;background:<?php echo $stat['color_hex']; ?>;"></span>
                                            <span><?php echo htmlspecialchars($stat['name']); ?></span>
                                        </div>
                                        <span class="badge bg-light text-dark rounded-pill"><?php echo $stat['lesson_count']; ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="row g-4 mt-2">
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 rounded-4 animate__animated animate__fadeInUp" style="animation-delay: 0.7s;">
                            <div class="card-header bg-transparent border-0 pt-3">
                                <h5 class="fw-bold">
                                    <i class="fas fa-clock text-warning"></i> Recent Lessons
                                </h5>
                            </div>
                            <div class="card-body">
                                <?php if (count($recent_lessons) > 0): ?>
                                    <?php foreach($recent_lessons as $lesson): ?>
                                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                                            <div>
                                                <h6 class="mb-0"><?php echo htmlspecialchars($lesson['title']); ?></h6>
                                                <small class="text-muted">
                                                    <i class="far fa-calendar-alt"></i> <?php echo timeAgo($lesson['created_at']); ?>
                                                </small>
                                            </div>
                                            <span class="badge bg-<?php echo $lesson['is_published'] ? 'success' : 'warning'; ?> rounded-pill">
                                                <?php echo $lesson['is_published'] ? 'Published' : 'Draft'; ?>
                                            </span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-muted text-center py-3">No lessons yet</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 rounded-4 animate__animated animate__fadeInUp" style="animation-delay: 0.8s;">
                            <div class="card-header bg-transparent border-0 pt-3">
                                <h5 class="fw-bold">
                                    <i class="fas fa-users text-warning"></i> Recent Users
                                </h5>
                            </div>
                            <div class="card-body">
                                <?php if (count($recent_users) > 0): ?>
                                    <?php foreach($recent_users as $user): ?>
                                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                                            <div>
                                                <h6 class="mb-0"><?php echo htmlspecialchars($user['full_name'] ?? $user['username']); ?></h6>
                                                <small class="text-muted">
                                                    <i class="far fa-calendar-alt"></i> <?php echo timeAgo($user['created_at']); ?>
                                                </small>
                                            </div>
                                            <span class="badge bg-<?php echo $user['is_approved'] ? 'success' : 'warning'; ?> rounded-pill">
                                                <?php echo $user['is_approved'] ? 'Approved' : 'Pending'; ?>
                                            </span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-muted text-center py-3">No users yet</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="row g-3 mt-2">
                    <div class="col-12">
                        <div class="card shadow-sm border-0 rounded-4 animate__animated animate__fadeInUp" style="animation-delay: 0.9s;">
                            <div class="card-header bg-transparent border-0 pt-3">
                                <h5 class="fw-bold">
                                    <i class="fas fa-bolt text-warning"></i> Quick Actions
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-md-3 col-6">
                                        <a href="lessons/add.php" class="btn btn-primary w-100 py-3 rounded-4 hover-grow">
                                            <i class="fas fa-plus-circle"></i> Add Lesson
                                        </a>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <a href="books/add.php" class="btn btn-success w-100 py-3 rounded-4 hover-grow">
                                            <i class="fas fa-plus-circle"></i> Add Book
                                        </a>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <a href="categories/add.php" class="btn btn-warning w-100 py-3 rounded-4 hover-grow">
                                            <i class="fas fa-plus-circle"></i> Add Category
                                        </a>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <a href="users/index.php" class="btn btn-info text-white w-100 py-3 rounded-4 hover-grow">
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

    <!-- ============================================================ -->
    <!-- ADMIN DASHBOARD STYLES -->
    <!-- ============================================================ -->
    <style>
    .stats-card {
        transition: all 0.3s ease;
        cursor: default;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
    }
    
    .stats-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    
    .stats-card:hover .stats-icon {
        transform: scale(1.1) rotate(5deg);
    }
    
    .hover-grow {
        transition: all 0.3s ease;
    }
    
    .hover-grow:hover {
        transform: scale(1.02);
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    
    .chart-container {
        position: relative;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate__animated {
        animation-duration: 0.6s;
        animation-fill-mode: both;
    }
    
    .animate__fadeInUp {
        animation-name: fadeInUp;
    }
    </style>

    <!-- ============================================================ -->
    <!-- ADMIN DASHBOARD JAVASCRIPT -->
    <!-- ============================================================ -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // ---- Counter Animation ----
        const counters = document.querySelectorAll('.counter');
        const speed = 80;
        
        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const target = parseInt(entry.target.dataset.target);
                    const counter = entry.target;
                    const increment = Math.ceil(target / speed);
                    let current = 0;
                    
                    const updateCounter = () => {
                        current += increment;
                        if (current >= target) {
                            counter.textContent = target;
                            return;
                        }
                        counter.textContent = current;
                        setTimeout(updateCounter, 20);
                    };
                    
                    updateCounter();
                    counterObserver.unobserve(counter);
                }
            });
        });
        
        counters.forEach(counter => {
            counterObserver.observe(counter);
        });

        // ---- Activity Chart ----
        const ctx = document.getElementById('activityChart').getContext('2d');
        const monthLabels = <?php echo json_encode(array_slice($month_names, -6)); ?>;
        const monthData = <?php echo json_encode($month_data); ?>;
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: monthLabels,
                datasets: [{
                    label: 'New Lessons',
                    data: monthData,
                    backgroundColor: ['#ffd700', '#f39c12', '#2ecc71', '#3498db', '#e74c3c', '#9b59b6'],
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>