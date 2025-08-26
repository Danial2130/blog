<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
require 'includes/db.php';

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    
    // Ambil gambar lama untuk dihapus dari folder uploads
    $res = mysqli_query($conn, "SELECT image FROM posts WHERE id=$id");
    $row = mysqli_fetch_assoc($res);
    if ($row && !empty($row['image']) && file_exists('uploads/' . $row['image'])) {
        unlink('uploads/' . $row['image']);
    }

    mysqli_query($conn, "DELETE FROM posts WHERE id=$id");
}

header("Location: manage.php");
exit;
?>
