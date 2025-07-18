<!DOCTYPE html>
<html>
<head>
    <title>Edit Employee Information</title>
    <link href="../css/payslip.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        input[type="number"] { width: 100px; text-align: right; }
        button.update-btn { margin-top: 15px; padding: 8px 16px; }
    </style>
</head>
<body>
<div class="container">
    <div id="second_container">
        <a id="back" href="dashboard.php"><i class="fa-solid fa-arrow-left fa-2x"></i></a>
    </div>

    <h2>Edit Employee Information</h2>

    <form method="POST" action="edit_employee.php">
        <!-- Hidden employee_id -->
        <input type="hidden" name="employee_id" value="<?php echo htmlspecialchars($employee['employee_id']); ?>">

        <fieldset>
            
            <legend>Employee Info</legend>

            <label>Last Name:</label><br>
            <input type="text" name="last_name" value="<?php echo htmlspecialchars($employee['last_name']); ?>" required><br><br>

            <label>First Name:</label><br>
            <input type="text" name="first_name" value="<?php echo htmlspecialchars($employee['first_name']); ?>" required><br><br>

            <label>Position:</label><br>
            <input type="text" name="position" value="<?php echo htmlspecialchars($employee['position']); ?>" required><br><br>

            <label>Status:</label><br>
            <select name="status" required>
                <option value="Permanent" <?php if($employee['status'] == 'Permanent') echo 'selected'; ?>>Permanent</option>
                <option value="On-Call" <?php if($employee['status'] == 'On-Call') echo 'selected'; ?>>On-Call</option>
            </select><br><br>

            <label>Board & Lodging:</label><br>
            <select name="board_lodging" required onchange="toggleLodgingAddress(this.value)">
                <option value="Yes" <?php if($employee['board_lodging'] == 'Yes') echo 'selected'; ?>>Yes</option>
                <option value="No" <?php if($employee['board_lodging'] == 'No') echo 'selected'; ?>>No</option>
            </select><br><br>

            <div id="lodging_input" style="<?php echo ($employee['board_lodging'] == 'Yes') ? 'display:block;' : 'display:none;'; ?>">
                <label>Lodging Address:</label><br>
                <input type="text" name="lodging_address" value="<?php echo htmlspecialchars($employee['lodging_address']); ?>"><br><br>
            </div>

            <label>Food Allowance:</label><br>
            <select name="food_allowance" required>
                <option value="Full" <?php if($employee['food_allowance'] == 'Full') echo 'selected'; ?>>Full</option>
                <option value="Partial" <?php if($employee['food_allowance'] == 'Partial') echo 'selected'; ?>>Partial</option>
                <option value="None" <?php if($employee['food_allowance'] == 'None') echo 'selected'; ?>>None</option>
            </select><br><br>
        </fieldset>

        <button type="submit" class="update-btn">Update Employee</button>
    </form>

    <script>
        function toggleLodgingAddress(value) {
            document.getElementById("lodging_input").style.display = (value === "Yes") ? "block" : "none";
        }
        document.addEventListener('DOMContentLoaded', function() {
            const lodgingSelect = document.querySelector('select[name="board_lodging"]');
            toggleLodgingAddress(lodgingSelect.value);
        });
    </script>
</body>
</html>
