<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert"
    style="padding: 1rem 1rem; font-size: 1rem; background-color: #0641fb; margin-bottom: -10px;">
        <?= htmlspecialchars($_SESSION['success']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<!DOCTYPE html>
<html>
<head>
   <meta charset="UTF-8">
  <title>Employee List</title>
  <!-- Bootstrap CSS (if not already included) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Bundle JS (includes Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../css/dashboard.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>

<div id="header_container">
  <div id="header_text" class="controls">
    <img src="../images/logo-removebg-preview.png" alt="Company Logo" class="logo">
    <h1 id="the_text">AI Korean Restaurant</h1>
  </div>

  <div id="btn_actions">
    <div class="icon_label">
      <button id="add_btn" onclick="loadAddEmployeeModal()" class="btn btn-primary">
        <i id="add_icon" class="fa-solid fa-user-plus"></i>
      </button>
      <span style="margin-bottom: 5px;">Add People</span>
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
            <td><?= mb_strtoupper(htmlspecialchars($row['last_name'])) ?></td>
            <td><?= htmlspecialchars($row['first_name']) ?></td>
            <td><?= htmlspecialchars($row['position']) ?></td>
            <td><?= htmlspecialchars($row['status']) ?></td>
            <td><?= $row['board_lodging'] === 'Yes' ? htmlspecialchars($row['lodging_address']) : 'No' ?></td>
            <td><?= htmlspecialchars($row['food_allowance']) ?></td>
            <td>
              <a href="#" class="action-edit-btn" onclick="loadEditModal(<?= $row['employee_id'] ?>)">Edit</a>
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
           <th><input type="checkbox" id="selectAllPayslips"></th>
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
          <tr>
            <td><input type="checkbox" class="payslipCheckbox" value="<?= $row['payroll_id'] ?>"></td>
            <td><?= htmlspecialchars($row['employee_id']) ?></td>
            <td><?= htmlspecialchars($row['last_name']) ?></td>
            <td><?= htmlspecialchars($row['first_name']) ?></td>
            <td>₱ <?= isset($row['gross_pay']) ? number_format($row['gross_pay'], 2) : 'N/A' ?></td>
            <td>₱ <?= isset($row['total_deductions']) ? number_format($row['total_deductions'], 2) : 'N/A' ?></td>
            <td>₱ <?= isset($row['net_pay']) ? number_format($row['net_pay'], 2) : 'N/A' ?></td>
            <td>
                <a href="#" class="action-edit-btn" onclick="loadPayslipModal(<?= $row['payroll_id'] ?>)">Edit</a>
                <a href="payslip.php?payroll_id=<?= $row['payroll_id'] ?>" class="action-edit-btn">Payslip</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
    <button type="button" class="btn btn-success mt-3" onclick="generateBatchPayslips()">Generate Selected Payslips</button>

  <?php else: ?>
    <p>No payroll records found.</p>
  <?php endif; ?>
</div>

<!-- Edit Employee Modal -->
<div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Employee Information</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="editEmployeeBody">
        <!-- Dynamic content loaded here -->
      </div>
    </div>
  </div>
</div>

<!-- Edit Payslip Modal -->
<div class="modal fade" id="editPayslipModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content-payslip">
      <div class="modal-header">
        <h5 class="modal-title">Edit Payslip Information</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="editPayslipBody">
        <!-- Loaded via JS -->
      </div>
    </div>
  </div>
</div>

<!-- Add employee modal -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content" style="height: 800px">
      <div class="modal-header">
        <h5 class="modal-title">Add Employee Information</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <!-- Body (content goes here) -->
      <div class="modal-body" id="addEmployeeBody">
        <!-- Loaded via AJAX -->
      </div>
    </div>
  </div>
</div>


<script>
    function toggleLodgingAddress(value) {
        document.getElementById("add-lodging_input").style.display = value === "Yes" ? "block" : "none";
    }
</script>
<script>
     function toggleLodgingAddress1(value, inputId) {
        document.getElementById(inputId).style.display = (value === "Yes") ? "block" : "none";
    }

    document.addEventListener('DOMContentLoaded', function () {
        const lodgingSelect = document.querySelector('select[name="board_lodging"]');
        toggleLodgingAddress1(lodgingSelect.value, 'edit_lodging_input');
    });

        function toggleLodgingAddress1(value) {
        document.getElementById("edit_lodging_input").style.display = value === "Yes" ? "block" : "none";
    }
</script>
<script>
// multiple pazyslip generation
  document.getElementById("selectAllPayslips").addEventListener("change", function() {
    const checkboxes = document.querySelectorAll(".payslipCheckbox");
    checkboxes.forEach(cb => cb.checked = this.checked);
  });

  function generateBatchPayslips() {
    const selected = Array.from(document.querySelectorAll(".payslipCheckbox:checked"))
                         .map(cb => cb.value);

    if (selected.length === 0) {
      alert("Please select at least one payslip.");
      return;
    }

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '../php/batch_payslip.php';
    form.target = '_blank';

    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'payroll_ids';
    input.value = JSON.stringify(selected);

    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
  }
</script>
<script>
  function loadEditModal(employee_id) {
    fetch(`/payroll-system/html/edit_employee_html.php?id=${employee_id}`, {
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then(response => response.text())
    .then(data => {
      document.getElementById("editEmployeeBody").innerHTML = data;
      const modal = new bootstrap.Modal(document.getElementById("editEmployeeModal"));
      modal.show();
    })
    .catch(error => console.error("Error loading modal:", error));
  }
</script>

<script>
  function loadPayslipModal(payroll_id) {
    fetch(`/payroll-system/html/edit_payslip_html.php?id=${payroll_id}`, { 
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then(response => response.text())
    .then(data => {
      document.getElementById("editPayslipBody").innerHTML = data;
      const modal = new bootstrap.Modal(document.getElementById("editPayslipModal"));
      modal.show();
    })
    .catch(error => console.error("Error loading payslip modal:", error));
  }
</script>

<script>
  function loadAddEmployeeModal() {
    fetch('/payroll-system/html/add_html.php', {
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then(response => response.text())
    .then(data => {
      document.getElementById('addEmployeeBody').innerHTML = data;
      const modal = new bootstrap.Modal(document.getElementById('addEmployeeModal'));
      modal.show();
    })
    .catch(error => console.error("Error loading add employee modal:", error));
  }
</script>

<script src="../js/dashboard.js"></script>
</body>
</html>
