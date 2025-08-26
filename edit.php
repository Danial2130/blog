<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
require 'includes/db.php';

$id = (int) $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM posts WHERE id=$id");
$post = mysqli_fetch_assoc($result);

if (!$post) {
    echo "Post tidak ditemukan.";
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    
    $image = $post['image']; // default gambar lama
    
    if (!empty($_FILES['image']['name'])) {
        $filename = time() . '_' . basename($_FILES['image']['name']);
        $target = 'uploads/' . $filename;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            // hapus gambar lama
            if (!empty($image) && file_exists('uploads/' . $image)) {
                unlink('uploads/' . $image);
            }
            $image = $filename;
        } else {
            $error = "Gagal upload gambar baru.";
        }
    }

    if (!$error) {
        $sql = "UPDATE posts SET title='$title', content='$content', image='$image' WHERE id=$id";
        mysqli_query($conn, $sql);
        header("Location: manage.php");
        exit;
    }
}

include 'includes/header.php';
?>

<div class="container mt-5" style="max-width:600px">
    <h3 class="mb-3">Edit Post</h3>
    <?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
    <form method="post" enctype="multipart/form-data" class="card p-4 shadow-sm">
        <div class="mb-3">
            <label class="form-label">Judul</label>
            <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($post['title']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Isi</label>
            <textarea name="content" class="form-control" rows="6" required><?php echo htmlspecialchars($post['content']); ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Gambar (kosongkan jika tidak ganti)</label><br>
            <?php if ($post['image']): ?>
                <img src="uploads/<?php echo $post['image']; ?>" alt="" style="max-width:100%;height:auto;margin-bottom:10px;">
            <?php endif; ?>
            <input type="file" name="image" class="form-control">
        </div>
        <button class="btn btn-primary w-100">Update</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
