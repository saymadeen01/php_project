<?php
require_once('../config/db.php');

if (!isset($_GET['id'])) {
    echo "Transport ID not provided.";
    exit;
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM transport WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$transport = $result->fetch_assoc();

if (!$transport) {
    echo "Transport not found.";
    exit;
}

$status = $transport['status'];
$progress = 0;

switch ($status) {
    case 'Pending': $progress = 25; break;
    case 'In Transit': $progress = 65; break;
    case 'Delivered': $progress = 100; break;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Track Transport</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .track-card { max-width: 700px; margin: 0 auto; margin-top: 50px; border-radius: 1rem; }
  </style>
</head>
<body>
<div class="container">
  <div class="card shadow track-card p-4">
    <h4 class="mb-4">📦 Tracking Transport ID: <?= $id ?></h4>
    <p><strong>Vehicle:</strong> <?= htmlspecialchars($transport['vehicle']) ?></p>
    <p><strong>Driver:</strong> <?= htmlspecialchars($transport['driver']) ?></p>
    <p><strong>From:</strong> <?= htmlspecialchars($transport['origin']) ?> → <strong>To:</strong> <?= htmlspecialchars($transport['destination']) ?></p>
    <p><strong>Status:</strong> <?= $status ?></p>

    <div class="progress mb-3" style="height: 30px;">
      <div class="progress-bar progress-bar-striped progress-bar-animated" 
           role="progressbar" 
           style="width: <?= $progress ?>%;" 
           aria-valuenow="<?= $progress ?>" 
           aria-valuemin="0" 
           aria-valuemax="100">
        <?= $progress ?>%
      </div>
    </div>

    <?php if ($progress < 100): ?>
      <div class="alert alert-info">Your package is on its way. Please wait...</div>
    <?php else: ?>
      <div class="alert alert-success">Your package has been successfully delivered!</div>
    <?php endif; ?>

    <a href="index.php" class="btn btn-secondary mt-3">🔙 Back to Transport</a>
  </div>
</div>
</body>
</html>
