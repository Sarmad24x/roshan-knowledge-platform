<?php
// public/mark-complete.php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Please login first.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lesson_id = isset($_POST['lesson_id']) ? (int)$_POST['lesson_id'] : 0;
    $user_id = $_SESSION['user_id'];
    
    if (!$lesson_id) {
        echo json_encode(['success' => false, 'message' => 'Invalid lesson ID.']);
        exit();
    }
    
    try {
        // Check if progress exists
        $stmt = $pdo->prepare("SELECT * FROM user_progress WHERE user_id = ? AND lesson_id = ?");
        $stmt->execute([$user_id, $lesson_id]);
        $progress = $stmt->fetch();
        
        if ($progress) {
            // Update
            $stmt = $pdo->prepare("UPDATE user_progress SET is_completed = 1, last_accessed = NOW() WHERE user_id = ? AND lesson_id = ?");
            $stmt->execute([$user_id, $lesson_id]);
        } else {
            // Insert
            $stmt = $pdo->prepare("INSERT INTO user_progress (user_id, lesson_id, is_completed, last_accessed) VALUES (?, ?, 1, NOW())");
            $stmt->execute([$user_id, $lesson_id]);
        }
        
        echo json_encode(['success' => true, 'message' => 'Progress saved!']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>