<?php
session_start();
require 'includes/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim(mysqli_real_escape_string($conn, $_POST['username']));
    $password = trim(mysqli_real_escape_string($conn, $_POST['password']));
    $confirm_password = trim(mysqli_real_escape_string($conn, $_POST['confirm_password']));
    
    // Validasi input
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = "Semua field harus diisi!";
    } elseif ($password !== $confirm_password) {
        $error = "Konfirmasi password tidak cocok!";
    } else {
        // Cek apakah username sudah ada
        $checkUser = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
        if (mysqli_num_rows($checkUser) > 0) {
            $error = "Username sudah digunakan!";
        } else {
            // Hash password dan simpan ke database
            $hashedPassword = hashPassword($password);
            $query = "INSERT INTO users (username, password, role) VALUES ('$username', '$hashedPassword', 'user')";
            
            if (mysqli_query($conn, $query)) {
                $_SESSION['user_id'] = mysqli_insert_id($conn);
                $_SESSION['username'] = $username;
                $_SESSION['role'] = 'user'; // Default role adalah 'user'
                
                // Redirect berdasarkan role
                if ($username === 'admin') {
                    header("Location: manage.php"); // Admin menuju halaman manajemen
                } else {
                    header("Location: index.php"); // User biasa menuju homepage
                }
                exit;
            } else {
                $error = "Registrasi gagal: " . mysqli_error($conn);
            }
        }
    }
}

include 'includes/header.php';
?>

<style>
/* Register Page Styles - Konsisten dengan Login */
.register-container {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem 1rem;
  position: relative;
  background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
}

.register-container::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="10" cy="60" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="90" cy="40" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
  z-index: 0;
}

.register-card {
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 25px;
  padding: 3rem 2.5rem;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
  width: 100%;
  max-width: 450px;
  position: relative;
  z-index: 1;
  overflow: hidden;
}

.register-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 6px;
  background: linear-gradient(90deg, var(--pastel-green), var(--pastel-blue), var(--pastel-pink), var(--pastel-yellow));
  background-size: 300% 100%;
  animation: gradientShift 4s ease infinite;
}

.register-header {
  text-align: center;
  margin-bottom: 2.5rem;
}

.register-title {
  font-size: 2.2rem;
  font-weight: 700;
  color: var(--text-primary);
  margin-bottom: 0.5rem;
  background: linear-gradient(135deg, #764ba2, #667eea);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.register-subtitle {
  color: var(--text-secondary);
  font-size: 1rem;
  font-weight: 400;
  opacity: 0.8;
}

.register-form {
  width: 100%;
}

.form-group {
  margin-bottom: 1.8rem;
}

.form-label {
  display: block;
  font-weight: 600;
  color: var(--text-primary);
  margin-bottom: 0.6rem;
  font-size: 0.95rem;
}

.form-control {
  width: 100%;
  padding: 1rem 1.2rem;
  border: 2px solid rgba(102, 126, 234, 0.1);
  border-radius: 15px;
  font-size: 1rem;
  background: rgba(255, 255, 255, 0.8);
  backdrop-filter: blur(10px);
  transition: all 0.3s ease;
  color: var(--text-primary);
}

.form-control:focus {
  outline: none;
  border-color: var(--primary-color);
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
  background: rgba(255, 255, 255, 0.95);
}

.form-control::placeholder {
  color: var(--text-muted);
  opacity: 0.7;
}

.form-control.valid {
  border-color: #10b981;
  box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}

.form-control.invalid {
  border-color: #ef4444;
  box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
}

.input-group {
  position: relative;
  display: flex;
  align-items: center;
}

.input-group .form-control {
  padding-right: 3.5rem;
  border-radius: 15px;
}

.password-toggle {
  position: absolute;
  right: 0;
  top: 0;
  height: 100%;
  width: 3.5rem;
  background: none;
  border: none;
  color: var(--text-secondary);
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
  z-index: 2;
}

.password-toggle:hover {
  color: var(--primary-color);
  transform: scale(1.1);
}

.register-btn {
  width: 100%;
  padding: 1rem 2rem;
  background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
  color: blue;
  border: none;
  border-radius: 15px;
  font-size: 1.1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
  margin-bottom: 1.5rem;
}

.register-btn:hover {
  background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
  transform: translateY(-2px);
  box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
}

.register-btn:active {
  transform: translateY(0);
}

.register-btn:disabled {
  background: linear-gradient(135deg, #cbd5e0, #a0aec0);
  cursor: not-allowed;
  transform: none;
  box-shadow: none;
}

.register-btn.loading::after {
  content: '';
  display: inline-block;
  width: 16px;
  height: 16px;
  margin-left: 10px;
  border: 2px solid transparent;
  border-top: 2px solid white;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

.alert {
  padding: 1rem 1.2rem;
  border-radius: 12px;
  margin-bottom: 1.5rem;
  border: none;
  font-weight: 500;
  display: flex;
  align-items: center;
  gap: 0.8rem;
}

.alert-danger {
  background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(220, 38, 38, 0.1));
  color: #dc2626;
  border: 1px solid rgba(239, 68, 68, 0.2);
}

.alert-danger::before {
  content: '⚠️';
  font-size: 1.2rem;
}

.alert-success {
  background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.1));
  color: #059669;
  border: 1px solid rgba(16, 185, 129, 0.2);
}

