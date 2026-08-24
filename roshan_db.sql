-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 24, 2026 at 06:22 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `roshan_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `pdf_link` varchar(255) DEFAULT NULL,
  `purchase_link` varchar(255) DEFAULT NULL,
  `difficulty` enum('beginner','intermediate','advanced') DEFAULT 'beginner',
  `is_featured` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `category_id`, `title`, `author`, `description`, `cover_image`, `pdf_link`, `purchase_link`, `difficulty`, `is_featured`, `created_at`) VALUES
(1, 1, 'In the Shade of the Quran', 'Sayyid Qutb', 'A profound commentary on the Quran that explores its timeless guidance for humanity.', NULL, NULL, 'https://www.amazon.com/Shade-Quran-Vol-Sayyid-Qutb/dp/0860377682', 'advanced', 0, '2026-08-22 21:58:43'),
(2, 2, 'A Brief History of Time', 'Stephen Hawking', 'A journey through time, space, and the universe - from the big bang to black holes.', NULL, NULL, 'https://www.amazon.com/Brief-History-Time-Stephen-Hawking/dp/0553380168', 'intermediate', 0, '2026-08-22 21:58:43'),
(3, 3, 'The Power of Habit', 'Charles Duhigg', 'Why we do what we do and how to change the habits that shape our lives.', NULL, NULL, 'https://www.amazon.com/Power-Habit-What-Life-Business/dp/081298160X', 'beginner', 0, '2026-08-22 21:58:43'),
(4, 4, 'Sophie\'s World', 'Jostein Gaarder', 'A novel about the history of philosophy that makes complex ideas accessible and engaging.', NULL, 'https://www.goodreads.com/book/show/10959.Sophie_s_World', 'https://www.amazon.com/Sophies-World-History-Philosophy-Classic/dp/0374530718', 'beginner', 0, '2026-08-22 21:58:43'),
(5, 4, 'Meditations', 'Marcus Aurelius', 'Stoic wisdom from one of the greatest Roman emperors - timeless guidance for living a good life.', NULL, NULL, 'https://www.amazon.com/Meditations-Marcus-Aurelius/dp/0812968255', 'intermediate', 0, '2026-08-22 21:58:43'),
(6, 5, 'The Pragmatic Programmer', 'Andrew Hunt', 'Practical wisdom for developers - timeless advice for writing better code and building better software.', NULL, 'https://pragprog.com/titles/tpp20/the-pragmatic-programmer-20th-anniversary-edition/', 'https://www.amazon.com/Pragmatic-Programmer-Anniversary-Journey-Mastery/dp/0135957052', 'intermediate', 1, '2026-08-22 21:58:43'),
(7, 5, 'Code: The Hidden Language', 'Charles Petzold', 'The story of how computers work - from the basics of binary to the architecture of modern computers.', NULL, NULL, 'https://www.amazon.com/Code-Language-Computer-Hardware-Software/dp/0735611319', 'beginner', 0, '2026-08-22 21:58:43');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `icon_class` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `color_hex` varchar(7) DEFAULT '#3498db',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `icon_class`, `description`, `color_hex`, `is_active`, `created_at`) VALUES
(1, 'Islamic Studies', 'islamic-studies', 'fa-mosque', 'Understanding Islam beyond memorization - exploring the deeper meanings of faith, ethics, and spirituality.', '#2ecc71', 1, '2026-08-19 16:43:17'),
(2, 'Astronomy', 'astronomy', 'fa-rocket', 'The universe and our place in it - exploring space, time, and the cosmos.', '#3498db', 1, '2026-08-19 16:43:17'),
(3, 'Psychology', 'psychology', 'fa-brain', 'Understanding the human mind and behavior - psychology, motivation, and mental well-being.', '#e74c3c', 1, '2026-08-19 16:43:17'),
(4, 'Philosophy', 'philosophy', 'fa-book-open', 'Critical thinking, logic, and wisdom - philosophical approaches to life and knowledge.', '#f39c12', 1, '2026-08-19 16:43:17'),
(5, 'Computer Science', 'computer-science', 'fa-laptop-code', 'Digital skills for the future - programming, web development, AI, and cybersecurity.', '#9b59b6', 1, '2026-08-19 16:43:17');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` enum('student','teacher','parent','other') DEFAULT 'student',
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lessons`
--

CREATE TABLE `lessons` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `content` longtext NOT NULL,
  `summary` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `video_embed_code` text DEFAULT NULL,
  `curiosity_question` varchar(255) DEFAULT NULL,
  `difficulty` enum('beginner','intermediate','advanced') DEFAULT 'beginner',
  `reading_time` int(11) DEFAULT 5,
  `is_published` tinyint(1) DEFAULT 0,
  `view_count` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lessons`
