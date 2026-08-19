<?php
// config/constants.php
// Global constants for Roshan Platform

// User roles
define('ROLE_ADMIN', 'admin');
define('ROLE_TEACHER', 'teacher');
define('ROLE_STUDENT', 'student');

// Lesson difficulty levels
define('DIFFICULTY_BEGINNER', 'beginner');
define('DIFFICULTY_INTERMEDIATE', 'intermediate');
define('DIFFICULTY_ADVANCED', 'advanced');

// Media types
define('MEDIA_IMAGE', 'image');
define('MEDIA_INFOGRAPHIC', 'infographic');
define('MEDIA_VIDEO', 'video_thumbnail');
define('MEDIA_STUDENT_WORK', 'student_work');

// File upload limits (in bytes)
define('MAX_FILE_SIZE', 5242880); // 5MB

// Allowed file extensions
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
?>