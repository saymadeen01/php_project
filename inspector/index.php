<?php
session_start();
require_once '../config/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: ../auth/login.php");
  exit();
}

// Fetch inspector records from the database
$query = "SELECT * FROM inspectors";
$result = mysqli_query($conn, $query);

// Check if the query was successful
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Initialize an array to store inspector records
$inspectors = [];

// Fetch all records using a loop
while ($row = mysqli_fetch_assoc($result)) {
    $inspectors[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inspectors Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar (included) -->
        <div class="bg-dark text-white p-3" style="min-width: 250px; height: 100vh;">
            <?php include '../includes/sidebar.php'; ?>
        </div>

        <!-- Main content -->
        <div class="flex-grow-1 p-4">

        <h2>Inspectors List</h2>

        <!-- Generate Report Button -->
        <a href="inspection-reports.php" class="btn btn-success mb-3">Show Inspector Report</a>

        <div class="card mt-3">
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($inspectors) > 0): ?>
                            <?php foreach ($inspectors as $inspector): ?>
                                <tr>
                                    <td><?= htmlspecialchars($inspector['id']) ?></td>
                                    <td><?= htmlspecialchars($inspector['name']) ?></td>
                                    <td><?= htmlspecialchars($inspector['email']) ?></td>
                                    <td>
                                        <a href="view-inspector.php?id=<?= $inspector['id'] ?>" class="btn btn-info btn-sm">View</a>
                                        <a href="edit-inspector.php?id=<?= $inspector['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="delete-inspector.php?id=<?= $inspector['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">No inspectors found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </div> <!-- end of flex-grow-1 -->
    </div> <!-- end of d-flex -->

</body>

</html>
