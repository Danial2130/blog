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

<style>
/* Additional styles untuk halaman index */
.container {
  max-width: 1200px;
}

.post-card-enhanced {
  background: rgba(255, 255, 255, 0.9);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 25px;
  overflow: hidden;
  transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
  position: relative;
  margin-bottom: 2rem;
  z-index: 1;
}

.post-card-enhanced:hover {
  transform: translateY(-8px) scale(1.02);
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.12);
}

.post-card-enhanced::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(90deg, var(--pastel-pink), var(--pastel-blue), var(--pastel-green), var(--pastel-yellow));
  background-size: 300% 100%;
  animation: gradientShift 3s ease infinite;
}

@keyframes gradientShift {
  0%, 100% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
}

.post-image-container {
  position: relative;
  overflow: hidden;
  flex: 0 0 280px;
}

.post-image {
  width: 280px;
  height: 220px;
  object-fit: cover;
  transition: all 0.6s ease;
}

.post-card-enhanced:hover .post-image {
  transform: scale(1.1);
}

.post-image-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(45deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
  opacity: 0;
  transition: all 0.3s ease;
}

.post-card-enhanced:hover .post-image-overlay {
  opacity: 1;
}

.post-content-enhanced {
  padding: 1.8rem;
  display: flex;
  flex-direction: column;
  min-height: 220px;
  position: relative;
}

.post-title {
  color: var(--text-primary);
  font-weight: 700;
  font-size: 1.4rem;
  margin-bottom: 1rem;
  text-decoration: none;
  transition: all 0.3s ease;
  line-height: 1.4;
}

.post-title:hover {
  color: var(--accent-color);
  text-shadow: 0 2px 4px rgba(102, 126, 234, 0.2);
  text-decoration: none;
}

.post-meta {
  display: flex;
  justify-content: space-between;
  margin-bottom: 1rem;
  font-size: 0.9rem;
  color: var(--text-secondary);
}

.category-badge {
  background: linear-gradient(135deg, var(--pastel-purple), var(--pastel-pink));
  color: var(--text-primary);
  padding: 0.4rem 0.8rem;
  border-radius: 20px;
  font-weight: 600;
  font-size: 0.8rem;
  border: 1px solid rgba(255, 255, 255, 0.3);
}

.post-date {
  background: rgba(118, 75, 162, 0.1);
  padding: 0.4rem 0.8rem;
  border-radius: 20px;
  font-weight: 500;
}

.post-excerpt {
  flex: 1;
  color: var(--text-secondary);
  line-height: 1.6;
  margin-bottom: 1.5rem;
  font-size: 1rem;
}

.post-stats {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-top: 1rem;
  border-top: 1px solid rgba(0, 0, 0, 0.05);
}

.stat-item {
  display: flex;
  align-items: center;
  color: var(--text-secondary);
  font-weight: 500;
  font-size: 0.95rem;
  transition: all 0.3s ease;
}

.stat-item:hover {
  color: var(--accent-color);
  transform: scale(1.05);
}

.stat-icon {
  font-size: 1.2rem;
  margin-right: 0.5rem;
  filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
}

.welcome-header {
  text-align: center;
  margin-bottom: 3rem;
  padding: 2rem;
  background: rgba(255, 255, 255, 0.7);
  backdrop-filter: blur(10px);
  border-radius: 25px;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
  position: relative;
  z-index: 1; /* Lower than navbar dropdown */
}

