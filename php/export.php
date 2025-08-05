<?php



session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: authentication.php");
    exit();
}


require 'db.php';

if (!isset($_GET['payroll_id'])) {
    die("No payroll ID provided.");
}

$payroll_id = intval($_GET['payroll_id']);

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="payslip_payroll_' . $payroll_id . '.csv"');

$output = fopen('php://output', 'w');

// CSV column headers
fputcsv($output, [
    'Employee ID', 'Last Name', 'First Name', 'Position', 'Status',
    'Board & Lodging', 'Food Allowance',
    'Gross Pay', 'Total Deductions', 'Net Pay'
]);

// Query using payroll_id with a JOIN
$query = "
    SELECT 
        ei.employee_id,
        ei.last_name,
        ei.first_name,
        ei.position,
        ei.status,
        ei.board_lodging,
        ei.food_allowance,
        pc.gross_pay,
        pc.total_deductions,
        pc.net_pay
    FROM employee_info ei
    LEFT JOIN employee_payroll ep ON ei.employee_id = ep.employee_id
    LEFT JOIN payroll_computation pc ON ep.payroll_id = pc.payroll_id
    WHERE ep.payroll_id = ?
";

$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param('i', $payroll_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("No data found for payroll ID $payroll_id.");
}

while ($row = $result->fetch_assoc()) {
    fputcsv($output, [
        $row['employee_id'],
        $row['last_name'],
        $row['first_name'],
        $row['position'],
        $row['status'],
        $row['board_lodging'],
        $row['food_allowance'],
        $row['gross_pay'],
        $row['total_deductions'],
        $row['net_pay']
    ]);
}

fclose($output);
exit();
?>
