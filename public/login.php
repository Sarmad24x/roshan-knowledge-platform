<?php
$page_title = 'Login';
$current_page = 'login';
require_once '../config/database.php';
require_once '../includes/functions.php';

// If already logged in, redirect
if (isLoggedIn()) {
    header('Location: ' . SITE_URL . 'index.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password_hash'])) {
            if (!$user['is_approved']) {
                $error = 'Your account is pending approval. Please wait for admin verification.';
            } else {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_full_name'] = $user['full_name'];
                
                if ($user['role'] === 'admin' || $user['role'] === 'teacher') {
                    // Remove 'public/' from the path - go to root level admin
                    header('Location: /roshan-knowledge-platform/admin/dashboard.php');
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

<!-- ============================================================ -->
<!-- LOGIN PAGE WITH ENHANCED UI -->
<!-- ============================================================ -->
<section class="auth-section" style="min-height: calc(100vh - 200px); background: var(--primary-gradient); display: flex; align-items: center; padding: 60px 0;">
    
    <!-- Floating Shapes Background -->
    <div class="auth-shapes">
        <div class="auth-shape auth-shape-1"></div>
        <div class="auth-shape auth-shape-2"></div>
        <div class="auth-shape auth-shape-3"></div>
    </div>
    
    <div class="container position-relative" style="z-index: 1;">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                
                <!-- Logo/Brand -->
                <div class="text-center mb-4 animate__animated animate__fadeInDown">
                    <a href="<?php echo SITE_URL; ?>" class="text-decoration-none">
                        <h1 class="display-4 fw-bold text-warning">
                            <i class="fas fa-lightbulb"></i> Roshan
                        </h1>
                    </a>
                    <p class="text-white-50">Welcome back! Login to continue learning</p>
                </div>
                
                <!-- Login Card -->
                <div class="auth-card animate__animated animate__fadeInUp" style="background: rgba(255,255,255,0.05); backdrop-filter: blur(20px); border: 1px solid rgba(255,215,0,0.15); border-radius: 24px; padding: 40px; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
                    
                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show shake-animation" role="alert" style="border-radius: 12px;">
                            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="" class="needs-validation" novalidate>
                        <div class="mb-4">
                            <label class="form-label text-white fw-bold">Username or Email</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-transparent border-end-0 text-warning" style="border-color: rgba(255,255,255,0.2);">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text" name="username" class="form-control bg-transparent border-start-0 text-white" 
                                       placeholder="Enter your username or email" required
                                       style="border-color: rgba(255,255,255,0.2);">
                            </div>
                            <div class="invalid-feedback">Please enter your username or email.</div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label text-white fw-bold">Password</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-transparent border-end-0 text-warning" style="border-color: rgba(255,255,255,0.2);">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" name="password" id="password" class="form-control bg-transparent border-start-0 text-white" 
                                       placeholder="Enter your password" required
                                       style="border-color: rgba(255,255,255,0.2);">
                                <button type="button" id="togglePassword" class="input-group-text bg-transparent border-start-0 text-white-50" 
                                        style="border-color: rgba(255,255,255,0.2); cursor: pointer;">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback">Please enter your password.</div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember" style="border-color: rgba(255,255,255,0.3);">
                                <label class="form-check-label text-white-50" for="remember">Remember me</label>
                            </div>
                            <a href="<?php echo SITE_URL; ?>forgot-password.php" class="text-warning text-decoration-none small">
                                Forgot password?
                            </a>
                        </div>
                        
                        <button type="submit" class="btn btn-warning btn-lg w-100 rounded-pill fw-bold py-3 pulse-glow" 
                                style="font-size: 1.1rem; transition: all 0.3s ease;">
                            <i class="fas fa-sign-in-alt me-2"></i> Login
                        </button>
                    </form>
                    
                    <div class="text-center mt-4">
                        <p class="text-white-50 small">
                            Don't have an account? <a href="<?php echo SITE_URL; ?>register.php" class="text-warning fw-bold text-decoration-none">Register here</a>
                        </p>
                    </div>
                </div>
                
                <!-- Demo Credentials -->
                <div class="mt-3 text-center">
                    <div class="d-inline-block bg-dark bg-opacity-50 px-4 py-2 rounded-pill" style="border: 1px solid rgba(255,255,255,0.1);">
                        <small class="text-white-50">
                            <i class="fas fa-info-circle text-warning"></i> 
                            Demo: <strong class="text-white">admin</strong> / <strong class="text-white">admin123</strong>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- LOGIN PAGE STYLES -->
<!-- ============================================================ -->
<style>
.auth-shapes {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 0;
    pointer-events: none;
    overflow: hidden;
}

.auth-shape {
    position: absolute;
    border-radius: 50%;
    opacity: 0.05;
    animation: float-shape 25s infinite ease-in-out;
}

.auth-shape-1 {
    width: 400px;
    height: 400px;
    background: #ffd700;
    top: -10%;
    right: -5%;
    animation-delay: 0s;
}

.auth-shape-2 {
    width: 250px;
    height: 250px;
    background: #3498db;
    bottom: -10%;
    left: -5%;
    animation-delay: -8s;
}

.auth-shape-3 {
    width: 150px;
    height: 150px;
    background: #2ecc71;
    top: 50%;
    left: 50%;
    animation-delay: -15s;
}

.auth-card .form-control {
    color: white !important;
    padding: 12px 16px;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.auth-card .form-control:focus {
    border-color: #ffd700 !important;
    box-shadow: 0 0 0 0.25rem rgba(255, 215, 0, 0.15) !important;
    background: rgba(255,255,255,0.05) !important;
}

.auth-card .form-control::placeholder {
    color: rgba(255, 255, 255, 0.4);
}

.auth-card .input-group-text {
    color: rgba(255,255,255,0.6);
    padding: 12px 16px;
    border-radius: 12px 0 0 12px;
}

.auth-card .input-group .form-control {
    border-radius: 0 12px 12px 0;
}

.auth-card .input-group .form-control:focus + .input-group-text {
    border-color: #ffd700;
}

.shake-animation {
    animation: shake 0.5s ease;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    20% { transform: translateX(-10px); }
    40% { transform: translateX(10px); }
    60% { transform: translateX(-5px); }
    80% { transform: translateX(5px); }
}

/* Custom Checkbox */
.form-check-input {
    width: 18px;
    height: 18px;
    margin-top: 2px;
    background-color: rgba(255,255,255,0.1);
    border-color: rgba(255,255,255,0.3);
    cursor: pointer;
}

.form-check-input:checked {
    background-color: #ffd700;
    border-color: #ffd700;
}

.form-check-input:focus {
    box-shadow: 0 0 0 0.25rem rgba(255, 215, 0, 0.25);
}

/* Responsive */
@media (max-width: 576px) {
    .auth-card {
        padding: 24px !important;
    }
    .auth-card .input-group-lg .form-control,
    .auth-card .input-group-lg .input-group-text {
        font-size: 0.9rem;
        padding: 10px 12px;
    }
}
</style>

<!-- ============================================================ -->
<!-- LOGIN PAGE JAVASCRIPT -->
<!-- ============================================================ -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // ---- Toggle Password Visibility ----
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    
    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    }
    
    // ---- Form Validation with Animation ----
    const form = document.querySelector('.needs-validation');
    if (form) {
        form.addEventListener('submit', function(event) {
            if (!this.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                
                // Add shake animation to invalid fields
                const invalidFields = this.querySelectorAll(':invalid');
                invalidFields.forEach(field => {
                    field.closest('.mb-4')?.classList.add('shake-animation');
                    setTimeout(() => {
                        field.closest('.mb-4')?.classList.remove('shake-animation');
                    }, 500);
                });
            }
            this.classList.add('was-validated');
        }, false);
    }
    
    // ---- Auto-dismiss Alerts ----
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.classList.add('fade');
            setTimeout(() => { alert.style.display = 'none'; }, 500);
        }, 5000);
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>