<?php
$page_title = 'Contact Us';
$current_page = 'contact';
require_once '../config/database.php';
require_once '../includes/functions.php';

$message_sent = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $subject = sanitize($_POST['subject'] ?? '');
    $message = sanitize($_POST['message'] ?? '');
    $type = sanitize($_POST['type'] ?? 'student');
    
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO contacts (name, email, subject, message, type, created_at) 
                                   VALUES (?, ?, ?, ?, ?, NOW())");
            $stmt->execute([$name, $email, $subject, $message, $type]);
            $message_sent = true;
        } catch (PDOException $e) {
            $error = 'An error occurred. Please try again later.';
        }
    }
}

require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>

<!-- Page Header -->
<div style="background: var(--primary-gradient); padding: 50px 0 30px 0; margin-top: -1px;">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-8 mx-auto">
                <h1 class="display-4 fw-bold text-white">
                    <i class="fas fa-envelope text-warning"></i> Contact Us
                </h1>
                <p class="lead text-white-50">We'd love to hear from you</p>
            </div>
        </div>
    </div>
</div>

<!-- Contact Content -->
<div style="padding: 40px 0;">
    <div class="container">
        <?php if ($message_sent): ?>
            <div class="alert alert-success alert-dismissible fade show" style="border-radius: 16px;">
                <i class="fas fa-check-circle"></i> 
                Your message has been sent successfully! We'll get back to you soon.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" style="border-radius: 16px;">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <div class="row g-4">
            <!-- Contact Form -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm" style="border-radius: 20px; overflow: hidden;">
                    <div class="card-body p-4">
                        <h4 class="card-title fw-bold">Send a Message</h4>
                        <p class="text-muted small">Fill out the form below and we'll get back to you.</p>
                        
                        <form method="POST" action="" class="needs-validation" novalidate>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                    <div class="invalid-feedback">Please enter your name.</div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                    <div class="invalid-feedback">Please enter a valid email.</div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="type" class="form-label">I am a...</label>
                                    <select class="form-select" id="type" name="type">
                                        <option value="student">Student</option>
                                        <option value="teacher">Teacher/Scholar</option>
                                        <option value="parent">Parent</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="subject" name="subject" required>
                                    <div class="invalid-feedback">Please enter a subject.</div>
                                </div>
                                
                                <div class="col-12">
                                    <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                                    <div class="invalid-feedback">Please enter your message.</div>
                                </div>
                                
                                <div class="col-12">
                                    <button type="submit" class="btn btn-warning btn-lg w-100 rounded-pill">
                                        <i class="fas fa-paper-plane"></i> Send Message
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Contact Info -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 20px; overflow: hidden;">
                    <div class="card-body p-4">
                        <h4 class="card-title fw-bold">Get in Touch</h4>
                        <p class="text-muted small">
                            We're here to help and answer any questions you might have.
                        </p>
                        
                        <div class="mt-4">
                            <div class="d-flex align-items-center mb-3 p-3" style="background: #f8f9fa; border-radius: 12px;">
                                <div class="me-3 text-warning">
                                    <i class="fas fa-envelope fa-2x"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Email</h6>
                                    <p class="small text-muted mb-0">admin@roshan.com</p>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-center mb-3 p-3" style="background: #f8f9fa; border-radius: 12px;">
                                <div class="me-3 text-warning">
                                    <i class="fas fa-map-marker-alt fa-2x"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Location</h6>
                                    <p class="small text-muted mb-0">Balochistan, Pakistan</p>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-center p-3" style="background: #f8f9fa; border-radius: 12px;">
                                <div class="me-3 text-warning">
                                    <i class="fas fa-clock fa-2x"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Office Hours</h6>
                                    <p class="small text-muted mb-0">Monday - Friday, 9:00 AM - 5:00 PM</p>
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <h6>Follow Us</h6>
                        <div class="d-flex gap-3">
                            <a href="#" class="text-dark" style="transition: all 0.3s ease;"><i class="fab fa-facebook fa-2x"></i></a>
                            <a href="#" class="text-dark" style="transition: all 0.3s ease;"><i class="fab fa-twitter fa-2x"></i></a>
                            <a href="#" class="text-dark" style="transition: all 0.3s ease;"><i class="fab fa-youtube fa-2x"></i></a>
                            <a href="#" class="text-dark" style="transition: all 0.3s ease;"><i class="fab fa-github fa-2x"></i></a>
                        </div>
                        
                        <div class="alert alert-warning mt-4 mb-0" style="border-radius: 12px;">
                            <i class="fas fa-handshake"></i>
                            <strong>Want to contribute?</strong>
                            <p class="small mb-0">Teachers and scholars are welcome to partner with us!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card.border-0.shadow-sm:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.08) !important;
}
</style>

<?php require_once '../includes/footer.php'; ?>