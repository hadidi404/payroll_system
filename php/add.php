<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: authentication.php");
    exit();
}

include 'db.php';
include 'rates.php'; // Include the rates.php file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from form (Column 1)
    $id = $_POST['id'];
    $name = $_POST['name'];
    $position = $_POST['position'];
    $status = $_POST['status'];
    $board_lodging = $_POST['board_lodging'];
    $lodging_address = $_POST['lodging_address'] ?? null;
    $food_allowance = $_POST['food_allowance'];

    // Get data from form (Column 2)
    $daily_wage_rate = $_POST['daily_wage_rate'];
    $days_worked = $_POST['days_worked'];

    // Calculations
    $gross_pay = $daily_wage_rate * $days_worked;

    $deduction_amounts = calculateDeductions($daily_wage_rate);
    $sss = $deduction_amounts['sss_deduction'];
    $pagibig = $deduction_amounts['pagibig_deduction'];
    $philhealth = $deduction_amounts['philhealth_deduction'];

    $total_non_taxable_deductions = $sss + $pagibig + $philhealth;

    $tax_rate = 0; // Default 0%
    $taxable_income = $gross_pay - $total_non_taxable_deductions;
    if ($taxable_income < 0) $taxable_income = 0; // Taxable income cannot be negative

    $tax = $taxable_income * $tax_rate;

    $total_deductions = $total_non_taxable_deductions + $tax;
    $net_pay = $gross_pay - $total_deductions;


    // Check if the ID already exists
    $check_sql = "SELECT COUNT(*) FROM employee_info WHERE id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $id);
    $check_stmt->execute();
    $check_stmt->bind_result($id_count);
    $check_stmt->fetch();
    $check_stmt->close();

    if ($id_count > 0) {
        echo "<script>alert('Error: Employee ID is already in use.'); window.history.back();</script>";
        exit();
    }

    // Insert into database
    $sql = "INSERT INTO employee_info (id, name, position, status, board_lodging, lodging_address, food_allowance, daily_wage_rate, days_worked, gross_pay, sss, philhealth, pagibig, taxable_income, tax, net_pay)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssdidddddds",
        $id, $name, $position, $status, $board_lodging, $lodging_address, $food_allowance,
        $daily_wage_rate, $days_worked, $gross_pay, $sss, $philhealth,
        $pagibig, $taxable_income, $tax, $net_pay
    );

    if ($stmt->execute()) {
        header("Location: dashboard.php"); // Redirect to dashboard after successful add
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="../css/add.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Add Employee</title>
    <style>
        /* Basic two-column layout */
        #parent_div {
            display: flex;
            justify-content: center;
            align-items: flex-start; /* Align items to the top */
            gap: 20px; /* Space between columns */
            flex-wrap: wrap; /* Allow wrapping on smaller screens */
        }
        #info {
            display: flex;
            flex-direction: row; /* Arrange children in a row */
            gap: 20px; /* Space between the two main columns */
            width: 100%; /* Take full width of parent_div */
            max-width: 900px; /* Limit overall width */
        }
        .column {
            flex: 1; /* Each column takes equal space */
            min-width: 300px; /* Minimum width for columns before wrapping */
            padding: 20px;
            border: 1px solid #ccc; /* Optional: for visualization */
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        .column label, .column input, .column select, .column .toggle-group-lodging {
            display: block;
            margin-bottom: 10px;
        }
        .column input[type="radio"] {
            display: inline-block;
            width: auto;
            margin-right: 5px;
        }
        .column .toggle-group-lodging label {
            display: inline; /* For radio button labels */
        }
        .column #addressField {
            display: none; /* Hidden by default */
            margin-top: 10px;
        }
        .column #blank_text, .column select {
            width: calc(100% - 22px); /* Adjust for padding/border */
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .column h3 {
            margin-top: 0;
            color: #333;
        }
        .calculation-output {
            margin-top: 15px;
            padding: 10px;
            background-color: #e9e9e9;
            border-radius: 5px;
        }
        .calculation-output p {
            margin: 5px 0;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div id="header_h">
    <a id="back" href="dashboard.php"><i class="fa-solid fa-arrow-left fa-2x"></i></a>
    <div id="description">
        <h2>Add New Employee</h2>
    </div>
</div>

<div id="parent_div">
    <form id="info" method="POST">
        <!-- Column 1: Basic Employee Info -->
        <div class="column">
            <h3>Basic Information</h3>
            <label for="id">Employee ID:</label>
            <input id="blank_text" type="text" name="id" required>

            <label for="name">Full Name:</label>
            <input id="blank_text" type="text" name="name" required>

            <label for="position">Position:</label>
            <input id="blank_text" type="text" name="position" required>

            <div class="toggle-group-lodging">
                <label style="margin-top: 10px;" id="text_info_status">Status:</label>
                <div id="toggle-group-one">
                    <label>Permanent</label>
                    <input style="margin-left: -80px;" id="blank_text" type="radio" name="status" value="Permanent" required onchange="updateDailyWage()"> 
                </div>
                <div id="toggle-group-one">
                    <label>On&#8209;Call</label>
                    <input style="margin-left: -48px;" id="blank_text" type="radio" name="status" value="On-Call" required onchange="updateDailyWage()"> 
                </div>
            </div>

            <div class="toggle-group-lodging">
                <label style="margin-top: 10px;" id="text_info_lodging">Board & Lodging:</label>
                <div id="yes_section">
                    <div id="toggle-group-one">
                        <label>Yes</label>
                        <input style="margin-left: 161px;" id="blank_text" type="radio" name="board_lodging" value="Yes" required onchange="toggleAddress(true)"> 
                    </div>
                    <div id="addressField" style="margin-left: 10px;">
                        <input type="text" name="lodging_address" id="blank_text" placeholder="Address">
                    </div> 
                </div>     
                <div id="toggle-group-one">
                    <label>No</label>
                    <input style="margin-left: -7px;" id="blank_text" type="radio" name="board_lodging" value="No" required onchange="toggleAddress(false)"> 
                </div>  
            </div>

            <label id="text_info" for="food_allowance">Food Allowance:</label>
            <select name="food_allowance" required>
                <option value="Full">Full</option>
                <option value="Partial">Partial</option>
                <option value="None">None</option>
            </select>
        </div>

        <!-- Column 2: Rates and Calculations -->
        <div class="column">
            <h3>Payroll Details</h3>
            <label for="daily_wage_rate">Daily Minimum Wage Rate:</label>
            <input id="blank_text" type="number" step="0.01" name="daily_wage_rate" required oninput="calculatePayroll()">

            <label for="days_worked">Days Worked:</label>
            <input id="blank_text" type="number" step="1" name="days_worked" value="0" required oninput="calculatePayroll()">

            <h4>Deductions (Non-Taxable)</h4>
            <p>SSS (5%): <span id="sss_deduction_output">0.00</span></p>
            <p>Pag-IBIG (2%): <span id="pagibig_deduction_output">0.00</span></p>
            <p>PhilHealth (2.5%): <span id="philhealth_deduction_output">0.00</span></p>
            <p>Total Non-Taxable Deductions: <span id="total_non_taxable_deductions_output">0.00</span></p>

            <div class="calculation-output">
                <p>Gross Pay: <span id="gross_pay_output">0.00</span></p>
                <p>Taxable Income: <span id="taxable_income_output">0.00</span></p>
                <p>Net Pay: <span id="net_pay_output">0.00</span></p>
            </div>
        </div>
    </form>
    <div id="submit_div">
        <button type="submit" class="submit-btn" form="info">Add Employee</button>
    </div>
</div>

<script>
function toggleAddress(show) {
    const addressField = document.getElementById('addressField');
    if (show) {
        addressField.style.display = 'flex';
        document.getElementById('lodging_address').setAttribute('required', 'required');
    } else {
        addressField.style.display = 'none';
        document.getElementById('lodging_address').removeAttribute('required');
    }
}

function updateDailyWage() {
    const statusRadios = document.querySelectorAll('input[name="status"]');
    const dailyWageInput = document.getElementById('daily_wage_rate');
    let selectedStatus = '';

    statusRadios.forEach(radio => {
        if (radio.checked) {
            selectedStatus = radio.value;
        }
    });

    if (selectedStatus === 'Permanent') {
        dailyWageInput.value = 800.00;
    } else if (selectedStatus === 'On-Call') {
        dailyWageInput.value = '';
    }
    calculatePayroll(); // Recalculate when status changes
}

function calculatePayroll() {
    const dailyWage = parseFloat(document.getElementById('daily_wage_rate').value) || 0;
    const daysWorked = parseFloat(document.getElementById('days_worked').value) || 0;

    const grossPay = dailyWage * daysWorked;

    // Deductions (based on daily wage for simplicity, adjust if monthly basis is needed)
    // Note: The PHP calculateDeductions function assumes monthly_salary = hour_rate * 160.
    // For this JS, we'll use dailyWage * 20 (approx working days in a month) as a proxy for monthly basis for deductions.
    // This needs to be consistent with the PHP side.
    const monthlyBasisForDeductions = dailyWage * 20; // Assuming 20 working days for monthly deduction basis

    const sssDeduction = monthlyBasisForDeductions * 0.05;
    const pagibigDeduction = monthlyBasisForDeductions * 0.02;
    const philhealthDeduction = monthlyBasisForDeductions * 0.025;

    const totalNonTaxableDeductions = sssDeduction + pagibigDeduction + philhealthDeduction;

    const taxRate = 0; // Default 0%
    let taxableIncome = grossPay - totalNonTaxableDeductions;
    if (taxableIncome < 0) taxableIncome = 0;

    const taxAmount = taxableIncome * taxRate;

    const totalDeductions = totalNonTaxableDeductions + taxAmount;
    const netPay = grossPay - totalDeductions;

    document.getElementById('sss_deduction_output').textContent = sssDeduction.toFixed(2);
    document.getElementById('pagibig_deduction_output').textContent = pagibigDeduction.toFixed(2);
    document.getElementById('philhealth_deduction_output').textContent = philhealthDeduction.toFixed(2);
    document.getElementById('total_non_taxable_deductions_output').textContent = totalNonTaxableDeductions.toFixed(2);
    document.getElementById('gross_pay_output').textContent = grossPay.toFixed(2);
    document.getElementById('taxable_income_output').textContent = taxableIncome.toFixed(2);
    document.getElementById('net_pay_output').textContent = netPay.toFixed(2);
}

// Initial calculation on page load
document.addEventListener('DOMContentLoaded', () => {
    updateDailyWage(); // Set default wage if Permanent is selected
    calculatePayroll(); // Perform initial calculation
});
</script>

</body>
</html>
