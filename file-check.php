<?php
// file-check.php
echo "<h1>📁 Files in public/ folder</h1>";
echo "<ul>";
$files = scandir('public/');
foreach ($files as $file) {
    if ($file != '.' && $file != '..') {
        echo "<li>" . $file . "</li>";
    }
}
echo "</ul>";

// Check if lesson-details.php exists
echo "<h2>Checking for lesson-details.php:</h2>";
if (file_exists('public/lesson-details.php')) {
    echo "✅ public/lesson-details.php EXISTS";
} else {
    echo "❌ public/lesson-details.php DOES NOT EXIST";
}

echo "<h2>Checking for lesson-detail.php:</h2>";
if (file_exists('public/lesson-detail.php')) {
    echo "✅ public/lesson-detail.php EXISTS";
} else {
    echo "❌ public/lesson-detail.php DOES NOT EXIST";
}
?>