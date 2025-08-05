<?php
// Assuming this is called via AJAX
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once('../php/db.php'); // Make sure DB connection is available
?>

<link rel="stylesheet" href="../css/edit_payslip.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

<?php
$payrollId = $_GET['id'] ?? null;
$successMessage = '';
$errorMessage = '';

if ($payrollId) {
    $stmt = $conn->prepare("
        SELECT ei.*, ep.*, pc.* 
        FROM employee_info ei
        LEFT JOIN employee_payroll ep ON ei.employee_id = ep.employee_id
        LEFT JOIN payroll_computation pc ON ep.payroll_id = pc.payroll_id
        WHERE ep.payroll_id = ?
    ");
    $stmt->bind_param("i", $payrollId);
    $stmt->execute();
    $result = $stmt->get_result();
    $value = $result->fetch_assoc();
    $stmt->close();
} else {
    echo "Payroll ID not provided.";
    exit;
}

function formatCurrency($amount) {
    return '₱' . number_format($amount, 2);
}
?>

<div class="payslip-modal-payslip-editor">

    <?php if ($successMessage): ?>
        <p class="payslip-success"><?= $successMessage ?></p>
    <?php endif; ?>
    <?php if ($errorMessage): ?>
        <p class="payslip-error"><?= $errorMessage ?></p>
    <?php endif; ?>

    <form method="post" action="../payroll-system/php/edit_payslip.php" class="payslip-edit-payslip-form">
        <input type="hidden" name="payroll_id" value="<?= $payrollId ?>">

        <div class="payslip-for-style">
            <h7 id="payslip-text">AI Korean Buffet Restaurant</h7>
            <h7 id="payslip-text-special">MH del pilar Burnham Legarda road, Baguio City, Philippines</h7>
        </div>
        <div class="payslip-third_container">
            <div>
                <p class="payslip-p"><strong>ID:</strong> <?= htmlspecialchars($value['employee_id']) ?></p>
                <p class="payslip-p"><strong>Name:</strong> <?= strtoupper(htmlspecialchars($value['last_name'])) ?>, <?= htmlspecialchars($value['first_name']) ?></p>
            </div>
            <div>
                <p class="payslip-p"><strong>Position:</strong> <?= htmlspecialchars($value['position']) ?></p>
                <p class="payslip-p"><strong>Status:</strong> <?= htmlspecialchars($value['status']) ?></p>
            </div>
        </div>

        <h3 class="payslip-h3">Work Details</h3>
        <table class="payslip-table">
            <thead class="payslip-thead">
                <tr class="payslip-tr">
                    <th class="payslip-th">Category</th>
                    <th class="payslip-th">Days/Hours</th>
                    <th class="payslip-th">Rate</th>
                    <th class="payslip-th">Total</th>
                </tr>
            </thead>
            <tbody class="payslip-tbody">
            <?php
            $categories = [
                ['Daily Minimum Wage', 'w1_daily_minimum_wage', 'w1_rate', 'w1'],
                ['Sunday Rest Day', 'w2_sunday_rest_day', 'w2_rate', 'w2'],
                ['Legal Holiday', 'w3_legal_holiday', 'w3_rate', 'w3'],
                ['Special Holiday', 'w4_special_holiday', 'w4_rate', 'w4'],
                ['Regular Overtime', 'w5_regular_overtime_perhour', 'w5_rate', 'w5'],
                ['Special Overtime', 'w6_special_overtime_perhour', 'w6_rate', 'w6'],
                ['Special Holiday Overtime', 'w7_special_holiday_overtime_perhour', 'w7_rate', 'w7'],
                ['Regular Holiday Overtime', 'w8_regular_holiday_overtime_perhour', 'w8_rate', 'w8'],
                ['Cater', 'w9_cater', 'w9_rate', 'w9'],
            ];

            foreach ($categories as [$label, $hourField, $rateField, $totalField]) {
                $hours = (float)$value[$hourField];
                $total = (float)$value[$totalField];
                $rate = ($hours > 0) ? round($total / $hours, 2) : 0.00;

                echo "<tr>
                    <td>$label</td>
                    <td><input type='number' step='0.01' name='$hourField' value='$hours' class='hours form-control' data-target='$totalField' data-rate='$rateField'></td>
                    <td><input type='number' step='0.01' name='$rateField' value='$rate' class='rate form-control' data-target='$totalField' data-hours='$hourField'></td>
                    <td><span id='{$totalField}_display'>" . formatCurrency($total) . "</span></td>
                </tr>";
            }
            ?>
            <tr class="payslip-tr">
                <td class="payslip-td"><strong>Gross Pay</strong></td>
                <td class="payslip-td-special" colspan="3"><?= formatCurrency($value['gross_pay']) ?></td>
            </tr>
            </tbody>
        </table>

        <h3 class="payslip-h3">Deductions</h3>
        <table class="payslip-table">
            <thead class="payslip-thead">
                <tr class="payslip-tr">
                    <th class="payslip-th">Deduction</th>
                    <th class="payslip-th">Amount</th>
                </tr>
            </thead>
            <tbody class="payslip-tbody">
                <tr class="payslip-tr"><td class="payslip-td">SSS</td><td><?= formatCurrency($value['sss']) ?></td></tr>
                <tr class="payslip-tr"><td class="payslip-td">PhilHealth</td><td><?= formatCurrency($value['philhealth']) ?></td></tr>
                <tr class="payslip-tr"><td class="payslip-td">Pag-IBIG</td><td><?= formatCurrency($value['pagibig']) ?></td></tr>
                <tr class="payslip-tr">
                    <td class="payslip-td">Cater Deduction</td>
                    <td class="payslip-td-special"><input type="number" step="0.01" name="cater1" value="<?= $value['cater1'] ?>" class="payslip-form-control"></td>
                </tr>
                <tr class="payslip-tr">
                    <td class="payslip-td">Advance</td>
                    <td class="payslip-td-special"><input type="number" step="0.01" name="advance" value="<?= $value['advance'] ?>" class="payslip-form-control"></td>
                </tr>
                <tr class="payslip-tr"><td class="payslip-td"><strong>Total Deductions</strong></td><td><?= formatCurrency($value['total_deductions']) ?></td></tr>
                <tr class="payslip-tr"><td class="payslip-td"><strong>Net Pay</strong></td><td><?= formatCurrency($value['net_pay']) ?></td></tr>
            </tbody>
        </table>

        <br>
        <button type="submit" class="payslip-save_btn">Save Changes</button>
    </form>
</div>

<script src="../js/edit_payslip.js"></script>
