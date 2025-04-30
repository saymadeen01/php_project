<?php
session_start();
require_once('../config/db.php');

// Handle search functionality
$searchQuery = "";
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
}

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

// Fetch all grade records with search functionality
$limit = 10; // Number of records per page
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$searchCondition = "";
if (!empty($searchQuery)) {
    $searchCondition = "WHERE crop_name LIKE '%$searchQuery%'";
}

$grades = $conn->query("SELECT * FROM grades $searchCondition ORDER BY date DESC LIMIT $limit OFFSET $offset");

// Get total record count for pagination
$totalRecordsResult = $conn->query("SELECT COUNT(*) AS total FROM grades $searchCondition");
$totalRecords = $totalRecordsResult->fetch_assoc()['total'];
$totalPages = ceil($totalRecords / $limit);
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
    .search-bar { margin-bottom: 30px; }
  </style>
</head>
<body>

<?php include('../includes/sidebar.php'); ?>

<div class="main-content">
  <!-- Search Bar -->
  <div class="search-bar mb-4">
    <form action="" method="GET" class="d-flex">
      <input type="text" name="search" class="form-control" placeholder="Search by Crop Name" value="<?= htmlspecialchars($searchQuery) ?>">
      <button type="submit" class="btn btn-primary ms-2">Search</button>
    </form>
  </div>

  <!-- Grade Table -->
  <div class="mb-5">
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
              <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" data-id="<?= $row['id'] ?>">🗑️ Delete</button>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <nav aria-label="Page navigation">
    <ul class="pagination">
      <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
        <a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= htmlspecialchars($searchQuery) ?>" aria-label="Previous">
          <span aria-hidden="true">&laquo;</span>
        </a>
      </li>
      <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <li class="page-item <?= $i == $page ? 'active' : '' ?>"><a class="page-link" href="?page=<?= $i ?>&search=<?= htmlspecialchars($searchQuery) ?>"><?= $i ?></a></li>
      <?php endfor; ?>
      <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
        <a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= htmlspecialchars($searchQuery) ?>" aria-label="Next">
          <span aria-hidden="true">&raquo;</span>
        </a>
      </li>
    </ul>
  </nav>

  <!-- Chart Section (Moved below the table) -->
  <div class="mb-5">
    <canvas id="gradeChart" height="100"></canvas>
  </div>
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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="confirmDeleteLabel">Delete Grade</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this grade?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="deleteGradeBtn">Delete</button>
      </div>
    </div>
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

  // Delete Grade Handler
  const deleteGradeBtns = document.querySelectorAll('[data-bs-target="#confirmDeleteModal"]');
  deleteGradeBtns.forEach(btn => {
    btn.addEventListener('click', function() {
      const gradeId = this.getAttribute('data-id');
      const deleteBtn = document.getElementById('deleteGradeBtn');
      deleteBtn.setAttribute('data-id', gradeId);
    });
  });

  // Confirm Delete Action
  document.getElementById('deleteGradeBtn').addEventListener('click', function() {
    const gradeId = this.getAttribute('data-id');
    window.location.href = `delete.php?id=${gradeId}`;
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
