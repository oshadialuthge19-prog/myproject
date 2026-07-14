<?php

session_start();

include "Includes/db.php";


/* ==========================
   STUDENT ACCESS
========================== */

if (
    !isset($_SESSION['role']) ||
    $_SESSION['role'] != 'student'
) {

    header("Location: login.php");
    exit();
}


$student_id = $_SESSION['user_id'];


/* ==========================
   ASSIGNED MENTOR
========================== */

$mentorQuery = $conn->prepare(

    "SELECT
        users.usersId,
        users.usersName,
        mentor_profiles.specialization,
        mentor_profiles.department,
        mentor_profiles.qualification,
        mentor_profiles.profile_picture

    FROM mentor_assignments

    JOIN users
    ON mentor_assignments.mentor_id = users.usersId

    LEFT JOIN mentor_profiles
    ON users.usersId = mentor_profiles.mentor_id

    WHERE mentor_assignments.student_id = ?

    LIMIT 1"

);

$mentorQuery->bind_param(
    "i",
    $student_id
);

$mentorQuery->execute();

$mentorResult =
$mentorQuery->get_result();

$assignedMentor =
$mentorResult->fetch_assoc();


/* ==========================
   GPA REPORT COUNT
========================== */

$gpaCountQuery = $conn->prepare(

    "SELECT COUNT(*) AS total
    FROM gpa_submissions
    WHERE student_id = ?"

);

$gpaCountQuery->bind_param(
    "i",
    $student_id
);

$gpaCountQuery->execute();

$gpaCountResult =
$gpaCountQuery->get_result();

$gpaCountData =
$gpaCountResult->fetch_assoc();

$gpaCount =
$gpaCountData['total'] ?? 0;


/* ==========================
   LATEST GPA
========================== */

$latestGpaQuery = $conn->prepare(

    "SELECT semester, gpa
    FROM gpa_submissions
    WHERE student_id = ?
    ORDER BY id DESC
    LIMIT 1"

);

$latestGpaQuery->bind_param(
    "i",
    $student_id
);

$latestGpaQuery->execute();

$latestGpaResult =
$latestGpaQuery->get_result();

$latestGpa =
$latestGpaResult->fetch_assoc();


/* ==========================
   APPOINTMENT COUNT
========================== */

$appointmentCountQuery = $conn->prepare(

    "SELECT COUNT(*) AS total
    FROM appointments
    WHERE student_id = ?"

);

$appointmentCountQuery->bind_param(
    "i",
    $student_id
);

$appointmentCountQuery->execute();

$appointmentCountResult =
$appointmentCountQuery->get_result();

$appointmentCountData =
$appointmentCountResult->fetch_assoc();

$appointmentCount =
$appointmentCountData['total'] ?? 0;


/* ==========================
   UPCOMING APPOINTMENTS
========================== */

$appointmentQuery = $conn->prepare(

    "SELECT
        appointments.*,
        users.usersName AS mentor_name

    FROM appointments

    JOIN users
    ON appointments.mentor_id = users.usersId

    WHERE appointments.student_id = ?

    ORDER BY appointments.appointment_date ASC

    LIMIT 3"

);

$appointmentQuery->bind_param(
    "i",
    $student_id
);

$appointmentQuery->execute();

$appointments =
$appointmentQuery->get_result();


/* ==========================
   DASHBOARD COUNTS
========================== */

$mentorCount =
$assignedMentor ? 1 : 0;

?>


<!DOCTYPE html>

<html lang="en">


<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >


    <!-- Boxicons -->

    <link
        href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
        rel="stylesheet"
    >


    <!-- Font Awesome -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >


    <!-- Student Header -->

    <link
        rel="stylesheet"
        href="Includes/student_header.css"
    >


    <!-- Dashboard -->

    <link
        rel="stylesheet"
        href="student_dashbord.css"
    >


    <!-- Footer -->

    <link
        rel="stylesheet"
        href="Includes/footer.css"
    >


    <title>
        Student Dashboard
    </title>

