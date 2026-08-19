<?php
$page_title = 'Our Teachers';
$current_page = 'teachers';
require_once '../config/database.php';
require_once '../includes/functions.php';

// Get approved teachers
$stmt = $pdo->query("SELECT * FROM users WHERE role = 'teacher' AND is_approved = 1 ORDER BY created_at DESC");
$teachers = $stmt->fetchAll();

require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>

<!-- Page Header -->
<section class="py-5" style="background: var(--primary-gradient);">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center text-white">
                <h1 class="display-4 fw-bold">
                    <i class="fas fa-chalkboard-teacher text-warning"></i> Our Teachers
                </h1>
                <p class="lead">Scholars, professors, and philosophers sharing knowledge</p>
            </div>
        </div>
    </div>
</section>

<!-- Teachers Grid -->
<section class="py-5">
    <div class="container">
        <?php if (count($teachers) > 0): ?>
            <div class="row g-4">
                <?php foreach($teachers as $teacher): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm hover-card border-0 text-center">
                            <?php if($teacher['profile_image']): ?>
                                <img src="<?php echo SITE_URL . $teacher['profile_image']; ?>" 
                                     class="card-img-top rounded-circle mx-auto mt-3" 
                                     alt="<?php echo htmlspecialchars($teacher['full_name']); ?>"
                                     style="width: 150px; height: 150px; object-fit: cover;">
                            <?php else: ?>
                                <div class="mx-auto mt-3" style="width: 150px; height: 150px;">
                                    <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 100%; height: 100%;">
                                        <i class="fas fa-user-graduate fa-4x"></i>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($teacher['full_name']); ?></h5>
                                <p class="text-muted small">
                                    <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($teacher['email']); ?>
                                </p>
                                <?php if($teacher['bio']): ?>
                                    <p class="card-text small">
                                        <?php echo htmlspecialchars(substr($teacher['bio'], 0, 150)) . '...'; ?>
                                    </p>
                                <?php endif; ?>
                                <span class="badge bg-success">Verified Scholar</span>
                            </div>
                            
                            <div class="card-footer bg-transparent border-0">
                                <a href="<?php echo SITE_URL; ?>lessons.php?teacher=<?php echo $teacher['id']; ?>" 
                                   class="btn btn-outline-primary btn-sm">
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
        <div class="card bg-dark text-white mt-5 border-0">
            <div class="card-body p-5 text-center">
                <h4>Are You a Teacher or Scholar?</h4>
                <p class="text-muted">Join us in transforming education in Balochistan.</p>
                <a href="<?php echo SITE_URL; ?>contact.php" class="btn btn-warning">
                    <i class="fas fa-handshake"></i> Partner With Us
                </a>
            </div>
        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>