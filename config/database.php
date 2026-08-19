<?php
// config/database.php
// Database configuration for Roshan Platform

// Database credentials
define('DB_HOST', 'localhost');
define('DB_NAME', 'roshan_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Create connection
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Site settings - CORRECT PATH
define('SITE_NAME', 'Roshan');
define('SITE_URL', 'http://localhost/roshan-knowledge-platform/public/');  // <-- FIXED
define('ADMIN_EMAIL', 'admin@roshan.com');
define('UPLOAD_PATH', __DIR__ . '/../assets/images/uploads/');

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>