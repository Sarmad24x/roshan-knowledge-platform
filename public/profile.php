<?php
$page_title = 'My Profile';
$current_page = 'profile';
require_once '../config/database.php';
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    header('Location: ' . SITE_URL . 'login.php');
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $full_name = sanitize($_POST['full_name'] ?? '');
        $bio = sanitize($_POST['bio'] ?? '');
        
        $stmt = $pdo->prepare("UPDATE users SET full_name = ?, bio = ? WHERE id = ?");
        if ($stmt->execute([$full_name, $bio, $_SESSION['user_id']])) {
            $_SESSION['user_full_name'] = $full_name;
            $message = 'Profile updated successfully!';
        } else {
            $error = 'Failed to update profile.';
        }
    } elseif (isset($_POST['change_password'])) {
        $current = $_POST['current_password'] ?? '';
        $new = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';
        
        if (!password_verify($current, $user['password_hash'])) {
            $error = 'Current password is incorrect.';
        } elseif (strlen($new) < 6) {
            $error = 'New password must be at least 6 characters.';
        } elseif ($new !== $confirm) {
            $error = 'Passwords do not match.';
        } else {
            $hash = password_hash($new, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
            if ($stmt->execute([$hash, $_SESSION['user_id']])) {
                $message = 'Password changed successfully!';
            } else {
                $error = 'Failed to change password.';
            }
        }
    }
}

require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>

<!-- Page Header -->
<div style="background: var(--primary-gradient); padding: 50px 0 30px 0; margin-top: -1px;">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-8 mx-auto">
                <h1 class="display-4 fw-bold text-white">
                    <i class="fas fa-user-circle text-warning"></i> My Profile
                </h1>
                <p class="lead text-white-50">Manage your account settings</p>
            </div>
        </div>
    </div>
</div>

<!-- Profile Content -->
<div style="padding: 40px 0;">
    <div class="container">
        <?php if ($message): ?>
            <div class="alert alert-success alert-dismissible fade show" style="border-radius: 16px;">
                <i class="fas fa-check-circle"></i> <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" style="border-radius: 16px;">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <div class="row g-4">
            <!-- Profile Info -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm" style="border-radius: 20px; overflow: hidden;">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-bold">
                            <i class="fas fa-user text-warning"></i> Profile Information
                        </h5>
                        
                        <form method="POST" action="">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Username</label>
                                    <input type="text" class="form-control bg-light" value="<?php echo $user['username']; ?>" disabled>
                                    <div class="form-text small text-muted">Username cannot be changed</div>
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control bg-light" value="<?php echo $user['email']; ?>" disabled>
                                    <div class="form-text small text-muted">Email cannot be changed</div>
                                </div>
                                
                                <div class="col-12">
                                    <label for="full_name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="full_name" name="full_name" 
                                           value="<?php echo htmlspecialchars($user['full_name']); ?>">
                                </div>
                                
                                <div class="col-12">
                                    <label for="bio" class="form-label">Bio</label>
                                    <textarea class="form-control" id="bio" name="bio" rows="4"><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                                    <div class="form-text small text-muted">Tell us a bit about yourself</div>
                                </div>
                                
                                <div class="col-12">
                                    <button type="submit" name="update_profile" class="btn btn-warning w-100 rounded-pill">
                                        <i class="fas fa-save"></i> Update Profile
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Change Password -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm" style="border-radius: 20px; overflow: hidden;">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-bold">
                            <i class="fas fa-key text-warning"></i> Change Password
                        </h5>
                        
                        <form method="POST" action="">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="current_password" class="form-label">Current Password</label>
                                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                                </div>
                                
                                <div class="col-12">
                                    <label for="new_password" class="form-label">New Password</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                                    <div class="form-text small text-muted">At least 6 characters</div>
                                </div>
                                
                                <div class="col-12">
                                    <label for="confirm_password" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                </div>
                                
                                <div class="col-12">
                                    <button type="submit" name="change_password" class="btn btn-primary w-100 rounded-pill">
                                        <i class="fas fa-key"></i> Change Password
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Account Info -->
                <div class="card border-0 bg-light mt-3" style="border-radius: 20px; overflow: hidden;">
                    <div class="card-body p-4">
                        <h6 class="fw-bold"><i class="fas fa-info-circle text-warning"></i> Account Info</h6>
                        <div class="row small">
                            <div class="col-6">
                                <strong>Role:</strong>
                                <span class="badge bg-secondary"><?php echo ucfirst($user['role']); ?></span>
                            </div>
                            <div class="col-6">
                                <strong>Status:</strong>
                                <?php if ($user['is_approved']): ?>
                                    <span class="badge bg-success">Approved ✅</span>
                                <?php else: ?>
                                    <span class="badge bg-warning">Pending ⏳</span>
                                <?php endif; ?>
                            </div>
                            <div class="col-12 mt-2">
                                <strong>Joined:</strong> <?php echo formatDate($user['created_at']); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>