.welcome-title {
  font-size: 2.5rem;
  font-weight: 700;
  color: var(--text-primary);
  margin-bottom: 1rem;
  background: linear-gradient(135deg, var(--accent-color), #764BA2);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.welcome-subtitle {
  font-size: 1.2rem;
  color: var(--text-secondary);
  font-weight: 400;
}

.no-posts {
  text-align: center;
  padding: 3rem;
  color: var(--text-secondary);
}

.no-posts-icon {
  font-size: 3rem;
  margin-bottom: 1rem;
  color: var(--pastel-purple);
}

/* Responsive Design */
@media (max-width: 768px) {
  .post-card-enhanced {
    flex-direction: column;
  }
  
  .post-image-container {
    flex: none;
  }
  
  .post-image {
    width: 100%;
    height: 250px;
  }
  
  .welcome-title {
    font-size: 2rem;
  }
  
  .welcome-subtitle {
    font-size: 1rem;
  }
}

/* Floating elements */
.floating-shapes {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  pointer-events: none;
  z-index: -1; /* Behind everything */
  overflow: hidden;
}

.shape {
  position: absolute;
  border-radius: 50%;
  opacity: 0.1;
  animation: float 6s ease-in-out infinite;
}

.shape1 {
  width: 60px;
  height: 60px;
  background: var(--pastel-pink);
  top: 20%;
  left: 10%;
  animation-delay: 0s;
}

.shape2 {
  width: 40px;
  height: 40px;
  background: var(--pastel-blue);
  top: 60%;
  right: 15%;
  animation-delay: 2s;
}

.shape3 {
  width: 80px;
  height: 80px;
  background: var(--pastel-green);
  bottom: 20%;
  left: 20%;
  animation-delay: 4s;
}

@keyframes float {
  0%, 100% { transform: translateY(0px) rotate(0deg); }
  50% { transform: translateY(-20px) rotate(180deg); }
}
</style>

<div class="floating-shapes">
  <div class="shape shape1"></div>
  <div class="shape shape2"></div>
  <div class="shape shape3"></div>
</div>

<div class="container my-4">
  <div class="welcome-header">
    <h1 class="welcome-title">Selamat Datang di BlogD ✨</h1>
    <p class="welcome-subtitle">Tempat berbagi ide, bertanya-tanya, dan hal-hal random menarik lainnya</p>
  </div>

  <?php if (mysqli_num_rows($res) > 0): ?>
    <?php while ($row = mysqli_fetch_assoc($res)): ?>
      <div class="post-card-enhanced d-flex">
        
        <!-- Gambar -->
        <div class="post-image-container">
          <?php 
          $img_path = 'uploads/' . $row['image'];
          if (!empty($row['image']) && file_exists($img_path)): ?>
            <img src="<?php echo $img_path; ?>" 
                 alt="gambar"
                 class="post-image">
          <?php else: ?>
            <img src="https://via.placeholder.com/280x220/E5F4FF/667EEA?text=BlogD" 
                 alt="no-image"
                 class="post-image">
          <?php endif; ?>
          <div class="post-image-overlay"></div>
        </div>

        <!-- Konten -->
        <div class="post-content-enhanced">
          <h5>
            <a href="post.php?id=<?php echo $row['id']; ?>" class="post-title">
              <?php echo htmlspecialchars($row['title']); ?>
            </a>
          </h5>

          <div class="post-meta">
            <span class="category-badge">
              <i class="fas fa-tag me-1"></i>
              <?php echo htmlspecialchars($row['category']); ?>
            </span>
            <span class="post-date">
              <i class="fas fa-calendar me-1"></i>
              <?php echo date('d M Y', strtotime($row['created_at'])); ?>
            </span>
          </div>

          <p class="post-excerpt">
            <?php echo htmlspecialchars(mb_substr(strip_tags($row['content']), 0, 180)) . "..."; ?>
          </p>

          <div class="post-stats">
            <div class="stat-item">
              <span class="stat-icon">👍</span>
              <span><?php echo (int)$row['like_count']; ?> Like</span>
            </div>
            <div class="stat-item">
              <span class="stat-icon">💬</span>
              <span><?php echo (int)$row['comment_count']; ?> Komentar</span>
            </div>
            <div class="stat-item">
              <span class="stat-icon">👁️</span>
              <span>Baca Selengkapnya</span>
            </div>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <div class="no-posts">
      <div class="no-posts-icon">
        <i class="fas fa-inbox"></i>
      </div>
      <h3>Belum Ada Postingan</h3>
      <p>Jadilah yang pertama untuk membuat postingan menarik!</p>
    </div>
  <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>