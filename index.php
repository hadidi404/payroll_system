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
    .actions button {
      margin: 2px;
      padding: 6px 10px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    .edit-btn { background-color: #007bff; color: white; }
    .delete-btn { background-color: #dc3545; color: white; }
    .add-btn {
      margin: 20px auto;
      display: block;
      padding: 10px 20px;
      background-color: #28a745;
      color: white;
      text-decoration: none;
      border-radius: 4px;
    }
  </style>
</head>
<body>

<h1>Employee List</h1>

<a href="add.php" class="add-btn">+ Add Employee</a>

<?php if ($result->num_rows > 0): ?>
<table>
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
    <tr>
      <td><?= $row['id'] ?></td>
      <td><?= htmlspecialchars($row['name']) ?></td>
      <td><?= htmlspecialchars($row['position']) ?></td>
      <td><?= htmlspecialchars($row['status']) ?></td>
      <td><?= htmlspecialchars($row['board_lodging']) ?></td>
      <td><?= htmlspecialchars($row['food_allowance']) ?></td>
      <td class="actions">
        <a href="edit.php?id=<?= $row['id'] ?>"><button class="edit-btn">Edit</button></a>
        <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this employee?');"><button class="delete-btn">Delete</button></a>
      </td>
    </tr>
    <?php endwhile; ?>
  </tbody>
</table>
<?php else: ?>
  <p style="text-align:center;">No employees found.</p>
<?php endif; ?>

</body>
</html>
