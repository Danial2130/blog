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

<div class="container" style="max-width: 420px">
  <h3 class="mt-5 mb-3 text-center">Daftar Akun Baru</h3>
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
    <div class="mb-3">
      <label class="form-label">Konfirmasi Password</label>
      <div class="input-group">
        <input type="password" name="confirm_password" class="form-control" id="confirm-password-field" required>
        <button class="btn btn-outline-secondary" type="button" id="toggle-confirm-password">
          <i class="fas fa-eye" id="confirm-eye-icon"></i>
        </button>
      </div>
    </div>
    <button class="btn btn-primary w-100">Daftar</button>
  </form>
  <div class="text-center mt-3">
    <span>Sudah punya akun? <a href="login.php">Masuk di sini</a></span>
  </div>
</div>
<?php include 'includes/footer.php'; ?>

<!-- Tambahkan script JavaScript -->
<script>
// Toggle password field
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

// Toggle confirm password field
document.getElementById('toggle-confirm-password').addEventListener('click', function () {
  const confirmPasswordField = document.getElementById('confirm-password-field');
  const confirmEyeIcon = document.getElementById('confirm-eye-icon');
  
  if (confirmPasswordField.type === 'password') {
    confirmPasswordField.type = 'text';
    confirmEyeIcon.classList.remove('fa-eye');
    confirmEyeIcon.classList.add('fa-eye-slash');
  } else {
    confirmPasswordField.type = 'password';
    confirmEyeIcon.classList.remove('fa-eye-slash');
    confirmEyeIcon.classList.add('fa-eye');
  }
});
</script>