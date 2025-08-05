<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['export']) && $_POST['export'] === 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename="payroll_export.csv"');

    $output = fopen('php://output', 'w');

    // Column headers
    fputcsv($output, [
        'Employee ID', 'Last Name', 'First Name', 'Position', 'Status',
        'Board & Lodging', 'Food Allowance', 'Gross Pay', 'Total Deductions', 'Net Pay'
    ]);

    // SQL query with proper JOINs
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
        LEFT JOIN payroll_computation pc ON ei.employee_id = pc.employee_id
    ";

    $result = $conn->query($query);

    // Error check
    if (!$result) {
        die("Query failed: " . $conn->error);
    }

    // Output each row
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
}
?>
