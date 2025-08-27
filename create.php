<?php
session_start();
if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit; 
}

// hanya admin yg boleh
$is_admin = isset($_SESSION['role']) ? ($_SESSION['role']==='admin') : (isset($_SESSION['username']) && $_SESSION['username']==='admin');
if (!$is_admin) { 
    header("Location: index.php"); 
    exit; 
}

require 'includes/db.php';

define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5 MB

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $image = "";

    if (!empty($_FILES['image']['name'])) {
        $file = $_FILES['image'];

        // cek error upload
        if ($file['error'] !== 0) {
            die("<p style='color:red;'>Upload gagal! Error code: {$file['error']}</p>");
        }

        // cek ukuran file
        if ($file['size'] > MAX_FILE_SIZE) {
            die("<p style='color:red;'>File terlalu besar! Maksimal 5 MB</p>");
        }

        // cek tipe file
        $allowed_types = ['image/jpeg','image/jpg','image/png','image/gif'];
        if (!in_array($file['type'], $allowed_types)) {
            die("<p style='color:red;'>File bukan gambar yang valid!</p>");
        }

        // nama file unik
        $name = time() . "_" . preg_replace("/[^A-Za-z0-9_.-]/", "", $file['name']);
        $tmp  = $file['tmp_name'];

        // folder uploads harus sudah ada
        $target_dir = __DIR__ . '/uploads/';
        if (!is_dir($target_dir)) {
            die("<p style='color:red;'>Folder uploads/ tidak ada. Silakan buat folder uploads/ dan beri permission writable!</p>");
        }
        if (!is_writable($target_dir)) {
            die("<p style='color:red;'>Folder uploads/ tidak writable. Cek permission!</p>");
        }

        $target_file = $target_dir . $name;
        if (move_uploaded_file($tmp, $target_file)) {
            $image = $name;
        } else {
            die("<p style='color:red;'>Upload gagal! Cek permission folder uploads/ dan php.ini upload_max_filesize.</p>");
        }
    }

    // simpan ke database
    $query = "INSERT INTO posts (title, content, image) VALUES ('$title','$content','$image')";
    if (mysqli_query($conn, $query)) {
        header("Location: index.php");
        exit;
    } else {
        echo "<p style='color:red;'>Gagal simpan ke database: " . mysqli_error($conn) . "</p>";
    }
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
            <label class="form-label">Gambar (opsional, max 5MB)</label>
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
