<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['export'], $_POST['employee_id'])) {
    $id = intval($_POST['employee_id']);
    $exportType = $_POST['export'];

    // Get employee data
    $stmt = $conn->prepare("SELECT * FROM employee_info WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if (!$result || $result->num_rows === 0) {
        die("No employee data found.");
    }

    $employee = $result->fetch_assoc();

    if ($exportType === 'csv') {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="employee_' . $id . '.csv"');
        $output = fopen('php://output', 'w');
        fputcsv($output, array_keys($employee));
        fputcsv($output, $employee);
        fclose($output);
        exit;
    }

    if ($exportType === 'xlsx') {
        require 'vendor/autoload.php'; // PhpSpreadsheet required

        use PhpOffice\PhpSpreadsheet\Spreadsheet;
        use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $col = 1;
        foreach ($employee as $key => $value) {
            $sheet->setCellValueByColumnAndRow($col, 1, $key);
            $sheet->setCellValueByColumnAndRow($col, 2, $value);
            $col++;
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="employee_' . $id . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
?>
