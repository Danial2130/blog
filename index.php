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
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 20px;
  overflow: hidden;
  transition: all 0.3s ease;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
  position: relative;
  margin-bottom: 2rem;
  z-index: 1;
}

.post-card-enhanced:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 45px rgba(0, 0, 0, 0.15);
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
  animation: gradientShift 4s ease infinite;
}

@keyframes gradientShift {
  0%, 100% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
}

.post-image-container {
  position: relative;
  overflow: hidden;
  flex: 0 0 300px;
  height: 100%;
}

.post-image {
  width: 300px;
  height: 100%;
  min-height: 250px;
  object-fit: cover;
  transition: transform 0.4s ease;
}

.post-card-enhanced:hover .post-image {
  transform: scale(1.05);
}

.post-image-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(45deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
  opacity: 0;
  transition: opacity 0.3s ease;
}

.post-card-enhanced:hover .post-image-overlay {
  opacity: 1;
}

.post-content-enhanced {
  padding: 2rem;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  min-height: 250px;
  position: relative;
  flex: 1;
}

.post-title {
  color: var(--text-primary);
  font-weight: 700;
  font-size: 1.5rem;
  margin-bottom: 1rem;
  text-decoration: none;
  transition: all 0.3s ease;
  line-height: 1.4;
  display: block;
}

.post-title:hover {
  color: var(--accent-color);
  text-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
  text-decoration: none;
}

.post-meta {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.2rem;
  font-size: 0.9rem;
  color: var(--text-secondary);
}

.category-badge {
  background: linear-gradient(135deg, var(--pastel-purple), var(--pastel-pink));
  color: var(--text-primary);
  padding: 0.5rem 1rem;
  border-radius: 25px;
  font-weight: 600;
  font-size: 0.8rem;
  border: 1px solid rgba(255, 255, 255, 0.3);
  display: flex;
  align-items: center;
  gap: 0.3rem;
}

.post-date {
  background: rgba(118, 75, 162, 0.1);
  padding: 0.5rem 1rem;
  border-radius: 25px;
  font-weight: 500;
  display: flex;
  align-items: center;
  gap: 0.3rem;
}

.post-excerpt {
  flex: 1;
  color: var(--text-secondary);
  line-height: 1.7;
  margin-bottom: 1.5rem;
  font-size: 1rem;
  display: -webkit-box;
  -webkit-line-clamp: 4;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
}

.post-actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-top: 1.5rem;
  border-top: 1px solid rgba(0, 0, 0, 0.08);
  margin-top: auto;
}

.action-group {
  display: flex;
  gap: 1rem;
}

.action-item {
  display: flex;
  align-items: center;
  color: var(--text-secondary);
  font-weight: 500;
  font-size: 0.95rem;
  transition: all 0.3s ease;
  cursor: pointer;
  padding: 0.6rem 1rem;
  border-radius: 20px;
  background: rgba(102, 126, 234, 0.05);
  border: 1px solid rgba(102, 126, 234, 0.1);
}

.action-item:hover {
  color: var(--accent-color);
  transform: translateY(-2px);
  background: rgba(102, 126, 234, 0.1);
  box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
}

.action-icon {
  font-size: 1.2rem;
  margin-right: 0.6rem;
  filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
}

