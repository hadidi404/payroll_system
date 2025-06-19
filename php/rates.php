<?php
// This file contains logic for calculating payroll rates and deductions.

// Function to calculate non-taxable deductions based on hour rate
function calculateDeductions($hour_rate) {
    $monthly_salary = $hour_rate * 160; // Assuming 160 working hours per month

    $sss_deduction = $monthly_salary * 0.05;
    $pagibig_deduction = $monthly_salary * 0.02;
    $philhealth_deduction = $monthly_salary * 0.025;

    return [
        'monthly_salary' => $monthly_salary,
        'sss_deduction' => $sss_deduction,
        'pagibig_deduction' => $pagibig_deduction,
        'philhealth_deduction' => $philhealth_deduction
    ];
}
?>
