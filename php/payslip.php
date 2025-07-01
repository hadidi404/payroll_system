<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: authentication.php");
    exit();
}

include 'db.php';

function formatCurrency($value) {
    return '₱' . number_format((float)$value, 2);
}

$id = $_GET['id'] ?? null;
if (!$id) {
    die("No ID provided.");
}

// Fetch employee info with payroll and computation
$query1 = "
SELECT 
  ei.*, 
  ep.*, 
  pc.*
FROM employee_info ei
LEFT JOIN employee_payroll ep ON ei.employee_id = ep.employee_id
LEFT JOIN payroll_computation pc ON ep.payroll_id = pc.payroll_id
WHERE ei.employee_id = ?
";

$stmt = $conn->prepare($query1);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param('i', $id);
$stmt->execute();
$result1 = $stmt->get_result();

if ($result1->num_rows === 0) {
    die("Employee not found.");
}

$value = $result1->fetch_assoc();

include '../html/payslip_html.php';
?>
