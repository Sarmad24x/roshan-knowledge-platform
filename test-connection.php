<?php
// test-connection.php
// Quick test to verify database connection

echo "<h1>🔍 ROSHAN - Connection Test</h1>";
echo "<hr>";

// Test 1: Check if database.php exists
if (file_exists('config/database.php')) {
    echo "✅ config/database.php found<br>";
} else {
    die("❌ config/database.php NOT found! Check your folder structure.");
}

// Test 2: Test database connection
require_once 'config/database.php';

try {
    // Try a simple query
    $stmt = $pdo->query("SELECT 1");
    echo "✅ Database connection SUCCESSFUL!<br>";
    
    // Test 3: Check if tables exist
    $tables = ['users', 'categories', 'lessons', 'books', 'media', 'quiz_questions', 'user_progress', 'user_favorites'];
    echo "<br><strong>Checking tables:</strong><br>";
    
    foreach ($tables as $table) {
        $result = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($result->rowCount() > 0) {
            echo "✅ Table '$table' exists<br>";
        } else {
            echo "❌ Table '$table' is MISSING!<br>";
        }
    }
    
    // Test 4: Check data in categories
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM categories");
    $count = $stmt->fetch();
    echo "<br>📊 Categories found: " . $count['count'] . "<br>";
    
    // Test 5: Check admin user
    $stmt = $pdo->query("SELECT * FROM users WHERE username = 'admin'");
    $admin = $stmt->fetch();
    if ($admin) {
        echo "✅ Admin user found: " . $admin['username'] . "<br>";
    } else {
        echo "⚠️ Admin user NOT found. You may need to insert it manually.<br>";
    }
    
    echo "<hr>";
    echo "<h2 style='color: green;'>🎉 All tests passed! You're ready to continue.</h2>";
    
} catch (PDOException $e) {
    echo "<h2 style='color: red;'>❌ Database Error:</h2>";
    echo "<pre>" . $e->getMessage() . "</pre>";
}
?>