<?php
// create-admin.php
require_once 'config/database.php';

echo "<h1>🔧 Create Admin User</h1>";

// Check if admin exists
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = 'admin'");
$stmt->execute();
$existing = $stmt->fetch();

if ($existing) {
    echo "<p>⚠️ Admin already exists!</p>";
    echo "<pre>";
    print_r($existing);
    echo "</pre>";
    echo "<p>Current role: " . $existing['role'] . "</p>";
    echo "<p>Approved: " . ($existing['is_approved'] ? 'Yes' : 'No') . "</p>";
    
    // Update to admin if needed
    if ($existing['role'] != 'admin' || !$existing['is_approved']) {
        $stmt = $pdo->prepare("UPDATE users SET role = 'admin', is_approved = 1 WHERE id = ?");
        $stmt->execute([$existing['id']]);
        echo "<p style='color:green;'>✅ Updated user to admin and approved!</p>";
    }
} else {
    // Create new admin
    $password_hash = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, full_name, role, is_approved) 
                           VALUES (?, ?, ?, ?, 'admin', 1)");
    
    if ($stmt->execute(['admin', 'admin@roshan.com', $password_hash, 'Roshan Admin'])) {
        echo "<p style='color:green;'>✅ Admin user created successfully!</p>";
        echo "<p>Username: admin</p>";
        echo "<p>Password: admin123</p>";
    } else {
        echo "<p style='color:red;'>❌ Failed to create admin.</p>";
    }
}

// List all users
echo "<h2>All Users:</h2>";
$users = $pdo->query("SELECT id, username, email, role, is_approved FROM users")->fetchAll();
echo "<table border='1' cellpadding='8'>";
echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Approved</th></tr>";
foreach ($users as $user) {
    echo "<tr>";
    echo "<td>" . $user['id'] . "</td>";
    echo "<td>" . $user['username'] . "</td>";
    echo "<td>" . $user['email'] . "</td>";
    echo "<td>" . $user['role'] . "</td>";
    echo "<td>" . ($user['is_approved'] ? '✅ Yes' : '❌ No') . "</td>";
    echo "</tr>";
}
echo "</table>";
?>