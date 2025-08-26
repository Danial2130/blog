<?php session_start(); if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
include 'includes/header.php'; ?>
<div class="container my-5" style="max-width:800px;">
  <h2>Kontak</h2>
  <p>Kamu bisa hubungi saya via email (isi nanti).</p>
</div>
<?php include 'includes/footer.php'; ?>
