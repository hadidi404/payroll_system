<!DOCTYPE html>
<html>
<head>
    <title>Add New Employee and Payroll Computation</title>
</head>
<body>
    <h2>Employee Info + Payroll + Computation</h2>

    <form method="POST" action="add.php">

        <!-- Employee Info -->
        <fieldset>
            <legend>Employee Info</legend>

            <label>Last Name:</label><br>
            <input type="text" name="last_name" required><br><br>

            <label>First Name:</label><br>
            <input type="text" name="first_name" required><br><br>

            <label>Position:</label><br>
            <input type="text" name="position" required><br><br>

            <label>Status:</label><br>
            <select name="status" required>
                <option value="Permanent">Permanent</option>
                <option value="On-Call">On-Call</option>
            </select><br><br>

            <label>Board & Lodging:</label><br>
            <select name="board_lodging" required onchange="toggleLodgingAddress(this.value)">
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select><br><br>

            <div id="lodging_input" style="display:none;">
                <label>Lodging Address:</label><br>
                <input type="text" name="lodging_address"><br><br>
            </div>

            <label>Food Allowance:</label><br>
            <select name="food_allowance" required>
                <option value="Full">Full</option>
                <option value="Partial">Partial</option>
                <option value="None">None</option>
            </select><br><br>
        </fieldset>

        <!-- Payroll Setup -->
        <fieldset>
            <legend>Payroll Information</legend>

            <label>Daily Minimum Wage:</label><br>
            <input type="number" name="w1_daily_minimum_wage" value="0"><br><br>

            <label>Sunday Rest Day:</label><br>
            <input type="number" name="w2_sunday_rest_day" value="0"><br><br>

            <label>Legal Holiday:</label><br>
            <input type="number" name="w3_legal_holiday" value="0"><br><br>

            <label>Special Holiday:</label><br>
            <input type="number" name="w4_special_holiday" value="0"><br><br>

            <label>Regular OT/hour:</label><br>
            <input type="number" name="w5_regular_overtime_perhour" value="0"><br><br>

            <label>Special OT/hour:</label><br>
            <input type="number" name="w6_special_overtime_perhour" value="0"><br><br>

            <label>Special Holiday OT/hour:</label><br>
            <input type="number" name="w7_special_holiday_overtime_perhour" value="0"><br><br>

            <label>Regular Holiday OT/hour:</label><br>
            <input type="number" name="w8_regular_holiday_overtime_perhour" value="0"><br><br>

            <label>Cater:</label><br>
            <input type="number" name="w9_cater" value="0"><br><br>
        </fieldset>

        <!-- Computation -->
        <fieldset>
            <legend>Computation Input</legend>

            <label>W1 (days):</label><br>
            <input type="number" name="w1" value="470.00"><br><br>

            <label>W2 (days):</label><br>
            <input type="number" name="w2" value="611.00"><br><br>

            <label>W3 (days):</label><br>
            <input type="number" name="w3" value="940.00"><br><br>

            <label>W4 (days):</label><br>
            <input type="number" name="w4" value="611.00"><br><br>

            <label>W5 (hours):</label><br>
            <input type="number" name="w5" value="73.44"><br><br>

            <label>W6 (hours):</label><br>
            <input type="number" name="w6" value="76.38"><br><br>

            <label>W7 (hours):</label><br>
            <input type="number" name="w7" value="99.29"><br><br>

            <label>W8 (hours):</label><br>
            <input type="number" name="w8" value="152.00"><br><br>

            <label>W9 (meals):</label><br>
            <input type="number" name="w9" value="1000.00"><br><br>
        </fieldset>

        <input type="submit" value="Add Employee & Compute Payroll">

    </form>
    <?php if (isset($employee_id)): ?>
        <p>Employee ID: <input type="text" value="<?php echo $employee_id; ?>" readonly></p>
    <?php endif; ?>

    <script>
        function toggleLodgingAddress(value) {
            document.getElementById("lodging_input").style.display = value === "Yes" ? "block" : "none";
        }
    </script>
</body>
</html>
