<?php
// admin/users/approve.php
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

// Get user
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND role != 'admin'");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    header('Location: index.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'approve') {
        $stmt = $pdo->prepare("UPDATE users SET is_approved = 1 WHERE id = ?");
        if ($stmt->execute([$id])) {
            $success = 'User approved successfully!';
        } else {
            $error = 'Failed to approve user.';
        }
    } elseif ($action === 'reject') {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        if ($stmt->execute([$id])) {
            header('Location: index.php?msg=deleted');
            exit();
        } else {
            $error = 'Failed to reject user.';
        }
    } elseif ($action === 'change_role') {
        $role = sanitize($_POST['role'] ?? 'student');
        $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
        if ($stmt->execute([$role, $id])) {
            $success = 'Role updated successfully!';
        } else {
            $error = 'Failed to update role.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approve User - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar">
                <?php include '../includes/sidebar.php'; ?>
            </nav>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Approve User</h1>
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <div class="row">
                    <!-- User Details -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fas fa-user"></i> User Details</h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-4 fw-bold">Username:</div>
                                    <div class="col-8"><?php echo htmlspecialchars($user['username']); ?></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4 fw-bold">Email:</div>
                                    <div class="col-8"><?php echo htmlspecialchars($user['email']); ?></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4 fw-bold">Full Name:</div>
                                    <div class="col-8"><?php echo htmlspecialchars($user['full_name'] ?? 'Not provided'); ?></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4 fw-bold">Role:</div>
                                    <div class="col-8">
                                        <span class="badge bg-<?php echo $user['role'] == 'admin' ? 'danger' : ($user['role'] == 'teacher' ? 'warning' : 'info'); ?>">
                                            <?php echo ucfirst($user['role']); ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4 fw-bold">Status:</div>
                                    <div class="col-8">
                                        <?php if ($user['is_approved']): ?>
                                            <span class="status-badge published">Approved ✅</span>
                                        <?php else: ?>
                                            <span class="status-badge draft">Pending ⏳</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4 fw-bold">Joined:</div>
                                    <div class="col-8"><?php echo formatDate($user['created_at']); ?></div>
                                </div>
                                <?php if ($user['bio']): ?>
                                    <div class="row mb-2">
                                        <div class="col-4 fw-bold">Bio:</div>
                                        <div class="col-8"><?php echo nl2br(htmlspecialchars($user['bio'])); ?></div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fas fa-cog"></i> Actions</h5>
                            </div>
                            <div class="card-body">
                                <?php if (!$user['is_approved']): ?>
                                    <div class="alert alert-warning">
                                        <i class="fas fa-clock"></i> This user is pending approval.
                                    </div>
                                    
                                    <form method="POST" action="">
                                        <button type="submit" name="action" value="approve" class="btn btn-success w-100 mb-2">
                                            <i class="fas fa-check-circle"></i> Approve User
                                        </button>
                                        <button type="submit" name="action" value="reject" class="btn btn-danger w-100" 
                                                onclick="return confirm('Are you sure you want to reject this user? This will delete their account.')">
                                            <i class="fas fa-times-circle"></i> Reject User
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <div class="alert alert-success">
                                        <i class="fas fa-check-circle"></i> This user is already approved.
                                    </div>
                                    
                                    <a href="index.php" class="btn btn-primary w-100">
                                        <i class="fas fa-arrow-left"></i> Back to Users
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Change Role -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5><i class="fas fa-user-tag"></i> Change Role</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="">
                                    <div class="row g-2">
                                        <div class="col-8">
                                            <select name="role" class="form-select">
                                                <option value="student" <?php echo $user['role'] == 'student' ? 'selected' : ''; ?>>Student</option>
                                                <option value="teacher" <?php echo $user['role'] == 'teacher' ? 'selected' : ''; ?>>Teacher</option>
                                            </select>
                                        </div>
                                        <div class="col-4">
                                            <button type="submit" name="action" value="change_role" class="btn btn-primary w-100">
                                                <i class="fas fa-save"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <small class="text-muted">Change the user's role. Admin cannot be changed.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>