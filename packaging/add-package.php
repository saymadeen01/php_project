<?php
require_once('../config/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['package_name'];
    $weight = $_POST['weight'];
    $destination = $_POST['destination'];
    $date = $_POST['packaged_date'];

    $stmt = $conn->prepare("INSERT INTO packages (package_name, weight, destination, packaged_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdss", $name, $weight, $destination, $date);

    if ($stmt->execute()) {
        header("Location: index.php?added=1");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Package</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h3 class="mb-4">➕ Add New Package</h3>
  <form method="POST">
    <div class="mb-3">
      <label>Package Name</label>
      <input type="text" name="package_name" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Weight (kg)</label>
      <input type="number" step="0.01" name="weight" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Destination</label>
      <input type="text" name="destination" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Packaged Date</label>
      <input type="date" name="packaged_date" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-success">Add Package</button>
    <a href="index.php" class="btn btn-secondary">Back</a>
  </form>
</div>
</body>
</html>