--

INSERT INTO `lessons` (`id`, `category_id`, `user_id`, `title`, `slug`, `subtitle`, `content`, `summary`, `image_path`, `video_url`, `video_embed_code`, `curiosity_question`, `difficulty`, `reading_time`, `is_published`, `view_count`, `created_at`, `updated_at`) VALUES
(7, 1, 5, 'Understanding vs. Memorization - The Islamic Perspective', 'understanding-vs-memorization-the-islamic-perspective', 'Why Islam emphasizes comprehension over rote learning', '<p>In Islam, knowledge is not merely about memorizing words but about understanding the deeper meanings. The Quran itself challenges us to reflect and ponder. <strong>Tadabbur</strong> means deep reflection, and it is a core Islamic principle.</p><p>When we truly understand something, we can apply it in our lives. This is the essence of Islamic learning - moving from memorization to comprehension.</p>', 'In this lesson, we explore why Islam emphasizes understanding over blind memorization, and how this applies to all areas of learning.', 'assets/images/uploads/lessons/islamic-studies.svg', 'https://youtu.be/DElHd01eInk?si=4l502-WoWE4ww-5_', NULL, 'Did you know that the word &quot;Quran&quot; means &quot;recitation&quot; - but the Prophet ﷺ also said to reflect deeply on its meanings?', 'intermediate', 8, 1, 250, '2026-08-22 21:47:51', '2026-08-23 08:50:34'),
(8, 2, 5, 'The Wonders of Our Solar System', 'wonders-solar-system', 'Exploring the planets, stars, and our cosmic neighborhood', '<p>Our solar system is a vast and beautiful place. From the scorching surface of Mercury to the icy plains of Pluto, each planet tells a unique story about the formation of our cosmic home.</p><p>Understanding the solar system helps us understand our place in the universe and appreciate the incredible creation we are part of.</p>', 'Take a journey through our solar system and discover the amazing planets that orbit our sun.', 'assets/images/uploads/lessons/astronomy.svg', 'https://www.youtube.com/embed/T5WRSZuhc2U', NULL, 'Did you know that Jupiter is so large that 1,300 Earths could fit inside it?', 'beginner', 10, 1, 180, '2026-08-22 21:47:51', '2026-08-23 08:50:34'),
(9, 3, 5, 'Understanding How Your Brain Works', 'understanding-brain-works', 'A beginner\'s guide to the human mind', '<p>The human brain is the most complex organ in the body. It contains approximately 86 billion neurons, each forming thousands of connections with other neurons.</p><p>Understanding how the brain works can help us learn more effectively, manage our emotions, and improve our overall well-being.</p>', 'Learn the basics of how your brain processes information, makes decisions, and controls your body.', 'assets/images/uploads/lessons/psychology.svg', 'https://www.youtube.com/embed/ZcR4fLox-vI', NULL, 'Did you know that your brain uses about 20% of your body\'s total energy?', 'intermediate', 12, 1, 322, '2026-08-22 21:47:51', '2026-08-23 08:50:34'),
(10, 4, 5, 'Critical Thinking - The Path to Wisdom', 'critical-thinking-wisdom', 'How to think better and make better decisions', '<p>Critical thinking is the ability to analyze facts, generate ideas, and make logical decisions. It is the cornerstone of wisdom and understanding.</p><p>In a world full of information, critical thinking helps us distinguish truth from falsehood and make better choices in life.</p>', 'Learn the art of critical thinking and how to apply it to everyday situations.', 'assets/images/uploads/lessons/philosophy.svg', 'https://www.youtube.com/embed/6OLPL5p0fMg', NULL, 'Did you know that Socrates believed the unexamined life is not worth living?', 'intermediate', 7, 1, 195, '2026-08-22 21:47:51', '2026-08-23 08:50:34'),
(11, 5, 5, 'Introduction to Web Development', 'introduction-web-development', 'Building the future, one website at a time', '<p>Web development is one of the most valuable skills in the modern world. It combines creativity with logic, and design with functionality.</p><p>In this lesson, we\'ll explore the basic building blocks of the web and how you can start building your own websites.</p>', 'Learn the fundamentals of web development including HTML, CSS, and JavaScript.', 'assets/images/uploads/lessons/computer-science.svg', 'https://www.youtube.com/embed/HD13eq_Pmp8', NULL, 'Did you know that the first website ever created is still online today?', 'beginner', 15, 1, 420, '2026-08-22 21:47:51', '2026-08-23 08:50:34'),
(17, 1, 5, 'Understanding vs. Memorization - The Islamic Perspective', 'understanding-vs-memorization-islam-v2', 'Why Islam emphasizes comprehension over rote learning', 'In Islam, knowledge is not merely about memorizing words but about understanding the deeper meanings. The Quran itself challenges us to reflect and ponder. <strong>Tadabbur</strong> means deep reflection, and it is a core Islamic principle.\n\nWhen we truly understand something, we can apply it in our lives. This is the essence of Islamic learning - moving from memorization to comprehension.', 'In this lesson, we explore why Islam emphasizes understanding over blind memorization, and how this applies to all areas of learning.', 'assets/images/uploads/lessons/islamic-studies.svg', 'https://www.youtube.com/embed/videoseries?si=4nP4SJNFaAOk9-fw&amp;list=PLTp9Bu0cTGUyqRMW4HwO3IzmPZJh_KvE7', NULL, 'Did you know that the word \"Quran\" means \"recitation\" - but the Prophet ﷺ also said to reflect deeply on its meanings?', 'intermediate', 8, 1, 252, '2026-08-22 21:55:24', '2026-08-23 08:50:34'),
(18, 2, 5, 'The Wonders of Our Solar System', 'wonders-solar-system-v2', 'Exploring the planets, stars, and our cosmic neighborhood', 'Our solar system is a vast and beautiful place. From the scorching surface of Mercury to the icy plains of Pluto, each planet tells a unique story about the formation of our cosmic home.\n\nUnderstanding the solar system helps us understand our place in the universe and appreciate the incredible creation we are part of.', 'Take a journey through our solar system and discover the amazing planets that orbit our sun.', 'assets/images/uploads/lessons/astronomy.svg', 'https://www.youtube.com/embed/T5WRSZuhc2U', NULL, 'Did you know that Jupiter is so large that 1,300 Earths could fit inside it?', 'beginner', 10, 1, 181, '2026-08-22 21:55:24', '2026-08-23 08:50:34'),
(19, 3, 5, 'Understanding How Your Brain Works', 'understanding-brain-works-v2', 'A beginner\'s guide to the human mind', 'The human brain is the most complex organ in the body. It contains approximately 86 billion neurons, each forming thousands of connections with other neurons.\n\nUnderstanding how the brain works can help us learn more effectively, manage our emotions, and improve our overall well-being.', 'Learn the basics of how your brain processes information, makes decisions, and controls your body.', 'assets/images/uploads/lessons/psychology.svg', 'https://www.youtube.com/embed/ZcR4fLox-vI', NULL, 'Did you know that your brain uses about 20% of your body\'s total energy?', 'intermediate', 12, 1, 323, '2026-08-22 21:55:24', '2026-08-23 08:50:34'),
(20, 4, 5, 'Critical Thinking - The Path to Wisdom', 'critical-thinking-wisdom-v2', 'How to think better and make better decisions', 'Critical thinking is the ability to analyze facts, generate ideas, and make logical decisions. It is the cornerstone of wisdom and understanding.\n\nIn a world full of information, critical thinking helps us distinguish truth from falsehood and make better choices in life.', 'Learn the art of critical thinking and how to apply it to everyday situations.', 'assets/images/uploads/lessons/philosophy.svg', 'https://www.youtube.com/embed/6OLPL5p0fMg', NULL, 'Did you know that Socrates believed the unexamined life is not worth living?', 'intermediate', 7, 1, 195, '2026-08-22 21:55:24', '2026-08-23 08:50:34'),
(21, 5, 5, 'Introduction to Web Development', 'introduction-web-development-v2', 'Building the future, one website at a time', 'Web development is one of the most valuable skills in the modern world. It combines creativity with logic, and design with functionality.\n\nIn this lesson, we\'ll explore the basic building blocks of the web and how you can start building your own websites.', 'Learn the fundamentals of web development including HTML, CSS, and JavaScript.', 'assets/images/uploads/lessons/computer-science.svg', 'https://www.youtube.com/embed/HD13eq_Pmp8', NULL, 'Did you know that the first website ever created is still online today?', 'beginner', 15, 1, 422, '2026-08-22 21:55:24', '2026-08-23 08:50:34');

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` int(11) NOT NULL,
  `lesson_id` int(11) DEFAULT NULL,
  `media_type` enum('image','infographic','video_thumbnail','student_work') DEFAULT 'image',
  `file_path` varchar(255) NOT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `credit` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quiz_questions`
