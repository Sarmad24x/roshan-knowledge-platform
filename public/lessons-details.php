<?php
// public/lessons-details.php
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

<!-- ============================================================ -->
<!-- READING PROGRESS BAR -->
<!-- ============================================================ -->
<div id="readingProgress" style="position: fixed; top: 0; left: 0; width: 0%; height: 4px; background: linear-gradient(90deg, #ffd700, #f39c12); z-index: 9999; transition: width 0.3s ease; box-shadow: 0 0 20px rgba(255, 215, 0, 0.3);"></div>

<!-- ============================================================ -->
<!-- PAGE HEADER -->
<!-- ============================================================ -->
<div style="background: var(--primary-gradient); padding: 40px 0 30px 0; margin-top: -1px;">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center text-white">
                <span class="badge px-3 py-2 rounded-pill mb-3" style="background: <?php echo $lesson['color_hex']; ?>; color: white;">
                    <?php echo htmlspecialchars($lesson['category_name']); ?>
                </span>
                <h1 class="display-4 fw-bold"><?php echo htmlspecialchars($lesson['title']); ?></h1>
                <?php if ($lesson['subtitle']): ?>
                    <p class="lead text-white-50"><?php echo htmlspecialchars($lesson['subtitle']); ?></p>
                <?php endif; ?>
                <div class="d-flex justify-content-center gap-3 mt-3 flex-wrap">
                    <span class="badge bg-secondary px-3 py-2 rounded-pill">
                        <i class="far fa-clock"></i> <?php echo $lesson['reading_time']; ?> min read
                    </span>
                    <span class="badge bg-info px-3 py-2 rounded-pill">
                        <i class="fas fa-layer-group"></i> <?php echo ucfirst($lesson['difficulty']); ?>
                    </span>
                    <span class="badge bg-primary px-3 py-2 rounded-pill">
                        <i class="fas fa-eye"></i> <?php echo $lesson['view_count']; ?> views
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- MAIN CONTENT -->
<!-- ============================================================ -->
<div style="padding: 30px 0;">
    <div class="container">
        <div class="row g-4">
            
            <!-- ==================== MAIN CONTENT ==================== -->
            <div class="col-lg-8">
                
                <!-- Featured Image -->
                <?php if ($lesson['image_path']): ?>
                    <div class="mb-4" style="border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.08);">
                        <img src="<?php echo SITE_URL . $lesson['image_path']; ?>" 
                             class="img-fluid" 
                             alt="<?php echo htmlspecialchars($lesson['title']); ?>"
                             style="width: 100%; max-height: 400px; object-fit: cover;">
                    </div>
                <?php endif; ?>
                
                <!-- Lesson Content -->
                <div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">
                    <div class="card-body p-4">
                        <?php if ($lesson['summary']): ?>
                            <div class="alert alert-info" style="border-radius: 12px; border-left: 4px solid #ffd700;">
                                <i class="fas fa-info-circle"></i> 
                                <?php echo nl2br(htmlspecialchars($lesson['summary'])); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="lesson-content" style="font-size: 1.05rem; line-height: 1.8; color: #333;">
                            <?php echo nl2br($lesson['content']); ?>
                        </div>
                    </div>
                </div>
                
                <!-- Video Section -->
                <?php if ($lesson['video_url']): ?>
                    <div class="card border-0 shadow-sm mt-4" style="border-radius: 16px; overflow: hidden;">
                        <div class="card-header" style="background: #0a0a2e; border: none; padding: 15px 20px;">
                            <h5 class="mb-0 text-white">
                                <i class="fas fa-play-circle text-warning"></i> 
                                Watch Video
                            </h5>
                        </div>
                        <div class="card-body p-3">
                            <?php if ($lesson['curiosity_question']): ?>
                                <div class="alert alert-warning mb-3" style="border-radius: 12px;">
                                    <i class="fas fa-lightbulb"></i> 
                                    <?php echo htmlspecialchars($lesson['curiosity_question']); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="ratio ratio-16x9" style="border-radius: 12px; overflow: hidden;">
                                <iframe src="<?php echo getEmbedUrl($lesson['video_url']); ?>" 
                                        allowfullscreen 
                                        loading="lazy"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture">
                                </iframe>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Quiz Section -->
                <?php if (count($quiz_questions) > 0): ?>
                    <div class="card border-0 shadow-sm mt-4" style="border-radius: 16px; overflow: hidden;" id="quiz">
                        <div class="card-header" style="background: #2ecc71; border: none; padding: 15px 20px;">
                            <h5 class="mb-0 text-white">
                                <i class="fas fa-question-circle"></i> 
                                Test Your Understanding
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <p class="text-muted">Answer these questions to check your understanding.</p>
                            <form id="quizForm">
                                <?php foreach($quiz_questions as $index => $q): ?>
                                    <div class="quiz-question mb-4 p-3 border rounded-3" style="border-radius: 12px !important; border-color: #e0e0e0 !important;">
                                        <h6 class="fw-bold">Question <?php echo $index + 1; ?>: <?php echo htmlspecialchars($q['question']); ?></h6>
                                        <div class="ms-3 mt-2">
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
                                
                                <div class="d-flex gap-2 flex-wrap">
                                    <button type="button" class="btn btn-success rounded-pill px-4" onclick="checkQuiz()">
                                        <i class="fas fa-check"></i> Check Answers
                                    </button>
                                    <button type="button" class="btn btn-secondary rounded-pill px-4" onclick="resetQuiz()">
                                        <i class="fas fa-undo"></i> Reset
                                    </button>
                                </div>
                            </form>
                            <div id="quizResult" class="mt-3"></div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- ==================== SIDEBAR ==================== -->
            <div class="col-lg-4">
                
                <!-- Book Recommendations -->
                <?php if (count($related_books) > 0): ?>
                    <div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">
                        <div class="card-header" style="background: #ffd700; border: none; padding: 15px 20px;">
                            <h5 class="mb-0 fw-bold text-dark">
                                <i class="fas fa-book-open"></i> Recommended Books
                            </h5>
                        </div>
                        <div class="card-body p-3">
                            <?php foreach($related_books as $book): ?>
                                <div class="d-flex mb-3 align-items-start p-2" style="border-radius: 12px; transition: background 0.3s ease;">
                                    <?php if ($book['cover_image']): ?>
                                        <img src="<?php echo SITE_URL . $book['cover_image']; ?>" 
                                             style="width: 50px; height: 70px; object-fit: cover; border-radius: 6px;" 
                                             alt="<?php echo htmlspecialchars($book['title']); ?>">
                                    <?php else: ?>
                                        <div style="width: 50px; height: 70px; background: #eee; border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-book text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="ms-2">
                                        <h6 class="mb-0" style="font-size: 0.9rem;"><?php echo htmlspecialchars($book['title']); ?></h6>
                                        <small class="text-muted">by <?php echo htmlspecialchars($book['author']); ?></small>
                                        <br>
                                        <small>
                                            <?php if ($book['pdf_link']): ?>
                                                <a href="<?php echo $book['pdf_link']; ?>" target="_blank" class="text-success text-decoration-none">
                                                    <i class="fas fa-file-pdf"></i> Read
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($book['purchase_link']): ?>
                                                <a href="<?php echo $book['purchase_link']; ?>" target="_blank" class="text-primary text-decoration-none ms-2">
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
                    <div class="card border-0 shadow-sm mt-4" style="border-radius: 16px; overflow: hidden;">
                        <div class="card-header" style="background: #3498db; border: none; padding: 15px 20px;">
                            <h5 class="mb-0 text-white">
                                <i class="fas fa-arrow-right"></i> Related Lessons
                            </h5>
                        </div>
                        <div class="card-body p-3">
                            <?php foreach($related_lessons as $related): ?>
                                <div class="mb-2 p-2" style="border-radius: 12px; transition: background 0.3s ease;">
                                    <a href="lessons-details.php?id=<?php echo $related['id']; ?>" 
                                       class="text-decoration-none text-dark">
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
                
                <!-- Progress Tracking -->
                <?php if (isLoggedIn()): ?>
                    <div class="card border-0 shadow-sm mt-4" style="border-radius: 16px; overflow: hidden;">
                        <div class="card-header" style="background: #9b59b6; border: none; padding: 15px 20px;">
                            <h5 class="mb-0 text-white">
                                <i class="fas fa-chart-line"></i> Your Progress
                            </h5>
                        </div>
                        <div class="card-body p-3">
                            <?php
                            $stmt = $pdo->prepare("SELECT * FROM user_progress WHERE user_id = ? AND lesson_id = ?");
                            $stmt->execute([$_SESSION['user_id'], $id]);
                            $progress = $stmt->fetch();
                            ?>
                            <?php if ($progress && $progress['is_completed']): ?>
                                <div class="text-center">
                                    <div class="display-1 text-success">✅</div>
                                    <h6 class="fw-bold text-success">Completed!</h6>
                                    <small class="text-muted">Completed: <?php echo formatDate($progress['last_accessed']); ?></small>
                                    <div class="progress mt-2" style="height: 6px; border-radius: 10px;">
                                        <div class="progress-bar bg-success" style="width: 100%; border-radius: 10px;"></div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <p class="text-muted text-center mb-0">Complete the quiz to mark this lesson as done.</p>
                                <div class="progress mt-2" style="height: 6px; border-radius: 10px;">
                                    <div class="progress-bar bg-warning" style="width: 0%; border-radius: 10px;"></div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- CONFETTI CANVAS (Hidden) -->
