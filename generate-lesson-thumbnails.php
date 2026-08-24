<?php
// generate-lesson-thumbnails.php
// Run this once to generate all lesson thumbnails

echo "<h1>📸 Generating Lesson Thumbnails</h1>";

// Create folders if they don't exist
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

// Function to create gradient placeholder with text
function createGradientPlaceholder($filename, $color1, $color2, $text, $width = 800, $height = 400) {
    $image = imagecreatetruecolor($width, $height);
    
    // Create gradient
    $c1 = imagecolorallocate($image, hexdec(substr($color1, 0, 2)), hexdec(substr($color1, 2, 2)), hexdec(substr($color1, 4, 2)));
    $c2 = imagecolorallocate($image, hexdec(substr($color2, 0, 2)), hexdec(substr($color2, 2, 2)), hexdec(substr($color2, 4, 2)));
    
    imagefill($image, 0, 0, $c1);
    
    // Add a simple icon/symbol
    $white = imagecolorallocate($image, 255, 255, 255);
    $gold = imagecolorallocate($image, 255, 215, 0);
    
    // Draw a circle background for icon
    $cx = $width / 2;
    $cy = $height / 2 - 30;
    imagefilledellipse($image, $cx, $cy, 120, 120, imagecolorallocate($image, 255, 255, 255, 50));
    
    // Add text
    $fontSize = 5;
    $textWidth = imagefontwidth($fontSize) * strlen($text);
    $x = ($width - $textWidth) / 2;
    $y = $height / 2 + 40;
    
    // Add shadow
    imagestring($image, $fontSize, $x + 1, $y + 1, $text, imagecolorallocate($image, 0, 0, 0, 50));
    imagestring($image, $fontSize, $x, $y, $text, $white);
    
    // Add category label
    $label = "Roshan Lesson";
    $labelWidth = imagefontwidth(3) * strlen($label);
    imagestring($image, 3, ($width - $labelWidth) / 2, $height - 30, $label, $gold);
    
    imagejpeg($image, $filename, 85);
    imagedestroy($image);
    
    echo "✅ Created: $filename<br>";
}

// Generate Lesson Thumbnails
createGradientPlaceholder(
    'assets/images/uploads/lessons/islamic-studies.jpg',
    '1a5276', '2ecc71', 'Islamic Studies'
);

createGradientPlaceholder(
    'assets/images/uploads/lessons/astronomy.jpg',
    '1a1a2e', '3498db', 'Astronomy'
);

createGradientPlaceholder(
    'assets/images/uploads/lessons/psychology.jpg',
    '6c3483', 'e74c3c', 'Psychology'
);

createGradientPlaceholder(
    'assets/images/uploads/lessons/philosophy.jpg',
    '935116', 'f39c12', 'Philosophy'
);

createGradientPlaceholder(
    'assets/images/uploads/lessons/computer-science.jpg',
    '1a5276', '9b59b6', 'Computer Science'
);

// Generate Book Covers
createGradientPlaceholder(
    'assets/images/uploads/books/study-quran.jpg',
    '1a5276', '2ecc71', 'The Study Quran',
    300, 400
);

createGradientPlaceholder(
    'assets/images/uploads/books/cosmos.jpg',
    '1a1a2e', '3498db', 'Cosmos',
    300, 400
);

createGradientPlaceholder(
    'assets/images/uploads/books/thinking-fast.jpg',
    '6c3483', 'e74c3c', 'Thinking Fast',
    300, 400
);

echo "<h2 style='color:green;'>🎉 All images generated successfully!</h2>";
echo "<p>Now <strong>delete this file</strong> (generate-lesson-thumbnails.php) for security.</p>";
?>