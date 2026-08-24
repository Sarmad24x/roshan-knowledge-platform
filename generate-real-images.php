<?php
// generate-real-images.php
// Creates actual JPEG images using base64 encoded simple images

echo "<h1>📸 Creating Real JPEG Images</h1>";

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

// Function to create a simple JPEG using base64
function createImage($filename, $color, $text, $emoji = '📚') {
    // Create a simple HTML page that generates an image
    // We'll use a data URI approach
    
    // Color mapping
    $colors = [
        '1a5276' => ['r' => 26, 'g' => 82, 'b' => 118],
        '1a1a2e' => ['r' => 26, 'g' => 26, 'b' => 46],
        '6c3483' => ['r' => 108, 'g' => 52, 'b' => 131],
        '935116' => ['r' => 147, 'g' => 81, 'b' => 22],
        '2c3e50' => ['r' => 44, 'g' => 62, 'b' => 80],
    ];
    
    $rgb = $colors[$color] ?? ['r' => 52, 'g' => 73, 'b' => 94];
    
    // Create a simple 1x1 pixel image first (will be replaced)
    $img = imagecreatetruecolor(800, 400);
    
    // Colors
    $bg = imagecolorallocate($img, $rgb['r'], $rgb['g'], $rgb['b']);
    $white = imagecolorallocate($img, 255, 255, 255);
    $gold = imagecolorallocate($img, 255, 215, 0);
    $light = imagecolorallocate($img, 200, 200, 200);
    
    // Fill background
    imagefill($img, 0, 0, $bg);
    
    // Add a gradient effect (simple)
    for ($i = 0; $i < 400; $i++) {
        $alpha = 50 - ($i / 8);
        if ($alpha < 0) $alpha = 0;
        $color = imagecolorallocate($img, 
            $rgb['r'] + $i/3, 
            $rgb['g'] + $i/4, 
            $rgb['b'] + $i/2
        );
        imageline($img, 0, $i, 800, $i, $color);
    }
    
    // Draw a circle for icon background
    $circleColor = imagecolorallocate($img, 255, 255, 255, 20);
    imagefilledellipse($img, 400, 160, 120, 120, imagecolorallocate($img, 255, 255, 255, 10));
    imageellipse($img, 400, 160, 120, 120, $gold);
    
    // Add emoji text (using simple text as emoji might not render)
    $font = 5;
    $textWidth = imagefontwidth($font) * strlen($text);
    $x = (800 - $textWidth) / 2;
    
    // Add title
    imagestring($img, $font, $x, 240, $text, $white);
    
    // Add subtitle
    $subtext = "Roshan Knowledge Platform";
    $subWidth = imagefontwidth(3) * strlen($subtext);
    imagestring($img, 3, (800 - $subWidth) / 2, 290, $subtext, $light);
    
    // Add decorative line
    for ($i = 0; $i < 80; $i++) {
        imagesetpixel($img, 360 + $i, 330, $gold);
    }
    
    // Save as JPEG
    imagejpeg($img, $filename, 85);
    imagedestroy($img);
    
    echo "✅ Created: $filename<br>";
}

// Function for book covers
function createBookImage($filename, $color, $text) {
    $colors = [
        '1a5276' => ['r' => 26, 'g' => 82, 'b' => 118],
        '1a1a2e' => ['r' => 26, 'g' => 26, 'b' => 46],
        '6c3483' => ['r' => 108, 'g' => 52, 'b' => 131],
    ];
    
    $rgb = $colors[$color] ?? ['r' => 52, 'g' => 73, 'b' => 94];
    
    $img = imagecreatetruecolor(300, 400);
    
    // Colors
    $bg = imagecolorallocate($img, $rgb['r'], $rgb['g'], $rgb['b']);
    $white = imagecolorallocate($img, 255, 255, 255);
    $gold = imagecolorallocate($img, 255, 215, 0);
    $light = imagecolorallocate($img, 200, 200, 200);
    
    imagefill($img, 0, 0, $bg);
    
    // Border
    imagerectangle($img, 10, 10, 289, 389, $gold);
    
    // Book icon background
    imagefilledellipse($img, 150, 150, 80, 80, imagecolorallocate($img, 255, 255, 255, 10));
    imageellipse($img, 150, 150, 80, 80, $gold);
    
    // Title
    $font = 4;
    $textWidth = imagefontwidth($font) * strlen($text);
    if ($textWidth > 260) {
        $text = substr($text, 0, 15);
        $textWidth = imagefontwidth($font) * strlen($text);
    }
    $x = (300 - $textWidth) / 2;
    imagestring($img, $font, $x, 220, $text, $white);
    
    // Subtitle
    $subtext = "Roshan Book";
    $subWidth = imagefontwidth(2) * strlen($subtext);
    imagestring($img, 2, (300 - $subWidth) / 2, 260, $subtext, $light);
    
    // Decorative line
    for ($i = 0; $i < 60; $i++) {
        imagesetpixel($img, 120 + $i, 360, $gold);
    }
    
    imagejpeg($img, $filename, 85);
    imagedestroy($img);
    
    echo "✅ Created: $filename<br>";
}

echo "<h2>Creating Lesson Images...</h2>";
createImage('assets/images/uploads/lessons/islamic-studies.jpg', '1a5276', 'Islamic Studies');
createImage('assets/images/uploads/lessons/astronomy.jpg', '1a1a2e', 'Astronomy');
createImage('assets/images/uploads/lessons/psychology.jpg', '6c3483', 'Psychology');
createImage('assets/images/uploads/lessons/philosophy.jpg', '935116', 'Philosophy');
createImage('assets/images/uploads/lessons/computer-science.jpg', '1a5276', 'Computer Science');

echo "<h2>Creating Book Covers...</h2>";
createBookImage('assets/images/uploads/books/study-quran.jpg', '1a5276', 'The Study Quran');
createBookImage('assets/images/uploads/books/cosmos.jpg', '1a1a2e', 'Cosmos');
createBookImage('assets/images/uploads/books/thinking-fast.jpg', '6c3483', 'Thinking Fast');

echo "<hr>";
echo "<h2 style='color:green;'>🎉 All images created successfully!</h2>";
echo "<p>Now <strong>delete this file</strong> (generate-real-images.php) for security.</p>";
?>