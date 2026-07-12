<?php

session_start();

include "Includes/db.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $appointment_id =
    $_POST['appointment_id'];

    $status =
    $_POST['status'];

    // UPDATE APPOINTMENT STATUS

    $stmt = $conn->prepare(

    "UPDATE appointments

    SET status=?

    WHERE id=?"

    );

    $stmt->bind_param(

    "si",

    $status,
    $appointment_id

    );

    if($stmt->execute()){

        // GET STUDENT ID

        $studentQuery = $conn->prepare(

        "SELECT student_id
        FROM appointments
        WHERE id=?"

        );

        $studentQuery->bind_param(
        "i",
        $appointment_id
        );

        $studentQuery->execute();

        $studentResult =
        $studentQuery->get_result();

        $student =
        $studentResult->fetch_assoc();

        $student_id =
        $student['student_id'];

        // NOTIFICATION MESSAGE

        if($status == "Approved"){

            $message =
            "Your appointment has been APPROVED.";

        }else{

            $message =
            "Your appointment has been REJECTED.";
        }

        // INSERT NOTIFICATION

        $notify = $conn->prepare(

        "INSERT INTO notifications

        (student_id, message)

        VALUES (?, ?)"

        );

        $notify->bind_param(

        "is",

        $student_id,
        $message

        );

        $notify->execute();

        // REDIRECT

        header(
        "Location: mentor_appointment.php"
        );

        exit();

    }else{

        echo "Failed to update.";
    }
}
?>