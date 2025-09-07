<?php
session_start();
if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit; 
}
require 'includes/db.php';
include 'includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$q = mysqli_query($conn, "SELECT * FROM posts WHERE id=$id");
$post = mysqli_fetch_assoc($q);

if ($post) {
    // Hitung like & komen
    $likes = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM likes WHERE post_id=$id"))[0];
    $comments = mysqli_query($conn, "
        SELECT c.*, u.username 
        FROM comments c 
        JOIN users u ON u.id=c.user_id 
        WHERE c.post_id=$id 
        ORDER BY c.created_at DESC
    ");
}
?>

<div class="container my-4" style="max-width: 1000px;">
  <?php if ($post): ?>
    <?php 
      $img_path = 'uploads/' . $post['image'];
      if (!empty($post['image']) && file_exists($img_path)): ?>
        <img src="<?php echo $img_path; ?>" class="img-fluid rounded mb-3" alt="gambar">
    <?php else: ?>
        <img src="https://via.placeholder.com/1000x400?text=No+Image" class="img-fluid rounded mb-3" alt="no-image">
        <?php if (!empty($post['image'])): ?>
          <small class="text-danger">File <?php echo htmlspecialchars($post['image']); ?> tidak ditemukan di uploads/</small>
        <?php endif; ?>
    <?php endif; ?>
    
    <h1 class="mb-2"><?php echo htmlspecialchars($post['title']); ?></h1>
    <div class="mb-4 text-muted" style="font-size:14px;">
        <span class="badge bg-primary"><?php echo htmlspecialchars($post['category']); ?></span> | 
        <?php echo date("d M Y H:i", strtotime($post['created_at'])); ?>
    </div>
    <article class="mb-4" style="line-height:1.8; font-size:1.1rem;">
      <?php echo $post['content']; ?>
    </article>
    
    <!-- Form Like -->
    <div class="mb-4">
      <button id="like-btn" class="btn btn-primary" data-post-id="<?= $id ?>">
        👍 <?= $likes ?> Like
      </button>
    </div>
    
    <!-- Daftar Komentar -->
    <h4 class="mt-4 mb-3">Komentar</h4>
    <div id="comments-container">
      <?php while ($comment = mysqli_fetch_assoc($comments)): ?>
        <div class="border-bottom pb-3 mb-3">
          <div class="d-flex">
            <div class="me-3">
              <img src="https://ui-avatars.com/api/?name=<?= urlencode($comment['username']) ?>&background=random" 
                   alt="avatar" class="rounded-circle" width="40" height="40">
            </div>
            <div>
              <div class="d-flex justify-content-between">
                <strong><?= htmlspecialchars($comment['username']) ?></strong>
                <small class="text-muted"><?= date("d M Y H:i", strtotime($comment['created_at'])) ?></small>
              </div>
              <p class="mt-1"><?= htmlspecialchars($comment['comment']) ?></p>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
    
    <!-- Form Komentar -->
    <div class="mt-4">
      <h5>Beri Komentar</h5>
      <form id="comment-form" method="post">
        <div class="mb-3">
          <textarea id="comment-text" name="comment" class="form-control" rows="3" required placeholder="Tulis komentar Anda..."></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Kirim Komentar</button>
      </form>
    </div>
  <?php else: ?>
    <div class="alert alert-danger">Posting tidak ditemukan.</div>
  <?php endif; ?>
</div>

<a href="index.php" class="btn btn-secondary mt-2">← Kembali</a>
<?php include 'includes/footer.php'; ?>

<!-- Script JavaScript untuk Like dan Komentar -->
<script>
// Like functionality
document.getElementById('like-btn').addEventListener('click', function() {
  const postId = this.getAttribute('data-post-id');
  
  fetch('like.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'post_id=' + postId
  })
  .then(response => response.text())
  .then(data => {
    if (data === 'liked') {
      this.innerHTML = '✓ Sudah Disukai (' + (parseInt(this.innerText.match(/\d+/)[0]) + 1) + ')';
      this.disabled = true;
    } else if (data === 'unliked') {
      this.innerHTML = '👍 ' + (parseInt(this.innerText.match(/\d+/)[0]) - 1) + ' Like';
      this.disabled = false;
    }
  });
});

// Comment submission
document.getElementById('comment-form').addEventListener('submit', function(e) {
  e.preventDefault();
  
  const commentText = document.getElementById('comment-text').value;
  const postId = <?= $id ?>;
  
  fetch('comment.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'post_id=' + postId + '&comment=' + encodeURIComponent(commentText)
  })
  .then(response => response.text())
  .then(data => {
    if (data === 'success') {
      location.reload();
    } else {
      alert('Gagal mengirim komentar');
    }
  });
});
</script>