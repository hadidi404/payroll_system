<?php
include 'db.php'; // Connect to database

// Fetch employees from the database
$sql = "SELECT * FROM `employee_info`";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Employee List</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f8f9fa; }
    h1 { text-align: center; }

    table {
      margin: auto;
      border-collapse: collapse;
      width: 90%;
      background-color: #fff;
    }

    th, td {
      padding: 12px 15px;
      border: 1px solid #ddd;
      text-align: left;
    }

    th {
      background-color: #343a40;
      color: #fff;
    }

    tr:nth-child(even) { background-color: #f2f2f2; }

    .btn {
      padding: 8px 16px;
      margin: 5px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      color: white;
    }

    .add-btn { background-color: #28a745; text-decoration: none; display: inline-block; }
    .edit-btn { background-color: #007bff; }
    .delete-btn { background-color: #dc3545; }
    .export-btn { background-color: #6c757d; }

    .controls {
      text-align: center;
      margin: 20px;
    }

    .highlight {
      background-color: #ffeeba !important;
    }
  </style>
</head>
<body>

<h1>Employee List</h1>

<div class="controls">
  <a href="add.php" class="btn add-btn">+ Add Employee</a>
  
  <button class="btn edit-btn" onclick="editSelected()">Edit</button>
  <button class="btn delete-btn" onclick="deleteSelected()">Remove</button>

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
  <button class="btn export-btn" onclick="showExportOptions()">Export</button>
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
        <td><?= htmlspecialchars($row['board_lodging']) ?></td>
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
