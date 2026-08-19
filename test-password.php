<?php
// test-password.php
require_once 'config/database.php';

echo "<h1>🔍 Direct Password Test</h1>";

// Get admin user
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = 'admin'");
$stmt->execute();
$admin = $stmt->fetch();

if ($admin) {
    echo "<p>Admin found: " . $admin['username'] . "</p>";
    echo "<p>Stored hash: " . $admin['password_hash'] . "</p>";
    
    // Test the password 'admin123'
    $test_password = 'admin123';
    $result = password_verify($test_password, $admin['password_hash']);
    
    echo "<p>Testing password: <strong>$test_password</strong></p>";
    echo "<p>Password verify result: " . ($result ? '✅ TRUE (password works!)' : '❌ FALSE (password does NOT match)') . "</p>";
    
    if (!$result) {
        echo "<h3 style='color:red;'>⚠️ Password doesn't match! Resetting...</h3>";
        
        // Create new hash
        $new_hash = password_hash($test_password, PASSWORD_DEFAULT);
        echo "<p>New hash: " . $new_hash . "</p>";
        
        // Update database
        $update = $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
        if ($update->execute([$new_hash, $admin['id']])) {
            echo "<p style='color:green;'>✅ Password hash updated!</p>";
            
            // Test again
            if (password_verify($test_password, $new_hash)) {
                echo "<p style='color:green;'>✅ New hash verified - you can now login with 'admin123'</p>";
            }
        }
    }
    
    // Also test if password_verify works directly
    echo "<h3>Testing password_verify with raw values:</h3>";
    $test_hash = password_hash('admin123', PASSWORD_DEFAULT);
    echo "New hash for 'admin123': " . $test_hash . "<br>";
    echo "Verify new hash: " . (password_verify('admin123', $test_hash) ? '✅ Works' : '❌ Fails') . "<br>";
    
} else {
    echo "<p style='color:red;'>❌ Admin not found!</p>";
}
?>