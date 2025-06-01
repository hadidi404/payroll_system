<?php
include 'db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    die("No ID provided.");
}

// Fetch employee details
$sql = "SELECT * FROM employee_info WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Employee not found.");
}

$employee = $result->fetch_assoc();
$deductionsArray = explode(',', $employee['deductions']);

function formatCurrency($value) {
    return '₱' . number_format((float)$value, 2);
}

// Calculate total deductions
$total_deductions = 0;
foreach (['sss', 'philhealth', 'pagibig', 'tax', 'others'] as $deduction) {
    if (in_array($deduction, $deductionsArray)) {
        $total_deductions += (float)$employee[$deduction];
    }
}

// Calculate net pay
$gross_pay = (float)$employee['gross_pay'];
$net_pay = $gross_pay - $total_deductions;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payslip</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 50px; }
        .container { max-width: 700px; margin: auto; border: 1px solid #ccc; padding: 30px; border-radius: 8px; }
        h2 { text-align: center; margin-bottom: 30px; }
        table { width: 100%; margin-top: 20px; border-collapse: collapse; }
        th, td { text-align: left; padding: 8px; border-bottom: 1px solid #ccc; }
        .total { font-weight: bold; }
        .export-buttons { text-align: right; margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="container">
    <div class="export-buttons">
        <form method="post" action="export.php">
            <input type="hidden" name="employee_id" value="<?= htmlspecialchars($employee['id']) ?>">
            <button type="submit" name="export" value="csv">Export as CSV</button>
            <button type="submit" name="export" value="xlsx">Export as XLSX</button>
        </form>
    </div>

    <h2>Payslip</h2>

    <p><strong>Name:</strong> <?= htmlspecialchars($employee['name']) ?></p>
    <p><strong>Position:</strong> <?= htmlspecialchars($employee['position']) ?></p>
    <p><strong>Status:</strong> <?= htmlspecialchars($employee['status']) ?></p>

    <h3>Work Details</h3>
    <table>
        <tr><td>Days Worked</td><td><?= htmlspecialchars($employee['days_worked']) ?></td></tr>
        <tr><td>Hours Worked</td><td><?= htmlspecialchars($employee['hours_worked']) ?></td></tr>
        <tr><td>Overtime Hours</td><td><?= htmlspecialchars($employee['overtime_hours']) ?></td></tr>
        <tr><td>Hourly Rate</td><td><?= formatCurrency($employee['hourly_rate']) ?></td></tr>
        <tr><td>Overtime Rate</td><td><?= formatCurrency($employee['overtime_rate']) ?></td></tr>
        <tr><td><strong>Gross Pay</strong></td><td><strong><?= formatCurrency($gross_pay) ?></strong></td></tr>
    </table>

    <h3>Deductions</h3>
    <table>
        <?php foreach (['sss', 'philhealth', 'pagibig', 'tax', 'others'] as $deduction): ?>
            <?php if (in_array($deduction, $deductionsArray)): ?>
                <tr>
                    <td><?= strtoupper($deduction) ?></td>
                    <td><?= formatCurrency($employee[$deduction]) ?></td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
        <tr class="total">
            <td>Total Deductions</td>
            <td><?= formatCurrency($total_deductions) ?></td>
        </tr>
    </table>

    <h3>Allowances</h3>
    <p><strong>Food Allowance:</strong> <?= htmlspecialchars($employee['food_allowance']) ?></p>

    <?php if ($employee['board_lodging'] === 'Yes' && !empty($employee['lodging_address'])): ?>
        <p><strong>Board & Lodging:</strong> <?= htmlspecialchars($employee['lodging_address']) ?></p>
    <?php else: ?>
        <p><strong>Board & Lodging:</strong> <?= htmlspecialchars($employee['board_lodging']) ?></p>
    <?php endif; ?>

    <h3>Net Pay</h3>
    <p><strong><?= formatCurrency($net_pay) ?></strong></p>

    <br><a href="dashboard.php">← Back to Employee List</a>
</div>

</body>
</html>
