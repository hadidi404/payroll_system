<form method="POST" action="add.php" class="add-form">
    <!-- Employee Info -->
    <fieldset class="add-fieldset">

        <label>Last Name:</label>
        <input type="text" name="last_name" required>

        <label>First Name:</label>
        <input type="text" name="first_name" required>

        <label>Position:</label>
        <input type="text" name="position" required>

        <label>Status:</label>
        <select name="status" required>
            <option value="Permanent">Permanent</option>
            <option value="On-Call">On-Call</option>
        </select>

        <label>Board & Lodging:</label>
        <select name="board_lodging" required onchange="toggleLodgingAddress(this.value)">
            <option value="Yes" >Yes</option>
            <option value="No" selected>No</option>
        </select>

        <div id="add-lodging_input" style="display:none;">
            <label>Lodging Address:</label>
            <input class="add-input" type="text" name="lodging_address">
        </div>

        <label>Food Allowance:</label>
        <select name="food_allowance" required>
            <option value="Full">Full</option>
            <option value="Partial">Partial</option>
            <option value="None">None</option>
        </select>
    </fieldset>

    <div id="add-submit_div">
        <input type="submit" value="Add Employee" class="add-update-btn">
    </div>
</form>
