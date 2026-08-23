<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top" id="siteHeader">
    <div class="container">
        <a class="navbar-brand" href="<?php echo SITE_URL; ?>">
            <i class="fas fa-lightbulb text-warning"></i>
            <?php echo SITE_NAME; ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'home') ? 'active' : ''; ?>" href="<?php echo SITE_URL; ?>">
                        <i class="fas fa-home"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'disciplines') ? 'active' : ''; ?>" href="<?php echo SITE_URL; ?>disciplines.php">
                        <i class="fas fa-layer-group"></i> Disciplines
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'lessons') ? 'active' : ''; ?>" href="<?php echo SITE_URL; ?>lessons.php">
                        <i class="fas fa-book"></i> Lessons
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'books') ? 'active' : ''; ?>" href="<?php echo SITE_URL; ?>books.php">
                        <i class="fas fa-book-open"></i> Books
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'teachers') ? 'active' : ''; ?>" href="<?php echo SITE_URL; ?>teachers.php">
                        <i class="fas fa-chalkboard-teacher"></i> Teachers
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'about') ? 'active' : ''; ?>" href="<?php echo SITE_URL; ?>about.php">
                        <i class="fas fa-info-circle"></i> About
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'contact') ? 'active' : ''; ?>" href="<?php echo SITE_URL; ?>contact.php">
                        <i class="fas fa-envelope"></i> Contact
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <?php if(isLoggedIn()): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle"></i> <?php echo $_SESSION['username']; ?>
                        </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>profile.php"><i class="fas fa-user"></i> Profile</a></li>
                    <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>favorites.php"><i class="fas fa-heart"></i> Favorites</a></li>
                <?php if(hasRole('admin') || hasRole('teacher')): ?>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-primary" href="/roshan-knowledge-platform/admin/dashboard.php">
                        <i class="fas fa-cog"></i> Admin Panel
                    </a></li>
                <?php endif; ?>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="<?php echo SITE_URL; ?>logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITE_URL; ?>login.php"><i class="fas fa-sign-in-alt"></i> Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-success btn-sm px-3" href="<?php echo SITE_URL; ?>register.php"><i class="fas fa-user-plus"></i> Register</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- ============================================================ -->
<!-- HEADER PROGRESS BAR - NOW INSIDE NAVBAR AND STYLISH -->
<!-- ============================================================ -->
<div id="headerProgressWrapper" style="position: fixed; top: 0; left: 0; width: 100%; height: 3px; z-index: 9999; background: transparent; pointer-events: none;">
    <div id="headerProgress" style="width: 0%; height: 100%; background: linear-gradient(90deg, #ffd700, #f39c12); border-radius: 0 2px 2px 0; transition: width 0.1s ease; box-shadow: 0 0 20px rgba(255, 215, 0, 0.3);"></div>
</div>

<style>
/* Make navbar background darker and remove any gap */
.navbar {
    background: rgba(10, 10, 46, 0.98) !important;
    border-bottom: 2px solid #ffd700;
    padding: 12px 0;
    margin: 0 !important;
    position: sticky;
    top: 0;
    z-index: 1030;
}

/* Fix for any white space above navbar */
body {
    padding-top: 0 !important;
    margin-top: 0 !important;
}

/* Progress bar animation */
#headerProgress {
    animation: progressGlow 1.5s ease-in-out infinite;
}

@keyframes progressGlow {
    0%, 100% { box-shadow: 0 0 10px rgba(255, 215, 0, 0.2); }
    50% { box-shadow: 0 0 30px rgba(255, 215, 0, 0.5); }
}
</style>

<!-- ============================================================ -->
<!-- JAVASCRIPT FOR PROGRESS BAR -->
<!-- ============================================================ -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Header Progress Bar on Scroll
    const progressBar = document.getElementById('headerProgress');
    if (progressBar) {
        window.addEventListener('scroll', function() {
            const scrollTop = window.scrollY;
            const docHeight = document.documentElement.scrollHeight - window.innerHeight;
            const progress = (scrollTop / docHeight) * 100;
            progressBar.style.width = progress + '%';
        });
    }
});
</script>