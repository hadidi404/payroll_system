<?php

include 'db.php';

// Fetch employees from the database
$sql = "SELECT * FROM `employee_info`";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Employee List</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="dashboard.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>

<div class="circle small"></div>
<div class="circle small two"></div>
<div class="circle medium"></div>
<div class="circle medium three"></div>
<div class="circle large"></div>

<div id="header_container">
  <div id="header_text" class="controls">
    <img src="logo.png" alt="Company Logo" class="logo">
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
      <button id="edit_btn" class="icon_btn" onclick="editSelected()">
        <i id="i_hover" class="fa-solid fa-user-pen fa-2x"></i>
      </button>
      <span>Edit</span>
    </div>
    <div class="icon_label">
      <button id="delete_btn" class="icon_btn" onclick="deleteSelected()">
        <i id="i_hover" class="fa-solid fa-user-slash fa-2x"></i>
      </button>
      <span>Delete</span>
    </div>  
  </div>


    <!-- Export dropdown hidden until clicked -->
    <form method="POST" action="export.php" style="display:inline;" onsubmit="return validateExport()">
      <div id="export-options" style="display:none; margin-top:10px;">
        <select name="format" id="export-format" required>
          <option value="">-- Select Format --</option>
          <option value="csv">CSV</option>
          <option value="excel">Excel</option>
        </select>
        <button type="submit" class="btn export-btn">OK</button>
      </div>
    </form>
    <div id="second_btn_actions">
      <div class="icon_label">
        <button id="export_btn" class="btn export-btn" onclick="showExportOptions()">
          <i id="i_hover" class="fa-solid fa-file-export fa-2x"></i>
        </button>
        <span>Export</span>
      </div>
      <div class="icon_label">
        <a id="logout_btn" href="logout.php" class="icon_btn">
          <i id="add_icon" class="fa-solid fa-right-from-bracket fa-2x"></i>
        </a>  
        <span>Logout</span>
      </div>
    </div>
  </div>
</div>


<?php if ($result->num_rows > 0): ?>
<form id="employee-form">
  <table>
    <thead>
      <tr>
        <th>Select</th>
        <th>ID</th>
        <th>Name</th>
        <th>Position</th>
        <th>Status</th>
        <th>Board & Lodging</th>
        <th>Food Allowance</th>
      </tr>
    </thead>
    <tbody>
      <?php while($row = $result->fetch_assoc()): ?>
      <tr>
        <td><input type="radio" name="selected_id" value="<?= $row['id'] ?>"></td>
        <td><?= htmlspecialchars($row['id']) ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['position']) ?></td>
        <td><?= htmlspecialchars($row['status']) ?></td>
        <td>
  <?php if ($row['board_lodging'] === 'Yes'): ?>
    <?= htmlspecialchars($row['lodging_address']) ?>
  <?php else: ?>
    No
  <?php endif; ?>
</td>
        <td><?= htmlspecialchars($row['food_allowance']) ?></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</form>
<?php else: ?>
  <p style="text-align:center;">No employees found.</p>
<?php endif; ?>

<script>
  function getSelectedId() {
    const radios = document.getElementsByName('selected_id');
    for (let i = 0; i < radios.length; i++) {
      if (radios[i].checked) {
        return radios[i].value;
      }
    }
    return null;
  }

  function editSelected() {
    const selectedId = getSelectedId();
    if (selectedId) {
      window.location.href = `edit.php?id=${selectedId}`;
    } else {
      alert('Please select an employee to edit.');
    }
  }

  function deleteSelected() {
    const selectedId = getSelectedId();
    if (selectedId) {
      const confirmDelete = confirm('Are you sure you want to delete this employee?');
      if (confirmDelete) {
        window.location.href = `delete.php?id=${selectedId}`;
      }
    } else {
      alert('Please select an employee to remove.');
    }
  }

  function showExportOptions() {
    document.getElementById('export-options').style.display = 'inline-block';
  }

  function validateExport() {
    const format = document.getElementById('export-format').value;
    if (!format) {
      alert('Please select a format (CSV or Excel).');
      return false;
    }
    return true;
  }
</script>

</body>
</html>