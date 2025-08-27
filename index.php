<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
require 'includes/db.php';
include 'includes/header.php';

// Ambil semua post + jumlah like & komen
$sql = "SELECT p.*,
        (SELECT COUNT(*) FROM likes l WHERE l.post_id = p.id) AS like_count,
        (SELECT COUNT(*) FROM comments c WHERE c.post_id = p.id) AS comment_count
        FROM posts p
        ORDER BY p.created_at DESC";
$res = mysqli_query($conn, $sql);
?>

<div class="container my-4">
  <div class="row">
    <?php while ($row = mysqli_fetch_assoc($res)): ?>
      <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
          <?php 
          $img_path = 'uploads/' . $row['image'];
          if (!empty($row['image']) && file_exists($img_path)): ?>
            <img src="<?php echo $img_path; ?>" class="card-img-top" style="height:200px;object-fit:cover;" alt="gambar">
          <?php else: ?>
            <img src="https://via.placeholder.com/280x180?text=No+Image" class="card-img-top" style="height:200px;object-fit:cover;" alt="no-image">
            <?php if (!empty($row['image'])): ?>
              <small class="text-danger">File <?php echo $row['image']; ?> tidak ditemukan di uploads/</small>
            <?php endif; ?>
          <?php endif; ?>

          <div class="card-body d-flex flex-column">
            <h5 class="card-title"><a href="post.php?id=<?php echo $row['id']; ?>" class="text-decoration-none text-dark">
              <?php echo htmlspecialchars($row['title']); ?>
            </a></h5>
            <p class="card-text">
              <?php echo htmlspecialchars(mb_substr(strip_tags($row['content']), 0, 150)) . "..."; ?>
            </p>
            <div class="mt-auto d-flex justify-content-between text-muted">
              <span>👍 <?php echo (int)$row['like_count']; ?> Like</span>
              <span>💬 <?php echo (int)$row['comment_count']; ?> Komentar</span>
            </div>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
