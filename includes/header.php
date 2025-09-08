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
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- Custom CSS -->
  <style>
    :root {
      --pastel-pink: #FFE5E5;
      --pastel-blue: #E5F4FF;
      --pastel-green: #E8F8E8;
      --pastel-yellow: #FFF8E1;
      --pastel-purple: #F3E5F5;
      --pastel-peach: #FFE8D6;
      --soft-gray: #F8F9FA;
      --text-primary: #4A5568;
      --text-secondary: #718096;
      --accent-color: #667EEA;
      --success-color: #68D391;
      --warning-color: #F6AD55;
    }

    * {
      box-sizing: border-box;
    }

    html, body {
      height: 100%;
      margin: 0;
      font-family: 'Nunito', sans-serif;
      background: linear-gradient(135deg, var(--pastel-blue) 0%, var(--pastel-pink) 100%);
      min-height: 100vh;
    }

    body {
      display: flex;
      flex-direction: column;
      color: var(--text-primary);
    }

    main {
      flex: 1;
      padding-top: 2rem;
    }

    /* Navbar Styling */
    .navbar {
      background: linear-gradient(135deg, #667EEA 0%, #764BA2 100%) !important;
      backdrop-filter: blur(10px);
      box-shadow: 0 8px 32px rgba(102, 126, 234, 0.15);
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      padding: 1rem 0;
      z-index: 2000; /* Ensure navbar is above other content */
    }

    .navbar-brand {
      font-weight: 700;
      font-size: 1.8rem;
      color: white !important;
      text-decoration: none;
      position: relative;
    }

    .navbar-brand:hover {
      color: var(--pastel-yellow) !important;
      transform: scale(1.05);
      transition: all 0.3s ease;
    }

    .navbar-brand::after {
      content: '✨';
      margin-left: 8px;
      animation: sparkle 2s infinite;
    }

    @keyframes sparkle {
      0%, 100% { transform: scale(1) rotate(0deg); opacity: 0.8; }
      50% { transform: scale(1.2) rotate(180deg); opacity: 1; }
    }

    .navbar-nav .nav-link {
      color: rgba(255, 255, 255, 0.9) !important;
      font-weight: 500;
      margin: 0 0.5rem;
      padding: 0.5rem 1rem !important;
      border-radius: 20px;
      transition: all 0.3s ease;
      position: relative;
    }

    .navbar-nav .nav-link:hover {
      background: rgba(255, 255, 255, 0.1);
      color: var(--pastel-yellow) !important;
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(255, 255, 255, 0.2);
    }

    .navbar-nav .nav-link:before {
      content: '';
      position: absolute;
      bottom: -5px;
      left: 50%;
      width: 0;
      height: 2px;
      background: var(--pastel-yellow);
      transition: all 0.3s ease;
      transform: translateX(-50%);
    }

    .navbar-nav .nav-link:hover:before {
      width: 80%;
    }

    .dropdown-menu {
      background: rgba(255, 255, 255, 0.98);
      backdrop-filter: blur(15px);
      border: 1px solid rgba(102, 126, 234, 0.2);
      border-radius: 15px;
      box-shadow: 0 8px 32px rgba(102, 126, 234, 0.15);
      padding: 0.8rem;
      min-width: 200px;
      z-index: 2000; /* Higher than navbar */
      position: absolute;
    }

    .navbar-nav .dropdown {
      position: static; /* Allows dropdown to extend beyond navbar bounds */
    }

    @media (min-width: 992px) {
      .navbar-nav .dropdown {
        position: relative; /* Reset to relative on desktop */
      }
    }

    .dropdown-item {
      border-radius: 10px;
      padding: 0.8rem 1.2rem;
      transition: all 0.3s ease;
      color: var(--text-primary);
      font-weight: 500;
      text-decoration: none;
      display: flex;
      align-items: center;
      margin-bottom: 0.3rem;
      z-index: 9999;
    }

    .dropdown-item:hover {
      background: linear-gradient(135deg, var(--accent-color), #764BA2);
      color: white;
      transform: translateX(8px) scale(1.02);
      box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
    }

    .dropdown-item:focus {
      background: linear-gradient(135deg, var(--accent-color), #764BA2);
      color: white;
    }

    .dropdown-item i {
      width: 20px;
      margin-right: 0.8rem;
      font-size: 1.1rem;
    }

    .navbar-text {
      color: rgba(255, 255, 255, 0.9) !important;
      font-weight: 600;
      background: rgba(255, 255, 255, 0.1);
      padding: 0.5rem 1rem;
      border-radius: 20px;
      backdrop-filter: blur(10px);
    }

    .user-info {
      display: flex;
      align-items: center;
    }

    /* Card Styling */
    .post-card img {
      width: 280px;
      height: 180px;
      object-fit: cover;
      border-radius: 15px;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .post-card img:hover {
      transform: scale(1.02);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .post-row {
      display: flex;
      gap: 20px;
      margin-bottom: 30px;
      border-bottom: 2px solid transparent;
      background: linear-gradient(white, white) padding-box, 
                  linear-gradient(135deg, var(--pastel-pink), var(--pastel-blue)) border-box;
      padding-bottom: 20px;
      border-radius: 20px;
      overflow: hidden;
    }

    .post-row .post-content {
      flex: 1;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .navbar-brand {
        font-size: 1.5rem;
      }
      
      .post-row {
        flex-direction: column;
      }
      
      .post-card img {
        width: 100%;
        max-width: 280px;
        margin: 0 auto;
        display: block;
      }
    }

    /* Loading animation untuk transisi halus */
    body {
      opacity: 0;
      animation: fadeIn 0.5s ease forwards;
    }

    @keyframes fadeIn {
      to { opacity: 1; }
    }

    /* Navbar toggler styling */
    .navbar-toggler {
      border: 2px solid rgba(255, 255, 255, 0.3);
      border-radius: 10px;
      padding: 0.5rem;
    }

    .navbar-toggler:focus {
      box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.25);
    }

    .navbar-toggler-icon {
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.8%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='m4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand" href="index.php">BlogD</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-controls="nav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="nav" class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link" href="index.php">
            <i class="fas fa-home me-1"></i>Beranda
          </a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-folder me-1"></i>Kategori
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="index.php?category=Ide"><i class="fas fa-lightbulb me-2"></i>Ide</a></li>
            <li><a class="dropdown-item" href="index.php?category=Bertanya-tanya"><i class="fas fa-question-circle me-2"></i>Bertanya-tanya</a></li>
            <li><a class="dropdown-item" href="index.php?category=Random"><i class="fas fa-dice me-2"></i>Random</a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="about.php">
            <i class="fas fa-info-circle me-1"></i>Tentang
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="contact.php">
            <i class="fas fa-envelope me-1"></i>Kontak
          </a>
        </li>
        <?php if ($is_admin): ?>
          <li class="nav-item">
            <a class="nav-link" href="manage.php">
              <i class="fas fa-cog me-1"></i>Konten
            </a>
          </li>
        <?php endif; ?>
      </ul>
      <ul class="navbar-nav">
        <?php if (isset($_SESSION['user_id'])): ?>
          <li class="nav-item user-info">
            <span class="navbar-text me-3">
              <i class="fas fa-user me-1"></i>Halo, <?php echo htmlspecialchars($_SESSION['username']); ?>
            </span>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="logout.php">
              <i class="fas fa-sign-out-alt me-1"></i>Logout
            </a>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link" href="login.php">
              <i class="fas fa-sign-in-alt me-1"></i>Login
            </a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<main>