<!-- ============================================================ -->
<canvas id="confettiCanvas" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 99999; display: none;"></canvas>

<!-- ============================================================ -->
<!-- STYLES -->
<!-- ============================================================ -->
<style>
.lesson-content {
    font-size: 1.05rem;
    line-height: 1.8;
    color: #333;
}
.lesson-content p {
    margin-bottom: 1rem;
}
.quiz-question {
    transition: all 0.3s ease;
}
.quiz-question.correct {
    border-color: #2ecc71 !important;
    background: rgba(46, 204, 113, 0.05);
}
.quiz-question.incorrect {
    border-color: #e74c3c !important;
    background: rgba(231, 76, 60, 0.05);
}
.quiz-option:checked + label {
    font-weight: 600;
    color: #0a0a2e;
}
.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.08) !important;
}
</style>

<!-- ============================================================ -->
<!-- JAVASCRIPT -->
<!-- ============================================================ -->
<script>
// ============================================================
// READING PROGRESS BAR
// ============================================================
document.addEventListener('scroll', function() {
    const scrollTop = window.scrollY;
    const docHeight = document.documentElement.scrollHeight - window.innerHeight;
    const progress = (scrollTop / docHeight) * 100;
    document.getElementById('readingProgress').style.width = progress + '%';
});

// ============================================================
// QUIZ SYSTEM
// ============================================================
function checkQuiz() {
    const questions = document.querySelectorAll('.quiz-question');
    let score = 0;
    let total = questions.length;
    let allAnswered = true;
    
    // Store correct answers from PHP
    const correctAnswers = {
        <?php foreach($quiz_questions as $q): ?>
            <?php echo $q['id']; ?>: '<?php echo $q['correct_answer']; ?>',
        <?php endforeach; ?>
    };
    
    questions.forEach((q) => {
        const selected = q.querySelector('input:checked');
        const feedback = q.querySelector('.quiz-feedback');
        const questionId = q.querySelector('.quiz-option').name.replace('question_', '');
        
        // Remove previous classes
        q.classList.remove('correct', 'incorrect');
        
        if (!selected) {
            allAnswered = false;
            feedback.innerHTML = '<span class="text-warning"><i class="fas fa-exclamation-triangle"></i> Please select an answer</span>';
            return;
        }
        
        const correct = correctAnswers[questionId];
        if (selected.value === correct) {
            q.classList.add('correct');
            feedback.innerHTML = '<span class="text-success"><i class="fas fa-check-circle"></i> Correct!</span>';
            score++;
        } else {
            q.classList.add('incorrect');
            const explanation = <?php echo json_encode(array_column($quiz_questions, 'explanation', 'id')); ?>;
            const expText = explanation[questionId] ? explanation[questionId] : 'Try reviewing the lesson again.';
            feedback.innerHTML = `<span class="text-danger"><i class="fas fa-times-circle"></i> Incorrect. ${expText}</span>`;
        }
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
    let emoji = '';
    
    if (percentage >= 80) { 
        grade = 'Excellent! 🌟'; 
        color = 'success'; 
        emoji = '🎉';
        // Trigger confetti!
        if (score === total) {
            launchConfetti();
        }
    } else if (percentage >= 60) { 
        grade = 'Good job! 👍'; 
        color = 'info'; 
    } else if (percentage >= 40) { 
        grade = 'Keep learning! 📚'; 
        color = 'warning'; 
    } else { 
        grade = 'Review the lesson again. 🔄'; 
        color = 'danger'; 
    }
    
    document.getElementById('quizResult').innerHTML = `
        <div class="alert alert-${color} alert-dismissible fade show" style="border-radius: 12px;">
            <h6 class="mb-1">${emoji} Score: ${score}/${total} (${percentage}%)</h6>
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
                    // Update progress indicator
                    const progressCard = document.querySelector('.card-header[style*="background: #9b59b6"]');
                    if (progressCard) {
                        progressCard.closest('.card').querySelector('.card-body').innerHTML = `
                            <div class="text-center">
                                <div class="display-1 text-success">✅</div>
                                <h6 class="fw-bold text-success">Completed!</h6>
                                <small class="text-muted">Great job finishing this lesson!</small>
                                <div class="progress mt-2" style="height: 6px; border-radius: 10px;">
                                    <div class="progress-bar bg-success" style="width: 100%; border-radius: 10px;"></div>
                                </div>
                            </div>
                        `;
                    }
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
    document.querySelectorAll('.quiz-question').forEach(el => {
        el.classList.remove('correct', 'incorrect');
    });
    document.getElementById('quizResult').innerHTML = '';
}

// ============================================================
// CONFETTI EFFECT
// ============================================================
function launchConfetti() {
    const canvas = document.getElementById('confettiCanvas');
    const ctx = canvas.getContext('2d');
    canvas.style.display = 'block';
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
    
    const particles = [];
    const colors = ['#ffd700', '#f39c12', '#2ecc71', '#3498db', '#e74c3c', '#9b59b6', '#1abc9c'];
    
    for (let i = 0; i < 150; i++) {
        particles.push({
            x: Math.random() * canvas.width,
            y: Math.random() * canvas.height * 0.5 - canvas.height,
            width: Math.random() * 10 + 5,
            height: Math.random() * 6 + 3,
            color: colors[Math.floor(Math.random() * colors.length)],
            speed: Math.random() * 3 + 2,
            rotation: Math.random() * 360,
            rotationSpeed: Math.random() * 10 - 5,
            opacity: 1
        });
    }
    
    let frame = 0;
    const maxFrames = 200;
    
    function animateConfetti() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        
        particles.forEach(p => {
            p.y += p.speed;
            p.rotation += p.rotationSpeed;
            p.opacity = Math.max(0, 1 - (frame / maxFrames) * 1.5);
            
            ctx.save();
            ctx.translate(p.x, p.y);
            ctx.rotate(p.rotation * Math.PI / 180);
            ctx.globalAlpha = p.opacity;
            ctx.fillStyle = p.color;
            ctx.fillRect(-p.width/2, -p.height/2, p.width, p.height);
            ctx.restore();
        });
        
        frame++;
        
        if (frame < maxFrames) {
            requestAnimationFrame(animateConfetti);
        } else {
            canvas.style.display = 'none';
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }
    }
    
    animateConfetti();
}

// Reset confetti on window resize
window.addEventListener('resize', function() {
    const canvas = document.getElementById('confettiCanvas');
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
});
</script>

<?php require_once '../includes/footer.php'; ?>