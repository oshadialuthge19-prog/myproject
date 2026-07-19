<?php

session_start();

include "Includes/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $student_id = $_SESSION['user_id'];
    $mentor_id = $_POST['mentor_id'];
    $semester = $_POST['semester'];
    $gpa = $_POST['gpa'];

    if (empty($gpa)) {
        die("Please calculate GPA first.");
    }

    /* ============================
       SAVE GPA
    ============================ */

    $stmt = $conn->prepare(

        "INSERT INTO gpa_submissions
        (student_id, mentor_id, semester, gpa)

        VALUES (?, ?, ?, ?)"

    );

    $stmt->bind_param(
        "iisd",
        $student_id,
        $mentor_id,
        $semester,
        $gpa
    );

    if ($stmt->execute()) {

        // Get GPA submission ID
        $submission_id = $conn->insert_id;

        /* ============================
           CREATE MENTOR NOTIFICATION
        ============================ */

        $message = "A student submitted a GPA report.";

        $type = "gpa";

        $notification_link = "mentor_dashboard.php";

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
            $mentor_id,
            $type,
            $message,
            $submission_id,
            $notification_link
        );

        $notify->execute();

        // Redirect after notification is created
        header("Location: student_dashbord.php");
        exit();

    } else {

        echo "Failed to submit GPA.";

    }

}

?>