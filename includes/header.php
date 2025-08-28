<?php
session_start();
$is_admin = (isset($_SESSION['username']) && $_SESSION['username'] === 'admin');
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BlogD</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom CSS -->
  <style>
    html, body {
      height: 100%;
      margin: 0;
    }
    body {
      display: flex;
      flex-direction: column;
    }
    main {
      flex: 1;
    }
    .navbar-nav .user-info {
      display: flex;
      align-items: center;
    }
    .post-card img {
      width: 280px;
      height: 180px;
      object-fit: cover;
      border-radius: 8px;
    }
    .post-row {
      display: flex;
      gap: 20px;
      margin-bottom: 30px;
      border-bottom: 1px solid #ddd;
      padding-bottom: 20px;
    }
    .post-row .post-content {
      flex: 1;
    }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="index.php">BlogD</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="nav" class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li>
        <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
         Kategori</a>
      <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="index.php?category=Ide">Ide</a></li>
        <li><a class="dropdown-item" href="index.php?category=Bertanya-tanya">Bertanya-tanya</a></li>
        <li><a class="dropdown-item" href="index.php?category=Random">Random</a></li>
      </ul>
        </li>
        <li class="nav-item"><a class="nav-link" href="#">Favorit</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php">Tentang</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Kontak</a></li>
        <?php if ($is_admin): ?>
          <li class="nav-item"><a class="nav-link" href="manage.php">Konten</a></li>
        <?php endif; ?>
      </ul>
      <ul class="navbar-nav">
        <?php if (isset($_SESSION['user_id'])): ?>
          <li class="nav-item user-info">
            <span class="navbar-text me-3">Halo, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
          </li>
          <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<main>
