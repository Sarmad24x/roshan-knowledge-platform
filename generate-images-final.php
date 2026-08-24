<?php
// generate-images-final.php
// Uses SVG - no GD library required!

echo "<h1>📸 Generating Lesson Thumbnails (SVG Method)</h1>";

// Create folders
$folders = [
    'assets/images/uploads/lessons/',
    'assets/images/uploads/books/',
    'assets/images/uploads/media/'
];

foreach ($folders as $folder) {
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
        echo "✅ Created folder: $folder<br>";
    }
}

// Function to create a simple SVG placeholder
function createSVGPlaceholder($filename, $color, $text, $icon = '📚') {
    $svg = '<?xml version="1.0" encoding="UTF-8"?>
<svg width="800" height="400" xmlns="http://www.w3.org/2000/svg">
    <defs>
        <linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#' . $color . '" />
            <stop offset="100%" stop-color="#' . $color . '" stop-opacity="0.7" />
        </linearGradient>
    </defs>
    <rect width="800" height="400" fill="url(#grad)" rx="8"/>
    
    <!-- Icon Circle -->
    <circle cx="400" cy="170" r="60" fill="rgba(255,255,255,0.08)" stroke="rgba(255,255,255,0.15)" stroke-width="2"/>
    <text x="400" y="200" font-family="Arial" font-size="48" text-anchor="middle">' . $icon . '</text>
    
    <!-- Title -->
    <text x="400" y="270" font-family="Arial" font-size="28" fill="white" text-anchor="middle" font-weight="bold">' . $text . '</text>
    
    <!-- Subtitle -->
    <text x="400" y="310" font-family="Arial" font-size="14" fill="rgba(255,255,255,0.5)" text-anchor="middle">Roshan Knowledge Platform</text>
    
    <!-- Decorative Line -->
    <rect x="350" y="340" width="100" height="3" fill="#ffd700" rx="2"/>
</svg>';
    
    file_put_contents($filename, $svg);
    echo "✅ Created: $filename<br>";
}

// Function to create book cover SVG
function createBookSVG($filename, $color, $text) {
    $svg = '<?xml version="1.0" encoding="UTF-8"?>
<svg width="300" height="400" xmlns="http://www.w3.org/2000/svg">
    <defs>
        <linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#' . $color . '" />
            <stop offset="100%" stop-color="#' . $color . '" stop-opacity="0.6" />
        </linearGradient>
    </defs>
    <rect width="300" height="400" fill="url(#grad)" rx="8"/>
    <rect x="10" y="10" width="280" height="380" fill="none" stroke="rgba(255,255,255,0.08)" stroke-width="2" rx="4"/>
    
    <!-- Book Icon -->
    <circle cx="150" cy="150" r="50" fill="rgba(255,255,255,0.06)" stroke="rgba(255,215,0,0.2)" stroke-width="2"/>
    <text x="150" y="175" font-family="Arial" font-size="40" text-anchor="middle">📖</text>
    
    <!-- Title -->
    <text x="150" y="240" font-family="Arial" font-size="16" fill="white" text-anchor="middle" font-weight="bold">' . $text . '</text>
    
    <!-- Subtitle -->
    <text x="150" y="270" font-family="Arial" font-size="11" fill="rgba(255,255,255,0.4)" text-anchor="middle">Roshan Book</text>
    
    <!-- Decorative Line -->
    <rect x="90" y="370" width="120" height="3" fill="rgba(255,215,0,0.3)" rx="2"/>
</svg>';
    
    file_put_contents($filename, $svg);
    echo "✅ Created: $filename<br>";
}

// ============================================================
// Generate Lesson Thumbnails
// ============================================================
echo "<h2>Generating Lesson Images...</h2>";
createSVGPlaceholder('assets/images/uploads/lessons/islamic-studies.jpg', '1a5276', 'Islamic Studies', '🕌');
createSVGPlaceholder('assets/images/uploads/lessons/astronomy.jpg', '1a1a2e', 'Astronomy', '🚀');
createSVGPlaceholder('assets/images/uploads/lessons/psychology.jpg', '6c3483', 'Psychology', '🧠');
createSVGPlaceholder('assets/images/uploads/lessons/philosophy.jpg', '935116', 'Philosophy', '📜');
createSVGPlaceholder('assets/images/uploads/lessons/computer-science.jpg', '1a5276', 'Computer Science', '💻');

// ============================================================
// Generate Book Covers
// ============================================================
echo "<h2>Generating Book Covers...</h2>";
createBookSVG('assets/images/uploads/books/study-quran.jpg', '1a5276', 'The Study Quran');
createBookSVG('assets/images/uploads/books/cosmos.jpg', '1a1a2e', 'Cosmos');
createBookSVG('assets/images/uploads/books/thinking-fast.jpg', '6c3483', 'Thinking Fast');

// ============================================================
// Generate Media Images
// ============================================================
echo "<h2>Generating Media Images...</h2>";
createSVGPlaceholder('assets/images/uploads/media/milky-way.jpg', '1a1a2e', 'Milky Way Galaxy', '🌌');
createSVGPlaceholder('assets/images/uploads/media/andromeda.jpg', '2c3e50', 'Andromeda Galaxy', '🌠');
createSVGPlaceholder('assets/images/uploads/media/brain-map.jpg', '6c3483', 'Brain Map', '🧠');

echo "<hr>";
echo "<h2 style='color:green;'>🎉 All images generated successfully!</h2>";
echo "<p>Now <strong>delete this file</strong> (generate-images-final.php) for security.</p>";
echo "<p><a href='" . SITE_URL . "lessons.php' target='_blank'>View Lessons Page</a></p>";
?>