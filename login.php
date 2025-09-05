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

<div class="container" style="max-width: 420px">
  <h3 class="mt-5 mb-3 text-center">Masuk ke Blog</h3>
  <?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
  <form method="post" class="card p-4 shadow-sm">
    <div class="mb-3">
      <label class="form-label">Username</label>
      <input name="username" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Password</label>
      <div class="input-group">
        <input type="password" name="password" class="form-control" id="password-field" required>
        <button class="btn btn-outline-secondary" type="button" id="toggle-password">
          <i class="fas fa-eye" id="eye-icon"></i>
        </button>
      </div>
    </div>
    <button class="btn btn-primary w-100">Login</button>
  </form>
  <div class="text-center mt-3">
    <span>Belum punya akun? <a href="register.php">Daftar di sini</a></span>
  </div>
</div>
<?php include 'includes/footer.php'; ?>

<!-- Tambahkan script JavaScript -->
<script>
document.getElementById('toggle-password').addEventListener('click', function () {
  const passwordField = document.getElementById('password-field');
  const eyeIcon = document.getElementById('eye-icon');
  
  if (passwordField.type === 'password') {
    passwordField.type = 'text';
    eyeIcon.classList.remove('fa-eye');
    eyeIcon.classList.add('fa-eye-slash');
  } else {
    passwordField.type = 'password';
    eyeIcon.classList.remove('fa-eye-slash');
    eyeIcon.classList.add('fa-eye');
  }
});
</script>