<?php
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

// Get product to delete image
$stmt = $pdo->prepare("SELECT image_path FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if ($product && $product['image_path']) {
    $file_path = '../../' . $product['image_path'];
    if (file_exists($file_path)) {
        unlink($file_path);
    }
}

$stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
$stmt->execute([$id]);

header('Location: index.php?msg=deleted');
exit();
?>