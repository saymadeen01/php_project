<?php
require_once '../config/db.php';

// Check if ID is set and valid
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare and execute delete query
    $stmt = $conn->prepare("DELETE FROM packages WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redirect after successful deletion
        header("Location: index.php?deleted=1");
        exit;
    } else {
        echo "❌ Failed to delete package: " . $stmt->error;
    }
} else {
    echo "⚠️ Invalid or missing package ID.";
}
?>
