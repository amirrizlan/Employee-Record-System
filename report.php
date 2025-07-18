<?php
// Only start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "config.php";

// Redirect if not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Initialize variables
$start_date = date('Y-m-01'); // First day of current month
$end_date = date('Y-m-t');    // Last day of current month
$department_filter = '';

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $start_date = $_POST['start_date'] ?? $start_date;
    $end_date = $_POST['end_date'] ?? $end_date;
    $department_filter = $_POST['department'] ?? '';
}

// Get departments for filter dropdown
$departments = [];
try {
    $dept_query = "SELECT id, name FROM departments ORDER BY name";
    $dept_result = mysqli_query($conn, $dept_query);
    if ($dept_result) {
        while ($row = mysqli_fetch_assoc($dept_result)) {
            $departments[$row['id']] = $row['name'];
        }
    }
} catch (mysqli_sql_exception $e) {
    // Handle error if departments table doesn't exist
    $error = "Department data not available";
}

// Build the report query
$query = "SELECT 
            e.id, 
            e.first_name, 
            e.last_name, 
            e.position, 
            e.hire_date, 
            e.salary,
            e.status
          FROM employees e
          WHERE e.hire_date BETWEEN ? AND ?";

// Add department filter if selected and available
if (!empty($department_filter) && !empty($departments)) {
    $query .= " AND e.department_id = ?";
}

$query .= " ORDER BY e.last_name, e.first_name";

// Prepare and execute the query
$stmt = mysqli_prepare($conn, $query);
if (!empty($department_filter) && !empty($departments)) {
    mysqli_stmt_bind_param($stmt, "sss", $start_date, $end_date, $department_filter);
} else {
    mysqli_stmt_bind_param($stmt, "ss", $start_date, $end_date);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

include "user_header.php";
?>

<div class="dashboard-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-chart-bar me-2"></i>Employee Reports</h2>
        <button class="btn btn-success" onclick="window.print()">
            <i class="fas fa-print me-2"></i>Print Report
        </button>
    </div>

    <!-- Report Filters -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Report Filters</h5>
        </div>
        <div class="card-body">
            <form method="post" class="row g-3">
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" 
                           value="<?= htmlspecialchars($start_date) ?>" required>
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" 
                           value="<?= htmlspecialchars($end_date) ?>" required>
                </div>
                <?php if (!empty($departments)): ?>
                <div class="col-md-4">
                    <label for="department" class="form-label">Department</label>
                    <select class="form-select" id="department" name="department">
                        <option value="">All Departments</option>
                        <?php foreach ($departments as $id => $name): ?>
                            <option value="<?= $id ?>" <?= ($department_filter == $id) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($name) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i>Filter
                    </button>
                </div>
            </form>
            <?php if (isset($error)): ?>
                <div class="alert alert-warning mt-3"><?= $error ?></div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Report Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Total Employees</h6>
                            <h3 class="mb-0"><?= mysqli_num_rows($result) ?></h3>
                        </div>
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <?php
            // Calculate average salary
            $total_salary = 0;
            $count = 0;
            mysqli_data_seek($result, 0); // Reset pointer
            while ($row = mysqli_fetch_assoc($result)) {
                $total_salary += $row['salary'];
                $count++;
            }
            $avg_salary = $count > 0 ? $total_salary / $count : 0;
            mysqli_data_seek($result, 0); // Reset pointer again
            ?>
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Avg. Salary</h6>
                            <h3 class="mb-0">$<?= number_format($avg_salary, 2) ?></h3>
                        </div>
                        <i class="fas fa-dollar-sign fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Payroll Total</h6>
                            <h3 class="mb-0">$<?= number_format($total_salary, 2) ?></h3>
                        </div>
                        <i class="fas fa-calculator fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Report Table -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-table me-2"></i>Employee Details</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Employee Name</th>
                            <?php if (!empty($departments)): ?>
                            <th>Department</th>
                            <?php endif; ?>
                            <th>Position</th>
                            <th>Hire Date</th>
                            <th>Salary</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['id']) ?></td>
                                    <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                                    <?php if (!empty($departments)): ?>
                                    <td><?= htmlspecialchars($row['department'] ?? 'N/A') ?></td>
                                    <?php endif; ?>
                                    <td><?= htmlspecialchars($row['position']) ?></td>
                                    <td><?= date('M j, Y', strtotime($row['hire_date'])) ?></td>
                                    <td>$<?= number_format($row['salary'], 2) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $row['status'] == 'active' ? 'success' : 'secondary' ?>">
                                            <?= ucfirst($row['status']) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="<?= !empty($departments) ? '7' : '6' ?>" class="text-center">No employees found for the selected criteria</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Export Options -->
    <div class="mt-4 text-end">
        <a href="export_csv.php?start_date=<?= urlencode($start_date) ?>&end_date=<?= urlencode($end_date) ?>&department=<?= urlencode($department_filter) ?>" 
           class="btn btn-outline-success me-2">
            <i class="fas fa-file-csv me-2"></i>Export to CSV
        </a>
        <a href="export_pdf.php?start_date=<?= urlencode($start_date) ?>&end_date=<?= urlencode($end_date) ?>&department=<?= urlencode($department_filter) ?>" 
           class="btn btn-outline-danger">
            <i class="fas fa-file-pdf me-2"></i>Export to PDF
        </a>
    </div>
</div>

<?php include "user_footer.php"; ?>