</head>


<body>


<?php include "Includes/student_header.php"; ?>


<main class="student-dashboard">


<!-- ==========================
     DASHBOARD HERO
========================== -->

<section class="dashboard-hero">

    <div class="hero-content">

        <span class="dashboard-label">

            Student Dashboard

        </span>


        <h1>

            Welcome back,

            <span>

                <?php
                echo htmlspecialchars(
                    $_SESSION['name']
                );
                ?>

            </span>

            👋

        </h1>


        <p>

            Track your academic progress,
            connect with your mentor and manage
            your mentoring activities from one place.

        </p>

    </div>


    <div class="hero-decoration">

        <div class="hero-icon">

            <i class='bx bx-graduation'></i>

        </div>

    </div>

</section>



<!-- ==========================
     STATISTICS
========================== -->

<section class="dashboard-stats">


    <!-- MENTOR -->

    <div class="student-stat-card">

        <div class="stat-icon mentor-icon">

            <i class='bx bx-user-check'></i>

        </div>


        <div class="stat-content">

            <span>
                Assigned Mentor
            </span>

            <h2 id="mentor-count">

                0

            </h2>

        </div>

    </div>



    <!-- GPA -->

    <div class="student-stat-card">

        <div class="stat-icon gpa-icon">

            <i class='bx bx-line-chart'></i>

        </div>


        <div class="stat-content">

            <span>
                GPA Reports
            </span>

            <h2 id="gpa-count">

                0

            </h2>

        </div>

    </div>



    <!-- APPOINTMENTS -->

    <div class="student-stat-card">

        <div class="stat-icon appointment-icon">

            <i class='bx bx-calendar-check'></i>

        </div>


        <div class="stat-content">

            <span>
                Appointments
            </span>

            <h2 id="appointment-count">

                0

            </h2>

        </div>

    </div>


</section>



<!-- ==========================
     MAIN DASHBOARD GRID
========================== -->

<section class="dashboard-main-grid">


<!-- ==========================
     ASSIGNED MENTOR
========================== -->

<div class="dashboard-panel mentor-panel">


    <div class="panel-heading">

        <div>

            <h2>
                Assigned Mentor
            </h2>

            <p>
                Your current academic mentor
            </p>

        </div>


        <div class="heading-icon">

            <i class='bx bx-user-voice'></i>

        </div>

    </div>


    <?php if ($assignedMentor) { ?>


        <div class="mentor-profile-card">


            <div class="mentor-avatar">


                <?php if (
                    !empty(
                        $assignedMentor['profile_picture']
                    )
                ) { ?>


                    <img
                        src="<?php
                        echo htmlspecialchars(
                            $assignedMentor['profile_picture']
                        );
                        ?>"
                        alt="Mentor"
                    >


                <?php } else { ?>


                    <div class="mentor-default-avatar">

                        <i class='bx bx-user'></i>

                    </div>


                <?php } ?>


            </div>


            <div class="mentor-information">


                <h3>

                    <?php
                    echo htmlspecialchars(
                        $assignedMentor['usersName']
                    );
                    ?>

                </h3>


                <span class="mentor-specialization">

                    <?php
                    echo htmlspecialchars(
                        $assignedMentor['specialization']
                        ?? 'Academic Mentor'
                    );
                    ?>

                </span>


                <p>

                    <i class='bx bx-buildings'></i>

                    <?php
                    echo htmlspecialchars(
                        $assignedMentor['department']
                        ?? 'Department not specified'
                    );
                    ?>

                </p>


                <p>

                    <i class='bx bx-award'></i>

                    <?php
                    echo htmlspecialchars(
                        $assignedMentor['qualification']
                        ?? 'Qualification not specified'
                    );
                    ?>

                </p>


            </div>


        </div>


    <?php } else { ?>


        <div class="empty-dashboard-state">

            <div class="empty-icon">

                <i class='bx bx-user-plus'></i>

            </div>


            <h3>
                No Mentor Assigned Yet
            </h3>


            <p>

                Select a mentor to begin
                your mentoring journey.

            </p>


            <a
                href="index.php#mentors"
                class="dashboard-btn"
            >

                Find a Mentor

                <i class='bx bx-right-arrow-alt'></i>

            </a>

        </div>


    <?php } ?>


