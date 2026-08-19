<?php
// admin/login.php - DEBUG VERSION
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// If already logged in, redirect
if (isLoggedIn() && hasRole('admin')) {
    header('Location: dashboard.php');
    exit();
}

$error = '';
$debug = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    $debug .= "Username entered: $username<br>";
    $debug .= "Password entered: " . str_repeat('*', strlen($password)) . "<br>";
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        // Try both username and email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();
        
        if ($user) {
            $debug .= "User found: " . $user['username'] . " (Role: " . $user['role'] . ")<br>";
            $debug .= "Approved: " . ($user['is_approved'] ? 'Yes' : 'No') . "<br>";
            
            if (password_verify($password, $user['password_hash'])) {
                $debug .= "✅ Password verified!<br>";
                
                if ($user['role'] !== 'admin') {
                    $error = 'This account is not an admin account.';
                    $debug .= "❌ Role is: " . $user['role'] . " (expected 'admin')<br>";
                } elseif (!$user['is_approved']) {
                    $error = 'Your account is pending approval.';
                    $debug .= "❌ Account not approved<br>";
                } else {
                    $debug .= "✅ All checks passed! Logging in...<br>";
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['user_role'] = $user['role'];
                    $_SESSION['user_full_name'] = $user['full_name'];
                    header('Location: dashboard.php');
                    exit();
                }
            } else {
                $error = 'Invalid password.';
                $debug .= "❌ Password verification failed<br>";
            }
        } else {
            $error = 'User not found.';
            $debug .= "❌ No user found with username/email: $username<br>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Roshan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #0a0a2e 0%, #1a0a3e 50%, #0a0a2e 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 215, 0, 0.2);
            border-radius: 20px;
            padding: 40px;
            color: white;
            width: 100%;
        }
        .login-card .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
        }
        .login-card .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: #ffd700;
            box-shadow: 0 0 0 0.25rem rgba(255, 215, 0, 0.25);
        }
        .login-card .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }
        .btn-gold {
            background: #ffd700;
            color: #0a0a2e;
            font-weight: 600;
        }
        .btn-gold:hover {
            background: #c4a800;
            color: #0a0a2e;
        }
        .logo-text {
            color: #ffd700;
            font-size: 2.5rem;
            font-weight: 700;
        }
        .debug-box {
            background: rgba(0,0,0,0.3);
            border-radius: 8px;
            padding: 15px;
            margin-top: 15px;
            font-size: 0.9rem;
            font-family: monospace;
            color: #0f0;
            border: 1px solid rgba(255,255,255,0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="login-card">
                    <div class="text-center mb-4">
                        <h1 class="logo-text">
                            <i class="fas fa-lightbulb"></i> Roshan
                        </h1>
                        <p class="text-muted">Admin Panel</p>
                    </div>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label">Username or Email</label>
                            <input type="text" name="username" class="form-control" placeholder="Enter username or email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                        </div>
                        <button type="submit" class="btn btn-gold w-100 py-2">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </button>
                    </form>
                    
                    <div class="text-center mt-3">
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i> Default: admin / admin123
                        </small>
                    </div>
                    <div class="text-center mt-2">
                        <a href="../public/index.php" class="text-warning text-decoration-none">
                            <i class="fas fa-arrow-left"></i> Back to Site
                        </a>
                    </div>
                    
                    <?php if ($debug): ?>
                        <div class="debug-box">
                            <strong>🔍 Debug Info:</strong><br>
                            <?php echo $debug; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>