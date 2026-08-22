<?php
$page_title = 'Our Teachers';
$current_page = 'teachers';
require_once '../config/database.php';
require_once '../includes/functions.php';

$stmt = $pdo->query("SELECT * FROM users WHERE role = 'teacher' AND is_approved = 1 ORDER BY created_at DESC");
$teachers = $stmt->fetchAll();

require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>

<!-- Page Header -->
<div style="background: var(--primary-gradient); padding: 50px 0 30px 0; margin-top: -1px;">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-8 mx-auto">
                <h1 class="display-4 fw-bold text-white">
                    <i class="fas fa-chalkboard-teacher text-warning"></i> Our Teachers
                </h1>
                <p class="lead text-white-50">Scholars, professors, and philosophers sharing knowledge</p>
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">
                    <i class="fas fa-user-graduate"></i> <?php echo count($teachers); ?> Teachers & Scholars
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Teachers Grid -->
<div style="padding: 40px 0;">
    <div class="container">
        <?php if (count($teachers) > 0): ?>
            <div class="row g-4">
                <?php foreach($teachers as $index => $teacher): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm h-100 text-center p-4" style="border-radius: 20px; transition: all 0.4s ease;">
                            
                            <?php if($teacher['profile_image']): ?>
                                <img src="<?php echo SITE_URL . $teacher['profile_image']; ?>" 
                                     class="rounded-circle mx-auto" 
                                     alt="<?php echo htmlspecialchars($teacher['full_name']); ?>"
                                     style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #ffd700;">
                            <?php else: ?>
                                <div class="rounded-circle mx-auto" style="width: 120px; height: 120px; background: #f8f9fa; display: flex; align-items: center; justify-content: center; border: 4px solid #ffd700;">
                                    <i class="fas fa-user-graduate fa-4x text-muted"></i>
                                </div>
                            <?php endif; ?>
                            
                            <div class="mt-3">
                                <h5 class="card-title fw-bold"><?php echo htmlspecialchars($teacher['full_name']); ?></h5>
                                <p class="text-muted small">
                                    <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($teacher['email']); ?>
                                </p>
                                <?php if($teacher['bio']): ?>
                                    <p class="card-text small text-muted">
                                        <?php echo htmlspecialchars(substr($teacher['bio'], 0, 150)) . '...'; ?>
                                    </p>
                                <?php endif; ?>
                                <span class="badge bg-success rounded-pill px-3 py-2">Verified Scholar</span>
                            </div>
                            
                            <div class="mt-3">
                                <a href="<?php echo SITE_URL; ?>lessons.php?teacher=<?php echo $teacher['id']; ?>" 
                                   class="btn btn-outline-primary rounded-pill px-4">
                                    <i class="fas fa-book"></i> View Lessons
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-chalkboard-teacher fa-4x text-muted mb-3"></i>
                <h3>No Teachers Yet</h3>
                <p class="text-muted">We're currently onboarding scholars. Check back soon!</p>
            </div>
        <?php endif; ?>
        
        <!-- Call to Action -->
        <div class="card bg-dark text-white mt-5 border-0" style="border-radius: 20px; overflow: hidden;">
            <div class="card-body p-5 text-center">
                <h4 class="fw-bold">Are You a Teacher or Scholar?</h4>
                <p class="text-muted">Join us in transforming education in Balochistan.</p>
                <a href="<?php echo SITE_URL; ?>contact.php" class="btn btn-warning rounded-pill px-5">
                    <i class="fas fa-handshake"></i> Partner With Us
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.08) !important;
}
</style>

<?php require_once '../includes/footer.php'; ?>