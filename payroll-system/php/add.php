<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // EMPLOYEE INFO
    $last_name = $_POST['last_name'];
    $first_name = $_POST['first_name'];
    $position = $_POST['position'];
    $status = $_POST['status'];
    $board_lodging = $_POST['board_lodging'];
    $lodging_address = isset($_POST['lodging_address']) ? $_POST['lodging_address'] : NULL;
    $food_allowance = $_POST['food_allowance'];

    // Insert into employee_info
    $stmt = $conn->prepare("INSERT INTO employee_info (last_name, first_name, position, status, board_lodging, lodging_address, food_allowance)
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $last_name, $first_name, $position, $status, $board_lodging, $lodging_address, $food_allowance);
    
    if (!$stmt->execute()) {
        die("Error inserting employee info: " . $stmt->error);
    }

    $employee_id = $stmt->insert_id;
    $stmt->close();

    // Insert into employee_payroll with default zero values
    $stmt2 = $conn->prepare("INSERT INTO employee_payroll (employee_id) VALUES (?)");
    $stmt2->bind_param("i", $employee_id);
    
    if (!$stmt2->execute()) {
        die("Error inserting default payroll record: " . $stmt2->error);
    }

    $payroll_id = $stmt2->insert_id;
    $stmt2->close();

    // Insert into payroll_computation with default 0 values
    $stmt3 = $conn->prepare("INSERT INTO payroll_computation (payroll_id, employee_id, w1, w2, w3, w4, w5, w6, w7, w8, w9, cater1, advance)
                             VALUES (?, ?, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)");
    $stmt3->bind_param("ii", $payroll_id, $employee_id);
    
    if (!$stmt3->execute()) {
        die("Error inserting default computation record: " . $stmt3->error);
    }

    // Start the session if it hasn't started yet
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Set a success message
    $_SESSION['success'] = "Employee added successfully!";

    // Redirect to the dashboard page
    header("Location: dashboard.php");
    exit();

}
?>
