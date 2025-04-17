<?php
require_once('../config/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['student_name'];
    $grade = $_POST['grade'];
    $subject = $_POST['subject'];
    $date = $_POST['date'];

    $sql = "INSERT INTO grades (student_name, grade, subject, date) 
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $grade, $subject, $date);

    if ($stmt->execute()) {
        header("Location: index.php?success=1");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
