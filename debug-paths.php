<?php
// debug-paths.php
echo "<h1>🔍 Path Debug</h1>";

echo "<h2>File System:</h2>";
echo "assets/css/style.css exists: " . (file_exists('assets/css/style.css') ? '✅ YES' : '❌ NO') . "<br>";
echo "assets/js/main.js exists: " . (file_exists('assets/js/main.js') ? '✅ YES' : '❌ NO') . "<br>";
echo "public/index.php exists: " . (file_exists('public/index.php') ? '✅ YES' : '❌ NO') . "<br>";

echo "<h2>URL Tests (Click to test):</h2>";
echo "<ul>";
echo "<li><a href='/roshan-knowledge-platform/assets/css/style.css'>/roshan-knowledge-platform/assets/css/style.css</a></li>";
echo "<li><a href='assets/css/style.css'>assets/css/style.css (relative)</a></li>";
echo "<li><a href='public/assets/css/style.css'>public/assets/css/style.css</a></li>";
echo "</ul>";

echo "<h2>Current Directory:</h2>";
echo __DIR__ . "<br>";

echo "<h2>Files in assets/css/:</h2>";
if (is_dir('assets/css')) {
    $files = scandir('assets/css');
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            echo "📄 $file<br>";
        }
    }
} else {
    echo "❌ assets/css/ folder not found!<br>";
}

echo "<h2>Files in public/:</h2>";
if (is_dir('public')) {
    $files = scandir('public');
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            echo "📄 $file<br>";
        }
    }
} else {
    echo "❌ public/ folder not found!<br>";
}
?>