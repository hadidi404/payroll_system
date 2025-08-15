<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once('../php/db.php');

$payrollId = $_GET['id'] ?? null;
$successMessage = '';
$errorMessage = '';

if ($payrollId) {
    $stmt = $conn->prepare("
        SELECT eir.*, pt.*
        FROM employee_info_and_rates eir
        LEFT JOIN payroll_transactions pt
            ON eir.employee_id = pt.employee_id
        WHERE pt.payroll_id = ?
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
    return '₱' . number_format((float)$amount, 2);
}
?>

<div class="payslip-modal-payslip-editor">
    <form method="post" action="../php/edit_payslip.php" class="payslip-edit-payslip-form">
        <input type="hidden" name="payroll_id" value="<?= $payrollId ?>">

        <div class="payslip-for-style">
            <p>Date: <?= date("F j, Y") ?></p>
            <h7 id="ppayslip-text">AI Korean Buffet Restaurant</h7>
            <h7 id="ppayslip-text-special">MH del pilar Burnham Legarda road, Baguio City, Philippines</h7>
        </div>
        <div class="payslip-third_container">
            <div>
                <p class="ppayslip-p"><strong>ID:</strong> <?= htmlspecialchars($value['employee_id']) ?></p>
                <p class="ppayslip-p"><strong>Name:</strong> <?= strtoupper(htmlspecialchars($value['last_name'])) ?>, <?= htmlspecialchars($value['first_name']) ?></p>
            </div>
            <div>
                <p class="ppayslip-p"><strong>Position:</strong> <?= htmlspecialchars($value['position']) ?></p>
                <p class="ppayslip-p"><strong>Status:</strong> <?= htmlspecialchars($value['status']) ?></p>
            </div>
        </div>

        <h3 class="ppayslip-h3">Work Details</h3>
        <table class="ppayslip-table">
            <tr class="ppayslip-tr">
                <td class="ppayslip-td">Rate Type</td>
                <td class="ppayslip-td">Days/Hours</td>
                <td class="ppayslip-td">Rate</td>
                <td class="ppayslip-td">Total</td>
            </tr>
            <?php
            $categories = [
                ['Daily Minimum Wage', 'num_of_days_for_rate_1', 'rate_1_daily_minimum_wage'],
                ['Sunday Rest Day', 'num_of_days_for_rate_2', 'rate_2_sunday_rest_day'],
                ['Legal Holiday', 'num_of_days_for_rate_3', 'rate_3_legal_holiday'],
                ['Special Holiday', 'num_of_days_for_rate_4', 'rate_4_special_holiday'],
                ['Regular Overtime (per hr)', 'num_of_hours_for_rate_5', 'rate_5_regular_overtime_perhour'],
                ['Special Overtime (per hr)', 'num_of_hours_for_rate_6', 'rate_6_special_overtime_perhour'],
                ['Special Holiday OT (per hr)', 'num_of_hours_for_rate_7', 'rate_7_special_holiday_overtime_perhour'],
                ['Regular Holiday OT (per hr)', 'num_of_hours_for_rate_8', 'rate_8_regular_holiday_overtime_perhour'],
                ['Cater', 'num_of_days_for_rate_9', 'rate_9_cater'],
            ];

            $grossPay = 0;

            foreach ($categories as [$label, $numField, $rateField]) {
                $num = (float)($value[$numField] ?? 0);
                $rate = (float)($value[$rateField] ?? 0);
                $total = $num * $rate;
                $grossPay += $total;

                echo "<tr>
                    <td>{$label}</td>
                    <td><input type='number' name='{$numField}' value='{$num}' step='0.01' class='form-control'></td>
                    <td>" . formatCurrency($rate) . "</td>
                    <td>" . formatCurrency($total) . "</td>
                </tr>";
            }
            ?>
            <tr class="ppayslip-tr">
                <td colspan="3"><strong>Gross Pay</strong></td>
                <td><strong><?= formatCurrency($grossPay) ?></strong></td>
            </tr>
        </table>

        <h3 class="ppayslip-h3">Deductions</h3>
        <table class="ppayslip-table">
            <tr>
                <td>Cater Deduction</td>
                <td><input type="number" name="cater_deductions" value="<?= $value['cater_deductions'] ?? 0 ?>" step="0.01" class="form-control"></td>
            </tr>
            <tr>
                <td>Advance Deduction</td>
                <td><input type="number" name="advance_deductions" value="<?= $value['advance_deductions'] ?? 0 ?>" step="0.01" class="form-control"></td>
            </tr>
            <tr>
                <td><strong>Total Deductions</strong></td>
                <td><strong><?= formatCurrency(($value['cater_deductions'] ?? 0) + ($value['advance_deductions'] ?? 0)) ?></strong></td>
            </tr>
            <tr>
                <td><strong>Net Pay</strong></td>
                <td><strong><?= formatCurrency($grossPay - (($value['cater_deductions'] ?? 0) + ($value['advance_deductions'] ?? 0))) ?></strong></td>
            </tr>
        </table>

        <br>
        <button type="submit" class="ppayslip-save_btn">Save Changes</button>
    </form>
</div>
