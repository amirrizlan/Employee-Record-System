<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Employee Records System - Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --primary-color: #3498db;
      --secondary-color: #2c3e50;
      --accent-color: #e74c3c;
    }
    
    body {
      background: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .dashboard-container {
      margin-top: 20px;
      padding: 20px;
      background: white;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0,0,0,0.05);
    }
    
    .sidebar {
      background: var(--secondary-color);
      color: white;
      min-height: calc(100vh - 56px);
      padding-top: 20px;
    }
    
    .sidebar .nav-link {
      color: rgba(255,255,255,0.8);
      padding: 10px 15px;
      margin-bottom: 5px;
      border-radius: 5px;
    }
    
    .sidebar .nav-link:hover, .sidebar .nav-link.active {
      background: rgba(255,255,255,0.1);
      color: white;
    }
    
    .sidebar .nav-link i {
      margin-right: 10px;
      width: 20px;
      text-align: center;
    }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="dashboard.php">
      <i class="fas fa-users me-2"></i>Employee System
    </a>
    <div class="d-flex align-items-center">
      <span class="text-light me-3">Welcome, <?php
        if (!empty($_SESSION['username'])) {
          echo htmlspecialchars($_SESSION['username']);
        } elseif (!empty($_SESSION['user_id'])) {
          // Fallback: fetch username from DB if not set in session
          include_once 'config.php';
          $uid = intval($_SESSION['user_id']);
          $q = mysqli_query($conn, "SELECT username FROM users WHERE id='$uid' LIMIT 1");
          if ($row = mysqli_fetch_assoc($q)) {
            echo htmlspecialchars($row['username']);
            $_SESSION['username'] = $row['username']; // cache for next time
          } else {
            echo 'User';
          }
        } else {
          echo 'User';
        }
      ?></span>
      
    </div>
  </div>
</nav>
<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-3 col-lg-2 d-md-block sidebar bg-dark">
      <div class="position-sticky pt-3">
        <ul class="nav flex-column">
          <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="view_employee.php"><i class="fas fa-users"></i> View Employees</a></li>
          <li class="nav-item"><a class="nav-link" href="add_employee.php"><i class="fas fa-user-plus"></i> Add Employee</a></li>
          <li class="nav-item"><a class="nav-link" href="logout.php" onclick="return confirm('Are you sure you want to logout?');"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
      </div>
    </div>
    
    <!-- Main content: Start main tag, but let each page provide its own content inside this column -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
    <!-- Page content will be injected here by each individual page -->