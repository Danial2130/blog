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
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);
    
    if (!empty($comment)) {
        mysqli_query($conn, "INSERT INTO comments (post_id, user_id, comment) VALUES ($postId, $userId, '$comment')");
        echo 'success';
    } else {
        echo 'error';
    }
}
exit;