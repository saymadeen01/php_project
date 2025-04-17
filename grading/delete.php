<?php
require_once('../config/db.php');

// Check if the ID is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("❌ Error: Grade ID not provided.");
}

$id = intval($_GET['id']); // Sanitize to integer

// Prepare and execute delete query
$stmt = $conn->prepare("DELETE FROM grades WHERE id = ?");
if (!$stmt) {
    die("❌ Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    // Redirect after successful deletion
    header("Location: index.php?deleted=1");
    exit();
} else {
    die("❌ Delete failed: " . $stmt->error);
}
?>
