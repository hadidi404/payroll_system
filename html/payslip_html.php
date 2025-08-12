<?php
include '../php/db.php';

function formatCurrency($amount) {
    return '₱' . number_format((float)$amount, 2);
}

$payroll_id = $_GET['id'] ?? 0;

if ($payroll_id) {
    $stmt = $conn->prepare("
        SELECT ei.*, ep.*, pc.*
        FROM employee_info ei
        JOIN employee_payroll ep ON ei.employee_id = ep.employee_id
        JOIN payroll_computation pc ON ep.payroll_id = pc.payroll_id
        WHERE ep.payroll_id = ?
    ");

    $stmt->bind_param("i", $payroll_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $value = $result->fetch_assoc();

    $stmt->close();
} else {
    echo "No payroll ID provided.";
    exit();
}
?>


    <form class="ppayslip-form">

        <input class="ppayslip-input" type="hidden" name="employee_id" value="<?= htmlspecialchars($value['employee_id']) ?>">



            
            <div class="ppayslip-restaurant">
                <h2 class="ppayslip-text">AI Korean Buffet Restaurant</h2>
                <h2 class="ppayslip-text">MH del pilar Burnham Legarda road, Baguio City, Philippines</h2>
            </div>

            <div class="ppayslip-basic_info">
                <div class="ppayslip-first">
                    <p>Date: <?= date("F j, Y") ?></p>
                    <p class="ppayslip-info"><strong class="ppayslip-strong">ID:</strong> <?= htmlspecialchars($value['employee_id']) ?></p>
                    <p class="ppayslip-p"><strong class="ppayslip-strong">Name:</strong> <?= mb_strtoupper(htmlspecialchars($value['last_name'])) ?>, <?= htmlspecialchars($value['first_name']) ?></p>
                </div>
                <div class="ppayslip-second">
                    <p class="ppayslip-info"><strong class="ppayslip-strong">Position:</strong> <?= htmlspecialchars($value['position']) ?></p>
                    <p class="ppayslip-p"><strong class="ppayslip-strong">Status:</strong> <?= htmlspecialchars($value['status']) ?></p>
                </div>

            </div>

            <h3 class="ppayslip-h3">Work Details</h3>
            <table class="ppayslip-table">
            <tr class="ppayslip-tr">
                <td class="ppayslip-td"></td>
                <td class="ppayslip-td">Days/Hours</td>
                <td class="ppayslip-td">Total</td>
            </tr>
            <?php if (!is_null($value['w1_daily_minimum_wage']) && $value['w1_daily_minimum_wage'] != 0): ?>
            <tr class="ppayslip-tr">
                <td class="ppayslip-td">Daily Minimum Wage</td>
                <td class="ppayslip-center-align"><?= htmlspecialchars($value['w1_daily_minimum_wage']) ?></td>
                <td class="ppayslip-td"><?= formatCurrency($value['w1']) ?></td>
            </tr>
            <?php endif; ?>

            <?php if (!is_null($value['w2_sunday_rest_day']) && $value['w2_sunday_rest_day'] != 0): ?>
            <tr class="ppayslip-tr">
                <td class="ppayslip-td">Sunday Rest Day</td>
                <td class="ppayslip-center-align"><?= htmlspecialchars($value['w2_sunday_rest_day']) ?></td>
                <td class="ppayslip-td"><?= formatCurrency($value['w2']) ?></td>
            </tr>
            <?php endif; ?>

            <?php if (!is_null($value['w3_legal_holiday']) && $value['w3_legal_holiday'] != 0): ?>
            <tr class="ppayslip-tr">
                <td class="ppayslip-td">Legal Holiday</td>
                <td class="ppayslip-center-align"><?= htmlspecialchars($value['w3_legal_holiday']) ?></td>
                <td class="ppayslip-td"><?= formatCurrency($value['w3']) ?></td>
            </tr>
            <?php endif; ?>

            <?php if (!is_null($value['w4_special_holiday']) && $value['w4_special_holiday'] != 0): ?>
            <tr class="ppayslip-tr">
                <td class="ppayslip-td">Special Holiday</td>
                <td class="ppayslip-center-align"><?= htmlspecialchars($value['w4_special_holiday']) ?></td>
                <td class="ppayslip-td"><?= formatCurrency($value['w4']) ?></td>
            </tr>
            <?php endif; ?>

            <?php if (!is_null($value['w5_regular_overtime_perhour']) && $value['w5_regular_overtime_perhour'] != 0): ?>
            <tr class="ppayslip-tr">
                <td class="ppayslip-td">Regular Overtime/Hour</td>
                <td class="ppayslip-center-align"><?= htmlspecialchars($value['w5_regular_overtime_perhour']) ?></td>
                <td class="ppayslip-td"><?= formatCurrency($value['w5']) ?></td>
            </tr>
            <?php endif; ?>

            <?php if (!is_null($value['w6_special_overtime_perhour']) && $value['w6_special_overtime_perhour'] != 0): ?>
            <tr class="ppayslip-tr">
                <td class="ppayslip-td">Special Overtime/Hour</td>
                <td class="ppayslip-center-align"><?= htmlspecialchars($value['w6_special_overtime_perhour']) ?></td>
                <td class="ppayslip-td"><?= formatCurrency($value['w6']) ?></td>
            </tr>
            <?php endif; ?>

            <?php if (!is_null($value['w7_special_holiday_overtime_perhour']) && $value['w7_special_holiday_overtime_perhour'] != 0): ?>
            <tr class="ppayslip-tr">
                <td class="ppayslip-td">Special Holiday Overtime/Hour</td>
                <td class="ppayslip-center-align"><?= htmlspecialchars($value['w7_special_holiday_overtime_perhour']) ?></td>
                <td class="ppayslip-td"><?= formatCurrency($value['w7']) ?></td>
            </tr>
            <?php endif; ?>

            <?php if (!is_null($value['w8_regular_holiday_overtime_perhour']) && $value['w8_regular_holiday_overtime_perhour'] != 0): ?>
            <tr class="ppayslip-tr">
                <td class="ppayslip-td">Regular Holiday Overtime/Hour</td>
                <td class="ppayslip-center-align"><?= htmlspecialchars($value['w8_regular_holiday_overtime_perhour']) ?></td>
                <td class="ppayslip-td"><?= formatCurrency($value['w8']) ?></td>
            </tr>
            <?php endif; ?>

            <?php if (!is_null($value['w9_cater']) && $value['w9_cater'] != 0): ?>
            <tr class="ppayslip-tr">
                <td class="ppayslip-td">Cater</td>
                <td class="ppayslip-center-align"><?= htmlspecialchars($value['w9_cater']) ?></td>
                <td class="ppayslip-td"><?= formatCurrency($value['w9']) ?></td>
            </tr>
            <?php endif; ?>

            <tr class="ppayslip-tr">
                <td class="ppayslip-td"><strong class="ppayslip-strong">Gross Pay</strong></td>
                <td class="ppayslip-td"></td>
                <td class="ppayslip-td"><strong class="ppayslip-strong"strong><?= formatCurrency($value['gross_pay']) ?></strong></td>
            </tr>
            </table>

            <h3 class="ppayslip-h3-special">Deductions</h3>
            <table class="ppayslip-table">
            <?php if ($value['sss'] != 0): ?>
            <tr class="ppayslip-tr">
                <td class="ppayslip-td">SSS</td>
      
                <td class="ppayslip-td"><?= formatCurrency($value['sss']) ?></td>
            </tr>
            <?php endif; ?>
            <?php if ($value['philhealth'] != 0): ?>
            <tr class="ppayslip-tr">
                <td class="ppayslip-td">PhilHealth</td>
                <td class="ppayslip-td"><?= formatCurrency($value['philhealth']) ?></td>
            </tr>
            <?php endif; ?>
            <?php if ($value['pagibig'] != 0): ?>
            <tr class="ppayslip-tr">
                <td class="ppayslip-td">Pag-IBIG</td>
                <td class="ppayslip-td"><?= formatCurrency($value['pagibig']) ?></td>   
            </tr>
            <?php endif; ?>
            <?php if ($value['cater1'] != 0): ?>
            <tr class="ppayslip-tr">
                <td class="ppayslip-td">CATER</td>
                <td class="ppayslip-td"><?= formatCurrency($value['cater1']) ?></td>
            </tr>
            <?php endif; ?>
            <?php if ($value['advance'] != 0): ?>
            <tr class="ppayslip-tr">        
                <td class="ppayslip-td">Advance</td>
                <td class="ppayslip-td"><?= formatCurrency($value['advance']) ?></td>          
            </tr>
            <?php endif; ?>
                <tr class="ppayslip-tr">
                    <td class="ppayslip-td">Total Deductions</td>
                    <td class="ppayslip-td"><?= formatCurrency($value['total_deductions']) ?></td>
                </tr>
            
                <tr class="ppayslip-tr">
                    <td  class="ppayslip-td"><strong class="ppayslip-strong">Net Pay</strong></td>
                    <td class="ppayslip-td"><strong class="ppayslip-strong"><?= formatCurrency($value['net_pay']) ?></strong></td>
                </tr>
            </table>

    </form>
