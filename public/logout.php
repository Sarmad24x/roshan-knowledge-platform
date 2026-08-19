<?php
// public/logout.php
session_start();

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

// Redirect to home
header('Location: ' . SITE_URL . 'index.php');
exit();
?>