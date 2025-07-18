<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Employee Records System | Empowering HR</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary-color: #4361ee;
      --secondary-color: #3a0ca3;
      --accent-color: #f72585;
      --light-color: #f8f9fa;
      --dark-color: #212529;
      --success-color: #4cc9f0;
      --warning-color: #f8961e;
    }

    body {
      background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
      font-family: 'Inter', sans-serif;
      color: var(--dark-color);
      line-height: 1.6;
    }

    .hero {
      padding: 100px 20px;
      background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
      color: white;
      text-align: center;
    }

    .hero h1 {
      font-size: 3rem;
      font-weight: 800;
      margin-bottom: 20px;
    }

    .hero p {
      font-size: 1.3rem;
      margin-bottom: 30px;
    }

    .btn-custom {
      background-color: white;
      color: var(--primary-color);
      font-weight: 600;
      padding: 12px 25px;
      border-radius: 10px;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .btn-custom:hover {
      background-color: var(--accent-color);
      color: white;
      transform: translateY(-3px);
    }

    .features, .testimonials, .about, .video-section {
      padding: 60px 20px;
    }

    .feature-box, .testimonial-box {
      background: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.05);
      text-align: center;
    }

    .feature-icon {
      font-size: 2.8rem;
      margin-bottom: 20px;
      color: var(--primary-color);
    }

    .video-section video {
      max-width: 100%;
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }

    .testimonial-carousel .carousel-item {
      transition: transform 0.5s ease-in-out;
    }

    .footer {
      text-align: center;
      padding: 30px;
      background: var(--secondary-color);
      color: white;
    }

    .dark-toggle {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 999;
    }

    body.dark-mode {
      background: #1e1e2f;
      color: #ddd;
    }
    body.dark-mode .feature-box,
    body.dark-mode .testimonial-box {
      background: #2d2d44;
    }
    body.dark-mode .btn-custom {
      background-color: #4cc9f0;
      color: white;
    }
  </style>
</head>
<body>

<div class="dark-toggle">
  <button class="btn btn-sm btn-outline-light" onclick="toggleDark()"><i class="fas fa-moon"></i></button>
</div>

<section class="hero">
  <div class="container">
    <h1>Welcome to Employee Records</h1>
    <p>Smart, Secure & Seamless HR Management System</p>
    <a href="login.php" class="btn btn-custom">Login</a>
    <a href="register.php" class="btn btn-custom">Register</a>
  </div>
</section>

<section class="features container text-center">
  <div class="row g-4">
    <div class="col-md-4">
      <div class="feature-box">
        <div class="feature-icon"><i class="fas fa-user-cog"></i></div>
        <h4>Manage Employees</h4>
        <p>View, add, and update staff records easily.</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="feature-box">
        <div class="feature-icon"><i class="fas fa-chart-line"></i></div>
        <h4>Real-Time Reports</h4>
        <p>Monitor Number of employee & average salary.</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="feature-box">
        <div class="feature-icon"><i class="fas fa-lock"></i></div>
        <h4>Secure Login</h4>
        <p>Personal information is secured.</p>
      </div>
    </div>
  </div>
</section>

<section class="video-section text-center">
  <div class="container">
    <h2>Company Overview</h2>
    <p>Watch our journey and commitment to better HR solutions.</p>
    <video controls autoplay muted loop>
      <source src="src/video.mp4" type="video/mp4">
      Your browser does not support the video tag.
    </video>
  </div>
</section>

<section class="testimonials container">
  <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <div class="testimonial-box">
          <p>"This system transformed our HR process. Highly recommended!"</p>
          <h6>- Khawa, HR Manager</h6>
        </div>
      </div>
      <div class="carousel-item">
        <div class="testimonial-box">
          <p>"Simple and elegant UI with powerful functionality. Love it!"</p>
          <h6>- Aliff, CEO</h6>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="about text-center">
  <div class="container">
    <h3>About Us</h3>
    <p>We simplify HR with innovative solutions. Whether small businesses or large corporations, our tools adapt and scale with your workforce.</p>
  </div>
</section>

<footer class="footer">
  <p>&copy; <?php echo date('Y'); ?> Employee Records. All rights reserved.</p>
</footer>

<script>
  function toggleDark() {
    document.body.classList.toggle("dark-mode");
  }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>