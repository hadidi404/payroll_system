<!DOCTYPE html>
<html>
<head>
  <title>Employee List</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../css/dashboard.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>

<div id="header_container">
  <div id="header_text" class="controls">
    <img src="../images/logo.png" alt="Company Logo" class="logo">
    <h1 id="the_text">Employee List</h1>
  </div>

  <div id="btn_actions">
    <div class="icon_label">
      <a id="add_btn" href="add.php" class="icon_btn">
        <i id="add_icon" class="fa-solid fa-user-plus fa-2x"></i>
      </a>
      <span>Add</span>
    </div>
    <div class="icon_label">
      <a id="logout_btn" href="logout.php" class="icon_btn">
        <i id="add_icon" class="fa-solid fa-right-from-bracket fa-2x"></i>
      </a>
      <span>Logout</span>
    </div>
  </div>
</div>

<div class="tab">
  <button class="tablinks" onclick="openTab(event, 'employeeList')">Employee List</button>
  <button class="tablinks" onclick="openTab(event, 'payrollInfo')">Payroll Information</button>
</div>

<div id="employeeList" class="tabcontent">
<?php if ($result && $result->num_rows > 0): ?>
  <form id="employee-form">
    <input type="hidden" name="selected_ids" id="selected_ids">
    <table class="employee_table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Last Name</th>
          <th>First Name</th>
          <th>Position</th>
          <th>Status</th>
          <th>Board & Lodging</th>
          <th>Food Allowance</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
          <tr data-id="<?= $row['employee_id'] ?>">
            <td><?= htmlspecialchars($row['employee_id']) ?></td>
            <td><?= strtoupper(htmlspecialchars($row['last_name'])) ?></td>
            <td><?= htmlspecialchars($row['first_name']) ?></td>
            <td><?= htmlspecialchars($row['position']) ?></td>
            <td><?= htmlspecialchars($row['status']) ?></td>
            <td><?= $row['board_lodging'] === 'Yes' ? htmlspecialchars($row['lodging_address']) : 'No' ?></td>
            <td><?= htmlspecialchars($row['food_allowance']) ?></td>
            <td>
              <a href="edit.php?employee_id=<?= $row['employee_id'] ?>" class="action-edit-btn">Edit</a>
              <a href="delete_employee.php?employee_id=<?= $row['employee_id'] ?>" class="action-delete-btn" onclick="return confirm('Are you sure you want to delete this employee?');">Delete</a>
            </td>
         
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </form>
<?php else: ?>
  <p style="text-align:center;">No employees found.</p>
<?php endif; ?>
</div>

<div id="payrollInfo" class="tabcontent">
  <?php if ($result1 && $result1->num_rows > 0): ?>
    <table class="employee_table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Last Name</th>
          <th>First Name</th>
          <th>Gross Pay</th>
          <th>total_deductions</th>
          <th>Net Pay</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while($row = $result1->fetch_assoc()): ?>
          <?php
            // Check if all payroll fields are empty or zero
            $isEmpty = 
              empty($row['payroll_id']) &&
              empty($row['computation_id'])&&
              empty($row['gross_pay']) && 
              empty($row['total_deductions']) &&
              empty($row['net_pay']);
              
              if ($isEmpty) continue; // Skip row if no payroll data
          ?>
          <tr>
            <td><?= htmlspecialchars($row['employee_id']) ?></td>
            <td><?= htmlspecialchars($row['last_name']) ?></td>
            <td><?= htmlspecialchars($row['first_name']) ?></td>
            <td>₱ <?= isset($row['gross_pay']) ? number_format($row['gross_pay'], 2) : 'N/A' ?></td>
            <td>₱ <?= isset($row['total_deductions']) ? number_format($row['total_deductions'], 2) : 'N/A' ?></td>
            <td>₱ <?= isset($row['net_pay']) ? number_format($row['net_pay'], 2) : 'N/A' ?></td>
            <td>
              <a href="edit.php?employee_id=<?= $row['employee_id'] ?>" class="action-edit-btn">Edit</a>
              <a href="#" class="action-edit-btn" onclick="payslip(<?= $row['employee_id'] ?>); return false;">Payslip</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>No payroll records found.</p>
  <?php endif; ?>
</div>

<script src="../js/dashboard.js"></script>
</body>
</html>
