<?php
require 'db.php';

// These use statements must be at the top level, outside any conditional blocks
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['export'])) {
    $exportType = $_POST['export'];

    // Get all employee data
    $stmt = $conn->prepare("SELECT * FROM employee_info");
    $stmt->execute();
    $result = $stmt->get_result();
    if (!$result || $result->num_rows === 0) {
        die("No employee data found.");
    }

    $employees = [];
    while ($row = $result->fetch_assoc()) {
        $employees[] = $row;
    }

    if (empty($employees)) {
        die("No employee data to export.");
    }

    // Determine headers from the first employee's keys
    $headers = array_keys($employees[0]);

    if ($exportType === 'csv') {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="employees.csv"');
        $output = fopen('php://output', 'w');
        fputcsv($output, $headers); // Write headers
        foreach ($employees as $employee) {
            fputcsv($output, $employee); // Write each employee row
        }
        fclose($output);
        exit;
    }
}
?>
