<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: authentication.php");
    exit();
}

include 'db.php';
include 'rates.php';

$id = $_GET['employee_id'] ?? null;
if (!$id) {
    die("No ID provided.");
}

$sql = "SELECT * FROM employee_info WHERE employee_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Employee not found.");
}

$employee = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $position = $_POST['position'];
    $status = $_POST['status'];
    $board_lodging = $_POST['board_lodging'];
    $lodging_address = $_POST['lodging_address'] ?? null;
    $food_allowance = $_POST['food_allowance'];
    $daily_wage_rate = (float)$_POST['daily_wage_rate'];
    $days_worked = (float)$_POST['days_worked'];

    $daily_wage_rate = (float)$_POST['daily_wage_rate'];
    $days_worked = (float)$_POST['days_worked'];

    // Use the calculateDeductions function from rates.php for all payroll calculations
    $payroll_calculations = calculateDeductions($daily_wage_rate, $days_worked);

    $gross_pay = $payroll_calculations['gross_pay'];
    $sss = $payroll_calculations['sss_deduction'];
    $pagibig = $payroll_calculations['pagibig_deduction'];
    $philhealth = $payroll_calculations['philhealth_deduction'];
    $total_non_taxable_deductions = $payroll_calculations['total_non_taxable_deductions'];
    $taxable_income = $payroll_calculations['taxable_income'];
    $tax = $payroll_calculations['tax'];
    $net_pay = $payroll_calculations['net_pay'];

    $update_sql = "UPDATE employee_info SET 
        name = ?, 
        position = ?, 
        status = ?, 
        board_lodging = ?, 
        lodging_address = ?, 
        food_allowance = ?,
        daily_wage_rate = ?,
        days_worked = ?,
        gross_pay = ?,
        sss = ?,
        philhealth = ?,
        pagibig = ?,
        taxable_income = ?,
        tax = ?,
        net_pay = ?
        WHERE id = ?";

    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssssssdiddddds",
        $name, $position, $status, $board_lodging, $lodging_address, $food_allowance,
        $daily_wage_rate, $days_worked, $gross_pay, $sss, $philhealth,
        $pagibig, $taxable_income, $tax, $net_pay, $id
    );

    if ($update_stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error updating: " . $update_stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="../css/edit.css" rel="stylesheet"> <!-- Changed to edit.css -->
    <link href="../css/payroll_calculations.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Edit Employee</title>
    <style>
        /* Specific styles for edit.php if any, otherwise this can be empty or removed */
    </style>
</head>
<body>

<div id="header_h">
    <a id="back" href="dashboard.php"><i class="fa-solid fa-arrow-left fa-2x"></i></a>
    <div id="description">
        <h2>Edit Employee</h2>
    </div>
</div>

<div id="parent_div">
    <form id="info" method="POST">
        <!-- Column 1: Basic Employee Info -->
        <div class="column">
            <h3>Basic Information</h3>
            <label for="id">Employee ID:</label>
            <input id="blank_text" type="text" value="<?= htmlspecialchars($employee['employee_id']) ?>" disabled>
            <input type="hidden" name="employee_id" value="<?= htmlspecialchars($employee['employee_id']) ?>">

            <label for="name">Full Name:</label>
            <input id="blank_text" type="text" name="name" value="<?= htmlspecialchars($employee['name']) ?>" required>

            <label for="position">Position:</label>
            <input id="blank_text" type="text" name="position" value="<?= htmlspecialchars($employee['position']) ?>" required>

            <div class="toggle-group-lodging">
                <label style="margin-top: 10px;" id="text_info_status">Status:</label>
                <div id="toggle-group-one">
                    <label>Permanent</label>
                    <input style="margin-left: -80px;" id="blank_text" type="radio" name="status" value="Permanent" <?= $employee['status'] === 'Permanent' ? 'checked' : '' ?> required onchange="updateDailyWage()"> 
                </div>
                <div id="toggle-group-one">
                    <label>On&#8209;Call</label>
                    <input style="margin-left: -48px;" id="blank_text" type="radio" name="status" value="On-Call" <?= $employee['status'] === 'On-Call' ? 'checked' : '' ?> required onchange="updateDailyWage()"> 
                </div>
            </div>

            <div class="toggle-group-lodging">
                <label style="margin-top: 10px;" id="text_info_lodging">Board & Lodging:</label>
                <div id="yes_section">
                    <div id="toggle-group-one">
                        <label>Yes</label>
                        <input style="margin-left: 161px;" id="blank_text" type="radio" name="board_lodging" value="Yes" <?= $employee['board_lodging'] === 'Yes' ? 'checked' : '' ?> required onchange="toggleAddress(true)"> 
                    </div>
                    <div id="addressField" style="display:none; margin-top:10px;">
                        <input id="blank_text" type="text" name="lodging_address" id="lodging_address" placeholder="Address" value="<?= htmlspecialchars($employee['lodging_address']) ?>" style="width: calc(100% + 8px);">
                    </div>  
                </div>    
                <div id="toggle-group-one">
                    <label>No</label>
                    <input style="margin-left: -7px;" id="blank_text" type="radio" name="board_lodging" value="No" <?= $employee['board_lodging'] === 'No' ? 'checked' : '' ?> required onchange="toggleAddress(false)"> 
                </div> 
            </div>

            <label id="text_info" for="food_allowance">Food Allowance:</label>
            <select name="food_allowance" required>
                <option value="Full" <?= $employee['food_allowance'] === 'Full' ? 'selected' : '' ?>>Full</option>
                <option value="Partial" <?= $employee['food_allowance'] === 'Partial' ? 'selected' : '' ?>>Partial</option>
                <option value="None" <?= $employee['food_allowance'] === 'None' ? 'selected' : '' ?>>None</option>
            </select>
        </div>

        <!-- Column 2: Rates and Calculations -->
        <div class="column">
            <h3>Payroll Details</h3>
            <label for="daily_wage_rate">Daily Minimum Wage Rate:</label>
            <input id="blank_text" type="number" step="0.01" name="daily_wage_rate" id="daily_wage_rate" value="<?= htmlspecialchars($employee['daily_wage_rate']) ?>" required>

            <label for="days_worked">Days Worked:</label>
            <input id="blank_text" type="number" step="1" name="days_worked" id="days_worked" value="<?= htmlspecialchars($employee['days_worked']) ?>" required>

            <button type="button" onclick="calculatePayroll()">Compute</button>

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
        <button type="submit" class="submit-btn" form="info">Update Employee</button>
    </div>
</div>

<script src="../js/payroll_calculations.js"></script>

</body>
</html>
