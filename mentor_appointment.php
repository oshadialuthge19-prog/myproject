<?php

session_start();

include "Includes/db.php";


/* =========================================
   MENTOR AUTHENTICATION
========================================= */

if (
    !isset($_SESSION['role']) ||
    $_SESSION['role'] !== 'mentor'
) {

    header("Location: login.php");
    exit();

}


$mentor_id = $_SESSION['user_id'];


/* =========================================
   FETCH APPOINTMENTS
========================================= */

$appointment_query = $conn->prepare("

    SELECT
        appointments.*,
        users.usersName

    FROM appointments

    JOIN users
        ON appointments.student_id = users.usersId

    WHERE appointments.mentor_id = ?

    ORDER BY
        appointments.appointment_date ASC,
        appointments.appointment_time ASC

");


$appointment_query->bind_param(
    "i",
    $mentor_id
);


$appointment_query->execute();


$appointments =
    $appointment_query->get_result();



/* =========================================
   APPOINTMENT STATISTICS
========================================= */

$statsQuery = $conn->prepare("

    SELECT

        COUNT(*) AS total,

        SUM(
            CASE
                WHEN status = 'Pending'
                THEN 1
                ELSE 0
            END
        ) AS pending,

        SUM(
            CASE
                WHEN status = 'Approved'
                THEN 1
                ELSE 0
            END
        ) AS approved,

        SUM(
            CASE
                WHEN status = 'Rejected'
                THEN 1
                ELSE 0
            END
        ) AS rejected

    FROM appointments

    WHERE mentor_id = ?

");


$statsQuery->bind_param(
    "i",
    $mentor_id
);


$statsQuery->execute();


$stats =
    $statsQuery
    ->get_result()
    ->fetch_assoc();


$totalAppointments =
    (int)($stats['total'] ?? 0);


$pendingAppointments =
    (int)($stats['pending'] ?? 0);


$approvedAppointments =
    (int)($stats['approved'] ?? 0);


$rejectedAppointments =
    (int)($stats['rejected'] ?? 0);

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
    Appointment Management
</title>


<!-- Bootstrap -->

<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
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


<!-- Mentor Dashboard / Header -->

<link
    rel="stylesheet"
    href="mentor_dashbord.css"
>

<link
    rel="stylesheet"
    href="Includes/mentor_header.css"
>


<!-- Appointment CSS -->

<link
    rel="stylesheet"
    href="mentor_appointments.css"
>


<!-- Footer -->

<link
    rel="stylesheet"
    href="Includes/footer.css"
>


</head>


<body>


<?php include "Includes/mentor_header.php"; ?>


<main class="appointment-page">


<!-- =========================================
     PAGE HERO
========================================= -->

<section class="appointment-hero">

    <div class="appointment-hero-content">

        <div class="hero-text">

            <span class="page-badge">

                <i class='bx bx-calendar-check'></i>

                Mentoring Sessions

            </span>


            <h1>

                Appointment Management

            </h1>


            <p>

                Review and manage student mentoring
                session requests from one place.

            </p>

        </div>


        <div class="hero-decoration">

            <i class='bx bx-calendar-event'></i>

        </div>

    </div>


    <!-- STATISTICS -->


    <div class="appointment-stats">


        <div class="appointment-stat-card">

            <div class="stat-icon total-icon">

                <i class='bx bx-calendar'></i>

            </div>


            <div>

                <span>
                    Total Requests
                </span>

                <h2>
                    <?php echo $totalAppointments; ?>
                </h2>

            </div>

        </div>



        <div class="appointment-stat-card">

            <div class="stat-icon pending-icon">

                <i class='bx bx-time-five'></i>

            </div>


            <div>

                <span>
                    Pending
                </span>

                <h2>
                    <?php echo $pendingAppointments; ?>
                </h2>

            </div>

        </div>



        <div class="appointment-stat-card">

            <div class="stat-icon approved-icon">

                <i class='bx bx-check-circle'></i>

            </div>


            <div>

                <span>
                    Approved
                </span>

                <h2>
                    <?php echo $approvedAppointments; ?>
                </h2>

            </div>

        </div>



        <div class="appointment-stat-card">

            <div class="stat-icon rejected-icon">

                <i class='bx bx-x-circle'></i>

            </div>


            <div>

                <span>
                    Rejected
                </span>

                <h2>
                    <?php echo $rejectedAppointments; ?>
                </h2>

            </div>

        </div>


    </div>

</section>



<!-- =========================================
     APPOINTMENT REQUESTS
========================================= -->

<section class="appointment-content">


    <div class="section-heading">


        <div>

            <span class="section-label">

                APPOINTMENT REQUESTS

            </span>


            <h2>

                Student Session Requests

            </h2>


            <p>

                Review student requests and update
                their appointment status.

            </p>

        </div>


        <div class="request-count">

            <?php echo $totalAppointments; ?>

            <?php

            echo $totalAppointments === 1
                ? "Request"
                : "Requests";

            ?>

        </div>


    </div>



    <?php if ($appointments->num_rows > 0) { ?>


        <div class="appointments-grid">


        <?php

        while (
            $appointment =
            $appointments->fetch_assoc()
        ) {

            $status =
                strtolower(
                    $appointment['status']
                );

        ?>


        <article class="appointment-card">


            <!-- CARD HEADER -->


            <div class="appointment-card-header">


                <div class="student-avatar">

                    <?php

                    echo strtoupper(
                        substr(
                            $appointment['usersName'],
                            0,
                            1
                        )
                    );

                    ?>

                </div>


                <div class="student-details">

                    <h3>

                        <?php

                        echo htmlspecialchars(
                            $appointment['usersName']
                        );

                        ?>

                    </h3>


                    <p>

                        Student Appointment Request

                    </p>

                </div>


                <span
                    class="status-badge
                    <?php echo $status; ?>"
                >

                    <span class="status-dot"></span>


                    <?php

                    echo htmlspecialchars(
                        $appointment['status']
                    );

                    ?>

                </span>


            </div>



            <!-- APPOINTMENT DETAILS -->


            <div class="appointment-details">


                <div class="detail-item">


                    <div class="detail-icon">

                        <i class='bx bx-calendar'></i>

                    </div>


                    <div>

                        <span>
                            Appointment Date
                        </span>


                        <strong>

                            <?php

                            echo date(
                                "F d, Y",
                                strtotime(
                                    $appointment[
                                        'appointment_date'
                                    ]
                                )
                            );

                            ?>

                        </strong>

                    </div>


                </div>



                <div class="detail-item">


                    <div class="detail-icon">

                        <i class='bx bx-time-five'></i>

                    </div>


                    <div>

                        <span>
                            Appointment Time
                        </span>


                        <strong>

                            <?php

                            echo date(
                                "h:i A",
                                strtotime(
                                    $appointment[
                                        'appointment_time'
                                    ]
                                )
                            );

                            ?>

                        </strong>

                    </div>


                </div>


            </div>



            <!-- MESSAGE -->


            <div class="appointment-message">


                <div class="message-heading">

                    <i class='bx bx-message-square-detail'></i>

                    <span>

                        Reason for Appointment

                    </span>

                </div>


                <p>

                    <?php

                    if (
                        !empty(
                            $appointment['message']
                        )
                    ) {

                        echo nl2br(
                            htmlspecialchars(
                                $appointment['message']
                            )
                        );

                    } else {

                        echo
                        "No appointment reason was provided.";

                    }

                    ?>

                </p>


            </div>



            <!-- ACTIONS -->


            <div class="appointment-actions">


                <form
                    action="update_appointment.php"
                    method="POST"
                    class="action-form"
                >


                    <input
                        type="hidden"
                        name="appointment_id"
                        value="<?php
                        echo (int)$appointment['id'];
                        ?>"
                    >



                    <button
                        type="submit"
                        name="status"
                        value="Approved"
                        class="approve-btn"
                    >

                        <i class='bx bx-check'></i>

                        Approve

                    </button>



                    <button
                        type="submit"
                        name="status"
                        value="Rejected"
                        class="reject-btn"
                    >

                        <i class='bx bx-x'></i>

                        Reject

                    </button>


                </form>


            </div>


        </article>


        <?php } ?>


        </div>


    <?php } else { ?>


        <!-- EMPTY STATE -->


        <div class="appointment-empty-state">


            <div class="empty-icon">

                <i class='bx bx-calendar-x'></i>

            </div>


            <h3>

                No Appointment Requests Yet

            </h3>


            <p>

                Student appointment requests will
                appear here when a mentoring session
                is booked.

            </p>


        </div>


    <?php } ?>


</section>


</main>



<?php include "Includes/footer.php"; ?>



<!-- Bootstrap JavaScript -->


<script
src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


</body>

</html>