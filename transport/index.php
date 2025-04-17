<?php
require_once('../config/db.php');

// Fetch all transport data
$result = $conn->query("SELECT * FROM transport");

// Prepare data for chart
$statusQuery = "SELECT status, COUNT(*) as total FROM transport GROUP BY status";
$statusResult = $conn->query($statusQuery);

$statuses = [];
$counts = [];

while ($row = $statusResult->fetch_assoc()) {
    $statuses[] = $row['status'];
    $counts[] = $row['total'];
}

// Prepare data for map (transport location markers)
$locations = [];
$locationQuery = $conn->query("SELECT id, vehicle, latitude, longitude, origin, destination FROM transport WHERE latitude IS NOT NULL AND longitude IS NOT NULL");

while ($row = $locationQuery->fetch_assoc()) {
    $locations[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>🚚 Transport Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <style>
    #map {
      height: 300px;
      width: 100%;
      margin-bottom: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .chart-container {
      width: 100%;
      max-width: 480px;
      margin: 0 auto 40px;
    }
  </style>
</head>
<body>
<div class="container mt-5">

  <h2 class="mb-4">🚚 Transport Dashboard</h2>
  <a href="add-transport.php" class="btn btn-success mb-4">+ Add New Transport</a>

  <!-- 📊 Transport Status Chart -->
  <div class="chart-container bg-white p-4 rounded shadow-sm">
    <h5 class="text-center mb-3">Transport Status Overview</h5>
    <canvas id="statusChart"></canvas>
  </div>

  <!-- 🗺️ Transport Map -->
  <div class="bg-white p-4 rounded shadow-sm mb-4">
    <h5 class="text-center mb-3">Live Transport Map</h5>
    <div id="map"></div>
  </div>

  <!-- 📋 Transport Table -->
  <table class="table table-bordered table-hover shadow-sm">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Vehicle</th>
        <th>Driver</th>
        <th>From</th>
        <th>To</th>
        <th>Status</th>
        <th>Date</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['vehicle']) ?></td>
          <td><?= htmlspecialchars($row['driver']) ?></td>
          <td><?= htmlspecialchars($row['origin']) ?></td>
          <td><?= htmlspecialchars($row['destination']) ?></td>
          <td><?= htmlspecialchars($row['status']) ?></td>
          <td><?= $row['date'] ?></td>
          <td>
            <a href="view-transport.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm">🔍</a>
            <a href="edit-transport.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">✏️</a>
            <a href="delete-transport.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this transport?')">🗑️</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<!-- Chart Script -->
<script>
  const ctx = document.getElementById('statusChart').getContext('2d');
  const statusChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: <?= json_encode($statuses) ?>,
      datasets: [{
        label: 'Number of Transports',
        data: <?= json_encode($counts) ?>,
        backgroundColor: ['#0d6efd', '#ffc107', '#198754'],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { display: false }
      },
      scales: {
        y: { beginAtZero: true }
      }
    }
  });
</script>

<!-- Leaflet Map Script -->
<script>
  const map = L.map('map').setView([23.685, 90.3563], 6); // Center on Bangladesh

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
  }).addTo(map);

  const locations = <?= json_encode($locations) ?>;
  locations.forEach(loc => {
    if (loc.latitude && loc.longitude) {
      L.marker([loc.latitude, loc.longitude])
        .addTo(map)
        .bindPopup(`<strong>${loc.vehicle}</strong><br>From: ${loc.origin}<br>To: ${loc.destination}`);
    }
  });
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
