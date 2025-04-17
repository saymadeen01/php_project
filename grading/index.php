<?php
session_start();
require_once('../config/db.php');

// Fetch grade data for chart
$gradeCounts = [
  'A' => 0,
  'B' => 0,
  'C' => 0,
  'D' => 0,
  'F' => 0
];

$query = "SELECT grade, COUNT(*) as count FROM grades GROUP BY grade";
$result = $conn->query($query);

while ($row = $result->fetch_assoc()) {
  $grade = $row['grade'];
  $count = $row['count'];
  if (isset($gradeCounts[$grade])) {
    $gradeCounts[$grade] = $count;
  }
}

// Fetch all grade records
$grades = $conn->query("SELECT * FROM grades ORDER BY date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Grading Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    .main-content { margin-left: 220px; padding: 2rem; }
    .action-btns button { margin-right: 5px; }
  </style>
</head>
<body>

<?php include('../includes/sidebar.php'); ?>

<div class="main-content">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3>📋 Grade List</h3>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addGradeModal">➕ Add Grade</button>
  </div>

  <!-- Chart Section -->
  <div class="mb-5">
    <canvas id="gradeChart" height="100"></canvas>
  </div>

  <!-- Grade Table -->
  <table class="table table-bordered table-striped">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Crops Name</th>
        <th>Grade</th>
        <th>Description</th>
        <th>Date</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $grades->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['crop_name']) ?></td>
          <td><?= $row['grade'] ?></td>
          <td><?= htmlspecialchars($row['subject']) ?></td>
          <td><?= $row['date'] ?></td>
          <td class="action-btns">
            <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">✏️ Edit</a>
            <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">🗑️ Delete</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<!-- Add Grade Modal -->
<div class="modal fade" id="addGradeModal" tabindex="-1" aria-labelledby="addGradeLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="add-grade.php" method="POST" class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="addGradeLabel">Add New Grade</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label>Crops Name</label>
          <input type="text" name="crop_name" class="form-control" required>
        </div>
        <div class="mb-3">
          <label>Grade</label>
          <select name="grade" class="form-control" required>
            <option value="">Select Grade</option>
            <option>A</option>
            <option>B</option>
            <option>C</option>
            <option>D</option>
            <option>F</option>
          </select>
        </div>
        <div class="mb-3">
          <label>Subject</label>
          <input type="text" name="subject" class="form-control" required>
        </div>
        <div class="mb-3">
          <label>Date</label>
          <input type="date" name="date" class="form-control" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">💾 Save</button>
      </div>
    </form>
  </div>
</div>

<!-- ChartJS Script -->
<script>
  const ctx = document.getElementById('gradeChart').getContext('2d');
  const gradeChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['A', 'B', 'C', 'D', 'F'],
      datasets: [{
        label: 'Grade Distribution',
        data: [
          <?= $gradeCounts['A'] ?>,
          <?= $gradeCounts['B'] ?>,
          <?= $gradeCounts['C'] ?>,
          <?= $gradeCounts['D'] ?>,
          <?= $gradeCounts['F'] ?>
        ],
        backgroundColor: [
          '#28a745', '#17a2b8', '#ffc107', '#fd7e14', '#dc3545'
        ],
        borderColor: '#000',
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: { beginAtZero: true }
      }
    }
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
