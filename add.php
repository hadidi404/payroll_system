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
        // If ID exists, alert and stop execution
        echo "<script>alert('Error: Employee ID is already in use.'); window.history.back();</script>";
        exit();
    }

    // If ID does not exist, proceed with inserting the record
    $sql = "INSERT INTO employee_info (id, name, position, status, deductions, board_lodging, food_allowance, lodging_address)
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
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="add_edit.css" rel="stylesheet">
    <title>Add Employee</title>

</head>
<body>

<h2 style="text-align:center;">Add New Employee</h2>

<div id="parent_div">
    <form id="info" method="POST">
        <div id="text_info">
            <label for="id">Employee ID:</label>
            <input id="blank_text" type="text" name="id" required>

            <label for="name">Full Name:</label>
            <input id="blank_text" type="text" name="name" required>

            <label for="position">Position:</label>
            <input id="blank_text" type="text" name="position" required>
        </div>

        
        <div class="toggle-group-lodging">
            <label style="margin-top: 10px;" id="text_info_status">Status:</label>
            <div id="toggle-group-one">
                <input id="blank_text" type="radio" name="status" value="Permanent" required> 
                <label>Permanent</label>
            </div>
            <div id="toggle-group-one">
                <input id="blank_text" type="radio" name="status" value="On-Call" required> 
                <label>On-Call</label>
            </div>
        </div>

        <label id="text_info">Deductions:</label>
        <div class="toggle-group-deductions">
            <div id="toggle-group-one-deductions">
                <label>SSS</label>
                <input style="margin-left: 58px;" type="checkbox" name="deductions[]" value="sss" onchange="toggleDeduction('sss')">
                <input type="number" step="0.01" name="sss_amount" id="sss_input" placeholder="Amount" style="display:none; ">
            </div>
            <div id="toggle-group-one-deductions-two">
                <label>PhilHealth</label>
                <input type="checkbox" name="deductions[]" value="philhealth" onchange="toggleDeduction('philhealth')">
                <input type="number" step="0.01" name="philhealth_amount" id="philhealth_input" placeholder="Amount" style="display:none;">
            </div>
            <div id="toggle-group-one-deductions-three">
                <label>Pag&#8209;IBIG</label>
                <input style="margin-left: 17.5px;" type="checkbox" name="deductions[]" value="pagibig" onchange="toggleDeduction('pagibig')">
                <input type="number" step="0.01" name="pagibig_amount" id="pagibig_input" placeholder="Amount" style="display:none;">
            </div>
            <div id="toggle-group-one-deductions-four">
                <label>Tax</label>
                <input style="margin-left: 62px;" type="checkbox" name="deductions[]" value="tax" onchange="toggleDeduction('tax')">
                <input type="number" step="0.01" name="tax_amount" id="tax_input" placeholder="Amount" style="display:none;">
            </div>
            <div id="toggle-group-one-deductions-five">
                <label>Others</label>
                <input style="margin-left: 34.5px;" type="checkbox" name="deductions[]" value="others" onchange="toggleDeduction('others')"> 
                <input type="number" step="0.01" name="others_amount" id="others_input" placeholder="Amount" style="display:none;">
            </div>
        </div>


        <div class="toggle-group-lodging">
            <label style="margin-top: 10px;" id="text_info_lodging">Board & Lodging:</label>
            <div id="toggle-group-one">
                <input id="blank_text" type="radio" name="board_lodging" value="Yes" required onchange="toggleAddress(true)"> 
                <label>Yes</label>
            </div>
            <div id="toggle-group-one">
                <input id="blank_text" type="radio" name="board_lodging" value="No" required onchange="toggleAddress(false)"> 
                <label>No</label>
            </div>
        </div>

        <div id="addressField" style="display:none; margin-top:10px;">
            <label for="lodging_address">Lodging Address:</label>
            <input type="text" name="lodging_address" id="lodging_address">
        </div>

        <label id="text_info" for="food_allowance">Food Allowance:</label>
        <select name="food_allowance" required>
            <option value="None">None</option>
            <option value="Partial">Partial</option>
            <option value="Full">Full</option>
        </select>

        <button type="submit" class="submit-btn">Add Employee</button>
    </form>

<a href="dashboard.php">← Back to Employee List</a>

<script>
function toggleAddress(show) {
    const addressField = document.getElementById('addressField');
    if (show) {
        addressField.style.display = 'flex';
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
</div>
</body>
</html>
