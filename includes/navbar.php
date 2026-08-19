<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container">
        <a class="navbar-brand" href="<?php echo SITE_URL; ?>">
            <i class="fas fa-lightbulb text-warning"></i>
            <?php echo SITE_NAME; ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
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
                                <li><a class="dropdown-item text-primary" href="<?php echo SITE_URL; ?>admin/dashboard.php"><i class="fas fa-cog"></i> Admin Panel</a></li>
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