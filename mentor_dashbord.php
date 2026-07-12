
<?php
session_start();
include "Includes/db.php";


if (!isset($_SESSION['role']) || $_SESSION['role'] != 'mentor') {
    header("Location: login.php");
    exit();
}

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

// fetch GPA submissions for mentor

$gpa_query = $conn->prepare(

"SELECT users.usersName,
gpa_submissions.semester,
gpa_submissions.gpa

FROM gpa_submissions

JOIN users
ON gpa_submissions.student_id = users.usersId

WHERE gpa_submissions.mentor_id=?"

);

$gpa_query->bind_param("i", $mentor_id);

$gpa_query->execute();

$gpa_reports = $gpa_query->get_result();
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
     <!-- bootstrap -->
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
                <a href="mentor_dashbord.php" class="navbar_links">Dashboard</a>
            </li>

            <li class="navbar_item">
                <a href="#" class="navbar_links">Resources</a>
            </li>

            <li class="navbar_item">
                <a href="mentor_appointment.php" class="navbar_links">Appointments</a>
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

<!-- GPA Reports -->

<section class="gpa-reports">

    <h2>Student GPA Reports</h2>

    <div class="reports-container">

        <?php

        if($gpa_reports->num_rows > 0){

            while($report =
            $gpa_reports->fetch_assoc()){

        ?>

        <div class="report-card">

            <h3>
                <?php echo $report['usersName']; ?>
            </h3>

            <p>
                Semester:
                <?php echo $report['semester']; ?>
            </p>

            <span>
                GPA:
                <?php echo $report['gpa']; ?>
            </span>

        </div>

        <?php

            }

        }else{

            echo "

            <div class='empty-box'>

                <h3>
                No GPA Reports Yet
                </h3>

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

