<?php
include 'db.php'; // your DB connection

$ids_string = $_GET['ids'] ?? null;

if (!$ids_string) {
    die("No employee ID specified.");
}

$ids = explode(',', $ids_string);
$placeholders = implode(',', array_fill(0, count($ids), '?'));

// Prepare and execute deletion
$sql = "DELETE FROM employee_info WHERE id IN ($placeholders)";
$stmt = $conn->prepare($sql);

// Dynamically bind parameters
$types = str_repeat('s', count($ids)); // Assuming IDs are strings, adjust if they are integers ('i')
$stmt->bind_param($types, ...$ids);

if ($stmt->execute()) {
    // Redirect back to list after successful deletion
    header("Location: index.php");
    exit();
} else {
    echo "Error deleting employee: " . $stmt->error;
}
