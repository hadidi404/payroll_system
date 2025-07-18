<!DOCTYPE html>
<html>
<head>
    <title>Payslip Editor</title>
    <link href="../css/payslip.css" rel="stylesheet">
    <style>
        body { font-family: 'Montserrat', sans-serif; padding: 30px; }
        input { width: 100px; padding: 3px; }
        table { border-collapse: collapse; width: 100%; }
        td { padding: 8px; border: 1px solid #ccc; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
<div class="container">
    <h2>Payslip Editor</h2>

    <?php if ($successMessage): ?>
        <p class="success"><?= $successMessage ?></p>
    <?php endif; ?>
    <?php if ($errorMessage): ?>
        <p class="error"><?= $errorMessage ?></p>
    <?php endif; ?>

    <form method="post" action="edit_payslip.php">
        <input type="hidden" name="payroll_id" value="<?= $payrollId ?>">

        <p><strong>ID:</strong> <?= htmlspecialchars($value['employee_id']) ?></p>
        <p><strong>Name:</strong> <?= strtoupper(htmlspecialchars($value['last_name'])) ?>, <?= htmlspecialchars($value['first_name']) ?></p>
        <p><strong>Position:</strong> <?= htmlspecialchars($value['position']) ?></p>
        <p><strong>Status:</strong> <?= htmlspecialchars($value['status']) ?></p>

        <h3>Work Details</h3>
        <table>
            <tr>
                <td>Category</td>
                <td>Days/Hours</td>
                <td>Rate</td>
                <td>Total</td>
            </tr>
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
                    <td><input type='number' step='0.01' name='$hourField' value='$hours' class='hours' data-target='$totalField' data-rate='$rateField'></td>
                    <td><input type='number' step='0.01' name='$rateField' value='$rate' class='rate' data-target='$totalField' data-hours='$hourField'></td>
                    <td><span id='{$totalField}_display'>" . formatCurrency($total) . "</span></td>
                </tr>";
            }
            ?>
            <tr>
                <td><strong>Gross Pay</strong></td>
                <td colspan="3"><?= formatCurrency($value['gross_pay']) ?></td>
            </tr>
        </table>

        <h3>Deductions</h3>
        <table>
            <tr><td>SSS</td><td><?= formatCurrency($value['sss']) ?></td></tr>
            <tr><td>PhilHealth</td><td><?= formatCurrency($value['philhealth']) ?></td></tr>
            <tr><td>Pag-IBIG</td><td><?= formatCurrency($value['pagibig']) ?></td></tr>
            <tr>
                <td>Cater Deduction</td>
                <td><input type="number" step="0.01" name="cater1" value="<?= $value['cater1'] ?>"></td>
            </tr>
            <tr>
                <td>Advance</td>
                <td><input type="number" step="0.01" name="advance" value="<?= $value['advance'] ?>"></td>
            </tr>
            <tr><td><strong>Total Deductions</strong></td><td><?= formatCurrency($value['total_deductions']) ?></td></tr>
            <tr><td><strong>Net Pay</strong></td><td><?= formatCurrency($value['net_pay']) ?></td></tr>
        </table>

        <br>
        <button type="submit">Save Changes</button>
        <a href="dashboard.php">← Back to Dashboard</a>
    </form>
</div>

<script src="../js/edit_payslip.js"></script>
</body>
</html>
