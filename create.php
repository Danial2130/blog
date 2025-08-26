<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

// hanya admin yg boleh
$is_admin = isset($_SESSION['role']) ? ($_SESSION['role']==='admin') : (isset($_SESSION['username']) && $_SESSION['username']==='admin');
if (!$is_admin) { header("Location: index.php"); exit; }

require 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = mysqli_real_escape_string($conn, $_POST['title']);
  $content = mysqli_real_escape_string($conn, $_POST['content']);
  $image = "";

  if (!empty($_FILES['image']['name'])) {
    $name = time() . "_" . preg_replace("/[^A-Za-z0-9_.-]/","", $_FILES['image']['name']);
    $tmp  = $_FILES['image']['tmp_name'];
    if (!is_dir('uploads')) mkdir('uploads', 0777, true);
    move_uploaded_file($tmp, "uploads/$name");
    $image = $name;
  }

  mysqli_query($conn, "INSERT INTO posts (title, content, image) VALUES ('$title','$content','$image')");
  header("Location: index.php");
  exit;
}

include 'includes/header.php';
?>
<div class="container my-4" style="max-width:700px;">
  <h3 class="mb-3">Tambah Konten</h3>
  <form method="post" enctype="multipart/form-data" class="card p-4 shadow-sm">
    <div class="mb-3">
      <label class="form-label">Judul</label>
      <input class="form-control" name="title" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Gambar (opsional)</label>
      <input type="file" name="image" class="form-control" accept="image/*">
    </div>
    <div class="mb-3">
      <label class="form-label">Isi</label>
      <textarea name="content" rows="8" class="form-control" required></textarea>
    </div>
    <button class="btn btn-success">Simpan</button>
  </form>
</div>
<?php include 'includes/footer.php'; ?>
