<?php session_start(); if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
include 'includes/header.php'; ?>
<div class="container my-5" style="max-width:800px;">
  <h2>Tentang Blog</h2>
  <p>Blog ini dibuat untuk projek belajar.</p>
</div>
<?php include 'includes/footer.php'; ?>
