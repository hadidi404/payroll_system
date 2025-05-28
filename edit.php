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
    $deductions = $_POST['deductions'];
    $board_lodging = $_POST['board_lodging'];
    $food_allowance = $_POST['food_allowance'];

    $update_sql = "UPDATE employee_info SET 
        name = ?, 
        position = ?, 
        status = ?, 
        deductions = ?, 
        board_lodging = ?, 
        food_allowance = ?
        WHERE id = ?";
        
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssssss", $name, $position, $status, $deductions, $board_lodging, $food_allowance, $id);

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

    <label for="deductions">Deductions:</label>
    <select name="deductions" required>
        <option value="SSS" <?= $employee['deductions'] === 'SSS' ? 'selected' : '' ?>>SSS</option>
        <option value="PhilHealth" <?= $employee['deductions'] === 'PhilHealth' ? 'selected' : '' ?>>PhilHealth</option>
        <option value="Pag-IBIG" <?= $employee['deductions'] === 'Pag-IBIG' ? 'selected' : '' ?>>Pag-IBIG</option>
        <option value="Tax" <?= $employee['deductions'] === 'Tax' ? 'selected' : '' ?>>Tax</option>
        <option value="Others" <?= $employee['deductions'] === 'Others' ? 'selected' : '' ?>>Others</option>
    </select>

    <label>Board & Lodging:</label>
    <div class="toggle-group">
        <label><input type="radio" name="board_lodging" value="Yes" <?= $employee['board_lodging'] === 'Yes' ? 'checked' : '' ?>> Yes</label>
        <label><input type="radio" name="board_lodging" value="No" <?= $employee['board_lodging'] === 'No' ? 'checked' : '' ?>> No</label>
    </div>

    <label for="food_allowance">Food Allowance:</label>
    <select name="food_allowance" required>
        <option value="Full" <?= $employee['food_allowance'] === 'Full' ? 'selected' : '' ?>>Full</option>
        <option value="Partial" <?= $employee['food_allowance'] === 'Partial' ? 'selected' : '' ?>>Partial</option>
        <option value="None" <?= $employee['food_allowance'] === 'None' ? 'selected' : '' ?>>None</option>
    </select>

    <button type="submit" class="submit-btn">Update Employee</button>
</form>

<a href="index.php">← Back to Employee List</a>

</body>
</html>
