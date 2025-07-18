    <?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: authentication.php");
    exit();
}

require 'db.php';


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
        header('Content-Disposition: attachment; filename="employees.csv"');//name of employee for file name
        $output = fopen('php://output', 'w');
        fputcsv($output, $headers); // Write headers
        foreach ($employees as $employee) {
            fputcsv($output, $employee); // Write each employee row
        }
        fclose($output);
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
            </div>
        </form>
    </div>
</body>
</html>
<?php
}
?>
