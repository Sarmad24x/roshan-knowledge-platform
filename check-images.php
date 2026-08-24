<?php
// check-images.php - Check all image paths
require_once 'config/database.php';

echo "<h1>🔍 Image Path Checker</h1>";

// Check Lesson Images
echo "<h2>📚 Lesson Images</h2>";
$lessons = $pdo->query("SELECT id, title, image_path FROM lessons WHERE image_path IS NOT NULL")->fetchAll();

echo "<table border='1' cellpadding='8'>";
echo "<tr><th>ID</th><th>Title</th><th>Path in DB</th><th>File Exists?</th><th>Preview</th></tr>";

foreach ($lessons as $lesson) {
    $path = $lesson['image_path'];
    $full_path = __DIR__ . '/' . $path;
    $exists = file_exists($full_path);
    
    echo "<tr>";
    echo "<td>" . $lesson['id'] . "</td>";
    echo "<td>" . htmlspecialchars($lesson['title']) . "</td>";
    echo "<td><code>" . htmlspecialchars($path) . "</code></td>";
    echo "<td>" . ($exists ? '✅ YES' : '❌ NO') . "</td>";
    echo "<td>";
    if ($exists) {
        echo "<img src='/" . $path . "' style='width:100px;height:60px;object-fit:cover;border-radius:4px;'>";
    } else {
        echo "❌ File missing";
    }
    echo "</td>";
    echo "</tr>";
}
echo "</table>";

// Check Book Covers
echo "<h2>📖 Book Covers</h2>";
$books = $pdo->query("SELECT id, title, cover_image FROM books WHERE cover_image IS NOT NULL")->fetchAll();

echo "<table border='1' cellpadding='8'>";
echo "<tr><th>ID</th><th>Title</th><th>Path in DB</th><th>File Exists?</th><th>Preview</th></tr>";

foreach ($books as $book) {
    $path = $book['cover_image'];
    $full_path = __DIR__ . '/' . $path;
    $exists = file_exists($full_path);
    
    echo "<tr>";
    echo "<td>" . $book['id'] . "</td>";
    echo "<td>" . htmlspecialchars($book['title']) . "</td>";
    echo "<td><code>" . htmlspecialchars($path) . "</code></td>";
    echo "<td>" . ($exists ? '✅ YES' : '❌ NO') . "</td>";
    echo "<td>";
    if ($exists) {
        echo "<img src='/" . $path . "' style='width:60px;height:80px;object-fit:cover;border-radius:4px;'>";
    } else {
        echo "❌ File missing";
    }
    echo "</td>";
    echo "</tr>";
}
echo "</table>";

// List all files in the uploads folder
echo "<h2>📁 Files in Uploads Folder</h2>";
$folders = [
    'assets/images/uploads/lessons/',
    'assets/images/uploads/books/'
];

foreach ($folders as $folder) {
    echo "<h3>" . $folder . "</h3>";
    if (is_dir($folder)) {
        $files = scandir($folder);
        echo "<ul>";
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                echo "<li>" . $file . "</li>";
            }
        }
        echo "</ul>";
    } else {
        echo "❌ Folder not found: " . $folder;
    }
}

echo "<h2>🔧 Recommended Fix</h2>";
echo "<p>If paths don't match, run this SQL:</p>";
echo "<pre>
UPDATE lessons SET image_path = 'assets/images/uploads/lessons/YOUR_FILENAME_HERE' WHERE id = YOUR_ID;
</pre>";
?>