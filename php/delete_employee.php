<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: authentication.php");
    exit();
}

include 'db.php'; // DB connection

// Get the employee ID from the URL
$employee_id = $_GET['employee_id'] ?? null;

if (!$employee_id) {
    die("No employee ID specified.");
}

// DELETE from employee_info table
$sql = "DELETE FROM employee_info WHERE employee_id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param('i', $employee_id);

if ($stmt->execute()) {
    header("Location: dashboard.php");
    exit();
} else {
    echo "Error deleting employee: " . $stmt->error;
}
?>
