<?php
// public/logout.php
// Load config first to get SITE_URL
require_once '../config/database.php';
require_once '../includes/functions.php';

// Destroy all session data
$_SESSION = array();

// If using cookies, delete them
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the session
session_destroy();

// Redirect to home using SITE_URL
header('Location: ' . SITE_URL);
exit();
?>