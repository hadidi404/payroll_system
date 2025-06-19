<?php
include 'db.php';
include 'rates.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    die("No ID provided.");
}

$sql = "SELECT * FROM employee_info WHERE id = ?";
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
    $daily_wage_rate = $_POST['daily_wage_rate'];
    $days_worked = $_POST['days_worked'];

    $gross_pay = $daily_wage_rate * $days_worked;

    $deductions = calculateDeductions($daily_wage_rate);
    $sss_deduction = $deductions['sss_deduction'];
    $pagibig_deduction = $deductions['pagibig_deduction'];
    $philhealth_deduction = $deductions['philhealth_deduction'];

    $total_non_taxable_deductions = $sss_deduction + $pagibig_deduction + $philhealth_deduction;

    $taxable_income = $gross_pay - $total_non_taxable_deductions;
    if ($taxable_income < 0) $taxable_income = 0;

    $tax_amount = $taxable_income * 0;
    $total_deductions = $total_non_taxable_deductions + $tax_amount;
    $net_pay = $gross_pay - $total_deductions;

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
        sss_deduction = ?,
        pagibig_deduction = ?,
        philhealth_deduction = ?,
        taxable_income = ?,
        tax_amount = ?,
        net_pay = ?
        WHERE id = ?";

    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssssssdiddddddds",
        $name, $position, $status, $board_lodging, $lodging_address, $food_allowance,
        $daily_wage_rate, $days_worked, $gross_pay, $sss_deduction, $pagibig_deduction,
        $philhealth_deduction, $taxable_income, $tax_amount, $net_pay, $id
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
    <title>Edit Employee</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <link href="../css/add_edit.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        #parent_div {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            gap: 20px;
            flex-wrap: wrap;
        }
        #info {
            display: flex;
            flex-direction: row;
            gap: 20px;
            width: 100%;
            max-width: 900px;
        }
        .column {
            flex: 1;
            min-width: 300px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        .column label, .column input, .column select {
            display: block;
            margin-bottom: 10px;
        }
        .column input[type="radio"] {
            width: auto;
            margin-right: 5px;
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

<div class="circle small"></div>
<div class="circle small two"></div>
<div class="circle medium"></div>
<div class="circle medium three"></div>
<div class="circle large"></div>

<div id="header_h">
    <a id="back" href="dashboard.php"><i class="fa-solid fa-arrow-left fa-2x"></i></a>
    <div id="description">
        <h2>Edit Employee</h2>
    </div>
</div>

<div id="parent_div">
    <form id="info" method="POST">
        <div class="column">
            <h3>Basic Information</h3>
            <label for="id">Employee ID:</label>
            <input type="text" value="<?= htmlspecialchars($employee['id']) ?>" disabled>
            <input type="hidden" name="id" value="<?= htmlspecialchars($employee['id']) ?>">

            <label for="name">Full Name:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($employee['name']) ?>" required>

            <label for="position">Position:</label>
            <input type="text" name="position" value="<?= htmlspecialchars($employee['position']) ?>" required>

            <label>Status:</label>
            <input type="radio" name="status" value="Permanent" <?= $employee['status'] === 'Permanent' ? 'checked' : '' ?> onchange="updateDailyWage()"> Permanent
            <input type="radio" name="status" value="On-Call" <?= $employee['status'] === 'On-Call' ? 'checked' : '' ?> onchange="updateDailyWage()"> On-Call

            <label>Board & Lodging:</label>
            <input type="radio" name="board_lodging" value="Yes" <?= $employee['board_lodging'] === 'Yes' ? 'checked' : '' ?> onchange="toggleAddress(true)"> Yes
            <input type="radio" name="board_lodging" value="No" <?= $employee['board_lodging'] === 'No' ? 'checked' : '' ?> onchange="toggleAddress(false)"> No

            <div id="addressField" style="display:none;">
                <input type="text" name="lodging_address" id="lodging_address" placeholder="Address" value="<?= htmlspecialchars($employee['lodging_address']) ?>">
            </div>

            <label for="food_allowance">Food Allowance:</label>
            <select name="food_allowance" required>
                <option value="Full" <?= $employee['food_allowance'] === 'Full' ? 'selected' : '' ?>>Full</option>
                <option value="Partial" <?= $employee['food_allowance'] === 'Partial' ? 'selected' : '' ?>>Partial</option>
                <option value="None" <?= $employee['food_allowance'] === 'None' ? 'selected' : '' ?>>None</option>
            </select>
        </div>

        <div class="column">
            <h3>Payroll Details</h3>
            <label for="daily_wage_rate">Daily Wage Rate:</label>
            <input type="number" step="0.01" name="daily_wage_rate" id="daily_wage_rate" value="<?= htmlspecialchars($employee['daily_wage_rate']) ?>" required oninput="calculatePayroll()">

            <label for="days_worked">Days Worked:</label>
            <input type="number" step="1" name="days_worked" id="days_worked" value="<?= htmlspecialchars($employee['days_worked']) ?>" required oninput="calculatePayroll()">

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
    <button type="submit" class="submit-btn" form="info">Update Employee</button>
</div>

<script>
function toggleAddress(show) {
    const field = document.getElementById('addressField');
    const input = document.getElementById('lodging_address');
    field.style.display = show ? 'block' : 'none';
    if (show) input.required = true;
    else input.required = false;
}

function updateDailyWage() {
    const wage = document.getElementById('daily_wage_rate');
    const status = document.querySelector('input[name="status"]:checked').value;
    if (status === 'Permanent') wage.value = 800.00;
    else wage.value = '';
    calculatePayroll();
}

function calculatePayroll() {
    const dailyWage = parseFloat(document.getElementById('daily_wage_rate').value) || 0;
    const daysWorked = parseFloat(document.getElementById('days_worked').value) || 0;
    const gross = dailyWage * daysWorked;

    const monthlyBase = dailyWage * 20;
    const sss = monthlyBase * 0.05;
    const pagibig = monthlyBase * 0.02;
    const philhealth = monthlyBase * 0.025;

    const totalDeductions = sss + pagibig + philhealth;
    const taxableIncome = Math.max(0, gross - totalDeductions);
    const tax = 0;
    const netPay = gross - totalDeductions - tax;

    document.getElementById('sss_deduction_output').textContent = sss.toFixed(2);
    document.getElementById('pagibig_deduction_output').textContent = pagibig.toFixed(2);
    document.getElementById('philhealth_deduction_output').textContent = philhealth.toFixed(2);
    document.getElementById('total_non_taxable_deductions_output').textContent = totalDeductions.toFixed(2);
    document.getElementById('gross_pay_output').textContent = gross.toFixed(2);
    document.getElementById('taxable_income_output').textContent = taxableIncome.toFixed(2);
    document.getElementById('net_pay_output').textContent = netPay.toFixed(2);
}

document.addEventListener('DOMContentLoaded', () => {
    toggleAddress(document.querySelector('input[name="board_lodging"]:checked').value === 'Yes');
    updateDailyWage();
});
</script>

</body>
</html>
