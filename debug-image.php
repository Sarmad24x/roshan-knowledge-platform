<?php
// debug-image.php - Check image loading
require_once 'config/database.php';

echo "<h1>🔍 Image Loading Debug</h1>";

// Get one lesson
$stmt = $pdo->prepare("SELECT id, title, image_path FROM lessons WHERE image_path IS NOT NULL LIMIT 1");
$stmt->execute();
$lesson = $stmt->fetch();

if ($lesson) {
    echo "<h2>Lesson Details:</h2>";
    echo "<ul>";
    echo "<li><strong>ID:</strong> " . $lesson['id'] . "</li>";
    echo "<li><strong>Title:</strong> " . htmlspecialchars($lesson['title']) . "</li>";
    echo "<li><strong>Image Path in DB:</strong> <code>" . htmlspecialchars($lesson['image_path']) . "</code></li>";
    echo "</ul>";
    
    // Test paths
    $paths_to_test = [
        'Relative path' => $lesson['image_path'],
        'With SITE_URL' => SITE_URL . $lesson['image_path'],
        'Full filesystem' => __DIR__ . '/' . $lesson['image_path'],
        'Direct URL' => '/roshan-knowledge-platform/' . $lesson['image_path'],
    ];
    
    echo "<h2>Testing Paths:</h2>";
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>Method</th><th>Path</th><th>File Exists?</th><th>Preview</th></tr>";
    
    foreach ($paths_to_test as $method => $path) {
        // Check if file exists
        $full_path = __DIR__ . '/' . $lesson['image_path'];
        $exists = file_exists($full_path);
        
        echo "<tr>";
        echo "<td><strong>" . $method . "</strong></td>";
        echo "<td><code>" . htmlspecialchars($path) . "</code></td>";
        echo "<td>" . ($exists ? '✅ YES' : '❌ NO') . "</td>";
        echo "<td>";
        if ($exists) {
            // Try to display the image
            $ext = strtolower(pathinfo($lesson['image_path'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                echo "<img src='/" . $lesson['image_path'] . "' style='max-width:200px;max-height:150px;border:1px solid #ddd;border-radius:8px;'>";
            } else if ($ext === 'svg') {
                // For SVG, read the file content
                $svg_content = file_get_contents($full_path);
                echo "<div style='max-width:200px;max-height:150px;border:1px solid #ddd;border-radius:8px;overflow:hidden;'>";
                echo $svg_content;
                echo "</div>";
            } else {
                echo "Unsupported format: " . $ext;
            }
        } else {
            echo "❌ File missing";
        }
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // List all files in the folder
    echo "<h2>Files in Uploads Folder:</h2>";
    $folder = 'assets/images/uploads/lessons/';
    if (is_dir($folder)) {
        $files = scandir($folder);
        echo "<ul>";
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                $ext = pathinfo($file, PATHINFO_EXTENSION);
                $icon = '';
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) $icon = '🖼️';
                else if ($ext === 'svg') $icon = '📄';
                else if ($ext === 'webp') $icon = '🎨';
                else $icon = '📁';
                echo "<li>$icon $file</li>";
            }
        }
        echo "</ul>";
    }
    
    // Show the actual HTML that would be output
    echo "<h2>Actual HTML Output (from lessons.php):</h2>";
    echo "<div style='border:2px solid #ffd700;padding:20px;border-radius:12px;background:#f8f9fa;'>";
    echo "<h3>Image Tag:</h3>";
    echo "<pre style='background:#fff;padding:15px;border-radius:8px;'>&lt;img src='" . SITE_URL . $lesson['image_path'] . "' class='card-img-top' alt='" . htmlspecialchars($lesson['title']) . "' style='height:200px;object-fit:cover;'&gt;</pre>";
    
    echo "<h3>Rendered Image:</h3>";
    echo "<img src='" . SITE_URL . $lesson['image_path'] . "' style='max-width:100%;max-height:300px;border:2px solid #ddd;border-radius:8px;'>";
    echo "</div>";
    
    // Test direct URL access
    echo "<h2>Test Direct URL Access:</h2>";
    echo "<p>Click this link to see if the image loads directly:</p>";
    echo "<p><a href='" . SITE_URL . $lesson['image_path'] . "' target='_blank'>" . SITE_URL . $lesson['image_path'] . "</a></p>";
}

echo "<h2>🔧 SITE_URL Value:</h2>";
echo "<pre>" . SITE_URL . "</pre>";
?>