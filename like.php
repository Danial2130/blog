<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit('Harus login terlebih dahulu');
}

require 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postId = (int)$_POST['post_id'];
    $userId = $_SESSION['user_id'];
    
    // Cek apakah user sudah like postingan ini
    $checkLike = mysqli_query($conn, "SELECT * FROM likes WHERE post_id=$postId AND user_id=$userId");
    
    if (mysqli_num_rows($checkLike) > 0) {
        // Hapus like
        mysqli_query($conn, "DELETE FROM likes WHERE post_id=$postId AND user_id=$userId");
        echo 'unliked';
    } else {
        // Tambah like
        mysqli_query($conn, "INSERT INTO likes (post_id, user_id) VALUES ($postId, $userId)");
        echo 'liked';
    }
}
exit;