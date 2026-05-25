<<<<<<< HEAD
<?php
session_start();
include "Includes/db.php";

// fetch assigned students for mentor
$mentor_id = $_SESSION['user_id'];

$query = $conn->prepare(

"SELECT users.usersName,
users.student_id,
users.course

FROM mentor_assignments

JOIN users
ON mentor_assignments.student_id = users.usersId

WHERE mentor_assignments.mentor_id=?"

);

$query->bind_param("i", $mentor_id);

$query->execute();

$students = $query->get_result();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'mentor') {
    header("Location: login.php");
    exit();
}
?>
 

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="mentor_dashbord.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <title>Document</title>
</head>
<body>



    <!-- Navigation bar -->
<nav class="navbar">
    <div class="navbar_container">

        <a href="index.php" id="navbar_logo">
            <img src="Assets/logoo.png" alt="Logo" class="logo">
            Smart Mentoring System
        </a>

        <div class="navbar_toggle" id="mobile-menu">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </div>

        <ul class="navbar_menu">

            <li class="navbar_item">
                <a href="mentor_dashboard.php" class="navbar_links">Dashboard</a>
            </li>

            <li class="navbar_item">
                <a href="#" class="navbar_links">Resources</a>
            </li>

            <li class="navbar_item">
                <a href="#" class="navbar_links">Appointments</a>
            </li>

            <!-- <li class="navbar_item">
                <a href="#" class="navbar_links">Contact</a>
            </li> -->

            <!-- <li class="navbar_btn">
                <a href="login.php" class="button">Login</a>
            </li> -->

        </ul>

        <div class="profile-menu">

    <div class="profile-icon" onclick="toggleMenu()">
        <i class='bx bx-user'></i>
    </div>

    <div class="sub-menu-wrap" id="subMenu">
        <div class="sub-menu">

            <a href="#" class="sub-menu-link">
                <i class='bx bx-user'></i>
                <p>My Profile</p>
            </a>

            <a href="#" class="sub-menu-link">
                <i class='bx bx-bell'></i>
                <p>Notifications</p>
            </a>

            <a href="#" class="sub-menu-link">
                <i class='bx bx-message-detail'></i>
                <p>Messages</p>
            </a>

            <a href="#" class="sub-menu-link">
                <i class='bx bx-cog'></i>
                <p>Settings</p>
            </a>

            <a href="logout.php" class="sub-menu-link">
                <i class='bx bx-log-out'></i>
                <p>Logout</p>
            </a>

        </div>
    </div>

</div>

    </div>
</nav>

<!-- hero section -->

<section class="mentor-dashboard-header">

    <h1>
        Welcome Back,
        <?php echo $_SESSION['name']; ?> 👋
    </h1>

    <div class="mentor-stats">

        <div class="stat-card">

            <h2 id="student-count">0</h2>

            <p>Students</p>

        </div>

        <div class="stat-card">

            <h2 id="gpa-count">0</h2>

            <p>GPA Reports</p>

        </div>

        <div class="stat-card">

            <h2 id="appointment-count">0</h2>

            <p>Appointments</p>

        </div>

    </div>

</section>

<!-- Assigned Students -->

<section class="assigned-students">

    <h2>Assigned Students</h2>

    <div class="students-container">

        <?php

        if($students->num_rows > 0){

            while($student =
            $students->fetch_assoc()){

        ?>

        <div class="student-card">

            <h3>

                <?php
                echo $student['usersName'];
                ?>

            </h3>

            <p>

                <?php
                echo $student['student_id'];
                ?>

            </p>

            <span>

                <?php
                echo $student['course'];
                ?>

            </span>

        </div>

        <?php

            }

        }else{

            echo "

            <div class='empty-box'>

                <i class='bx bx-user-x'></i>

                <h3>
                No Students Assigned Yet
                </h3>

                <p>
                Students who choose you
                will appear here.
                </p>

            </div>

            ";

        }

        ?>

    </div>

</section>

<script>
// Toggle profile menu
  let subMenu = document.getElementById("subMenu");

function toggleMenu(){
    subMenu.classList.toggle("open-menu");
}

// Animate numbers

function animateValue(id, start, end, duration){

    let obj = document.getElementById(id);

    let range = end - start;

    let current = start;

    let increment = end > start ? 1 : -1;

    if(range == 0){
        obj.innerHTML = end;
        return;
    }

    let stepTime =
    Math.abs(Math.floor(duration / range));

    let timer = setInterval(function(){

        current += increment;

        obj.innerHTML = current;

        if(current == end){

            clearInterval(timer);
        }

    }, stepTime);
}

animateValue("student-count", 0, 0, 1000);

animateValue("gpa-count", 0, 0, 1000);

animateValue("appointment-count", 0, 0, 1000);

</script>

</body>
</html>

<?php include "Includes/footer.php"; ?>
=======

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
>>>>>>> ee59040cc70d5b4e2460ecdb82f21e941850dc09
