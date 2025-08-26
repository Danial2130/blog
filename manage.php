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
<div class="container my-4">
  <h3 class="mb-3">Kelola Konten</h3>
  <a href="create.php" class="btn btn-success mb-3">Tambah Post</a>
  <table class="table table-bordered table-hover">
    <thead class="table-dark">
      <tr>
        <th>Judul</th>
        <th>Likes</th>
        <th>Komentar</th>
        <th>Tanggal</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = mysqli_fetch_assoc($res)): ?>
        <tr>
          <td><a href="post.php?id=<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['title']); ?></a></td>
          <td><?php echo $row['like_count']; ?></td>
          <td><?php echo $row['comment_count']; ?></td>
          <td><?php echo date("d-m-Y H:i", strtotime($row['created_at'])); ?></td>
          <td>
            <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
            <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus post ini?')">Hapus</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
<?php include 'includes/footer.php'; ?>
