<?php
$page_title = 'Login';
$current_page = 'login';
require_once '../config/database.php';
require_once '../includes/functions.php';

// If already logged in, redirect to home
if (isLoggedIn()) {
    header('Location: ' . SITE_URL . 'index.php');
    exit();
}

$error = '';

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        // Check if user exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password_hash'])) {
            // Check if user is approved
            if (!$user['is_approved']) {
                $error = 'Your account is pending approval. Please wait for admin verification.';
            } else {
                // Login successful
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_full_name'] = $user['full_name'];
                
                // Redirect based on role
                if ($user['role'] === 'admin' || $user['role'] === 'teacher') {
                    header('Location: ' . SITE_URL . 'admin/dashboard.php');
                } else {
                    header('Location: ' . SITE_URL . 'index.php');
                }
                exit();
            }
        } else {
            $error = 'Invalid username or password.';
        }
    }
}

require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>

<!-- Page Header -->
<section class="py-5" style="background: var(--primary-gradient);">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center text-white">
                <h1 class="display-4 fw-bold">
                    <i class="fas fa-sign-in-alt text-warning"></i> Login
                </h1>
                <p class="lead">Welcome back to Roshan</p>
            </div>
        </div>
    </div>
</section>

<!-- Login Form -->
<section class="py-5 page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h4 class="card-title fw-bold">Login</h4>
                        <p class="text-muted small">Enter your credentials to continue</p>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <form method="POST" action="" class="needs-validation auth-form" novalidate>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="username" class="form-label">Username or Email <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="username" name="username" required>
                                    <div class="invalid-feedback">Please enter your username or email.</div>
                                </div>
                                
                                <div class="col-12">
                                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password" name="password" required>
                                        <button class="btn btn-outline-secondary" type="button" data-password-toggle="password" aria-label="Show password" aria-pressed="false"><i class="fas fa-eye"></i></button>
                                    </div>
                                    <div class="invalid-feedback">Please enter your password.</div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                            <label class="form-check-label" for="remember">Remember me</label>
                                        </div>
                                        <a href="<?php echo SITE_URL; ?>forgot-password.php" class="small">Forgot password?</a>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <button type="submit" class="btn btn-warning btn-lg w-100">
                                        <i class="fas fa-sign-in-alt"></i> Login
                                    </button>
                                </div>
                                
                                <div class="col-12 text-center">
                                    <p class="small text-muted">
                                        Don't have an account? <a href="<?php echo SITE_URL; ?>register.php">Register here</a>
                                    </p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Demo Credentials -->
                <div class="card mt-3 bg-light border-0">
                    <div class="card-body p-3">
                        <h6 class="fw-bold"><i class="fas fa-info-circle text-warning"></i> Demo Credentials</h6>
                        <div class="row small">
                            <div class="col-md-6">
                                <strong>Admin:</strong><br>
                                Username: admin<br>
                                Password: admin123
                            </div>
                            <div class="col-md-6">
                                <strong>Student:</strong><br>
                                Username: student<br>
                                Password: student123
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>