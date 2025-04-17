<?php
require_once('../config/db.php');

// Initialize with empty values to avoid undefined key warnings
$package = [
    'id' => '',
    'package_name' => '',
    'weight' => '',
    'destination' => '',
    'packaged_date' => ''
];

// Fetch package by ID
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM packages WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $package = $result->fetch_assoc();
    } else {
        echo "<div class='alert alert-danger m-3'>⚠️ Package not found!</div>";
        exit;
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['package_name'];
    $weight = $_POST['weight'];
    $destination = $_POST['destination'];
    $date = $_POST['packaged_date'];

    $stmt = $conn->prepare("UPDATE packages SET package_name=?, weight=?, destination=?, packaged_date=? WHERE id=?");
    $stmt->bind_param("sdssi", $name, $weight, $destination, $date, $id);

    if ($stmt->execute()) {
        header("Location: index.php?updated=1");
        exit;
    } else {
        echo "<div class='alert alert-danger m-3'>Update failed: " . $stmt->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Package</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .form-container {
      max-width: 600px;
      margin: 50px auto;
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>

<div class="form-container">
  <h3 class="mb-4">📦 Edit Package</h3>
  <form method="POST">
    <input type="hidden" name="id" value="<?= htmlspecialchars($package['id']) ?>">

    <div class="mb-3">
      <label class="form-label">Package Name</label>
      <input type="text" name="package_name" class="form-control" value="<?= htmlspecialchars($package['package_name']) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Weight (kg)</label>
      <input type="number" step="0.01" name="weight" class="form-control" value="<?= htmlspecialchars($package['weight']) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Destination</label>
      <input type="text" name="destination" class="form-control" value="<?= htmlspecialchars($package['destination']) ?>" required>
    </div>

    <div class="mb-4">
      <label class="form-label">Packaged Date</label>
      <input type="date" name="packaged_date" class="form-control" value="<?= htmlspecialchars($package['packaged_date']) ?>" required>
    </div>

    <button type="submit" class="btn btn-primary">✅ Update Package</button>
    <a href="index.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>

</body>
</html>
