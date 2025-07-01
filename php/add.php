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
    echo "<p>Employee ID: " . $employee_id . "</p>";

    // EMPLOYEE PAYROLL
    $w1_daily_minimum_wage = $_POST['w1_daily_minimum_wage'];
    $w2_sunday_rest_day = $_POST['w2_sunday_rest_day'];
    $w3_legal_holiday = $_POST['w3_legal_holiday'];
    $w4_special_holiday = $_POST['w4_special_holiday'];
    $w5_regular_overtime_perhour = $_POST['w5_regular_overtime_perhour'];
    $w6_special_overtime_perhour = $_POST['w6_special_overtime_perhour'];
    $w7_special_holiday_overtime_perhour = $_POST['w7_special_holiday_overtime_perhour'];
    $w8_regular_holiday_overtime_perhour = $_POST['w8_regular_holiday_overtime_perhour'];
    $w9_cater = $_POST['w9_cater'];

    $stmt2 = $conn->prepare("INSERT INTO employee_payroll (
        employee_id, w1_daily_minimum_wage, w2_sunday_rest_day, w3_legal_holiday, w4_special_holiday,
        w5_regular_overtime_perhour, w6_special_overtime_perhour, w7_special_holiday_overtime_perhour,
        w8_regular_holiday_overtime_perhour, w9_cater
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt2->bind_param("iiiiiiiiii", $employee_id, $w1_daily_minimum_wage, $w2_sunday_rest_day, $w3_legal_holiday, $w4_special_holiday,
        $w5_regular_overtime_perhour, $w6_special_overtime_perhour, $w7_special_holiday_overtime_perhour,
        $w8_regular_holiday_overtime_perhour, $w9_cater);

    if (!$stmt2->execute()) {
        die("Error inserting payroll info: " . $stmt2->error);
    }

    $payroll_id = $stmt2->insert_id; // Correct place to get payroll_id
    $stmt2->close();

    // FETCH RATES for computation
    $sql = "SELECT 
        w1_daily_minimum_wage,
        w2_sunday_rest_day,
        w3_legal_holiday,
        w4_special_holiday,
        w5_regular_overtime_perhour,
        w6_special_overtime_perhour,
        w7_special_holiday_overtime_perhour,
        w8_regular_holiday_overtime_perhour,
        w9_cater
    FROM employee_payroll WHERE payroll_id = ?";

    $stmt3 = $conn->prepare($sql);
    $stmt3->bind_param("i", $payroll_id);
    $stmt3->execute();
    $result = $stmt3->get_result();

    if ($row = $result->fetch_assoc()) {
        $w1 = $_POST['w1'] * $row['w1_daily_minimum_wage'];
        $w2 = $_POST['w2'] * $row['w2_sunday_rest_day'];
        $w3 = $_POST['w3'] * $row['w3_legal_holiday'];
        $w4 = $_POST['w4'] * $row['w4_special_holiday'];
        $w5 = $_POST['w5'] * $row['w5_regular_overtime_perhour'];
        $w6 = $_POST['w6'] * $row['w6_special_overtime_perhour'];
        $w7 = $_POST['w7'] * $row['w7_special_holiday_overtime_perhour'];
        $w8 = $_POST['w8'] * $row['w8_regular_holiday_overtime_perhour'];
        $w9 = $_POST['w9'] * $row['w9_cater'];

        $gross_pay = $w1 + $w2 + $w3 + $w4 + $w5 + $w6 + $w7 + $w8 + $w9;
        $sss = $gross_pay * 0.05;
        $philhealth = $gross_pay * 0.025;
        $pagibig = $gross_pay * 0.02;
        $total_deductions = $sss + $philhealth + $pagibig;
        $net_pay = $gross_pay - $total_deductions;

        $insert = $conn->prepare("INSERT INTO payroll_computation (
            payroll_id, employee_id,
            w1, w2, w3, w4, w5, w6, w7, w8, w9,
            sss, philhealth, pagibig,
            gross_pay, total_deductions, net_pay
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $insert->bind_param("iiddddddddddddddd",
            $payroll_id, $employee_id,
            $w1, $w2, $w3, $w4, $w5, $w6, $w7, $w8, $w9,
            $sss, $philhealth, $pagibig,
            $gross_pay, $total_deductions, $net_pay
        );

        if ($insert->execute()) {
            echo "✅ Computation saved successfully!";
        } else {
            echo "❌ Execute failed: " . $insert->error;
        }

        $insert->close();
    } else {
        echo "❌ Payroll not found.";
    }
    $stmt3->close();
    $conn->close();
}
?>

<?php include '../html/add_html.php'; ?>
