<?php
require_once('../config/db.php');

// Check if ID is present
if (!isset($_GET['id'])) {
    die("❌ Error: Grade ID not provided.");
}

$id = $_GET['id'];

// Fetch grade data
$stmt = $conn->prepare("SELECT * FROM grades WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    die("❌ Error: No grade found for ID $id.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Grade</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <h2 class="mb-4">🌾 Edit Crop Grade</h2>

  <form action="update-grade.php" method="POST">
    <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">

    <div class="mb-3">
      <label for="crop_name" class="form-label">Crops Name</label>
      <input type="text" name="crop_name" class="form-control" value="<?= htmlspecialchars($row['crop_name'] ?? '') ?>" required>
    </div>

    <div class="mb-3">
      <label for="grade" class="form-label">Grade</label>
      <select name="grade" class="form-select" required>
        <option value="A" <?= ($row['grade'] ?? '') === 'A' ? 'selected' : '' ?>>A</option>
        <option value="B" <?= ($row['grade'] ?? '') === 'B' ? 'selected' : '' ?>>B</option>
        <option value="C" <?= ($row['grade'] ?? '') === 'C' ? 'selected' : '' ?>>C</option>
        <option value="D" <?= ($row['grade'] ?? '') === 'D' ? 'selected' : '' ?>>D</option>
        <option value="F" <?= ($row['grade'] ?? '') === 'F' ? 'selected' : '' ?>>F</option>
      </select>
    </div>

    <div class="mb-3">
      <label for="subject" class="form-label">Subject</label>
      <input type="text" name="subject" class="form-control" value="<?= htmlspecialchars($row['subject'] ?? '') ?>" required>
    </div>

    <div class="mb-3">
      <label for="date" class="form-label">Date</label>
      <input type="date" name="date" class="form-control" value="<?= htmlspecialchars($row['date'] ?? '') ?>" required>
    </div>

    <button type="submit" class="btn btn-success">✅ Update</button>
    <a href="index.php" class="btn btn-secondary">↩️ Cancel</a>
  </form>
</div>
</body>
</html>
