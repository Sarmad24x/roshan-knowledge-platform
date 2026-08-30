-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 30, 2026 at 08:29 AM
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
(7, 1, 5, 'Understanding vs. Memorization - The Islamic Perspective', 'understanding-vs-memorization-the-islamic-perspective', 'Why Islam emphasizes comprehension over rote learning', '<h2>📖 The Islamic Approach to Knowledge</h2>\r\n\r\n<p>In Islam, knowledge is not merely about memorizing words but about understanding the deeper meanings. The Quran itself challenges us to reflect and ponder. <strong>Tadabbur</strong> means deep reflection, and it is a core Islamic principle.</p>\r\n\r\n<h3>🕌 What is Tadabbur?</h3>\r\n<p>Tadabbur is the act of reflecting deeply on the meanings of the Quran. Allah says in the Quran:</p>\r\n<blockquote style=\"background: #f8f9fa; padding: 15px; border-left: 4px solid #2ecc71; border-radius: 8px;\">\r\n    \"Do they not reflect upon the Quran? Or are there locks upon their hearts?\" (Surah Muhammad, 47:24)\r\n</blockquote>\r\n<p>This verse shows that understanding is a requirement, not an option. Allah criticizes those who do not reflect on His words.</p>\r\n\r\n<h3>🧠 Understanding Over Memorization</h3>\r\n<p>The Prophet Muhammad ﷺ said:</p>\r\n<blockquote style=\"background: #f8f9fa; padding: 15px; border-left: 4px solid #2ecc71; border-radius: 8px;\">\r\n    \"The best of you are those who learn the Quran and teach it.\" (Bukhari)\r\n</blockquote>\r\n<p>Notice that the Prophet said \"learn\" and \"teach\" - not just \"memorize.\" Learning implies understanding, application, and transmission of knowledge.</p>\r\n\r\n<h3>📝 Why Understanding Matters</h3>\r\n<ul>\r\n    <li><strong>Application:</strong> You can only apply what you understand. Memorization without understanding leads to blind following.</li>\r\n    <li><strong>Connection:</strong> Understanding creates a personal connection with Allah and His message.</li>\r\n    <li><strong>Transformation:</strong> True understanding changes behavior and character, not just recitation.</li>\r\n    <li><strong>Teaching:</strong> You can only teach what you truly understand. The best way to learn is to teach.</li>\r\n</ul>\r\n\r\n<h3>💡 How to Develop Understanding</h3>\r\n<ol>\r\n    <li><strong>Read with Tafsir:</strong> Don\'t just read the Arabic. Read translations and commentaries.</li>\r\n    <li><strong>Ask \"Why?\":</strong> For every verse, ask: Why was this revealed? What is the context? What is the message for me?</li>\r\n    <li><strong>Connect to Life:</strong> How does this verse apply to my daily life? How can I act on it?</li>\r\n    <li><strong>Discuss with Others:</strong> Knowledge grows when shared. Discuss with teachers, friends, and family.</li>\r\n    <li><strong>Teach Others:</strong> The best way to solidify understanding is to explain it to someone else.</li>\r\n</ol>\r\n\r\n<h3>🌟 The Balance</h3>\r\n<p>Memorization is important in Islam - there is great reward in memorizing the Quran. But memorization without understanding is like having a beautiful book you cannot read. The ideal is to <strong>memorize AND understand</strong>.</p>\r\n\r\n<h3>📚 Recommended Books</h3>\r\n<ul>\r\n    <li>\"The Study Quran\" - Seyyed Hossein Nasr</li>\r\n    <li>\"In the Shade of the Quran\" - Sayyid Qutb</li>\r\n    <li>\"The Message of the Quran\" - Muhammad Asad</li>\r\n</ul>\r\n\r\n<h3>🎯 Key Takeaway</h3>\r\n<p>Islam is a religion of understanding, not just recitation. The goal is to know Allah, love Him, and live according to His guidance - and that requires deep, genuine understanding.</p>', 'In this comprehensive lesson, we explore why Islam emphasizes understanding over blind memorization. Learn what Tadabbur means, how to develop deep understanding of the Quran, and why true knowledge transforms both heart and action.', 'assets/images/uploads/lessons/islamic-studies.jpg', 'https://youtu.be/DElHd01eInk?si=4l502-WoWE4ww-5_', NULL, 'Did you know that the word &quot;Quran&quot; means &quot;recitation&quot; - but the Prophet ﷺ also said to reflect deeply on its meanings?', 'intermediate', 8, 1, 251, '2026-08-22 21:47:51', '2026-08-24 11:31:23'),
(8, 2, 5, 'The Wonders of Our Solar System', 'wonders-solar-system', 'Exploring the planets, stars, and our cosmic neighborhood', '<h2>🌌 Our Cosmic Neighborhood</h2>\r\n\r\n<p>Our solar system is a vast and beautiful place. From the scorching surface of Mercury to the icy plains of Pluto, each planet tells a unique story about the formation of our cosmic home.</p>\r\n\r\n<h3>☀️ The Sun - Our Star</h3>\r\n<p>The Sun is the center of our solar system. It is a massive ball of hot gas that provides light and heat to all the planets. Without the Sun, life on Earth would not exist.</p>\r\n<ul>\r\n    <li><strong>Diameter:</strong> 1.39 million km (109 times Earth)</li>\r\n    <li><strong>Mass:</strong> 330,000 times Earth</li>\r\n    <li><strong>Temperature:</strong> 5,500°C (surface), 15 million°C (core)</li>\r\n    <li><strong>Age:</strong> 4.6 billion years</li>\r\n</ul>\r\n\r\n<h3>🪐 The Inner Planets</h3>\r\n<h4>Mercury</h4>\r\n<p>The smallest planet and closest to the Sun. It has no atmosphere, so temperatures range from -180°C to 430°C. One day on Mercury is 59 Earth days.</p>\r\n\r\n<h4>Venus</h4>\r\n<p>Earth\'s \"sister planet\" because of similar size. But Venus is extremely hot (475°C) due to a runaway greenhouse effect. It rotates backward compared to other planets.</p>\r\n\r\n<h4>Earth</h4>\r\n<p>Our home - the only known planet with life. It has liquid water, a protective atmosphere, and a magnetic field that shields us from solar radiation.</p>\r\n\r\n<h4>Mars</h4>\r\n<p>The \"Red Planet\" - named for its reddish appearance from iron oxide. It has the tallest mountain in the solar system (Olympus Mons, 21.9 km) and the largest canyon (Valles Marineris, 4,000 km long).</p>\r\n\r\n<h3>🌍 The Outer Planets</h3>\r\n<h4>Jupiter</h4>\r\n<p>The largest planet - 1,300 Earths could fit inside it. It\'s a gas giant with no solid surface. Its Great Red Spot is a storm bigger than Earth that has raged for hundreds of years.</p>\r\n\r\n<h4>Saturn</h4>\r\n<p>Famous for its beautiful rings made of ice and rock. Saturn is the least dense planet - it would float in water! It has 82 known moons, including Titan (larger than Mercury).</p>\r\n\r\n<h4>Uranus</h4>\r\n<p>The coldest planet (-224°C) that spins on its side. It has 27 known moons, all named after Shakespearean characters.</p>\r\n\r\n<h4>Neptune</h4>\r\n<p>The windiest planet - winds reach 2,100 km/h! It was the first planet discovered through mathematics before being seen.</p>\r\n\r\n<h3>🔭 Beyond the Planets</h3>\r\n<ul>\r\n    <li><strong>Asteroid Belt:</strong> Between Mars and Jupiter - millions of rocky objects</li>\r\n    <li><strong>Kuiper Belt:</strong> Beyond Neptune - home to Pluto and other dwarf planets</li>\r\n    <li><strong>Oort Cloud:</strong> A massive cloud of icy objects surrounding the solar system</li>\r\n</ul>\r\n\r\n<h3>🚀 Space Exploration</h3>\r\n<ul>\r\n    <li><strong>Moon Landing:</strong> Apollo 11 (1969) - first humans on the Moon</li>\r\n    <li><strong>Mars Rovers:</strong> Curiosity, Perseverance - exploring the Red Planet</li>\r\n    <li><strong>Voyager 1 & 2:</strong> The farthest human-made objects, now in interstellar space</li>\r\n    <li><strong>James Webb Telescope:</strong> New images of the early universe</li>\r\n</ul>\r\n\r\n<h3>💡 Key Takeaways</h3>\r\n<ul>\r\n    <li>Our solar system is vast and diverse - each planet is unique</li>\r\n    <li>Understanding the solar system helps us understand our place in the universe</li>\r\n    <li>Space exploration shows human curiosity and the desire to know more</li>\r\n    <li>Every discovery leads to more questions - the universe is full of wonder</li>\r\n</ul>\r\n\r\n<h3>📚 Recommended Books</h3>\r\n<ul>\r\n    <li>\"Cosmos\" - Carl Sagan</li>\r\n    <li>\"A Brief History of Time\" - Stephen Hawking</li>\r\n    <li>\"Astrophysics for People in a Hurry\" - Neil deGrasse Tyson</li>\r\n</ul>', 'Take a detailed journey through our solar system! Learn about the Sun, all 8 planets, dwarf planets, space exploration, and the incredible facts that make our cosmic neighborhood so fascinating.', 'assets/images/uploads/lessons/astronomy.jpg', 'https://www.youtube.com/embed/T5WRSZuhc2U', NULL, 'Did you know that Jupiter is so large that 1,300 Earths could fit inside it?', 'beginner', 10, 1, 181, '2026-08-22 21:47:51', '2026-08-24 11:31:27'),
(9, 3, 5, 'Understanding How Your Brain Works', 'understanding-brain-works', 'A beginner\'s guide to the human mind', '<h2>🧠 The Most Complex Organ</h2>\r\n\r\n<p>The human brain is the most complex organ in the body. It contains approximately 86 billion neurons, each forming thousands of connections with other neurons. Understanding how the brain works can help us learn more effectively, manage our emotions, and improve our overall well-being.</p>\r\n\r\n<h3>🔬 Brain Structure</h3>\r\n<h4>Cerebrum (The Big Brain)</h4>\r\n<p>The largest part of the brain, responsible for conscious thought, language, memory, and sensory processing. It is divided into two hemispheres:</p>\r\n<ul>\r\n    <li><strong>Left Hemisphere:</strong> Logic, language, analytical thinking</li>\r\n    <li><strong>Right Hemisphere:</strong> Creativity, intuition, spatial awareness</li>\r\n</ul>\r\n\r\n<h4>Cerebellum (The Little Brain)</h4>\r\n<p>Responsible for coordination, balance, and fine motor movements. It helps you walk, type, and even speak clearly.</p>\r\n\r\n<h4>Brainstem</h4>\r\n<p>Controls automatic functions like breathing, heartbeat, and sleep cycles. It connects the brain to the spinal cord.</p>\r\n\r\n<h3>⚡ How the Brain Works</h3>\r\n<p>Neurons are the building blocks of the brain. They communicate through electrical and chemical signals:</p>\r\n<ol>\r\n    <li><strong>Dendrites</strong> receive signals from other neurons</li>\r\n    <li><strong>Cell body</strong> processes the signal</li>\r\n    <li><strong>Axon</strong> transmits the signal to the next neuron</li>\r\n    <li><strong>Synapse</strong> is the gap between neurons - chemicals (neurotransmitters) cross this gap</li>\r\n</ol>\r\n\r\n<h3>🧪 Neurotransmitters - The Brain\'s Messengers</h3>\r\n<ul>\r\n    <li><strong>Dopamine:</strong> Motivation, reward, pleasure</li>\r\n    <li><strong>Serotonin:</strong> Mood, happiness, sleep</li>\r\n    <li><strong>Adrenaline:</strong> Fight-or-flight response</li>\r\n    <li><strong>Endorphins:</strong> Pain relief, happiness (\"runner\'s high\")</li>\r\n    <li><strong>Oxytocin:</strong> Love, bonding, trust</li>\r\n</ul>\r\n\r\n<h3>💡 Learning and Memory</h3>\r\n<p>Learning is the process of forming new connections between neurons. Memory is the ability to store and retrieve information.</p>\r\n\r\n<h4>Types of Memory</h4>\r\n<ul>\r\n    <li><strong>Short-term memory:</strong> Holds information for 15-30 seconds (like remembering a phone number)</li>\r\n    <li><strong>Long-term memory:</strong> Stores information for days, months, or years</li>\r\n    <li><strong>Procedural memory:</strong> How to do things (riding a bike, typing)</li>\r\n    <li><strong>Declarative memory:</strong> Facts and events</li>\r\n</ul>\r\n\r\n<h4>How to Improve Learning</h4>\r\n<ol>\r\n    <li><strong>Spaced repetition:</strong> Review material at increasing intervals</li>\r\n    <li><strong>Active recall:</strong> Test yourself instead of re-reading</li>\r\n    <li><strong>Sleep:</strong> Sleep consolidates memories</li>\r\n    <li><strong>Exercise:</strong> Increases blood flow to the brain</li>\r\n    <li><strong>Teach others:</strong> Explaining helps you understand better</li>\r\n</ol>\r\n\r\n<h3>🧘 Mental Health and Well-being</h3>\r\n<ul>\r\n    <li><strong>Stress:</strong> Short-term stress can be helpful, but chronic stress damages the brain</li>\r\n    <li><strong>Mindfulness:</strong> Reduces stress and improves focus</li>\r\n    <li><strong>Exercise:</strong> Boosts mood and brain function</li>\r\n    <li><strong>Social connection:</strong> Essential for mental health</li>\r\n</ul>\r\n\r\n<h3>📚 Recommended Books</h3>\r\n<ul>\r\n    <li>\"Thinking, Fast and Slow\" - Daniel Kahneman</li>\r\n    <li>\"The Power of Habit\" - Charles Duhigg</li>\r\n    <li>\"Mindset\" - Carol Dweck</li>\r\n</ul>', 'Explore the fascinating world of the human brain! Learn about brain structure, how neurons communicate, neurotransmitters, memory formation, and practical tips for improving learning and mental well-being.', 'assets/images/uploads/lessons/psychology.jpg', 'https://www.youtube.com/embed/ZcR4fLox-vI', NULL, 'Did you know that your brain uses about 20% of your body\'s total energy?', 'intermediate', 12, 1, 322, '2026-08-22 21:47:51', '2026-08-24 11:30:27'),
(10, 4, 5, 'Critical Thinking - The Path to Wisdom', 'critical-thinking-wisdom', 'How to think better and make better decisions', '<h2>💭 The Art of Thinking</h2>\r\n\r\n<p>Critical thinking is the ability to analyze facts, generate ideas, and make logical decisions. It is the cornerstone of wisdom and understanding. In a world full of information, critical thinking helps us distinguish truth from falsehood and make better choices in life.</p>\r\n\r\n<h3>🧩 The Elements of Critical Thinking</h3>\r\n\r\n<h4>1. Clarity</h4>\r\n<p>Can you explain the idea clearly? If not, you don\'t understand it well enough. Ask: \"What exactly do you mean?\"</p>\r\n\r\n<h4>2. Accuracy</h4>\r\n<p>Is the information correct? Always verify facts from reliable sources. Ask: \"Is this really true?\"</p>\r\n\r\n<h4>3. Relevance</h4>\r\n<p>Does this information apply to the question? Avoid irrelevant distractions. Ask: \"How does this connect to the issue?\"</p>\r\n\r\n<h4>4. Depth</h4>\r\n<p>Are you looking at the surface or the complexity underneath? Ask: \"What are the deeper issues?\"</p>\r\n\r\n<h4>5. Logic</h4>\r\n<p>Does the conclusion follow from the evidence? Ask: \"Does this make sense?\"</p>\r\n\r\n<h3>🚫 Common Logical Fallacies</h3>\r\n\r\n<ul>\r\n    <li><strong>Ad Hominem:</strong> Attacking the person instead of the argument</li>\r\n    <li><strong>Straw Man:</strong> Misrepresenting an argument to make it easier to attack</li>\r\n    <li><strong>Appeal to Authority:</strong> \"It\'s true because X says so\"</li>\r\n    <li><strong>False Dilemma:</strong> \"Either A or B\" when there are more options</li>\r\n    <li><strong>Slippery Slope:</strong> \"If A happens, then B will happen\" without evidence</li>\r\n    <li><strong>Confirmation Bias:</strong> Seeking evidence that confirms your beliefs</li>\r\n</ul>\r\n\r\n<h3>🔍 Questions for Critical Thinking</h3>\r\n\r\n<ul>\r\n    <li>What is the source of this information?</li>\r\n    <li>What evidence supports this claim?</li>\r\n    <li>Are there alternative explanations?</li>\r\n    <li>What are the implications of this idea?</li>\r\n    <li>What would change if this were wrong?</li>\r\n    <li>What do I still not know?</li>\r\n</ul>\r\n\r\n<h3>🌱 Developing Critical Thinking Skills</h3>\r\n\r\n<ol>\r\n    <li><strong>Read widely:</strong> Expose yourself to different perspectives</li>\r\n    <li><strong>Listen actively:</strong> Truly listen before forming a response</li>\r\n    <li><strong>Question assumptions:</strong> What do you take for granted?</li>\r\n    <li><strong>Reflect on your thinking:</strong> Why do I believe this?</li>\r\n    <li><strong>Engage in debate:</strong> Discuss with people who disagree</li>\r\n    <li><strong>Learn from mistakes:</strong> When you were wrong, why?</li>\r\n</ol>\r\n\r\n<h3>🧠 Critical Thinking vs. Intelligence</h3>\r\n<p>Intelligence is the ability to learn quickly. Critical thinking is the ability to think deeply and logically. You can be intelligent but not think critically - and vice versa. True wisdom requires both.</p>\r\n\r\n<h3>📚 Recommended Books</h3>\r\n<ul>\r\n    <li>\"Thinking, Fast and Slow\" - Daniel Kahneman</li>\r\n    <li>\"The Art of Thinking Clearly\" - Rolf Dobelli</li>\r\n    <li>\"Sophie\'s World\" - Jostein Gaarder</li>\r\n    <li>\"Meditations\" - Marcus Aurelius</li>\r\n</ul>\r\n\r\n<h3>🎯 Key Takeaway</h3>\r\n<p>Critical thinking is not about being negative or doubting everything. It\'s about <strong>thinking well</strong> - asking the right questions, examining evidence, and making decisions based on reason rather than emotion or habit. This is the path to wisdom.</p>', 'Learn the art of critical thinking! Discover the key elements of clear thinking, identify common logical fallacies, and develop skills that will help you make better decisions in life.', 'assets/images/uploads/lessons/philosophy.jpg', 'https://www.youtube.com/embed/6OLPL5p0fMg', NULL, 'Did you know that Socrates believed the unexamined life is not worth living?', 'intermediate', 7, 1, 195, '2026-08-22 21:47:51', '2026-08-24 11:30:37'),
(11, 5, 5, 'Introduction to Web Development', 'introduction-web-development', 'Building the future, one website at a time', '<h2>💻 Building the Future, One Website at a Time</h2>\r\n\r\n<p>Web development is one of the most valuable skills in the modern world. It combines creativity with logic, and design with functionality. In this comprehensive lesson, we\'ll explore the basic building blocks of the web and how you can start building your own websites.</p>\r\n\r\n<h3>🌐 How the Web Works</h3>\r\n\r\n<h4>What Happens When You Visit a Website</h4>\r\n<ol>\r\n    <li><strong>You type a URL</strong> (e.g., www.google.com)</li>\r\n    <li><strong>DNS Lookup:</strong> Your computer finds the server\'s IP address</li>\r\n    <li><strong>Request sent:</strong> Your browser requests the webpage</li>\r\n    <li><strong>Server responds:</strong> The server sends back HTML, CSS, and JavaScript</li>\r\n    <li><strong>Browser renders:</strong> Your browser displays the page</li>\r\n</ol>\r\n\r\n<h3>🔧 The Three Pillars of Web Development</h3>\r\n\r\n<h4>1. HTML - Structure (The Skeleton)</h4>\r\n<p>HTML (HyperText Markup Language) defines the structure of a webpage:</p>\r\n<pre style=\"background: #1a1a2e; color: #ffd700; padding: 15px; border-radius: 8px; overflow-x: auto;\">\r\n&lt;!DOCTYPE html&gt;\r\n&lt;html&gt;\r\n    &lt;head&gt;\r\n        &lt;title&gt;My First Website&lt;/title&gt;\r\n    &lt;/head&gt;\r\n    &lt;body&gt;\r\n        &lt;h1&gt;Welcome!&lt;/h1&gt;\r\n        &lt;p&gt;This is my first website.&lt;/p&gt;\r\n    &lt;/body&gt;\r\n&lt;/html&gt;\r\n</pre>\r\n\r\n<h4>2. CSS - Style (The Skin)</h4>\r\n<p>CSS (Cascading Style Sheets) controls how a webpage looks:</p>\r\n<pre style=\"background: #1a1a2e; color: #ffd700; padding: 15px; border-radius: 8px; overflow-x: auto;\">\r\nh1 {\r\n    color: blue;\r\n    font-size: 2rem;\r\n    text-align: center;\r\n}\r\n\r\np {\r\n    color: #333;\r\n    line-height: 1.6;\r\n}\r\n</pre>\r\n\r\n<h4>3. JavaScript - Behavior (The Brain)</h4>\r\n<p>JavaScript adds interactivity to websites:</p>\r\n<pre style=\"background: #1a1a2e; color: #ffd700; padding: 15px; border-radius: 8px; overflow-x: auto;\">\r\nfunction greetUser() {\r\n    let name = prompt(\"What is your name?\");\r\n    alert(\"Hello, \" + name + \"!\");\r\n}\r\n\r\ngreetUser();\r\n</pre>\r\n\r\n<h3>🛠️ Essential Tools</h3>\r\n<ul>\r\n    <li><strong>Text Editor:</strong> VS Code, Sublime Text, or Notepad++</li>\r\n    <li><strong>Browser:</strong> Chrome, Firefox, or Edge (for testing)</li>\r\n    <li><strong>Local Server:</strong> XAMPP or WAMP (for PHP testing)</li>\r\n    <li><strong>Version Control:</strong> Git and GitHub</li>\r\n</ul>\r\n\r\n<h3>📖 Backend vs. Frontend</h3>\r\n\r\n<h4>Frontend (Client-side)</h4>\r\n<ul>\r\n    <li>What the user sees and interacts with</li>\r\n    <li>Languages: HTML, CSS, JavaScript</li>\r\n    <li>Frameworks: Bootstrap, React, Angular</li>\r\n</ul>\r\n\r\n<h4>Backend (Server-side)</h4>\r\n<ul>\r\n    <li>What happens behind the scenes</li>\r\n    <li>Languages: PHP, Python, Java, Node.js</li>\r\n    <li>Databases: MySQL, PostgreSQL, MongoDB</li>\r\n    <li>Frameworks: Laravel, Django, Spring Boot</li>\r\n</ul>\r\n\r\n<h3>💡 Getting Started</h3>\r\n\r\n<h4>Step 1: Learn HTML</h4>\r\n<p>Start with the basics - headings, paragraphs, links, images, lists, and forms.</p>\r\n\r\n<h4>Step 2: Learn CSS</h4>\r\n<p>Understand colors, fonts, layouts, flexbox, grid, and responsive design.</p>\r\n\r\n<h4>Step 3: Learn JavaScript</h4>\r\n<p>Master variables, functions, events, DOM manipulation, and APIs.</p>\r\n\r\n<h4>Step 4: Build Projects</h4>\r\n<p>The best way to learn is by building. Start with a personal portfolio, then a blog, then an e-commerce site.</p>\r\n\r\n<h3>🌟 Career Opportunities</h3>\r\n<ul>\r\n    <li><strong>Frontend Developer:</strong> Focus on UI/UX, design, and user interaction</li>\r\n    <li><strong>Backend Developer:</strong> Focus on servers, databases, and APIs</li>\r\n    <li><strong>Full Stack Developer:</strong> Both frontend and backend</li>\r\n    <li><strong>Mobile Developer:</strong> Building apps for iOS and Android</li>\r\n    <li><strong>DevOps Engineer:</strong> Managing servers, deployment, and infrastructure</li>\r\n</ul>\r\n\r\n<h3>📚 Recommended Books</h3>\r\n<ul>\r\n    <li>\"The Pragmatic Programmer\" - Andrew Hunt</li>\r\n    <li>\"Code: The Hidden Language\" - Charles Petzold</li>\r\n    <li>\"Eloquent JavaScript\" - Marijn Haverbeke</li>\r\n    <li>\"You Don\'t Know JS\" - Kyle Simpson</li>\r\n</ul>\r\n\r\n<h3>🎯 Key Takeaway</h3>\r\n<p>Web development is an exciting and in-demand skill. Start small, build projects, and never stop learning. The best developers are those who are curious and love solving problems.</p>', 'Master the fundamentals of web development! Learn HTML, CSS, JavaScript, frontend vs backend, essential tools, and how to start your journey as a developer.', 'assets/images/uploads/lessons/computer-science.jpg', 'https://www.youtube.com/embed/HD13eq_Pmp8', NULL, 'Did you know that the first website ever created is still online today?', 'beginner', 15, 1, 421, '2026-08-22 21:47:51', '2026-08-25 07:04:22'),
(17, 1, 5, 'Understanding vs. Memorization - The Islamic Perspective', 'understanding-vs-memorization-islam-v2', 'Why Islam emphasizes comprehension over rote learning', '<h2>📖 The Islamic Approach to Knowledge</h2>\r\n\r\n<p>In Islam, knowledge is not merely about memorizing words but about understanding the deeper meanings. The Quran itself challenges us to reflect and ponder. <strong>Tadabbur</strong> means deep reflection, and it is a core Islamic principle.</p>\r\n\r\n<h3>🕌 What is Tadabbur?</h3>\r\n<p>Tadabbur is the act of reflecting deeply on the meanings of the Quran. Allah says in the Quran:</p>\r\n<blockquote style=\"background: #f8f9fa; padding: 15px; border-left: 4px solid #2ecc71; border-radius: 8px;\">\r\n    \"Do they not reflect upon the Quran? Or are there locks upon their hearts?\" (Surah Muhammad, 47:24)\r\n</blockquote>\r\n<p>This verse shows that understanding is a requirement, not an option. Allah criticizes those who do not reflect on His words.</p>\r\n\r\n<h3>🧠 Understanding Over Memorization</h3>\r\n<p>The Prophet Muhammad ﷺ said:</p>\r\n<blockquote style=\"background: #f8f9fa; padding: 15px; border-left: 4px solid #2ecc71; border-radius: 8px;\">\r\n    \"The best of you are those who learn the Quran and teach it.\" (Bukhari)\r\n</blockquote>\r\n<p>Notice that the Prophet said \"learn\" and \"teach\" - not just \"memorize.\" Learning implies understanding, application, and transmission of knowledge.</p>\r\n\r\n<h3>📝 Why Understanding Matters</h3>\r\n<ul>\r\n    <li><strong>Application:</strong> You can only apply what you understand. Memorization without understanding leads to blind following.</li>\r\n    <li><strong>Connection:</strong> Understanding creates a personal connection with Allah and His message.</li>\r\n    <li><strong>Transformation:</strong> True understanding changes behavior and character, not just recitation.</li>\r\n    <li><strong>Teaching:</strong> You can only teach what you truly understand. The best way to learn is to teach.</li>\r\n</ul>\r\n\r\n<h3>💡 How to Develop Understanding</h3>\r\n<ol>\r\n    <li><strong>Read with Tafsir:</strong> Don\'t just read the Arabic. Read translations and commentaries.</li>\r\n    <li><strong>Ask \"Why?\":</strong> For every verse, ask: Why was this revealed? What is the context? What is the message for me?</li>\r\n    <li><strong>Connect to Life:</strong> How does this verse apply to my daily life? How can I act on it?</li>\r\n    <li><strong>Discuss with Others:</strong> Knowledge grows when shared. Discuss with teachers, friends, and family.</li>\r\n    <li><strong>Teach Others:</strong> The best way to solidify understanding is to explain it to someone else.</li>\r\n</ol>\r\n\r\n<h3>🌟 The Balance</h3>\r\n<p>Memorization is important in Islam - there is great reward in memorizing the Quran. But memorization without understanding is like having a beautiful book you cannot read. The ideal is to <strong>memorize AND understand</strong>.</p>\r\n\r\n<h3>📚 Recommended Books</h3>\r\n<ul>\r\n    <li>\"The Study Quran\" - Seyyed Hossein Nasr</li>\r\n    <li>\"In the Shade of the Quran\" - Sayyid Qutb</li>\r\n    <li>\"The Message of the Quran\" - Muhammad Asad</li>\r\n</ul>\r\n\r\n<h3>🎯 Key Takeaway</h3>\r\n<p>Islam is a religion of understanding, not just recitation. The goal is to know Allah, love Him, and live according to His guidance - and that requires deep, genuine understanding.</p>', 'In this comprehensive lesson, we explore why Islam emphasizes understanding over blind memorization. Learn what Tadabbur means, how to develop deep understanding of the Quran, and why true knowledge transforms both heart and action.', 'assets/images/uploads/lessons/islamic-studies.jpg', 'https://www.youtube.com/embed/videoseries?si=4nP4SJNFaAOk9-fw&amp;list=PLTp9Bu0cTGUyqRMW4HwO3IzmPZJh_KvE7', NULL, 'Did you know that the word \"Quran\" means \"recitation\" - but the Prophet ﷺ also said to reflect deeply on its meanings?', 'intermediate', 8, 1, 255, '2026-08-22 21:55:24', '2026-08-24 11:31:00'),
(18, 2, 5, 'The Wonders of Our Solar System', 'wonders-solar-system-v2', 'Exploring the planets, stars, and our cosmic neighborhood', '<h2>🌌 Our Cosmic Neighborhood</h2>\r\n\r\n<p>Our solar system is a vast and beautiful place. From the scorching surface of Mercury to the icy plains of Pluto, each planet tells a unique story about the formation of our cosmic home.</p>\r\n\r\n<h3>☀️ The Sun - Our Star</h3>\r\n<p>The Sun is the center of our solar system. It is a massive ball of hot gas that provides light and heat to all the planets. Without the Sun, life on Earth would not exist.</p>\r\n<ul>\r\n    <li><strong>Diameter:</strong> 1.39 million km (109 times Earth)</li>\r\n    <li><strong>Mass:</strong> 330,000 times Earth</li>\r\n    <li><strong>Temperature:</strong> 5,500°C (surface), 15 million°C (core)</li>\r\n    <li><strong>Age:</strong> 4.6 billion years</li>\r\n</ul>\r\n\r\n<h3>🪐 The Inner Planets</h3>\r\n<h4>Mercury</h4>\r\n<p>The smallest planet and closest to the Sun. It has no atmosphere, so temperatures range from -180°C to 430°C. One day on Mercury is 59 Earth days.</p>\r\n\r\n<h4>Venus</h4>\r\n<p>Earth\'s \"sister planet\" because of similar size. But Venus is extremely hot (475°C) due to a runaway greenhouse effect. It rotates backward compared to other planets.</p>\r\n\r\n<h4>Earth</h4>\r\n<p>Our home - the only known planet with life. It has liquid water, a protective atmosphere, and a magnetic field that shields us from solar radiation.</p>\r\n\r\n<h4>Mars</h4>\r\n<p>The \"Red Planet\" - named for its reddish appearance from iron oxide. It has the tallest mountain in the solar system (Olympus Mons, 21.9 km) and the largest canyon (Valles Marineris, 4,000 km long).</p>\r\n\r\n<h3>🌍 The Outer Planets</h3>\r\n<h4>Jupiter</h4>\r\n<p>The largest planet - 1,300 Earths could fit inside it. It\'s a gas giant with no solid surface. Its Great Red Spot is a storm bigger than Earth that has raged for hundreds of years.</p>\r\n\r\n<h4>Saturn</h4>\r\n<p>Famous for its beautiful rings made of ice and rock. Saturn is the least dense planet - it would float in water! It has 82 known moons, including Titan (larger than Mercury).</p>\r\n\r\n<h4>Uranus</h4>\r\n<p>The coldest planet (-224°C) that spins on its side. It has 27 known moons, all named after Shakespearean characters.</p>\r\n\r\n<h4>Neptune</h4>\r\n<p>The windiest planet - winds reach 2,100 km/h! It was the first planet discovered through mathematics before being seen.</p>\r\n\r\n<h3>🔭 Beyond the Planets</h3>\r\n<ul>\r\n    <li><strong>Asteroid Belt:</strong> Between Mars and Jupiter - millions of rocky objects</li>\r\n    <li><strong>Kuiper Belt:</strong> Beyond Neptune - home to Pluto and other dwarf planets</li>\r\n    <li><strong>Oort Cloud:</strong> A massive cloud of icy objects surrounding the solar system</li>\r\n</ul>\r\n\r\n<h3>🚀 Space Exploration</h3>\r\n<ul>\r\n    <li><strong>Moon Landing:</strong> Apollo 11 (1969) - first humans on the Moon</li>\r\n    <li><strong>Mars Rovers:</strong> Curiosity, Perseverance - exploring the Red Planet</li>\r\n    <li><strong>Voyager 1 & 2:</strong> The farthest human-made objects, now in interstellar space</li>\r\n    <li><strong>James Webb Telescope:</strong> New images of the early universe</li>\r\n</ul>\r\n\r\n<h3>💡 Key Takeaways</h3>\r\n<ul>\r\n    <li>Our solar system is vast and diverse - each planet is unique</li>\r\n    <li>Understanding the solar system helps us understand our place in the universe</li>\r\n    <li>Space exploration shows human curiosity and the desire to know more</li>\r\n    <li>Every discovery leads to more questions - the universe is full of wonder</li>\r\n</ul>\r\n\r\n<h3>📚 Recommended Books</h3>\r\n<ul>\r\n    <li>\"Cosmos\" - Carl Sagan</li>\r\n    <li>\"A Brief History of Time\" - Stephen Hawking</li>\r\n    <li>\"Astrophysics for People in a Hurry\" - Neil deGrasse Tyson</li>\r\n</ul>', 'Take a detailed journey through our solar system! Learn about the Sun, all 8 planets, dwarf planets, space exploration, and the incredible facts that make our cosmic neighborhood so fascinating.', 'assets/images/uploads/lessons/astronomy.jpg', 'https://www.youtube.com/embed/T5WRSZuhc2U', NULL, 'Did you know that Jupiter is so large that 1,300 Earths could fit inside it?', 'beginner', 10, 1, 183, '2026-08-22 21:55:24', '2026-08-24 11:31:06'),
(19, 3, 5, 'Understanding How Your Brain Works', 'understanding-brain-works-v2', 'A beginner\'s guide to the human mind', '<h2>🧠 The Most Complex Organ</h2>\r\n\r\n<p>The human brain is the most complex organ in the body. It contains approximately 86 billion neurons, each forming thousands of connections with other neurons. Understanding how the brain works can help us learn more effectively, manage our emotions, and improve our overall well-being.</p>\r\n\r\n<h3>🔬 Brain Structure</h3>\r\n<h4>Cerebrum (The Big Brain)</h4>\r\n<p>The largest part of the brain, responsible for conscious thought, language, memory, and sensory processing. It is divided into two hemispheres:</p>\r\n<ul>\r\n    <li><strong>Left Hemisphere:</strong> Logic, language, analytical thinking</li>\r\n    <li><strong>Right Hemisphere:</strong> Creativity, intuition, spatial awareness</li>\r\n</ul>\r\n\r\n<h4>Cerebellum (The Little Brain)</h4>\r\n<p>Responsible for coordination, balance, and fine motor movements. It helps you walk, type, and even speak clearly.</p>\r\n\r\n<h4>Brainstem</h4>\r\n<p>Controls automatic functions like breathing, heartbeat, and sleep cycles. It connects the brain to the spinal cord.</p>\r\n\r\n<h3>⚡ How the Brain Works</h3>\r\n<p>Neurons are the building blocks of the brain. They communicate through electrical and chemical signals:</p>\r\n<ol>\r\n    <li><strong>Dendrites</strong> receive signals from other neurons</li>\r\n    <li><strong>Cell body</strong> processes the signal</li>\r\n    <li><strong>Axon</strong> transmits the signal to the next neuron</li>\r\n    <li><strong>Synapse</strong> is the gap between neurons - chemicals (neurotransmitters) cross this gap</li>\r\n</ol>\r\n\r\n<h3>🧪 Neurotransmitters - The Brain\'s Messengers</h3>\r\n<ul>\r\n    <li><strong>Dopamine:</strong> Motivation, reward, pleasure</li>\r\n    <li><strong>Serotonin:</strong> Mood, happiness, sleep</li>\r\n    <li><strong>Adrenaline:</strong> Fight-or-flight response</li>\r\n    <li><strong>Endorphins:</strong> Pain relief, happiness (\"runner\'s high\")</li>\r\n    <li><strong>Oxytocin:</strong> Love, bonding, trust</li>\r\n</ul>\r\n\r\n<h3>💡 Learning and Memory</h3>\r\n<p>Learning is the process of forming new connections between neurons. Memory is the ability to store and retrieve information.</p>\r\n\r\n<h4>Types of Memory</h4>\r\n<ul>\r\n    <li><strong>Short-term memory:</strong> Holds information for 15-30 seconds (like remembering a phone number)</li>\r\n    <li><strong>Long-term memory:</strong> Stores information for days, months, or years</li>\r\n    <li><strong>Procedural memory:</strong> How to do things (riding a bike, typing)</li>\r\n    <li><strong>Declarative memory:</strong> Facts and events</li>\r\n</ul>\r\n\r\n<h4>How to Improve Learning</h4>\r\n<ol>\r\n    <li><strong>Spaced repetition:</strong> Review material at increasing intervals</li>\r\n    <li><strong>Active recall:</strong> Test yourself instead of re-reading</li>\r\n    <li><strong>Sleep:</strong> Sleep consolidates memories</li>\r\n    <li><strong>Exercise:</strong> Increases blood flow to the brain</li>\r\n    <li><strong>Teach others:</strong> Explaining helps you understand better</li>\r\n</ol>\r\n\r\n<h3>🧘 Mental Health and Well-being</h3>\r\n<ul>\r\n    <li><strong>Stress:</strong> Short-term stress can be helpful, but chronic stress damages the brain</li>\r\n    <li><strong>Mindfulness:</strong> Reduces stress and improves focus</li>\r\n    <li><strong>Exercise:</strong> Boosts mood and brain function</li>\r\n    <li><strong>Social connection:</strong> Essential for mental health</li>\r\n</ul>\r\n\r\n<h3>📚 Recommended Books</h3>\r\n<ul>\r\n    <li>\"Thinking, Fast and Slow\" - Daniel Kahneman</li>\r\n    <li>\"The Power of Habit\" - Charles Duhigg</li>\r\n    <li>\"Mindset\" - Carol Dweck</li>\r\n</ul>', 'Explore the fascinating world of the human brain! Learn about brain structure, how neurons communicate, neurotransmitters, memory formation, and practical tips for improving learning and mental well-being.', 'assets/images/uploads/lessons/psychology.jpg', 'https://www.youtube.com/embed/ZcR4fLox-vI', NULL, 'Did you know that your brain uses about 20% of your body\'s total energy?', 'intermediate', 12, 1, 325, '2026-08-22 21:55:24', '2026-08-24 11:31:10'),
(20, 4, 5, 'Critical Thinking - The Path to Wisdom', 'critical-thinking-wisdom-v2', 'How to think better and make better decisions', '<h2>💭 The Art of Thinking</h2>\r\n\r\n<p>Critical thinking is the ability to analyze facts, generate ideas, and make logical decisions. It is the cornerstone of wisdom and understanding. In a world full of information, critical thinking helps us distinguish truth from falsehood and make better choices in life.</p>\r\n\r\n<h3>🧩 The Elements of Critical Thinking</h3>\r\n\r\n<h4>1. Clarity</h4>\r\n<p>Can you explain the idea clearly? If not, you don\'t understand it well enough. Ask: \"What exactly do you mean?\"</p>\r\n\r\n<h4>2. Accuracy</h4>\r\n<p>Is the information correct? Always verify facts from reliable sources. Ask: \"Is this really true?\"</p>\r\n\r\n<h4>3. Relevance</h4>\r\n<p>Does this information apply to the question? Avoid irrelevant distractions. Ask: \"How does this connect to the issue?\"</p>\r\n\r\n<h4>4. Depth</h4>\r\n<p>Are you looking at the surface or the complexity underneath? Ask: \"What are the deeper issues?\"</p>\r\n\r\n<h4>5. Logic</h4>\r\n<p>Does the conclusion follow from the evidence? Ask: \"Does this make sense?\"</p>\r\n\r\n<h3>🚫 Common Logical Fallacies</h3>\r\n\r\n<ul>\r\n    <li><strong>Ad Hominem:</strong> Attacking the person instead of the argument</li>\r\n    <li><strong>Straw Man:</strong> Misrepresenting an argument to make it easier to attack</li>\r\n    <li><strong>Appeal to Authority:</strong> \"It\'s true because X says so\"</li>\r\n    <li><strong>False Dilemma:</strong> \"Either A or B\" when there are more options</li>\r\n    <li><strong>Slippery Slope:</strong> \"If A happens, then B will happen\" without evidence</li>\r\n    <li><strong>Confirmation Bias:</strong> Seeking evidence that confirms your beliefs</li>\r\n</ul>\r\n\r\n<h3>🔍 Questions for Critical Thinking</h3>\r\n\r\n<ul>\r\n    <li>What is the source of this information?</li>\r\n    <li>What evidence supports this claim?</li>\r\n    <li>Are there alternative explanations?</li>\r\n    <li>What are the implications of this idea?</li>\r\n    <li>What would change if this were wrong?</li>\r\n    <li>What do I still not know?</li>\r\n</ul>\r\n\r\n<h3>🌱 Developing Critical Thinking Skills</h3>\r\n\r\n<ol>\r\n    <li><strong>Read widely:</strong> Expose yourself to different perspectives</li>\r\n    <li><strong>Listen actively:</strong> Truly listen before forming a response</li>\r\n    <li><strong>Question assumptions:</strong> What do you take for granted?</li>\r\n    <li><strong>Reflect on your thinking:</strong> Why do I believe this?</li>\r\n    <li><strong>Engage in debate:</strong> Discuss with people who disagree</li>\r\n    <li><strong>Learn from mistakes:</strong> When you were wrong, why?</li>\r\n</ol>\r\n\r\n<h3>🧠 Critical Thinking vs. Intelligence</h3>\r\n<p>Intelligence is the ability to learn quickly. Critical thinking is the ability to think deeply and logically. You can be intelligent but not think critically - and vice versa. True wisdom requires both.</p>\r\n\r\n<h3>📚 Recommended Books</h3>\r\n<ul>\r\n    <li>\"Thinking, Fast and Slow\" - Daniel Kahneman</li>\r\n    <li>\"The Art of Thinking Clearly\" - Rolf Dobelli</li>\r\n    <li>\"Sophie\'s World\" - Jostein Gaarder</li>\r\n    <li>\"Meditations\" - Marcus Aurelius</li>\r\n</ul>\r\n\r\n<h3>🎯 Key Takeaway</h3>\r\n<p>Critical thinking is not about being negative or doubting everything. It\'s about <strong>thinking well</strong> - asking the right questions, examining evidence, and making decisions based on reason rather than emotion or habit. This is the path to wisdom.</p>', 'Learn the art of critical thinking! Discover the key elements of clear thinking, identify common logical fallacies, and develop skills that will help you make better decisions in life.', 'assets/images/uploads/lessons/philosophy.jpg', 'https://www.youtube.com/embed/6OLPL5p0fMg', NULL, 'Did you know that Socrates believed the unexamined life is not worth living?', 'intermediate', 7, 1, 196, '2026-08-22 21:55:24', '2026-08-24 11:31:14'),
(21, 5, 5, 'Introduction to Web Development', 'introduction-web-development-v2', 'Building the future, one website at a time', '<h2>💻 Building the Future, One Website at a Time</h2>\r\n\r\n<p>Web development is one of the most valuable skills in the modern world. It combines creativity with logic, and design with functionality. In this comprehensive lesson, we\'ll explore the basic building blocks of the web and how you can start building your own websites.</p>\r\n\r\n<h3>🌐 How the Web Works</h3>\r\n\r\n<h4>What Happens When You Visit a Website</h4>\r\n<ol>\r\n    <li><strong>You type a URL</strong> (e.g., www.google.com)</li>\r\n    <li><strong>DNS Lookup:</strong> Your computer finds the server\'s IP address</li>\r\n    <li><strong>Request sent:</strong> Your browser requests the webpage</li>\r\n    <li><strong>Server responds:</strong> The server sends back HTML, CSS, and JavaScript</li>\r\n    <li><strong>Browser renders:</strong> Your browser displays the page</li>\r\n</ol>\r\n\r\n<h3>🔧 The Three Pillars of Web Development</h3>\r\n\r\n<h4>1. HTML - Structure (The Skeleton)</h4>\r\n<p>HTML (HyperText Markup Language) defines the structure of a webpage:</p>\r\n<pre style=\"background: #1a1a2e; color: #ffd700; padding: 15px; border-radius: 8px; overflow-x: auto;\">\r\n&lt;!DOCTYPE html&gt;\r\n&lt;html&gt;\r\n    &lt;head&gt;\r\n        &lt;title&gt;My First Website&lt;/title&gt;\r\n    &lt;/head&gt;\r\n    &lt;body&gt;\r\n        &lt;h1&gt;Welcome!&lt;/h1&gt;\r\n        &lt;p&gt;This is my first website.&lt;/p&gt;\r\n    &lt;/body&gt;\r\n&lt;/html&gt;\r\n</pre>\r\n\r\n<h4>2. CSS - Style (The Skin)</h4>\r\n<p>CSS (Cascading Style Sheets) controls how a webpage looks:</p>\r\n<pre style=\"background: #1a1a2e; color: #ffd700; padding: 15px; border-radius: 8px; overflow-x: auto;\">\r\nh1 {\r\n    color: blue;\r\n    font-size: 2rem;\r\n    text-align: center;\r\n}\r\n\r\np {\r\n    color: #333;\r\n    line-height: 1.6;\r\n}\r\n</pre>\r\n\r\n<h4>3. JavaScript - Behavior (The Brain)</h4>\r\n<p>JavaScript adds interactivity to websites:</p>\r\n<pre style=\"background: #1a1a2e; color: #ffd700; padding: 15px; border-radius: 8px; overflow-x: auto;\">\r\nfunction greetUser() {\r\n    let name = prompt(\"What is your name?\");\r\n    alert(\"Hello, \" + name + \"!\");\r\n}\r\n\r\ngreetUser();\r\n</pre>\r\n\r\n<h3>🛠️ Essential Tools</h3>\r\n<ul>\r\n    <li><strong>Text Editor:</strong> VS Code, Sublime Text, or Notepad++</li>\r\n    <li><strong>Browser:</strong> Chrome, Firefox, or Edge (for testing)</li>\r\n    <li><strong>Local Server:</strong> XAMPP or WAMP (for PHP testing)</li>\r\n    <li><strong>Version Control:</strong> Git and GitHub</li>\r\n</ul>\r\n\r\n<h3>📖 Backend vs. Frontend</h3>\r\n\r\n<h4>Frontend (Client-side)</h4>\r\n<ul>\r\n    <li>What the user sees and interacts with</li>\r\n    <li>Languages: HTML, CSS, JavaScript</li>\r\n    <li>Frameworks: Bootstrap, React, Angular</li>\r\n</ul>\r\n\r\n<h4>Backend (Server-side)</h4>\r\n<ul>\r\n    <li>What happens behind the scenes</li>\r\n    <li>Languages: PHP, Python, Java, Node.js</li>\r\n    <li>Databases: MySQL, PostgreSQL, MongoDB</li>\r\n    <li>Frameworks: Laravel, Django, Spring Boot</li>\r\n</ul>\r\n\r\n<h3>💡 Getting Started</h3>\r\n\r\n<h4>Step 1: Learn HTML</h4>\r\n<p>Start with the basics - headings, paragraphs, links, images, lists, and forms.</p>\r\n\r\n<h4>Step 2: Learn CSS</h4>\r\n<p>Understand colors, fonts, layouts, flexbox, grid, and responsive design.</p>\r\n\r\n<h4>Step 3: Learn JavaScript</h4>\r\n<p>Master variables, functions, events, DOM manipulation, and APIs.</p>\r\n\r\n<h4>Step 4: Build Projects</h4>\r\n<p>The best way to learn is by building. Start with a personal portfolio, then a blog, then an e-commerce site.</p>\r\n\r\n<h3>🌟 Career Opportunities</h3>\r\n<ul>\r\n    <li><strong>Frontend Developer:</strong> Focus on UI/UX, design, and user interaction</li>\r\n    <li><strong>Backend Developer:</strong> Focus on servers, databases, and APIs</li>\r\n    <li><strong>Full Stack Developer:</strong> Both frontend and backend</li>\r\n    <li><strong>Mobile Developer:</strong> Building apps for iOS and Android</li>\r\n    <li><strong>DevOps Engineer:</strong> Managing servers, deployment, and infrastructure</li>\r\n</ul>\r\n\r\n<h3>📚 Recommended Books</h3>\r\n<ul>\r\n    <li>\"The Pragmatic Programmer\" - Andrew Hunt</li>\r\n    <li>\"Code: The Hidden Language\" - Charles Petzold</li>\r\n    <li>\"Eloquent JavaScript\" - Marijn Haverbeke</li>\r\n    <li>\"You Don\'t Know JS\" - Kyle Simpson</li>\r\n</ul>\r\n\r\n<h3>🎯 Key Takeaway</h3>\r\n<p>Web development is an exciting and in-demand skill. Start small, build projects, and never stop learning. The best developers are those who are curious and love solving problems.</p>', 'Master the fundamentals of web development! Learn HTML, CSS, JavaScript, frontend vs backend, essential tools, and how to start your journey as a developer.', 'assets/images/uploads/lessons/computer-science.jpg', 'https://www.youtube.com/embed/HD13eq_Pmp8', NULL, 'Did you know that the first website ever created is still online today?', 'beginner', 15, 1, 424, '2026-08-22 21:55:24', '2026-08-24 11:31:17');

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
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_number` varchar(50) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_email` varchar(100) NOT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `order_notes` text DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` enum('bank_transfer','easypaisa','jazzcash','cash_on_delivery') DEFAULT 'easypaisa',
  `payment_status` enum('pending','paid','failed') DEFAULT 'pending',
  `status` enum('pending','processing','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `user_id`, `customer_name`, `customer_email`, `customer_phone`, `shipping_address`, `order_notes`, `subtotal`, `total_amount`, `payment_method`, `payment_status`, `status`, `created_at`) VALUES
(1, 'ROSHAN-20260824-7204', 6, 'test3', 'test3@test.com', '12354698754', 'asdakdjkashasjkdnajk', '', 1999.00, 1999.00, 'cash_on_delivery', 'pending', 'completed', '2026-08-24 10:03:24');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `quantity`, `price`) VALUES
(1, 1, 2, 'Quran Study & Understanding Bundle', 1, 1999.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `category` enum('bundle','guide','merchandise') DEFAULT 'guide',
  `product_type` enum('digital','physical') DEFAULT 'digital',
  `file_path` varchar(255) DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `stock_quantity` int(11) DEFAULT 0,
  `views_count` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `slug`, `description`, `price`, `sale_price`, `image_path`, `category`, `product_type`, `file_path`, `is_featured`, `is_active`, `stock_quantity`, `views_count`, `created_at`) VALUES
(1, 'Complete Web Development Bootcamp', 'web-dev-bootcamp', 'Master HTML, CSS, JavaScript, PHP, and MySQL. Includes 15+ lessons, 5 real projects, and a completion certificate. Perfect for beginners!', 2499.00, NULL, NULL, 'bundle', 'digital', NULL, 1, 1, 0, 2, '2026-08-24 09:06:11'),
(2, 'Quran Study & Understanding Bundle', 'quran-study-bundle', '10 comprehensive lessons on Quranic understanding, Tafsir, and Islamic ethics. Includes study guides and reflection journals.', 1999.00, NULL, NULL, 'bundle', 'digital', NULL, 1, 1, 0, 4, '2026-08-24 09:06:11'),
(3, 'Psychology Mastery Guide', 'psychology-mastery-guide', 'Complete guide to understanding the human mind - learning psychology, cognitive biases, motivation, and mental well-being. 80+ pages PDF.', 1499.00, NULL, NULL, 'guide', 'digital', NULL, 1, 1, 0, 0, '2026-08-24 09:06:11'),
(4, 'Astronomy For Beginners - Study Pack', 'astronomy-beginners-pack', 'Introduction to our solar system, stars, galaxies, and the universe. Includes 30+ illustrations, diagrams, and interactive activities.', 1299.00, NULL, NULL, 'guide', 'digital', NULL, 0, 1, 0, 0, '2026-08-24 09:06:11'),
(5, 'Critical Thinking & Philosophy Guide', 'critical-thinking-guide', 'Learn the art of critical thinking, logic, and wisdom. Practical exercises to improve decision making and problem solving.', 999.00, NULL, NULL, 'guide', 'digital', NULL, 0, 1, 0, 1, '2026-08-24 09:06:11'),
(6, 'Programming Quick Reference Bundle', 'programming-quick-reference', 'PHP, JavaScript, HTML, CSS, and MySQL cheat sheets in one bundle. 50+ pages of essential code snippets and examples.', 799.00, NULL, NULL, 'bundle', 'digital', NULL, 0, 1, 0, 1, '2026-08-24 09:06:11'),
(7, 'Computer Science Fundamentals', 'cs-fundamentals', 'Complete guide to computer science basics - algorithms, data structures, web development, and cybersecurity. Perfect for beginners.', 1799.00, NULL, NULL, 'bundle', 'digital', NULL, 0, 1, 0, 1, '2026-08-24 09:06:11'),
(8, 'Islamic Studies Quick Reference', 'islamic-quick-reference', 'Concise guide to Islamic concepts, Quranic verses, and Hadith for students. Perfect for quick revision and understanding.', 599.00, NULL, NULL, 'guide', 'digital', NULL, 0, 1, 0, 0, '2026-08-24 09:06:11');

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
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

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
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

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
