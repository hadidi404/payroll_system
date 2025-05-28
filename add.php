<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $position = $_POST['position'];
    $status = $_POST['status'];
    $deductions = isset($_POST['deductions']) ? implode(',', $_POST['deductions']) : '';
    $board_lodging = $_POST['board_lodging'];
    $food_allowance = $_POST['food_allowance'];
    $lodging_address = $_POST['lodging_address'] ?? null;

    $sql = "INSERT INTO employee_info (id, name, position, status, deductions, board_lodging, food_allowance,lodging_address)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss", $id, $name, $position, $status, $deductions, $board_lodging, $food_allowance, $lodging_address);

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
            gap: 10px;
            margin-top: 4px;
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
<div class="toggle-group">
    <label><input type="checkbox" name="deductions[]" value="SSS"> SSS</label>
    <label><input type="checkbox" name="deductions[]" value="PhilHealth"> PhilHealth</label>
    <label><input type="checkbox" name="deductions[]" value="Pag-IBIG"> Pag-IBIG</label>
    <label><input type="checkbox" name="deductions[]" value="Tax"> Tax</label>
    <label><input type="checkbox" name="deductions[]" value="Others"> Others</label>
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
</script>

</body>
</html>
