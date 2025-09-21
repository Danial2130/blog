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

<style>
/* PERBAIKAN UTAMA - Layout Structure */
body {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  margin: 0;
  padding: 0;
}

/* Blog container HARUS flex: 1 dan JANGAN ada min-height */
.blog-container {
    background: #f8f9fa;
    flex: 1; /* INI YANG PALING PENTING */
    padding: 2rem 0;
    /* HILANGKAN min-height: 100vh - ini yang menyebabkan masalah */
}

.post-wrapper {
    max-width: 900px;
    margin: 0 auto 2rem auto;
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    overflow: hidden;
    animation: fadeInUp 0.8s ease;
}

.post-wrapper:last-child {
  margin-bottom: 0;
}

/* Pastikan footer tidak ada konflik */
.footer-simple {
  margin-top: 0 !important;
  background: linear-gradient(135deg, #667EEA 0%, #764BA2 100%);
  color: white;
  padding: 3rem 0 1.5rem;
  position: relative;
}

.post-hero {
    position: relative;
    height: 400px;
    overflow: hidden;
}

.post-hero img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.post-hero:hover img {
    transform: scale(1.05);
}

.post-hero-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0,0,0,0.7));
    padding: 2rem;
    color: white;
}

.post-content {
    padding: 2.5rem;
}

.post-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 1rem;
    line-height: 1.3;
}

.post-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f1f3f4;
}

.category-badge {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-size: 0.85rem;
    font-weight: 500;
    text-decoration: none;
    transition: transform 0.3s ease;
}

.category-badge:hover {
    transform: translateY(-2px);
    color: white;
}

.post-date {
    color: #6c757d;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.post-article {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #2c3e50;
    margin-bottom: 2rem;
}

.post-article p {
    margin-bottom: 1.5rem;
}

.post-article h2, .post-article h3 {
    color: #2c3e50;
    margin-top: 2rem;
    margin-bottom: 1rem;
}

.engagement-section {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 15px;
    margin-bottom: 2rem;
}

.like-button {
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
    border: none;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 50px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.like-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(255, 107, 107, 0.4);
    color: white;
}

.like-button:disabled {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    cursor: not-allowed;
}

.like-button:disabled:hover {
    transform: none;
}

.comments-section {
    margin-top: 3rem;
}

.comments-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 2rem;
    font-size: 1.5rem;
    font-weight: 600;
    color: #2c3e50;
}

.comment-item {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
    position: relative;
}

.comment-item:hover {
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.comment-item::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px 0 0 15px;
}

.comment-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.comment-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    border: 3px solid #e9ecef;
    transition: border-color 0.3s ease;
}

.comment-item:hover .comment-avatar {
    border-color: #667eea;
}

.comment-author {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.2rem;
}

.comment-date {
    font-size: 0.85rem;
    color: #6c757d;
}

.comment-text {
    color: #495057;
    line-height: 1.6;
    margin-bottom: 0;
}

.comment-form-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 15px;
    padding: 2rem;
    margin-top: 2rem;
}

.comment-form-title {
    color: #2c3e50;
    margin-bottom: 1.5rem;
    font-weight: 600;
}

.comment-textarea {
    border: 2px solid #e9ecef;
    border-radius: 12px;
    padding: 1rem;
    font-size: 1rem;
    transition: all 0.3s ease;
    resize: vertical;
    min-height: 120px;
}

.comment-textarea:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.submit-comment-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    padding: 0.75rem 2rem;
    border-radius: 50px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.submit-comment-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    color: white;
}

.back-button {
    position: fixed;
    bottom: 2rem;
    left: 2rem;
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    color: white;
    border: none;
    padding: 1rem 1.5rem;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
    z-index: 1000;
}

.back-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(108, 117, 125, 0.4);
    color: white;
}

.no-post-alert {
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
    color: white;
    border: none;
    border-radius: 15px;
    padding: 2rem;
    text-align: center;
    max-width: 600px;
    margin: 2rem auto;
}

.empty-comments {
    text-align: center;
    padding: 2rem;
    color: #6c757d;
}

.empty-comments i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

