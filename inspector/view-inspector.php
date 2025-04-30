<?php
session_start();
require_once '../config/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: ../auth/login.php");
  exit();
}

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Fetch inspector details
    $query = "SELECT * FROM inspectors WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $inspector = mysqli_fetch_assoc($result);

    if (!$inspector) {
        echo "Inspector not found.";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Inspector</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body>
    <?php include '../includes/sidebar.php'; ?>

    <div class="container mt-5">
        <h2>Inspector Details</h2>

        <table class="table">
            <tr>
                <th>Name</th>
                <td><?= htmlspecialchars($inspector['name']) ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?= htmlspecialchars($inspector['email']) ?></td>
            </tr>
            <tr>
                <th>Phone</th>
                <td><?= htmlspecialchars($inspector['phone']) ?></td>
            </tr>
            <tr>
                <th>Created At</th>
                <td><?= htmlspecialchars($inspector['created_at']) ?></td>
            </tr>
        </table>

        <a href="index.php" class="btn btn-primary">Back to List</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
