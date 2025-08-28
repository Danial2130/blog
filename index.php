<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
require 'includes/db.php';
include 'includes/header.php';

// Filter kategori jika ada
$filter = "";
if (isset($_GET['category']) && in_array($_GET['category'], ['Ide','Bertanya-tanya','Random'])) {
    $cat = mysqli_real_escape_string($conn, $_GET['category']);
    $filter = "WHERE p.category='$cat'";
}

// Ambil semua post + jumlah like & komen
$sql = "SELECT p.*,
        (SELECT COUNT(*) FROM likes l WHERE l.post_id = p.id) AS like_count,
        (SELECT COUNT(*) FROM comments c WHERE c.post_id = p.id) AS comment_count
        FROM posts p
        $filter
        ORDER BY p.created_at DESC";
$res = mysqli_query($conn, $sql);
?>

<div class="container my-4">
  <?php while ($row = mysqli_fetch_assoc($res)): ?>
    <div class="card mb-4 shadow-sm d-flex flex-row" style="overflow:hidden; border-radius:10px;">
      
      <!-- Gambar -->
      <div style="flex:0 0 250px;">
        <?php 
        $img_path = 'uploads/' . $row['image'];
        if (!empty($row['image']) && file_exists($img_path)): ?>
          <img src="<?php echo $img_path; ?>" 
               alt="gambar"
               style="width:250px; height:200px; object-fit:cover;">
        <?php else: ?>
          <img src="https://via.placeholder.com/250x200?text=No+Image" 
               alt="no-image"
               style="width:250px; height:200px; object-fit:cover;">
        <?php endif; ?>
      </div>

      <!-- Konten -->
      <div class="card-body d-flex flex-column" style="min-height:200px;">
        <h5 class="card-title mb-1">
          <a href="post.php?id=<?php echo $row['id']; ?>" class="text-decoration-none text-dark">
            <?php echo htmlspecialchars($row['title']); ?>
          </a>
        </h5>

        <div class="d-flex justify-content-between mb-2 text-muted" style="font-size:14px;">
          <span><?php echo htmlspecialchars($row['category']); ?></span>
          <span><?php echo date('d M Y', strtotime($row['created_at'])); ?></span>
        </div>

        <p class="card-text mb-2" style="flex:1;">
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
