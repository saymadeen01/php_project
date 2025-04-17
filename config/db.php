<?php
// Database configuration
$host = 'localhost';
$dbname = 'grading_system';
$username = 'root'; // change if needed
$password = '';     // change if needed

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("❌ Database connection failed: " . $conn->connect_error);
}

// Optional: set charset
$conn->set_charset("utf8mb4");

// Optional success message for debugging (can comment out in production)
// echo "✅ Connected successfully!";
?>
