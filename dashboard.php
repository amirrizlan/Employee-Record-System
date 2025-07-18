<?php
include "config.php";
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

include "user_header.php";

/** 
 * API Data Fetching Functions 
 */

// Malaysia Holidays from Calendarific
function getMalaysiaHolidays($year) {
    $api_key = "YD1SpjGQcNRQq47DiwhWgrNhatAIgoEd";
    $url = "https://calendarific.com/api/v2/holidays?api_key={$api_key}&country=MY&year={$year}";
    
    $response = @file_get_contents($url);
    if ($response !== false) {
        $data = json_decode($response, true);
        return $data['response']['holidays'] ?? [];
    }
    return [];
}

// GNews API Data
function getBusinessNews() {
    $api_key = '8ba799e28a0065d56df35779c57f008a';
    $url = "https://gnews.io/api/v4/top-headlines?country=my&token=$api_key";
    
    $response = @file_get_contents($url);
    if ($response !== false) {
        $data = json_decode($response, true);
        return array_slice($data['articles'] ?? [], 0, 3);
    }
    return [];
}

/** 
 * Dashboard Data 
 */
$metrics = [
    'total_employees' => mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM employees"))[0],
    'avg_salary' => mysqli_fetch_row(mysqli_query($conn, "SELECT AVG(salary) FROM employees"))[0],
    'active_employees' => mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM employees WHERE status='Active'"))[0]
];

$upcomingHolidays = array_slice(getMalaysiaHolidays(date('Y')), 0, 3);
$businessNews = getBusinessNews();
$topEmployees = mysqli_query($conn, "SELECT full_name, position, salary FROM employees ORDER BY salary DESC LIMIT 3");
?>

<main class="col-md-9 col-lg-10 px-md-4 py-4">
  <!-- Dashboard Header -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-tachometer-alt me-2"></i>Dashboard Overview</h2>
    <div class="text-muted small">Last updated: <?= date('M j, Y g:i a') ?></div>
  </div>

  <!-- Key Metrics Row -->
  <div class="row g-4 mb-4">
    <div class="col-md-4">
      <div class="card shadow-sm h-100">
        <div class="card-body text-center">
          <div class="metric-icon bg-primary-light">
            <i class="fas fa-users text-primary"></i>
          </div>
          <h3 class="mt-3"><?= $metrics['total_employees'] ?></h3>
          <h5 class="text-muted">Total Employees</h5>
        </div>
      </div>
    </div>
    
    <div class="col-md-4">
      <div class="card shadow-sm h-100">
        <div class="card-body text-center">
          <div class="metric-icon bg-success-light">
            <i class="fas fa-money-bill-wave text-success"></i>
          </div>
          <h3 class="mt-3">RM<?= number_format($metrics['avg_salary'], 2) ?></h3>
          <h5 class="text-muted">Average Salary</h5>
        </div>
      </div>
    </div>
    
    <div class="col-md-4">
      <div class="card shadow-sm h-100">
        <div class="card-body text-center">
          <div class="metric-icon bg-info-light">
            <i class="fas fa-user-check text-info"></i>
          </div>
          <h3 class="mt-3"><?= $metrics['active_employees'] ?></h3>
          <h5 class="text-muted">Active Employees</h5>
        </div>
      </div>
    </div>
  </div>

  <!-- Content Sections -->
  <div class="row g-4">
    <!-- Left Column -->
    <div class="col-lg-6">
      <!-- Holidays Card -->
      <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
          <div class="d-flex justify-content-between align-items-center">
            <span><i class="fas fa-calendar-alt me-2"></i>Upcoming Holidays</span>
            <small><?= date('Y') ?></small>
          </div>
        </div>
        <div class="card-body">
          <?php if (!empty($upcomingHolidays)): ?>
            <div class="list-group">
              <?php foreach ($upcomingHolidays as $holiday): ?>
                <div class="list-group-item border-0 py-3">
                  <div class="d-flex justify-content-between align-items-center">
                    <div>
                      <h6 class="mb-1"><?= htmlspecialchars($holiday['name']) ?></h6>
                      <small class="text-muted">
                        <?= date('l, F j', strtotime($holiday['date']['iso'])) ?>
                      </small>
                    </div>
                    <span class="badge bg-primary rounded-pill px-3 py-2">
                      <?= date('M j', strtotime($holiday['date']['iso'])) ?>
                    </span>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <div class="text-center py-4 text-muted">
              <i class="fas fa-calendar-times fa-2x mb-3"></i>
              <p>No upcoming holidays data</p>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Top Employees Card -->
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
          <i class="fas fa-award me-2"></i>High Salary Employees
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead class="table-light">
                <tr>
                  <th>Name</th>
                  <th>Position</th>
                  <th class="text-end">Salary</th>
                </tr>
              </thead>
              <tbody>
                <?php while ($emp = mysqli_fetch_assoc($topEmployees)): ?>
                  <tr>
                    <td><?= htmlspecialchars($emp['full_name']) ?></td>
                    <td><?= htmlspecialchars($emp['position']) ?></td>
                    <td class="text-end fw-bold">RM<?= number_format($emp['salary'], 2) ?></td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Right Column -->
    <div class="col-lg-6">
      <!-- Business News Card -->
      <div class="card shadow-sm h-100">
        <div class="card-header bg-primary text-white">
          <div class="d-flex justify-content-between align-items-center">
            <span><i class="fas fa-newspaper me-2"></i>Business News</span>
            <small>Malaysia</small>
          </div>
        </div>
        <div class="card-body">
          <?php if (!empty($businessNews)): ?>
            <div class="news-feed">
              <?php foreach ($businessNews as $article): ?>
                <div class="news-item mb-4 pb-3 border-bottom">
                  <?php if (!empty($article['image'])): ?>
                    <img src="<?= htmlspecialchars($article['image']) ?>" 
                         class="img-fluid rounded mb-3" 
                         alt="<?= htmlspecialchars($article['title']) ?>">
                  <?php endif; ?>
                  <h5>
                    <a href="<?= htmlspecialchars($article['url']) ?>" 
                       target="_blank" 
                       class="text-decoration-none">
                      <?= htmlspecialchars($article['title']) ?>
                    </a>
                  </h5>
                  <?php if (!empty($article['description'])): ?>
                    <p class="text-muted"><?= htmlspecialchars($article['description']) ?></p>
                  <?php endif; ?>
                  <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                      <?= !empty($article['source']['name']) ? htmlspecialchars($article['source']['name']) : 'Source' ?>
                    </small>
                    <small class="text-muted">
                      <?= !empty($article['publishedAt']) ? date('M j, g:i a', strtotime($article['publishedAt'])) : '' ?>
                    </small>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <div class="text-center py-4 text-muted">
              <i class="fas fa-newspaper fa-2x mb-3"></i>
              <p>No news articles available</p>
              <button class="btn btn-sm btn-outline-primary">Refresh</button>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</main>

<style>
  .metric-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
  }
  .bg-primary-light { background-color: rgba(13, 110, 253, 0.1); }
  .bg-success-light { background-color: rgba(25, 135, 84, 0.1); }
  .bg-info-light { background-color: rgba(13, 202, 240, 0.1); }
  .news-item img { max-height: 180px; object-fit: cover; }
  .card { border: none; border-radius: 10px; }
  .card-header { border-radius: 10px 10px 0 0 !important; }
</style>

<?php include "user_footer.php"; ?>