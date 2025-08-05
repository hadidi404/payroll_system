
<div class="container">
    
    <h2 id="text">AI Korean Buffet Restaurant</h2>
    <h2 id="text">MH del pilar Burnham Legarda road, Baguio City, Philippines</h2>

    <div id="basic_info">
        <div id="first">
            <p class="info"><strong>ID:</strong> <?= htmlspecialchars($value['employee_id']) ?></p>
            <p><strong>Name:</strong> <?= strtoupper(htmlspecialchars($value['last_name'])) ?>, <?= htmlspecialchars($value['first_name']) ?></p>
        </div>
        <div id="second">
            <p class="info"><strong>Position:</strong> <?= htmlspecialchars($value['position']) ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($value['status']) ?></p>
        </div>

    </div>

    <h3>Work Details</h3>
    <table>
    <tr>
        <td></td>
        <td>Rates</td>
        <td>Days/Hours</td>
        <td>Total</td>
    </tr>
    <?php if (!is_null($value['w1_daily_minimum_wage']) && $value['w1_daily_minimum_wage'] != 0): ?>
    <tr>
        <td>Daily Minimum Wage</td>
        <td>₱ <?= number_format((float)$value['w1'] / max((float)$value['w1_daily_minimum_wage'], 1), 2) ?></td>
        <td class="center-align"><?= htmlspecialchars($value['w1_daily_minimum_wage']) ?></td>
        <td><?= formatCurrency($value['w1']) ?></td>
    </tr>
    <?php endif; ?>

    <?php if (!is_null($value['w2_sunday_rest_day']) && $value['w2_sunday_rest_day'] != 0): ?>
    <tr>
        <td>Sunday Rest Day</td>
        <td>₱ <?= number_format((float)$value['w2'] / max((float)$value['w2_sunday_rest_day'], 1), 2) ?></td>
        <td class="center-align"><?= htmlspecialchars($value['w2_sunday_rest_day']) ?></td>
        <td><?= formatCurrency($value['w2']) ?></td>
    </tr>
    <?php endif; ?>

    <?php if (!is_null($value['w3_legal_holiday']) && $value['w3_legal_holiday'] != 0): ?>
    <tr>
        <td>Legal Holiday</td>
        <td>₱ <?= number_format((float)$value['w3'] / max((float)$value['w3_legal_holiday'], 1), 2) ?></td>
        <td class="center-align"><?= htmlspecialchars($value['w3_legal_holiday']) ?></td>
        <td><?= formatCurrency($value['w3']) ?></td>
    </tr>
    <?php endif; ?>

    <?php if (!is_null($value['w4_special_holiday']) && $value['w4_special_holiday'] != 0): ?>
    <tr>
        <td>Special Holiday</td>
        <td>₱ <?= number_format((float)$value['w4'] / max((float)$value['w4_special_holiday'], 1), 2) ?></td>
        <td class="center-align"><?= htmlspecialchars($value['w4_special_holiday']) ?></td>
        <td><?= formatCurrency($value['w4']) ?></td>
    </tr>
    <?php endif; ?>

    <?php if (!is_null($value['w5_regular_overtime_perhour']) && $value['w5_regular_overtime_perhour'] != 0): ?>
    <tr>
        <td>Regular Overtime/Hour</td>
        <td>₱ <?= number_format((float)$value['w5'] / max((float)$value['w5_regular_overtime_perhour'], 1), 2) ?></td>
        <td class="center-align"><?= htmlspecialchars($value['w5_regular_overtime_perhour']) ?></td>
        <td><?= formatCurrency($value['w5']) ?></td>
    </tr>
    <?php endif; ?>

    <?php if (!is_null($value['w6_special_overtime_perhour']) && $value['w6_special_overtime_perhour'] != 0): ?>
    <tr>
        <td>Special Overtime/Hour</td>
        <td>₱ <?= number_format((float)$value['w6'] / max((float)$value['w6_special_overtime_perhour'], 1), 2) ?></td>
        <td class="center-align"><?= htmlspecialchars($value['w6_special_overtime_perhour']) ?></td>
        <td><?= formatCurrency($value['w6']) ?></td>
    </tr>
    <?php endif; ?>

    <?php if (!is_null($value['w7_special_holiday_overtime_perhour']) && $value['w7_special_holiday_overtime_perhour'] != 0): ?>
    <tr>
        <td>Special Holiday Overtime/Hour</td>
        <td>₱ <?= number_format((float)$value['w7'] / max((float)$value['w7_special_holiday_overtime_perhour'], 1), 2) ?></td>
        <td class="center-align"><?= htmlspecialchars($value['w7_special_holiday_overtime_perhour']) ?></td>
        <td><?= formatCurrency($value['w7']) ?></td>
    </tr>
    <?php endif; ?>

    <?php if (!is_null($value['w8_regular_holiday_overtime_perhour']) && $value['w8_regular_holiday_overtime_perhour'] != 0): ?>
    <tr>
        <td>Regular Holiday Overtime/Hour</td>
        <td>₱ <?= number_format((float)$value['w8'] / max((float)$value['w8_regular_holiday_overtime_perhour'], 1), 2) ?></td>
        <td class="center-align"><?= htmlspecialchars($value['w8_regular_holiday_overtime_perhour']) ?></td>
        <td><?= formatCurrency($value['w8']) ?></td>
    </tr>
    <?php endif; ?>

    <?php if (!is_null($value['w9_cater']) && $value['w9_cater'] != 0): ?>
    <tr>
        <td>Cater</td>
        <td>₱ <?= number_format((float)$value['w9'] / max((float)$value['w9_cater'], 1), 2) ?></td>
        <td class="center-align"><?= htmlspecialchars($value['w9_cater']) ?></td>
        <td><?= formatCurrency($value['w9']) ?></td>
    </tr>
    <?php endif; ?>

    <tr>
        <td><strong>Gross Pay</strong></td>
        <td></td>
        <td></td>
        <td><strong><?= formatCurrency($value['gross_pay']) ?></strong></td>
    </tr>
    </table>

    <h3>Deductions</h3>
    <table>
    <?php if ($value['sss'] != 0): ?>
    <tr>
        <td>SSS</td>
        <td><?= formatCurrency($value['gross_pay']) ?></td>
        <td> x 5% </td>
        <td><?= formatCurrency($value['sss']) ?></td>
    </tr>
    <?php endif; ?>
    <?php if ($value['philhealth'] != 0): ?>
    <tr>
        <td>PhilHealth</td>
        <td><?= formatCurrency($value['gross_pay']) ?></td>
        <td> x 2% </td>
        <td><?= formatCurrency($value['philhealth']) ?></td>
    </tr>
    <?php endif; ?>
    <?php if ($value['pagibig'] != 0): ?>
    <tr>
        <td>Pag-IBIG</td>
        <td><?= formatCurrency($value['gross_pay']) ?></td>
        <td> x 2% </td>
        <td><?= formatCurrency($value['pagibig']) ?></td>   
    </tr>
    <?php endif; ?>
    <?php if ($value['cater1'] != 0): ?>
    <tr>
        <td>CATER</td>
        <td></td>
        <td></td>
        <td><?= formatCurrency($value['cater1']) ?></td>
    </tr>
    <?php endif; ?>
    <?php if ($value['advance'] != 0): ?>
    <tr>        
        <td>Advance</td>
        <td></td>
        <td></td>
        <td><?= formatCurrency($value['advance']) ?></td>          
    </tr>
    <?php endif; ?>
        <tr class="total">
            <td><strong>Total Deductions</strong></td>
            <td></td>
            <td></td>
            <td><?= formatCurrency($value['total_deductions']) ?></td>
        </tr>
    
        <tr>
            <td><strong>Net Pay</strong></td>
            <td><?= formatCurrency($value['total_deductions']) ?></td>
            <td><?= formatCurrency($value['total_deductions']) ?></td> 
            <td><strong><?= formatCurrency($value['net_pay']) ?></strong></td>
        </tr>
    </table>
</div>



