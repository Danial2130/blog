<?php
session_start();
if (isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }
require 'includes/db.php';

$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $username = mysqli_real_escape_string($conn, $_POST['username']);
  $password = mysqli_real_escape_string($conn, $_POST['password']);

  $q = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' LIMIT 1");
  $u = mysqli_fetch_assoc($q);

  // pakai plain password sesuai setup kamu
  if ($u && $password === $u['password']) {
    $_SESSION['user_id'] = $u['id'];
    $_SESSION['username'] = $u['username'];
    $_SESSION['role'] = isset($u['role']) ? $u['role'] : 'user'; // fallback jika kolom role belum ada
    header("Location: index.php");
    exit;
  } else {
    $error = "Username atau password salah";
  }
}
include 'includes/header.php';
?>
<div class="container" style="max-width:420px">
  <h3 class="mt-5 mb-3 text-center">Masuk ke Blog</h3>
  <?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
  <form method="post" class="card p-4 shadow-sm">
    <div class="mb-3">
      <label class="form-label">Username</label>
      <input name="username" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Password</label>
      <input type="password" name="password" class="form-control" required>
    </div>
    <button class="btn btn-primary w-100">Login</button>
  </form>
</div>
<?php include 'includes/footer.php'; ?>
