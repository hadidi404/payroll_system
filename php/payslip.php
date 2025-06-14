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
<link href="../css/payslip.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="container">
    <div id="second_container">
        <a id="back" href="dashboard.php"><i class="fa-solid fa-arrow-left fa-2x"></i></a>
        <div class="export-buttons">
            <form method="post" action="export.php">
                <input type="hidden" name="employee_id" value="<?= htmlspecialchars($employee['id']) ?>">
                <button class="export" type="submit" name="export" value="csv">Export as CSV</button>
                <button class="export" type="submit" name="export" value="xlsx">Export as XLSX</button>
            </form>
        </div>
    </div>
    

    <h2>Payslip</h2>
    <h2 id="text">AI Korean Buffet Restaurant</h2>
    <h2 id="text">MH del pilar Burnham Legarda road, Baguio City, Philippines</h2>

    <div id="basic_info">
        <div id="first">
            <p><strong>ID:</strong> <?= htmlspecialchars($employee['id']) ?></p>
            <p><strong>Name:</strong> <?= htmlspecialchars($employee['name']) ?></p>
        </div>
        <div id="second">
            <p><strong>Position:</strong> <?= htmlspecialchars($employee['position']) ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($employee['status']) ?></p>
        </div>
        <div id="third">
            <p><strong>Days Worked:</strong> <?= htmlspecialchars($employee['days_worked']) ?></p>
            <p><strong>Hours Worked:</strong> <?= htmlspecialchars($employee['hours_worked']) ?></p>
        </div>
    </div>

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
    <div class="allowance_box">
        <p><strong>Food Allowance:</strong> <?= htmlspecialchars($employee['food_allowance']) ?></p>

        <?php if ($employee['board_lodging'] === 'Yes' && !empty($employee['lodging_address'])): ?>
            <p><strong>Board & Lodging:</strong> <?= htmlspecialchars($employee['lodging_address']) ?></p>
        <?php else: ?>
            <p><strong>Board & Lodging:</strong> <?= htmlspecialchars($employee['board_lodging']) ?></p>
        <?php endif; ?>
    </div>

    <h3>Net Pay</h3>
    <p><strong><?= formatCurrency($net_pay) ?></strong></p>

</div>
</body>
</html>
