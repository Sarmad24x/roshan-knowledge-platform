<?php
$page_title = 'Register';
$current_page = 'register';
require_once '../config/database.php';
require_once '../includes/functions.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $full_name = sanitize($_POST['full_name'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if (empty($username) || empty($email) || empty($full_name) || empty($password)) {
        $error = 'Please fill in all required fields.';
    } elseif (strlen($username) < 3) {
        $error = 'Username must be at least 3 characters.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        
        if ($stmt->rowCount() > 0) {
            $error = 'Username or email already exists.';
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, full_name, role, is_approved) 
                                   VALUES (?, ?, ?, ?, 'student', 0)");
            
            if ($stmt->execute([$username, $email, $password_hash, $full_name])) {
                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['username'] = $username;
                $_SESSION['user_role'] = 'student';
                $_SESSION['user_full_name'] = $full_name;
                header('Location: ' . SITE_URL . 'index.php?registered=1');
                exit();
            } else {
                $error = 'Registration failed. Please try again.';
            }
        }
    }
}

require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>

<!-- ============================================================ -->
<!-- REGISTRATION PAGE WITH ENHANCED UI -->
<!-- ============================================================ -->
<section class="auth-section" style="min-height: calc(100vh - 200px); background: var(--primary-gradient); display: flex; align-items: center; padding: 60px 0;">
    
    <!-- Floating Shapes Background -->
    <div class="auth-shapes">
        <div class="auth-shape auth-shape-1"></div>
        <div class="auth-shape auth-shape-2"></div>
        <div class="auth-shape auth-shape-3"></div>
    </div>
    
    <div class="container position-relative" style="z-index: 1;">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                
                <!-- Logo/Brand -->
                <div class="text-center mb-4 animate__animated animate__fadeInDown">
                    <a href="<?php echo SITE_URL; ?>" class="text-decoration-none">
                        <h1 class="display-4 fw-bold text-warning">
                            <i class="fas fa-lightbulb"></i> Roshan
                        </h1>
                    </a>
                    <p class="text-white-50">Join the movement! Create your account</p>
                </div>
                
                <!-- Registration Card -->
                <div class="auth-card animate__animated animate__fadeInUp" style="background: rgba(255,255,255,0.05); backdrop-filter: blur(20px); border: 1px solid rgba(255,215,0,0.15); border-radius: 24px; padding: 40px; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
                    
                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show shake-animation" role="alert" style="border-radius: 12px;">
                            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="" class="needs-validation" novalidate>
                        <!-- Progress Steps -->
                        <div class="registration-progress mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="step active" data-step="1">
                                    <span class="step-number">1</span>
                                    <span class="step-label">Account</span>
                                </div>
                                <div class="step-line"></div>
                                <div class="step" data-step="2">
                                    <span class="step-number">2</span>
                                    <span class="step-label">Profile</span>
                                </div>
                                <div class="step-line"></div>
                                <div class="step" data-step="3">
                                    <span class="step-number">3</span>
                                    <span class="step-label">Done</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row g-3">
                            <!-- Step 1: Account Info -->
                            <div class="step-content active" data-step="1">
                                <div class="mb-3">
                                    <label class="form-label text-white fw-bold">Username <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-transparent border-end-0 text-warning" style="border-color: rgba(255,255,255,0.2);">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <input type="text" name="username" class="form-control bg-transparent border-start-0 text-white" 
                                               placeholder="Choose a username" required minlength="3"
                                               style="border-color: rgba(255,255,255,0.2);">
                                    </div>
                                    <div class="invalid-feedback">Username must be at least 3 characters.</div>
                                    <small class="text-white-50">Must be at least 3 characters</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label text-white fw-bold">Email Address <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-transparent border-end-0 text-warning" style="border-color: rgba(255,255,255,0.2);">
                                            <i class="fas fa-envelope"></i>
                                        </span>
                                        <input type="email" name="email" class="form-control bg-transparent border-start-0 text-white" 
                                               placeholder="Enter your email" required
                                               style="border-color: rgba(255,255,255,0.2);">
                                    </div>
                                    <div class="invalid-feedback">Please enter a valid email.</div>
                                </div>
                            </div>
                            
                            <!-- Step 2: Profile Info -->
                            <div class="step-content" data-step="2" style="display: none;">
                                <div class="mb-3">
                                    <label class="form-label text-white fw-bold">Full Name <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-transparent border-end-0 text-warning" style="border-color: rgba(255,255,255,0.2);">
                                            <i class="fas fa-user-circle"></i>
                                        </span>
                                        <input type="text" name="full_name" class="form-control bg-transparent border-start-0 text-white" 
                                               placeholder="Enter your full name" required
                                               style="border-color: rgba(255,255,255,0.2);">
                                    </div>
                                    <div class="invalid-feedback">Please enter your full name.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label text-white fw-bold">Password <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-transparent border-end-0 text-warning" style="border-color: rgba(255,255,255,0.2);">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input type="password" name="password" id="password" class="form-control bg-transparent border-start-0 text-white" 
                                               placeholder="Create a password" required minlength="6"
                                               style="border-color: rgba(255,255,255,0.2);">
                                        <button type="button" id="togglePassword" class="input-group-text bg-transparent border-start-0 text-white-50" 
                                                style="border-color: rgba(255,255,255,0.2); cursor: pointer;">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="invalid-feedback">Password must be at least 6 characters.</div>
                                    <div id="passwordStrength" class="mt-2">
                                        <div class="progress" style="height: 4px; background: rgba(255,255,255,0.1);">
                                            <div id="strengthBar" class="progress-bar" role="progressbar" style="width: 0%; transition: width 0.3s ease;"></div>
                                        </div>
                                        <small id="strengthText" class="text-white-50">Enter a password to check strength</small>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label text-white fw-bold">Confirm Password <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-transparent border-end-0 text-warning" style="border-color: rgba(255,255,255,0.2);">
                                            <i class="fas fa-check-circle"></i>
                                        </span>
                                        <input type="password" name="confirm_password" id="confirmPassword" class="form-control bg-transparent border-start-0 text-white" 
                                               placeholder="Confirm your password" required
                                               style="border-color: rgba(255,255,255,0.2);">
                                    </div>
                                    <div id="passwordMatch" class="mt-1"></div>
                                    <div class="invalid-feedback">Passwords must match.</div>
                                </div>
                            </div>
                            
                            <!-- Step 3: Done -->
                            <div class="step-content" data-step="3" style="display: none;">
                                <div class="text-center py-4">
                                    <div class="display-1 text-success mb-3">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <h3 class="text-white fw-bold">Almost Done!</h3>
                                    <p class="text-white-50">You're about to join the Roshan community.</p>
                                    <p class="text-white-50 small">By creating an account, you agree to our Terms of Service.</p>
                                    <div class="form-check mt-3">
                                        <input type="checkbox" class="form-check-input" id="terms" required>
                                        <label class="form-check-label text-white-50" for="terms">
                                            I agree to the <a href="#" class="text-warning">Terms of Service</a> and <a href="#" class="text-warning">Privacy Policy</a>
                                        </label>
                                        <div class="invalid-feedback">You must agree to the terms.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Navigation Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" id="prevStep" class="btn btn-outline-light rounded-pill px-4" style="display: none;">
                                <i class="fas fa-arrow-left"></i> Back
                            </button>
                            <button type="button" id="nextStep" class="btn btn-warning rounded-pill px-5 py-2">
                                Next <i class="fas fa-arrow-right"></i>
                            </button>
                            <button type="submit" id="submitBtn" class="btn btn-success rounded-pill px-5 py-2" style="display: none;">
                                <i class="fas fa-user-plus"></i> Create Account
                            </button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-4">
                        <p class="text-white-50 small">
                            Already have an account? <a href="<?php echo SITE_URL; ?>login.php" class="text-warning fw-bold text-decoration-none">Login here</a>
                        </p>
                    </div>
                </div>
                
                <!-- Benefits -->
                <div class="row g-3 mt-3">
                    <div class="col-md-4">
                        <div class="text-center text-white-50">
                            <i class="fas fa-graduation-cap fa-2x text-warning mb-2 d-block"></i>
                            <small>Free Access</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center text-white-50">
                            <i class="fas fa-flag-checkered fa-2x text-warning mb-2 d-block"></i>
                            <small>Track Progress</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center text-white-50">
                            <i class="fas fa-star fa-2x text-warning mb-2 d-block"></i>
                            <small>Earn Badges</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- REGISTRATION PAGE STYLES -->
