<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from form
    $id = $_POST['id'];
    $name = $_POST['name'];
    $position = $_POST['position'];
    $status = $_POST['status'];
    $deductions = isset($_POST['deductions']) ? implode(',', $_POST['deductions']) : '';
    $board_lodging = $_POST['board_lodging'];
    $food_allowance = $_POST['food_allowance'];
    $lodging_address = $_POST['lodging_address'] ?? null;

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

    // Insert record
    $sql = "INSERT INTO employee_info (id, name, position, status, deductions, board_lodging, food_allowance, lodging_address,sss,philhealth,pagibig,tax)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $sss = isset($_POST['sss_amount']) ? $_POST['sss_amount'] : null;  
    $philhealth = isset($_POST['philhealth_amount']) ? $_POST['philhealth_amount'] : null;
    $pagibig = isset($_POST['pagibig_amount']) ? $_POST['pagibig_amount'] : null;
    $tax = isset($_POST['tax_amount']) ? $_POST['tax_amount'] : null;
    if ($board_lodging === 'No') {
        $lodging_address = null; // Set to null if board_lodging is 'No'
    } 
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssss", $id, $name, $position, $status, $deductions, $board_lodging, $food_allowance, $lodging_address,$sss, $philhealth,$pagibig,$tax);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Employee</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8f9fa; padding: 30px; }
        form {
            background-color: #fff;
            padding: 20px;
            max-width: 500px;
            margin: auto;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        label { display: block; margin-top: 10px; }
        input, select {
            width: 100%;
            padding: 8px;
            margin-top: 4px;
        }
        .toggle-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 4px;
        }
        .deduction-box {
            margin-top: 10px;
        }
        .submit-btn {
            background-color: #28a745;
            color: white;
            margin-top: 20px;
            padding: 10px;
            width: 100%;
            border: none;
            border-radius: 4px;
        }
        a { display: block; text-align: center; margin-top: 15px; color: #007bff; }
    </style>
</head>
<body>

<h2 style="text-align:center;">Add New Employee</h2>

<form method="POST">
    <label for="id">Employee ID:</label>
    <input type="text" name="id" required>

    <label for="name">Full Name:</label>
    <input type="text" name="name" required>

    <label for="position">Position:</label>
    <input type="text" name="position" required>

      <label>Status:</label>
    <div class="toggle-group">
        <label><input type="radio" name="status" value="Permanent" required> Permanent</label>
        <label><input type="radio" name="status" value="On-Call" required> On-Call</label>
    </div>

    <label>Deductions:</label>
    <div class="toggle-group deduction-box">
        <div>
            <label><input type="checkbox" name="deductions[]" value="sss" onchange="toggleDeduction('sss')"> SSS</label>
            <input type="number" name="sss_amount" id="sss_input" placeholder="SSS Amount" style="display:none;">
        </div>
        <div>
            <label><input type="checkbox" name="deductions[]" value="philhealth" onchange="toggleDeduction('philhealth')"> PhilHealth</label>
            <input type="number" step="0.01" name="philhealth_amount" id="philhealth_input" placeholder="PhilHealth Amount" style="display:none;">
        </div>
        <div>
            <label><input type="checkbox" name="deductions[]" value="pagibig" onchange="toggleDeduction('pagibig')"> Pag-IBIG</label>
            <input type="number" step="0.01" name="pagibig_amount" id="pagibig_input" placeholder="Pag-IBIG Amount" style="display:none;">
        </div>
        <div>
            <label><input type="checkbox" name="deductions[]" value="tax" onchange="toggleDeduction('tax')"> Tax</label>
            <input type="number" step="0.01" name="tax_amount" id="tax_input" placeholder="Tax Amount" style="display:none;">
        </div>
        
        
    </div>

    <label>Board & Lodging:</label>
    <div class="toggle-group">
        <label><input type="radio" name="board_lodging" value="Yes" required onchange="toggleAddress(true)"> Yes</label>
        <label><input type="radio" name="board_lodging" value="No" required onchange="toggleAddress(false)"> No</label>
    </div>

    <div id="addressField" style="display:none; margin-top:10px;">
        <label for="lodging_address">Lodging Address:</label>
        <input type="text" name="lodging_address" id="lodging_address">
    </div>

    <label for="food_allowance">Food Allowance:</label>
    <select name="food_allowance" required>
        <option value="None">None</option>
        <option value="Partial">Partial</option>
        <option value="Full">Full</option>
    </select>

    <button type="submit" class="submit-btn">Add Employee</button>
</form>

<a href="index.php">← Back to Employee List</a>

<script>
function toggleAddress(show) {
    const addressField = document.getElementById('addressField');
    if (show) {
        addressField.style.display = 'block';
        document.getElementById('lodging_address').setAttribute('required', 'required');
    } else {
        addressField.style.display = 'none';
        document.getElementById('lodging_address').removeAttribute('required');
    }
}

function toggleDeduction(field) {
    const checkbox = document.querySelector(`input[type="checkbox"][name="deductions[]"][value="${field}"]`);
    const input = document.getElementById(`${field}_input`);

    if (checkbox && input) {
        if (checkbox.checked) {
            input.style.display = 'block';
            input.required = true;
        } else {
            input.style.display = 'none';
            input.required = false;
            input.value = '';
        }
    }
}
</script>

</body>
</html>
