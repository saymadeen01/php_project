<?php
require_once('../config/db.php');

if (!isset($_GET['id'])) {
    echo "Transport ID missing.";
    exit;
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM transport WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$transport = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vehicle = $_POST['vehicle'];
    $driver = $_POST['driver'];
    $origin = $_POST['origin'];
    $destination = $_POST['destination'];
    $status = $_POST['status'];
    $date = $_POST['date'];

    $update = $conn->prepare("UPDATE transport SET vehicle=?, driver=?, origin=?, destination=?, status=?, date=? WHERE id=?");
    $update->bind_param("ssssssi", $vehicle, $driver, $origin, $destination, $status, $date, $id);

    if ($update->execute()) {
        header("Location: index.php?updated=1");
        exit;
    } else {
        echo "Update failed: " . $update->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Transport</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h3 class="mb-4">✏️ Edit Transport</h3>
  <form method="POST">
    <div class="mb-3">
      <label>Vehicle</label>
      <input type="text" name="vehicle" class="form-control" value="<?= htmlspecialchars($transport['vehicle']) ?>" required>
    </div>
    
    <div class="mb-3">
      <label>Driver</label>
      <input type="text" name="driver" class="form-control" value="<?= htmlspecialchars($transport['driver']) ?>" required>
    </div>
    
    <div class="mb-3">
      <label>Origin</label>
      <input type="text" name="origin" class="form-control" value="<?= htmlspecialchars($transport['origin']) ?>" required>
    </div>

    <div class="mb-3">
      <label>Destination</label>
      <input type="text" name="destination" class="form-control" value="<?= htmlspecialchars($transport['destination']) ?>" required>
    </div>

    <div class="mb-3">
      <label>Status</label>
      <select name="status" class="form-select" required>
        <option <?= $transport['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
        <option <?= $transport['status'] == 'In Transit' ? 'selected' : '' ?>>In Transit</option>
        <option <?= $transport['status'] == 'Delivered' ? 'selected' : '' ?>>Delivered</option>
      </select>
    </div>

    <div class="mb-3">
      <label>Date</label>
      <input type="date" name="date" class="form-control" value="<?= $transport['date'] ?>" required>
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
    <a href="index.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>
</body>
</html>