<!-- ============================================================ -->
<style>
.auth-shapes {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 0;
    pointer-events: none;
    overflow: hidden;
}

.auth-shape {
    position: absolute;
    border-radius: 50%;
    opacity: 0.05;
    animation: float-shape 25s infinite ease-in-out;
}

.auth-shape-1 {
    width: 400px;
    height: 400px;
    background: #ffd700;
    top: -10%;
    right: -5%;
    animation-delay: 0s;
}

.auth-shape-2 {
    width: 250px;
    height: 250px;
    background: #3498db;
    bottom: -10%;
    left: -5%;
    animation-delay: -8s;
}

.auth-shape-3 {
    width: 150px;
    height: 150px;
    background: #2ecc71;
    top: 50%;
    left: 50%;
    animation-delay: -15s;
}

/* Registration Progress */
.registration-progress {
    padding: 0 10px;
}

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    flex: 1;
    position: relative;
}

.step-number {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: rgba(255,255,255,0.1);
    border: 2px solid rgba(255,255,255,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: rgba(255,255,255,0.5);
    font-weight: 700;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.step.active .step-number {
    background: #ffd700;
    border-color: #ffd700;
    color: #0a0a2e;
}

.step.completed .step-number {
    background: #2ecc71;
    border-color: #2ecc71;
    color: #fff;
}

.step-label {
    font-size: 0.7rem;
    color: rgba(255,255,255,0.4);
    text-transform: uppercase;
    letter-spacing: 1px;
}

.step.active .step-label {
    color: #ffd700;
}

.step-line {
    flex: 1;
    height: 2px;
    background: rgba(255,255,255,0.1);
    margin: 0 5px;
    margin-bottom: 20px;
}

.step-line.completed {
    background: #2ecc71;
}

/* Form Controls */
.auth-card .form-control {
    color: white !important;
    padding: 12px 16px;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.auth-card .form-control:focus {
    border-color: #ffd700 !important;
    box-shadow: 0 0 0 0.25rem rgba(255, 215, 0, 0.15) !important;
    background: rgba(255,255,255,0.05) !important;
}

.auth-card .form-control::placeholder {
    color: rgba(255, 255, 255, 0.4);
}

.auth-card .input-group-text {
    color: rgba(255,255,255,0.6);
    padding: 12px 16px;
    border-radius: 12px 0 0 12px;
}

.auth-card .input-group .form-control {
    border-radius: 0 12px 12px 0;
}

.shake-animation {
    animation: shake 0.5s ease;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    20% { transform: translateX(-10px); }
    40% { transform: translateX(10px); }
    60% { transform: translateX(-5px); }
    80% { transform: translateX(5px); }
}

/* Custom Checkbox */
.form-check-input {
    width: 18px;
    height: 18px;
    margin-top: 2px;
    background-color: rgba(255,255,255,0.1);
    border-color: rgba(255,255,255,0.3);
    cursor: pointer;
}

.form-check-input:checked {
    background-color: #ffd700;
    border-color: #ffd700;
}

.form-check-input:focus {
    box-shadow: 0 0 0 0.25rem rgba(255, 215, 0, 0.25);
}

/* Responsive */
@media (max-width: 576px) {
    .auth-card {
        padding: 24px !important;
    }
    .auth-card .input-group-lg .form-control,
    .auth-card .input-group-lg .input-group-text {
        font-size: 0.9rem;
        padding: 10px 12px;
    }
    .step-label {
        font-size: 0.6rem;
    }
}
</style>

<!-- ============================================================ -->
<!-- REGISTRATION PAGE JAVASCRIPT -->
<!-- ============================================================ -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // ---- Step Navigation ----
    let currentStep = 1;
    const totalSteps = 3;
    const stepContents = document.querySelectorAll('.step-content');
    const steps = document.querySelectorAll('.step');
    const stepLines = document.querySelectorAll('.step-line');
    const prevBtn = document.getElementById('prevStep');
    const nextBtn = document.getElementById('nextStep');
    const submitBtn = document.getElementById('submitBtn');
    
    function updateStep(step) {
        // Hide all step contents
        stepContents.forEach(content => {
            content.style.display = 'none';
        });
        
        // Show current step content
        document.querySelector(`.step-content[data-step="${step}"]`).style.display = 'block';
        
        // Update step indicators
        steps.forEach((s, index) => {
            const stepNum = index + 1;
            s.classList.remove('active', 'completed');
            if (stepNum === step) {
                s.classList.add('active');
            } else if (stepNum < step) {
                s.classList.add('completed');
            }
        });
        
        // Update step lines
        stepLines.forEach((line, index) => {
            const lineStep = index + 1;
            line.classList.toggle('completed', lineStep < step);
        });
        
        // Update buttons
        prevBtn.style.display = step === 1 ? 'none' : 'inline-block';
        nextBtn.style.display = step === totalSteps ? 'none' : 'inline-block';
        submitBtn.style.display = step === totalSteps ? 'inline-block' : 'none';
    }
    
    if (nextBtn) {
        nextBtn.addEventListener('click', function() {
            if (currentStep < totalSteps) {
                currentStep++;
                updateStep(currentStep);
            }
        });
    }
    
    if (prevBtn) {
        prevBtn.addEventListener('click', function() {
            if (currentStep > 1) {
                currentStep--;
                updateStep(currentStep);
            }
        });
    }
    
    // ---- Toggle Password Visibility ----
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    
    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    }
    
    // ---- Password Strength Meter ----
    const strengthBar = document.getElementById('strengthBar');
    const strengthText = document.getElementById('strengthText');
    
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            
            if (password.length >= 6) strength++;
            if (password.length >= 10) strength++;
            if (/[A-Z]/.test(password) && /[a-z]/.test(password)) strength++;
            if (/\d/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            const percentages = [0, 20, 40, 60, 80, 100];
            const colors = ['#e74c3c', '#e74c3c', '#f39c12', '#f39c12', '#2ecc71', '#2ecc71'];
            const labels = ['Weak', 'Weak', 'Fair', 'Good', 'Strong', 'Very Strong'];
            
            const index = Math.min(strength, 5);
            strengthBar.style.width = percentages[index] + '%';
            strengthBar.style.background = colors[index];
            strengthText.textContent = 'Strength: ' + labels[index];
            strengthText.style.color = colors[index];
        });
    }
    
    // ---- Password Match Check ----
    const confirmPassword = document.getElementById('confirmPassword');
    const passwordMatch = document.getElementById('passwordMatch');
    
    if (confirmPassword && passwordInput) {
        confirmPassword.addEventListener('input', function() {
            if (this.value.length === 0) {
                passwordMatch.innerHTML = '';
                return;
            }
            if (this.value === passwordInput.value) {
                passwordMatch.innerHTML = '<span class="text-success"><i class="fas fa-check-circle"></i> Passwords match</span>';
            } else {
                passwordMatch.innerHTML = '<span class="text-danger"><i class="fas fa-times-circle"></i> Passwords do not match</span>';
            }
        });
    }
});
</script>

<?php require_once '../includes/footer.php'; ?>