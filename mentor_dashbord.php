
<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'mentor') {
    header("Location: login.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Mentor Dashboard</title>
  <link rel="stylesheet" href="style.css?v=3" />
</head>
<body class="mentor-dashboard-page">

  <div class="mentor-dashboard">

    <header class="mentor-header">
      <div class="logo">
        <img src="Assets/logo.png" alt="Mentoring Platform Logo" />
        <div>
          <h3>Mentoring Platform</h3>
          <p>ABC University</p>
        </div>
      </div>

      <nav class="nav">
        <a href="index.php">Home</a>
        <a href="mentor_dashbord.php">Dashboard</a>
      </nav>

      <div class="mentor-icons">
        <span>🔔</span>
        <span>👤</span>
        <span>⌄</span>
        <span>☰</span>
      </div>
    </header>

    <div class="portal-buttons">
      <button class="selected">Mentor Portal</button>
      <button>Student Portal</button>
    </div>

    <main>
      <h2>Have a nice day mentor name !</h2>

      <div class="mentor-grid">

        <section class="mentor-left">
          <div class="card upcoming">
            <h3>Up Coming Sessions..</h3>
            <div class="session-box">
              <p>Monday - at 10.00 am (Via Zoom) - 1198s0</p>
              <p>Friday - at 9.00 am</p>
            </div>
          </div>

          <div class="card notices">
            <h3>Important Notices</h3>

            <div class="notice-row">
              <p>Assessment submitted by 32190s</p>
              <button>View Details</button>
            </div>

            <div class="notice-row">
              <p>Session Reminder</p>
              <button>View Details</button>
            </div>
          </div>
        </section>

        <section class="mentor-middle">
          <div class="card calendar">
            <h3>Calender</h3>
            <input type="date" />
          </div>

          <div class="upload-area">
            <button>📁 Upload files</button>
          </div>
        </section>

        <section class="mentor-right">
          <div class="side-menu">
            <a href="#">Student Request</a>
            <a href="#">My Student</a>
            <a href="#">Sessions</a>
            <a href="#">Availability</a>
            <a href="#">Log Out</a>
          </div>

          <div class="mentor-image">
            <img src="Assets/mentor.png" alt="Mentor illustration" />
          </div>

          <div class="todo-card">
            <h3>To Do List</h3>

            <ul>
              <li>Identify current status of students</li>
              <li>Identify gaps of each students</li>
              <li>Make new assessments</li>
              <li>Learn a new method</li>
              <li>Review assessments</li>
            </ul>

            <div class="todo-actions">
              <button>Add</button>
              <button>Delete</button>
            </div>
          </div>
        </section>

      </div>
    </main>

    <!-- <footer>
      <div class="footer-logo">
        <img src="Assets/logo.png" alt="Mentoring Platform Logo" />
        <p>Mentoring Platform<br>ABC University</p>
      </div>

      <p>What we DO!<br>Privacy Policy</p>
      <p>Student Portal<br>Mentor Portal</p>
      <p>Contact Us : &nbsp;&nbsp; Email: mentor@gmail.com</p>
      <p>Address: 123/u,yuimanwer, reyqw.</p>
    </footer> -->

  </div>

</body>
</html>
