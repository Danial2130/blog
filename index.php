<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
require 'includes/db.php';
include 'includes/header.php';

/*
  Ambil post + jumlah like & komentar
  (butuh tabel likes & comments seperti rancangan)
*/
$sql = "SELECT p.*,
        (SELECT COUNT(*) FROM likes l WHERE l.post_id = p.id) AS like_count,
        (SELECT COUNT(*) FROM comments c WHERE c.post_id = p.id) AS comment_count
        FROM posts p
        ORDER BY p.created_at DESC";
$res = mysqli_query($conn, $sql);
?>
<div class="container my-4">
  <?php while ($row = mysqli_fetch_assoc($res)): ?>
    <div class="post-row">
      <?php if (!empty($row['image'])): ?>
        <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="gambar">
      <?php else: ?>
        <img src="https://via.placeholder.com/280x180?text=No+Image" alt="no-image">
      <?php endif; ?>
      <div class="post-content">
        <h4><a href="post.php?id=<?php echo $row['id']; ?>" class="text-decoration-none text-dark">
          <?php echo htmlspecialchars($row['title']); ?>
        </a></h4>
        <p class="post-excerpt">
          <?php echo htmlspecialchars(mb_substr(strip_tags($row['content']), 0, 150)) . "..."; ?>
        </p>
        <div class="d-flex justify-content-between text-muted">
          <span>👍 <?php echo (int)$row['like_count']; ?> Like</span>
          <span>💬 <?php echo (int)$row['comment_count']; ?> Komentar</span>
        </div>
      </div>
    </div>
  <?php endwhile; ?>
</div>

<?php include 'includes/footer.php'; ?>
