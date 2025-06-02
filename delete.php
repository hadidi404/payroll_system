<?php
include 'db.php'; // Your DB connection

// Handle multiple or single IDs
$ids = [];

if (isset($_GET['id'])) {
    $ids[] = $_GET['id'];
} elseif (isset($_GET['ids'])) {
    $ids = explode(',', $_GET['ids']);
}

if (empty($ids)) {
    die("No employee ID(s) specified.");
}

// Build dynamic placeholders (?, ?, ?) for the IN clause
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$types = str_repeat('s', count($ids)); // Assuming IDs are strings

$sql = "DELETE FROM employee_info WHERE id IN ($placeholders)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param($types, ...$ids);

if ($stmt->execute()) {
    header("Location: dashboard.php");
    exit();
} else {
    echo "Error deleting employee(s): " . $stmt->error;
}
?>
