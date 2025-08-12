<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: authentication.php");
    exit();
}

include 'db.php';

// 🔹 For Employee List tab
$query = "SELECT * FROM employee_info";
$result = $conn->query($query);
if (!$result) {
    die("Error retrieving employee info: " . $conn->error);
}

// 🔹 For Payroll List tab
$query1 = "
SELECT 
  ei.employee_id,
  ei.last_name,
  ei.first_name,
  ep.payroll_id,
  pc.computation_id,
  pc.total_deductions,
  pc.gross_pay,
  pc.net_pay
FROM employee_info ei
LEFT JOIN employee_payroll ep ON ei.employee_id = ep.employee_id
LEFT JOIN payroll_computation pc ON ep.payroll_id = pc.payroll_id
";
$result1 = $conn->query($query1);
if (!$result1) {
    die("Error retrieving payroll info: " . $conn->error);
}

include '../html/dashboard_html.php';
?>
