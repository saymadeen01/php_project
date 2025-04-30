<?php
session_start();
require_once('config/db.php');

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
  header('Location: auth/login.php');
  exit;
}

// Fetch total grades, packages, transports, and inspectors from the database
$queryGrades = "SELECT COUNT(*) as total FROM grades";
$queryPackages = "SELECT COUNT(*) as total FROM packages";
$queryTransports = "SELECT COUNT(*) as total FROM transport";
$queryInspectors = "SELECT COUNT(*) as total FROM inspectors";

$totalGrades = mysqli_fetch_assoc(mysqli_query($conn, $queryGrades))['total'];
$totalPackages = mysqli_fetch_assoc(mysqli_query($conn, $queryPackages))['total'];
$totalTransports = mysqli_fetch_assoc(mysqli_query($conn, $queryTransports))['total'];
$totalInspectors = mysqli_fetch_assoc(mysqli_query($conn, $queryInspectors))['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard | Grading System</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap CSS & Chart.js -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
    body {
      background-color: #f4f6f9;
      font-family: 'Segoe UI', sans-serif;
    }

    .main-content {
      padding: 30px;
      margin-left: 240px;
    }

    .card {
      border: none;
      border-radius: 1rem;
    }

    h2 {
      font-weight: 600;
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<?php include('includes/sidebar.php'); ?>

<!-- Main Dashboard Content -->
<div class="main-content">
  <h2 class="mb-4">📊 Dashboard Overview</h2>

  <div class="row g-4 mb-5">
    <div class="col-md-3">
      <div class="card bg-primary text-white p-4 shadow-sm">
        <h5>Total Grades</h5>
        <h3><?= $totalGrades ?></h3>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card bg-success text-white p-4 shadow-sm">
        <h5>Total Packages</h5>
        <h3><?= $totalPackages ?></h3>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card bg-warning text-dark p-4 shadow-sm">
        <h5>Total Transports</h5>
        <h3><?= $totalTransports ?></h3>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card bg-info text-white p-4 shadow-sm">
        <h5>Total Inspectors</h5>
        <h3><?= $totalInspectors ?></h3>
      </div>
    </div>
  </div>

  <div class="card p-4 shadow-sm">
    <h5 class="mb-4">📈 Grade Distribution</h5>
    <canvas id="gradeChart" height="100"></canvas>
  </div>
</div>

<script>
const ctx = document.getElementById('gradeChart').getContext('2d');
new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ['A', 'B', 'C', 'D', 'F'],
    datasets: [{
      label: 'Number of Students',
      data: [10, 12, 8, 6, 6],
      backgroundColor: ['#4caf50', '#2196f3', '#ffc107', '#ff5722', '#e91e63'],
      borderRadius: 6
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { display: false }
    },
    scales: {
      y: {
        beginAtZero: true,
        title: {
          display: true,
          text: 'Number of Students'
        }
      },
      x: {
        title: {
          display: true,
          text: 'Grade'
        }
      }
    }
  }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
