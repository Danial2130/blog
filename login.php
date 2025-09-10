<?php
session_start();
if (isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }
require 'includes/db.php';
$error = '';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    $q = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    $u = mysqli_fetch_assoc($q);
    
    if ($u && verifyPassword($password, $u['password'])) {
        $_SESSION['user_id'] = $u['id'];
        $_SESSION['username'] = $u['username'];
        $_SESSION['role'] = $u['role']; // Simpan role di session
        
        // Redirect berdasarkan role
        if ($u['role'] === 'admin') {
            header("Location: manage.php"); // Admin menuju halaman manajemen
        } else {
            header("Location: index.php"); // User biasa menuju homepage
        }
        exit;
    } else {
        $error = "Username atau password salah";
    }
}
include 'includes/header.php';
?>

<style>
/* Login Page Styles */
.login-container {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem 1rem;
  position: relative;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.login-container::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="10" cy="60" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="90" cy="40" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
  z-index: 0;
}

.login-card {
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 25px;
  padding: 3rem 2.5rem;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
  width: 100%;
  max-width: 420px;
  position: relative;
  z-index: 1;
  overflow: hidden;
}

.login-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 6px;
  background: linear-gradient(90deg, var(--pastel-pink), var(--pastel-blue), var(--pastel-green), var(--pastel-yellow));
  background-size: 300% 100%;
  animation: gradientShift 4s ease infinite;
}

.login-header {
  text-align: center;
  margin-bottom: 2.5rem;
}

