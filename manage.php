<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
$is_admin = isset($_SESSION['role']) ? ($_SESSION['role']==='admin') : ($_SESSION['username']==='admin');
if (!$is_admin) { header("Location: index.php"); exit; }
require 'includes/db.php';
include 'includes/header.php';
$q = "SELECT p.*,
      (SELECT COUNT(*) FROM likes WHERE post_id=p.id) AS like_count,
      (SELECT COUNT(*) FROM comments WHERE post_id=p.id) AS comment_count
      FROM posts p ORDER BY p.created_at DESC";
$res = mysqli_query($conn, $q);
?>

<style>
.admin-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem 0;
    margin-bottom: 2rem;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
}

.admin-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.admin-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    margin-bottom: 0;
}

.stats-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid #e9ecef;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: #667eea;
    margin-bottom: 0.5rem;
}

.stat-label {
    color: #6c757d;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.content-section {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    overflow: hidden;
    margin-bottom: 2rem;
}

.section-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 1.5rem 2rem;
    border-bottom: 1px solid #dee2e6;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #495057;
    margin-bottom: 0;
}

.btn-create {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    color: white;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    box-shadow: 0 3px 10px rgba(40, 167, 69, 0.3);
}

.btn-create:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
    color: white;
}

.btn-create i {
    margin-right: 0.5rem;
}

.posts-table {
    margin: 0;
}

.posts-table thead th {
    background: linear-gradient(135deg, #343a40 0%, #495057 100%);
    color: white;
    border: none;
    padding: 1rem;
    font-weight: 500;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
}

.posts-table tbody tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid #e9ecef;
}

.posts-table tbody tr:hover {
    background-color: #f8f9fa;
    transform: scale(1.01);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.posts-table td {
    padding: 1rem;
    vertical-align: middle;
}

.post-title-link {
    color: #495057;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s ease;
}

.post-title-link:hover {
    color: #667eea;
    text-decoration: underline;
}

.stat-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
}

.likes-badge {
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
    color: white;
}

.comments-badge {
    background: linear-gradient(135deg, #4834d4 0%, #667eea 100%);
    color: white;
}

.date-text {
    color: #6c757d;
    font-size: 0.9rem;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn-action {
    padding: 0.4rem 0.8rem;
    border-radius: 6px;
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 500;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-edit {
    background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%);
    color: #212529;
}

.btn-edit:hover {
    transform: translateY(-1px);
    box-shadow: 0 3px 8px rgba(255, 193, 7, 0.4);
    color: #212529;
}

.btn-delete {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
}

.btn-delete:hover {
    transform: translateY(-1px);
    box-shadow: 0 3px 8px rgba(220, 53, 69, 0.4);
    color: white;
}

.empty-state {
    text-align: center;
    padding: 3rem;
    color: #6c757d;
}

.empty-state i {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

@media (max-width: 768px) {
    .admin-title {
        font-size: 2rem;
    }
    
    .section-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .posts-table {
        font-size: 0.85rem;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .stats-cards {
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.content-section, .stat-card {
    animation: fadeInUp 0.6s ease forwards;
}
</style>

<div class="container my-4">
    <!-- Admin Header -->
    <div class="admin-header text-center">
        <h1 class="admin-title">
            <i class="fas fa-cogs"></i>
            Panel Admin
        </h1>
        <p class="admin-subtitle">Kelola konten dan postingan blog Anda</p>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-cards">
        <?php
        $total_posts = mysqli_num_rows($res);
        mysqli_data_seek($res, 0); // Reset result pointer
        
        $total_likes = 0;
        $total_comments = 0;
        while ($stat_row = mysqli_fetch_assoc($res)) {
            $total_likes += $stat_row['like_count'];
            $total_comments += $stat_row['comment_count'];
        }
        mysqli_data_seek($res, 0); // Reset again for main loop
        ?>
        
        <div class="stat-card">
            <div class="stat-number"><?php echo $total_posts; ?></div>
            <div class="stat-label">Total Posts</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo $total_likes; ?></div>
            <div class="stat-label">Total Likes</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo $total_comments; ?></div>
            <div class="stat-label">Total Komentar</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo $total_posts > 0 ? round($total_likes / $total_posts, 1) : 0; ?></div>
            <div class="stat-label">Rata-rata Likes</div>
        </div>
    </div>

    <!-- Content Management Section -->
    <div class="content-section">
        <div class="section-header">
            <h3 class="section-title">
                <i class="fas fa-newspaper"></i>
                Manajemen Konten
            </h3>
            <a href="create.php" class="btn-create">
                <i class="fas fa-plus"></i>
                Tambah Post Baru
            </a>
        </div>

        <?php if (mysqli_num_rows($res) > 0): ?>
            <table class="table posts-table">
                <thead>
                    <tr>
                        <th><i class="fas fa-heading"></i> Judul</th>
                        <th><i class="fas fa-heart"></i> Likes</th>
                        <th><i class="fas fa-comments"></i> Komentar</th>
                        <th><i class="fas fa-calendar"></i> Tanggal</th>
                        <th><i class="fas fa-cog"></i> Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($res)): ?>
                        <tr>
                            <td>
                                <a href="post.php?id=<?php echo $row['id']; ?>" class="post-title-link">
                                    <?php echo htmlspecialchars($row['title']); ?>
                                </a>
                            </td>
                            <td>
                                <span class="stat-badge likes-badge">
                                    <i class="fas fa-heart"></i>
                                    <?php echo $row['like_count']; ?>
                                </span>
                            </td>
                            <td>
                                <span class="stat-badge comments-badge">
                                    <i class="fas fa-comment"></i>
                                    <?php echo $row['comment_count']; ?>
                                </span>
                            </td>
                            <td>
                                <span class="date-text">
                                    <i class="fas fa-clock"></i>
                                    <?php echo date("d M Y", strtotime($row['created_at'])); ?>
                                    <br>
                                    <small><?php echo date("H:i", strtotime($row['created_at'])); ?></small>
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn-action btn-edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="delete.php?id=<?php echo $row['id']; ?>" 
                                       class="btn-action btn-delete" 
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus post ini?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h4>Belum ada postingan</h4>
                <p>Mulai dengan membuat postingan pertama Anda</p>
                <a href="create.php" class="btn-create">
                    <i class="fas fa-plus"></i>
                    Buat Post Pertama
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>