<?php
include "config.php";
if (!isset($_SESSION["user_id"])) header("Location: login.php");

$id = intval($_GET["id"]);
$sql = "SELECT * FROM employees WHERE id=$id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    die("Employee not found.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = mysqli_real_escape_string($conn, $_POST["full_name"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $position = mysqli_real_escape_string($conn, $_POST["position"]);
    $salary = $_POST["salary"];
    $date_hired = $_POST["date_hired"];
    $status = $_POST["status"];

    // Update photo only if new file uploaded
    if ($_FILES["photo"]["name"]) {
        $photo = "uploads/" . basename($_FILES["photo"]["name"]);
        move_uploaded_file($_FILES["photo"]["tmp_name"], $photo);
        $sqlUpdate = "UPDATE employees SET full_name='$full_name', email='$email', position='$position',
        salary='$salary', date_hired='$date_hired', status='$status', photo='$photo' WHERE id=$id";
    } else {
        $sqlUpdate = "UPDATE employees SET full_name='$full_name', email='$email', position='$position',
        salary='$salary', date_hired='$date_hired', status='$status' WHERE id=$id";
    }

    if (mysqli_query($conn, $sqlUpdate)) {
        header("Location: view_employee.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Edit Employee</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include "user_header.php"; ?>
<div class="container mt-4">
<h2>Edit Employee</h2>
<form method="post" enctype="multipart/form-data">
  <div class="mb-3">
    <input name="full_name" class="form-control" value="<?php echo htmlspecialchars($row['full_name']); ?>" required>
  </div>
  <div class="mb-3">
    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($row['email']); ?>" required>
  </div>
  <div class="mb-3">
    <input name="position" class="form-control" value="<?php echo htmlspecialchars($row['position']); ?>" required>
  </div>
  <div class="mb-3">
    <input type="number" name="salary" class="form-control" value="<?php echo $row['salary']; ?>" required>
  </div>
  <div class="mb-3">
    <input type="date" name="date_hired" class="form-control" value="<?php echo $row['date_hired']; ?>" required>
  </div>
  <div class="mb-3">
    <select name="status" class="form-control">
      <option <?php if($row['status']=='Active') echo 'selected'; ?>>Active</option>
      <option <?php if($row['status']=='Inactive') echo 'selected'; ?>>Inactive</option>
    </select>
  </div>
  <div class="mb-3">
    <input type="file" name="photo" class="form-control">
    <small>Leave blank to keep existing photo.</small>
  </div>
  <button class="btn btn-primary">Update</button>
</form>
</div>
</body>
</html>
