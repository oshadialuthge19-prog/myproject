<?php

session_start();

include "Includes/db.php";

if (
    !isset($_SESSION['role']) ||
    $_SESSION['role'] !== 'mentor'
) {
    header("Location: login.php");
    exit();
}

$mentor_id = $_SESSION['user_id'];


/* =========================
   ASSIGNED STUDENTS
========================= */

$query = $conn->prepare(
    "SELECT 
        users.usersName,
        users.student_id,
        users.course
    FROM mentor_assignments
    JOIN users
        ON mentor_assignments.student_id = users.usersId
    WHERE mentor_assignments.mentor_id = ?"
);

$query->bind_param("i", $mentor_id);

$query->execute();

$students = $query->get_result();

$student_count = $students->num_rows;


/* =========================
   GPA REPORTS
========================= */

$gpa_query = $conn->prepare(
    "SELECT 
        users.usersName,
        gpa_submissions.semester,
        gpa_submissions.gpa
    FROM gpa_submissions
    JOIN users
        ON gpa_submissions.student_id = users.usersId
    WHERE gpa_submissions.mentor_id = ?"
);

$gpa_query->bind_param("i", $mentor_id);

$gpa_query->execute();

$gpa_reports = $gpa_query->get_result();

$gpa_count = $gpa_reports->num_rows;


/* =========================
   APPOINTMENT COUNT
========================= */

$appointment_query = $conn->prepare(
    "SELECT COUNT(*) AS total
    FROM appointments
    WHERE mentor_id = ?"
);

$appointment_query->bind_param(
    "i",
    $mentor_id
);

$appointment_query->execute();

$appointment_result =
$appointment_query->get_result();

$appointment_data =
$appointment_result->fetch_assoc();

$appointment_count =
$appointment_data['total'] ?? 0;

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0"
>

<!-- Bootstrap -->

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet"
>
<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<!-- Boxicons -->

<link
href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
rel="stylesheet"
>

<!-- Header CSS -->

<link
rel="stylesheet"
href="Includes/mentor_header.css">

<!-- footer css -->
<link rel="stylesheet" href="Includes/footer.css">

<!-- Dashboard CSS -->

<link
rel="stylesheet"
href="mentor_dashbord.css"
>

<title>Mentor Dashboard</title>

</head>


<body>


<?php include "Includes/mentor_header.php"; ?>


<main class="mentor-dashboard">


<!-- =========================
     WELCOME AREA
========================= -->

<section class="dashboard-welcome">

<div>

<span class="dashboard-label">

Mentor Dashboard

</span>

<h1>

Welcome back,
<?php echo htmlspecialchars($_SESSION['name']); ?>

</h1>

<p>

Manage your students, review academic progress
and stay connected with your mentoring activities.

</p>

</div>


<div class="welcome-decoration">

<i class='bx bx-book-reader'></i>

</div>

</section>



<!-- =========================
     STATISTICS
========================= -->

<section class="dashboard-stats">


<div class="dashboard-stat-card">

<div class="stat-icon students-icon">

<i class='bx bx-group'></i>

</div>

<div>

<span>Assigned Students</span>

<h2
class="counter"
data-count="<?php echo $student_count; ?>"
>

0

</h2>

</div>

</div>



<div class="dashboard-stat-card">

<div class="stat-icon gpa-icon">

<i class='bx bx-line-chart'></i>

</div>

<div>

<span>GPA Reports</span>

<h2
class="counter"
data-count="<?php echo $gpa_count; ?>"
>

0

</h2>

</div>

</div>



<div class="dashboard-stat-card">

<div class="stat-icon appointment-icon">

<i class='bx bx-calendar-check'></i>

</div>

<div>

<span>Appointments</span>

<h2
class="counter"
data-count="<?php echo $appointment_count; ?>"
>

0

</h2>

</div>

</div>


</section>



<!-- =========================
     DASHBOARD CONTENT
========================= -->

<div class="row g-4">


<!-- ASSIGNED STUDENTS -->

<div class="col-lg-7">


<section class="dashboard-panel">


<div class="panel-heading">

<div>

<h2>Assigned Students</h2>

<p>
Students currently assigned to you
</p>

</div>


<div class="panel-icon">

<i class='bx bx-group'></i>

</div>

</div>



<div class="student-list">


