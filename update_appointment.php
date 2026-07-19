<?php

session_start();

include "Includes/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $appointment_id = $_POST['appointment_id'];
    $status = $_POST['status'];

    /* ============================
       UPDATE APPOINTMENT STATUS
    ============================ */

    $stmt = $conn->prepare(

        "UPDATE appointments
        SET status = ?
        WHERE id = ?"

    );

    $stmt->bind_param(
        "si",
        $status,
        $appointment_id
    );

    if ($stmt->execute()) {

        /* ============================
           GET STUDENT ID
        ============================ */

        $studentQuery = $conn->prepare(

            "SELECT student_id
             FROM appointments
             WHERE id = ?"

        );

        $studentQuery->bind_param(
            "i",
            $appointment_id
        );

        $studentQuery->execute();

        $student = $studentQuery->get_result()->fetch_assoc();

        $student_id = $student['student_id'];

        /* ============================
           CREATE NOTIFICATION MESSAGE
        ============================ */

        if ($status == "Approved") {

            $message = "Your appointment request has been approved.";

        } else {

            $message = "Your appointment request has been rejected.";

        }

        /* ============================
           SAVE NOTIFICATION
        ============================ */

        $type = "appointment";
        $link = "book_appointment.php";

        $notify = $conn->prepare(

            "INSERT INTO system_notifications
            (
                user_id,
                type,
                message,
                related_id,
                notification_link,
                is_read,
                created_at
            )

            VALUES (?, ?, ?, ?, ?, 0, NOW())"

        );

        $notify->bind_param(

            "issis",

            $student_id,
            $type,
            $message,
            $appointment_id,
            $link

        );

        $notify->execute();

        header("Location: mentor_appointment.php");
        exit();

    } else {

        echo "Failed to update.";

    }

}

?>