</div>



<!-- ==========================
     QUICK ACTIONS
========================== -->

<div class="dashboard-panel quick-actions-panel">


    <div class="panel-heading">

        <div>

            <h2>
                Quick Actions
            </h2>

            <p>
                Frequently used student tools
            </p>

        </div>


        <div class="heading-icon">

            <i class='bx bx-bolt-circle'></i>

        </div>

    </div>



    <div class="quick-actions-grid">


        <a
            href="student_profile.php"
            class="quick-action-card"
        >

            <div class="action-icon">

                <i class='bx bx-user'></i>

            </div>


            <div>

                <h3>
                    My Profile
                </h3>

                <p>
                    Update your information
                </p>

            </div>


            <i class='bx bx-chevron-right action-arrow'></i>

        </a>



        <a
            href="book_appointment.php"
            class="quick-action-card"
        >

            <div class="action-icon">

                <i class='bx bx-calendar-plus'></i>

            </div>


            <div>

                <h3>
                    Book Appointment
                </h3>

                <p>
                    Schedule a mentor session
                </p>

            </div>


            <i class='bx bx-chevron-right action-arrow'></i>

        </a>



        <a
            href="student_profile.php"
            class="quick-action-card"
        >

            <div class="action-icon">

                <i class='bx bx-line-chart'></i>

            </div>


            <div>

                <h3>
                    Submit GPA
                </h3>

                <p>
                    Update academic progress
                </p>

            </div>


            <i class='bx bx-chevron-right action-arrow'></i>

        </a>


    </div>


</div>


</section>



<!-- ==========================
     GPA + MOTIVATION
========================== -->

<section class="dashboard-secondary-grid">


<!-- GPA -->

<div class="dashboard-panel academic-panel">


    <div class="panel-heading">

        <div>

            <h2>
                Academic Progress
            </h2>

            <p>
                Your latest GPA submission
            </p>

        </div>


        <div class="heading-icon">

            <i class='bx bx-bar-chart-alt-2'></i>

        </div>

    </div>



    <?php if ($latestGpa) { ?>


        <div class="gpa-display">


            <div class="gpa-circle">

                <span>
                    GPA
                </span>


                <strong>

                    <?php
                    echo number_format(
                        $latestGpa['gpa'],
                        2
                    );
                    ?>

                </strong>


                <small>
                    / 4.00
                </small>

            </div>



            <div class="gpa-details">


                <span>
                    Latest Submission
                </span>


                <h3>

                    Semester

                    <?php
                    echo htmlspecialchars(
                        $latestGpa['semester']
                    );
                    ?>

                </h3>


                <p>

                    Keep tracking your academic
                    performance and share progress
                    with your mentor.

                </p>


            </div>


        </div>


    <?php } else { ?>


        <div class="empty-dashboard-state small-state">

            <i class='bx bx-line-chart'></i>


            <h3>
                No GPA Reports Yet
            </h3>


            <p>
                Submit your first GPA report
                from your profile.
            </p>


            <a
                href="student_profile.php"
                class="dashboard-btn"
            >

                Submit GPA

            </a>

        </div>


    <?php } ?>


</div>



<!-- MOTIVATION -->

<div class="motivation-card">


    <div class="quote-decoration">

        <i class='bx bxs-quote-alt-left'></i>

    </div>


    <span>
        Today's Motivation
    </span>


    <p id="daily-quote">

        Success is the sum of small
        efforts repeated daily.

    </p>


    <div class="motivation-bottom">

        <i class='bx bx-sun'></i>

        <small>
            Keep moving forward
        </small>

    </div>