--

CREATE TABLE `quiz_questions` (
  `id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `option_a` varchar(255) DEFAULT NULL,
  `option_b` varchar(255) DEFAULT NULL,
  `option_c` varchar(255) DEFAULT NULL,
  `option_d` varchar(255) DEFAULT NULL,
  `correct_answer` char(1) DEFAULT NULL,
  `explanation` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `role` enum('admin','teacher','student') DEFAULT 'student',
  `bio` text DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `is_approved` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `full_name`, `role`, `bio`, `profile_image`, `is_approved`, `created_at`) VALUES
(2, 'test1', 'test1@test.com', '$2y$10$DNLHGSVJeavGIG8LY2z1r.UYpbPoR5Pt9jJeQHkAvy2M.yw8SsE6W', 'test1', 'student', NULL, NULL, 1, '2026-08-19 17:51:04'),
(3, 'test2', 'test2@test.com', '$2y$10$ouAjS6hNfuPOi2mxh4XqgegvqKjcFSPd4Vo9EN20aFlugIrnZCRk.', 'test2new', 'student', 'asdasedqdasdasd', NULL, 1, '2026-08-19 18:05:11'),
(5, 'admin', 'admin@roshan.com', '$2y$10$nPOK2pWFNZlEUg.lXEfKjeiemwzy5enlnhFOe5GjtNdSC1XBHydWy', 'Roshan Admin', 'admin', NULL, NULL, 1, '2026-08-19 18:23:06'),
(6, 'test3', 'test3@test.com', '$2y$10$v302yCuhJueX.btDW1EhGO/iEd.MjuTEKFdGxt1fO9HjRsBhKhk6q', 'test3', 'student', NULL, NULL, 1, '2026-08-20 15:27:27'),
(7, 'test4', 'test4@test.com', '$2y$10$LB3/4CX5v3r7O.hUdm8Jhe7066RnxMyLHIpESjcP4dLOBbXSU0dlG', 'test4', 'student', NULL, NULL, 1, '2026-08-20 15:33:08'),
(8, 'test5', 'test5@test.com', '$2y$10$sXUKVXOGQlJWbLxxwMJYkuu4/oqA16Mmv2b7jWv2o/h406BTdDyO6', 'test5', 'student', NULL, NULL, 1, '2026-08-22 20:31:16'),
(9, 'teacher_ali', 'ali@roshan.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Ali Hassan', 'teacher', 'Islamic Studies scholar with 15 years of teaching experience. Passionate about making Islamic knowledge accessible to all.', NULL, 1, '2026-08-22 22:01:02'),
(10, 'teacher_fatima', 'fatima@roshan.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Fatima Ahmed', 'teacher', 'Astronomy and Physics educator. Dedicated to inspiring curiosity about the universe.', NULL, 1, '2026-08-22 22:01:02');

-- --------------------------------------------------------

--
-- Table structure for table `user_favorites`
--

CREATE TABLE `user_favorites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `lesson_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_progress`
--

CREATE TABLE `user_progress` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `lesson_id` int(11) DEFAULT NULL,
  `is_completed` tinyint(1) DEFAULT 0,
  `quiz_score` int(11) DEFAULT 0,
  `time_spent` int(11) DEFAULT 0,
  `last_accessed` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lesson_id` (`lesson_id`);

--
-- Indexes for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lesson_id` (`lesson_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_favorites`
--
ALTER TABLE `user_favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_favorite` (`user_id`,`lesson_id`),
  ADD KEY `lesson_id` (`lesson_id`);

--
-- Indexes for table `user_progress`
--
ALTER TABLE `user_progress`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_progress` (`user_id`,`lesson_id`),
  ADD KEY `lesson_id` (`lesson_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lessons`
--
ALTER TABLE `lessons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user_favorites`
--
ALTER TABLE `user_favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_progress`
--
ALTER TABLE `user_progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lessons`
--
ALTER TABLE `lessons`
  ADD CONSTRAINT `lessons_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lessons_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `media`
--
ALTER TABLE `media`
  ADD CONSTRAINT `media_ibfk_1` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD CONSTRAINT `quiz_questions_ibfk_1` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_favorites`
--
ALTER TABLE `user_favorites`
  ADD CONSTRAINT `user_favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_favorites_ibfk_2` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_progress`
--
ALTER TABLE `user_progress`
  ADD CONSTRAINT `user_progress_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_progress_ibfk_2` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