.alert-success::before {
  content: '✅';
  font-size: 1.2rem;
}

.register-footer {
  text-align: center;
  margin-top: 1.5rem;
  padding-top: 1.5rem;
  border-top: 1px solid rgba(0, 0, 0, 0.1);
}

.register-footer span {
  color: var(--text-secondary);
  font-size: 0.95rem;
}

.register-footer a {
  color: var(--primary-color);
  text-decoration: none;
  font-weight: 600;
  transition: all 0.3s ease;
}

.register-footer a:hover {
  color: var(--secondary-color);
  text-decoration: underline;
}

/* Password Strength Indicator */
.password-strength {
  margin-top: 0.5rem;
  display: flex;
  gap: 0.25rem;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.password-strength.show {
  opacity: 1;
}

.strength-bar {
  height: 4px;
  flex: 1;
  background: #e5e7eb;
  border-radius: 2px;
  transition: all 0.3s ease;
}

.strength-bar.weak {
  background: #ef4444;
}

.strength-bar.medium {
  background: #f59e0b;
}

.strength-bar.strong {
  background: #10b981;
}

.strength-text {
  font-size: 0.8rem;
  margin-top: 0.25rem;
  font-weight: 500;
}

.strength-text.weak {
  color: #ef4444;
}

.strength-text.medium {
  color: #f59e0b;
}

.strength-text.strong {
  color: #10b981;
}

/* Floating Shapes untuk Register */
.register-shapes {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  pointer-events: none;
  z-index: 0;
  overflow: hidden;
}

.register-shape {
  position: absolute;
  border-radius: 50%;
  opacity: 0.1;
  animation: floatRegister 12s ease-in-out infinite;
}

.register-shape1 {
  width: 100px;
  height: 100px;
  background: rgba(255, 255, 255, 0.3);
  top: 15%;
  left: 8%;
  animation-delay: 0s;
}

.register-shape2 {
  width: 70px;
  height: 70px;
  background: rgba(255, 255, 255, 0.2);
  top: 60%;
  right: 12%;
  animation-delay: 4s;
}

.register-shape3 {
  width: 50px;
  height: 50px;
  background: rgba(255, 255, 255, 0.25);
  bottom: 25%;
  left: 20%;
  animation-delay: 8s;
}

.register-shape4 {
  width: 35px;
  height: 35px;
  background: rgba(255, 255, 255, 0.15);
  top: 25%;
  right: 25%;
  animation-delay: 2s;
}

.register-shape5 {
  width: 45px;
  height: 45px;
  background: rgba(255, 255, 255, 0.2);
  bottom: 15%;
  right: 8%;
  animation-delay: 6s;
}

@keyframes floatRegister {
  0%, 100% { 
    transform: translateY(0px) translateX(0px) rotate(0deg); 
    opacity: 0.1;
  }
  20% { 
    transform: translateY(-15px) translateX(8px) rotate(72deg); 
    opacity: 0.15;
  }
  40% { 
    transform: translateY(-25px) translateX(-5px) rotate(144deg); 
    opacity: 0.08;
  }
  60% { 
    transform: translateY(-20px) translateX(-12px) rotate(216deg); 
    opacity: 0.12;
  }
  80% { 
    transform: translateY(-10px) translateX(5px) rotate(288deg); 
    opacity: 0.14;
  }
}

@keyframes gradientShift {
  0%, 100% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* Responsive Design */
@media (max-width: 480px) {
  .register-card {
    padding: 2rem 1.5rem;
    margin: 1rem;
  }
  
  .register-title {
    font-size: 1.8rem;
  }
  
  .form-control {
    padding: 0.9rem 1rem;
  }
  
  .register-btn {
    padding: 0.9rem 1.5rem;
  }
}

/* Focus visible untuk accessibility */
.form-control:focus-visible,
.register-btn:focus-visible,
.password-toggle:focus-visible {
  outline: 2px solid var(--primary-color);
  outline-offset: 2px;
}
</style>

<div class="register-shapes">
  <div class="register-shape register-shape1"></div>
  <div class="register-shape register-shape2"></div>
  <div class="register-shape register-shape3"></div>
  <div class="register-shape register-shape4"></div>
  <div class="register-shape register-shape5"></div>
</div>

<div class="register-container">
  <div class="register-card">
    <div class="register-header">
      <h1 class="register-title">Daftar ke BlogD 🚀</h1>
      <p class="register-subtitle">Bergabunglah dengan komunitas blogger kami!</p>
    </div>
    
    <?php if ($error): ?>
      <div class="alert alert-danger">
        <?php echo htmlspecialchars($error); ?>
      </div>
    <?php endif; ?>
    
    <form method="post" class="register-form" id="register-form">
      <div class="form-group">
        <label class="form-label" for="username">Username</label>
        <input 
          type="text" 
          name="username" 
          id="username"
          class="form-control" 
          placeholder="Pilih username unik Anda"
          required
          autocomplete="username"
          minlength="3"
          maxlength="50">
      </div>
      
      <div class="form-group">
        <label class="form-label" for="password">Password</label>
        <div class="input-group">
          <input 
            type="password" 
            name="password" 
            id="password-field"
            class="form-control" 
            placeholder="Buat password yang kuat"
            required
            autocomplete="new-password"
            minlength="6">
          <button 
            class="password-toggle" 
            type="button" 
            id="toggle-password"
            aria-label="Toggle password visibility">
            <i class="fas fa-eye" id="eye-icon"></i>
          </button>
        </div>
        <div class="password-strength" id="password-strength">
          <div class="strength-bar" id="strength-1"></div>
          <div class="strength-bar" id="strength-2"></div>
          <div class="strength-bar" id="strength-3"></div>
          <div class="strength-bar" id="strength-4"></div>
        </div>
        <div class="strength-text" id="strength-text"></div>
      </div>
      
      <div class="form-group">
        <label class="form-label" for="confirm-password">Konfirmasi Password</label>
        <div class="input-group">
          <input 
            type="password" 
            name="confirm_password" 
            id="confirm-password-field"
            class="form-control" 
            placeholder="Ulangi password Anda"
            required
            autocomplete="new-password">
          <button 
            class="password-toggle" 
            type="button" 
            id="toggle-confirm-password"
            aria-label="Toggle confirm password visibility">
            <i class="fas fa-eye" id="confirm-eye-icon"></i>
          </button>
        </div>
      </div>
      
      <button type="submit" class="register-btn" id="register-btn">
        Daftar Sekarang
      </button>
    </form>
    
    <div class="register-footer">
      <span>Sudah punya akun? <a href="login.php">Masuk di sini</a></span>
    </div>
  </div>
</div>

<script>
// Toggle password visibility
document.getElementById('toggle-password').addEventListener('click', function () {
  const passwordField = document.getElementById('password-field');
  const eyeIcon = document.getElementById('eye-icon');
  
  if (passwordField.type === 'password') {
    passwordField.type = 'text';
    eyeIcon.classList.remove('fa-eye');
    eyeIcon.classList.add('fa-eye-slash');
    this.setAttribute('aria-label', 'Hide password');
  } else {
    passwordField.type = 'password';
    eyeIcon.classList.remove('fa-eye-slash');
    eyeIcon.classList.add('fa-eye');
    this.setAttribute('aria-label', 'Show password');
  }
});

// Toggle confirm password visibility
document.getElementById('toggle-confirm-password').addEventListener('click', function () {
  const confirmPasswordField = document.getElementById('confirm-password-field');
  const confirmEyeIcon = document.getElementById('confirm-eye-icon');
  
  if (confirmPasswordField.type === 'password') {
    confirmPasswordField.type = 'text';
    confirmEyeIcon.classList.remove('fa-eye');
    confirmEyeIcon.classList.add('fa-eye-slash');
    this.setAttribute('aria-label', 'Hide confirm password');
  } else {
    confirmPasswordField.type = 'password';
    confirmEyeIcon.classList.remove('fa-eye-slash');
    confirmEyeIcon.classList.add('fa-eye');
    this.setAttribute('aria-label', 'Show confirm password');
  }
});

// Password strength checker
function checkPasswordStrength(password) {
  let score = 0;
  let feedback = '';
  
  if (password.length >= 8) score++;
  if (password.match(/[a-z]/)) score++;
  if (password.match(/[A-Z]/)) score++;
  if (password.match(/[0-9]/)) score++;
  if (password.match(/[^a-zA-Z0-9]/)) score++;
  
  const strengthIndicator = document.getElementById('password-strength');
  const strengthText = document.getElementById('strength-text');
  
  // Reset bars
  for (let i = 1; i <= 4; i++) {
    const bar = document.getElementById(`strength-${i}`);
    bar.classList.remove('weak', 'medium', 'strong');
  }
  
  if (password.length > 0) {
    strengthIndicator.classList.add('show');
    
    if (score <= 2) {
      feedback = 'Password lemah';
      strengthText.className = 'strength-text weak';
      document.getElementById('strength-1').classList.add('weak');
    } else if (score <= 3) {
      feedback = 'Password sedang';
      strengthText.className = 'strength-text medium';
      for (let i = 1; i <= 2; i++) {
        document.getElementById(`strength-${i}`).classList.add('medium');
      }
    } else if (score <= 4) {
      feedback = 'Password kuat';
      strengthText.className = 'strength-text strong';
      for (let i = 1; i <= 3; i++) {
        document.getElementById(`strength-${i}`).classList.add('strong');
      }
    } else {
      feedback = 'Password sangat kuat';
      strengthText.className = 'strength-text strong';
      for (let i = 1; i <= 4; i++) {
        document.getElementById(`strength-${i}`).classList.add('strong');
      }
    }
    
    strengthText.textContent = feedback;
  } else {
    strengthIndicator.classList.remove('show');
  }
}

// Real-time password strength checking
document.getElementById('password-field').addEventListener('input', function() {
  checkPasswordStrength(this.value);
  validateForm();
});

// Form validation
function validateForm() {
  const username = document.getElementById('username');
  const password = document.getElementById('password-field');
  const confirmPassword = document.getElementById('confirm-password-field');
  
  // Username validation
  if (username.value.length >= 3) {
    username.classList.remove('invalid');
    username.classList.add('valid');
  } else if (username.value.length > 0) {
    username.classList.remove('valid');
    username.classList.add('invalid');
  } else {
    username.classList.remove('valid', 'invalid');
  }
  
  // Password confirmation validation
  if (confirmPassword.value.length > 0) {
    if (password.value === confirmPassword.value && password.value.length >= 6) {
      confirmPassword.classList.remove('invalid');
      confirmPassword.classList.add('valid');
    } else {
      confirmPassword.classList.remove('valid');
      confirmPassword.classList.add('invalid');
    }
  } else {
    confirmPassword.classList.remove('valid', 'invalid');
  }
}

// Add event listeners for validation
document.getElementById('username').addEventListener('input', validateForm);
document.getElementById('confirm-password-field').addEventListener('input', validateForm);

// Form submission with loading state
document.getElementById('register-form').addEventListener('submit', function(e) {
  const registerBtn = document.getElementById('register-btn');
  const username = document.getElementById('username').value.trim();
  const password = document.getElementById('password-field').value;
  const confirmPassword = document.getElementById('confirm-password-field').value;
  
  if (username && password && confirmPassword && password === confirmPassword) {
    registerBtn.classList.add('loading');
    registerBtn.disabled = true;
    registerBtn.textContent = 'Sedang mendaftar...';
  }
});

// Auto focus on username field
document.addEventListener('DOMContentLoaded', function() {
  const usernameField = document.getElementById('username');
  if (usernameField) {
    usernameField.focus();
  }
});
</script>

<?php include 'includes/footer.php'; ?>