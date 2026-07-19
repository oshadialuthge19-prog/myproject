<?php

session_start();

include "Includes/db.php";


/* ========================================
   STUDENT ACCESS ONLY
======================================== */

if (
    !isset($_SESSION['role'])
    ||
    $_SESSION['role'] !== 'student'
) {

    header("Location: login.php");

    exit();

}


$student_id = $_SESSION['user_id'];

$message = "";

$messageType = "";


/* ========================================
   BOOK APPOINTMENT
======================================== */

if ($_SERVER["REQUEST_METHOD"] === "POST") {


    $mentor_id = intval(
        $_POST['mentor_id'] ?? 0
    );


    $appointment_date = trim(
        $_POST['appointment_date'] ?? ''
    );


    $appointment_time = trim(
        $_POST['appointment_time'] ?? ''
    );


    $appointment_message = trim(
        $_POST['message'] ?? ''
    );


    /* ========================================
       VALIDATE APPOINTMENT
    ======================================== */

    if (
        $mentor_id <= 0
        ||
        empty($appointment_date)
        ||
        empty($appointment_time)
    ) {


        $message =
        "Please complete all required appointment details.";


        $messageType =
        "danger";


    } else {


        /* ========================================
           INSERT APPOINTMENT
        ======================================== */

        $stmt = $conn->prepare("

            INSERT INTO appointments
            (
                student_id,
                mentor_id,
                appointment_date,
                appointment_time,
                message
            )

            VALUES (?, ?, ?, ?, ?)

        ");


        $stmt->bind_param(

            "iisss",

            $student_id,
            $mentor_id,
            $appointment_date,
            $appointment_time,
            $appointment_message

        );


        if ($stmt->execute()) {


            /* ========================================
               GET APPOINTMENT ID
            ======================================== */

            $appointment_id =
            $conn->insert_id;


            /* ========================================
               CREATE MENTOR NOTIFICATION
            ======================================== */

            $notification_type =
            "appointment";


            $notification_message =
            "A student has booked a new appointment.";


            $notification_link =
            "mentor_appointment.php";


            $notify = $conn->prepare("

    INSERT INTO system_notifications
    (
        user_id,
        type,
        message,
        related_id,
        notification_link,
        is_read,
        created_at
    )

    VALUES (?, ?, ?, ?, ?, 0, NOW())

");


            $notify->bind_param(

                "issis",

                $mentor_id,
                $notification_type,
                $notification_message,
                $appointment_id,
                $notification_link

            );


            $notify->execute();


            /* ========================================
               SUCCESS MESSAGE
            ======================================== */

            $_SESSION['appointment_message'] =
            "Your appointment request has been submitted successfully.";


            header(
                "Location: book_appointment.php"
            );


            exit();


        } else {


            $message =
            "Unable to book your appointment. Please try again.";


            $messageType =
            "danger";


        }


    }


}


/* ========================================
   SUCCESS MESSAGE
======================================== */

if (
    isset($_SESSION['appointment_message'])
) {


    $message =
    $_SESSION['appointment_message'];


    $messageType =
    "success";


    unset(
        $_SESSION['appointment_message']
    );


}


/* ========================================
   FETCH MENTORS
======================================== */

$mentors = $conn->query("

    SELECT usersId, usersName

    FROM users

    WHERE role = 'mentor'

    ORDER BY usersName ASC

");


/* ========================================
   FETCH APPOINTMENT HISTORY
======================================== */

$appointmentHistory = $conn->prepare("

    SELECT

        appointments.*,

        users.usersName

    FROM appointments

    JOIN users

    ON appointments.mentor_id = users.usersId

    WHERE appointments.student_id = ?

    ORDER BY

        appointment_date DESC,

        appointment_time DESC

");


$appointmentHistory->bind_param(

    "i",

    $student_id

);


$appointmentHistory->execute();


$appointmentResults =

$appointmentHistory->get_result();


/* ========================================
   APPOINTMENT COUNT
======================================== */

$totalAppointments =

$appointmentResults->num_rows;

?>


<!DOCTYPE html>

<html lang="en">


<head>


<meta charset="UTF-8">


<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>


<title>
    Student Appointments
</title>



<!-- BOXICONS -->

<link
    href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
    rel="stylesheet"
>



<!-- FONT AWESOME -->

<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
>



<!-- STUDENT HEADER -->

<link
    rel="stylesheet"
    href="Includes/student_header.css"
>



<!-- APPOINTMENT CSS -->

<link
    rel="stylesheet"
    href="book_appointment.css"
>



<!-- FOOTER -->

<link
    rel="stylesheet"
    href="Includes/footer.css"
>


</head>



<body>



<?php

include "Includes/student_header.php";

?>



<main class="appointment-page">



<!-- ========================================
     APPOINTMENT HERO
======================================== -->


<section class="appointment-hero">


    <div class="appointment-hero-content">


        <span class="appointment-page-label">

            Student Appointments

        </span>



        <h1>

            Schedule a Mentoring Session

        </h1>



        <p>

            Connect with your mentor to discuss academic progress,
            receive guidance and plan your next steps.

        </p>


    </div>



    <div class="appointment-hero-decoration">


        <div class="appointment-hero-icon">

            <i class='bx bx-calendar-check'></i>

        </div>


    </div>


</section>




<!-- ========================================
     MESSAGE
======================================== -->


<?php if (!empty($message)) { ?>


<div
    class="appointment-message
    <?php echo htmlspecialchars($messageType); ?>"
>


    <div class="appointment-message-icon">


        <?php if ($messageType === "success") { ?>


            <i class='bx bx-check'></i>


        <?php } else { ?>


            <i class='bx bx-error-circle'></i>


        <?php } ?>


    </div>



    <div>


        <strong>


            <?php

            echo $messageType === "success"

            ? "Appointment Submitted"

            : "Unable to Book Appointment";

            ?>


        </strong>


        <p>

            <?php

            echo htmlspecialchars($message);

            ?>

        </p>


    </div>



    <button

        type="button"

        class="appointment-message-close"

        onclick="this.parentElement.remove()"

    >


        <i class='bx bx-x'></i>


    </button>


</div>


<?php } ?>




<!-- ========================================
     APPOINTMENT CONTENT
======================================== -->


<section class="appointment-content-grid">



<!-- ========================================
     BOOK APPOINTMENT PANEL
======================================== -->


<div class="booking-panel">



    <div class="panel-heading">


        <div>


            <span class="panel-label">

                New Request

            </span>


            <h2>

                Book an Appointment

            </h2>


            <p>

                Select your mentor and preferred meeting schedule.

            </p>


        </div>



        <div class="heading-icon">


            <i class='bx bx-calendar-plus'></i>


        </div>


    </div>




    <form

        action="book_appointment.php"

        method="POST"

        class="appointment-form"

    >



        <!-- MENTOR -->


        <div class="appointment-form-group">


            <label>


                <i class='bx bx-user-voice'></i>


                Select Mentor


            </label>



            <div class="appointment-input-wrapper">


                <i class='bx bx-user'></i>



                <select

                    name="mentor_id"

                    required

                >


                    <option value="">

                        Choose your mentor

                    </option>



                    <?php

                    while (

                        $mentor =
                        $mentors->fetch_assoc()

                    ) {

                    ?>


                    <option

                        value="<?php
                        echo intval(
                            $mentor['usersId']
                        );
                        ?>"

                    >


                        <?php

                        echo htmlspecialchars(

                            $mentor['usersName']

                        );

                        ?>


                    </option>


                    <?php } ?>


                </select>


            </div>


        </div>




        <!-- DATE AND TIME -->


        <div class="appointment-form-row">



            <div class="appointment-form-group">


                <label>


                    <i class='bx bx-calendar'></i>


                    Appointment Date


                </label>



                <div class="appointment-input-wrapper">


                    <i class='bx bx-calendar-event'></i>


                    <input

                        type="date"

                        name="appointment_date"

                        id="appointmentDate"

                        required

                    >


                </div>


            </div>




            <div class="appointment-form-group">


                <label>


                    <i class='bx bx-time-five'></i>


                    Preferred Time


                </label>



                <div class="appointment-input-wrapper">


                    <i class='bx bx-time'></i>


                    <input

                        type="time"

                        name="appointment_time"

                        required

                    >


                </div>


            </div>


        </div>




        <!-- MESSAGE -->


        <div class="appointment-form-group">


            <label>


                <i class='bx bx-message-square-detail'></i>


                Reason for Appointment


            </label>



            <textarea

                name="message"

                rows="6"

                placeholder="Briefly explain what you would like to discuss with your mentor..."

            ></textarea>



            <small class="form-help-text">


                <i class='bx bx-info-circle'></i>


                Provide a short description so your mentor can
                prepare for the session.


            </small>


        </div>




        <!-- BUTTON -->


        <button

            type="submit"

            class="book-appointment-btn"

        >


            <i class='bx bx-calendar-check'></i>


            Submit Appointment Request


        </button>


    </form>




    <!-- INFORMATION -->


    <div class="booking-information">


        <div class="booking-info-icon">


            <i class='bx bx-bulb'></i>


        </div>



        <div>


            <strong>

                Before booking

            </strong>


            <p>

                Your mentor will review your appointment request.
                The appointment status will be updated once the
                mentor approves or rejects the request.

            </p>


        </div>


    </div>


</div>




<!-- ========================================
     APPOINTMENT SUMMARY
======================================== -->


<div class="appointment-summary-panel">



    <div class="summary-header">


        <span>

            Appointment Overview

        </span>


        <div class="summary-icon">


            <i class='bx bx-calendar'></i>


        </div>


    </div>



    <div class="appointment-total">


        <span>

            Total Appointments

        </span>


        <strong>

            <?php

            echo $totalAppointments;

            ?>

        </strong>


        <p>

            Mentoring sessions you have requested

        </p>


    </div>



    <div class="appointment-process">



        <div class="process-item">


            <div class="process-number">

                1

            </div>


            <div>


                <h3>

                    Submit Request

                </h3>


                <p>

                    Choose your mentor and meeting schedule.

                </p>


            </div>


        </div>




        <div class="process-line"></div>




        <div class="process-item">


            <div class="process-number">

                2

            </div>


            <div>


                <h3>

                    Mentor Review

                </h3>


                <p>

                    Your mentor reviews the appointment request.

                </p>


            </div>


        </div>




        <div class="process-line"></div>




        <div class="process-item">


            <div class="process-number">

                3

            </div>


            <div>


                <h3>

                    Appointment Update

                </h3>


                <p>

                    Check the status of your mentoring session.

                </p>


            </div>


        </div>


    </div>


</div>


</section>




<!-- ========================================
     APPOINTMENT HISTORY
======================================== -->


<section class="appointment-history-panel">



    <div class="history-heading">


        <div>


            <span class="panel-label">

                Session History

            </span>


            <h2>

                My Appointments

            </h2>


            <p>

                Review your mentoring appointment requests and status.

            </p>


        </div>



        <div class="history-count">


            <i class='bx bx-calendar'></i>


            <?php

            echo $totalAppointments;

            ?>


            Appointments


        </div>


    </div>




    <div class="appointment-history-list">



<?php


if (

    $appointmentResults->num_rows > 0

) {



    while (

        $appointment =
        $appointmentResults->fetch_assoc()

    ) {



        $appointmentDate =

        strtotime(

            $appointment['appointment_date']

        );



        $month =

        strtoupper(

            date(

                "M",

                $appointmentDate

            )

        );



        $day =

        date(

            "d",

            $appointmentDate

        );



        $formattedTime =

        date(

            "h:i A",

            strtotime(

                $appointment['appointment_time']

            )

        );



        $status =

        $appointment['status'] ?? 'Pending';



        $statusClass =

        strtolower(

            $status

        );


?>



<div class="history-appointment-card">



    <div class="history-date">


        <span>

            <?php

            echo htmlspecialchars($month);

            ?>

        </span>


        <strong>

            <?php

            echo htmlspecialchars($day);

            ?>

        </strong>


    </div>




    <div class="history-appointment-information">



        <div class="history-mentor">


            <div class="mentor-small-icon">


                <i class='bx bx-user'></i>


            </div>



            <div>


                <span>

                    Mentoring Session

                </span>


                <h3>


                    <?php

                    echo htmlspecialchars(

                        $appointment['usersName']

                    );

                    ?>


                </h3>


            </div>


        </div>




        <div class="appointment-meta">



            <span>


                <i class='bx bx-time-five'></i>


                <?php

                echo htmlspecialchars(

                    $formattedTime

                );

                ?>


            </span>




            <?php if (

                !empty(

                    $appointment['message']

                )

            ) { ?>


            <span>


                <i class='bx bx-message-detail'></i>


                <?php

                echo htmlspecialchars(

                    $appointment['message']

                );

                ?>


            </span>


            <?php } ?>


        </div>


    </div>




    <div

        class="professional-status-badge

        <?php

        echo htmlspecialchars(

            $statusClass

        );

        ?>"

    >


        <span class="status-dot"></span>


        <?php

        echo htmlspecialchars(

            $status

        );

        ?>


    </div>


</div>



<?php


    }


} else {


?>



<div class="empty-appointment-state">



    <div class="empty-appointment-icon">


        <i class='bx bx-calendar-x'></i>


    </div>



    <h3>

        No Appointments Yet

    </h3>



    <p>

        You haven't booked any mentoring sessions.
        Use the appointment form above to schedule
        your first session.

    </p>


</div>



<?php


}


?>



    </div>


</section>



</main>



<?php

include "Includes/footer.php";

?>



<script>


/* ========================================
   PREVENT PAST DATES
======================================== */


const appointmentDate =

document.getElementById(

    "appointmentDate"

);


if (appointmentDate) {


    const today =

    new Date();


    const year =

    today.getFullYear();


    const month =

    String(

        today.getMonth() + 1

    ).padStart(

        2,

        "0"

    );


    const day =

    String(

        today.getDate()

    ).padStart(

        2,

        "0"

    );


    appointmentDate.min =

    `${year}-${month}-${day}`;

}


</script>



</body>


</html>