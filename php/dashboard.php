<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: authentication.php");
    exit();
}

include 'db.php';
$sql = "SELECT * FROM `employee_info`";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Employee List</title>
  <style>
    
  </style>

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
    </div>
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
          <th>Actions</th>
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
            <td>
              <a href="edit.php?id=<?= $row['id'] ?>" class="action-edit-btn">Edit</a>
              <a href="delete.php?ids=<?= $row['id'] ?>" class="action-delete-btn" onclick="return confirm('Are you sure you want to delete this employee?');">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </form>
<?php else: ?>
  <p style="text-align:center;">No employees found.</p>
<?php endif; ?>

<script>
  const selectedRows = new Set();

  document.querySelectorAll('tbody tr').forEach(row => {
    row.addEventListener('click', () => {
      const id = row.getAttribute('data-id');

      if (selectedRows.has(id)) {
        row.classList.remove('selected');
        selectedRows.clear();
      } else {
        clearSelections();
        row.classList.add('selected');
        selectedRows.add(id);
      }

      updateSelectedInput();
    });
  });

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

  function updateSelectedInput() {
    document.getElementById('selected_ids').value = Array.from(selectedRows).join(',');
  }

  function clearSelections() {
    selectedRows.clear();
    document.querySelectorAll('tr.selected').forEach(row => row.classList.remove('selected'));
    updateSelectedInput();
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
</script>

</body>
</html>
