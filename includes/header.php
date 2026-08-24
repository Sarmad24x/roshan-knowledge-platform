<?php
// includes/header.php
// Add this at the VERY TOP before anything else
require_once __DIR__ . '/cart-functions.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?><?php echo SITE_NAME; ?></title>
    
    <!-- Meta Tags for SEO -->
    <meta name="description" content="Roshan - Enlightenment through knowledge. Educational platform for Balochistan.">
    <meta name="keywords" content="education, balochistan, learning, understanding, knowledge">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu&family=Amiri&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/roshan-knowledge-platform/assets/css/style.css">
    <link rel="stylesheet" href="/roshan-knowledge-platform/assets/css/animations.css">
    <link rel="stylesheet" href="/roshan-knowledge-platform/assets/css/responsive.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/roshan-knowledge-platform/assets/images/icons/favicon.png">
    <link rel="icon" type="image/svg+xml" href="/roshan-knowledge-platform/assets/images/icons/favicon.svg">
    <link rel="shortcut icon" href="/roshan-knowledge-platform/assets/images/icons/favicon.ico">
</head>
<body>