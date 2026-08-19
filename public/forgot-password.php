<?php
$page_title = 'Forgot Password';
$current_page = 'forgot-password';
require_once '../config/database.php';
require_once '../includes/functions.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        // Check if user exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() > 0) {
            // In a real project, send password reset email
            // For demo, just show success message
            $message = 'If an account exists with this email, you will receive a password reset link.';
        } else {
            // Don't reveal if email exists or not (security)
            $message = 'If an account exists with this email, you will receive a password reset link.';
        }
    }
}

require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>

<section class="py-5" style="background: var(--primary-gradient);">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center text-white">
                <h1 class="display-4 fw-bold">
                    <i class="fas fa-key text-warning"></i> Forgot Password
                </h1>
                <p class="lead">We'll help you reset it</p>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <?php if ($message): ?>
                            <div class="alert alert-success"><?php echo $message; ?></div>
                        <?php endif; ?>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <p class="text-muted small">
                            Enter your email address and we'll send you a link to reset your password.
                        </p>
                        
                        <form method="POST" action="">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                
                                <div class="col-12">
                                    <button type="submit" class="btn btn-warning w-100">
                                        <i class="fas fa-paper-plane"></i> Send Reset Link
                                    </button>
                                </div>
                                
                                <div class="col-12 text-center">
                                    <a href="<?php echo SITE_URL; ?>login.php">Back to Login</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>