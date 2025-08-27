<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
require 'includes/db.php';
include 'includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$q = mysqli_query($conn, "SELECT * FROM posts WHERE id=$id");
$post = mysqli_fetch_assoc($q);

if ($post) {
    // hitung like & komen
    $likes = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM likes WHERE post_id=$id"))[0];
    $comments = mysqli_query($conn, "SELECT c.*, u.username FROM comments c JOIN users u ON u.id=c.user_id WHERE c.post_id=$id ORDER BY c.created_at DESC");
}
?>

<div class="container my-4" style="max-width: 900px;">
  <?php if ($post): ?>
    <?php 
      $img_path = 'uploads/' . $post['image'];
      if (!empty($post['image']) && file_exists($img_path)): ?>
        <img src="<?php echo $img_path; ?>" class="img-fluid rounded mb-3" alt="gambar">
    <?php else: ?>
        <img src="https://via.placeholder.com/900x400?text=No+Image" class="img-fluid rounded mb-3" alt="no-image">
        <?php if (!empty($post['image'])): ?>
          <small class="text-danger">File <?php echo $post['image']; ?> tidak ditemukan di uploads/</small>
        <?php endif; ?>
    <?php endif; ?>

    <h1 class="mb-3"><?php echo htmlspecialchars($post['title']); ?></h1>
    <div class="mb-4 text-muted">Dipost: <?php echo date("d M Y H:i", strtotime($post['created_at'])); ?></div>
    <article class="mb-4" style="line-height:1.8">
      <?php echo nl2br(htmlspecialchars($post['content'])); ?>
    </article>

    <div class="d-flex justify-content-between align-items-center border-top pt-3 mb-3">
      <div>👍 <?php echo (int)$likes; ?> Like</div>
      <div>💬 <?php echo mysqli_num_rows($comments); ?> Komentar</div>
    </div>

    

  <?php else: ?>
    <div class="alert alert-danger">Posting tidak ditemukan.</div>
  <?php endif; ?>
  <a href="index.php" class="btn btn-secondary mt-2">← Kembali</a>
</div>

<?php include 'includes/footer.php'; ?>
