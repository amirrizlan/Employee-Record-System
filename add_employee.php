<?php
include "config.php";
if (!isset($_SESSION["user_id"])) header("Location: login.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle random data generation request
    if (isset($_POST['generate_random'])) {
        header('Content-Type: application/json');
        
        // Generate random data
        $firstNames = ['John', 'Jane', 'Michael', 'Emily', 'David', 'Sarah', 'Robert', 'Lisa'];
        $lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Miller', 'Davis', 'Wilson'];
        $positions = ['Manager', 'Developer', 'Designer', 'Accountant', 'HR Specialist', 'Sales Rep'];
        $domains = ['example.com', 'company.com', 'business.org'];
        
        $randomData = [
            'full_name' => $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)],
            'email' => strtolower($firstNames[array_rand($firstNames)]) . '.' . strtolower($lastNames[array_rand($lastNames)]) . '@' . $domains[array_rand($domains)],
            'position' => $positions[array_rand($positions)],
            'salary' => rand(30000, 120000),
            'date_hired' => date('Y-m-d', strtotime('-'.rand(0, 365*5).' days')),
            'status' => (rand(0,1) ? 'Active' : 'Inactive')
        ];
        
        echo json_encode($randomData);
        exit();
    }

    // Handle form submission
    $full_name = mysqli_real_escape_string($conn, $_POST["full_name"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $position = mysqli_real_escape_string($conn, $_POST["position"]);
    $salary = $_POST["salary"];
    $date_hired = $_POST["date_hired"];
    $status = $_POST["status"];

    // Handle image upload
    $photo = "";
    if ($_FILES["photo"]["name"]) {
        $photo = "uploads/" . basename($_FILES["photo"]["name"]);
        move_uploaded_file($_FILES["photo"]["tmp_name"], $photo);
    }

    $sql = "INSERT INTO employees (full_name, email, position, salary, date_hired, photo, status)
            VALUES ('$full_name','$email','$position','$salary','$date_hired','$photo','$status')";
    if (mysqli_query($conn, $sql)) {
        header("Location: view_employee.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Add Employee</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    .generate-random-btn {
      margin-bottom: 20px;
    }
    .form-control:disabled {
      background-color: #f8f9fa;
    }
  </style>
</head>
<body>
<?php include "user_header.php"; ?>
<div class="container mt-4">
  <div class="card border-primary" style="max-width: 500px; margin: 0 auto;">
    <div class="card-body">
      <h2 class="mb-4">Add Employee</h2>
      <button id="generateRandom" class="btn btn-secondary generate-random-btn">
        <i class="fas fa-random me-2"></i>Fill with Random Data
      </button>
      <form method="post" enctype="multipart/form-data" id="employeeForm">
        <div class="mb-3">
          <input name="full_name" id="full_name" class="form-control" placeholder="Full Name" required>
        </div>
        <div class="mb-3">
          <input type="email" name="email" id="email" class="form-control" placeholder="Email" required>
        </div>
        <div class="mb-3">
          <input name="position" id="position" class="form-control" placeholder="Position" required>
        </div>
        <div class="mb-3">
          <div class="input-group" style="max-width: 180px;">
            <span class="input-group-text">RM</span>
            <input type="number" name="salary" id="salary" class="form-control" placeholder="Salary" required>
          </div>
        </div>
        <div class="mb-3">
          <label for="date_hired" class="form-label">Date Hired</label>
          <input type="date" name="date_hired" id="date_hired" class="form-control" style="max-width: 180px;" required>
        </div>
        <div class="mb-3">
          <select name="status" id="status" class="form-control">
            <option>Active</option>
            <option>Inactive</option>
          </select>
        </div>
        <div class="mb-3">
          <input type="file" name="photo" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-save me-2"></i>Save
        </button>
      </form>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
  $('#generateRandom').click(function() {
    // Disable button during request
    $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Generating...');
    
    $.ajax({
      url: '',
      type: 'POST',
      data: { generate_random: true },
      dataType: 'json',
      success: function(data) {
        // Fill form with random data
        $('#full_name').val(data.full_name);
        $('#email').val(data.email);
        $('#position').val(data.position);
        $('#salary').val(data.salary);
        $('#date_hired').val(data.date_hired);
        $('#status').val(data.status);
        
        // Show success message
        alert('Random data generated successfully!');
      },
      error: function() {
        alert('Error generating random data. Please try again.');
      },
      complete: function() {
        // Re-enable button
        $('#generateRandom').prop('disabled', false).html('<i class="fas fa-random me-2"></i>Fill with Random Data');
      }
    });
  });
  
  // Prevent form submission when pressing enter in inputs
  $('#employeeForm').on('keyup keypress', function(e) {
    if(e.keyCode == 13) {
      e.preventDefault();
      return false;
    }
  });
});
</script>
</body>
</html>