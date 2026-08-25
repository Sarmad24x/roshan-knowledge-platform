<?php
// admin/includes/admin-header.php
// Admin panel header with favicon and common resources
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Roshan Admin</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Admin Custom CSS -->
    <link rel="stylesheet" href="/roshan-knowledge-platform/admin/assets/css/admin.css">
    
    <!-- ============================================================ -->
    <!-- FAVICON -->
    <!-- ============================================================ -->
    <link rel="icon" type="image/png" href="/roshan-knowledge-platform/assets/images/icons/favicon.png">
    <link rel="icon" type="image/svg+xml" href="/roshan-knowledge-platform/assets/images/icons/favicon.svg">
    <link rel="shortcut icon" href="/roshan-knowledge-platform/assets/images/icons/favicon.ico">
</head>
<body>