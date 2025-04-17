<?php
require_once('../config/db.php');

$package = [
    'package_name' => 'N/A',
    'weight' => 'N/A',
    'destination' => 'N/A',
    'packaged_date' => 'N/A'
];

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM packages WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $package = $result->fetch_assoc();
    } else {
        echo "<div class='alert alert-danger m-3'>⚠️ No package found with ID $id.</div>";
        exit;
    }
} else {
    echo "<div class='alert alert-warning m-3'>⚠️ No package ID provided.</div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Package Details</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .card {
      max-width: 600px;
      margin: 50px auto;
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>
<div class="container mt-5">
  <h3 class="mb-4 text-center">📦 Package Details</h3>

  <div class="card">
    <div class="card-body">
      <h5 class="card-title"><?= htmlspecialchars($package['package_name'] ?? 'N/A') ?></h5>
      <p class="card-text"><strong>Weight:</strong> <?= htmlspecialchars($package['weight'] ?? 'N/A') ?> kg</p>
      <p class="card-text"><strong>Destination:</strong> <?= htmlspecialchars($package['destination'] ?? 'N/A') ?></p>
      <p class="card-text"><strong>Packaged Date:</strong> <?= htmlspecialchars($package['packaged_date'] ?? 'N/A') ?></p>
      <a href="index.php" class="btn btn-primary mt-3">⬅️ Back to Dashboard</a>
    </div>
  </div>
</div>
</body>
</html>
