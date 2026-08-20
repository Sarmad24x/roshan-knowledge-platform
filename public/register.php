<?php
$page_title = 'Register';
$current_page = 'register';
require_once '../config/database.php';
require_once '../includes/functions.php';

$error = '';
$success = '';

// Handle registration
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $full_name = sanitize($_POST['full_name'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validation
    if (empty($username) || empty($email) || empty($full_name) || empty($password)) {
        $error = 'Please fill in all required fields.';
    } elseif (strlen($username) < 3) {
        $error = 'Username must be at least 3 characters.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } else {
        // Check if username or email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        
        if ($stmt->rowCount() > 0) {
            $error = 'Username or email already exists.';
        } else {
            // Hash password and insert user
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, full_name, role, is_approved) 
                                   VALUES (?, ?, ?, ?, 'student', 0)");
            
            if ($stmt->execute([$username, $email, $password_hash, $full_name])) {
                $success = 'Registration successful! You can now login.';
                // Optionally auto-login
                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['username'] = $username;
                $_SESSION['user_role'] = 'student';
                header('Location: ' . SITE_URL . 'index.php');
                exit();
            } else {
                $error = 'Registration failed. Please try again.';
            }
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
                    <i class="fas fa-user-plus text-warning"></i> Register
                </h1>
                <p class="lead">Join the Roshan community</p>
            </div>
        </div>
    </div>
</section>

<!-- Registration Form -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h4 class="card-title fw-bold">Create Account</h4>
                        <p class="text-muted small">Start your journey to understanding</p>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?php echo $success; ?></div>
                        <?php endif; ?>
                        
                        <form method="POST" action="" class="needs-validation" novalidate>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="full_name" name="full_name" required>
                                    <div class="invalid-feedback">Please enter your full name.</div>
                                </div>
                                
                                <div class="col-12">
                                    <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="username" name="username" required>
                                    <div class="form-text">At least 3 characters</div>
                                    <div class="invalid-feedback">Please choose a username.</div>
                                </div>
                                
                                <div class="col-12">
                                    <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                    <div class="invalid-feedback">Please enter a valid email.</div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <div class="form-text">At least 6 characters</div>
                                    <div class="invalid-feedback">Please enter a password.</div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="confirm_password" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                    <div class="invalid-feedback">Please confirm your password.</div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="terms" required>
                                        <label class="form-check-label" for="terms">
                                            I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
                                        </label>
                                        <div class="invalid-feedback">You must agree to the terms.</div>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <button type="submit" class="btn btn-warning btn-lg w-100">
                                        <i class="fas fa-user-plus"></i> Create Account
                                    </button>
                                </div>
                                
                                <div class="col-12 text-center">
                                    <p class="small text-muted">
                                        Already have an account? <a href="<?php echo SITE_URL; ?>login.php">Login here</a>
                                    </p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Register -->
<section class="py-4 bg-light">
    <div class="container">
        <div class="row text-center">
            <h4 class="fw-bold">Why Join Roshan?</h4>
            <div class="col-md-4">
                <i class="fas fa-check-circle text-success fa-2x"></i>
                <h6>Free Access</h6>
                <small class="text-muted">All content is completely free</small>
            </div>
            <div class="col-md-4">
                <i class="fas fa-flag-checkered text-success fa-2x"></i>
                <h6>Track Progress</h6>
                <small class="text-muted">Save your learning journey</small>
            </div>
            <div class="col-md-4">
                <i class="fas fa-heart text-success fa-2x"></i>
                <h6>Be the Change</h6>
                <small class="text-muted">Join the Balochistan learning revolution</small>
            </div>
        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>