<?php
require_once('../config/db.php');

if (!isset($_GET['id'])) {
    echo "Missing transport ID.";
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Transport</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h3 class="mb-4">🚚 Transport Details</h3>
  <div class="card shadow rounded-4">
    <div class="card-body">
      <p><strong>Vehicle:</strong> <?= htmlspecialchars($transport['vehicle']) ?></p>
      <p><strong>Driver:</strong> <?= htmlspecialchars($transport['driver']) ?></p>
      <p><strong>Origin:</strong> <?= htmlspecialchars($transport['origin']) ?></p>
      <p><strong>Destination:</strong> <?= htmlspecialchars($transport['destination']) ?></p>
      <p><strong>Status:</strong> <?= htmlspecialchars($transport['status']) ?></p>
      <p><strong>Date:</strong> <?= htmlspecialchars($transport['date']) ?></p>
    </div>
  </div>
  <a href="index.php" class="btn btn-secondary mt-4">Back to Transport List</a>
</div>
</body>
</html>
