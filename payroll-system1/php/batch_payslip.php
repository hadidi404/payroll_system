<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ids = json_decode($_POST['payroll_ids'], true);
    
    // Connect to DB
    include('db.php');

    if (!$ids || !is_array($ids)) {
        die("Invalid input.");
    }

    echo "<html><head><title>Batch Payslip</title><style>
        .payslip {
            margin-bottom: 40px;
            padding: 20px;
            border: 1px solid #ccc;
        }
        @media print {
            body {
                font-family: Arial, sans-serif;
            }
        }
    </style></head><body>";

    foreach ($ids as $payroll_id) {
        $stmt = $conn->prepare("
    SELECT 
        ei.first_name, ei.last_name, ei.position,
        ep.*, pc.*
    FROM payroll_computation pc
    JOIN employee_payroll ep ON pc.payroll_id = ep.payroll_id
    JOIN employee_info ei ON pc.employee_id = ei.employee_id
    WHERE pc.payroll_id = ?
");

        
        if (!$stmt) {
            die("Database error: " . $conn->error); // Show error clearly
        }

        $stmt->bind_param("i", $payroll_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            echo "<div class='payslip'>";
            echo "<h3>Payslip for {$row['first_name']} {$row['last_name']}</h3>";
            echo "<p>Gross Pay: ₱" . number_format($row['gross_pay'], 2) . "</p>";
            echo "<p>Total Deductions: ₱" . number_format($row['total_deductions'], 2) . "</p>";
            echo "<p>Net Pay: ₱" . number_format($row['net_pay'], 2) . "</p>";
            echo "<hr>";
            echo "</div>";
        }

        $stmt->close(); // Always good to close statements
    }

    echo "<script>window.onload = () => window.print();</script>";
    echo "</body></html>";
}
?>
