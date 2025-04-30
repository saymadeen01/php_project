<?php
require_once('../config/db.php');

// Fetch all packages for the table
$query = "SELECT * FROM packages";
$result = $conn->query($query);

// Fetch data for chart
$chartQuery = "SELECT type, COUNT(*) as count FROM packages GROUP BY type";
$chartResult = $conn->query($chartQuery);

$types = [];
$counts = [];

if ($chartResult && $chartResult->num_rows > 0) {
    while ($row = $chartResult->fetch_assoc()) {
        $types[] = $row['type'];
        $counts[] = $row['count'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>📦 Package Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    .chart-container {
      width: 100%;
      max-width: 500px;
      height: 350px;
      margin: 0 auto 40px;
    }
    canvas {
      height: 100% !important;
      width: 100% !important;
    }
  </style>
</head>
<body>
<div class="container mt-5">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2>📦 Packaging Dashboard</h2>
    <a href="add-package.php" class="btn btn-success">➕ Add New Package</a>
  </div>

  <!-- 📋 Table Section -->
  <div class="table-responsive mb-5">
    <table class="table table-bordered table-striped table-hover shadow-sm">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Package Name</th>
          <th>Weight (kg)</th>
          <th>Destination</th>
          <th>Packaged Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['package_name']) ?></td>
            <td><?= $row['weight'] ?></td>
            <td><?= isset($row['destination']) ? htmlspecialchars($row['destination']) : 'N/A' ?></td>
            <td><?= isset($row['packaged_date']) ? $row['packaged_date'] : 'N/A' ?></td>
            <td>
              <a href="view.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm">🔍 View</a>
              <a href="edit-package.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">✏️ Edit</a>
              <a href="delete-package.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this package?')">🗑️ Delete</a>
            </td>
          </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="6" class="text-center text-muted">No packages found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- 📊 Chart Section (Moved Below Table) -->
  <div class="bg-white rounded shadow-sm p-4 mb-5">
    <h5 class="text-center mb-4">📊 Package Type Distribution</h5>
    <div class="chart-container">
      <canvas id="packagePieChart"></canvas>
    </div>
  </div>

</div>

<!-- Chart Script -->
<script>
  const ctx = document.getElementById('packagePieChart').getContext('2d');
  const packagePieChart = new Chart(ctx, {
    type: 'pie',
    data: {
      labels: <?= json_encode($types) ?>,
      datasets: [{
        label: 'Package Type Count',
        data: <?= json_encode($counts) ?>,
        backgroundColor: [
          '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e',
          '#e74a3b', '#6f42c1', '#20c997', '#fd7e14'
        ],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'right',
          labels: {
            boxWidth: 20,
            padding: 15
          }
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              const label = context.label || '';
              const value = context.raw || 0;
              return `${label}: ${value} packages`;
            }
          }
        }
      }
    }
  });
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