.login-title {
  font-size: 2.2rem;
  font-weight: 700;
  color: var(--text-primary);
  margin-bottom: 0.5rem;
  background: linear-gradient(135deg, #667eea, #764ba2);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.login-subtitle {
  color: var(--text-secondary);
  font-size: 1rem;
  font-weight: 400;
  opacity: 0.8;
}

.login-form {
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

.login-btn {
  width: 100%;
  padding: 1rem 2rem;
  background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
  color: white;
  border: none;
  border-radius: 15px;
  font-size: 1.1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
  margin-bottom: 1.5rem;
}

.login-btn:hover {
  background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
  transform: translateY(-2px);
  box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
}

.login-btn:active {
  transform: translateY(0);
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

.login-footer {
  text-align: center;
  margin-top: 1.5rem;
  padding-top: 1.5rem;
  border-top: 1px solid rgba(0, 0, 0, 0.1);
}

.login-footer span {
  color: var(--text-secondary);
  font-size: 0.95rem;
}

.login-footer a {
  color: var(--primary-color);
  text-decoration: none;
  font-weight: 600;
  transition: all 0.3s ease;
}

.login-footer a:hover {
  color: var(--secondary-color);
  text-decoration: underline;
}

/* Floating Shapes untuk Login */
.login-shapes {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  pointer-events: none;
  z-index: 0;
  overflow: hidden;
}

.login-shape {
  position: absolute;
  border-radius: 50%;
  opacity: 0.1;
  animation: floatLogin 10s ease-in-out infinite;
}

.login-shape1 {
  width: 120px;
  height: 120px;
  background: rgba(255, 255, 255, 0.3);
  top: 10%;
  left: 5%;
  animation-delay: 0s;
}

.login-shape2 {
  width: 80px;
  height: 80px;
  background: rgba(255, 255, 255, 0.2);
  top: 70%;
  right: 10%;
  animation-delay: 3s;
}

.login-shape3 {
  width: 60px;
  height: 60px;
  background: rgba(255, 255, 255, 0.25);
  bottom: 20%;
  left: 15%;
  animation-delay: 6s;
}

.login-shape4 {
  width: 40px;
  height: 40px;
  background: rgba(255, 255, 255, 0.15);
  top: 30%;
  right: 20%;
  animation-delay: 9s;
}

@keyframes floatLogin {
  0%, 100% { 
    transform: translateY(0px) translateX(0px) rotate(0deg); 
    opacity: 0.1;
  }
  25% { 
    transform: translateY(-20px) translateX(10px) rotate(90deg); 
    opacity: 0.15;
  }
  50% { 
    transform: translateY(-40px) translateX(-10px) rotate(180deg); 
    opacity: 0.1;
  }
  75% { 
    transform: translateY(-20px) translateX(-15px) rotate(270deg); 
    opacity: 0.12;
  }
}

@keyframes gradientShift {
  0%, 100% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
}

/* Responsive Design */
@media (max-width: 480px) {
  .login-card {
    padding: 2rem 1.5rem;
    margin: 1rem;
  }
  
  .login-title {
    font-size: 1.8rem;
  }
  
  .form-control {
    padding: 0.9rem 1rem;
  }
  
  .login-btn {
    padding: 0.9rem 1.5rem;
  }
}

/* Loading State */
.login-btn:disabled {
  background: linear-gradient(135deg, #cbd5e0, #a0aec0);
  cursor: not-allowed;
  transform: none;
  box-shadow: none;
}

.login-btn.loading::after {
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

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* Focus visible untuk accessibility */
.form-control:focus-visible,
.login-btn:focus-visible,
.password-toggle:focus-visible {
  outline: 2px solid var(--primary-color);
  outline-offset: 2px;
}
</style>

<div class="login-shapes">
  <div class="login-shape login-shape1"></div>
  <div class="login-shape login-shape2"></div>
  <div class="login-shape login-shape3"></div>
  <div class="login-shape login-shape4"></div>
</div>

<div class="login-container">
  <div class="login-card">
    <div class="login-header">
      <h1 class="login-title">Masuk ke BlogD ✨</h1>
      <p class="login-subtitle">Selamat datang kembali! Masuk untuk melanjutkan</p>
    </div>
    
    <?php if ($error): ?>
      <div class="alert alert-danger">
        <?php echo htmlspecialchars($error); ?>
      </div>
    <?php endif; ?>
    
    <form method="post" class="login-form">
      <div class="form-group">
        <label class="form-label" for="username">Username</label>
        <input 
          type="text" 
          name="username" 
          id="username"
          class="form-control" 
          placeholder="Masukkan username Anda"
          required
          autocomplete="username">
      </div>
      
      <div class="form-group">
        <label class="form-label" for="password">Password</label>
        <div class="input-group">
          <input 
            type="password" 
            name="password" 
            id="password-field"
            class="form-control" 
            placeholder="Masukkan password Anda"
            required
            autocomplete="current-password">
          <button 
            class="password-toggle" 
            type="button" 
            id="toggle-password"
            aria-label="Toggle password visibility">
            <i class="fas fa-eye" id="eye-icon"></i>
          </button>
        </div>
      </div>
      
      <button type="submit" class="login-btn" id="login-btn">
        Masuk ke BlogD
      </button>
    </form>
    
    <div class="login-footer">
      <span>Belum punya akun? <a href="register.php">Daftar di sini</a></span>
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

// Form submission with loading state
document.querySelector('.login-form').addEventListener('submit', function(e) {
  const loginBtn = document.getElementById('login-btn');
  const username = document.getElementById('username').value.trim();
  const password = document.getElementById('password-field').value;
  
  if (username && password) {
    loginBtn.classList.add('loading');
    loginBtn.disabled = true;
    loginBtn.textContent = 'Sedang masuk...';
  }
});

// Auto focus on username field
document.addEventListener('DOMContentLoaded', function() {
  const usernameField = document.getElementById('username');
  if (usernameField && !usernameField.value) {
    usernameField.focus();
  }
});

// Enhanced form validation
document.getElementById('username').addEventListener('input', function() {
  const value = this.value.trim();
  if (value.length < 3 && value.length > 0) {
    this.setCustomValidity('Username minimal 3 karakter');
  } else {
    this.setCustomValidity('');
  }
});

document.getElementById('password-field').addEventListener('input', function() {
  const value = this.value;
  if (value.length < 6 && value.length > 0) {
    this.setCustomValidity('Password minimal 6 karakter');
  } else {
    this.setCustomValidity('');
  }
});
</script>

<?php include 'includes/footer.php'; ?>