<?php
include 'db.php'; // your DB connection

$id = $_GET['id'] ?? null;

if (!$id) {
    die("No employee ID specified.");
}

// Prepare and execute deletion
$sql = "DELETE FROM employee_info WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id);

if ($stmt->execute()) {
    // Redirect back to list after successful deletion
    header("Location: index.php");
    exit();
} else {
    echo "Error deleting employee: " . $stmt->error;
}
