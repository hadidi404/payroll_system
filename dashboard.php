<?php
include 'db.php';
$sql = "SELECT * FROM `employee_info`";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Employee List</title>
  <style>
    table, td, th {
      font-family: 'Montserrat', sans-serif;
    }
    tr.selected {
      background-color: #cce5ff !important;
    }
    tr:hover {
      cursor: pointer;
      background-color: #f1f1f1;
    }
    #deselect_btn {
      display: none;
      margin: 10px;
      padding: 5px 10px;
      font-family: 'Montserrat', sans-serif;
      background-color: #f44336;
      color: white;
      border: none;
      cursor: pointer;
      border-radius: 4px;
    }
  </style>

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
    <img src="logo_dashboard.png" alt="Company Logo" class="logo">
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
      <button id="delete_btn" class="icon_btn" onclick="enterDeleteMode()">
        <i id="i_hover" class="fa-solid fa-user-slash fa-2x"></i>
      </button>
      <span>Delete</span>
    </div>
    <div class="icon_label">
      <button id="export_btn" class="btn export-btn" onclick="export1()">
        <i id="i_hover" class="fa-solid fa-file-export fa-2x"></i>
      </button>
       <span>Payslip</span>
    </div>
    <div class="icon_label">
      <a id="logout_btn" href="logout.php" class="icon_btn">
        <i id="add_icon" class="fa-solid fa-right-from-bracket fa-2x"></i>
      </a>
      <span>Logout</span>
    </div>
        <button type="button" id="deselect_btn" onclick="deselectAll()">Deselect All</button>
  </div>
</div>

<?php if ($result->num_rows > 0): ?>
  <form id="employee-form">
    <input type="hidden" name="selected_ids" id="selected_ids">
    <table class="employee_table">
      <thead>
        <tr>
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
          <tr data-id="<?= $row['id'] ?>">
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['position']) ?></td>
            <td><?= htmlspecialchars($row['status']) ?></td>
            <td><?= $row['board_lodging'] === 'Yes' ? htmlspecialchars($row['lodging_address']) : 'No' ?></td>
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
  let mode = 'single'; // can be 'single' or 'multiple'
  const selectedRows = new Set();

  document.querySelectorAll('tbody tr').forEach(row => {
    row.addEventListener('click', () => {
      const id = row.getAttribute('data-id');

      if (mode === 'single') {
        if (selectedRows.has(id)) {
          row.classList.remove('selected');
          selectedRows.clear();
        } else {
          clearSelections();
          row.classList.add('selected');
          selectedRows.add(id);
        }
      } else if (mode === 'multiple') {
        if (selectedRows.has(id)) {
          selectedRows.delete(id);
          row.classList.remove('selected');
        } else {
          selectedRows.add(id);
          row.classList.add('selected');
        }
      }

      updateSelectedInput();
    });
  });

  function updateSelectedInput() {
    document.getElementById('selected_ids').value = Array.from(selectedRows).join(',');
  }

  function clearSelections() {
    selectedRows.clear();
    document.querySelectorAll('tr.selected').forEach(row => row.classList.remove('selected'));
    updateSelectedInput();
  }

  function editSelected() {
    if (selectedRows.size === 1) {
      const id = Array.from(selectedRows)[0];
      window.location.href = `edit.php?id=${id}`;
    } else if (selectedRows.size === 0) {
      alert("Please select an employee to edit.");
    } else {
      alert("Please select only one employee to edit.");
    }
  }

  function export1() {
    if (selectedRows.size === 1) {
      const id = Array.from(selectedRows)[0];
      window.location.href = `payslip.php?id=${id}`;
    } else if (selectedRows.size === 0) {
      alert("Please select an employee to generate payslip.");
    } else {
      alert("Please select only one employee to generate payslip.");
    }
  }

  function enterDeleteMode() {
    mode = 'multiple';
    document.getElementById('deselect_btn').style.display = 'inline-block';

    if (selectedRows.size === 0) {
      alert("Select employees to delete by clicking their rows. Click 'Deselect All' to cancel.");
      return;
    }

    const ids = Array.from(selectedRows);
    const confirmDelete = confirm(`Are you sure you want to delete ${ids.length} employee(s)?`);
    if (confirmDelete) {
      window.location.href = `delete.php?ids=${ids.join(',')}`;
    }
  }

  function deselectAll() {
    clearSelections();
    mode = 'single';
    document.getElementById('deselect_btn').style.display = 'none';
  }
</script>

</body>
</html>
