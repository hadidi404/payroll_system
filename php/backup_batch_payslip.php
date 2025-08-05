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
        ei.*,
        ep.*, pc.*
    FROM payroll_computation pc
    JOIN employee_payroll ep ON pc.payroll_id = ep.payroll_id
    JOIN employee_info ei ON pc.employee_id = ei.employee_id
    WHERE pc.payroll_id = ?
");

function renderPayslip($value) {

    // Define formatCurrency function locally
    function formatCurrency($amount) {
        return '₱' . number_format((float)$amount, 2);
    }

    ob_start();
    include 'payslip_template.php';
    return ob_get_clean();
}

        
        if (!$stmt) {
            die("Database error: " . $conn->error); // Show error clearly
        }

        $stmt->bind_param("i", $payroll_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $value = $result->fetch_assoc();

        
        if ($value) {
            echo renderPayslip($value);
        }

        $stmt->close(); // Always good to close statements
    }

    echo "<script>window.onload = () => window.print();</script>";
    echo "</body></html>";
}

?>


