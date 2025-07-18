<?php
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = mysqli_real_escape_string($conn, $_POST["email"]); // email 
    $password = $_POST["password"];

    // Query for user by email or username
    $sql = "SELECT * FROM users WHERE email='$login' OR username='$login'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["user_id"] = $user["id"];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid email/username or password.";
    }
}

// Redirect if already logged in
if (isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
    exit();
}

include "header.php";
?>

<!-- Home and Logout Buttons -->


<div class="auth-container">
  <div class="auth-box">
    <h2><i class="fas fa-sign-in-alt me-2"></i>Login</h2>
    
    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form method="post">
      <div class="mb-3">
        <label for="email" class="form-label">Email or Username</label>
        <input type="text" class="form-control" id="email" name="email" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
      </div>
      <button type="submit" class="btn btn-primary">
        <i class="fas fa-sign-in-alt me-2"></i>Login
      </button>
      <a href="register.php" class="btn btn-link">Don't have an account? Register</a>
    </form>
  </div>
</div>
