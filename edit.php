<?php
include 'db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    die("No ID provided.");
}

// Fetch existing employee data
$sql = "SELECT * FROM employee_info WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Employee not found.");
}

$employee = $result->fetch_assoc();

// Update logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $position = $_POST['position'];
    $status = $_POST['status'];
$deductions = isset($_POST['deductions']) ? implode(',', $_POST['deductions']) : '';
    $board_lodging = $_POST['board_lodging'];
    $food_allowance = $_POST['food_allowance'];
    $lodging_address = $_POST['lodging_address'] ?? null;

    $update_sql = "UPDATE employee_info SET 
    name = ?, 
    position = ?, 
    status = ?, 
    deductions = ?, 
    board_lodging = ?, 
    food_allowance = ?,
    lodging_address = ?
    WHERE id = ?";

$update_stmt = $conn->prepare($update_sql);
$update_stmt->bind_param("ssssssss", $name, $position, $status, $deductions, $board_lodging, $food_allowance, $lodging_address, $id);

    if ($update_stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error updating record: " . $update_stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Employee</title>
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
            gap: 10px;
            margin-top: 4px;
        }
        .submit-btn {
            background-color: #007bff;
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

<h2 style="text-align:center;">Edit Employee</h2>

<form method="POST">
    <label>Employee ID:</label>
    <input type="text" value="<?= htmlspecialchars($employee['id']) ?>" disabled>

    <label for="name">Full Name:</label>
    <input type="text" name="name" value="<?= htmlspecialchars($employee['name']) ?>" required>

    <label for="position">Position:</label>
    <input type="text" name="position" value="<?= htmlspecialchars($employee['position']) ?>" required>

    <label>Status:</label>
    <div class="toggle-group">
        <label><input type="radio" name="status" value="Permanent" <?= $employee['status'] === 'Permanent' ? 'checked' : '' ?>> Permanent</label>
        <label><input type="radio" name="status" value="On-Call" <?= $employee['status'] === 'On-Call' ? 'checked' : '' ?>> On-Call</label>
    </div>
    <label>Deductions:</label>
<div class="toggle-group">
    <label><input type="checkbox" name="deductions[]" value="SSS"> SSS</label>
    <label><input type="checkbox" name="deductions[]" value="PhilHealth"> PhilHealth</label>
    <label><input type="checkbox" name="deductions[]" value="Pag-IBIG"> Pag-IBIG</label>
    <label><input type="checkbox" name="deductions[]" value="Tax"> Tax</label>
    <label><input type="checkbox" name="deductions[]" value="Others"> Others</label>
</div>


    <label>Board & Lodging:</label>
<div class="toggle-group">
    <label><input type="radio" name="board_lodging" value="Yes" <?= $employee['board_lodging'] === 'Yes' ? 'checked' : '' ?> onchange="toggleAddress(true)"> Yes</label>
    <label><input type="radio" name="board_lodging" value="No" <?= $employee['board_lodging'] === 'No' ? 'checked' : '' ?> onchange="toggleAddress(false)"> No</label>
</div>

<div id="addressField" style="display:none; margin-top:10px;">
    <label for="lodging_address">Lodging Address:</label>
    <input type="text" name="lodging_address" id="lodging_address" value="<?= htmlspecialchars($employee['lodging_address'] ?? '') ?>">
</div>


    <label for="food_allowance">Food Allowance:</label>
    <select name="food_allowance" required>
        <option value="None" <?= $employee['food_allowance'] === 'None' ? 'selected' : '' ?>>None</option>
        <option value="Partial" <?= $employee['food_allowance'] === 'Partial' ? 'selected' : '' ?>>Partial</option>
        <option value="Full" <?= $employee['food_allowance'] === 'Full' ? 'selected' : '' ?>>Full</option>
    </select>

    <button type="submit" class="submit-btn">Update Employee</button>
</form>

<a href="index.php">← Back to Employee List</a>

<script>
function toggleAddress(show) {
    const addressField = document.getElementById('addressField');
    const addressInput = document.getElementById('lodging_address');
    if (show) {
        addressField.style.display = 'block';
        addressInput.setAttribute('required', 'required');
    } else {
        addressField.style.display = 'none';
        addressInput.removeAttribute('required');
        addressInput.value = ''; // optional: clear the value
    }
}

// Trigger the correct state on page load
window.onload = function () {
    const boardLodgingYes = document.querySelector('input[name="board_lodging"][value="Yes"]');
    toggleAddress(boardLodgingYes.checked);
};
</script>

</body>
</html>