<?php if ($students->num_rows > 0) { ?>


<?php while ($student = $students->fetch_assoc()) { ?>


<div class="student-row">


<div class="student-avatar">

<?php

$name =
$student['usersName'] ?? 'Student';

echo strtoupper(
substr($name, 0, 1)
);

?>

</div>


<div class="student-information">

<h3>

<?php
echo htmlspecialchars(
$student['usersName']
);
?>

</h3>

<p>

Student ID:

<?php
echo htmlspecialchars(
$student['student_id']
);
?>

</p>

</div>


<span class="course-badge">

<?php
echo htmlspecialchars(
$student['course']
);
?>

</span>


</div>


<?php } ?>


<?php } else { ?>


<div class="dashboard-empty">

<i class='bx bx-user-x'></i>

<h3>No students assigned yet</h3>

<p>

Students who select you as their mentor
will appear here.

</p>

</div>


<?php } ?>


</div>


</section>


</div>



<!-- QUICK ACTIONS -->

<div class="col-lg-5">


<section class="dashboard-panel quick-panel">


<div class="panel-heading">

<div>

<h2>Quick Actions</h2>

<p>
Frequently used mentor tools
</p>

</div>

</div>



<a
href="mentor_appointment.php"
class="quick-action"
>

<div class="quick-icon">

<i class='bx bx-calendar'></i>

</div>

<div>

<h3>Manage Appointments</h3>

<p>
Review mentoring sessions
</p>

</div>

<i class='bx bx-chevron-right action-arrow'></i>

</a>



<a
href="mentor_profile.php"
class="quick-action"
>

<div class="quick-icon">

<i class='bx bx-user'></i>

</div>

<div>

<h3>Update Profile</h3>

<p>
Manage mentor information
</p>

</div>

<i class='bx bx-chevron-right action-arrow'></i>

</a>



<a
href="#gpa-reports"
class="quick-action"
>

<div class="quick-icon">

<i class='bx bx-line-chart'></i>

</div>

<div>

<h3>Review GPA Reports</h3>

<p>
Monitor student performance
</p>

</div>

<i class='bx bx-chevron-right action-arrow'></i>

</a>


</section>


</div>


</div>



<!-- =========================
     GPA REPORTS
========================= -->

<section
class="dashboard-panel gpa-panel"
id="gpa-reports"
>


<div class="panel-heading">

<div>

<h2>Recent GPA Reports</h2>

<p>
Academic progress submitted by your students
</p>

</div>


<div class="panel-icon">

<i class='bx bx-bar-chart-alt-2'></i>

</div>

</div>



<div class="row g-3">


<?php if ($gpa_reports->num_rows > 0) { ?>


<?php while ($report = $gpa_reports->fetch_assoc()) { ?>


<div class="col-md-6 col-lg-4">


<div class="gpa-report-card">


<div class="gpa-student">

<div class="student-avatar">

<?php

echo strtoupper(
substr(
$report['usersName'],
0,
1
)
);

?>

</div>


<div>

<h3>

<?php
echo htmlspecialchars(
$report['usersName']
);
?>

</h3>

<p>

Semester

<?php
echo htmlspecialchars(
$report['semester']
);
?>

</p>

</div>

</div>



<div class="gpa-value">

<span>GPA</span>

<strong>

<?php
echo htmlspecialchars(
$report['gpa']
);
?>

</strong>

</div>


</div>


</div>


<?php } ?>


<?php } else { ?>


<div class="col-12">


<div class="dashboard-empty">

<i class='bx bx-bar-chart-alt-2'></i>

<h3>No GPA reports yet</h3>

<p>

Student GPA submissions will appear here.

</p>

</div>


</div>


<?php } ?>


</div>


</section>


</main>



<?php include "Includes/footer.php"; ?>



<script>

/* =========================
   COUNTER ANIMATION
========================= */

const counters =
document.querySelectorAll(".counter");


counters.forEach(counter => {

    const target =
    Number(counter.dataset.count);

    let current = 0;


    if(target === 0){

        counter.textContent = 0;

        return;

    }


    const increment =
    Math.max(1, Math.ceil(target / 30));


    const timer =
    setInterval(() => {

        current += increment;


        if(current >= target){

            counter.textContent = target;

            clearInterval(timer);

        }else{

            counter.textContent = current;

        }

    }, 40);

});

</script>


<script
src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


</body>

</html>