@media (max-width: 768px) {
    .blog-container {
        padding: 1rem 0;
    }
    
    .post-wrapper {
        border-radius: 15px;
        margin: 0 1rem 1rem 1rem;
    }
    
    .post-content {
        padding: 1.5rem;
    }
    
    .post-title {
        font-size: 2rem;
    }
    
    .post-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .back-button {
        bottom: 1rem;
        left: 1rem;
        padding: 0.75rem 1rem;
    }
    
    .comment-form-section {
        padding: 1.5rem;
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.fade-in {
    animation: fadeInUp 0.6s ease forwards;
}
</style>

<div class="blog-container">
  <?php if ($post): ?>
    <div class="post-wrapper">
      <!-- Hero Image Section -->
      <div class="post-hero">
        <?php 
          $img_path = 'uploads/' . $post['image'];
          if (!empty($post['image']) && file_exists($img_path)): ?>
            <img src="<?php echo $img_path; ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
        <?php else: ?>
            <img src="https://images.unsplash.com/photo-1499750310107-5fef28a66643?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2340&q=80" alt="Default blog image">
        <?php endif; ?>
      </div>
      
      <!-- Post Content -->
      <div class="post-content">
        <h1 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h1>
        
        <div class="post-meta">
          <span class="category-badge">
            <i class="fas fa-tag"></i>
            <?php echo htmlspecialchars($post['category']); ?>
          </span>
          <div class="post-date">
            <i class="fas fa-calendar-alt"></i>
            <?php echo date("d M Y", strtotime($post['created_at'])); ?>
            <span class="mx-2">•</span>
            <i class="fas fa-clock"></i>
            <?php echo date("H:i", strtotime($post['created_at'])); ?>
          </div>
        </div>
        
        <article class="post-article">
          <?php echo $post['content']; ?>
        </article>
        
        <!-- Engagement Section -->
        <div class="engagement-section">
          <button id="like-btn" class="like-button" data-post-id="<?= $id ?>">
            <i class="fas fa-heart"></i>
            <span><?= $likes ?> Like</span>
          </button>
        </div>
      </div>
    </div>
    
    <!-- Comments Section -->
    <div class="post-wrapper mt-4">
      <div class="post-content">
        <div class="comments-section">
          <div class="comments-header">
            <i class="fas fa-comments"></i>
            <span>Komentar (<?php echo mysqli_num_rows($comments); ?>)</span>
          </div>
          
          <div id="comments-container">
            <?php if (mysqli_num_rows($comments) > 0): ?>
              <?php while ($comment = mysqli_fetch_assoc($comments)): ?>
                <div class="comment-item fade-in">
                  <div class="comment-header">
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($comment['username']) ?>&background=667eea&color=ffffff&size=50" 
                         alt="<?= htmlspecialchars($comment['username']) ?>" class="comment-avatar">
                    <div>
                      <div class="comment-author"><?= htmlspecialchars($comment['username']) ?></div>
                      <div class="comment-date">
                        <i class="fas fa-clock"></i>
                        <?= date("d M Y • H:i", strtotime($comment['created_at'])) ?>
                      </div>
                    </div>
                  </div>
                  <p class="comment-text"><?= htmlspecialchars($comment['comment']) ?></p>
                </div>
              <?php endwhile; ?>
            <?php else: ?>
              <div class="empty-comments">
                <i class="fas fa-comment-slash"></i>
                <h5>Belum ada komentar</h5>
                <p>Jadilah yang pertama memberikan komentar!</p>
              </div>
            <?php endif; ?>
          </div>
        </div>
        
        <!-- Comment Form -->
        <div class="comment-form-section">
          <h5 class="comment-form-title">
            <i class="fas fa-pen"></i>
            Berikan Komentar Anda
          </h5>
          <form id="comment-form" method="post">
            <div class="mb-3">
              <textarea id="comment-text" name="comment" class="form-control comment-textarea" 
                        rows="4" required placeholder="Bagikan pemikiran Anda tentang artikel ini..."></textarea>
            </div>
            <button type="submit" class="submit-comment-btn">
              <i class="fas fa-paper-plane"></i>
              Kirim Komentar
            </button>
          </form>
        </div>
      </div>
    </div>
    
  <?php else: ?>
    <div class="no-post-alert">
      <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
      <h3>Artikel Tidak Ditemukan</h3>
      <p>Maaf, artikel yang Anda cari tidak dapat ditemukan.</p>
    </div>
  <?php endif; ?>
</div>

<!-- Back Button -->
<a href="index.php" class="back-button">
  <i class="fas fa-arrow-left"></i>
  Kembali
</a>

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
      this.innerHTML = '<i class="fas fa-check"></i><span>Sudah Disukai (' + (parseInt(this.querySelector('span').innerText.match(/\d+/)[0]) + 1) + ')</span>';
      this.disabled = true;
    } else if (data === 'unliked') {
      this.innerHTML = '<i class="fas fa-heart"></i><span>' + (parseInt(this.querySelector('span').innerText.match(/\d+/)[0]) - 1) + ' Like</span>';
      this.disabled = false;
    }
  });
});

// Comment submission
document.getElementById('comment-form').addEventListener('submit', function(e) {
  e.preventDefault();
  
  const commentText = document.getElementById('comment-text').value;
  const postId = <?= $id ?>;
  
  if (commentText.trim() === '') {
    alert('Komentar tidak boleh kosong');
    return;
  }
  
  // Disable button during submission
  const submitBtn = this.querySelector('button[type="submit"]');
  const originalText = submitBtn.innerHTML;
  submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
  submitBtn.disabled = true;
  
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
      submitBtn.innerHTML = originalText;
      submitBtn.disabled = false;
    }
  })
  .catch(error => {
    alert('Terjadi kesalahan saat mengirim komentar');
    submitBtn.innerHTML = originalText;
    submitBtn.disabled = false;
  });
});

// Add fade-in animation to new elements
document.addEventListener('DOMContentLoaded', function() {
  const elements = document.querySelectorAll('.fade-in');
  elements.forEach((el, index) => {
    el.style.animationDelay = (index * 0.1) + 's';
  });
});
</script>