<?php
require_once('../config/db.php');

if (!isset($_GET['id'])) {
    echo "Missing transport ID.";
    exit;
}

$id = $_GET['id'];

// Delete query
$stmt = $conn->prepare("DELETE FROM transport WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: index.php?deleted=1");
    exit;
} else {
    echo "Failed to delete: " . $stmt->error;
}
?>
