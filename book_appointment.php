<?php

session_start();

include "Includes/db.php";
include "Includes/student_header.php";

$student_id = $_SESSION['user_id'];

// fetch appointment history

$appointmentHistory = $conn->prepare(

"SELECT appointments.*,
users.usersName

FROM appointments

JOIN users
ON appointments.mentor_id = users.usersId

WHERE appointments.student_id=?

ORDER BY appointment_date DESC"

);

$appointmentHistory->bind_param(
"i",
$student_id
);

$appointmentHistory->execute();

$appointmentResults =
$appointmentHistory->get_result();


// book appointment

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $mentor_id = $_POST['mentor_id'];

    $appointment_date =
    $_POST['appointment_date'];

    $appointment_time =
    $_POST['appointment_time'];

    $message =
    $_POST['message'];

    $stmt = $conn->prepare(

    "INSERT INTO appointments
    (student_id, mentor_id,
    appointment_date,
    appointment_time,
    message)

    VALUES (?, ?, ?, ?, ?)"

    );

    $stmt->bind_param(

    "iisss",

    $student_id,
    $mentor_id,
    $appointment_date,
    $appointment_time,
    $message

    );

    if($stmt->execute()){

    if($stmt->execute()){

    // mentor notification

    $notifyMessage =
    "A new appointment has been booked.";

    $notify = $conn->prepare(

    "INSERT INTO mentor_notifications
    (mentor_id, message)

    VALUES (?, ?)"

    );

    $notify->bind_param(
    "is",
    $mentor_id,
    $notifyMessage
    );

    $notify->execute();

    header(
    "Location: book_appointment.php"
    );

    exit();

}else{

    echo "Failed to book appointment.";
}

        header(
        "Location: book_appointment.php"
        );

        exit();

    }else{

        echo "Failed to book appointment.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
    content="width=device-width, initial-scale=1.0">

    <title>Book Appointment</title>
    <link rel="stylesheet"href="student_dashbord.css">
     <link rel="stylesheet" href="student_dashbord.css">

<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css'rel='stylesheet'>
    <link rel="stylesheet" href="book_appointment.css">

</head>

<body>

<section class="appointment-section">

    <div class="appointment-container">

<form action="book_appointment.php" method="POST">

    <div class="appointment-card">

        <h2>Book Appointment</h2>

        <!-- Mentor -->

        <select
        name="mentor_id"
        class="appointment-input"
        required>

            <option value="">
                Select Mentor
            </option>

            <?php

            $mentors2 = $conn->query(
            "SELECT usersId, usersName
            FROM users
            WHERE role='mentor'"
            );

            while($mentor2 =
            $mentors2->fetch_assoc()){

            ?>

            <option value="<?php echo $mentor2['usersId']; ?>">

                <?php
                echo $mentor2['usersName'];
                ?>

            </option>

            <?php } ?>

        </select>

        <!-- Date -->

        <input
        type="date"
        name="appointment_date"
        class="appointment-input"
        required>

        <!-- Time -->

        <input
        type="time"
        name="appointment_time"
        class="appointment-input"
        required>

        <!-- Message -->

        <textarea
        name="message"
        placeholder="Reason for appointment..."
        class="appointment-textarea"></textarea>

        <!-- Submit -->

        <button type="submit">

            Book Appointment

        </button>

    </div>

</form>

<section class="student-appointments">

    <h2>My Appointments</h2>

    <div class="appointments-grid">

<?php

if($appointmentResults->num_rows > 0){

    while($appointment =
    $appointmentResults->fetch_assoc()){

?>

<div class="appointment-history-card">

    <h3>

        Mentor:
        <?php
        echo $appointment['usersName'];
        ?>

    </h3>

    <p>

        Date:
        <?php
        echo $appointment['appointment_date'];
        ?>

    </p>

    <p>

        Time:
        <?php
        echo $appointment['appointment_time'];
        ?>

    </p>

    <p>

        <?php
        echo $appointment['message'];
        ?>

    </p>

    <span class="status-badge

    <?php

    if($appointment['status'] == 'Approved'){

        echo 'approved';

    }else if($appointment['status'] == 'Rejected'){

        echo 'rejected';

    }else{

        echo 'pending';
    }

    ?>

    ">

    <?php
    echo $appointment['status'];
    ?>

    </span>

</div>

<?php

    }

}else{

    echo "

    <h3>
    No appointments booked yet.
    </h3>

    ";

}

?>

    </div>

</section>

    </div>

</section>
<script>

let subMenu = document.getElementById("subMenu");

function toggleMenu(){

    subMenu.classList.toggle("open-menu");

}

</script>

</body>

</html>