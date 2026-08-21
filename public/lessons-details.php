<?php
// public/lesson-detail.php
$page_title = 'Lesson Details';
$current_page = 'lesson';
require_once '../config/database.php';
require_once '../includes/functions.php';

// Get lesson ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    header('Location: lessons.php');
    exit();
}

// Get lesson with category info
$stmt = $pdo->prepare("
    SELECT l.*, c.name as category_name, c.color_hex 
    FROM lessons l 
    LEFT JOIN categories c ON l.category_id = c.id 
    WHERE l.id = ? AND l.is_published = 1
");
$stmt->execute([$id]);
$lesson = $stmt->fetch();

if (!$lesson) {
    header('Location: lessons.php');
    exit();
}

// Increment view count
$stmt = $pdo->prepare("UPDATE lessons SET view_count = view_count + 1 WHERE id = ?");
$stmt->execute([$id]);

// Get related lessons from same category
$stmt = $pdo->prepare("
    SELECT * FROM lessons 
    WHERE category_id = ? AND id != ? AND is_published = 1 
    ORDER BY RAND() LIMIT 3
");
$stmt->execute([$lesson['category_id'], $id]);
$related_lessons = $stmt->fetchAll();

// Get books for this category
$stmt = $pdo->prepare("
    SELECT * FROM books 
    WHERE category_id = ? 
    ORDER BY is_featured DESC, created_at DESC 
    LIMIT 3
");
$stmt->execute([$lesson['category_id']]);
$related_books = $stmt->fetchAll();

// Get quiz questions for this lesson
$stmt = $pdo->prepare("SELECT * FROM quiz_questions WHERE lesson_id = ? ORDER BY id");
$stmt->execute([$id]);
$quiz_questions = $stmt->fetchAll();

require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>

<!-- Reading Progress Bar -->
<div id="readingProgress" style="position:fixed;top:0;left:0;width:0;height:4px;background:#ffd700;z-index:9999;transition:width 0.3s;"></div>

<!-- Lesson Header -->
<main class="page-content">
<section style="background: var(--primary-gradient);padding:60px 0 40px;">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center text-white">
                <span class="badge bg-warning text-dark mb-3" style="background: <?php echo $lesson['color_hex']; ?> !important;">
                    <?php echo htmlspecialchars($lesson['category_name']); ?>
                </span>
                <h1 class="display-4 fw-bold"><?php echo htmlspecialchars($lesson['title']); ?></h1>
                <?php if ($lesson['subtitle']): ?>
                    <p class="lead"><?php echo htmlspecialchars($lesson['subtitle']); ?></p>
                <?php endif; ?>
                <div class="d-flex justify-content-center gap-3 mt-3">
                    <span class="badge bg-secondary">
                        <i class="far fa-clock"></i> <?php echo $lesson['reading_time']; ?> min read
                    </span>
                    <span class="badge bg-info">
                        <i class="fas fa-layer-group"></i> <?php echo ucfirst($lesson['difficulty']); ?>
                    </span>
                    <span class="badge bg-primary">
                        <i class="fas fa-eye"></i> <?php echo $lesson['view_count']; ?> views
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Featured Image -->
                <?php if ($lesson['image_path']): ?>
                    <div class="mb-4">
                        <img src="<?php echo SITE_URL . $lesson['image_path']; ?>" 
                             class="img-fluid rounded-3 shadow-sm" 
                             alt="<?php echo htmlspecialchars($lesson['title']); ?>"
                             style="width:100%;max-height:400px;object-fit:cover;">
                    </div>
                <?php endif; ?>
                
                <!-- Lesson Content -->
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <?php if ($lesson['summary']): ?>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> 
                                <?php echo nl2br(htmlspecialchars($lesson['summary'])); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="lesson-content">
                            <?php echo nl2br($lesson['content']); ?>
                        </div>
                    </div>
                </div>
                
                <!-- Video Section -->
                <?php if ($lesson['video_url']): ?>
                    <div class="card shadow-sm border-0 mt-4">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-play-circle text-warning"></i> 
                                Watch Video
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if ($lesson['curiosity_question']): ?>
                                <div class="alert alert-warning">
                                    <i class="fas fa-lightbulb"></i> 
                                    <?php echo htmlspecialchars($lesson['curiosity_question']); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="ratio ratio-16x9">
                                <iframe src="<?php echo getEmbedUrl($lesson['video_url']); ?>" 
                                        allowfullscreen 
                                        loading="lazy"
                                        class="rounded-3">
                                </iframe>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Quiz Section -->
                <?php if (count($quiz_questions) > 0): ?>
                    <div class="card shadow-sm border-0 mt-4 reveal-on-scroll" id="quiz">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-question-circle"></i> 
                                Test Your Understanding
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">Answer these questions to check your understanding.</p>
                            <form id="quizForm">
                                <?php foreach($quiz_questions as $index => $q): ?>
                                    <div class="quiz-question mb-4 p-3 border rounded-3">
                                        <h6>Question <?php echo $index + 1; ?>: <?php echo htmlspecialchars($q['question']); ?></h6>
                                        <div class="ms-3">
                                            <?php 
                                            $options = ['a' => $q['option_a'], 'b' => $q['option_b'], 'c' => $q['option_c'], 'd' => $q['option_d']];
                                            foreach($options as $key => $value):
                                                if (empty($value)) continue;
                                            ?>
                                                <div class="form-check">
                                                    <input class="form-check-input quiz-option" 
                                                           type="radio" 
                                                           name="question_<?php echo $q['id']; ?>" 
                                                           value="<?php echo $key; ?>" 
                                                           id="q<?php echo $q['id']; ?>_<?php echo $key; ?>">
                                                    <label class="form-check-label" for="q<?php echo $q['id']; ?>_<?php echo $key; ?>">
                                                        <?php echo htmlspecialchars($value); ?>
                                                    </label>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <div class="quiz-feedback mt-2 small" id="feedback_<?php echo $q['id']; ?>"></div>
                                    </div>
                                <?php endforeach; ?>
                                
                                <button type="button" class="btn btn-success" onclick="checkQuiz()">
                                    <i class="fas fa-check"></i> Check Answers
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="resetQuiz()">
                                    <i class="fas fa-undo"></i> Reset
                                </button>
                            </form>
                            <div id="quizResult" class="mt-3"></div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Book Recommendations -->
                <?php if (count($related_books) > 0): ?>
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">
                                <i class="fas fa-book-open"></i> Recommended Books
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php foreach($related_books as $book): ?>
                                <div class="d-flex mb-3 align-items-start">
                                    <?php if ($book['cover_image']): ?>
                                        <img src="<?php echo SITE_URL . $book['cover_image']; ?>" 
                                             style="width:60px;height:80px;object-fit:cover;border-radius:4px;" 
                                             alt="<?php echo htmlspecialchars($book['title']); ?>">
                                    <?php else: ?>
                                        <div style="width:60px;height:80px;background:#eee;border-radius:4px;display:flex;align-items:center;justify-content:center;">
                                            <i class="fas fa-book text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="ms-2">
                                        <h6 class="mb-0"><?php echo htmlspecialchars($book['title']); ?></h6>
                                        <small class="text-muted">by <?php echo htmlspecialchars($book['author']); ?></small>
                                        <br>
                                        <small>
                                            <?php if ($book['pdf_link']): ?>
                                                <a href="<?php echo $book['pdf_link']; ?>" target="_blank" class="text-success">
                                                    <i class="fas fa-file-pdf"></i> Read
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($book['purchase_link']): ?>
                                                <a href="<?php echo $book['purchase_link']; ?>" target="_blank" class="text-primary ms-2">
                                                    <i class="fas fa-shopping-cart"></i> Buy
                                                </a>
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Related Lessons -->
                <?php if (count($related_lessons) > 0): ?>
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-arrow-right"></i> Related Lessons
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php foreach($related_lessons as $related): ?>
                                <div class="mb-2">
                                    <a href="lesson-detail.php?id=<?php echo $related['id']; ?>" 
                                       class="text-decoration-none">
                                        <i class="fas fa-book text-primary"></i>
                                        <?php echo htmlspecialchars($related['title']); ?>
                                    </a>
                                    <br>
                                    <small class="text-muted">
                                        <i class="far fa-clock"></i> <?php echo $related['reading_time']; ?> min
                                    </small>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Progress Tracking (if logged in) -->
                <?php if (isLoggedIn()): ?>
                    <div class="card shadow-sm border-0 mt-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-chart-line"></i> Your Progress
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php
                            // Check if user has completed this lesson
                            $stmt = $pdo->prepare("SELECT * FROM user_progress WHERE user_id = ? AND lesson_id = ?");
                            $stmt->execute([$_SESSION['user_id'], $id]);
                            $progress = $stmt->fetch();
                            ?>
                            <?php if ($progress && $progress['is_completed']): ?>
                                <p class="text-success">
                                    <i class="fas fa-check-circle"></i> You've completed this lesson!
                                </p>
                                <small class="text-muted">Completed: <?php echo formatDate($progress['last_accessed']); ?></small>
                            <?php else: ?>
                                <p class="text-muted">Complete the quiz to mark this lesson as done.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<script>
// Quiz System
function checkQuiz() {
    const questions = document.querySelectorAll('.quiz-question');
    let score = 0;
    let total = questions.length;
    let allAnswered = true;
    
    questions.forEach((q, index) => {
        const selected = q.querySelector('input:checked');
        const feedback = q.querySelector('.quiz-feedback');
        const questionId = q.querySelector('.quiz-option').name.replace('question_', '');
        
        if (!selected) {
            allAnswered = false;
            feedback.innerHTML = '<span class="text-warning">⚠️ Please select an answer</span>';
            return;
        }
        
        // Get correct answer from data
        <?php foreach($quiz_questions as $q): ?>
            if (questionId == '<?php echo $q['id']; ?>') {
                const correct = '<?php echo $q['correct_answer']; ?>';
                if (selected.value === correct) {
                    feedback.innerHTML = '<span class="text-success">✅ Correct!</span>';
                    score++;
                } else {
                    feedback.innerHTML = '<span class="text-danger">❌ Incorrect. <?php echo htmlspecialchars($q['explanation'] ?? 'Try again!'); ?></span>';
                }
            }
        <?php endforeach; ?>
    });
    
    if (!allAnswered) {
        document.getElementById('quizResult').innerHTML = `
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> Please answer all questions.
            </div>
        `;
        return;
    }
    
    const percentage = Math.round((score / total) * 100);
    let grade = '';
    let color = '';
    
    if (percentage >= 80) { grade = 'Excellent! 🌟'; color = 'success'; if (window.launchConfetti) window.launchConfetti(); }
    else if (percentage >= 60) { grade = 'Good job! 👍'; color = 'info'; }
    else if (percentage >= 40) { grade = 'Keep learning! 📚'; color = 'warning'; }
    else { grade = 'Review the lesson again. 🔄'; color = 'danger'; }
    
    document.getElementById('quizResult').innerHTML = `
        <div class="alert alert-${color}">
            <h6>Score: ${score}/${total} (${percentage}%)</h6>
            <p class="mb-0">${grade}</p>
        </div>
    `;
    
    // Mark lesson as completed if score >= 60%
    <?php if (isLoggedIn()): ?>
        if (percentage >= 60) {
            fetch('mark-complete.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'lesson_id=<?php echo $id; ?>'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.querySelector('.card-header.bg-info').innerHTML = `
                        <h5 class="mb-0">
                            <i class="fas fa-check-circle"></i> Completed! 🎉
                        </h5>
                    `;
                }
            })
            .catch(error => console.error('Error:', error));
        }
    <?php endif; ?>
}

function resetQuiz() {
    document.querySelectorAll('.quiz-option').forEach(el => {
        el.checked = false;
    });
    document.querySelectorAll('.quiz-feedback').forEach(el => {
        el.innerHTML = '';
    });
    document.getElementById('quizResult').innerHTML = '';
}

// Reading Progress
window.addEventListener('scroll', function() {
    const scrollTop = window.scrollY;
    const docHeight = document.documentElement.scrollHeight - window.innerHeight;
    const progress = (scrollTop / docHeight) * 100;
    document.getElementById('readingProgress').style.width = progress + '%';
});
</script>

</main>

<?php require_once '../includes/footer.php'; ?>