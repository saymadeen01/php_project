<?php
require_once('../config/db.php');

// Fetch all transport data
$result = $conn->query("SELECT id, vehicle, driver, origin, destination, latitude, longitude FROM transport");
$transports = [];

while ($row = $result->fetch_assoc()) {
    $transports[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Transport Map</title>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <style>
    #map { height: 600px; border-radius: 12px; margin-top: 20px; }
  </style>
</head>
<body>
<div class="container mt-5">
  <h3 class="mb-4">📍 Transport Location Map</h3>
  <div id="map"></div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
  var map = L.map('map').setView([23.8103, 90.4125], 6); // Center on Dhaka by default

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
  }).addTo(map);

  const transports = <?= json_encode($transports) ?>;

  transports.forEach(t => {
    if (t.latitude && t.longitude) {
      L.marker([t.latitude, t.longitude])
        .addTo(map)
        .bindPopup(`<strong>${t.vehicle}</strong><br>Driver: ${t.driver}<br>${t.origin} ➜ ${t.destination}`);
    }
  });
</script>
</body>
</html>
