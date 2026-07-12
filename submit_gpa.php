<?php

session_start();

include "Includes/db.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $student_id = $_SESSION['user_id'];

    $mentor_id = $_POST['mentor_id'];

    $semester = $_POST['semester'];

    $gpa = $_POST['gpa'];

    if(empty($gpa)){
    die("Please calculate GPA first.");
}

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

    if($stmt->execute()){

        header("Location: student_dashbord.php");

    }else{

        echo "Failed to submit GPA.";
    }
}

// mentor notification

$message =
"A student submitted a GPA report.";

$notify = $conn->prepare(

"INSERT INTO mentor_notifications
(mentor_id, message)

VALUES (?, ?)"

);

$notify->bind_param(
"is",
$mentor_id,
$message
);

$notify->execute();
?>