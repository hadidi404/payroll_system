<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $position = $_POST['position'];
    $status = $_POST['status'];
    $deductions = $_POST['deductions'];
    $board_lodging = $_POST['board_lodging'];
    $food_allowance = $_POST['food_allowance'];

    $sql = "INSERT INTO employee_info (id, name, position, status, deductions, board_lodging, food_allowance)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $id, $name, $position, $status, $deductions, $board_lodging, $food_allowance);

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

    <label for="deductions">Deductions:</label>
    <select name="deductions" required>
        <option value="SSS">SSS</option>
        <option value="PhilHealth">PhilHealth</option>
        <option value="Pag-IBIG">Pag-IBIG</option>
        <option value="Tax">Tax</option>
        <option value="Others">Others</option>
    </select>

    <label>Board & Lodging:</label>
    <div class="toggle-group">
        <label><input type="radio" name="board_lodging" value="Yes" required> Yes</label>
        <label><input type="radio" name="board_lodging" value="No" required> No</label>
    </div>

    <label for="food_allowance">Food Allowance:</label>
    <select name="food_allowance" required>
        <option value="Full">Full</option>
        <option value="Partial">Partial</option>
        <option value="None">None</option>
    </select>

    <button type="submit" class="submit-btn">Add Employee</button>
</form>

<a href="index.php">← Back to Employee List</a>

</body>
</html>
