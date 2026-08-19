<div class="position-sticky pt-3">
    <h5 class="text-warning px-3 py-2">
        <i class="fas fa-lightbulb"></i> Roshan
    </h5>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?> text-white" href="../dashboard.php">
                <i class="fas fa-home"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'categories') !== false ? 'active' : ''; ?> text-white" href="../categories/index.php">
                <i class="fas fa-layer-group"></i> Categories
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'lessons') !== false ? 'active' : ''; ?> text-white" href="../lessons/index.php">
                <i class="fas fa-book"></i> Lessons
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'books') !== false ? 'active' : ''; ?> text-white" href="../books/index.php">
                <i class="fas fa-book-open"></i> Books
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'users') !== false ? 'active' : ''; ?> text-white" href="../users/index.php">
                <i class="fas fa-users"></i> Users
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'media') !== false ? 'active' : ''; ?> text-white" href="../media/index.php">
                <i class="fas fa-images"></i> Media
            </a>
        </li>
        <li class="nav-item mt-3">
            <a class="nav-link text-danger" href="../logout.php">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </li>
    </ul>
</div>