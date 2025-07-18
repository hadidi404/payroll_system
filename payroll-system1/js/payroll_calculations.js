function toggleAddress(show) {
    const addressField = document.getElementById('addressField');
    const lodgingAddressInput = document.getElementById('lodging_address'); // Added for edit.php compatibility
    if (show) {
        addressField.style.display = 'flex';
        // Check if lodgingAddressInput exists before setting attribute
        if (lodgingAddressInput) { 
            lodgingAddressInput.setAttribute('required', 'required');
        }
    } else {
        addressField.style.display = 'none';
        // Check if lodgingAddressInput exists before removing attribute
        if (lodgingAddressInput) {
            lodgingAddressInput.removeAttribute('required');
        }
    }
}

function calculatePayroll() {
    let grossPay = 0;
    const rateList = [
        { name: "daily_minimum_wage", rate: 800.00 },
        { name: "sunday_rest_day", rate: 611.00 },
        { name: "legal_holiday", rate: 940.00 },
        { name: "special_holiday", rate: 611.00 },
        // Add other rates here
    ];

    rateList.forEach(rate => {
        const quantity = parseFloat(document.querySelector(`[name="${rate.name}_hours"]`).value) || 0;
        grossPay += rate.rate * quantity;
    });

    // "result = daily minimum wage * 15 days(constant value)" - This logic seems to be for a base deduction, not gross pay.
}

function calculatePayroll() {
    const dailyWage = parseFloat(document.getElementById('daily_wage_rate').value) || 0;
    const daysWorked = parseFloat(document.getElementById('days_worked').value) || 0;

    // "result = daily minimum wage * 15 days(constant value)"
    const resultDeductionBase = dailyWage * 15;

    // Deductions based off the result_deduction_base
    const sssDeduction = resultDeductionBase * 0.05; // Assuming 5% SSS
    const pagibigDeduction = resultDeductionBase * 0.02; // Assuming 2% Pag-IBIG
    const philhealthDeduction = resultDeductionBase * 0.025; // Assuming 2.5% PhilHealth

    // "add all the deductions = total non taxable deductions"
    const totalNonTaxableDeductions = sssDeduction + pagibigDeduction + philhealthDeduction;

    // "gross pay= daily minimum wage * days worked"
    const grossPay = dailyWage * daysWorked;

    const taxRate = 0; // Default 0%
    let taxableIncome = grossPay - totalNonTaxableDeductions;
    if (taxableIncome < 0) taxableIncome = 0; // Taxable income cannot be negative

    const taxAmount = taxableIncome * taxRate;

    const totalDeductions = totalNonTaxableDeductions + taxAmount;
    // "net pay = result 2 - total non taxable deductions" (result 2 is gross pay)
    const netPay = grossPay - totalDeductions;

    document.getElementById('sss_deduction_output').textContent = sssDeduction.toFixed(2);
    document.getElementById('pagibig_deduction_output').textContent = pagibigDeduction.toFixed(2);
    document.getElementById('philhealth_deduction_output').textContent = philhealthDeduction.toFixed(2);
    document.getElementById('total_non_taxable_deductions_output').textContent = totalNonTaxableDeductions.toFixed(2);
    document.getElementById('gross_pay_output').textContent = grossPay.toFixed(2);
    document.getElementById('taxable_income_output').textContent = taxableIncome.toFixed(2);
    document.getElementById('net_pay_output').textContent = netPay.toFixed(2);
}

// Initial setup on page load
document.addEventListener('DOMContentLoaded', () => {
    // Initialize address field visibility based on current employee data for edit.php
    const boardLodgingRadios = document.querySelectorAll('input[name="board_lodging"]');
    let currentBoardLodging = '';
    boardLodgingRadios.forEach(radio => {
        if (radio.checked) {
            currentBoardLodging = radio.value;
        }
    });
    toggleAddress(currentBoardLodging === 'Yes');
    
    // Call updateDailyWage to set initial daily wage based on status
    updateDailyWage(); 
    // Perform initial calculation on page load
    calculatePayroll();
});
