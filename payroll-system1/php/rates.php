<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: authentication.php");
    exit();
}

// This file contains logic for calculating payroll rates and deductions.

// Function to calculate non-taxable deductions based on hour rate
function calculateDeductions($daily_wage_rate, $days_worked) {
    // "result = daily minimum wage * 15 days(constant value)"
    $result_deduction_base = $daily_wage_rate * 15;

    // Deductions based off the result_deduction_base
    $sss_deduction = $result_deduction_base * 0.05; // Assuming 5% SSS
    $pagibig_deduction = $result_deduction_base * 0.02; // Assuming 2% Pag-IBIG
    $philhealth_deduction = $result_deduction_base * 0.025; // Assuming 2.5% PhilHealth

    // "add all the deductions = total non taxable deductions"
    $total_non_taxable_deductions = $sss_deduction + $pagibig_deduction + $philhealth_deduction;

    // "gross pay= daily minimum wage * days worked"
    $gross_pay = $daily_wage_rate * $days_worked;

    $tax_rate = 0; // Default 0%
    $taxable_income = $gross_pay - $total_non_taxable_deductions;
    if ($taxable_income < 0) $taxable_income = 0; // Taxable income cannot be negative

    $tax = $taxable_income * $tax_rate;

    $total_deductions = $total_non_taxable_deductions + $tax;
    // "net pay = result 2 - total non taxable deductions" (result 2 is gross pay)
    $net_pay = $gross_pay - $total_deductions;

    return [
        'sss_deduction' => $sss_deduction,
        'pagibig_deduction' => $pagibig_deduction,
        'philhealth_deduction' => $philhealth_deduction,
        'total_non_taxable_deductions' => $total_non_taxable_deductions,
        'gross_pay' => $gross_pay,
        'taxable_income' => $taxable_income,
        'tax' => $tax,
        'total_deductions' => $total_deductions,
        'net_pay' => $net_pay
    ];
}
?>