.read-more-link {
  background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
  color: white;
  padding: 0.7rem 1.5rem;
  border-radius: 25px;
  text-decoration: none;
  font-weight: 600;
  font-size: 0.95rem;
  transition: all 0.3s ease;
  box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.read-more-link:hover {
  color: white;
  text-decoration: none;
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
  background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
}

.welcome-header {
  text-align: center;
  margin-bottom: 3rem;
  padding: 3rem 2rem;
  background: rgba(255, 255, 255, 0.8);
  backdrop-filter: blur(15px);
  border-radius: 25px;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
  position: relative;
  z-index: 1;
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.welcome-title {
  font-size: 3rem;
  font-weight: 700;
  color: var(--text-primary);
  margin-bottom: 1rem;
  background: linear-gradient(135deg, var(--accent-color), #764BA2);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  text-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.welcome-subtitle {
  font-size: 1.3rem;
  color: var(--text-secondary);
  font-weight: 400;
  opacity: 0.9;
}

.no-posts {
  text-align: center;
  padding: 4rem 2rem;
  color: var(--text-secondary);
  background: rgba(255, 255, 255, 0.8);
  backdrop-filter: blur(10px);
  border-radius: 25px;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

.no-posts-icon {
  font-size: 4rem;
  margin-bottom: 1.5rem;
  color: var(--pastel-purple);
  opacity: 0.7;
}

.no-posts h3 {
  font-size: 1.8rem;
  margin-bottom: 0.8rem;
  color: var(--text-primary);
}

.no-posts p {
  font-size: 1.1rem;
  opacity: 0.8;
}

/* Enhanced Responsive Design */
@media (max-width: 1024px) {
  .post-card-enhanced {
    flex-direction: column;
  }
  
  .post-image-container {
    flex: none;
    width: 100%;
    height: 250px;
  }
  
  .post-image {
    width: 100%;
    height: 250px;
  }
  
  .welcome-title {
    font-size: 2.5rem;
  }
}

@media (max-width: 768px) {
  .post-content-enhanced {
    padding: 1.5rem;
    min-height: auto;
  }
  
  .post-title {
    font-size: 1.3rem;
  }
  
  .welcome-title {
    font-size: 2rem;
  }
  
  .welcome-subtitle {
    font-size: 1.1rem;
  }
  
  .post-meta {
    flex-direction: column;
    gap: 0.8rem;
    align-items: flex-start;
  }
  
  .post-actions {
    flex-direction: column;
    gap: 1rem;
  }
  
  .action-group {
    width: 100%;
    justify-content: space-around;
  }
  
  .read-more-link {
    width: 100%;
    justify-content: center;
  }
}

@media (max-width: 480px) {
  .welcome-header {
    padding: 2rem 1rem;
  }
  
  .post-content-enhanced {
    padding: 1rem;
  }
  
  .post-title {
    font-size: 1.2rem;
  }
  
  .action-group {
    flex-wrap: wrap;
    justify-content: center;
  }
  
  .action-item {
    padding: 0.5rem 0.8rem;
    font-size: 0.85rem;
  }
}

/* Floating elements - kept minimal */
.floating-shapes {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  pointer-events: none;
  z-index: -1;
  overflow: hidden;
}

.shape {
  position: absolute;
  border-radius: 50%;
  opacity: 0.08;
  animation: float 8s ease-in-out infinite;
}

.shape1 {
  width: 80px;
  height: 80px;
  background: var(--pastel-pink);
  top: 20%;
  left: 10%;
  animation-delay: 0s;
}

.shape2 {
  width: 60px;
  height: 60px;
  background: var(--pastel-blue);
  top: 60%;
  right: 15%;
  animation-delay: 3s;
}

.shape3 {
  width: 100px;
  height: 100px;
  background: var(--pastel-green);
  bottom: 20%;
  left: 20%;
  animation-delay: 6s;
}

@keyframes float {
  0%, 100% { transform: translateY(0px) rotate(0deg); }
  33% { transform: translateY(-15px) rotate(120deg); }
  66% { transform: translateY(-25px) rotate(240deg); }
}

/* Improved scroll behavior */
html {
  scroll-behavior: smooth;
}

/* Loading states */
.post-card-enhanced.loading {
  opacity: 0.7;
  pointer-events: none;
}

.post-card-enhanced.loading::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(255, 255, 255, 0.5);
  z-index: 10;
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
            <img src="<?php echo htmlspecialchars($img_path); ?>" 
                 alt="<?php echo htmlspecialchars($row['title']); ?>"
                 class="post-image">
          <?php else: ?>
            <img src="https://via.placeholder.com/300x250/E5F4FF/667EEA?text=BlogD" 
                 alt="<?php echo htmlspecialchars($row['title']); ?>"
                 class="post-image">
          <?php endif; ?>
          <div class="post-image-overlay"></div>
        </div>

        <!-- Konten -->
        <div class="post-content-enhanced">
          <div>
            <h5>
              <a href="post.php?id=<?php echo $row['id']; ?>" class="post-title">
                <?php echo htmlspecialchars($row['title']); ?>
              </a>
            </h5>

            <div class="post-meta">
              <span class="category-badge">
                <i class="fas fa-tag"></i>
                <?php echo htmlspecialchars($row['category']); ?>
              </span>
              <span class="post-date">
                <i class="fas fa-calendar-alt"></i>
                <?php echo date('d M Y', strtotime($row['created_at'])); ?>
              </span>
            </div>

            <p class="post-excerpt">
              <?php echo htmlspecialchars(mb_substr(strip_tags($row['content']), 0, 200)) . "..."; ?>
            </p>
          </div>

          <div class="post-actions">
            <div class="action-group">
              <div class="action-item">
                <span class="action-icon">❤️</span>
                <span><?php echo (int)$row['like_count']; ?> Like</span>
              </div>
              <div class="action-item">
                <span class="action-icon">💬</span>
                <span><?php echo (int)$row['comment_count']; ?> Komentar</span>
              </div>
              <div class="action-item">
                <span class="action-icon">👁️</span>
                <span>Lihat</span>
              </div>
            </div>
            
            <a href="post.php?id=<?php echo $row['id']; ?>" class="read-more-link">
              <span>Baca Selengkapnya</span>
              <i class="fas fa-arrow-right"></i>
            </a>
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