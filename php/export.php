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

    if ($exportType === 'xlsx') {
        if (!file_exists('vendor/autoload.php')) {
            die("PhpSpreadsheet library not found. Please run 'composer install' in the project root.");
        }
        require 'vendor/autoload.php'; // PhpSpreadsheet required

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Write headers
        $col = 1;
        foreach ($headers as $header) {
            $sheet->setCellValueByColumnAndRow($col, 1, $header);
            $col++;
        }

        // Write data
        $row = 2;
        foreach ($employees as $employee) {
            $col = 1;
            foreach ($employee as $value) {
                $sheet->setCellValueByColumnAndRow($col, $row, $value);
                $col++;
            }
            $row++;
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="employees.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
} else {
    // Display the export page with buttons
?>
<!DOCTYPE html>
<html>
<head>
    <title>Export Employee Data</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../css/dashboard.css" /> <!-- Reusing dashboard CSS for styling -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f0f2f5; /* Light gray background */
            margin: 0;
            font-family: 'Montserrat', sans-serif;
        }
        .export-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .export-container h1 {
            color: #333;
            margin-bottom: 30px;
        }
        .export-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
        }
        .export-buttons button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.1em;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: background-color 0.3s ease;
        }
        .export-buttons button:hover {
            background-color: #0056b3;
        }
        .export-buttons button i {
            font-size: 1.5em;
        }
    </style>
</head>
<body>
    <div class="export-container">
        <h1>Export Employee Data</h1>
        <form id="export-form" action="export.php" method="POST">
            <div class="export-buttons">
                <button type="submit" name="export" value="csv">
                    <i class="fa-solid fa-file-csv"></i> Export as CSV
                </button>
                <button type="submit" name="export" value="xlsx">
                    <i class="fa-solid fa-file-excel"></i> Export as Excel
                </button>
            </div>
        </form>
    </div>
</body>
</html>
<?php
}
?>
