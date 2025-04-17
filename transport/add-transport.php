<?php
require_once('../config/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vehicle = $_POST['vehicle'];
    $driver = $_POST['driver'];
    $origin = $_POST['origin'];
    $destination = $_POST['destination'];
    $status = $_POST['status'];
    $date = $_POST['date'];

    $stmt = $conn->prepare("INSERT INTO transport (vehicle, driver, origin, destination, status, date) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $vehicle, $driver, $origin, $destination, $status, $date);

    if ($stmt->execute()) {
        header("Location: index.php?added=1");
        exit();
    } else {
        echo "Failed to add: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Transport</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h3 class="mb-4">➕ Add Transport</h3>
  <form method="POST">
    <div class="mb-3">
      <label>Vehicle</label>
      <input type="text" name="vehicle" class="form-control" required>
    </div>
    
    <div class="mb-3">
      <label>Driver</label>
      <input type="text" name="driver" class="form-control" required>
    </div>
    
    <div class="mb-3">
      <label>Origin</label>
      <input type="text" name="origin" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Destination</label>
      <input type="text" name="destination" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Status</label>
      <select name="status" class="form-select" required>
        <option value="Pending">Pending</option>
        <option value="In Transit">In Transit</option>
        <option value="Delivered">Delivered</option>
      </select>
    </div>

    <div class="mb-3">
      <label>Date</label>
      <input type="date" name="date" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-success">Submit</button>
    <a href="index.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>
</body>
</html>
