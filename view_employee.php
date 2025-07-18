
<?php
include "config.php";
if (!isset($_SESSION["user_id"])) header("Location: login.php");

// AJAX handler for search
if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
    $search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
    $sql = $search ?
        "SELECT * FROM employees WHERE full_name LIKE '%$search%' OR position LIKE '%$search%' OR status LIKE '%$search%'" :
        "SELECT * FROM employees";
    $result = mysqli_query($conn, $sql);
    ob_start();
    while($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td><img src="' . $row["photo"] . '" width="50"></td>';
        echo '<td>' . htmlspecialchars($row["full_name"]) . '</td>';
        echo '<td>' . htmlspecialchars($row["position"]) . '</td>';
        echo '<td>RM ' . number_format($row["salary"], 2, '.', ',') . ' (Annual: RM ' . number_format($row["salary"] * 12, 2, '.', ',') . ')</td>';
        echo '<td>' . $row["status"];
        if ($row["salary"] > 5000) echo ' <span class="badge bg-success">High Earner</span>';
        echo '</td>';
        echo '<td>';
        echo '<a class="btn btn-sm btn-primary" href="edit_employee.php?id=' . $row["id"] . '">Edit</a> ';
        echo '<a class="btn btn-sm btn-danger" href="delete_employee.php?id=' . $row["id"] . '" onclick="return confirm(\'Delete this employee?\')">Delete</a>';
        echo '</td>';
        echo '</tr>';
    }
    echo ob_get_clean();
    exit();
}

// Default page load
$search = "";
$search = "";
if (isset($_GET["search"])) {
    $search = mysqli_real_escape_string($conn, $_GET["search"]);
    $sql = "SELECT * FROM employees WHERE full_name LIKE '%$search%' OR position LIKE '%$search%' OR status LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM employees";
}
$result = mysqli_query($conn, $sql);
?>
<?php include "user_header.php"; ?>

<!DOCTYPE html>
<html>
<head>
  <title>View Employees</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
<h2>Employees</h2>
<form class="mb-3" method="get" onsubmit="return false;">
  <input name="search" id="searchBox" class="form-control" placeholder="Search by name or position..." value="<?php echo htmlspecialchars($search); ?>" autocomplete="off">
</form>
<table class="table table-striped">
  <tr>
    <th>Photo</th><th>Name</th><th>Position</th><th>Salary</th><th>Status</th><th>Actions</th>
  </tr>
  <tbody id="employeeTableBody">
  <?php while($row = mysqli_fetch_assoc($result)): ?>
  <tr>
    <td><img src="<?php echo $row["photo"]; ?>" width="50"></td>
    <td><?php echo htmlspecialchars($row["full_name"]); ?></td>
    <td><?php echo htmlspecialchars($row["position"]); ?></td>
    <td>RM <?php echo number_format($row["salary"], 2, '.', ','); ?> (Annual: RM <?php echo number_format($row["salary"] * 12, 2, '.', ','); ?>)</td>
    <td>
      <?php echo $row["status"]; ?>
      <?php if ($row["salary"] > 5000): ?>
        <span class="badge bg-success">High Earner</span>
      <?php endif; ?>
    </td>
    <td>
      <a class="btn btn-sm btn-primary" href="edit_employee.php?id=<?php echo $row["id"]; ?>">Edit</a>
      <a class="btn btn-sm btn-danger" href="delete_employee.php?id=<?php echo $row["id"]; ?>" onclick="return confirm('Delete this employee?')">Delete</a>
    </td>
  </tr>
  <?php endwhile; ?>
  </tbody>
</table>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function() {
  $('#searchBox').on('input', function() {
    var val = $(this).val();
    $.get(window.location.pathname, { ajax: 1, search: val }, function(data) {
      $('#employeeTableBody').html(data);
    });
  });
});
</script>
</div>
</body>
</html>
