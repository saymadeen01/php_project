<?php
session_start();
require_once 'config/db.php';
$isLoggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Grading System | Smart Logistics Platform</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f8f9fa;
    }

    .hero {
      background: linear-gradient(90deg, #4e4376 0%, #2b5876 100%);
      color: #fff;
      padding: 100px 20px;
      text-align: center;
      border-radius: 0 0 40px 40px;
    }

    .hero h1 {
      font-weight: 700;
      font-size: 3rem;
    }

    .hero p {
      font-size: 1.1rem;
      opacity: 0.95;
      max-width: 600px;
      margin: auto;
    }

    .btn-lg {
      padding: 0.75rem 2rem;
      font-size: 1.1rem;
    }

    .module-card {
      border-radius: 20px;
      transition: all 0.3s ease;
      background: #fff;
      border: none;
    }

    .module-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
    }

    .module-card i {
      font-size: 2rem;
      margin-bottom: 15px;
      color: #4e4376;
    }

    footer {
      background: #fff;
      padding: 30px 0;
      border-top: 1px solid #e4e4e4;
    }

    footer p {
      margin: 0;
      color: #6c757d;
    }

    @media (max-width: 768px) {
      .hero h1 {
        font-size: 2.2rem;
      }

      .hero p {
        font-size: 1rem;
      }
    }
  </style>
</head>

<body>

<!-- HERO SECTION -->
<section class="hero">
  <div class="container">
    <h1 class="mb-3">Grading & Logistics Platform</h1>
    <p class="mb-4">Manage grading, packaging, and transport — smartly and efficiently, all in one place.</p>
    <?php if ($isLoggedIn): ?>
      <a href='auth/login.php' class="btn btn-light btn-lg px-4">Go to Dashboard</a>
    <?php else: ?>
      <a href="auth/login.php" class="btn btn-outline-light btn-lg me-3 px-4">Login</a>
      <a href="auth/register.php" class="btn btn-light btn-lg px-4">Register</a>
    <?php endif; ?>
  </div>
</section>

<!-- MODULES SECTION -->
<section class="py-5">
  <div class="container">
    <h2 class="text-center mb-5 fw-bold">Explore Modules</h2>
    <div class="row g-4 justify-content-center">

      <div class="col-md-4">
        <div class="card module-card p-4 text-center shadow-sm">
          <i class="bi bi-bar-chart-line-fill"></i>
          <h4 class="mb-2">Grading</h4>
          <p>Define quality standards and manage grading records with ease.</p>
          <a href='auth/login.php' class="btn btn-outline-primary btn-sm mt-3">Access Grading</a>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card module-card p-4 text-center shadow-sm">
          <i class="bi bi-box-seam"></i>
          <h4 class="mb-2">Packaging</h4>
          <p>Efficiently assign grades and manage packaging logistics.</p>
          <a href='auth/login.php' class="btn btn-outline-success btn-sm mt-3">Access Packaging</a>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card module-card p-4 text-center shadow-sm">
          <i class="bi bi-truck-front-fill"></i>
          <h4 class="mb-2">Transport</h4>
          <p>Track vehicle routes, deliveries, and transport data.</p>
          <a href='auth/login.php' class="btn btn-outline-info btn-sm mt-3">Access Transport</a>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- FOOTER -->
<footer class="text-center mt-5">
  <div class="container">
    <p>&copy; <?= date('Y') ?> Grading & Logistics System. All rights reserved.</p>
  </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