</div>


</section>



<!-- ==========================
     UPCOMING APPOINTMENTS
========================== -->

<section class="dashboard-panel appointments-panel">


    <div class="panel-heading">

        <div>

            <h2>
                Upcoming Appointments
            </h2>

            <p>
                Your scheduled mentoring sessions
            </p>

        </div>


        <a
            href="book_appointment.php"
            class="view-all-link"
        >

            View Appointments

            <i class='bx bx-right-arrow-alt'></i>

        </a>

    </div>



    <div class="appointment-list">


        <?php if (
            $appointments->num_rows > 0
        ) { ?>


            <?php while (
                $appointment =
                $appointments->fetch_assoc()
            ) { ?>


                <div class="appointment-card">


                    <div class="appointment-date">


                        <span>

                            <?php

                            echo date(
                                "M",
                                strtotime(
                                    $appointment[
                                        'appointment_date'
                                    ]
                                )
                            );

                            ?>

                        </span>


                        <strong>

                            <?php

                            echo date(
                                "d",
                                strtotime(
                                    $appointment[
                                        'appointment_date'
                                    ]
                                )
                            );

                            ?>

                        </strong>


                    </div>



                    <div class="appointment-details">


                        <h3>

                            <?php
                            echo htmlspecialchars(
                                $appointment['mentor_name']
                            );
                            ?>

                        </h3>


                        <p>

                            <i class='bx bx-time'></i>

                            <?php

                            echo htmlspecialchars(
                                $appointment[
                                    'appointment_time'
                                ]
                            );

                            ?>

                        </p>


                    </div>



                    <span class="appointment-status">

                        <?php
                        echo htmlspecialchars(
                            $appointment['status']
                        );
                        ?>

                    </span>


                </div>


            <?php } ?>


        <?php } else { ?>


            <div class="empty-dashboard-state">

                <i class='bx bx-calendar-x'></i>


                <h3>
                    No Upcoming Appointments
                </h3>


                <p>

                    Book a mentoring session
                    with your assigned mentor.

                </p>


                <a
                    href="book_appointment.php"
                    class="dashboard-btn"
                >

                    Book Appointment

                </a>

            </div>


        <?php } ?>


    </div>


</section>


</main>



<?php include "Includes/footer.php"; ?>



<script>


/* ==========================
   DAILY QUOTES
========================== */

const quotes = [

    "Success is the sum of small efforts repeated daily.",

    "Believe in yourself and all that you are.",

    "Every day is a new opportunity to grow.",

    "Your future is created by what you do today.",

    "Small progress is still progress."

];


const today =
new Date().getDate();


document
.getElementById("daily-quote")
.innerText =
quotes[today % quotes.length];



/* ==========================
   COUNTER ANIMATION
========================== */

function animateValue(
    id,
    start,
    end,
    duration
){

    const element =
    document.getElementById(id);


    if(!element){
        return;
    }


    if(end === 0){

        element.innerText = 0;

        return;

    }


    let startTime = null;


    function animation(
        currentTime
    ){

        if(!startTime){

            startTime =
            currentTime;

        }


        const progress =
        Math.min(

            (
                currentTime -
                startTime
            ) / duration,

            1

        );


        element.innerText =
        Math.floor(
            progress *
            (end - start) +
            start
        );


        if(progress < 1){

            requestAnimationFrame(
                animation
            );

        }

    }


    requestAnimationFrame(
        animation
    );

}


animateValue(
    "mentor-count",
    0,
    <?php echo $mentorCount; ?>,
    800
);


animateValue(
    "gpa-count",
    0,
    <?php echo $gpaCount; ?>,
    1000
);


animateValue(
    "appointment-count",
    0,
    <?php echo $appointmentCount; ?>,
    1200
);


</script>


</body>

</html>